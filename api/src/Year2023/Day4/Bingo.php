<?php

declare(strict_types=1);

namespace App\Year2023\Day4;

use LogicException;

final readonly class Bingo
{
    private const PATTERN = '/^([\w\s]+):(.*)\|(.*)$/';

    public function __construct(private string $line)
    {
        if (!self::isValid($this->line)) {
            throw new LogicException('Invalid bingo line format !');
        }
    }

    /**
     * @return string[]
     */
    public function winningNumbers(): array
    {
        return $this->retrieve(NumbersType::winning);
    }

    /**
     * @return string[]
     */
    public function myNumbers(): array
    {
        return $this->retrieve(NumbersType::obtained);
    }

    /**
     * @return string[]
     */
    public function matches(): array
    {
        $winningNumbers = $this->retrieve(NumbersType::winning);
        $myNumbers = $this->retrieve(NumbersType::obtained);

        $matches = array_intersect($winningNumbers, $myNumbers);
        sort($matches);

        return $matches;
    }

    public function calculate(): int
    {
        $counts = count($this->matches());

        if (0 === $counts) {
            return $counts;
        }

        return pow(2, $counts - 1);
    }

    private static function isValid(string $line): bool
    {
        return (bool) preg_match(self::PATTERN, $line);
    }

    /**
     * @return string[]
     */
    private function retrieve(NumbersType $type): array
    {
        $matches = [];
        preg_match(self::PATTERN, $this->line, $matches);

        $index = 'obtained' === $type->name ? 3 : 2;

        return array_filter(explode(' ', preg_replace('/\s{2,}/', ' ', trim($matches[$index]))));
    }
}
