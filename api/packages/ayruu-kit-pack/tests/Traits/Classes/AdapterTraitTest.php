<?php

declare(strict_types=1);

namespace Package\AyruuKit\Tests\Traits\Classes;

use LogicException;
use Package\AyruuKit\App\Traits\Classes\AdapterTrait;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class AdapterTraitTest extends TestCase
{
    /**
     * @covers \Package\AyruuKit\App\Traits\Classes\AdapterTrait::__call
     * @covers \Package\AyruuKit\App\Traits\Classes\AdapterTrait::hasParent
     */
    public function testAdapterRequiresParent(): void
    {
        // Assert
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Protected property parent must be defined with the adapted object.');

        // Arrange
        // Act
        $adapter = new NoneAdapter();
    }

    /**
     * @covers \Package\AyruuKit\App\Traits\Classes\AdapterTrait::__call
     * @covers \Package\AyruuKit\App\Traits\Classes\AdapterTrait::hasParent
     */
    public function testAdapterDoesNotRequiresStaticParent(): void
    {
        $adapter = new PropertyAdapter();
        $adapter->getName();
        $this->expectNotToPerformAssertions();
    }

    /**
     * @covers \Package\AyruuKit\App\Traits\Classes\AdapterTrait::__call
     */
    public function testCall(): void
    {
        $adapter = new PropertyAdapter();
        $name = $adapter->getName();
        $this->assertNotEmpty($name);
    }

    /**
     * @covers \Package\AyruuKit\App\Traits\Classes\AdapterTrait::__call
     */
    public function testCallFailure(): void
    {
        // Assert
        $this->expectExceptionMessage('Call to undefined method Package\AyruuKit\Tests\Traits\Classes\Adapted::notExistingMethod()');

        // Arrange
        // Act
        $adapter = new PropertyAdapter();
        $adapter->notExistingMethod();
    }

    /**
     * @covers \Package\AyruuKit\App\Traits\Classes\AdapterTrait::__callStatic
     */
    public function testStaticCallRequireStaticParent(): void
    {
        // Assert
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Constant PARENT must be defined with the adapted class name definition.');

        // Arrange
        // Act
        PropertyAdapter::notExistingStaticMethod();
    }

    /**
     * @covers \Package\AyruuKit\App\Traits\Classes\AdapterTrait::__callStatic
     */
    public function testStaticCall(): void
    {
        $name = PropertyStaticAdapter::getClassName();
        $this->assertNotEmpty($name);
    }

    /**
     * @covers \Package\AyruuKit\App\Traits\Classes\AdapterTrait::__callStatic
     */
    public function testStaticCallFailure(): void
    {
        // Assert
        $this->expectExceptionMessage('Call to undefined method Package\AyruuKit\Tests\Traits\Classes\Adapted::notExistingStaticMethod()');

        // Arrange
        // Act
        PropertyStaticAdapter::notExistingStaticMethod();
    }

    /**
     * @coversNothing
     */
    public function testDefaultGet(): void
    {
        $adapter = new PropertyAdapter();
        $this->assertNotEmpty($adapter->surname);
    }

    /**
     * @covers \Package\AyruuKit\App\Traits\Classes\AdapterTrait::__get
     */
    public function testMagicGet(): void
    {
        $adapter = new PropertyAdapter();
        $this->assertNotEmpty($adapter->name);
    }

    /**
     * @coversNothing
     */
    public function testDefaultSet(): void
    {
        $adapter = new PropertyAdapter();
        $adapter->surname = '';
        $this->assertEmpty($adapter->surname);
    }

    /**
     * @covers \Package\AyruuKit\App\Traits\Classes\AdapterTrait::__set
     */
    public function testMagicSet(): void
    {
        $adapter = new PropertyAdapter();
        $adapter->name = '';
        $this->assertEmpty($adapter->name);
    }

    /**
     * @coversNothing
     */
    public function testDefaultIsset(): void
    {
        $adapter = new PropertyAdapter();
        $this->assertTrue(isset($adapter->surname));
    }

    /**
     * @covers \Package\AyruuKit\App\Traits\Classes\AdapterTrait::__isset
     */
    public function testMagicIsset(): void
    {
        $adapter = new PropertyAdapter();
        $this->assertTrue(isset($adapter->name));
    }

    /**
     * @coversNothing
     */
    public function testDefaultUnset(): void
    {
        $adapter = new PropertyAdapter();
        unset($adapter->surname);
        $this->assertFalse(isset($adapter->surname));
    }

    /**
     * @covers \Package\AyruuKit\App\Traits\Classes\AdapterTrait::__unset
     */
    public function testMagicUnset(): void
    {
        $adapter = new PropertyAdapter();
        unset($adapter->name);
        $this->assertFalse(isset($adapter->name));
    }

    /**
     * @covers \Package\AyruuKit\App\Traits\Classes\AdapterTrait::__toString
     */
    public function testMagicToString(): void
    {
        $adapter = new StringableAdapter();
        $this->assertNotEmpty((string) $adapter);
    }

    /**
     * @covers \Package\AyruuKit\App\Traits\Classes\AdapterTrait::__toString
     */
    public function testMagicToStringFailure(): void
    {
        // Assert
        $this->expectExceptionMessage('Cannot cast Package\AyruuKit\Tests\Traits\Classes\PropertyAdapter to string.');

        // Arrange
        $adapter = new PropertyAdapter();
        // Act
        $name = (string) $adapter;
    }

    /**
     * @covers \Package\AyruuKit\App\Traits\Classes\AdapterTrait::__invoke
     */
    public function testMagicInvokeNotCallable(): void
    {
        // Assert
        $this->expectExceptionMessage('Object of type Package\AyruuKit\Tests\Traits\Classes\PropertyAdapter is not callable');

        // Arrange
        $adapter = new PropertyAdapter();

        // Act
        $adapter('I am a wizard!');
    }

    /**
     * @covers \Package\AyruuKit\App\Traits\Classes\AdapterTrait::__invoke
     */
    public function testMagicInvoke(): void
    {
        $adapter = new InvokableAdapter();
        $invocation = $adapter('I am a wizard!');
        $this->assertNotEmpty($invocation);
    }

    /**
     * @covers \Package\AyruuKit\App\Traits\Classes\AdapterTrait::__clone
     */
    public function testClone(): void
    {
        $adapter = new CloneableAdapter();
        $clone = clone $adapter;
        $this->assertEquals('The clone war...', $clone->getName());
    }

    /**
     * @covers \Package\AyruuKit\App\Traits\Classes\AdapterTrait::__unserialize
     */
    public function testWakeUp(): void
    {
        $adapter = new WokeAdapter();
        $woke = unserialize(serialize($adapter));
        $this->assertEquals('I am woke.', $woke->getName());
    }

    /**
     * @covers \Package\AyruuKit\App\Traits\Classes\AdapterTrait::__unserialize
     */
    public function testUnserialize(): void
    {
        $adapter = new SerializableAdapter();
        $woke = unserialize(serialize($adapter));
        $this->assertEquals('Leave me alone. This is magic !', $woke->getName());
    }
}

class Adapted
{
    public string $name = 'Leave me alone.';

    public function getName(): string
    {
        return $this->name;
    }

    public static function getClassName(): string
    {
        return self::class;
    }
}

final class InvokableAdapted extends Adapted
{
    public function __invoke(string $text = ''): string
    {
        return $text;
    }
}

final class StringableAdapted extends Adapted
{
    public function __toString(): string
    {
        return $this->getName();
    }
}

final class CloneableAdapted extends Adapted
{
    public function __clone(): void
    {
        $this->name = 'The clone war...';
    }
}

final class WokeAdapted extends Adapted
{
    public function __wakeup(): void
    {
        $this->name = 'I am woke.';
    }
}

final class SerializableAdapted extends Adapted
{
    /**
     * @param array<string> $data
     */
    public function __unserialize(array $data): void
    {
        $this->name = $data['name'].' This is magic !';
    }
}

/**
 * @property Adapted $parent
 */
final class NoneAdapter
{
    use AdapterTrait;
}

/**
 * @property string $name
 *
 * @method        string notExistingMethod()
 * @method static string notExistingStaticMethod()
 * @method        string getName()
 */
final class PropertyAdapter
{
    use AdapterTrait;
    public string $surname = 'Leave me alone. Again.';

    private Adapted $parent;

    public function __construct()
    {
        $this->parent = new Adapted();
    }
}

/**
 * @property string $name
 *
 * @method string getName()
 */
final class InvokableAdapter
{
    use AdapterTrait;

    private InvokableAdapted $parent;

    public function __construct()
    {
        $this->parent = new InvokableAdapted();
    }
}

/**
 * @property string $name
 *
 * @method string getName()
 */
final class StringableAdapter
{
    use AdapterTrait;

    private StringableAdapted $parent;

    public function __construct()
    {
        $this->parent = new StringableAdapted();
    }
}

/**
 * @property string $name
 *
 * @method string getName()
 */
final class CloneableAdapter
{
    use AdapterTrait;

    private CloneableAdapted $parent;

    public function __construct()
    {
        $this->parent = new CloneableAdapted();
    }
}

/**
 * @property string $name
 *
 * @method string getName()
 */
final class WokeAdapter
{
    use AdapterTrait;

    private WokeAdapted $parent;

    public function __construct()
    {
        $this->parent = new WokeAdapted();
    }
}

/**
 * @property string $name
 *
 * @method string getName()
 */
final class SerializableAdapter
{
    use AdapterTrait;

    private SerializableAdapted $parent;

    public function __construct()
    {
        $this->parent = new SerializableAdapted();
    }
}

/**
 * @property string $name
 *
 * @method        string getName()
 * @method static string notExistingStaticMethod()
 * @method static string getClassName()
 */
final class PropertyStaticAdapter
{
    use AdapterTrait;
    public const PARENT = Adapted::class;

    private Adapted $parent;

    public function __construct()
    {
        $this->parent = new Adapted();
    }
}
