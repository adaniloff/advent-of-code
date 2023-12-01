<?php

declare(strict_types=1);

namespace Package\AyruuKit\App\Application\Printer;

use Stringable;

interface PrinterInterface
{
    public function print(string|Stringable $message): void;
}
