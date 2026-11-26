<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BorrowingUpdateRequest extends FormRequest
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
            'member_id' => [
                'required',
                'integer',
                Rule::exists('members', 'id')->where('status', 'active'),
            ],
            'borrow_date' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after:borrow_date'],
            'notes' => ['nullable', 'string'],

            'books' => ['required', 'array', 'min:1'],
            'books.*.book_id' => ['required', 'integer', 'distinct', 'exists:books,id'],
            'books.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'member_id.required' => 'Anggota wajib dipilih.',
            'member_id.integer' => 'ID anggota tidak valid.',
            'member_id.exists' => 'Anggota tidak ditemukan.',

            'borrow_date.required' => 'Tanggal peminjaman wajib diisi.',
            'borrow_date.date' => 'Tanggal peminjaman tidak valid.',

            'due_date.required' => 'Tanggal pengembalian wajib diisi.',
            'due_date.date' => 'Tanggal pengembalian tidak valid.',
            'due_date.after' => 'Tanggal pengembalian harus setelah tanggal peminjaman.',

            'notes.string' => 'Catatan harus berupa teks.',

            'books.required' => 'Minimal satu buku harus dipilih.',
            'books.array' => 'Format daftar buku tidak valid.',
            'books.min' => 'Minimal satu buku harus dipilih.',

            'books.*.book_id.required' => 'Buku wajib dipilih.',
            'books.*.book_id.integer' => 'ID buku tidak valid.',
            'books.*.book_id.distinct' => 'Buku tidak boleh dipilih lebih dari satu kali.',
            'books.*.book_id.exists' => 'Buku tidak ditemukan.',

            'books.*.quantity.required' => 'Jumlah buku wajib diisi.',
            'books.*.quantity.integer' => 'Jumlah buku harus berupa angka.',
            'books.*.quantity.min' => 'Jumlah buku minimal 1.',
        ];
    }
}
