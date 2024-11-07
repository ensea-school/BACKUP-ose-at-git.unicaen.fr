<?php

namespace OffreFormation\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Unicaen\BddAdmin\BddAwareTrait;

/**
 * Description of MajTauxMixiteCommand
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class MajTauxMixiteCommand extends Command
{

    use BddAwareTrait;

    protected function configure(): void
    {
        $this->setDescription('Mise à jour des taux de mixité');
    }



    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Mise à jour des taux de mixité');
        $io->writeln('<info>Actualisation des taux ...</info>');
        $this->bdd->exec("BEGIN UNICAEN_IMPORT.SYNCHRONISATION('ELEMENT_TAUX_REGIMES', 'JOIN element_pedagogique ep ON ep.id = element_pedagogique_id WHERE import_action = ''update'' AND annee_id >= OSE_PARAMETRE.GET_ANNEE_IMPORT'); END;");

        $io->writeln('<info>Actualisation des éléments pédagogiques ...</info>');
        $this->bdd->exec("BEGIN UNICAEN_IMPORT.SYNCHRONISATION('ELEMENT_PEDAGOGIQUE'); END;");

        $io->success("Mise à jour des taux de mixité terminée avec succés");


        return Command::SUCCESS;
    }
}