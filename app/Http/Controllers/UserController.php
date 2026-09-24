<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // 1. READ: Daftar akun user + pencarian
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(10);
        $users->appends($request->all());

        return view('users.index', compact('users'));
    }

    // 2. CREATE: Form tambah user baru
    public function create()
    {
        return view('users.create');
    }

    // 3. STORE: Simpan user baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'role' => ['required', Rule::in(['admin', 'staff'])],
            'password' => 'required|min:8|confirmed',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar di sistem.',
            'role.required' => 'Silakan pilih role user.',
            'role.in' => 'Role user tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Akun user berhasil ditambahkan.');
    }

    // 4. EDIT: Form ubah user
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // 5. UPDATE: Perbarui user (password dikosongkan agar tidak ikut berubah)
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'staff'])],
            'password' => 'nullable|min:8|confirmed',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh akun lain.',
            'role.required' => 'Silakan pilih role user.',
            'role.in' => 'Role user tidak valid.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
        ]);

        $data = $request->only('name', 'email', 'role');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'Akun user berhasil diperbarui.');
    }

    // 6. DESTROY: Hapus user dengan pengaman akun sendiri & admin terakhir
    public function destroy(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun yang sedang digunakan.');
        }

        if ($user->role === 'admin' && User::where('role', 'admin')->count() === 1) {
            return back()->with('error', 'Tidak dapat menghapus akun admin terakhir di sistem.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Akun user berhasil dihapus.');
    }
}
