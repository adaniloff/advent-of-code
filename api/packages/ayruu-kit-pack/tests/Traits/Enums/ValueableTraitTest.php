<?php

declare(strict_types=1);

namespace Package\AyruuKit\Tests\Traits\Enums;

use LogicException;
use Package\AyruuKit\App\Traits\Enums\ValueableTrait;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class ValueableTraitTest extends TestCase
{
    /**
     * @covers \Package\AyruuKit\App\Traits\Enums\ValueableTrait::values
     */
    public function testValueableUsageOnClass(): void
    {
        // Assert
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('The `Package\AyruuKit\App\Traits\Enums\ValueableTrait` trait can only be used on `BackedEnum`.');

        // Arrange
        // Act
        DummyClass::values();
    }

    /**
     * @covers \Package\AyruuKit\App\Traits\Enums\ValueableTrait::values
     */
    public function testValueableUsageOnUnitEnum(): void
    {
        // Assert
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('The `Package\AyruuKit\App\Traits\Enums\ValueableTrait` trait can only be used on `BackedEnum`.');

        // Arrange
        // Act
        DummyUnitEnum::values();
    }

    /**
     * @covers \Package\AyruuKit\App\Traits\Enums\ValueableTrait::values
     */
    public function testBackedEnumUsage(): void
    {
        $expected = [DummyBackedEnum::JSON->value, DummyBackedEnum::HTML->value];
        $result = DummyBackedEnum::values();
        $this->assertEquals($expected, $result);
    }

    /**
     * @covers \Package\AyruuKit\App\Traits\Enums\ValueableTrait::values
     */
    public function testEmptyCases(): void
    {
        $this->assertEmpty(EmptyBackedEnum::values());
    }
}

/**
 * @method static array<mixed> cases()
 */
class DummyClass
{
    use ValueableTrait;
}

enum EmptyBackedEnum: string
{
    use ValueableTrait;
}

enum DummyUnitEnum
{
    use ValueableTrait;

    case JSON;
    case HTML;
}

enum DummyBackedEnum: string
{
    use ValueableTrait;

    case JSON = 'json';
    case HTML = 'html';
}
