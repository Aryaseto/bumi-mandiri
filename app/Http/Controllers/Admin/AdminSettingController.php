<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    private array $keys = [
        'site_name', 'tagline', 'phone', 'email', 'whatsapp',
        'address', 'instagram', 'google_maps_url',
    ];

    public function edit()
    {
        $settings = Setting::whereIn('key', $this->keys)->pluck('value', 'key');

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:500'],
            'instagram' => ['nullable', 'string', 'max:255'],
            'google_maps_url' => ['nullable', 'string', 'max:500'],
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
