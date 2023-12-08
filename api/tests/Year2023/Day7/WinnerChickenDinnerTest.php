<?php

declare(strict_types=1);

namespace App\Tests\Year2023\Day7;

use App\FileReader\Reader;
use App\Year2023\Day7\{Card, Hand, HandBid, HandType, WinnerChickenDinner};
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversDefaultClass \App\Year2023\Day7\WinnerChickenDinner
 */
final class WinnerChickenDinnerTest extends TestCase
{
    public function testSimpleCase(): void
    {
        $bestHand = new HandBid((new Hand())->build(3, 2, 'T', 3, 'K'), 765);
        $secondBestHand = new HandBid((new Hand())->build('T', 5, 5, 'J', 5), 684);

        $winner = new WinnerChickenDinner($secondBestHand, $bestHand);

        $this->assertEquals($bestHand, $winner->position(1));
        $this->assertEquals($secondBestHand, $winner->position(2));
        $this->assertEquals(765 + 684 * 2, $winner->evaluate());
    }

    public function testFakeDataset(): void
    {
        $schema = Reader::toArray(__DIR__.'/fakeDataset.txt');

        $winner = WinnerChickenDinner::fromSchema($schema);

        $this->assertEquals(6440, $winner->evaluate());
    }

    public function testFakeDatasetWithJoker(): void
    {
        $schema = Reader::toArray(__DIR__.'/fakeDataset.txt');

        $winner = WinnerChickenDinner::fromSchema($schema, true);

        $this->assertEquals(5905, $winner->evaluate());
    }

    public function testAdvancedDataset(): void
    {
        $schema = Reader::toArray(__DIR__.'/fakeAdvancedDataset.txt');

        $winner = WinnerChickenDinner::fromSchema($schema);

        $this->assertEquals(14595, $winner->evaluate());
    }
}
