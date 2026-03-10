<?php    
namespace  App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\Store;
use App\Models\Transaction;
use App\Models\Participant;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\OrderItem;
use App\Enum\TransactionType;
class AdminDashboardController extends Controller
{ //t
   public function eventDashboard(Request $request, Event $event)
    {
        $dateRange = $request->input('date_range');
        
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
            $startDate = Carbon::parse($event->start_date)->startOfDay();
            $endDate = Carbon::parse($event->end_date)->endOfDay();
        }

        $txQuery = Transaction::where('event_id', $event->id)
                              ->whereBetween('created_at', [$startDate, $endDate]);

       
        $totalTopUp = (clone $txQuery)->where('type', TransactionType::TopUp)->sum('amount');
        $globalRevenue = (clone $txQuery)->where('type', TransactionType::Payment)->sum('amount'); 
        $totalRefund = (clone $txQuery)->where('type', TransactionType::Refund)->sum('amount');
        
        $dormant = $totalTopUp - $globalRevenue - $totalRefund;

       
        $globalOrdersCount = (clone $txQuery)->where('type', TransactionType::Payment)->count();
        $globalAverageOrder = $globalOrdersCount > 0 ? ($globalRevenue / $globalOrdersCount) : 0;
        
        $activeStoresCount = Store::where('event_id', $event->id)->where('status', 'active')->count();

//graph1
        $diffInDays = $startDate->diffInDays($endDate);
        $chartLabels = [];
        $chartDataTopUp = [];
        $chartDataPayment = [];
        $chartTitle = "";

        if ($diffInDays < 1) {
          
            $transactionsRaw = (clone $txQuery)
                ->selectRaw('HOUR(created_at) as label, type, SUM(amount) as total')
                ->groupBy('label', 'type')
                ->get();
                
            for ($i = 0; $i < 24; $i++) {
                $chartLabels[] = $i . 'h';
                $chartDataTopUp[] = $transactionsRaw->where('label', $i)->where('type', TransactionType::TopUp)->first()->total ?? 0;
                $chartDataPayment[] = $transactionsRaw->where('label', $i)->where('type', TransactionType::Payment)->first()->total ?? 0;
            }
            $chartTitle = "Flux Financiers (Par Heure)";
        } else {
            
            $transactionsRaw = (clone $txQuery)
                ->selectRaw('DATE(created_at) as label, type, SUM(amount) as total')
                ->groupBy('label', 'type')
                ->get();

            $period = CarbonPeriod::create($startDate, $endDate);
            foreach ($period as $date) {
                $dateString = $date->format('Y-m-d');
                $chartLabels[] = $date->format('d/m');
                $chartDataTopUp[] = $transactionsRaw->where('label', $dateString)->where('type', TransactionType::TopUp)->first()->total ?? 0;
                $chartDataPayment[] = $transactionsRaw->where('label', $dateString)->where('type', TransactionType::Payment)->first()->total ?? 0;
            }
            $chartTitle = "Flux Financiers (Par Jour)";
        }

        // graph 2 
        $topStoresRaw = Store::where('event_id', $event->id)
            ->withSum(['orders as total_revenue' => function($q) use ($startDate, $endDate) {
                $q->where('status', 'completed')
                  ->whereBetween('created_at', [$startDate, $endDate]);
            }], 'total_points')
            ->orderByDesc('total_revenue')
            ->take(5) 
            ->get();

        $topStoresNames = $topStoresRaw->map(fn($store) => $store->name)->toArray();
        $topStoresRevenues = $topStoresRaw->map(fn($store) => $store->total_revenue ?? 0)->toArray();

        return view('Admin.events.dashboard', compact(
            'event', 'startDate', 'endDate', 'dateRange',
            'globalRevenue', 'totalTopUp', 'dormant', 'globalOrdersCount', 'globalAverageOrder', 'activeStoresCount',
            'chartLabels', 'chartDataTopUp', 'chartDataPayment', 'chartTitle',
            'topStoresNames', 'topStoresRevenues'
        ));
    }






    public function storeDashboard(Request $request, Event $event, Store $store)
    {
        $dateRange = $request->input('date_range');
        
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
           
            $startDate = Carbon::parse($event->start_date)->startOfDay();
            $endDate = Carbon::parse($event->end_date)->endOfDay();
        }

        $completedOrders = $store->orders()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed');

        $revenue = $completedOrders->sum('total_points');
        $ordersCount = $completedOrders->count();
        $averageOrderValue = $ordersCount > 0 ? ($revenue / $ordersCount) : 0;
        
        $itemsSold = OrderItem::whereHas('order', function($q) use ($store, $startDate, $endDate) {
            $q->where('store_id', $store->id)
              ->whereBetween('created_at', [$startDate, $endDate])
              ->where('status', 'completed');
        })->sum('quantity');

       //graph1
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

       //graph2
        $topProductsRaw = OrderItem::whereHas('order', function($q) use ($store, $startDate, $endDate) {
                $q->where('store_id', $store->id)
                  ->whereBetween('created_at', [$startDate, $endDate])
                  ->where('status', 'completed');
            })
            ->selectRaw('product_id, SUM(quantity) as total_quantity')
            ->groupBy('product_id')
            ->with('product') 
            ->orderByDesc('total_quantity')
            ->take(5)
            ->get();

        $topProductsNames = $topProductsRaw->map(fn($item) => $item->product->name)->toArray();
        $topProductsQuantities = $topProductsRaw->map(fn($item) => $item->total_quantity)->toArray();

        return view('Admin.Stores.dashboard', compact(
            'event', 'store', 'startDate', 'endDate', 'dateRange',
            'revenue', 'ordersCount', 'averageOrderValue', 'itemsSold',
            'chartLabels', 'chartData', 'chartTitle',
            'topProductsNames', 'topProductsQuantities'
        ));
    }





    public function globalDashboard(Request $request)
    {
        
        $dateRange = $request->input('date_range');

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
            
            $startDate = Carbon::now()->subDays(29)->startOfDay();
            $endDate = Carbon::now()->endOfDay();
        }

     
        $txQuery = Transaction::whereBetween('created_at', [$startDate, $endDate]);

       
        $totalTopUp = (clone $txQuery)->where('type', TransactionType::TopUp)->sum('amount');
        
        $totalPayment = (clone $txQuery)->where('type', TransactionType::Payment)->sum('amount');
        
        $totalRefund = (clone $txQuery)->where('type', TransactionType::Refund)->sum('amount');

        $dormant = $totalTopUp - $totalPayment - $totalRefund;

        $activeWristbands = (clone $txQuery)->distinct('participant_id')->count('participant_id');

  //grph1
        $transactionsRaw = (clone $txQuery)
            ->selectRaw('DATE(created_at) as date, type, SUM(amount) as total')
            ->groupBy('date', 'type')
            ->get();

        $chartLabels = [];
        $chartDataTopUp = [];
        $chartDataPayment = [];

        $period = CarbonPeriod::create($startDate, $endDate);
        foreach ($period as $date) {
            $dateString = $date->format('Y-m-d');
            $chartLabels[] = $date->format('d/m');
            
            $dayTopUp = $transactionsRaw->where('date', $dateString)->where('type', TransactionType::TopUp)->first();
            $dayPayment = $transactionsRaw->where('date', $dateString)->where('type', TransactionType::Payment)->first();

            $chartDataTopUp[] = $dayTopUp ? $dayTopUp->total : 0;
            $chartDataPayment[] = $dayPayment ? $dayPayment->total : 0;
        }

     //grph2
        $topEventsRaw = (clone $txQuery)
            ->where('type', TransactionType::Payment)
            ->selectRaw('event_id, SUM(amount) as total_revenue')
            ->groupBy('event_id')
            ->orderByDesc('total_revenue')
            ->take(5)
            ->with('event')
            ->get();

        $topEventNames = $topEventsRaw->map(fn($tx) => $tx->event->name ?? 'Inconnu')->toArray();
        $topEventRevenues = $topEventsRaw->map(fn($tx) => $tx->total_revenue)->toArray();

        return view('Admin.dashboard', compact(
            'startDate', 'endDate', 'dateRange',
            'totalTopUp', 'totalPayment', 'totalRefund', 'dormant', 'activeWristbands',
            'chartLabels', 'chartDataTopUp', 'chartDataPayment',
            'topEventNames', 'topEventRevenues'
        ));
    }
}
