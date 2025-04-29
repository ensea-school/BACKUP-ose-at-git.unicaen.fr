<?php

namespace Intervenant\Command;

use Intervenant\Entity\Db\Intervenant;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Workflow\Service\WorkflowServiceAwareTrait;

/**
 * Description of CalculFeuilleDeRouteCommand
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class CalculFeuilleDeRouteCommand extends Command
{
    use WorkflowServiceAwareTrait;
    use EntityManagerAwareTrait;


    protected function configure(): void
    {
        $this
            ->setName(' calcul-feuille-de-route')
            ->setDescription('Recalcule la feuille de route d\'intervenant')
            ->addArgument('intervenantId', InputArgument::REQUIRED, 'Id de l\'intervenant pour lequel il faut recalculer la feuille de route');


    }



    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io            = new SymfonyStyle($input, $output);
        $intervenantId = $input->getArgument('intervenantId');
        if (empty($intervenantId)) {
            $intervenantId = $io->ask("Veuillez saisir l'ID de l'intervenant à actualiser :");
        }

        $intervenant = $this->getEntityManager()->getRepository(Intervenant::class)->find($intervenantId);
        if (!$intervenant) {
            $io->error('Intervenant n\'existe pas');
            return Command::FAILURE;
        }

        $io->title('Actualisation de la feuille de route de ' . $intervenant);
        $io->writeln('Année universitaire : ' . $intervenant->getAnnee());
        $io->writeln('Statut de l\'intervenant : ' . $intervenant->getStatut());
        $io->writeln('Calcul en cours ...');
        $this->getServiceWorkflow()->calculerTableauxBord([], $intervenant);
        $io->success('Feuille de route de ' . $intervenant . ' actualisée avec succés !');

        return Command::SUCCESS;


    }
}