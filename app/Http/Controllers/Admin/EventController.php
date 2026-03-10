<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class EventController extends Controller
{
    public function index(Request $request)
    {
       
       $query = Event::withCount([
            'participants', 
            'stores' => function ($query) {
              
                $query->where('status', 'active'); 
            }
        ]);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('date_start')) {
            $query->whereDate('start_date', '>=', $request->date_start);
        }

        if ($request->filled('date_end')) {
            $query->whereDate('end_date', '<=', $request->date_end);
        }

        $events = $query->latest('start_date')->paginate(12)->withQueryString();

        return view('admin.events.index', compact('events'));
    }



public function create()
    {
        return view('admin.events.create');
    }

public function store(Request $request)
    {
      
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date', 
        ]);

       
        $validated['is_active'] = true;

        Event::create($validated);

       
        return redirect()->route('admin.events.index')
            ->with('success', 'Le festival a été créé avec succès !');
    }


    public function show(Event $event)
    {
        return view('admin.in_progress');
    }

public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }
 public function update(Request $request, Event $event)
    {
     
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'is_active'  => 'required|boolean', 
        ]);

       
        $event->update($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Le festival a été mis à jour avec succès.');
    }
    


public function destroy(Event $event)
{
    try {
       
        return DB::transaction(function () use ($event) {
           //on soft delet  les particpants et store  mais on laisse les user et les produits 
            $event->participants()->delete();

            $event->stores()->delete();

            $event->delete();

            return redirect()->route('admin.events.index')
                ->with('success', 'Le festival et toutes ses données ont été archivés avec succès.');
        });

    } catch (\Exception $e) {
        return back()->with('error', 'Impossible d\'archiver le festival : ' . $e->getMessage());
    }
}





}