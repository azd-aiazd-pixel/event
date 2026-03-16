<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
class StoreSettingsController extends Controller
{

    public function edit(Store $store)
    {
        Gate::authorize('view', $store);

        return view('store.settings', compact('store'));
    }

    // Section Identité (nom + logo) 

    public function updateIdentity(Request $request, Store $store)
    {
        Gate::authorize('view', $store);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'remove_logo' => ['nullable', 'boolean'],
        ]);

        $data = [
            'name' => $validated['name'],
        ];

        if ($request->has('remove_logo') && $request->remove_logo) {
            if ($store->logo) {
                Storage::disk('public')->delete($store->logo);
            }
            $data['logo'] = null;
        } elseif ($request->hasFile('logo')) {
            if ($store->logo) {
                Storage::disk('public')->delete($store->logo);
            }
            $data['logo'] = $request->file('logo')->store('logos_stores', 'public');
        }

        $store->update($data);

        return redirect()->back()->with('success_identity', 'Identité de la boutique mise à jour avec succès.');
    }

    // ─── Section Fonctionnement (workflow_type) ───────────────────────────────

    public function updateWorkflow(Request $request, Store $store)
    {
        Gate::authorize('view', $store);

        $validated = $request->validate([
            'workflow_type' => ['required', 'in:direct,queue'],
        ]);

        $store->update(['workflow_type' => $validated['workflow_type']]);

        return redirect()->back()->with('success_workflow', 'Mode de fonctionnement mis à jour avec succès.');
    }

    // ─── Section Design (couleurs + images) ──────────────────────────────────

    public function update(Request $request, Store $store)
    {
        Gate::authorize('view', $store);

        $validated = $request->validate([
            'theme_primary_color' => ['nullable', 'string', 'regex:/^#[a-fA-F0-9]{6}$/'],
            'theme_bg_color' => ['nullable', 'string', 'regex:/^#[a-fA-F0-9]{6}$/'],
            'theme_text_color' => ['nullable', 'string', 'regex:/^#[a-fA-F0-9]{6}$/'],
            'theme_bg_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'remove_bg_image' => ['nullable', 'boolean'],
            'theme_body_bg_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'remove_body_bg_image' => ['nullable', 'boolean'],
        ]);

        $data = [
            'theme_primary_color' => $validated['theme_primary_color'] ?? null,
            'theme_bg_color' => $validated['theme_bg_color'] ?? null,
            'theme_text_color' => $validated['theme_text_color'] ?? null,
        ];

        if ($request->has('remove_bg_image') && $request->remove_bg_image) {
            if ($store->theme_bg_image) {
                Storage::disk('public')->delete($store->theme_bg_image);
            }
            $data['theme_bg_image'] = null;
        } elseif ($request->hasFile('theme_bg_image')) {
            if ($store->theme_bg_image) {
                Storage::disk('public')->delete($store->theme_bg_image);
            }
            $data['theme_bg_image'] = $request->file('theme_bg_image')->store('store_themes', 'public');
        }

        if ($request->has('remove_body_bg_image') && $request->remove_body_bg_image) {
            if ($store->theme_body_bg_image) {
                Storage::disk('public')->delete($store->theme_body_bg_image);
            }
            $data['theme_body_bg_image'] = null;
        } elseif ($request->hasFile('theme_body_bg_image')) {
            if ($store->theme_body_bg_image) {
                Storage::disk('public')->delete($store->theme_body_bg_image);
            }
            $data['theme_body_bg_image'] = $request->file('theme_body_bg_image')->store('store_themes', 'public');
        }

        $store->update($data);

        return redirect()->back()->with('success_theme', 'Thème de la boutique mis à jour avec succès.');
    }
}
