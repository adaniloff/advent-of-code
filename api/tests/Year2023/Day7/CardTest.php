<?php

declare(strict_types=1);

namespace App\Tests\Year2023\Day7;

use App\Year2023\Day7\{Card, Hand, HandType};
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversDefaultClass \App\Year2023\Day7\Hand
 */
final class CardTest extends TestCase
{
    /**
     * @dataProvider cardsDeckWithoutJoker
     */
    public function testValidateWithoutJoker(int|string $card, Card $expectedCard): void
    {
        $this->assertEquals($expectedCard, Card::validate($card));
    }

    public static function cardsDeckWithoutJoker(): array
    {
        return [
            ['2', Card::TWO],
            ['3', Card::THREE],
            ['4', Card::FOUR],
            ['5', Card::FIVE],
            ['6', Card::SIX],
            ['7', Card::SEVEN],
            ['8', Card::EIGHT],
            ['9', Card::NINE],
            ['T', Card::T],
            ['J', Card::J],
            ['Q', Card::Q],
            ['K', Card::K],
            ['A', Card::A],
        ];
    }

    /**
     * @dataProvider cardsDeckWithJoker
     */
    public function testValidateWithJoker(int|string $card, Card $expectedCard): void
    {
        $this->assertEquals($expectedCard, Card::validate($card, true));
    }

    public function testRefuseJokerValue(): void
    {
        $this->assertEquals(false, Card::validate(1));
        $this->assertEquals(false, Card::validate(1, true));
    }

    public static function cardsDeckWithJoker(): array
    {
        return [
            ['2', Card::TWO],
            ['3', Card::THREE],
            ['4', Card::FOUR],
            ['5', Card::FIVE],
            ['6', Card::SIX],
            ['7', Card::SEVEN],
            ['8', Card::EIGHT],
            ['9', Card::NINE],
            ['T', Card::T],
            ['J', Card::JOKER],
            ['Q', Card::Q],
            ['K', Card::K],
            ['A', Card::A],
        ];
    }
}
