<?php

declare(strict_types=1);

namespace Package\AyruuKit\Tests\Traits\Classes;

use Package\AyruuKit\App\Traits\Classes\LogAwareTrait;
use PHPUnit\Framework\TestCase;
use Psr\Log\{LoggerInterface, NullLogger};
use Stringable;

/**
 * @internal
 *
 * @coversNothing
 */
final class LogAwareTraitTest extends TestCase
{
    private SpyLogger $spy;

    protected function setUp(): void
    {
        $this->spy = new SpyLogger();
    }

    /**
     * @covers \Package\AyruuKit\App\Traits\Classes\LogAwareTrait::__construct
     */
    public function testNullLoggerIsDefault(): void
    {
        $repository = new DummyRepository();
        $logger = $repository->getLogger();
        $this->assertInstanceOf(NullLogger::class, $logger);
    }

    /**
     * @covers \Package\AyruuKit\App\Traits\Classes\LogAwareTrait::__call
     * @covers \Package\AyruuKit\App\Traits\Classes\LogAwareTrait::setLogger
     */
    public function testExistingCallDoesNotTriggerLog(): void
    {
        $repository = new DummyRepository();
        $repository->setLogger($this->spy);

        $repository->getName();

        $this->assertEquals(0, $this->spy->count());
    }

    /**
     * @covers \Package\AyruuKit\App\Traits\Classes\LogAwareTrait::__call
     * @covers \Package\AyruuKit\App\Traits\Classes\LogAwareTrait::setLogger
     */
    public function testNotExistingCallDoesTriggerLog(): void
    {
        $repository = new DummyRepository();
        $repository->setLogger($this->spy);

        $repository->notExisting();

        $this->assertEquals(1, $this->spy->count());
        $this->assertEquals('Package\AyruuKit\Tests\Traits\Classes\DummyRepository::notExisting has been called, but the method does not exist.', $this->spy->last());
    }
}

/**
 * @method void notExisting()
 */
final class DummyRepository
{
    use LogAwareTrait;

    public function getName(): string
    {
        return __CLASS__;
    }

    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }
}

final class SpyLogger extends NullLogger
{
    /**
     * @var array<array<mixed>>
     */
    private array $entries = [];

    /**
     * @param array<mixed> $context
     */
    public function info(string|Stringable $message, array $context = []): void
    {
        $this->entries[] = [$message, $context];
        parent::info($message, $context);
    }

    public function count(): int
    {
        return count($this->entries);
    }

    public function last(): null|string|Stringable
    {
        if ($this->count() < 1) {
            return null;
        }

        return $this->entries[$this->count() - 1][0];
    }
}
