<?php

namespace Administration\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Unicaen\BddAdmin\BddAwareTrait;

/**
 * Description of MajExportsCommand
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class MajExportsCommand extends Command
{
    use BddAwareTrait;

    protected function configure(): void
    {
        $this->setName('maj-exports')
            ->setDescription('Lance le rafraîchissement des vues matérialisées dans le cadre de l\'utilisation des univers BO');

    }



    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Mise à jour des vues matérialisées');
        $io->writeln('Mise à jour en cours...');
        $this->bdd->refreshMaterializedViews();
        $io->success('Mise à jour des vues matérialisées réalisée avec succés');

        Command::SUCCESS;

    }
}