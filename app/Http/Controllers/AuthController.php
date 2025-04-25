<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        // Redirect ke dashboard jika pengguna sudah login
        if (Auth::check()) {
            return redirect()->intended(auth()->user()->isOwner() ? 'owner/dashboard' : 'admin/dashboard');
        }
        
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi kredensial login
        $credentials = $this->validateLogin($request);

        // Coba untuk login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Menampilkan pesan sukses menggunakan SweetAlert
            return redirect()->intended(auth()->user()->isOwner() ? 'owner/dashboard' : 'admin/dashboard')
                             ->with('success','Berhasil login');
        }

        // Menampilkan pesan kesalahan jika login gagal
        return back()->withErrors(['email' => __('auth.failed')]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login')->with('success', 'berhasil logout');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validasi input pendaftaran
        $validatedData = $this->validateRegistration($request);

        // Buat pengguna baru
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'role' => $validatedData['role'],
        ]);

        // Login pengguna
        Auth::login($user);

        // Redirect berdasarkan role dan menampilkan pesan sukses
        return redirect()->route($user->isOwner() ? 'owner.dashboard' : 'admin.dashboard')
                         ->with('success', 'berhasil mendaftar akun');
    }

    protected function validateLogin(Request $request)
    {
        return $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Alamat email wajib diisi',
            'email.email' => 'Format alamat email tidak valid',
            'password.required' => 'Password wajib diisi',
        ]);
    }

    protected function validateRegistration(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,owner',
        ], [
            'name.required' => __('validation.required', ['attribute' => 'nama']),
            'email.required' => __('validation.required', ['attribute' => 'email']),
            'email.email' => __('validation.email', ['attribute' => 'email']),
            'email.unique' => __('validation.unique', ['attribute' => 'email']),
            'password.required' => __('validation.required', ['attribute' => 'password']),
            'password.min' => __('validation.min.string', ['attribute' => 'password', 'min' => 8]),
            'password.confirmed' => __('validation.confirmed'),
            'role.required' => __('validation.required', ['attribute' => 'role']),
            'role.in' => __('validation.in', ['attribute' => 'role']),
        ]);
    }
}