<?php

declare(strict_types=1);

namespace App\Tests\Year2023\Day4;

use App\Year2023\Day4\BingoCalculator;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversDefaultClass \App\Year2023\Day4\BingoCalculator
 */
final class BingoCalculatorTest extends TestCase
{
    private const COMPUTE_FIXTURES = [
        'Anything 1 : 13 32 20 16 61 | ',
        'Anything 2 : 13 32    20 16 61|15     31 17  9',
        'Anything 3:| 61 30 68 82 17 32 24 19',
        'Card 1: 41 48 83 86 17 | 83 86  6 31 17  9 48 53',  // 1 + 0
        'Card 2: 13 32 20 16 61 | 611 30 68 82 17 32 24 19', // 1 + 1
        'Anything 4 : 13 32 20 16 61 | ',                    // 0 + 2
        'Anything 5 : 13 32    20 16 61|15     31 17  9',    // 0 + 1
    ];

    private const SCRATCHCARDS_FIXTURES = [
        'Card 1: 41 48 83 86 17 | 83 86  6 31 17  9 48 53', // 4M
        'Card 2: 13 32 20 16 61 | 61 30 68 82 17 32 24 19', // 2M
        'Card 3:  1 21 53 59 44 | 69 82 63 72 16 21 14  1', // 1M
        'Card 4: 41 92 73 84 69 | 59 84 76 51 58  5 54 83',
        'Card 5: 87 83 26 28 32 | 88 30 70 12 93 22 82 36',
        'Card 6: 31 18 13 56 72 | 74 77 10 23 35 67 36 11',
    ];

    public function testCompute(): void
    {
        $calculator = new BingoCalculator(self::COMPUTE_FIXTURES);
        $this->assertEquals(9, $calculator->compute());
    }

    public function testComputeScratchCards(): void
    {
        $calculator = new BingoCalculator(self::SCRATCHCARDS_FIXTURES);

        $result = $calculator->computeScratchCards();

        $this->assertEquals(0, $calculator->getCopyCount(0));
        $this->assertEquals(1, $calculator->getCopyCount(1));
        $this->assertEquals(3, $calculator->getCopyCount(2));
        $this->assertEquals(7, $calculator->getCopyCount(3));
        $this->assertEquals(13, $calculator->getCopyCount(4));
        $this->assertEquals(0, $calculator->getCopyCount(5));
        $this->assertEquals(30, $result);
    }
}
