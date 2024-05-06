<?php

namespace Signature\Controller;

use Application\Controller\AbstractController;
use UnicaenSignature\Service\SignatureService;


/**
 * Description of SignatureController
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class SignatureController extends AbstractController
{

    protected SignatureService|null $serviceSignature = null;



    public function indexAction()
    {
        return [];
    }



    public function signatureContratAction()
    {
        var_dump($this->serviceSignature->getSignatures());
        die;

        return $this->redirect()->toUrl($this->url()->fromRoute('signatures', [], [], true));
    }



    public function setServiceSignature(SignatureService $serviceSignature)
    {
        $this->serviceSignature = $serviceSignature;

        return $this;
    }

}

