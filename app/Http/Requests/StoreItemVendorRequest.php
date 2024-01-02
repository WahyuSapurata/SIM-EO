<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemVendorRequest extends FormRequest
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
            'kegiatan' => 'required',
            'qty' => 'required',
            'satuan_kegiatan' => 'required',
            'freq' => 'required',
            'satuan' => 'required',
            'harga_satuan' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'kegiatan.required' => 'Kolom kegiatan harus di isi.',
            'qty.required' => 'Kolom qty harus di isi.',
            'satuan_kegiatan.required' => 'Kolom satuan kegiatan harus di isi.',
            'freq.required' => 'Kolom freq harus di isi.',
            'satuan.required' => 'Kolom satuan harus di isi.',
            'harga_satuan.required' => 'Kolom harga satuan harus di isi.',
        ];
    }
}
