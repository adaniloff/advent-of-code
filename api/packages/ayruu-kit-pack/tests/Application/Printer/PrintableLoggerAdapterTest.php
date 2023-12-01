<?php

declare(strict_types=1);

namespace Package\AyruuKit\Tests\Application\Printer;

use Package\AyruuKit\App\Application\Printer\PrintableLoggerAdapter;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversDefaultClass \Package\AyruuKit\App\Application\Printer\PrintableLoggerAdapter
 */
final class PrintableLoggerAdapterTest extends TestCase
{
    /**
     * @covers \Package\AyruuKit\App\Application\Printer\PrintableLoggerAdapter::log
     */
    public function testPrintDefinitivelyReplaceLog(): void
    {
        $adapter = new PrintableLoggerAdapter();
        $adapter->log('', 'Hello world !');
        $this->expectOutputString('Hello world !');
    }

    /**
     * @dataProvider methods
     *
     * @covers \Package\AyruuKit\App\Application\Printer\PrintableLoggerAdapter::alert
     * @covers \Package\AyruuKit\App\Application\Printer\PrintableLoggerAdapter::critical
     * @covers \Package\AyruuKit\App\Application\Printer\PrintableLoggerAdapter::debug
     * @covers \Package\AyruuKit\App\Application\Printer\PrintableLoggerAdapter::emergency
     * @covers \Package\AyruuKit\App\Application\Printer\PrintableLoggerAdapter::error
     * @covers \Package\AyruuKit\App\Application\Printer\PrintableLoggerAdapter::info
     * @covers \Package\AyruuKit\App\Application\Printer\PrintableLoggerAdapter::notice
     * @covers \Package\AyruuKit\App\Application\Printer\PrintableLoggerAdapter::print
     * @covers \Package\AyruuKit\App\Application\Printer\PrintableLoggerAdapter::warning
     */
    public function testPrintReplaceLog(string $method): void
    {
        $adapter = new PrintableLoggerAdapter();
        $adapter->{$method}('Hello world !');
        $this->expectOutputString('Hello world !');
    }

    /**
     * @return array<array<string>>
     */
    public static function methods(): array
    {
        return [
            ['print'],
            ['alert'],
            ['critical'],
            ['debug'],
            ['emergency'],
            ['error'],
            ['info'],
            ['notice'],
            ['warning'],
        ];
    }
}
