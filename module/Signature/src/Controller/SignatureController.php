<?php

namespace Signature\Controller;

use Application\Controller\AbstractController;
use UnicaenSignature\Entity\Db\Signature;
use UnicaenSignature\Entity\Db\SignatureRecipient;
use UnicaenSignature\Service\SignatureConfigurationServiceAwareTrait;
use UnicaenSignature\Service\SignatureService;
use UnicaenSignature\Service\SignatureServiceAwareTrait;


/**
 * Description of SignatureController
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class SignatureController extends AbstractController
{

    use SignatureServiceAwareTrait;
    use SignatureConfigurationServiceAwareTrait;

    public function indexAction()
    {
        return [];
    }



    public function signatureContratAction()
    {

        //$service = $this->serviceSignature->getSignatures();
        /**
         * @var SignatureService $service
         */
        $serviceSignature = $this->getSignatureService()->getSignatures();
        $signature        = $serviceSignature->
        var_dump($service);
        die;

        return $this->redirect()->toUrl($this->url()->fromRoute('signatures', [], [], true));
    }



    public function configurationAction()
    {
        $parapheurs = $this->getSignatureConfigurationService()->getLetterfileConfiguration();
        $logger     = $this->getSignatureConfigurationService()->getLoggerConfiguration();


        return compact('logger', 'parapheurs');
    }



    public function signatureSimpleAction()
    {
        $letterfileKey = 'esup';
        $letterFile    = $this->getSignatureService()->getLetterfileService()->getLetterFileStrategy($letterfileKey);
        $typeSignature = $this->getSignatureService()->getMarksSelectFrom(array_keys($letterFile->getLevels()));

        $documentPath = 'contrat_U01_Bridenne_61774.pdf';
        $signature    = new Signature();
        $signature->setLetterfileKey($letterFile->getName());
        $signature->setType('sign_visual')
            ->setLabel('Test')
            ->setAllSignToComplete(true)
            ->setDescription('Signature test')
            ->setDocumentPath($documentPath);

        //On traite les destinataires
        $destinataires  = [];
        $data['emails'] = 'anthony.lecourtes@gmail.com';
        $postedEmails   = explode(',', $data['emails']);
        foreach ($postedEmails as $email) {
            $sr = new SignatureRecipient();
            $sr->setSignature($signature);
            $sr->setStatus(Signature::STATUS_SIGNATURE_DRAFT);
            $sr->setEmail($email);
            $sr->setPhone('0679434732');
            $destinataires[] = $sr;
        }
        $signature->setRecipients($destinataires);


        $this->getSignatureService()->saveNewSignature($signature, true);
    }

}

