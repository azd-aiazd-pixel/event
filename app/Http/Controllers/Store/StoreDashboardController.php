<?php
namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Gate;
use App\Enum\OrderStatus;
class StoreDashboardController extends Controller
{
   //la page d acceuil avec les differents store ducompte
    public function index()
    {
      
        $stores = Auth::user()->store()->with('event')->get();

        return view('store.index', compact('stores'));
    }





public function dashboard(Request $request, Store $store) 
    {
        Gate::authorize('view', $store);

        $dateRange = $request->input('date_range');
        
        //a modif plustard
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

      
        $completedOrders = $store->orders()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', OrderStatus::Completed);
            
    
        $revenue = $completedOrders->sum('total_points');
        $ordersCount = $completedOrders->count();
        

        $averageOrderValue = $ordersCount > 0 ? ($revenue / $ordersCount) : 0;
        
        $itemsSold = OrderItem::whereHas('order', function($q) use ($store, $startDate, $endDate) {
            $q->where('store_id', $store->id)
              ->whereBetween('created_at', [$startDate, $endDate])
              ->where('status', OrderStatus::Completed);
        })->sum('quantity');

       



        $diffInDays = $startDate->diffInDays($endDate);
        $chartLabels = [];
        $chartData = [];
        $chartTitle = "";

        if ($diffInDays < 1) {
           
            $salesRaw = $completedOrders->clone()
                ->selectRaw('HOUR(created_at) as label, SUM(total_points) as total')
                ->groupBy('label')
                ->pluck('total', 'label')
                ->toArray();
                
            for ($i = 0; $i < 24; $i++) {
                $chartLabels[] = $i . 'h';
                $chartData[] = $salesRaw[$i] ?? 0;
            }
            $chartTitle = "Évolution (Par Heure)";
        } else {
          
            $salesRaw = $completedOrders->clone()
                ->selectRaw('DATE(created_at) as label, SUM(total_points) as total')
                ->groupBy('label')
                ->pluck('total', 'label')
                ->toArray();

            $period = CarbonPeriod::create($startDate, $endDate);
            foreach ($period as $date) {
                $dateString = $date->format('Y-m-d');
                $chartLabels[] = $date->format('d/m'); 
                $chartData[] = $salesRaw[$dateString] ?? 0;
            }
            $chartTitle = "Évolution (Par Jour)";
        }

       

        
        $topProductsRaw = OrderItem::whereHas('order', function($q) use ($store, $startDate, $endDate) {
                $q->where('store_id', $store->id)
                  ->whereBetween('created_at', [$startDate, $endDate])
                  ->where('status', OrderStatus::Completed);
            })
            ->selectRaw('product_id, SUM(quantity) as total_quantity')
            ->groupBy('product_id')
            ->with('product') 
            ->orderByDesc('total_quantity')
            ->take(5)
            ->get();

        $topProductsNames = $topProductsRaw->map(fn($item) => $item->product->name)->toArray();
        $topProductsQuantities = $topProductsRaw->map(fn($item) => $item->total_quantity)->toArray();

        return view('store.dashboard', compact(
            'store', 'startDate', 'endDate', 'dateRange',
            'revenue', 'ordersCount', 'averageOrderValue', 'itemsSold',
            'chartLabels', 'chartData', 'chartTitle',
            'topProductsNames', 'topProductsQuantities'
        ));
    }


}