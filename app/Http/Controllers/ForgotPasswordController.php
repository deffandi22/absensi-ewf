<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class ForgotPasswordController extends Controller
{
    public function showRequest()
    {
        return view('auth.forgot-password');
    }

    public function sendTemporaryPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak terdaftar pada sistem.',
        ]);

        $user = User::where('email', $validated['email'])->firstOrFail();

        $oldPassword = $user->password;
        $temporaryPassword = Str::random(10);

        try {
            $user->update([
                'password' => Hash::make($temporaryPassword),
            ]);

            Mail::raw(
                "Halo {$user->name},\n\n" .
                "Password sementara akun absensi Anda adalah: {$temporaryPassword}\n\n" .
                "Silakan login menggunakan password tersebut. Demi keamanan, segera ubah password setelah berhasil masuk.\n\n" .
                "Terima kasih.",
                function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('Password Sementara Sistem Absensi');
                }
            );

            return redirect()
                ->route('login')
                ->with('success', 'Password sementara berhasil dikirim ke email terdaftar.');
        } catch (Throwable $e) {
            $user->update([
                'password' => $oldPassword,
            ]);

            return back()
                ->withInput()
                ->withErrors([
                    'email' => 'Gagal mengirim email password sementara. Periksa konfigurasi email sistem.',
                ]);
        }
    }
}