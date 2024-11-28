<?php

namespace Formule\Command;

use Application\Service\Traits\AnneeServiceAwareTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use UnicaenTbl\Service\TableauBordServiceAwareTrait;

/**
 * Description of CalculCommand
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class CalculCommand extends Command
{
    use AnneeServiceAwareTrait;
    use TableauBordServiceAwareTrait;

    protected function configure(): void
    {
        $this->setDescription('Recalcul de toutes les formules');
    }



    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io  = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());

        $io->warning("Ce traitement peut prendre plusieurs minutes");

        $annees = $this->getServiceAnnee()->getActives();
        foreach ($annees as $annee) {
            $io->comment('Calcul pour l\'année '.$annee->getLibelle());
            $params = ['ANNEE_ID' => $annee->getId()];
            $this->getServiceTableauBord()->calculer('formule', $params);
        }

        return Command::SUCCESS;
    }
}