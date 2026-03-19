<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Store;
use App\Models\Product;
use App\Models\Order;
use App\Models\Transaction;
use App\Enum\TransactionType; 
use App\Mail\OrderConfirmed;
use Illuminate\Support\Facades\Mail;
use App\Events\OrderReadyForPickup;
use App\Events\NewPendingOrder;
use App\Models\Participant;

class CartController extends Controller
{
    public function index()
    {
        $participant = Auth::user()->participant;
        if (!$participant) abort(403);
        return view('participant.cart.index', compact('participant'));
    }

  
public function checkout(Request $request)
    {
        $request->validate([
            'store_id'    => 'required|exists:stores,id',
            'items'       => 'required|array|min:1',
            'items.*.id'  => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        
        $participantSession = Auth::user()->participant;
        $store = Store::findOrFail($request->store_id);

        if ($store->event_id !== $participantSession->event_id) {
            return response()->json(['message' => 'Boutique invalide.'], 403);
        }
        
        if (!$store->isActive()) {
            return response()->json(['message' => 'Cette boutique est actuellement fermée.'], 403);
        }

        if (!$store->event->isActive()) {
            return response()->json(['message' => 'Cet événement est terminé ou fermé.'], 403);
        }
      
        try {
            DB::beginTransaction();

           
            $participant = Participant::where('id', $participantSession->id)
                                      ->lockForUpdate()
                                      ->first();

            $totalAmount = 0;
            $orderItemsData = [];
            $productsToUpdate = [];

            $sortedItems = collect($request->items)->sortBy('id')->values()->all();

            // Boucle sur les produits avec vérification des STOCKS
            foreach ($sortedItems as $item) {
                
                $product = Product::where('id', $item['id'])
                    ->where('store_id', $store->id)
                    ->where('is_active', true)
                    ->lockForUpdate()
                    ->first();

                if (!$product) {
                    throw new \Exception("Un produit n'est plus disponible.");
                }

               // on ajoute dans la tble des prod a modif les prod stockable
                if ($product->is_stockable) {
                    if ($product->quantity < $item['qty']) {
                        throw new \Exception("Stock insuffisant pour l'article : {$product->name}. (Restant: {$product->quantity})");
                    }
                    
                  
                    $productsToUpdate[] = [
                        'product' => $product,
                        'qty_to_deduct' => $item['qty']
                    ];
                }

                $subtotal = $product->unit_price * $item['qty'];
                $totalAmount += $subtotal;

                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'quantity'   => $item['qty'],
                    'unit_price' => $product->unit_price,
                ];
            }

          
            if ($participant->available_balance < $totalAmount) {
                throw new \Exception("Solde disponible insuffisant pour cette commande.");
            }

           
            $participant->reserved_balance += $totalAmount;
            $participant->save();
            
            $orderStatus = $store->isQueue() ? 'pending' : 'ready';

            $order = Order::create([
                'participant_id' => $participant->id,
                'store_id'       => $store->id,
                'total_points'   => $totalAmount,
                'status'         => $orderStatus
            ]);

      
            foreach ($orderItemsData as $data) {
                $order->items()->create($data);
            }
            
           
            foreach ($productsToUpdate as $update) {
                $update['product']->decrement('quantity', $update['qty_to_deduct']);
            }

          

            DB::commit();

            $order->load(['store', 'items.product']);
            
            if ($store->isDirect()) {
               
                OrderReadyForPickup::dispatch($order);
            } elseif ($store->isQueue()) {
                
                NewPendingOrder::dispatch($order);
            }
         
            $user = $participant->user; 
            
            // mail          
  /* if ($user && $user->email) {
                Mail::to($user->email)->queue(new OrderConfirmed($order));            
            } */

            return response()->json([
                'message' => 'Commande validée avec succès',
                'order_id' => $order->id
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}