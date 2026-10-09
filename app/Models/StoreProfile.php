<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class StoreProfile extends Model
{
    protected $fillable = [
        'store_name', 'tagline', 'description', 'logo', 'banner', 'banner_badge', 'banner_text',
        'owner_name', 'owner_photo', 'owner_role', 'owner_bio', 'founded_year', 'product_focus', 'advantages', 'whatsapp', 'whatsapp_greeting',
        'instagram', 'address', 'maps_url', 'email',
    ];

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('store_profile');
            static::$resolved = null;
        });
    }

    protected static ?self $resolved = null;

    /** Ambil profil toko (single row). Dibuat default jika belum ada. */
    public static function current(): self
    {
        if (static::$resolved) {
            return static::$resolved;
        }
        if (! Schema::hasTable('store_profiles')) {
            return new self(['store_name' => config('app.name'), 'whatsapp' => '6280000000000']);
        }

        return static::$resolved = static::first() ?? static::create([
            'store_name' => 'OLDWEAR.SCND',
            'whatsapp' => '6280000000000',
        ]);
    }

    /** Normalisasi nomor: hanya digit, 08xx -> 628xx. */
    public static function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone);
        if (str_starts_with($digits, '0')) {
            $digits = '62'.substr($digits, 1);
        }

        return $digits;
    }

    /** Buat link wa.me dengan pesan yang di-URL-encode (rawurlencode => spasi jadi %20, newline %0A). */
    public function whatsappLink(?string $message = null): string
    {
        $url = 'https://wa.me/'.self::normalizePhone((string) $this->whatsapp);

        return $message ? $url.'?text='.rawurlencode($message) : $url;
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo ? asset('storage/' . ltrim($this->logo, '/')) : null;
    }

    public function getBannerUrlAttribute(): ?string
    {
        return $this->banner ? asset('storage/' . ltrim($this->banner, '/')) : null;
    }

    public function getOwnerPhotoUrlAttribute(): ?string
    {
        return $this->owner_photo ? asset('storage/' . ltrim($this->owner_photo, '/')) : null;
    }

    public function getAdvantagesListAttribute(): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\r\n|\n|\r/', (string) $this->advantages))));
    }

    public function getInstagramUrlAttribute(): ?string
    {
        if (! $this->instagram) {
            return null;
        }
        if (str_starts_with($this->instagram, 'http')) {
            return $this->instagram;
        }

        return 'https://instagram.com/'.ltrim($this->instagram, '@');
    }

    public function getInstagramHandleAttribute(): ?string
    {
        if (! $this->instagram) {
            return null;
        }

        return '@'.ltrim(basename(rtrim($this->instagram, '/')), '@');
    }

    public function getGoogleMapsUrlAttribute(): ?string
    {
        if ($this->maps_url) {
            $url = trim($this->maps_url);
            if (! preg_match('~^https?://~i', $url)) {
                $url = 'https://' . $url;
            }
            return $url;
        }

        if ($this->address) {
            return 'https://www.google.com/maps/search/?api=1&query=' . urlencode(trim($this->address));
        }

        return null;
    }
}
