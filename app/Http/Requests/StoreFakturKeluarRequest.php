<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFakturKeluarRequest extends FormRequest
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
            'npwp' => 'required',
            'client' => 'required',
            'no_faktur' => 'required',
            'tanggal_faktur' => 'required',
            'masa' => 'required',
            'tahun' => 'required',
            'status_faktur' => 'required',
            'dpp' => 'required',
            'event' => 'required',
            'area' => 'required',
            'total_tagihan' => 'required',
            'realisasi_dana_masuk' => 'required',
            'deskripsi' => 'required',
            'selisih' => 'required',
            'no_bupot' => 'required',
            'tgl_bupot' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'total_fee.required' => 'Kolom fee harus di isi.',
        ];
    }
}
