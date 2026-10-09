<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->search($request->string('q')->limit(100)->toString())
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status') === 'sold' ? 'sold' : 'available'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create', [
            'product' => new Product(['stock' => 1, 'status' => Product::STATUS_AVAILABLE]),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request, true);

        // Ambil file cover baik dari field 'image' maupun 'images'
        $mainFile = $request->file('image');
        if (! $mainFile && $request->hasFile('images')) {
            $imgs = $request->file('images');
            $mainFile = is_array($imgs) ? ($imgs[0] ?? null) : $imgs;
        }

        if ($mainFile && $mainFile->isValid()) {
            $data['image'] = $mainFile->store('products', 'public');
        } else {
            $data['image'] = null;
        }

        $product = Product::create($data);
        $this->storeGallery($request, $product);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', [
            'product' => $product->load('images'),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validated($request, false);

        // Ambil file cover jika di-upload
        $mainFile = $request->file('image');
        if (! $mainFile && $request->hasFile('images')) {
            $imgs = $request->file('images');
            $mainFile = is_array($imgs) ? ($imgs[0] ?? null) : $imgs;
        }

        if ($mainFile && $mainFile->isValid()) {
            $this->deleteFile($product->image);
            $data['image'] = $mainFile->store('products', 'public');
        }

        // Jika admin set status available tapi stok 0, model akan otomatis menjadikannya sold.
        $product->update($data);

        // Hapus foto galeri yang dicentang
        $removeIds = array_map('intval', (array) $request->input('remove_images', []));
        if ($removeIds) {
            $product->images()->whereIn('id', $removeIds)->get()->each(function (ProductImage $img) {
                $this->deleteFile($img->path);
                $img->delete();
            });
        }
        $this->storeGallery($request, $product);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $this->deleteFile($product->image);
        $product->images->each(fn ($img) => $this->deleteFile($img->path));
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }

    private function validated(Request $request, bool $creating): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'price' => ['required', 'integer', 'min:0', 'max:1000000000'],
            'size' => ['nullable', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'max:50'],
            'condition' => ['required', 'string', Rule::in(Product::CONDITIONS)],
            'stock' => ['required', 'integer', 'min:0', 'max:100000'],
            'status' => ['required', Rule::in([Product::STATUS_AVAILABLE, Product::STATUS_SOLD])],
            'description' => ['nullable', 'string', 'max:5000'],
            'is_featured' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'images' => ['nullable'],
            'images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'gallery' => ['nullable', 'array', 'max:6'],
            'gallery.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'remove_images' => ['nullable', 'array'],
            'remove_images.*' => ['integer'],
        ], [], [
            'name' => 'nama', 'category_id' => 'kategori', 'price' => 'harga', 'size' => 'ukuran',
            'color' => 'warna', 'condition' => 'kondisi', 'stock' => 'stok', 'image' => 'foto utama',
            'description' => 'deskripsi', 'gallery.*' => 'foto galeri',
        ]);

        $data['is_featured'] = $request->boolean('is_featured');
        unset($data['image'], $data['images'], $data['gallery'], $data['remove_images']);

        return $data;
    }

    private function storeGallery(Request $request, Product $product): void
    {
        $galleryFiles = (array) $request->file('gallery', []);
        if ($request->hasFile('images')) {
            $imgs = (array) $request->file('images');
            if (count($imgs) > 1 && ! $request->hasFile('image')) {
                array_shift($imgs);
                $galleryFiles = array_merge($galleryFiles, $imgs);
            }
        }

        foreach ($galleryFiles as $file) {
            if ($file && $file->isValid()) {
                $product->images()->create(['path' => $file->store('products/gallery', 'public')]);
            }
        }
    }

    private function deleteFile(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
