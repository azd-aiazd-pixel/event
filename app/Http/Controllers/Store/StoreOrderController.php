<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
class StoreOrderController extends Controller
{
    



public function index(Request $request, Store $store)
    {Gate::authorize('view', $store);
    
        $query = $store->orders()->with(['items.product:id,name']);

  
        if ($request->filled('date_range')) {
            $range = str_replace(' au ', ' to ', $request->date_range);
            $dates = explode(' to ', $range);

            if (count($dates) == 2) {
                $start = Carbon::parse($dates[0])->startOfDay();
                $end = Carbon::parse($dates[1])->endOfDay();
                $query->whereBetween('created_at', [$start, $end]);
            } else {
                $query->whereDate('created_at', Carbon::parse($dates[0]));
            }
        } else {
            $query->whereDate('created_at', Carbon::today());
        }

      //query de calcule pour chaque jour
        $dailyTotals = (clone $query)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_points) as total'))
            ->groupBy('date')
            ->pluck('total', 'date'); // retourne un tableau ['2026-02-13' => 500.00, ...]

        // query de la list 
        $orders = $query->latest()->paginate(50)->withQueryString();
        
     
        $totalRevenue = $dailyTotals->sum();

        return view('store.orders.index', compact('store', 'orders', 'totalRevenue', 'dailyTotals'));
    }
}
