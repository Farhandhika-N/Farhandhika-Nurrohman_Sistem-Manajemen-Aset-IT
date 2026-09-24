<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh akun lain.',
        ]);

        $user->update($request->only('name', 'email'));

        return redirect()->route('profile.edit')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'current_password.current_password' => 'Password lama yang Anda masukkan salah.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak sama.',
        ]);

        $request->user()->update(['password' => $request->password]);

        return redirect()->route('profile.edit')
            ->with('success', 'Password berhasil diganti. Gunakan password baru Anda untuk login berikutnya.');
    }
}
