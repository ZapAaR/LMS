<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class MemberStoreRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'member_code' => ['required', 'string', 'max:255', 'unique:members,member_code'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:members,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }

    public function messages(): array
    {
        return [
            'member_code.required' => 'Kode anggota wajib diisi.',
            'member_code.string' => 'Kode anggota harus berupa teks.',
            'member_code.max' => 'Kode anggota maksimal 255 karakter.',
            'member_code.unique' => 'Kode anggota sudah digunakan.',

            'name.required' => 'Nama anggota wajib diisi.',
            'name.string' => 'Nama anggota harus berupa teks.',
            'name.max' => 'Nama anggota maksimal 255 karakter.',

            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'email.unique' => 'Email sudah digunakan.',

            'phone.string' => 'Nomor telepon harus berupa teks.',
            'phone.max' => 'Nomor telepon maksimal 20 karakter.',

            'address.string' => 'Alamat harus berupa teks.',

            'status.required' => 'Status anggota wajib dipilih.',
            'status.in' => 'Status anggota harus active atau inactive.',
        ];
    }
}
