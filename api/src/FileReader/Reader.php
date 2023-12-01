<?php

declare(strict_types=1);

namespace App\FileReader;

use RuntimeException;

final class Reader
{
    public static function content(string $filename): string
    {
        $file = fopen($filename, 'r');
        $size = filesize($filename);
        if (!$file || !$size) {
            throw new RuntimeException('Unable to open the file');
        }

        return fread($file, $size) ?: '';
    }

    /**
     * @return string[]
     */
    public static function toArray(string $filename): array
    {
        return explode("\n", self::content($filename));
    }
}
