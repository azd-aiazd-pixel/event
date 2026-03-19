<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
class WishlistController extends Controller
{
   public function toggle(Product $product)
    {
        $participant = Auth::user()->participant;
if (!$participant) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Profil participant introuvable.'
            ], 403);
        }
if (!$product->is_active) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Ce produit n\'est plus disponible.'
            ], 403);
        }
if ($product->store->event_id !== $participant->event_id) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Ce produit n\'appartient pas à votre événement.'
            ], 403);
        }



        $result = $participant->wishlistedProducts()->toggle($product->id);

        $isAttached = count($result['attached']) > 0;

        return response()->json([
            'status' => 'success',
            'is_favorite' => $isAttached,
            'message' => $isAttached ? 'Ajouté aux favoris' : 'Retiré des favoris'
        ]);
    }


    public function index()
    {
        $participant = Auth::user()->participant;
        if (!$participant) {
            abort(403, 'Profil participant introuvable.');
        }
        
        $wishlistItems = $participant->wishlistedProducts()
            ->with(['store', 'category'])
            ->get();

 
        $groupedWishlist = $wishlistItems->groupBy(function($product) {
            return $product->store->name ?? 'Autre';
        });

        return view('participant.wishlist.index', compact('groupedWishlist'));
    }
}
