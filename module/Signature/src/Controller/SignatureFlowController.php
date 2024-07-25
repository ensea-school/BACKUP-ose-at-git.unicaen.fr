<?php

namespace Signature\Controller;

use Application\Controller\AbstractController;
use Contrat\Service\ContratServiceAwareTrait;
use Signature\Form\SignatureFlowFormAwareTrait;
use Signature\Service\SignatureFlowServiceAwareTrait;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenSignature\Entity\Db\Signature;
use UnicaenSignature\Entity\Db\SignatureFlow;
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
    use SignatureFlowServiceAwareTrait;
    use EntityManagerAwareTrait;


    public function indexAction()
    {
        $listeSignatureFlow = $this->getProcessService()->getSignatureFlows();

        return compact('listeSignatureFlow');
    }



    public function saisirCircuitAction()
    {
        $signatureFlow = $this->getEvent()->getParam('signatureFlow');
        if (empty($signatureFlow)) {
            $signatureFlow = new SignatureFlow();
        }

        $form = $this->getFormSignatureFLow();

        $form->bindRequestSave($signatureFlow, $this->getRequest(), function($signatureFlow){
            $this->getServiceSignatureFlow()->save($signatureFlow);
        });

        return compact('form');
    }



    public function supprimerCircuitAction()
    {
        $signatureFlow = $this->getEvent()->getParam('signatureFlow');
        if ($signatureFlow instanceof SignatureFlow) {
            $this->getServiceSignatureFlow()->delete($signatureFlow);
        }

        return $this->redirect()->toRoute('signature-flow');
    }

}

