<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLaporanLabaRequest extends FormRequest
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
            'nama_event' => 'required',
            'budget_client' => 'required',
            'real_cost' => 'required',
            'pph' => 'required',
            'operasional_kantor' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'nama_event.required' => 'Kolom nama event harus di isi.',
            'budget_client.required' => 'Kolom budget client harus di isi.',
            'real_cost.required' => 'Kolom real cost harus di isi.',
            'pph.required' => 'Kolom pph harus di isi.',
            'operasional_kantor.required' => 'Kolom operasional kantor harus di isi.',
        ];
    }
}
