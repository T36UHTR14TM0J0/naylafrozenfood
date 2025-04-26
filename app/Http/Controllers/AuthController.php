<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->intended($this->redirectTo());
        }
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'     => 'required|email',
            'password'  => 'required',
        ], [
            'email.required'    => 'Email harus diisi',
            'email.email'       => 'Format email tidak valid',
            'password.required' => 'Password harus diisi',
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended($this->redirectTo())
                             ->with('success', 'Login berhasil');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah',
        ])->onlyInput('email');
    }

    /**
     * Show registration form
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|max:255',
            'email'         => 'required|email|unique:users',
            'password'      => ['required', 'confirmed', Rules\Password::min(8)
                                ->letters()
                                ->mixedCase()
                                ->numbers()
                                ->symbols()],
            'role'          => 'required|in:admin,owner',
        ], [
            'name.required'         => 'Nama lengkap harus diisi',
            'email.required'        => 'Email harus diisi',
            'email.email'           => 'Format email tidak valid',
            'email.unique'          => 'Email sudah terdaftar',
            'password.required'     => 'Password harus diisi',
            'password.confirmed'    => 'Konfirmasi password tidak cocok',
            'role.required'         => 'Pilih role pengguna',
            'role.in'               => 'Role tidak valid',
        ]);

        $user = User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => bcrypt($data['password']),
            'role'      => $data['role'],
        ]);

        Auth::login($user);

        return redirect($this->redirectTo())->with('success', 'Registrasi berhasil');
    }

    /**
     * Show forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot_password');
    }

    /**
     * Handle forgot password request
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email'             => 'required|email',
        ], [
            'email.required'    => 'Alamat email wajib diisi',
            'email.email'       => 'Format alamat email tidak valid',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with("success","Berhasil mengirim link reset password")
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Show password reset form
     */
    public function showResetPasswordForm($token)
    {
        $email = $_GET['email'];
        return view('auth.reset_password', ['token' => $token,'email' => $email]);
    }

    /**
     * Handle password reset request
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'             => 'required',
            'email'             => 'required|email',
            'password'          => ['required', 'confirmed', Rules\Password::min(8)],
        ], [
            'token.required'    => 'Token reset password wajib ada',
            'email.required'    => 'Alamat email wajib diisi',
            'email.email'       => 'Format alamat email tidak valid',
            'password.required' => 'Password baru wajib diisi',
            'password.confirmed'=> 'Konfirmasi password tidak cocok',
            'password.min'      => 'Password minimal harus 8 karakter',
        ]);
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', "Berhasil membuat password baru")
            : back()->withErrors(['email' => [__($status)]]);
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Anda berhasil logout');
    }

    /**
     * Determine redirect path based on user role
     */
    protected function redirectTo()
    {
        if (Auth::check()) {
            return 'dashboard';
        }
        return '/login';
    }
}