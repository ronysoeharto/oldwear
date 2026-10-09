<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Testimonial;

class PageController extends Controller
{
    public function categories()
    {
        $categories = Category::withCount(['products' => fn ($q) => $q->available()])
            ->orderBy('name')
            ->get();

        return view('pages.categories', compact('categories'));
    }

    public function about()
    {
        return view('pages.about');
    }

    public function testimonials()
    {
        $testimonials = Testimonial::active()->latest()->paginate(12);

        return view('pages.testimonials', compact('testimonials'));
    }

    public function contact()
    {
        return view('pages.contact');
    }
}
