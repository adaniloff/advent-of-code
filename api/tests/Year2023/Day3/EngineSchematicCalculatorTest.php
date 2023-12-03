<?php

declare(strict_types=1);

namespace App\Tests\Year2023\Day3;

use App\Year2023\Day3\{EngineParser, EngineSchematicCalculator};
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversDefaultClass \App\Year2023\Day3\EngineSchematicCalculator
 */
final class EngineSchematicCalculatorTest extends TestCase
{
    public function testCalculationsAreAlwaysCorrect(): void
    {
        $calculator = new EngineSchematicCalculator(new EngineParser([
            '...1...21...3.!',
            '...4.15**6..6..',
        ]));
        for ($i = 0; $i < 10; ++$i) {
            $this->assertEquals(42, $calculator->compute());
            $this->assertEquals(441, $calculator->computeGearParts());
        }
    }
}
