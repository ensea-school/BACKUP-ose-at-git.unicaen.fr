<?php

namespace Signature\Controller;

use Application\Controller\AbstractController;
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
        $service = $this->getSignatureService()->getSignatures();
        var_dump($service);
        die;

        return $this->redirect()->toUrl($this->url()->fromRoute('signatures', [], [], true));
    }

}

