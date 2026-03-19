<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\OrderItem;
use App\Models\Order;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Enum\OrderStatus;

class ParticipantDashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $participant = Auth::user()->participant;

        if (!$participant) {
            abort(403, 'Profil participant introuvable.');
        }

        $dateRange = $request->input('date_range');

        // Define start and end date based on Flatpickr input string
        if ($dateRange && str_contains($dateRange, ' au ')) {
            [$start, $end] = explode(' au ', $dateRange);
            $startDate = Carbon::parse($start)->startOfDay();
            $endDate = Carbon::parse($end)->endOfDay();
        } elseif ($dateRange && str_contains($dateRange, ' to ')) {
            [$start, $end] = explode(' to ', $dateRange);
            $startDate = Carbon::parse($start)->startOfDay();
            $endDate = Carbon::parse($end)->endOfDay();
        } elseif ($dateRange) {
            $startDate = Carbon::parse($dateRange)->startOfDay();
            $endDate = Carbon::parse($dateRange)->endOfDay();
        } else {
            $startDate = Carbon::today();
            $endDate = Carbon::today()->endOfDay();
        }

        // Get completed orders for the current participant within the date range
        $completedOrders = $participant->orders()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', OrderStatus::Completed);

        // KPIs
        $currentBalance = $participant->balance;
        $totalSpent = $completedOrders->sum('total_points');
        $ordersCount = $completedOrders->count();

        $itemsPurchased = OrderItem::whereHas('order', function ($q) use ($participant, $startDate, $endDate) {
            $q->where('participant_id', $participant->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', OrderStatus::Completed);
        })->sum('quantity');

        // Preparing data for the line chart (spending over time)
        $diffInDays = $startDate->diffInDays($endDate);
        $chartLabels = [];
        $chartData = [];
        $chartTitle = "";

        if ($diffInDays < 1) {
            $spentRaw = $completedOrders->clone()
                ->selectRaw('HOUR(created_at) as label, SUM(total_points) as total')
                ->groupBy('label')
                ->pluck('total', 'label')
                ->toArray();

            for ($i = 0; $i < 24; $i++) {
                $chartLabels[] = $i . 'h';
                $chartData[] = $spentRaw[$i] ?? 0;
            }
            $chartTitle = "Dépenses (Par Heure)";
        } else {
            $spentRaw = $completedOrders->clone()
                ->selectRaw('DATE(created_at) as label, SUM(total_points) as total')
                ->groupBy('label')
                ->pluck('total', 'label')
                ->toArray();

            $period = CarbonPeriod::create($startDate, $endDate);
            foreach ($period as $date) {
                $dateString = $date->format('Y-m-d');
                $chartLabels[] = $date->format('d/m');
                $chartData[] = $spentRaw[$dateString] ?? 0;
            }
            $chartTitle = "Dépenses (Par Jour)";
        }

        // Top 5 Products purchased by this participant
        $topProductsRaw = OrderItem::whereHas('order', function ($q) use ($participant, $startDate, $endDate) {
            $q->where('participant_id', $participant->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', OrderStatus::Completed);
        })
            ->selectRaw('product_id, SUM(quantity) as total_quantity')
            ->groupBy('product_id')
            ->with('product')
            ->orderByDesc('total_quantity')
            ->take(5)
            ->get();

        $topProductsNames = $topProductsRaw->map(fn($item) => $item->product->name ?? 'Produit Inconnu')->toArray();
        $topProductsQuantities = $topProductsRaw->map(fn($item) => $item->total_quantity)->toArray();

        return view('participant.dashboard', compact(
            'participant',
            'startDate',
            'endDate',
            'dateRange',
            'currentBalance',
            'totalSpent',
            'ordersCount',
            'itemsPurchased',
            'chartLabels',
            'chartData',
            'chartTitle',
            'topProductsNames',
            'topProductsQuantities'
        ));
    }
}
