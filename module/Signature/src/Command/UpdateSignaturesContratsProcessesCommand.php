<?php

namespace Signature\Command;

use Contrat\Entity\Db\Contrat;
use Contrat\Service\ContratService;
use Contrat\Service\ContratServiceAwareTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use UnicaenSignature\Service\ProcessService;
use UnicaenSignature\Service\ProcessServiceAwareTrait;

class UpdateSignaturesContratsProcessesCommand extends Command
{
    use ContratServiceAwareTrait;
    use ProcessServiceAwareTrait;


    protected function configure(): void
    {
        $this
            ->setDescription("Actualise l'état des procédures de signature de contrat/avenant en cours");
    }



    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /**
         * @var ProcessService $processService
         * @var ContratService $contratService
         */
        $processService = $this->getProcessService();
        $contratService = $this->getServiceContrat();
        $io             = new SymfonyStyle($input, $output);

        $headers     = ['id', 'intervenant', 'label', 'status', 'msg'];
        $rows        = [];
        $listContrat = $contratService->getContratWithProcessWaiting();


        if (!empty($listContrat)) {
            foreach ($listContrat as $contrat) {
                /**
                 * @var Contrat $contrat
                 */
                $intervenant = $contrat->getIntervenant();
                $process     = $contrat->getProcessSignature();
                $msg         = "Rien à faire";
                $row         = [
                    $process->getId(),
                    $process->getLabel(),
                    $intervenant->getPrenom() . ' ' . $intervenant->getNomUsuel(),
                    $process->getStatusText(),
                ];
                if ($process->isTriggerable()) {

                    try {
                        //On met à jour le process et le contrat qui lui est rattaché
                        //Récupération du contrat rattacher au process
                        $etat = $contratService->rafraichirProcessSignatureElectronique($contrat);
                        $row  = [
                            $process->getId(),
                            $process->getLabel(),
                            $intervenant->getPrenom() . ' ' . $intervenant->getNomUsuel(),
                            $process->getStatusText(),
                        ];
                        $msg  = ($etat) ? "Mis à jour" : 'Aucune mise à jour';

                    } catch (\Exception $e) {
                        $msg = 'Error : ' . $e->getMessage();
                    }
                }
                $row[]  = $msg;
                $rows[] = $row;
            }
            $io->table($headers, $rows);
        } else {
            $io->info("Aucun contrat avec des signatures en cours");
        }


        return self::SUCCESS;
    }
}