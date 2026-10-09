<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Testimonial;
use App\Models\User;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $stats = [
            'products' => Product::count(),
            'available' => Product::available()->count(),
            'sold' => Product::where(fn ($q) => $q->where('status', Product::STATUS_SOLD)->orWhere('stock', '<=', 0))->count(),
            'categories' => Category::count(),
            'testimonials' => Testimonial::count(),
            'users' => User::where('role', User::ROLE_USER)->count(),
        ];

        $latestProducts = Product::with('category')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'latestProducts'));
    }
}
