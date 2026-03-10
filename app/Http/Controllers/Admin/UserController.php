<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Enum\Role; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
   
    public function create(Request $request)
    {
       
        $returnToEventId = $request->query('return_to_event');

        return view('admin.users.create', [
            'returnToEventId' => $returnToEventId
        ]);
    }





public function store(Request $request)
{
  
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'confirmed', Password::defaults()],
        'return_to_event_id' => ['nullable', 'integer', 'exists:events,id'],
    ]);

   
    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => Role::Store, 
    ]);

  
    
    //  On vient du Store 
    if ($request->filled('return_to_event_id')) {
        return redirect()
            ->route('admin.stores.create', $request->return_to_event_id)
            ->with('new_user_id', $user->id)
            ->with('new_user_name', $user->name . ' (' . $user->email . ')')
            ->with('success', "Le vendeur {$user->name} a été créé !");
    }

    //  Cas par défaut (Création simple)
    // TEMPORAIRE : On reste sur la page tant que tu n'as pas fait la liste des users
    return back()->with('success', 'Utilisateur créé avec succès !');

    /* FUTUR (Quand j aurais fait la page Index) :
    return redirect()
        ->route('admin.users.index')
        ->with('success', 'Utilisateur créé avec succès.');
    */
}
}