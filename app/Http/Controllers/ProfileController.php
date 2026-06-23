<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Throwable;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();

        $relations = ['division', 'employee'];

        if (method_exists($user, 'leaderProfile')) {
            $relations[] = 'leaderProfile';
        }

        $user->load($relations);

        return view('profile.show', compact('user'));
    }

    public function edit()
    {
        $user = auth()->user();

        $relations = ['division', 'employee'];

        if (method_exists($user, 'leaderProfile')) {
            $relations[] = 'leaderProfile';
        }

        $user->load($relations);

        return view('profile.edit', compact('user'));
    }

    public function sendEmailOtp(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
        ], [
            'email.required' => 'Email baru wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh pengguna lain.',
        ]);

        if ($request->email === $user->email) {
            return back()
                ->withInput()
                ->with('warning', 'Email baru sama dengan email saat ini.');
        }

        $otp = random_int(100000, 999999);

        session([
            'profile_new_email' => $request->email,
            'profile_email_otp' => Hash::make($otp),
            'profile_email_otp_expires_at' => now()->addMinutes(10),
        ]);

        try {
            Mail::raw(
                "Kode OTP untuk mengubah email akun Anda adalah: {$otp}\n\n" .
                "Email lama: {$user->email}\n" .
                "Email baru: {$request->email}\n\n" .
                "Kode ini berlaku selama 10 menit.\n\n" .
                "Jika Anda tidak meminta perubahan email, segera abaikan proses ini dan jangan berikan kode OTP kepada siapa pun.",
                function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('Kode OTP Verifikasi Perubahan Email - Sistem Absensi EWF');
                }
            );
        } catch (\Throwable $e) {
            session()->forget([
                'profile_new_email',
                'profile_email_otp',
                'profile_email_otp_expires_at',
            ]);

            return back()
                ->withInput()
                ->with('warning', 'Kode OTP gagal dikirim ke email lama. Periksa konfigurasi email pada file .env.');
        }

        return back()
            ->withInput()
            ->with('success', 'Kode OTP berhasil dikirim ke email lama Anda: ' . $user->email);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validatedUser = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'email_otp' => ['nullable', 'digits:6'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh pengguna lain.',
            'email_otp.digits' => 'Kode OTP harus berisi 6 digit angka.',
            'phone.max' => 'Nomor telepon maksimal 20 karakter.',
            'address.max' => 'Alamat maksimal 255 karakter.',
            'profile_photo.image' => 'File harus berupa gambar.',
            'profile_photo.mimes' => 'Foto profil harus berformat JPG, JPEG, PNG, atau WEBP.',
            'profile_photo.max' => 'Ukuran foto profil maksimal 2 MB.',
        ]);

        if ($validatedUser['email'] !== $user->email) {
            if (!$request->filled('email_otp')) {
                return back()
                    ->withInput()
                    ->with('warning', 'Kode OTP wajib diisi untuk mengubah email.');
            }

            if (session('profile_new_email') !== $validatedUser['email']) {
                return back()
                    ->withInput()
                    ->with('warning', 'Email yang dimasukkan tidak sesuai dengan email tujuan OTP. Silakan kirim OTP ulang.');
            }

            if (!session('profile_email_otp') || !session('profile_email_otp_expires_at')) {
                return back()
                    ->withInput()
                    ->with('warning', 'Kode OTP belum dikirim. Silakan kirim OTP terlebih dahulu.');
            }

            if (now()->greaterThan(session('profile_email_otp_expires_at'))) {
                session()->forget([
                    'profile_new_email',
                    'profile_email_otp',
                    'profile_email_otp_expires_at',
                ]);

                return back()
                    ->withInput()
                    ->with('warning', 'Kode OTP sudah kedaluwarsa. Silakan kirim ulang OTP.');
            }

            if (!Hash::check($validatedUser['email_otp'], session('profile_email_otp'))) {
                return back()
                    ->withInput()
                    ->with('warning', 'Kode OTP salah.');
            }

            session()->forget([
                'profile_new_email',
                'profile_email_otp',
                'profile_email_otp_expires_at',
            ]);
        }

        $profilePhotoPath = $user->profile_photo;

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $profilePhotoPath = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        $user->update([
            'name' => $validatedUser['name'],
            'email' => $validatedUser['email'],
            'profile_photo' => $profilePhotoPath,
        ]);

        if ($user->employee) {
            $user->employee->update([
                'phone' => $validatedUser['phone'] ?? null,
                'address' => $validatedUser['address'] ?? null,
            ]);
        }

        if (method_exists($user, 'leaderProfile') && $user->leaderProfile) {
            $user->leaderProfile->update([
                'phone' => $validatedUser['phone'] ?? null,
                'address' => $validatedUser['address'] ?? null,
            ]);
        }

        return redirect('/profile')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    public function password()
    {
        return view('profile.password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak sesuai.',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Password lama tidak sesuai.'])
                ->withInput();
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect('/profile')
            ->with('success', 'Password berhasil diperbarui.');
    }
}