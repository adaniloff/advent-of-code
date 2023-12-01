<?php

declare(strict_types=1);

namespace Dev\Foundry\Tests\Example\Cache\Factory;

use DateTime;
use Dev\Foundry\App\Example\Cache\Adapter\Entity\{ExampleCacheProxy};
use Dev\Foundry\App\Example\Cache\Factory\ExampleFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use function Zenstruck\Foundry\faker;

use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\Test\Factories;

/**
 * @group internal
 *
 * @coversNothing
 *
 * @internal
 */
final class ExampleFactoryTest extends KernelTestCase
{
    use Factories;

    protected function setUp(): void
    {
        $this->resetCache();
    }

    protected function tearDown(): void
    {
        $this->resetCache();
    }

    public function testCreateOne(): void
    {
        // Arrange
        $this->assertCount(0, ExampleFactory::all());

        // Act
        /**
         * @var Proxy<ExampleCacheProxy> $example
         */
        $example = ExampleFactory::createOne();

        // Assert
        $this->assertCount(1, ExampleFactory::all());
        $this->assertEquals(
            $example->object()->object(),
            ExampleFactory::all()[0]->object()
        );
    }

    public function testCreateMany(): void
    {
        $this->assertCount(0, ExampleFactory::all());
        ExampleFactory::createMany(5);
        $this->assertCount(5, ExampleFactory::all());
    }

    public function testCreateWithArguments(): void
    {
        // Arrange
        $name = faker()->name();

        // Act
        /**
         * @var Proxy<ExampleCacheProxy> $example
         */
        $example = ExampleFactory::new(['name' => $name])->create();

        // Assert
        $this->assertSame($example->getName(), $name);
        $this->assertEquals($example->object()->object(), ExampleFactory::all()[0]->object());
    }

    public function testManyOperationsTakeNoTime(): void
    {
        // Arrange
        ExampleFactory::clear(); // force clear all cache before starting
        $maxExecutionTime = 2;
        $beginAt = new DateTime();
        $this->assertCount(0, ExampleFactory::all());
        $i = 0;

        // Act
        do {
            ExampleFactory::createMany(1);
            ExampleFactory::createMany(2);
            ExampleFactory::createMany(3);
            ExampleFactory::createMany(10);
        } while (++$i < 50);

        // Assert
        $this->assertCount(800, ExampleFactory::all());
        $realExecutionTime = (new DateTime())->getTimestamp() - $beginAt->getTimestamp();

        $this->assertLessThan($maxExecutionTime, $realExecutionTime);
        ExampleFactory::clear(); // force clear all cache
    }

    private function resetCache(): void
    {
        ExampleFactory::empty();
        $this->assertEmpty(ExampleFactory::all());
    }
}
