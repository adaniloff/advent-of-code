<?php

declare(strict_types=1);

namespace App\Tests\Year2023\Day7;

use App\Year2023\Day7\{Hand, HandType};
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversDefaultClass \App\Year2023\Day7\Hand
 */
final class HandTest extends TestCase
{
    public function testValidHands(): void
    {
        $this->assertInstanceOf(Hand::class, (new Hand())->build('A', 'A', 'A', 'A', 'A'));
        $this->assertInstanceOf(Hand::class, (new Hand())->build('A', '2', '3', 'A', 'A'));
    }

    public function testNotEnoughCards(): void
    {
        $this->expectExceptionMessage('A hand must have 5 cards.');
        (new Hand())->build('A', 'A', 'A', 'A');
    }

    public function testTooMuchCards(): void
    {
        $this->expectExceptionMessage('A hand must have 5 cards.');
        (new Hand())->build('A', 'A', 'A', 'A', 'A', 'A');
    }

    public function testInvalidCard(): void
    {
        $this->expectExceptionMessage('Invalid card provided.');
        $this->assertInstanceOf(Hand::class, (new Hand())->build('A', '1', 'A', 'A', 'A'));
    }

    public function testHandTypes(): void
    {
        $this->assertEquals(HandType::HIGH_CARD, (new Hand())->build('A', '3', 'J', '5', '6')->getType());
        $this->assertEquals(HandType::SINGLE_PAIR, (new Hand(true))->build('A', '3', 'J', '5', '6')->getType());
    }
}
