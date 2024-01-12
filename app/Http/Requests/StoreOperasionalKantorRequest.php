<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOperasionalKantorRequest extends FormRequest
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
            'deskripsi' => 'required',
            'spsifikasi' => 'required',
            'harga_satuan' => 'required',
            'qty' => 'required',
            'qty_satuan' => 'required',
            'freq' => 'required',
            'freq_satuan' => 'required',
            'kategori' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'deskripsi.required' => 'Kolom deskripsi harus di isi.',
            'spsifikasi.required' => 'Kolom spsifikasi harus di isi.',
            'harga_satuan.required' => 'Kolom harga satuan harus di isi.',
            'qty.required' => 'Kolom qty harus di isi.',
            'qty_satuan.required' => 'Kolom qty satuan harus di isi.',
            'freq.required' => 'Kolom freq harus di isi.',
            'freq_satuan.required' => 'Kolom freq satuan harus di isi.',
            'kategori.required' => 'Kolom kategori harus di isi.',
        ];
    }
}
