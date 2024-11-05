<?php

namespace Administration\Command;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Description of UpdateCodeCommand
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class UpdateCodeCommand extends SymfonyCommand
{
    protected function configure(): void
    {
        $this->setDescription('... description à adapter ...');
    }



    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io  = new SymfonyStyle($input, $output);
$io->title('MAJ du code');
        return SymfonyCommand::SUCCESS;
    }
}