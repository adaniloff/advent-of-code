<?php

declare(strict_types=1);

namespace App\Year2023\Day7;

use InvalidArgumentException;

final readonly class WinnerChickenDinner
{
    /**
     * @var HandBid[]
     */
    public array $bids;

    public function __construct(HandBid ...$bids)
    {
        $this->bids = $this->order(...$bids);
    }

    public function position(int $index): HandBid
    {
        if ($index > count($this->bids)) {
            throw new InvalidArgumentException('Invalid position.');
        }

        $clone = $this->bids;
        end($clone);
        while ($index-- > 1) {
            prev($clone);
        }

        return current($clone);
    }

    public function evaluate(): int
    {
        $sum = 0;
        $i = 0;
        do {
            $sum += ++$i * $this->position($i)->bid;
        } while ($i < count($this->bids));

        return $sum;
    }

    public static function fromSchema(array $schema, ?bool $withJoker = false): self
    {
        $bids = [];
        foreach ($schema as $line) {
            [$cards, $bid] = explode(' ', $line);
            $hand = (new Hand(joker: $withJoker))->build(...str_split($cards));
            $bids[] = new HandBid($hand, (int) $bid);
        }

        return new self(...$bids);
    }

    /**
     * @return HandBid[]
     */
    private function order(HandBid ...$bids): array
    {
        $hands = [];
        foreach ($bids as $bid) {
            $sum = $bid->hand->getType()->value;
            foreach ($bid->hand->cards as $i => $card) {
                $sum += ($card->value / pow(100, $i + 1));
            }
            $hands[(string) $sum] = $bid;
        }

        krsort($hands);

        return $hands;
    }
}
