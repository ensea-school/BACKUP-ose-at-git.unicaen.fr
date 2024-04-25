<?php

namespace Signature\Command;

use Laminas\ServiceManager\ServiceManager;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use UnicaenSignature\Command\SignatureCommandAbstract;

class SignatureLetterfileInfosOseCommand extends SignatureCommandAbstract
{
    // php bin/oscar.php signature:add-signature "$(<module/UnicaenSignature/test/datas/json-datas-add-signature.json)"

    const ARG_ID = "id";

    protected static $defaultName = 'signature:letterfile-info';



    protected function configure()
    {
        $this
            ->setDescription("Récupération des informations sur la procédure de signature dans ESUP")
            ->addArgument(self::ARG_ID, InputArgument::REQUIRED, "données JSON");
    }



    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io    = $this->getIO($input, $output);
        $id    = $input->getArgument(self::ARG_ID);
        $infos = $this->getSignatureService()->getLetterfileService()->getDefaultLetterFileStrategy()->getSignRequestInfo($id);
        echo json_encode($infos->toArray(), JSON_PRETTY_PRINT);
        

        return self::SUCCESS;
    }
}