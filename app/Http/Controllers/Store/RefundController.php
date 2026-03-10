<?php

namespace App\Http\Controllers\Store;

use App\Models\Order;
use App\Http\Controllers\Controller;
use App\Models\Participant;
use App\Models\Store;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Enum\TransactionType;
use Illuminate\Support\Facades\Gate;
class RefundController extends Controller
{
   public function index(Store $store)
    {
       Gate::authorize('view', $store);
        return view('store.refund.index', compact('store'));
    }


   public function search(Request $request, Store $store)
    {
        Gate::authorize('view', $store);

        $request->validate([
            'nfc_code' => 'required|string',
        ]);

       
        $participant = Participant::where('nfc_code', $request->nfc_code)
            ->where('event_id', $store->event_id)
            ->first();

        if (!$participant) {
            return response()->json(['message' => 'Bracelet non reconnu ou invalide.'], 404);
        }

       
        $orders = Order::with('items.product')
            ->where('participant_id', $participant->id)
            ->where('store_id', $store->id)
            ->where('status', 'completed')
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json([
            'orders' => $orders
        ]);
    }


    public function process(Request $request, Store $store)
    {
        Gate::authorize('view', $store);
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        try {
            DB::beginTransaction();

            $order = Order::where('id', $request->order_id)
                ->where('store_id', $store->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($order->status !== 'completed') {
                throw new \Exception("Seules les commandes complétées peuvent être remboursées.");
            }

            $participant = Participant::where('id', $order->participant_id)
                ->lockForUpdate()
                ->firstOrFail();

            $participant->balance += $order->total_points;
            $participant->save();

            $order->status = 'rejected';
            $order->save();

            Transaction::create([
                'participant_id' => $participant->id,
                'event_id'       => $participant->event_id,
                'order_id'       => $order->id,
                'amount'         => $order->total_points,
                'type'           => TransactionType::Refund,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Remboursement effectué avec succès.',
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}