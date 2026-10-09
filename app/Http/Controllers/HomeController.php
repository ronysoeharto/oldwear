<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function __invoke()
    {
        $featured = Product::with('category')
            ->available()
            ->orderByDesc('is_featured')
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::withCount(['products' => fn ($q) => $q->available()])
            ->orderBy('name')
            ->get();

        $testimonials = Testimonial::active()->latest()->take(6)->get();

        return view('pages.home', compact('featured', 'categories', 'testimonials'));
    }
}
