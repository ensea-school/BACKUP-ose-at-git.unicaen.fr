<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Common\Exception\RuntimeException;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Listener\DossierListener;
use Application\Acl\IntervenantRole;
use Application\Service\ContextProviderAwareTrait;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\Workflow\WorkflowIntervenantAwareInterface;
use Application\Service\Workflow\WorkflowIntervenantAwareTrait;
use Application\Entity\Db\TypeValidation;

/**
 * Description of DossierController
 *
 * @method \Doctrine\ORM\EntityManager                em()
 * @method \Application\Controller\Plugin\Context     context()
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class DossierController extends AbstractActionController implements ContextProviderAwareInterface, WorkflowIntervenantAwareInterface
{
    use ContextProviderAwareTrait;
    use WorkflowIntervenantAwareTrait;

    /**
     * @var \Application\Entity\Db\IntervenantExterieur
     */
    private $intervenant;

    /**
     * @var \Zend\Form\Form
     */
    private $form;

    /**
     * @var bool
     */
    private $readonly = false;

    /**
     *
     * @return \Zend\View\Model\ViewModel
     * @throws \Common\Exception\MessageException
     */
    public function voirAction()
    {
        $intervenant = $this->context()->mandatory()->intervenantFromRoute();
        $dossier     = $intervenant->getDossier();
        $title       = "Données personnelles <small>$intervenant</small>";
        $short       = $this->params()->fromQuery('short', false);
        $view        = new \Zend\View\Model\ViewModel();

        if (!$dossier) {
            throw new \Common\Exception\MessageException("L'intervenant $intervenant n'a aucune donnée personnelle enregistrée.");
        }

        $view->setVariables(compact('intervenant', 'dossier', 'title', 'short'));

        return $view;
    }

    /**
     * Modification du dossier d'un intervenant.
     *
     * @return type
     * @throws RuntimeException
     */
    public function modifierAction()
    {
        $role    = $this->getContextProvider()->getSelectedIdentityRole();
        $service = $this->getDossierService();
        $this->form    = $this->getFormModifier();

        if ($role instanceof IntervenantRole) {
            $this->intervenant = $role->getIntervenant();
        }
        else {
            $this->intervenant = $this->context()->mandatory()->intervenantFromRoute();
        }

        $validation = null;
        $dossierValide = $this->getServiceLocator()->get('DossierValideRule')->setIntervenant($this->intervenant);
        if ($dossierValide->isRelevant() && $dossierValide->execute()) {
            $this->readonly = true;
            if (count($validations = $this->intervenant->getValidation($this->getTypeValidationDossier()))) {
                $validation = $validations->first();
            }
        }

        $this->form->get('submit')->setAttribute('value', $this->getSubmitButtonLabel());

        $service->canAdd($this->intervenant, true);

        if (!($dossier = $this->intervenant->getDossier())) {
            $dossier = $service->newEntity()->fromIntervenant($this->intervenant);
            $this->intervenant->setDossier($dossier);
        }

        $this->form->bind($this->intervenant);

        if (!$this->readonly && $this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $this->form->setData($data);
            if ($this->form->isValid()) {
                $this->getDossierService()->enregistrerDossier($dossier, $this->intervenant);
//                $notified = $this->notify($this->intervenant);
                $this->flashMessenger()->addSuccessMessage("Données personnelles enregistrées avec succès.");

                return $this->redirect()->toUrl($this->getModifierRedirectionUrl());
            }
        }

        $view = new \Zend\View\Model\ViewModel([
            'intervenant' => $this->intervenant,
            'form'        => $this->form,
            'validation'  => $validation,
            'readonly'    => $this->readonly,
        ]);

        return $view;
    }

    /**
     * @return string
     */
    private function getSubmitButtonLabel()
    {
        $label = null;
        $role  = $this->getContextProvider()->getSelectedIdentityRole();
        $wf    = $this->getWorkflowIntervenant()->setIntervenant($this->intervenant); /* @var $wf \Application\Service\Workflow\Workflow */
        $step  = $wf->getNextStep($wf->getStepForCurrentRoute());

        if ($role instanceof IntervenantRole) {
            $role->getIntervenant();
            $label = $step ? ' et ' . lcfirst($step->getLabel($role)) . '...' : null;
        }

        $label = "J'enregistre" . $label;

        return $label;
    }

    /**
     * @return string
     */
    private function getModifierRedirectionUrl()
    {
        $wf    = $this->getWorkflowIntervenant()->setIntervenant($this->intervenant); /* @var $wf \Application\Service\Workflow\Workflow */
        $step  = $wf->getNextStep($wf->getStepForCurrentRoute());

        $url   = $step ? $wf->getStepUrl($step) : $this->url()->fromRoute(null, [], [], true);

        return $url;
    }

    /**
     * @return TypeValidation
     */
    private function getTypeValidationDossier()
    {
        $qb = $this->getTypeValidationService()->finderByCode(TypeValidation::CODE_DONNEES_PERSO);

        return $qb->getQuery()->getOneOrNullResult();
    }

    protected function notify(Intervenant $intervenant)
    {
        if (DossierListener::$created || DossierListener::$modified) {
            // envoyer un mail au gestionnaire
            return true;
        }

        return false;
    }

    /**
     * @return \Application\Form\Intervenant\Dossier
     */
    private function getFormModifier()
    {
        return $this->getServiceLocator()->get('FormElementManager')->get('IntervenantDossier');
    }

    /**
     * @return \Application\Service\TypeValidation
     */
    private function getTypeValidationService()
    {
        return $this->getServiceLocator()->get('ApplicationTypeValidation');
    }

    /**
     * @return \Application\Service\Dossier
     */
    private function getDossierService()
    {
        return $this->getServiceLocator()->get('ApplicationDossier');
    }
}
