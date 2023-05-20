<?php

namespace App\Helpers;

use Fernet\Fernet;

class CryptHelper
{
    public static function encrypt($value): string
    {
        $key = Fernet::base64url_decode(config('connector.key'));

        $fernet = new Fernet($key);

        return $fernet->encode($value);
    }

    public static function decrypt($value): ?string
    {
        $key = Fernet::base64url_decode(config('connector.key'));

        $fernet = new Fernet($key);

        return $fernet->decode($value);
    }
}
