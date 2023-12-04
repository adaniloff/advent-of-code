<?php

declare(strict_types=1);

namespace App\Year2023\Day4;

final class BingoCalculator
{
    /**
     * @var Bingo[]
     */
    private readonly array $bingos;

    /**
     * @var int[]
     */
    private array $copies;

    /**
     * @param string[] $lines
     */
    public function __construct(array $lines)
    {
        $this->copies = [];
        $bingos = [];
        foreach ($lines as $line) {
            $bingos[] = new Bingo($line);
        }

        $this->bingos = $bingos;
    }

    public function compute(): int
    {
        $points = 0;
        foreach ($this->bingos as $bingo) {
            $points += $bingo->calculate();
        }

        return $points;
    }

    public function computeScratchCards(): int
    {
        // @todo: improve this part (seems particularly too slow)
        foreach ($this->bingos as $cardIndex => $bingo) {
            $matches = count($bingo->matches());
            if (!$matches) {
                continue;
            }

            $copies = $this->copies[$cardIndex] ?? 0;
            $iteration = 0;
            while ($iteration++ <= $copies) {
                $copyIndex = $cardIndex + 1;
                while ($copyIndex <= $cardIndex + $matches && isset($this->bingos[$copyIndex])) {
                    $this->copies[$copyIndex] ??= 0;
                    ++$this->copies[$copyIndex];
                    ++$copyIndex;
                }
            }
        }

        return count($this->bingos) + array_sum($this->copies);
    }

    public function getCopyCount(int $i): int
    {
        return $this->copies[$i] ?? 0;
    }
}
