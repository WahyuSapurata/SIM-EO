<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
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
            'client' => 'required',
            'no_invoice' => 'required',
            'tanggal_invoice' => 'required',
            'deskripsi' => 'required',
            'penanggung_jawab' => 'required',
            'jabatan' => 'required',
            'uuid_bank' => 'required',
            'total' => 'required',
            'pajak' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'client.required' => 'Kolom client owner harus di isi.',
            'no_invoice.required' => 'Kolom no invoice harus di isi.',
            'tanggal_invoice.required' => 'Kolom tanggal invoice perusahaan harus di isi.',
            'deskripsi.required' => 'Kolom deskripsi harus di isi.',
            'penanggung_jawab.required' => 'Kolom penanggung jawab harus di isi.',
            'jabatan.required' => 'Kolom jabatan harus di isi.',
            'uuid_bank.required' => 'Kolom bank harus di isi.',
            'total.required' => 'Kolom total harus di isi.',
            'pajak.required' => 'Kolom pajak harus di isi.',
        ];
    }
}
