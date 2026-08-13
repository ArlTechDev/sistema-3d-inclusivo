<?php

namespace App\Support;

use Illuminate\Http\RedirectResponse;

class SafeRedirect
{
    public static function intended(string $fallback): RedirectResponse
    {
        $intended = session()->pull('url.intended', $fallback);

        if (self::isExternalUrl($intended, $fallback)) {
            return redirect($fallback);
        }

        return redirect($intended);
    }

    private static function isExternalUrl(string $url, string $fallback): bool
    {
        $appUrl = config('app.url');

        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            if (! str_starts_with($url, $appUrl)) {
                return true;
            }
        }

        return false;
    }
}
