<?php
namespace App\Http\Controllers\Store;


use App\Http\Controllers\Controller;
use App\Models\{Store, Order, Participant};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\OrderReadyForPickup;
use App\Events\OrderCancelled;
use Illuminate\Support\Facades\Gate;
class OrderQueueController extends Controller
{
    public function index(Store $store)
    {

    Gate::authorize('view', $store);

        if ($store->workflow_type !== 'queue') {
            abort(403, 'Ce point de vente fonctionne en mode direct et n\'a pas de file d\'attente.');
        }

        $pendingOrders = Order::where('store_id', $store->id)
            ->where('status', 'pending')
            ->with('items.product') 
            ->oldest()
            ->get();

        return view('store.queue.index', compact('store', 'pendingOrders'));
    }



   public function complete(Request $request, Order $order)
    {  
      
    Gate::authorize('update', $order);
    
       $order->update(['status' => 'ready']);

       OrderReadyForPickup::dispatch($order);

       if ($request->wantsJson() || $request->ajax()) {
           return response()->json(['success' => true]);
       }
       return back()->with('success', "Commande #{$order->id} prête !");
    }


      public function cancel(Request $request, Order $order)
    {
        Gate::authorize('update', $order);

        try {
            DB::transaction(function () use ($order) {

                $freshOrder = Order::where('id', $order->id)
                    ->lockForUpdate()
                    ->first();

                if ($freshOrder->status === 'rejected') {
                    throw new \Exception('ALREADY_CANCELLED');
                }

                $participant = Participant::where('id', $freshOrder->participant_id)
                    ->lockForUpdate()
                    ->first();

                if ($participant) {
                    $participant->decrement('reserved_balance', $freshOrder->total_points);
                }

                foreach ($freshOrder->items as $item) {
                    if ($item->product && $item->product->is_stockable) {
                        $item->product->increment('quantity', $item->quantity);
                    }
                }

                $freshOrder->update(['status' => 'rejected']);
            });

            OrderCancelled::dispatch($order->fresh());

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => true]);
            }
            return back()->with('success', "Commande #{$order->id} annulée.");

        } catch (\Exception $e) {
            if ($e->getMessage() === 'ALREADY_CANCELLED') {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Cette commande est déjà annulée.'], 400);
                }
                return back()->with('error', 'Cette commande est déjà annulée.');
            }

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => "Erreur technique lors de l'annulation."], 500);
            }
            return back()->with('error', "Erreur lors de l'annulation : " . $e->getMessage());
        }
    }
}