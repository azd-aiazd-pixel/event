<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
class OrderController extends Controller
{
    public function show(Order $order)
    {
        $participant = Auth::user()->participant;
        if (!$participant) {
                    abort(403, 'Profil participant introuvable.');
                }
        if ($order->participant_id !== $participant->id) {
            abort(403, 'Accès non autorisé à cette commande.');
        }

        $order->load(['store', 'items.product']);

        return view('participant.orders.show', compact('order', 'participant'));
    }

    public function index()
    {
        $participant = Auth::user()->participant;
if (!$participant) {
                    abort(403, 'Profil participant introuvable.');
                }
        $orders = Order::with('store')
            ->where('participant_id', $participant->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('participant.orders.index', compact('orders'));
    }
}
