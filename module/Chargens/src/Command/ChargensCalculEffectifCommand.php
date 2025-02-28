<?php

namespace Chargens\Command;

use Chargens\Provider\ChargensProviderAwareTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Description of ChargensCalculEffectifCommand
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ChargensCalculEffectifCommand extends Command
{
    use ChargensProviderAwareTrait;

    protected function configure(): void
    {
        $this->setName('chargens-calcul-effectif')
            ->setDescription('Recalcule la charge d\'enseignement par scénario');

    }



    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Charges d\'enseignement : calcul de tous les effectifs des noeuds');
        $io->info("Attention : ce traitement peut être long.");

        $scenarioNoeudsEffectifs = $this->getProviderChargensChargens()->getScenarioNoeudsEffectifs();


        $count = count($scenarioNoeudsEffectifs);

        foreach ($scenarioNoeudsEffectifs as $index => $scenarioNoeudEffectif) {
            $index++;
            $annee = ((int)$scenarioNoeudEffectif['ANNEE_ID']) . '/' . ((int)$scenarioNoeudEffectif['ANNEE_ID'] + 1);
            $io->writeln("Calcul de la formation $index / $count (année $annee)...");
            $this->getProviderChargensChargens()->calculChargeScenarioNoeudEffectif($scenarioNoeudEffectif);
        }

        $io->success('Charge d\'enseignement calculée avec succés');

        return Command::SUCCESS;

    }
}