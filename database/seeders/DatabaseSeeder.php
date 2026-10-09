<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\StoreProfile;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ---------- Admin Accounts ----------
        // Buat akun admin untuk akses panel admin
        $admin = User::firstOrNew(['email' => 'admin@oldwear.local']);
        $admin->name = 'Admin OLDWEAR';
        $admin->password = 'password';
        $admin->role = User::ROLE_ADMIN;
        $admin->save();

        // Akun admin cadangan
        $admin2 = User::firstOrNew(['email' => 'admin@example.com']);
        $admin2->name = 'Admin';
        $admin2->password = 'password';
        $admin2->role = User::ROLE_ADMIN;
        $admin2->save();

        // ---------- Profil Toko ----------
        StoreProfile::query()->delete();
        StoreProfile::create([
            'store_name' => 'OLDWEAR.SCND',
            'tagline' => 'Second hand, first class.',
            'description' => 'OLDWEAR.SCND adalah thrift shop yang mengkurasi pakaian vintage dan second hand berkualitas. Setiap item dicek dan dijelaskan dengan jujur agar kamu bisa tampil percaya diri.',
            'owner_name' => 'Ronny',
            'founded_year' => 2021,
            'product_focus' => 'Pakaian vintage & thrift curated untuk pria dan wanita',
            'advantages' => "Semua item dikurasi & dicek kondisinya\nFoto asli tanpa filter berlebihan\nHarga bersahabat untuk produk pilihan\nFast response pemesanan via WhatsApp",
            'whatsapp' => '6281234567890',
            'whatsapp_greeting' => 'Halo OLDWEAR.SCND, saya tertarik membeli produk:',
            'instagram' => 'oldwear.scnd',
            'address' => 'Jakarta, Indonesia',
            'email' => 'hello@oldwear.local',
        ]);

        // ---------- Kategori Siap Pakai ----------
        // Kategori disediakan agar admin langsung bisa memilih kategori saat menambahkan produk
        $categories = [
            ['Kaos', 'Kaos vintage, band tee, dan basic tee pilihan.'],
            ['Kemeja', 'Kemeja flanel, denim, dan kemeja kasual klasik.'],
            ['Hoodie', 'Hoodie & zip hoodie hangat dengan karakter.'],
            ['Jaket', 'Jaket corduroy, varsity, denim, dan outer lainnya.'],
            ['Celana', 'Cargo, jeans, chino, dan celana vintage.'],
            ['Sweater', 'Sweater rajut dan crewneck vintage.'],
            ['Lainnya', 'Aksesoris dan item unik lainnya.'],
        ];

        foreach ($categories as [$name, $desc]) {
            Category::updateOrCreate(['name' => $name], ['description' => $desc]);
        }

        // ---------- Hapus Semua Produk Dummy ----------
        // Sesuai permintaan, produk dummy dihapus agar katalog bersih dan hanya diisi oleh Admin
        ProductImage::query()->delete();
        Product::query()->delete();

        // Bersihkan file storage produk jika ada
        Storage::disk('public')->deleteDirectory('products');
        Storage::disk('public')->makeDirectory('products');
        Storage::disk('public')->makeDirectory('products/gallery');

        // ---------- Testimoni Kosong ----------
        Testimonial::query()->delete();
    }
}
