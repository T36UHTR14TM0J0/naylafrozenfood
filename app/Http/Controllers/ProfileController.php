<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index(){
        $user = Auth::user();
       return view('profile.edit',compact('user')); 
    }

    /**
     * Memproses update data akun
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'fullname.required' => 'Nama lengkap wajib diisi',
            'email.required' => 'Alamat email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email ini sudah digunakan',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
        }

        $data = [
            'name' => $request->fullname,
            'email' => $request->email,
        ];

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('profile.edit')
                        ->with('success', 'Profil berhasil diperbarui!');
    }

}
