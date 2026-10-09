<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request, ?Category $category = null)
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:120'],
            'min_price' => ['nullable', 'integer', 'min:0'],
            'max_price' => ['nullable', 'integer', 'min:0'],
            'sort' => ['nullable', 'in:newest,price_asc,price_desc'],
            'available' => ['nullable', 'boolean'],
        ]);

        $categorySlug = $category?->slug ?? ($filters['category'] ?? null);

        $products = Product::with('category')
            ->search($filters['q'] ?? null)
            ->when($categorySlug, fn ($q) => $q->whereHas('category', fn ($c) => $c->where('slug', $categorySlug)))
            ->when(isset($filters['min_price']), fn ($q) => $q->where('price', '>=', $filters['min_price']))
            ->when(isset($filters['max_price']), fn ($q) => $q->where('price', '<=', $filters['max_price']))
            ->when(! empty($filters['available']), fn ($q) => $q->available())
            ->when(($filters['sort'] ?? 'newest'), function ($q, $sort) {
                return match ($sort) {
                    'price_asc' => $q->orderBy('price'),
                    'price_desc' => $q->orderByDesc('price'),
                    default => $q->orderByRaw("case when status = 'available' and stock > 0 then 0 else 1 end")->latest(),
                };
            })
            ->paginate(12)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('pages.products.index', [
            'products' => $products,
            'categories' => $categories,
            'activeCategory' => $category ?? $categories->firstWhere('slug', $categorySlug),
            'filters' => $filters,
        ]);
    }

    public function show(Product $product)
    {
        $product->load(['category', 'images']);

        $related = Product::with('category')
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->id)
            ->available()
            ->latest()
            ->take(4)
            ->get();

        return view('pages.products.show', compact('product', 'related'));
    }
}
