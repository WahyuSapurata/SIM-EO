<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDataBankRequest extends FormRequest
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
            'nama_bank' => 'required',
            'no_rek' => 'required',
            'cabang' => 'required',
            'atas_nama' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'nama_bank.required' => 'Kolom nama bank harus di isi.',
            'no_rek.required' => 'Kolom no. Rek harus di isi.',
            'cabang.required' => 'Kolom cabang harus di isi.',
            'atas_nama.required' => 'Kolom atas nama harus di isi.',
        ];
    }
}
