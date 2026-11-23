<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BookStoreRequest extends FormRequest
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
            'category_id' => ['required', 'exists:categories,id'],
            'book_code' => ['required', 'string', 'max:255', 'unique:books,book_code'],
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'publisher' => ['nullable', 'string', 'max:255'],
            'publication_year' => ['nullable', 'integer', 'min:1000', 'max:' . date('Y')],
            'isbn' => ['nullable', 'string', 'max:255', 'unique:books,isbn'],
            'stock' => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Kategori buku wajib dipilih.',
            'category_id.exists' => 'Kategori buku tidak ditemukan.',

            'book_code.required' => 'Kode buku wajib diisi.',
            'book_code.string' => 'Kode buku harus berupa teks.',
            'book_code.max' => 'Kode buku maksimal 255 karakter.',
            'book_code.unique' => 'Kode buku sudah digunakan.',

            'title.required' => 'Judul buku wajib diisi.',
            'title.string' => 'Judul buku harus berupa teks.',
            'title.max' => 'Judul buku maksimal 255 karakter.',

            'author.required' => 'Nama penulis wajib diisi.',
            'author.string' => 'Nama penulis harus berupa teks.',
            'author.max' => 'Nama penulis maksimal 255 karakter.',

            'publisher.string' => 'Nama penerbit harus berupa teks.',
            'publisher.max' => 'Nama penerbit maksimal 255 karakter.',

            'publication_year.integer' => 'Tahun terbit harus berupa angka.',
            'publication_year.min' => 'Tahun terbit tidak valid.',
            'publication_year.max' => 'Tahun terbit tidak boleh melebihi tahun sekarang.',

            'isbn.string' => 'ISBN harus berupa teks.',
            'isbn.max' => 'ISBN maksimal 255 karakter.',
            'isbn.unique' => 'ISBN sudah terdaftar.',

            'stock.required' => 'Stok buku wajib diisi.',
            'stock.integer' => 'Stok buku harus berupa angka.',
            'stock.min' => 'Stok buku tidak boleh kurang dari 0.',
        ];
    }
}
