<?php

declare(strict_types=1);

namespace App\Year2023\Day6;

use App\FileReader\Reader;

final readonly class RaceReader
{
    /**
     * @var array|string[]
     */
    private array $data;

    public function __construct(string $filename)
    {
        $this->data = Reader::toArray($filename);
    }

    /**
     * @return Race[]
     */
    public function read(): array
    {
        [$times, $distances] = array_map(function ($line) {
            return array_values(array_filter(explode(' ', $line)));
        }, $this->data);

        $races = [];
        for ($i = 1; $i < count($times); ++$i) {
            $races[] = new Race((int) $times[$i], (int) $distances[$i]);
        }

        return $races;
    }

    public function readNiceKerning(): Race
    {
        $time = '';
        $distance = '';
        foreach ($this->read() as $race) {
            $time .= $race->time;
            $distance .= $race->distance;
        }

        return new Race((int) $time, (int) $distance);
    }
}
