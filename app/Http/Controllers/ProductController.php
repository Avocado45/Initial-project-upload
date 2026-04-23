<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['category', 'retailers'])
            ->orderBy('product_name')
            ->paginate(8);

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();

        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_name'  => 'required|string|max:255',
            'product_year'  => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'product_price' => 'required|numeric|min:0',
            'category_id'   => 'nullable|exists:categories,id',
        ]);

        $product = Product::create([
            'product_name'  => $data['product_name'],
            'product_year'  => $data['product_year'],
            'product_price' => $data['product_price'],
            'category_id'   => $data['category_id'] ?? null,
            'user_id'       => $request->user()->id, // owner
        ]);

        return redirect()
            ->route('products.show', $product)
            ->with('status', 'Product created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load(['productdetail', 'category', 'retailers', 'comments.user', ]);

        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Product $product)
    {
        if ($request->user()->id !== $product->user_id && !$request->user()->isAdmin()) {
            abort(403, 'You are not allowed to edit this product.');
        }

        $categories = Category::all();

        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        if ($request->user()->id !== $product->user_id && !$request->user()->isAdmin()) {
        abort(403, 'You are not allowed to edit this product.');
    }

    $data = $request->validate([
            'product_name'  => 'required|string|max:255',
            'product_year'  => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'product_price' => 'required|numeric|min:0',
            'category_id'   => 'nullable|exists:categories,id',
        ]);

        $product->update($data);

        return redirect()
            ->route('products.show', $product)
            ->with('status', 'Product updated.');
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Product $product)
    {
        if ($request->user()->id !== $product->user_id && !$request->user()->isAdmin()) {
        abort(403, 'You are not allowed to delete this product.');
    }

    $product->delete();

    return redirect()->route('products.index')->with('status', 'Product deleted.');
    }
}
