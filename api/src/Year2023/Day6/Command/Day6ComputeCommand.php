<?php

declare(strict_types=1);

namespace App\Year2023\Day6\Command;

use App\Year2023\Day6\{RaceReader, RaceSimulator};
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\{InputInterface};
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'aoc:2023:day6',
    description: 'Resolve the day6 puzzle: https://adventofcode.com/2023/day/6',
)]
final class Day6ComputeCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $reader = new RaceReader(__DIR__.'/../dataset.txt');

        $power = 1;
        foreach ($reader->read() as $race) {
            $calculator = new RaceSimulator($race);
            $power *= count($calculator->possibilities(false));
        }

        $race = $reader->readNiceKerning();
        $calculator = new RaceSimulator($race);

        $io->success(sprintf('The computed value is %s', $power));
        $io->success(sprintf('The single race value is %s', count($calculator->possibilities(false))));

        return Command::SUCCESS;
    }
}
