<?php

namespace Signature\Controller;

use Application\Controller\AbstractController;
use Contrat\Service\ContratServiceAwareTrait;
use Signature\Form\SignatureFlowFormAwareTrait;
use UnicaenSignature\Entity\Db\Signature;
use UnicaenSignature\Entity\Db\SignatureRecipient;
use UnicaenSignature\Service\ProcessServiceAwareTrait;
use UnicaenSignature\Service\SignatureConfigurationServiceAwareTrait;
use UnicaenSignature\Service\SignatureService;
use UnicaenSignature\Service\SignatureServiceAwareTrait;
use UnicaenVue\View\Model\VueModel;


/**
 * Description of SignatureFlowController
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class SignatureFlowController extends AbstractController
{

    use SignatureServiceAwareTrait;
    use SignatureConfigurationServiceAwareTrait;
    use ProcessServiceAwareTrait;
    use SignatureFlowFormAwareTrait;

    public function indexAction()
    {
        $listeSignatureFlow = $this->getProcessService()->getSignatureFlows();

        return compact('listeSignatureFlow');
    }



    public function saisirAction()
    {
        $signatureFlow = $this->getEvent()->getParam('signatureFlow');
        $form          = $this->getFormSignatureFLow();

        $form->bindRequestSave($signatureFlow, $this->getRequest(), function($signatureFlow){

        });

        return compact('form');
    }

}

