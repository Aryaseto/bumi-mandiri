<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'booking_time' => ['nullable', 'date_format:H:i'],
            'participants' => ['required', 'integer', 'min:1'],
            'package_id' => ['nullable', 'exists:packages,id'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'booking_date.after_or_equal' => 'Tanggal booking tidak boleh di masa lalu.',
            'participants.min' => 'Jumlah peserta minimal 1 orang.',
        ];
    }
}
