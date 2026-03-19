<?php
namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class ParticipantStoreController extends Controller
{
   
  public function index()
    {
       
        $participant = Auth::user()->participant;

      
        if (!$participant) {
            abort(403, 'Profil participant introuvable.');
        }

    
   $stores = $participant->event
        ->stores()
        ->where('status', 'active')
        ->get();

      
        return view('participant.stores.index', compact('stores'));
    }

public function show(Store $store)
    {
        $participant = Auth::user()->participant;

        if (!$participant) {
            abort(403, 'Profil participant introuvable.');
        }

        if ($store->event_id !== $participant->event_id || !$store->isActive()) {
            abort(403, 'Vous n\'avez pas accès à cette boutique.');
        }

        $store->load('activeProducts.category');
        $products = $store->activeProducts;

        $categories = $products->pluck('category')->unique()->filter();

        
        $wishlistedIds = $participant->wishlists()->pluck('product_id')->toArray();

        $products = $products->sortByDesc(function($product) use ($wishlistedIds) {
            return in_array($product->id, $wishlistedIds);
        })->values();
       
        return view('participant.stores.show', compact('store', 'products', 'categories', 'wishlistedIds'));
    }

}