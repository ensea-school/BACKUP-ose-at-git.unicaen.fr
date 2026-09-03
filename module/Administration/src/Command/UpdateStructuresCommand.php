<?php

namespace Administration\Command;

use Administration\Service\GitRepoServiceAwareTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Description of UpdateStructuresCommand
 *
 */
class UpdateStructuresCommand extends Command
{
    use GitRepoServiceAwareTrait;

    private OutputInterface $output;



    protected function configure(): void
    {
        $this
            ->setDescription('Mise à jour des id arborescent de structures');
    }



    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());

        return Command::SUCCESS;
    }
}