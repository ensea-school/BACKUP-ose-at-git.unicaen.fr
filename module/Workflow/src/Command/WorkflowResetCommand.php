<?php

namespace Workflow\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Unicaen\BddAdmin\BddAwareTrait;
use Workflow\Service\WorkflowServiceAwareTrait;

/**
 * Description of WorkflowResetCommand
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class WorkflowResetCommand extends Command
{
    use BddAwareTrait;
    use WorkflowServiceAwareTrait;

    protected function configure(): void
    {
        $this->setDescription('Réinitialisation de la configuration du workflow');
    }



    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io  = new SymfonyStyle($input, $output);
        $bdd = $this->getBdd()->setLogger($io);

        $io->title($this->getDescription());
        try {
            $bdd->data()->addAction('workflow-reset', 'Réinitialisation du workflow');

            $config = $bdd->data()->getOption('config');
            // ignore plus ces colonnes en cas d'update afin de tout remettre à l'état initial
            unset($config['WORKFLOW_ETAPE']['options']['update-ignore-cols']);
            $bdd->data()->setOption('config', $config);

            $bdd->data()->run('workflow-reset');
            $this->getServiceWorkflow()->clearEtapesCache();
            $io->success('Workflow réinitialisé');
        } catch (\Exception $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}