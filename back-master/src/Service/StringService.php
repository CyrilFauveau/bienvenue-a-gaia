<?php

namespace App\Service;

class StringService
{
    /**
     * Allows to minimize a character string
     *
     * @param string $text
     * @param string $divider
     * @return string
     */
    public function slugify(string $text, string $divider = '-'): string
    {
        // Replace non letter or digits by divider
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

        // Transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // Remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // Trim
        $text = trim($text, $divider);

        // Remove duplicate divider
        $text = preg_replace('~-+~', $divider, $text);

        // Lowercase
        $text = strtolower($text);

        return $text;
    }
}
