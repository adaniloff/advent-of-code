<?php

declare(strict_types=1);

namespace App\Year2023\Day7;

use InvalidArgumentException;

final readonly class Hand
{
    /**
     * @var Card[]
     */
    public array $cards;

    private HandType $type;

    public function __construct(private ?bool $joker = false) {}

    public function build(int|string ...$cards): self
    {
        $valids = [];
        foreach ($cards as $card) {
            ($valids[] = Card::validate($card, $this->joker)) || throw new InvalidArgumentException('Invalid card provided.');
        }
        $this->cards = $valids;

        if (5 !== count($this->cards)) {
            throw new InvalidArgumentException('A hand must have 5 cards.');
        }

        $this->type = HandType::detect($this);

        return $this;
    }

    public function getType(): HandType
    {
        return $this->type;
    }
}
