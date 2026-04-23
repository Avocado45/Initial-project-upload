<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    public function show(Category $category)
    {
        $products = $category->products()
            ->with(['category', 'retailers'])
            ->orderBy('product_name')
            ->paginate(8);

        return view('categories.show', compact('category', 'products'));
    }
}
