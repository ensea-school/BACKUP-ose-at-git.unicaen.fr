<?php

namespace Application\Controller;


use Application\Entity\Db\FormuleTestIntervenant;
use Application\Form\FormuleTest\Traits\IntervenantFormAwareTrait;
use Application\Service\Traits\FormuleTestIntervenantServiceAwareTrait;

/**
 * Description of FormuleController
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class FormuleController extends AbstractController
{
    use FormuleTestIntervenantServiceAwareTrait;
    use IntervenantFormAwareTrait;

    public function testAction()
    {
        $fti = $this->getServiceFormuleTestIntervenant()->getList();

        return compact('fti', 'title');
    }



    public function testSaisirAction()
    {
        /* @var $formuleTestIntervenant FormuleTestIntervenant */
        $formuleTestIntervenant = $this->getEvent()->getParam('formuleTestIntervenant');

        $form = $this->getFormFormuleTestIntervenant();

        if (!$formuleTestIntervenant) {
            $title      = 'Ajout d\'un test de formule';
            $formuleTestIntervenant = new FormuleTestIntervenant();
        } else {
            $title = 'Modification d\'un test de formule';
        }

        $form->bindRequestSave($formuleTestIntervenant, $this->getRequest(), function (FormuleTestIntervenant $fti) {
            try {
                $this->getServiceFormuleTestIntervenant()->save($fti);
                $this->flashMessenger()->addSuccessMessage('Test de formule bien enregistré');
                return $this->redirect()->toRoute('formule-calcul/test');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });

        return compact('form', 'formuleTestIntervenant', 'title');
    }



    public function testSupprimerAction()
    {
        /* @var $formuleTestIntervenant FormuleTestIntervenant */
        $formuleTestIntervenant = $this->getEvent()->getParam('FormuleTestIntervenant');

        try {
            $this->getServiceFormuleTestIntervenant()->delete($formuleTestIntervenant);
            $this->flashMessenger()->addSuccessMessage("Test de formule supprimé avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage(DbException::translate($e)->getMessage());
        }

        return new MessengerViewModel();
    }



    public function calculerToutAction()
    {
        $this->em()->getConnection()->exec('BEGIN OSE_FORMULE.CALCULER_TOUT; END;');
    }

}