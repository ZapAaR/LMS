<?php

namespace App\services;

use App\Models\Category;

class CategoryService
{
    protected $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function index($search, $sort='asc')
    {
        if (!in_array($sort, ['asc', 'desc'], true)){
            $sort='asc';
        }

        return $this->category->withCount('books')
        ->when($search, function ($query) use ($search) {
            $query->where('name', 'like', $search . '%');
        })
        ->orderBy('name', $sort)
        ->paginate(5)
        ->withQueryString();
    }

    public function store(array $data)
    {
        return $this->category->create($data);
    }

    public function update(Category $category, array $data)
    {
        return $category->update($data);
    }

    public function delete(Category $category)
    {
        if ($category->books()->exists()) {
            return false;
        }

        return $category->delete();
    }
}
