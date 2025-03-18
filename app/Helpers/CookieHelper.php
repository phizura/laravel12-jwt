<?php

namespace App\Helpers;

class CookieHelper
{
    static public function makeCookie(string $key, string $data)
    {
        return cookie($key, $data, 45, '/',  env('COOKIE_DOMAIN', 'localhost'), env('APP_ENV') === 'production', true);
    }
}
