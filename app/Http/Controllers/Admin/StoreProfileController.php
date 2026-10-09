<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreProfileController extends Controller
{
    public function edit()
    {
        return view('admin.store-profile', ['store' => StoreProfile::current()]);
    }

    public function update(Request $request)
    {
        $store = StoreProfile::current();

        $data = $request->validate([
            'store_name' => ['required', 'string', 'max:100'],
            'tagline' => ['nullable', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:3000'],
            'owner_name' => ['nullable', 'string', 'max:100'],
            'owner_role' => ['nullable', 'string', 'max:100'],
            'owner_bio' => ['nullable', 'string', 'max:1000'],
            'owner_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'founded_year' => ['nullable', 'integer', 'min:1900', 'max:'.date('Y')],
            'product_focus' => ['nullable', 'string', 'max:200'],
            'advantages' => ['nullable', 'string', 'max:2000'],
            'whatsapp' => ['required', 'string', 'max:30'],
            'whatsapp_greeting' => ['nullable', 'string', 'max:300'],
            'instagram' => ['nullable', 'string', 'max:150'],
            'address' => ['nullable', 'string', 'max:255'],
            'maps_url' => ['nullable', 'string', 'max:500'],
            'email' => ['nullable', 'email', 'max:150'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [], [
            'store_name' => 'nama toko',
            'founded_year' => 'tahun berdiri',
            'owner_name' => 'nama pemilik',
            'owner_photo' => 'foto pemilik',
            'maps_url' => 'link Google Maps',
        ]);

        $data['whatsapp'] = StoreProfile::normalizePhone($data['whatsapp']);

        if ($request->hasFile('logo')) {
            if ($store->logo && Storage::disk('public')->exists($store->logo)) {
                Storage::disk('public')->delete($store->logo);
            }
            $data['logo'] = $request->file('logo')->store('store', 'public');
        } else {
            unset($data['logo']);
        }

        if ($request->hasFile('owner_photo')) {
            if ($store->owner_photo && Storage::disk('public')->exists($store->owner_photo)) {
                Storage::disk('public')->delete($store->owner_photo);
            }
            $data['owner_photo'] = $request->file('owner_photo')->store('store/owner', 'public');
        } elseif ($request->boolean('remove_owner_photo') && $store->owner_photo) {
            if (Storage::disk('public')->exists($store->owner_photo)) {
                Storage::disk('public')->delete($store->owner_photo);
            }
            $data['owner_photo'] = null;
        } else {
            unset($data['owner_photo']);
        }

        $store->update($data);

        return back()->with('success', 'Profil toko berhasil diperbarui.');
    }

    public function editBanner()
    {
        return view('admin.banner', ['store' => StoreProfile::current()]);
    }

    public function updateBanner(Request $request)
    {
        $store = StoreProfile::current();

        $data = $request->validate([
            'banner' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'banner_badge' => ['nullable', 'string', 'max:100'],
            'banner_text' => ['nullable', 'string', 'max:150'],
        ], [], [
            'banner' => 'foto banner',
            'banner_badge' => 'label badge banner',
            'banner_text' => 'keterangan badge',
        ]);

        if ($request->hasFile('banner')) {
            if ($store->banner && Storage::disk('public')->exists($store->banner)) {
                Storage::disk('public')->delete($store->banner);
            }
            $data['banner'] = $request->file('banner')->store('store/banners', 'public');
        } else {
            unset($data['banner']);
        }

        $store->update($data);

        return back()->with('success', 'Banner toko berhasil diperbarui.');
    }

    public function destroyBanner()
    {
        $store = StoreProfile::current();

        if ($store->banner && Storage::disk('public')->exists($store->banner)) {
            Storage::disk('public')->delete($store->banner);
        }

        $store->update([
            'banner' => null,
            'banner_badge' => null,
            'banner_text' => null,
        ]);

        return back()->with('success', 'Banner toko berhasil dihapus.');
    }

    // Tetap sediakan jika ada link lama
    public function editWhatsapp()
    {
        return redirect()->route('admin.store-profile.edit');
    }

    public function updateWhatsapp(Request $request)
    {
        return redirect()->route('admin.store-profile.edit');
    }
}
