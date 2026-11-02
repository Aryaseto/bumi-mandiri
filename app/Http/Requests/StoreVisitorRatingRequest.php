<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVisitorRatingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:80'],
            'email' => ['nullable', 'email', 'max:120'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'min:5', 'max:600'],
            // Honeypot: field ini tidak boleh diisi manusia.
            'website' => ['nullable', 'max:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama Anda perlu diisi agar rating bisa ditampilkan.',
            'name.min' => 'Nama minimal 2 karakter.',
            'rating.required' => 'Pilih jumlah bintang terlebih dahulu.',
            'rating.min' => 'Rating minimal 1 bintang.',
            'rating.max' => 'Rating maksimal 5 bintang.',
            'comment.min' => 'Ulasan minimal 5 karakter.',
            'comment.max' => 'Ulasan maksimal 600 karakter.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => is_string($this->name) ? trim($this->name) : $this->name,
            'comment' => is_string($this->comment) ? trim($this->comment) : $this->comment,
        ]);
    }
}
