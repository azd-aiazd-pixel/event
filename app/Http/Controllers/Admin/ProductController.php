<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;



class ProductController extends Controller
{
    
    public function index(Request $request, Store $store)
    {
        $query = $store->products();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $sort = $request->get('sort', 'latest');

       match ($sort) {
            'price_asc'  => $query->orderBy('unit_price', 'asc'),
            'price_desc' => $query->orderBy('unit_price', 'desc'),
          
            'stock_asc'  => $query->orderBy('is_stockable', 'desc')->orderBy('quantity', 'asc'), 
           
            'stock_desc' => $query->orderBy('is_stockable', 'asc')->orderBy('quantity', 'desc'),
            
            'name'       => $query->orderBy('name', 'asc'),
            default      => $query->latest(),
        };

        $products = $query->paginate(10)->withQueryString();
        
        $store->load('event');

        return view('admin.products.index', compact('store', 'products'));
    }


    public function create(Store $store)
    {
       
        return view('admin.products.create', compact('store'));
    }
    
public function store(Request $request, Store $store)
{
    
    $request->merge([
        'is_stockable' => $request->boolean('is_stockable'),
        'is_active'    => $request->boolean('is_active') 
    ]);

    $validated = $request->validate([
        'name'            => ['required', 'string', 'max:255'],
        'unit_price'      => ['required', 'numeric', 'min:0'],
        'category_id'     => ['nullable', 'integer', 'exists:categories,id'],
        'unit_measure_id' => ['nullable', 'integer', 'exists:unit_measures,id'],
        'quantity'        => ['nullable', 'required_if:is_stockable,true', 'integer', 'min:0'],
        'is_stockable'    => ['boolean'],
        'picture'         => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'], 
        'is_active'       => ['required', 'boolean'],
    ]);

    $picturePath = null;

    try {
        if ($request->hasFile('picture')) {
            $picturePath = $request->file('picture')->store('products', 'public');
        }

        $store->products()->create([
            'name'            => $validated['name'],
            'unit_price'      => $validated['unit_price'],
            'quantity'        => $validated['is_stockable'] ? $validated['quantity'] : null,
            'category_id'     => $validated['category_id'] ?? null,
            'unit_measure_id' => $validated['unit_measure_id'] ?? null,
            'is_active'       => $validated['is_active'],
            'is_stockable'    => $validated['is_stockable'],
            'picture'         => $picturePath,
        ]);

        return redirect()->route('admin.stores.products.index', $store->id)
            ->with('success', 'Produit ajouté avec succès !');

    } catch (\Exception $e) {
        if ($picturePath) { 
            Storage::disk('public')->delete($picturePath); 
        }
        return back()->withInput()->with('error', $e->getMessage());
    }
}


// csv ou txt  forme (name; unit_price; picture)  
public function import(Request $request, Store $store)
{
    $request->validate([
        'file' => ['required', 'file', 'max:2048'],
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
                $cleanPrice = str_replace(',', '.', $rawPrice);
                $unitPrice = floatval($cleanPrice);
              $picture = isset($row[2]) ? trim($row[2]) : null;

                if (empty($name)) continue;

                $store->products()->create([
                    'name'       => $name,
                    'unit_price' => $unitPrice,
                    'picture'    => $picture,
                    'is_stockable' => true,
                    'quantity'   => 0, 
                    'is_active'  => true,
                ]);

                $count++;
            }

            DB::commit();
            fclose($handle);

            return redirect()->route('admin.stores.products.index', $store->id)
                ->with('success', "$count produits importés avec le format ; et , !");

        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($handle)) fclose($handle);
            return back()->with('error', "Erreur technique : " . $e->getMessage());
        }
    }
}




public function edit(Store $store, Product $product)
    {
        return view('admin.products.edit', compact('store', 'product'));
    }

public function update(Request $request, Store $store, Product $product)
    {
        
        $request->merge([
            'is_stockable' => $request->boolean('is_stockable'),
            'is_active'    => $request->boolean('is_active')
        ]);

        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'unit_price'   => ['required', 'numeric', 'min:0'],
            'is_stockable' => ['boolean'],
            'quantity'     => ['nullable', 'required_if:is_stockable,true', 'integer', 'min:0'],
            'picture'      => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'is_active'    => ['required', 'boolean'],
        ]);

        try {
            $data = [
                'name'         => $validated['name'],
                'unit_price'   => $validated['unit_price'],
                'is_stockable' => $validated['is_stockable'],
                'quantity'     => $validated['is_stockable'] ? $validated['quantity'] : null,
                'is_active'    => $validated['is_active'],
            ];

           
            $oldPicture = $product->picture;

         
            if ($request->hasFile('picture')) {
                $data['picture'] = $request->file('picture')->store('products', 'public');
            }

         
            $product->update($data);

            if ($request->hasFile('picture') && $oldPicture) {
                Storage::disk('public')->delete($oldPicture);
            }

            return redirect()->route('admin.stores.products.index', $store->id)
                ->with('success', 'Produit mis à jour avec succès !');

        } catch (\Exception $e) {
        
            if (isset($data['picture']) && $request->hasFile('picture')) {
                Storage::disk('public')->delete($data['picture']);
            }
            
            return back()->withInput()->with('error', "Erreur : " . $e->getMessage());
        }
    }

//soft delete 
public function destroy(Store $store, Product $product)
    {
        try {
         
            $product->delete();

            return redirect()->route('admin.stores.products.index', $store->id)
                ->with('success', 'Produit archivé avec succès ');

        } catch (\Exception $e) {
            return back()->with('error', "Impossible d'archiver le produit : " . $e->getMessage());
        }
    }
    public function export(Request $request, Store $store)
    {

        $type = $request->query('type', 'csv');
        $fileName = 'catalogue_' . Str::slug($store->name) . '_' . date('d-m-Y') . '.' . $type;

        $products = $store->products()->get();

        //  On définit les headers pour forcer le téléchargement
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        return response()->stream(function () use ($products) {
            
            $handle = fopen('php://output', 'w');

  
            foreach ($products as $product) {
             
                $row = [
                    $product->name,                
                    number_format($product->unit_price, 2, '.', ''), 
                    $product->picture
                ];

                fputcsv($handle, $row, ';');
            }

            fclose($handle);

        }, 200, $headers);
    }
}