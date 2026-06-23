<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OfficeNetworkMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $clientIp = $this->getRealClientIp($request);

        /*
         * IP lokal untuk kebutuhan development.
         * 127.0.0.1 dan ::1 digunakan ketika akses dari laptop sendiri.
         */
        $localIps = [
            '127.0.0.1',
            '::1',
        ];

        if (in_array($clientIp, $localIps)) {
            return $next($request);
        }

        /*
         * Saat APP_ENV=local, izinkan IP private network.
         * Ini berguna ketika testing dari HP yang satu WiFi dengan laptop/server lokal.
         * Contoh IP HP biasanya 192.168.x.x.
         */
        if (app()->environment('local') && $this->isPrivateIp($clientIp)) {
            return $next($request);
        }

        $setting = Setting::first();

        /*
         * Jika setting IP belum diisi, akses tetap dibuka agar sistem tidak terkunci saat development.
         * Saat sistem sudah siap digunakan, office_ip sebaiknya diisi dengan IP jaringan kantor.
         */
        if (!$setting || !$setting->office_ip) {
            return $next($request);
        }

        /*
         * office_ip bisa diisi satu IP atau beberapa IP dipisah koma.
         * Contoh:
         * 103.xxx.xxx.xxx
         * 103.xxx.xxx.xxx,114.xxx.xxx.xxx
         */
        $allowedIps = array_map('trim', explode(',', $setting->office_ip));

        if (!in_array($clientIp, $allowedIps)) {
            return response()->view('errors.network-denied', [
                'clientIp' => $clientIp,
            ], 403);
        }

        return $next($request);
    }

    private function getRealClientIp(Request $request): string
    {
        /*
         * Saat aplikasi dideploy di Railway, request melewati proxy.
         * Karena itu request()->ip() bisa membaca IP internal Railway.
         * IP asli pengguna biasanya dikirim melalui header berikut.
         */
        $headers = [
            'CF-Connecting-IP',
            'X-Real-IP',
            'X-Forwarded-For',
        ];

        foreach ($headers as $header) {
            $value = $request->header($header);

            if (!$value) {
                continue;
            }

            /*
             * X-Forwarded-For bisa berisi beberapa IP:
             * IP_USER, IP_PROXY_1, IP_PROXY_2
             * Yang kita ambil adalah IP pertama.
             */
            $ips = explode(',', $value);
            $ip = trim($ips[0]);

            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }

        return $request->ip();
    }

    private function isPrivateIp(string $ip): bool
    {
        return filter_var(
            $ip,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        ) === false;
    }
}