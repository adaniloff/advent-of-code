<?php

declare(strict_types=1);

namespace App\Year2023\Day2\Command;

use App\Year2023\Day2\GamesManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\{InputInterface};
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'aoc:2023:day2',
    description: 'Resolve the day2 puzzle: https://adventofcode.com/2023/day/2',
)]
final class Day2ComputeCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $games = GamesManager::fileToGames(__DIR__.'/../dataset.txt');
        $manager = new GamesManager($games, maxRed: 12, maxGreen: 13, maxBlue: 14);

        $io->success(sprintf('The computed value is %s', $manager->computeIds()));
        $io->success(sprintf('The power value is %s', $manager->computePower()));

        return Command::SUCCESS;
    }
}
