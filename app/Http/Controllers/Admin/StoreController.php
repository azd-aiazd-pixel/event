<?php

namespace App\Http\Controllers\Admin;

use App\Enum\Role;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
class StoreController extends Controller
{
    
    public function index(Request $request, Event $event)
    {
      
        $query = Store::where('event_id', $event->id)
            ->with(['user']); 

     
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                
                $q->where('name', 'like', "%{$search}%")
                  
                  ->orWhereHas('user', function ($sq) use ($search) {
                      $sq->where('email', 'like', "%{$search}%")
                         ->orWhere('name', 'like', "%{$search}%");
                  });
            });
        }

        
        $stores = $query->latest()->paginate(10)->withQueryString();

  
        return view('admin.stores.index', [
            'stores' => $stores,
            'event'  => $event,
        ]);
    }



    public function create(Event $event)
    {
        return view('admin.stores.create', [
            'event' => $event
           
        ]);
    }


public function searchUsers(Request $request): JsonResponse
{
    $search = $request->get('q');

    // On ne commence la recherche que si on a au moins 2 caractères
    if (strlen($search) < 2) {
        return response()->json([]);
    }

    $users = User::where('role', Role::Store)
        ->where(function ($query) use ($search) {
            $query->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
        })
        ->limit(10) 
        ->get(['id', 'name', 'email']);

    return response()->json($users);
}






public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'user_id' => ['required', 'exists:users,id'], 
            'logo'    => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'], 
        ]);

        $logoPath = null; 

        try {
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('logos_stores', 'public');
            }

            return DB::transaction(function () use ($event, $validated, $logoPath) {
                Store::create([
                    'name'     => $validated['name'],
                    'user_id'  => $validated['user_id'],
                    'event_id' => $event->id,
                    'logo'     => $logoPath,
                    'status'   => 'active',
                ]);

                return redirect()
                    ->route('admin.stores.index', $event->id)
                    ->with('success', 'La boutique a été créée et affectée avec succès.');
            });

        } catch (\Exception $e) {
            if ($logoPath) {
                Storage::disk('public')->delete($logoPath);
            }

            return back()
                ->with('error', 'Erreur lors de la création de la boutique : ' . $e->getMessage())
                ->withInput();
        }
    }




public function edit(Event $event, Store $store)
{
  
    if ($store->event_id !== $event->id) {
        abort(404);
    }

    $store->load('user');

    return view('admin.stores.edit', [
        'event' => $event,
        'store' => $store
    ]);
}


public function update(Request $request, Event $event, Store $store)
{
    if ($store->event_id !== $event->id) {
        abort(403);
    }

    $validated = $request->validate([
        'name'    => ['required', 'string', 'max:255'],
        'user_id' => ['required', 'exists:users,id'],
        'status'  => ['required', 'in:active,inactive'],
        'logo'    => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
    ]);

    try {
        $oldLogo = $store->logo; 

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('logos_stores', 'public');
        }

        $store->update($validated);

        if ($request->hasFile('logo') && $oldLogo) {
            Storage::disk('public')->delete($oldLogo);
        }

        return redirect()
            ->route('admin.stores.index', $event->id)
            ->with('success', 'Boutique mise à jour.');

    } catch (\Exception $e) {
       
        if (isset($validated['logo']) && $request->hasFile('logo')) {
            Storage::disk('public')->delete($validated['logo']);
        }

        return back()->with('error', 'Erreur lors de la mise à jour.')->withInput();
    }
}

// Soft delete de la boutique et de ses produits
public function destroy(Event $event, Store $store)
{
   
    if ($store->event_id !== $event->id) {
        abort(403, 'Action non autorisée.');
    }

    try {
       
        return DB::transaction(function () use ($store, $event) {
        $store->products()->delete();
            $store->delete();

            return redirect()
                ->route('admin.stores.index', $event->id)
                ->with('success', 'Boutique et ses produits archivés avec succès.');
        });

    } catch (\Exception $e) {
        return back()->with('error', 'Une erreur technique est survenue lors de l\'archivage.');
    }
}
}