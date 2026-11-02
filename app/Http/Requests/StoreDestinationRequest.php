<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDestinationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $destination = $this->route('destination');

        return [
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('destinations', 'name')->ignore($destination?->id),
            ],
            'description' => ['nullable', 'string', 'max:2000'],
            'location' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'google_maps_url' => ['nullable', 'url', 'max:500'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_cover_image' => ['nullable', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama destinasi',
            'description' => 'deskripsi',
            'location' => 'lokasi',
            'latitude' => 'latitude',
            'longitude' => 'longitude',
            'google_maps_url' => 'URL Google Maps',
            'cover_image' => 'gambar cover',
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Nama destinasi sudah digunakan.',
            'latitude.between' => 'Latitude harus di antara -90 dan 90.',
            'longitude.between' => 'Longitude harus di antara -180 dan 180.',
            'google_maps_url.url' => 'URL Google Maps harus berupa tautan yang valid (diawali https://).',
            'cover_image.max' => 'Ukuran gambar cover maksimal 2 MB.',
        ];
    }
}
