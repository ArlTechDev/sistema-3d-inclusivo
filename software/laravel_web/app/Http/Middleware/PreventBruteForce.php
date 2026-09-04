<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class PreventBruteForce
{
    private int $maxAttempts = 5;
    private int $lockoutMinutes = 15;

    public function handle(Request $request, Closure $next): HttpResponse
    {
        $ip = $request->ip();
        $blockedUntil = DB::table('failed_logins')
            ->where('ip_address', $ip)
            ->where('blocked_until', '>', now())
            ->value('blocked_until');

        if ($blockedUntil) {
            $secondsRemaining = now()->diffInSeconds($blockedUntil);
            return Response::json([
                'message' => 'Demasiados intentos fallidos. Intente nuevamente en ' . $secondsRemaining . ' segundos.',
            ], HttpResponse::HTTP_TOO_MANY_REQUESTS);
        }

        $response = $next($request);

        if ($request->is('login') && $request->isMethod('POST')) {
            if ($response->isRedirect()) {
                $this->clearAttempts($ip);
            } elseif ($response->getStatusCode() === HttpResponse::HTTP_FOUND) {
                $this->recordFailedAttempt($ip, $request->input('email'));
            }
        }

        return $response;
    }

    private function recordFailedAttempt(string $ip, ?string $email): void
    {
        DB::table('failed_logins')->insert([
            'ip_address'   => $ip,
            'email'        => $email,
            'attempted_at' => now(),
        ]);

        $recentAttempts = DB::table('failed_logins')
            ->where('ip_address', $ip)
            ->where('attempted_at', '>=', now()->subMinutes($this->lockoutMinutes))
            ->count();

        if ($recentAttempts >= $this->maxAttempts) {
            DB::table('failed_logins')->insert([
                'ip_address'    => $ip,
                'email'         => $email,
                'attempted_at'  => now(),
                'blocked_until' => now()->addMinutes($this->lockoutMinutes),
            ]);
        }
    }

    private function clearAttempts(string $ip): void
    {
        DB::table('failed_logins')
            ->where('ip_address', $ip)
            ->delete();
    }
}
