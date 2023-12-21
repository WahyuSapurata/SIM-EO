<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDataPajakRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'pajak' => 'required|regex:/^[0-9]+(\.[0-9]{1,2})?$/',
        ];
    }

    public function messages()
    {
        return [
            'pajak.required' => 'Kolom pajak harus di isi.',
            'pajak.regex' => 'Format pajak tidak valid. Gunakan titik sebagai pemisah desimal.',
        ];
    }
}
