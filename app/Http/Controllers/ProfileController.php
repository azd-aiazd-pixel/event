<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use App\Models\Store;
use Illuminate\Support\Facades\Gate;
class ProfileController extends Controller
{
    /**
     * Retourne la vue profil selon le rôle de l'utilisateur connecté.
     */
     private function profileView(): string
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return 'admin.profile.edit';
        }

        if ($user->isStore()) {
            return 'store.profile.edit';
        }

        return 'Participant.profile.edit';
    }

    public function edit(Request $request, Store $store = null)
    {
        if ($store) {
            Gate::authorize('view', $store);
        }
        $user = $request->user();
        return view($this->profileView(), compact('user', 'store'));
    }

    public function updateInfo(Request $request, Store $store = null)
    {
        if ($store) {
            Gate::authorize('view', $store);
        }
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->update($validated);

        return back()->with('success', 'Vos informations ont été mises à jour.');
    }

    public function updatePassword(Request $request, Store $store = null)
    {
          if ($store) {
            Gate::authorize('view', $store);
        }
        
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Votre mot de passe a été modifié.');
    }
}
