<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Category;
use App\services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'asc');
        $categories = $this->categoryService->index($search, $sort);

        return view('category.index', compact('categories', 'search', 'sort'));
    }

    public function store(CategoryStoreRequest $request)
    {
        $this->categoryService->store($request->validated());

        return redirect()->route('category.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(CategoryUpdateRequest $request, Category $category)
    {
        $this->categoryService->update(
            $category,
            $request->validated()
        );

        return redirect()->route('category.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        $deleted = $this->categoryService->delete($category);

        if (!$deleted) {
            return redirect()->route('category.index')
                ->with('error', 'Kategori tidak bisa dihapus karena masih digunakan oleh buku.');
        }

        return redirect()->route('category.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
