<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePoRequest extends FormRequest
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
            'vendor' => 'required',
            'tempo' => 'required',
            'no_invoice' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'vendor.required' => 'Kolom vendor harus di isi.',
            'tempo.required' => 'Kolom jatuh Tempo harus di isi.',
            'no_invoice.required' => 'Kolom no invoice kegiatan harus di isi.',
        ];
    }
}
