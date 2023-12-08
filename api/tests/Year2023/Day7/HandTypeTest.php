<?php

declare(strict_types=1);

namespace App\Tests\Year2023\Day7;

use App\Year2023\Day7\{Hand, HandType};
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversDefaultClass \App\Year2023\Day7\HandType
 */
final class HandTypeTest extends TestCase
{
    public function testDetection(): void
    {
        // Arrange
        // Act
        // Assert
        $this->assertEquals(HandType::HIGH_CARD, HandType::detect((new Hand())->build('A', 'K', 'Q', 'J', 'T')));
        $this->assertEquals(HandType::SINGLE_PAIR, HandType::detect((new Hand())->build('A', 'A', 'Q', 'J', 'T')));
        $this->assertEquals(HandType::TWO_PAIR, HandType::detect((new Hand())->build('A', 'A', 'Q', 'Q', 'T')));
        $this->assertEquals(HandType::FULL_HOUSE, HandType::detect((new Hand())->build('A', 'A', 'A', 'J', 'J')));
        $this->assertEquals(HandType::THREE_OF_A_KIND, HandType::detect((new Hand())->build('A', 'A', 'A', 'J', 'T')));
        $this->assertEquals(HandType::FOUR_OF_A_KIND, HandType::detect((new Hand())->build('A', 'A', 'A', 'A', 'T')));
        $this->assertEquals(HandType::FIVE_OF_A_KIND, HandType::detect((new Hand())->build('A', 'A', 'A', 'A', 'A')));
    }

    public function testDetectionWithJoker(): void
    {
        // Arrange
        // Act
        // Assert
        $this->assertEquals(HandType::SINGLE_PAIR, HandType::detect((new Hand(true))->build('A', 'K', 'Q', 'J', 'T')));
        $this->assertEquals(HandType::THREE_OF_A_KIND, HandType::detect((new Hand(true))->build('A', 'K', 'J', 'J', 'T')));
        $this->assertEquals(HandType::THREE_OF_A_KIND, HandType::detect((new Hand(true))->build('A', 'A', 'Q', 'J', 'T')));
        $this->assertEquals(HandType::FULL_HOUSE, HandType::detect((new Hand(true))->build('A', 'A', 'J', 'T', 'T')));
        $this->assertEquals(HandType::FOUR_OF_A_KIND, HandType::detect((new Hand(true))->build('A', 'A', 'A', 'J', 'T')));
        $this->assertEquals(HandType::FOUR_OF_A_KIND, HandType::detect((new Hand(true))->build('J', 'J', 'J', 'Q', 'T')));
        $this->assertEquals(HandType::FIVE_OF_A_KIND, HandType::detect((new Hand(true))->build('J', 'J', 'J', 'J', 'T')));
        $this->assertEquals(HandType::FIVE_OF_A_KIND, HandType::detect((new Hand(true))->build('A', 'A', 'A', 'J', 'J')));
    }
}
