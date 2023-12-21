<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDataClientRequest extends FormRequest
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
            'nama_client' => 'required',
            'event' => 'required',
            'venue' => 'required',
            'project_date' => 'required',
            'nama_pic' => 'required',
            'no_pic' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'nama_client.required' => 'Kolom nama client harus di isi.',
            'event.required' => 'Kolom event perusahaan harus di isi.',
            'venue.required' => 'Kolom venue perusahaan harus di isi.',
            'project_date.required' => 'Kolom project date.telp harus di isi.',
            'nama_pic.required' => 'Kolom nama pic harus di isi.',
            'no_pic.required' => 'Kolom no. pic harus di isi.',
        ];
    }
}
