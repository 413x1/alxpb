<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class General
{
    public static function generateAccessCode(): string
    {
        return self::generateRandomCode(5);
    }

    public static function generateRandomCode($length = 6): string
    {
        return Str::upper(Str::random($length, '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'));
    }

    public static function generateDeviceIdentifier(): string
    {
        return 'dev_' . md5(uniqid(rand(), true));
    }

    public static function decimalToRupiah($amount, $withDecimals = false): string
    {
        $decimals = $withDecimals ? 2 : 0;
        return 'Rp ' . number_format($amount, $decimals, ',', '.').',00';
    }

    public static function isActiveRoute($routeName, $output = 'active') {
        return request()->routeIs($routeName) ? $output : '';
    }
}
