<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user(); // ambil user yang sedang login
        $activeMenu = 'profile'; // set menu yang aktif
        $breadcrumb = (object) [
            'title' => 'Profil Saya',
            'list'  => ['Home', 'Profil Saya']
        ];

        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];

        return view('profile.index', compact('user', 'activeMenu', 'breadcrumb', 'page'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }


    public function update(Request $request)
    {
        $request->validate([
            'picture' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $user = Auth::user();

        // Simpan file baru
        if ($request->hasFile('picture')) {
            $filename = 'photo_' . $user->user_id . '.' . $request->file('picture')->getClientOriginalExtension();
            $path = $request->file('picture')->storeAs('storage/profile_images', $filename);

            $user->picture = $filename;
            $user->save();
        }

        return redirect()->back()->with('success', 'Foto profil berhasil diperbarui.');
    }
}
