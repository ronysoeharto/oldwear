<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    public const STATUS_AVAILABLE = 'available';
    public const STATUS_SOLD = 'sold';

    public const CONDITIONS = ['Baru dengan Tag', 'Like New', 'Sangat Baik', 'Baik', 'Cukup'];

    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'price', 'size',
        'color', 'condition', 'stock', 'image', 'status', 'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'stock' => 'integer',
            'is_featured' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Product $product) {
            if (blank($product->slug) || $product->isDirty('name')) {
                $product->slug = static::uniqueSlug($product->name, $product->id);
            }
            // Stok 0 => otomatis dianggap terjual
            if ((int) $product->stock <= 0) {
                $product->status = self::STATUS_SOLD;
            }
        });
    }

    public static function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'produk';
        $slug = $base;
        $i = 2;
        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    /* ---------- Scopes ---------- */

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_AVAILABLE)->where('stock', '>', 0);
    }

    /** Pencarian aman (parameter binding Eloquent) berdasarkan nama, deskripsi, dan kategori. */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        $term = trim((string) $term);
        if ($term === '') {
            return $query;
        }
        $like = '%'.addcslashes($term, '%_\\').'%';

        return $query->where(function (Builder $q) use ($like) {
            $q->where('name', 'like', $like)
                ->orWhere('description', 'like', $like)
                ->orWhereHas('category', fn (Builder $c) => $c->where('name', 'like', $like));
        });
    }

    /* ---------- Helpers ---------- */

    public function isSold(): bool
    {
        return $this->status === self::STATUS_SOLD || $this->stock <= 0;
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp'.number_format($this->price, 0, ',', '.');
    }

    public function getImageUrlAttribute(): string
    {
        if (! $this->image) {
            return asset('images/no-image.svg');
        }

        return asset('storage/' . ltrim($this->image, '/'));
    }

    /** Link WhatsApp "Beli Sekarang" dengan pesan otomatis yang sudah di-encode. */
    public function whatsappUrl(?StoreProfile $store = null): string
    {
        $store ??= StoreProfile::current();

        $lines = [
            ($store->whatsapp_greeting ?: 'Halo '.$store->store_name.', saya tertarik membeli produk:'),
            '',
            'Nama Produk: '.$this->name,
            'Harga: '.$this->formatted_price,
        ];
        if ($this->size) {
            $lines[] = 'Ukuran: '.$this->size;
        }
        if ($this->color) {
            $lines[] = 'Warna: '.$this->color;
        }
        $lines[] = 'Link: '.route('products.show', $this);
        $lines[] = '';
        $lines[] = 'Apakah produk ini masih tersedia?';

        return $store->whatsappLink(implode("\n", $lines));
    }
}
