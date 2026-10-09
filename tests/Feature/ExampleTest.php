<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\StoreProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        StoreProfile::create([
            'store_name' => 'OLDWEAR.SCND',
            'whatsapp' => '6281234567890',
        ]);

        Category::create([
            'name' => 'Kaos',
            'description' => 'Kategori Kaos',
        ]);
    }

    public function test_public_pages_render_successfully(): void
    {
        $category = Category::first();
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Adidas Vintage Tee',
            'price' => 150000,
            'condition' => 'Sangat Baik',
            'stock' => 1,
            'status' => 'available',
        ]);

        $this->get('/')->assertStatus(200);
        $this->get('/products')->assertStatus(200);
        $this->get('/products/' . $product->slug)->assertStatus(200)->assertSee('galleryTrack');
        $this->get('/categories')->assertStatus(200);
        $this->get('/categories/kaos')->assertStatus(200);
        $this->get('/about')->assertStatus(200);
        $this->get('/testimonials')->assertStatus(200);
        $this->get('/contact')->assertStatus(200);
        $this->get('/login')->assertStatus(200);
        $this->get('/register')->assertStatus(200);
    }

    public function test_guest_is_redirected_from_admin_pages(): void
    {
        $this->get('/admin/dashboard')->assertRedirect('/login');
        $this->get('/admin/products')->assertRedirect('/login');
        $this->get('/admin/products/create')->assertRedirect('/login');
    }

    public function test_regular_user_cannot_access_admin_pages(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_USER]);

        $this->actingAs($user)->get('/admin/dashboard')->assertStatus(403);
        $this->actingAs($user)->get('/admin/products/create')->assertStatus(403);
    }

    public function test_admin_can_access_all_admin_pages(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)->get('/admin/dashboard')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/products')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/products/create')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/categories')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/categories/create')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/testimonials')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/testimonials/create')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/store-profile')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/banner')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/profile')->assertStatus(200);
    }

    public function test_admin_can_store_product_without_photo(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $category = Category::first();

        $response = $this->actingAs($admin)->post('/admin/products', [
            'name' => 'Vintage Polo Shirt',
            'category_id' => $category->id,
            'price' => 120000,
            'condition' => 'Sangat Baik',
            'size' => 'L',
            'color' => 'Navy',
            'stock' => 1,
            'status' => 'available',
            'description' => 'Kondisi mulus tanpa minus.',
        ]);

        $response->assertRedirect('/admin/products');
        $this->assertDatabaseHas('products', [
            'name' => 'Vintage Polo Shirt',
            'price' => 120000,
        ]);
    }

    public function test_admin_can_store_and_update_product_with_photo(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $category = Category::first();

        $file = \Illuminate\Http\UploadedFile::fake()->create('tracktop.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($admin)->post('/admin/products', [
            'name' => 'Tracktop ADIDAS Firebird',
            'category_id' => $category->id,
            'price' => 499000,
            'condition' => 'Sangat Baik',
            'size' => 'XL',
            'color' => 'Navy',
            'stock' => 1,
            'status' => 'available',
            'description' => 'Nominus mulus',
            'image' => $file,
        ]);

        $response->assertRedirect('/admin/products');
        $product = Product::where('name', 'Tracktop ADIDAS Firebird')->first();
        $this->assertNotNull($product);
        $this->assertNotNull($product->image);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($product->image);
    }

    public function test_admin_can_store_and_edit_testimonial(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $photo = \Illuminate\Http\UploadedFile::fake()->create('bukti_chat.jpg', 150, 'image/jpeg');

        $res = $this->actingAs($admin)->post('/admin/testimonials', [
            'name' => 'Budi Santoso',
            'rating' => 5,
            'message' => 'Barang thrift sangat terawat dan sesuai deskripsi!',
            'photo' => $photo,
            'is_active' => '1',
        ]);

        $res->assertRedirect('/admin/testimonials');
        $this->assertDatabaseHas('testimonials', [
            'name' => 'Budi Santoso',
            'message' => 'Barang thrift sangat terawat dan sesuai deskripsi!',
        ]);

        $testimonial = \App\Models\Testimonial::first();
        $this->assertNotNull($testimonial->photo);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($testimonial->photo);

        $this->actingAs($admin)->get("/admin/testimonials/{$testimonial->id}/edit")->assertStatus(200);

        // Verify public testimonials page displays the proof photo
        $this->get('/testimonials')
            ->assertStatus(200)
            ->assertSee('testimonial-media')
            ->assertSee('Bukti Transaksi');
    }

    public function test_admin_can_store_and_edit_category(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $res = $this->actingAs($admin)->post('/admin/categories', [
            'name' => 'Jaket Vintage',
            'description' => 'Koleksi jaket windbreaker dan varsity vintage',
        ]);

        $res->assertRedirect('/admin/categories');
        $this->assertDatabaseHas('categories', [
            'name' => 'Jaket Vintage',
        ]);

        $category = Category::where('name', 'Jaket Vintage')->first();
        $this->actingAs($admin)->get("/admin/categories/{$category->slug}/edit")->assertStatus(200);
    }

    public function test_admin_can_update_owner_photo_and_profile_and_view_on_about_page(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $photo = \Illuminate\Http\UploadedFile::fake()->create('owner.jpg', 150, 'image/jpeg');

        $res = $this->actingAs($admin)->put('/admin/store-profile', [
            'store_name' => 'OLDWEAR.SCND',
            'whatsapp' => '081234567890',
            'owner_name' => 'Ronny Soeharto',
            'owner_role' => 'Founder & Owner',
            'owner_bio' => 'Senang berbagi inspirasi fashion thrift berkualitas.',
            'owner_photo' => $photo,
            'address' => 'Jl. Kaliurang KM 5, Yogyakarta',
            'maps_url' => 'https://maps.app.goo.gl/example123',
        ]);

        $res->assertSessionHas('success');

        $store = StoreProfile::current();
        $this->assertEquals('Ronny Soeharto', $store->owner_name);
        $this->assertEquals('Founder & Owner', $store->owner_role);
        $this->assertEquals('Senang berbagi inspirasi fashion thrift berkualitas.', $store->owner_bio);
        $this->assertEquals('https://maps.app.goo.gl/example123', $store->maps_url);
        $this->assertNotNull($store->owner_photo);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($store->owner_photo);

        // Verify About page displays owner information, photo, and Google Maps button
        $this->get('/about')
            ->assertStatus(200)
            ->assertSee('Ronny Soeharto')
            ->assertSee('Founder & Owner')
            ->assertSee('Senang berbagi inspirasi fashion thrift berkualitas.')
            ->assertSee('owner-card')
            ->assertSee('Buka di Google Maps')
            ->assertSee('https://maps.app.goo.gl/example123');

        // Verify Contact page displays Google Maps button
        $this->get('/contact')
            ->assertStatus(200)
            ->assertSee('Buka Lokasi di Google Maps')
            ->assertSee('https://maps.app.goo.gl/example123');

        // Verify admin can remove owner photo
        $removeRes = $this->actingAs($admin)->put('/admin/store-profile', [
            'store_name' => 'OLDWEAR.SCND',
            'whatsapp' => '081234567890',
            'owner_name' => 'Ronny Soeharto',
            'remove_owner_photo' => '1',
        ]);
        $removeRes->assertSessionHas('success');

        $store->refresh();
        $this->assertNull($store->owner_photo);
    }
}
