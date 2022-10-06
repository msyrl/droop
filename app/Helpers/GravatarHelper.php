<?php

namespace App\Helpers;

class GravatarHelper
{
    public static function generateUrl(string $email, int $size = 50): string
    {
        $url = 'https://www.gravatar.com/avatar/';
        $hash = md5(strtolower(trim($email)));

        return "{$url}{$hash}?s={$size}";
    }
}
