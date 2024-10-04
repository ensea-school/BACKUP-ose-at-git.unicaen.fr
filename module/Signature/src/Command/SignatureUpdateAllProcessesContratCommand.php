<?php

namespace Signature\Command;

use Contrat\Entity\Db\Contrat;
use Contrat\Service\ContratService;
use DoctrineORMModule\Proxy\__CG__\UnicaenSignature\Entity\Db\Process;
use Laminas\ServiceManager\ServiceManager;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use UnicaenSignature\Command\SignatureCommandAbstract;
use UnicaenSignature\Service\ProcessService;

class SignatureUpdateAllProcessesContratCommand extends SignatureCommandAbstract
{
    protected static $defaultName = 'signature:update-process-contrat-all';



    protected function configure()
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
        $processService = \OseAdmin::instance()->container()->get(ProcessService::class);
        $contratService = \OseAdmin::instance()->container()->get(ContratService::class);

        $io = $this->getIO($input, $output);

        $headers     = ['id', 'label', 'status', 'msg'];
        $rows        = [];
        $listContrat = $contratService->getContratWithProcessWaiting();


        if (!empty($listContrat)) {
            foreach ($listContrat as $contrat) {
                /**
                 * @var Contrat $contrat
                 */
                $process = $contrat->getProcessSignature();
                $msg     = "Rien à faire";
                $row     = [
                    $process->getId(),
                    $process->getLabel(),
                    $process->getStatusText(),
                ];
                if ($process->isTriggerable()) {

                    try {
                        //On met à jour le process et le contrat qui lui est rattaché
                        //Récupération du contrat rattacher au process
                        $contratService->rafraichirProcessSignatureElectronique($contrat);
                        $msg = "Fait";
                    } catch (\Exception $e) {
                        $msg = 'Error : ' . $e->getMessage();
                    }
                }
                $row[]  = $msg;
                $rows[] = $row;
            }
        }

        $io->table($headers, $rows);


        return self::SUCCESS;
    }
}