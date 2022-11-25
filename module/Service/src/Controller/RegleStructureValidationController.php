<?php

namespace Service\Controller;

use Application\Controller\AbstractController;
use Service\Entity\Db\RegleStructureValidation;
use Service\Form\RegleStructureValidationFormAwareTrait;
use Service\Service\RegleStructureValidationServiceAwareTrait;


/**
 * Description of RegleStructureValidationController
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class RegleStructureValidationController extends AbstractController
{
    use RegleStructureValidationServiceAwareTrait;
    use RegleStructureValidationFormAwareTrait;

    public function indexAction()
    {
        $serviceRVS = $this->getServiceRegleStructureValidation();
        $listeRsv   = $serviceRVS->getList();

        return compact('listeRsv');
    }



    public function saisieAction()
    {
        $regleStructureValidation = $this->getEvent()->getParam('regleStructureValidation');
        $form                     = $this->getFormRegleStructureValidation();
        $title                    = 'Édition de la régle de validation';
        $form->bindRequestSave($regleStructureValidation, $this->getRequest(), function (RegleStructureValidation $rsv) {
            try {
                $this->getServiceRegleStructureValidation()->save($rsv);
                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
            } catch (\Exception $e) {
                $message = $this->translate($e);

                if (false !== strpos($message, 'ORA - 00001')) {
                    $this->flashMessenger()->addErrorMessage("Règle non enregistrée car elle existe déjà dans OSE");
                } else {
                    $this->flashMessenger()->addErrorMessage($this->translate($e));
                }
            }
        });

        return compact('form', 'title');
    }

}