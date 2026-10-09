<?php

namespace Tests\Feature;

use App\Models\StoreProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        StoreProfile::create([
            'store_name' => 'OLDWEAR.SCND',
            'whatsapp' => '6281234567890',
        ]);
    }

    public function test_login_page_renders_google_login_button(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Masuk dengan Google');
        $response->assertSee(route('auth.google.redirect'));
    }

    public function test_google_redirect_without_config_redirects_with_warning(): void
    {
        config(['services.google.client_id' => null]);
        config(['services.google.client_secret' => null]);

        $response = $this->get(route('auth.google.redirect'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error');
    }

    public function test_google_callback_creates_and_authenticates_new_user(): void
    {
        $mockGoogleUser = Mockery::mock(SocialiteUser::class);
        $mockGoogleUser->shouldReceive('getId')->andReturn('google-id-12345');
        $mockGoogleUser->shouldReceive('getName')->andReturn('Google User');
        $mockGoogleUser->shouldReceive('getEmail')->andReturn('googleuser@example.com');
        $mockGoogleUser->shouldReceive('getAvatar')->andReturn('https://lh3.googleusercontent.com/avatar.jpg');

        $provider = Mockery::mock('Laravel\Socialite\Two\GoogleProvider');
        $provider->shouldReceive('user')->andReturn($mockGoogleUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('home'));
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => 'googleuser@example.com',
            'name' => 'Google User',
            'google_id' => 'google-id-12345',
            'role' => User::ROLE_USER,
        ]);
    }

    public function test_google_callback_existing_admin_redirects_to_dashboard(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@oldwear.test',
            'role' => User::ROLE_ADMIN,
        ]);

        $mockGoogleUser = Mockery::mock(SocialiteUser::class);
        $mockGoogleUser->shouldReceive('getId')->andReturn('google-admin-999');
        $mockGoogleUser->shouldReceive('getName')->andReturn('Admin Oldwear');
        $mockGoogleUser->shouldReceive('getEmail')->andReturn('admin@oldwear.test');
        $mockGoogleUser->shouldReceive('getAvatar')->andReturn('https://lh3.googleusercontent.com/admin.jpg');

        $provider = Mockery::mock('Laravel\Socialite\Two\GoogleProvider');
        $provider->shouldReceive('user')->andReturn($mockGoogleUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);

        $this->assertDatabaseHas('users', [
            'email' => 'admin@oldwear.test',
            'google_id' => 'google-admin-999',
        ]);
    }
}
