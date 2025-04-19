<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
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

    public function edit_password()
    {
        $user = Auth::user();
        return view('profile.edit_password', compact('user'));
    }

    public function update_password(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'password_lama' => 'required|min:6',
                'password'      => 'required|min:6'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,    // respon json, true: berhasil, false: gagal 
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()  // menunjukkan field mana yang error 
                ]);
            }

            $check = UserModel::find($id);
            if ($check) {
                if (Hash::check($request->password_lama, $check->password)) {
                    $check->password = Hash::make($request->password);
                    $check->save();
                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil diupdate'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Password yang anda masukkan salah!'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }
}
