<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;


class UserController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->when(request('name'), function($query) {
                $query->where('name', 'like', '%'.request('name').'%');
            })
            ->when(request('email'), function($query) {
                $query->where('email', 'like', '%'.request('email').'%');
            })
            ->when(request('role'), function($query) {
                $query->where('role', request('role'));
            })
            ->orderBy(request('sort_by', 'id'), request('sort_direction', 'desc'))
            ->paginate(10);

        return view('user.index', compact('users'));
    }

    public function create(){
        return view('user.create');
    }

    public function store(Request $request)
    {
        // Validasi input
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

        // Membuat pengguna baru
        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']), // Hash password sebelum disimpan
            'role' => $data['role'],
        ]);

        // Redirect ke halaman pengguna dengan pesan sukses
        return redirect()->route('user.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    // Menampilkan detail pengguna
    public function show(User $user)
    {
        return view('user.show', compact('user'));
    }

    // Menampilkan form untuk mengedit pengguna
    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }

    // Memperbarui pengguna
public function update(Request $request, User $user)
{
    $data = $request->validate([
        'name' => 'required|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'role' => 'required|in:admin,owner',
        'password' => 'nullable|confirmed|min:8', // Menambahkan validasi untuk password
    ], [
        'name.required' => 'Nama lengkap harus diisi',
        'email.required' => 'Email harus diisi',
        'email.email' => 'Format email tidak valid',
        'email.unique' => 'Email sudah terdaftar',
        'role.required' => 'Pilih role pengguna',
        'role.in' => 'Role tidak valid',
        'password.confirmed' => 'Konfirmasi password tidak cocok',
        'password.min' => 'Password minimal 8 karakter',
    ]);

    // Memperbarui data pengguna
    $user->name = $data['name'];
    $user->email = $data['email'];
    $user->role = $data['role'];

    // Hanya memperbarui password jika diisi
    if (!empty($data['password'])) {
        $user->password = Hash::make($data['password']);
    }

    $user->save(); // Simpan perubahan

    return redirect()->route('user.index')->with('success', 'Pengguna berhasil diperbarui.');
}


    public function destroy(User $user)
    {
        $user->delete(); // Hapus pengguna
        return redirect()->route('user.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
