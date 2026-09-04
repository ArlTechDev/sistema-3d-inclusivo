<?php

namespace App\Support;

class Sanitizer
{
    public static function clean(?string $text): string
    {
        if ($text === null) {
            return '';
        }

        $clean = strip_tags($text);
        $clean = htmlspecialchars($clean, ENT_QUOTES | ENT_HTML5, 'UTF-8', false);

        return trim($clean);
    }

    public static function cleanArray(array $data, array $fields): array
    {
        foreach ($fields as $field) {
            if (isset($data[$field]) && is_string($data[$field])) {
                $data[$field] = self::clean($data[$field]);
            }
        }

        return $data;
    }
}
