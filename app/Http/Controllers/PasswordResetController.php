<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetController extends Controller
{
    // Form lupa password (guest)
    public function create()
    {
        return view('auth.forgot-password');
    }

    // Kirim tautan reset ke email
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'Tautan reset password telah dikirim ke email Anda. Periksa kotak masuk atau folder spam.');
        }

        $pesan = match ($status) {
            Password::INVALID_USER => 'Email tidak terdaftar di sistem.',
            default => 'Terlalu banyak permintaan. Silakan tunggu beberapa menit lalu coba lagi.',
        };

        return back()->withErrors(['email' => $pesan])->onlyInput('email');
    }

    // Form reset password (membawa token dari tautan di email)
    public function edit(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    // Simpan password baru
    public function update(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ], [
            'token.required' => 'Token reset tidak valid. Minta tautan baru.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak sama.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill(['password' => $password])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')
                ->with('status', 'Password berhasil diubah. Silakan login dengan password baru Anda.');
        }

        return back()->withErrors([
            'email' => 'Tautan reset tidak valid atau sudah kedaluwarsa. Silakan minta tautan baru.',
        ])->onlyInput('email');
    }
}
