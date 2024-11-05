<?php

namespace Administration\Command;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use UnicaenTbl\Service\TableauBordServiceAwareTrait;

/**
 * Description of CalculTableauxBordCommand
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class CalculTableauxBordCommand extends SymfonyCommand
{
    use TableauBordServiceAwareTrait;

    protected function configure(): void
    {
        $this->setDescription('Calcul des tableaux de bord');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io  = new SymfonyStyle($input, $output);

        $io->title('Calcul des tableaux de bord');

        $result = $this->getServiceTableauBord()->calculerTout(['formule'],function (array $d) use ($io) {
            $tblLine = 'Tableau de bord : ' . str_pad($d['tableau-bord'], 30);

            $io->write($tblLine);
            $io->write('Calcul en cours...');
        }, function (array $d) use ($io) {
            $tblLine = 'Tableau de bord : ' . str_pad($d['tableau-bord'], 30);
            $io->write("\r" . $tblLine);
            if ($d['result']) {
                $duree = round($d['duree'], 3) . ' secondes';
                $io->writeln('Effectué en ' . $duree);
            } else {
                $io->writeln('Erreur : ' . $d['exception']->getMessage());
            }
        });

        $io->comment('Fin du calcul des tableaux de bord');
        if ($result) {
            $io->success('Tout c\'est bien passé');
            return SymfonyCommand::SUCCESS;
        } else {
            $io->error('Attention : des erreurs ont été rencontrées!!');
            return SymfonyCommand::FAILURE;
        }
    }
}