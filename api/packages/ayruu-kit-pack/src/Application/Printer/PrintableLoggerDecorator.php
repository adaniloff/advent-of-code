<?php

declare(strict_types=1);

namespace Package\AyruuKit\App\Application\Printer;

use Package\AyruuKit\App\Traits\Classes\AdapterTrait;
use Psr\Log\LoggerInterface;
use Stringable;

/**
 * Add a print method to a logger.
 *
 * @see PrintableLoggerDecorator::print()
 */
final class PrintableLoggerDecorator implements LoggerInterface, PrinterInterface
{
    use AdapterTrait;

    /**
     * @codeCoverageIgnore
     */
    public function __construct(protected LoggerInterface $parent) {}

    public function print(string|Stringable $message): void
    {
        echo $message;
    }

    public function emergency(string|Stringable $message, array $context = []): void
    {
        $this->parent->emergency($message, $context);
    }

    public function alert(string|Stringable $message, array $context = []): void
    {
        $this->parent->alert($message, $context);
    }

    public function critical(string|Stringable $message, array $context = []): void
    {
        $this->parent->critical($message, $context);
    }

    public function error(string|Stringable $message, array $context = []): void
    {
        $this->parent->error($message, $context);
    }

    public function warning(string|Stringable $message, array $context = []): void
    {
        $this->parent->warning($message, $context);
    }

    public function notice(string|Stringable $message, array $context = []): void
    {
        $this->parent->notice($message, $context);
    }

    public function info(string|Stringable $message, array $context = []): void
    {
        $this->parent->info($message, $context);
    }

    public function debug(string|Stringable $message, array $context = []): void
    {
        $this->parent->debug($message, $context);
    }

    public function log($level, string|Stringable $message, array $context = []): void
    {
        $this->parent->log($level, $message, $context);
    }
}
