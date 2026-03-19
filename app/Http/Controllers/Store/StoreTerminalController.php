<?php
namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\{Store, Participant, Product, Order, OrderItem, Transaction,Category};
use App\Enum\TransactionType;
use App\Enum\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\NewPendingOrder;
use Illuminate\Support\Facades\Gate;
class StoreTerminalController extends Controller
{


public function index(Store $store)
    {
        Gate::authorize('view', $store);
        $products = Product::where('store_id', $store->id)
            ->where('is_active', true)
            ->with(['category', 'unitMeasure'])
            ->get();

        $categories = Category::whereHas('products', function($q) use ($store) {
            $q->where('store_id', $store->id);
        })->get();

       $readyOrders = Order::where('store_id', $store->id)
            ->where('status', OrderStatus::Ready)
            ->with('items.product')
            ->latest()
            ->get()
            ->groupBy('participant_id');

        return view('store.terminal.index', compact('store', 'products', 'categories', 'readyOrders'));
    }

  public function processPayment(Request $request, Store $store)
    {
        Gate::authorize('view', $store);

        $request->validate([
            'nfc_code' => 'required|string',
            'cart' => 'required|array|min:1',
            'cart.*.id' => 'required|integer|exists:products,id',
            'cart.*.qty' => 'required|integer|min:1',
        ]);

        if (!$store->isActive()) {
            return response()->json(['error' => 'Terminal inactif.'], 403);
        }
        if (!$store->event->isActive()) {
    return response()->json(['error' => 'Cet événement est terminé ou inactif.'], 403);
}

        try {
           
            $createdOrder = DB::transaction(function () use ($request, $store) {
                
                // Lock du Participant
                $participant = Participant::where('nfc_code', $request->nfc_code)
                    ->where('event_id', $store->event_id)
                    ->lockForUpdate()
                    ->first();

                if (!$participant) {
                    throw new \Exception("Bracelet non reconnu.");
                }

               // on tri  les produit pour les lock et evite deadlock
                $productIds = collect($request->cart)
                            ->pluck('id')
                            ->unique()
                            ->sort()
                            ->values() 
                            ->toArray();
 
                $products = Product::whereIn('id', $productIds)
                    ->where('store_id', $store->id)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                $totalOrder = 0;
                $itemsToCreate = [];

                //  Vérification Stock et  Calcul Total de l ordre et prepaaration des orderitem
                foreach ($request->cart as $item) {
                    $product = $products->get($item['id']);

                    if (!$product || !$product->is_active) {
                        throw new \Exception("Un des produits n'est plus disponible.");
                    }

                 if ($product->is_stockable && $product->quantity < $item['qty']) {
                        throw new \Exception("Stock insuffisant pour {$product->name}.");
                    }

                    $totalOrder += $product->unit_price * $item['qty'];
                    $itemsToCreate[] = [
                        'product' => $product,
                        'qty' => $item['qty'],
                        'price' => $product->unit_price
                    ];
                }

                if ($participant->available_balance < $totalOrder) {
                        throw new \Exception("Solde insuffisant.");
                    }

                $isQueue = ($store->workflow_type === 'queue');


                if ($isQueue) {
                   
                    $participant->increment('reserved_balance', $totalOrder);
                } else {
                    $participant->decrement('balance', $totalOrder);
                }

                $order = Order::create([
                    'store_id' => $store->id,
                    'participant_id' => $participant->id,
                    'total_points' => $totalOrder,
                    'status' => $isQueue ? OrderStatus::Pending : OrderStatus::Completed,
                ]);

                foreach ($itemsToCreate as $data) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $data['product']->id,
                        'quantity' => $data['qty'],
                        'unit_price' => $data['price'],
                    ]);
                    if ($data['product']->is_stockable) {
                        $data['product']->decrement('quantity', $data['qty']);
                    }
                }

                if (!$isQueue) {
                    Transaction::create([
                        'participant_id' => $participant->id,
                        'order_id' => $order->id,
                        'event_id' => $store->event_id,
                        'amount' => $totalOrder,
                        'type' => TransactionType::Payment,
                    ]);
                    }

                return $order; 
            });

            
            if ($store->isQueue()) {
                $createdOrder->load('items.product');
                NewPendingOrder::dispatch($createdOrder);
            }

         
            return response()->json([
                'success' => true,
                'message' => 'Paiement validé.',
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }


public function scanForPickup(Request $request, Store $store)
    {
        Gate::authorize('view', $store);
             if (!$store->isActive()) {
            return response()->json(['error' => 'store inactif.'], 403);
        }
        if (!$store->event->isActive()) {
    return response()->json(['error' => 'Cet événement est terminé ou inactif.'], 403);
}
        $request->validate(['nfc_code' => 'required|string']);

        $participant = Participant::where('nfc_code', $request->nfc_code)
            ->where('event_id', $store->event_id)
            ->first();

        if (!$participant) {
            return response()->json(['error' => 'Bracelet non reconnu.'], 404);
        }

        // On récupère les commandes de ce participant pour CE store qui sont "ready"
        $orders = Order::where('store_id', $store->id)
            ->where('participant_id', $participant->id)
            ->where('status', OrderStatus::Ready)
            ->with('items.product')
            ->get();

        if ($orders->isEmpty()) {
            return response()->json(['error' => 'Aucune commande prête pour ce bracelet.'], 404);
        }

        return response()->json([
            'success' => true,
            'participant_id' => $participant->id,
            'orders' => $orders
        ]);
    }
public function markAsCollected(Request $request, Store $store)
    {    Gate::authorize('view', $store);

     if (!$store->isActive()) {
            return response()->json(['error' => 'store inactif.'], 403);
        }
        if (!$store->event->isActive()) {
    return response()->json(['error' => 'Cet événement est terminé ou inactif.'], 403);
}
        $request->validate([
            'order_ids' => 'required|array|min:1',
            'order_ids.*' => 'required|integer|exists:orders,id'
        ]);

        try {
            DB::transaction(function () use ($request, $store) {

              
                $orders = Order::whereIn('id', $request->order_ids)
                    ->where('store_id', $store->id)
                    ->where('status', OrderStatus::Ready)
                    ->get();

                if ($orders->isEmpty()) {
                    throw new \Exception("Aucune commande valide trouvée pour la collecte.");
                }
            $ordersByParticipant = $orders->groupBy('participant_id');

           
                foreach ($ordersByParticipant as $participantId => $participantOrders) {
                   
                    $participant = Participant::where('id', $participantId)
                                              ->lockForUpdate()
                                              ->first();

                    if (!$participant) {
                        throw new \Exception("Participant introuvable.");
                    }

                    $totalAmount = $participantOrders->sum('total_points');

                  
                    $participant->decrement('reserved_balance', $totalAmount);
       
                    $participant->decrement('balance', $totalAmount);

                    foreach ($participantOrders as $order) {
                    
                        Transaction::create([
                            'participant_id' => $participant->id,
                            'order_id'       => $order->id,
                            'event_id'       => $store->event_id,
                            'amount'         => $order->total_points,
                            'type'           => TransactionType::Payment,
                        ]);

                        $order->update(['status' => OrderStatus::Completed]);
                    }
                }
            });
           
            return response()->json([
                'success' => true, 
                'message' => 'Commandes livrées et encaissées avec succès.'
            ]);

        } catch (\Exception $e) {
          
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    
    
    
    

    
}