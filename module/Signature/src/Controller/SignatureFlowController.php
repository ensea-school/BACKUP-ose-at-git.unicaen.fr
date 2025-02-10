<?php

namespace Signature\Controller;

use Application\Controller\AbstractController;
use Application\Entity\Db\Role;
use Application\Service\Traits\RoleServiceAwareTrait;
use Contrat\Service\ContratServiceAwareTrait;
use Signature\Form\SignatureFlowFormAwareTrait;
use Signature\Form\SignatureFlowStepFormAwareTrait;
use Signature\Service\SignatureFlowServiceAwareTrait;
use Signature\Service\SignatureFlowStepServiceAwareTrait;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenSignature\Entity\Db\Signature;
use UnicaenSignature\Entity\Db\SignatureFlow;
use UnicaenSignature\Entity\Db\SignatureFlowStep;
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
    use SignatureFlowStepFormAwareTrait;
    use SignatureFlowServiceAwareTrait;
    use SignatureFlowStepServiceAwareTrait;
    use EntityManagerAwareTrait;
    use RoleServiceAwareTrait;


    public function indexAction()
    {
        $listeSignatureFlow = $this->getProcessService()->getSignatureFlows();
        //Enrichissement des étapes du circuit pour l'affichage
        if ($listeSignatureFlow) {
            $listeSignatureFlow = $this->getServiceSignatureFlow()->formatDatasFlow($listeSignatureFlow);
        }

        $letterFileLevelsInfos = $this->getSignatureConfigurationService()->getLetterFiles();
        
        return compact('listeSignatureFlow', 'letterFileLevelsInfos');
    }



    public function saisirCircuitAction()
    {
        $signatureFlow = $this->getEvent()->getParam('signatureFlow');
        if (empty($signatureFlow)) {
            $signatureFlow = new SignatureFlow();
        }

        $form = $this->getFormSignatureFLow();

        $form->bindRequestSave($signatureFlow, $this->getRequest(), function ($signatureFlow) {
            $this->getServiceSignatureFlow()->save($signatureFlow);
        });

        return compact('form');
    }



    public function supprimerCircuitAction()
    {
        $signatureFlow = $this->getEvent()->getParam('signatureFlow');
        if ($signatureFlow instanceof SignatureFlow) {
            try{
                $this->getServiceSignatureFlow()->delete($signatureFlow);
            }catch (\Exception $e){
                if(str_contains($e->getMessage(), 'ORA-02292'))
                {
                    $this->flashMessenger()->addErrorMessage('Vous ne pouvez pas supprimer ce circuit de signature, car vous avez déjà des signatures électroniques qui l\'utilisent');
                }
                else{
                    $this->flashMessenger()->addErrorMessage($e->getMessage());
                }
            }
        }

        return true;
    }



    public function saisirEtapeAction()
    {
        $signatureFlow     = $this->getEvent()->getParam('signatureFlow');
        $signatureFlowStep = $this->getEvent()->getParam('signatureFlowStep');
        if (!$signatureFlow instanceof SignatureFlow) {
            throw new \Exception("Le circuit de signature n'est pas renseigné");
        }
        if (empty($signatureFlowStep)) {
            $signatureFlowStep = new SignatureFlowStep();
            $signatureFlowStep->setSignatureFlow($signatureFlow);
            $signatureFlowStep->setLetterfileName('esup');
            $signatureFlowStep->setLevel('');
        }
        $form = $this->getformSignatureFlowStep();

        $form->bindRequestSave($signatureFlowStep, $this->getRequest(), function ($signatureFlowStep) {
            $this->getServiceSignatureFlowStep()->save($signatureFlowStep);
        });

        return compact('form');
    }



    public function supprimerEtapeAction()
    {
        $signatureFlow     = $this->getEvent()->getParam('signatureFlow');
        $signatureFlowStep = $this->getEvent()->getParam('signatureFlowStep');
        if ($signatureFlowStep instanceof SignatureFlowStep &&
            $signatureFlow instanceof SignatureFlow) {
            //Si l'étape appartient bien au circuit de signature
            if ($signatureFlowStep->getSignatureFlow()->getId() == $signatureFlow->getId()) {
                $this->getServiceSignatureFlowStep()->delete($signatureFlowStep);
            }
        }

        return true;
    }

}

