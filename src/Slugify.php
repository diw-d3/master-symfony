<?php

namespace App;

class Slugify
{
    public static function slugify(string $string): string
    {
        return preg_replace(
            '/\s+/',
            '-',
            strtolower(trim(strip_tags($string)))
        );
    }
}
