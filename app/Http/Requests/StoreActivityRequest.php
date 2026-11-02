<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'destination_id' => ['required', 'exists:destinations,id'],
            'name' => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999999'],
            'duration_minutes' => ['required', 'integer', 'min:1', 'max:10080'],
            'capacity' => ['required', 'integer', 'min:1', 'max:10000'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_cover_image' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'category_id' => 'kategori',
            'destination_id' => 'destinasi',
            'name' => 'nama aktivitas',
            'short_description' => 'deskripsi singkat',
            'description' => 'deskripsi',
            'price' => 'harga',
            'duration_minutes' => 'durasi',
            'capacity' => 'kapasitas',
            'cover_image' => 'gambar cover',
        ];
    }

    public function messages(): array
    {
        return [
            'cover_image.max' => 'Ukuran gambar cover maksimal 2 MB.',
        ];
    }
}
