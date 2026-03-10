<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\UnitMeasure;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class StoreProductController extends Controller
{
  public function index(Request $request, Store $store)
{
    Gate::authorize('view', $store);

    $query = $store->products()->with(['category', 'unitMeasure']);

    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    if ($request->filled('category')) {
        $query->where('category_id', $request->category);
    }




    $products = $query->latest()
        ->paginate(12)
        ->withQueryString(); 

  $categories = Category::whereHas('products', function($q) use ($store) {
        $q->where('store_id', $store->id);
    })->get();

    return view('store.products.index', compact('store', 'products', 'categories'));
}


public function create(Store $store)
{
    Gate::authorize('view', $store);
   
    $categories = Category::all();
    $unitMeasures = UnitMeasure::all();

    return view('store.products.create', compact('store', 'categories', 'unitMeasures'));
}

public function store(Request $request, Store $store)
{
    Gate::authorize('view', $store);

    $request->merge([
        'is_stockable' => $request->boolean('is_stockable')
    ]);

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'category_id' => 'nullable|exists:categories,id',
        'unit_measure_id' => 'nullable|exists:unit_measures,id',
        'unit_price' => 'required|numeric|min:0',
        'is_stockable'    => 'boolean',
        'quantity'   => 'nullable|required_if:is_stockable,true|integer|min:0',
        'picture' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
    ]);

    $validated['is_active'] = $request->boolean('is_active');

    $path = null; 

    try {
        if ($request->hasFile('picture')) {
            $path = $request->file('picture')->store('products', 'public');
            $validated['picture'] = $path;
        }

        $store->products()->create([
            'name'            => $validated['name'],
            'category_id'     => $validated['category_id'] ?? null,
            'unit_measure_id' => $validated['unit_measure_id'] ?? null,
            'unit_price'      => $validated['unit_price'],
            'picture'         => $validated['picture'] ?? null,
            'is_active'       => $validated['is_active'],
            'is_stockable'    => $validated['is_stockable'],
            'quantity'        => $validated['is_stockable'] ? $validated['quantity'] : null,
        ]);

        return redirect()
            ->route('store.products.index', $store)
            ->with('success', 'Produit ajouté avec succès !');

    } catch (\Exception $e) {
        if ($path) {
            Storage::disk('public')->delete($path);
        }

        return back()
            ->with('error', 'Erreur lors de l\'ajout du produit : ' . $e->getMessage())
            ->withInput();
    }
}

public function edit(Store $store, Product $product)
{
    if ($product->store_id !== $store->id) {
        abort(404); 
    }
    Gate::authorize('update', $product);

    $categories = Category::all();
    $unitMeasures = UnitMeasure::all();

    return view('store.products.edit', compact('store', 'product', 'categories', 'unitMeasures'));
}



public function update(Request $request, Store $store, Product $product)
{ 
    if ($product->store_id !== $store->id) {
        abort(404); 
    }
    Gate::authorize('update', $product);

    $request->merge([
        'is_stockable' => $request->boolean('is_stockable')
    ]);

    $validated = $request->validate([
        'name'            => 'required|string|max:255',
        'unit_measure_id' => 'nullable|exists:unit_measures,id',
        'category_id'     => 'nullable|exists:categories,id',
        'unit_price'      => 'required|numeric|min:0',
        'is_stockable'    => 'boolean',
        'quantity'        => 'nullable|required_if:is_stockable,true|integer|min:0',
        'picture'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
    ]);

    $validated['is_active'] = $request->boolean('is_active');

    if (!$validated['is_stockable']) {
        $validated['quantity'] = null;
    }

    try {
        // 1. On mémorise l'ancienne image du produit
        $oldPicture = $product->picture;

        // 2. S'il y a une nouvelle image, on la sauvegarde
        if ($request->hasFile('picture')) {
            $validated['picture'] = $request->file('picture')->store('products', 'public');
        }

        // 3. On met à jour la base de données D'ABORD
        $product->update($validated);

        // 4. SI (et seulement si) la DB a marché, on supprime l'ancienne image
        if ($request->hasFile('picture') && $oldPicture) {
            Storage::disk('public')->delete($oldPicture);
        }

        return redirect()->route('store.products.index', $store)
                         ->with('success', 'Produit mis à jour avec succès !');

    } catch (\Exception $e) {
        // 5. SI ÇA PLANTE : On supprime la NOUVELLE image uploadée pour rien
        if (isset($validated['picture']) && $request->hasFile('picture')) {
            Storage::disk('public')->delete($validated['picture']);
        }

        return back()
            ->with('error', 'Erreur lors de la mise à jour du produit : ' . $e->getMessage())
            ->withInput();
    }
}






// csv ou txt - format (name; unit_price; picture)
    public function import(Request $request, Store $store)
    {Gate::authorize('view', $store);
        
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
        ]);

        $file = $request->file('file');

        if (($handle = fopen($file->getPathname(), 'r')) !== false) {
            
            $count = 0;
            try {
                DB::beginTransaction();
               
                while (($row = fgetcsv($handle, 0, ";")) !== false) {
                    
                    if (empty($row) || count($row) < 2) continue;

                    $name = trim($row[0]);
                    $rawPrice = trim($row[1]);
                    
                    // Nettoyage du prix (remplace la virgule par un point)
                    $cleanPrice = str_replace(',', '.', $rawPrice);
                    $unitPrice = floatval($cleanPrice);
                    
                  
                    $picture = isset($row[2]) ? trim($row[2]) : null;

                    if (empty($name)) continue;

                   
                    $store->products()->create([
                        'name'       => $name,
                        'unit_price' => $unitPrice,
                        'is_stockable' => true,
                        'picture'    => $picture,
                        'quantity'   => 0, 
                        'is_active'  => true,
                    ]);

                    $count++;
                }

               
                DB::commit();
                fclose($handle);

                return redirect()->route('store.products.index', $store)
                    ->with('success', "$count produits importés avec succès !");

            } catch (\Exception $e) {
             
                DB::rollBack();
                if (isset($handle)) fclose($handle);
                
                return back()->with('error', "Erreur technique : " . $e->getMessage());
            }
        }

        return back()->with('error', "Impossible de lire le fichier.");
    }

















}