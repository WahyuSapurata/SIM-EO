<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DataUser extends FormRequest
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
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'role' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Kolom nama harus di isi.',
            'username.required' => 'Kolom username harus di isi.',
            'username.unique' => 'username sudah digunakan oleh pengguna lain.',
            'role.required' => 'Kolom role harus di isi.',
        ];
    }
}
