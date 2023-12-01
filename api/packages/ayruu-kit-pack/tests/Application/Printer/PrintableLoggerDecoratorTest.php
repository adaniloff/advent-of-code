<?php

declare(strict_types=1);

namespace Package\AyruuKit\Tests\Application\Printer;

use Package\AyruuKit\App\Application\Printer\PrintableLoggerDecorator;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Stringable;

/**
 * @internal
 *
 * @coversDefaultClass \Package\AyruuKit\App\Application\Printer\PrintableLoggerDecorator
 */
final class PrintableLoggerDecoratorTest extends TestCase
{
    /**
     * @covers \Package\AyruuKit\App\Application\Printer\PrintableLoggerDecorator::print
     */
    public function testPrintIsPossible(): void
    {
        $spy = new SpyLogger();
        $adapter = new PrintableLoggerDecorator($spy);

        $adapter->print('Hello world !');

        $this->expectOutputString('Hello world !');
        $this->assertEquals(0, $spy->logs());
    }

    /**
     * @dataProvider methods
     *
     * @covers \Package\AyruuKit\App\Application\Printer\PrintableLoggerDecorator::alert
     * @covers \Package\AyruuKit\App\Application\Printer\PrintableLoggerDecorator::critical
     * @covers \Package\AyruuKit\App\Application\Printer\PrintableLoggerDecorator::debug
     * @covers \Package\AyruuKit\App\Application\Printer\PrintableLoggerDecorator::emergency
     * @covers \Package\AyruuKit\App\Application\Printer\PrintableLoggerDecorator::error
     * @covers \Package\AyruuKit\App\Application\Printer\PrintableLoggerDecorator::info
     * @covers \Package\AyruuKit\App\Application\Printer\PrintableLoggerDecorator::notice
     * @covers \Package\AyruuKit\App\Application\Printer\PrintableLoggerDecorator::warning
     */
    public function testLogIsPreserved(string $method): void
    {
        $spy = new SpyLogger();
        $adapter = new PrintableLoggerDecorator($spy);

        $adapter->{$method}('Hello world !');

        $this->expectOutputString('');
        $this->assertEquals(1, $spy->logs());
    }

    /**
     * @covers \Package\AyruuKit\App\Application\Printer\PrintableLoggerDecorator::log
     */
    public function testLogIsDefinitivelyPreserved(): void
    {
        $spy = new SpyLogger();
        $adapter = new PrintableLoggerDecorator($spy);

        $adapter->log('info', 'Hello world !');

        $this->expectOutputString('');
        $this->assertEquals(1, $spy->logs());
    }

    /**
     * @return array<array<string>>
     */
    public static function methods(): array
    {
        return [
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

final class SpyLogger extends NullLogger
{
    private int $logs = 0;

    /**
     * @param array<string> $context
     */
    public function log(mixed $level, string|Stringable $message, array $context = []): void
    {
        ++$this->logs;
        parent::log($level, $message, $context);
    }

    public function logs(): int
    {
        return $this->logs;
    }
}
