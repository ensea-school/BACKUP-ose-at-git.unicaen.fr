<?php

namespace Signature\Command;

use Application\Command\OseCommandAbstract;

use Laminas\Json\Json;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use UnicaenSignature\Service\SignatureService;

class SignatureAddSignatureDocumentOseCommand extends OseCommandAbstract
{
    protected static $defaultName = 'signature:add-signature-document';



    protected function configure()
    {
        $this
            ->setDescription("Envoie un document pour signature dans esup signature")
            ->addArgument("documentPath", InputArgument::REQUIRED, "Chemin absolue du contrat à faire signer");
    }



    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io                  = $this->getIO($input, $output);
        $contratDocumentPath = $input->getArgument('documentPath');
        if (!file_exists($contratDocumentPath)) {
            $io->error("Fichier introuvable " . $contratDocumentPath);

            return self::FAILURE;
        }
        $io->title("Envoi du document pour signature : " . $contratDocumentPath);
        /**
         *
         * @var $signatureService SignatureService
         */

        $signatureService = $this->getServicemanager()->get(SignatureService::class);
        $keyDocument      = $signatureService->getLetterfileService()->getDefaultLetterFileStrategy()->addDocument(
            $contratDocumentPath,
            ["antony.lecourtes@unicaen.fr"],
            'Signature de votre contrat OSE',
            "Signature test OSE",
            "certSign",
            1
        );

        $io->success("Document envoyé pour signature : id#" . $keyDocument);
        $io->block("Résumé des informations du document à signer : ");
        $infos = $signatureService->getLetterfileService()->getDefaultLetterFileStrategy()->getSignRequestInfo($keyDocument);
        $io->block(json_encode($infos->toArray(), JSON_PRETTY_PRINT), null, 'block');

        return self::SUCCESS;
    }
}