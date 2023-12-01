<?php

declare(strict_types=1);

namespace Package\AyruuKit\App\Application\Printer;

use Psr\Log\LoggerInterface;
use Stringable;

/**
 * Replace all logs by prints.
 *
 * @see PrintableLoggerAdapter::log()
 */
final class PrintableLoggerAdapter implements LoggerInterface, PrinterInterface
{
    public function print(string|Stringable $message): void
    {
        echo $message;
    }

    public function emergency(string|Stringable $message, array $context = []): void
    {
        $this->log(null, $message, $context);
    }

    public function alert(string|Stringable $message, array $context = []): void
    {
        $this->log(null, $message, $context);
    }

    public function critical(string|Stringable $message, array $context = []): void
    {
        $this->log(null, $message, $context);
    }

    public function error(string|Stringable $message, array $context = []): void
    {
        $this->log(null, $message, $context);
    }

    public function warning(string|Stringable $message, array $context = []): void
    {
        $this->log(null, $message, $context);
    }

    public function notice(string|Stringable $message, array $context = []): void
    {
        $this->log(null, $message, $context);
    }

    public function info(string|Stringable $message, array $context = []): void
    {
        $this->log(null, $message, $context);
    }

    public function debug(string|Stringable $message, array $context = []): void
    {
        $this->log(null, $message, $context);
    }

    public function log($level, string|Stringable $message, array $context = []): void
    {
        $this->print($message);
    }
}
