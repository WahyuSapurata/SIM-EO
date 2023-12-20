<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDataVendorRequest extends FormRequest
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
            'nama_owner' => 'required',
            'nama_perusahaan' => 'required',
            'alamat_perusahaan' => 'required',
            'no_telp' => 'required',
            'nama_bank' => 'required',
            'no_rek' => 'required',
            'npwp' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'nama_owner.required' => 'Kolom nama owner harus di isi.',
            'nama_perusahaan.required' => 'Kolom nama perusahaan harus di isi.',
            'alamat_perusahaan.required' => 'Kolom alamat perusahaan harus di isi.',
            'no_telp.required' => 'Kolom no.telp harus di isi.',
            'nama_bank.required' => 'Kolom nama_bank harus di isi.',
            'no_rek.required' => 'Kolom no.rek harus di isi.',
            'npwp.required' => 'Kolom npwp harus di isi.',
        ];
    }
}
