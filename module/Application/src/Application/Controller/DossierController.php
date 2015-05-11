<?php

namespace Application\Controller;

use Application\Acl\IntervenantRole;
use Application\Controller\Plugin\Context;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\IntervenantExterieur;
use Application\Entity\Db\StatutIntervenant;
use Application\Entity\Db\Listener\DossierListener;
use Application\Entity\Db\TypeValidation;
use Application\Form\Intervenant\Dossier as DossierForm;
use Application\Form\Intervenant\DossierFieldset;
use Application\Service\Traits\ContextAwareTrait;
use Application\Service\Traits\DossierAwareTrait;
use Application\Service\Traits\IntervenantAwareTrait;
use Application\Service\Traits\ServiceAwareTrait;
use Application\Service\Traits\ValidationAwareTrait;
use Application\Service\Traits\StatutIntervenantAwareTrait;
use Application\Service\Workflow\Workflow;
use Application\Service\Workflow\WorkflowIntervenantAwareInterface;
use Application\Service\Workflow\WorkflowIntervenantAwareTrait;
use Common\Exception\MessageException;
use Common\Exception\RuntimeException;
use Doctrine\ORM\EntityManager;
use NumberFormatter;
use UnicaenApp\Controller\Plugin\MessengerPlugin;
use UnicaenApp\Util;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Description of DossierController
 *
 * @method EntityManager em()
 * @method Context       context()
 * @method MessengerPlugin messenger()
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class DossierController extends AbstractActionController implements WorkflowIntervenantAwareInterface
{
    use WorkflowIntervenantAwareTrait,
        ContextAwareTrait,
        IntervenantAwareTrait,
        ServiceAwareTrait,
        DossierAwareTrait,
        ValidationAwareTrait,
        StatutIntervenantAwareTrait
    ;

    /**
     * @var IntervenantExterieur
     */
    private $intervenant;

    /**
     * @var DossierForm
     */
    private $form;

    /**
     * @var bool
     */
    private $readonly = false;

    /**
     * Initialisation des filtres Doctrine pour les historique.
     * Objectif : laisser passer les enregistrements passés en historique pour mettre en évidence ensuite les erreurs éventuelles
     * (services sur des enseignements fermés, etc.)
     */
    protected function initFilters()
    {
        $this->em()->getFilters()->enable('historique')->init(
            [
                'Application\Entity\Db\Validation',
                'Application\Entity\Db\TypeValidation',
                'Application\Entity\Db\Dossier',
            ],
            $this->getServiceContext()->getDateObservation()
        );
    }

    /**
     *
     * @return ViewModel
     * @throws MessageException
     */
    public function voirAction()
    {
        $intervenant = $this->context()->mandatory()->intervenantFromRoute();
        $dossier     = $intervenant->getDossier();
        $title       = "Données personnelles <small>$intervenant</small>";
        $short       = $this->params()->fromQuery('short', false);
        $view        = new ViewModel();

        if (!$dossier) {
            throw new MessageException("L'intervenant $intervenant n'a aucune donnée personnelle enregistrée.");
        }

        $view->setVariables(compact('intervenant', 'dossier', 'title', 'short'));

        return $view;
    }

    /**
     * Modification du dossier d'un intervenant.
     *
     * @return ViewModel
     * @throws RuntimeException
     */
    public function modifierAction()
    {
        $role       = $this->getServiceContext()->getSelectedIdentityRole();
        $service    = $this->getServiceDossier();
        $validation = null;

        $this->initFilters();
        
        if ($role instanceof IntervenantRole) {
            $this->intervenant = $role->getIntervenant();
        }
        else {
            $this->intervenant = $this->context()->mandatory()->intervenantFromRoute();
        }

        $this->form = $this->getFormModifier();
        
        $serviceValidation = $this->getServiceValidation();
        $qb = $serviceValidation->finderByType(TypeValidation::CODE_DONNEES_PERSO);
        $serviceValidation->finderByIntervenant($this->intervenant, $qb);
        $serviceValidation->finderByHistorique($qb);
        $validations = $serviceValidation->getList($qb);
        if (count($validations)) {
            $validation = current($validations);
        }

        if ($validation) {
            $this->readonly = true;
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
                $this->getServiceDossier()->enregistrerDossier($dossier, $this->intervenant);
//                $notified = $this->notify($this->intervenant);
                $this->flashMessenger()->addSuccessMessage("Données personnelles enregistrées avec succès.");
                
                return $this->redirect()->toUrl($this->getModifierRedirectionUrl());
            }
        }
        
        $view = new ViewModel([
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
        $role  = $this->getServiceContext()->getSelectedIdentityRole();
        $wf    = $this->getWorkflowIntervenant()->setIntervenant($this->intervenant); /* @var $wf Workflow */
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
        $role = $this->getServiceContext()->getSelectedIdentityRole();
        $url  = $this->url()->fromRoute(null, [], [], true);
        
        if ($role instanceof IntervenantRole) {
            $wf       = $this->getWorkflowIntervenant()->setIntervenant($this->intervenant); /* @var $wf Workflow */
            $nextStep = $wf->getNextStep($wf->getStepForCurrentRoute());
            if ($nextStep) {
                $url = $wf->getStepUrl($nextStep);
            }
        }
        
        return $url;
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
     * @return DossierForm
     */
    private function getFormModifier()
    {
        $form            = $this->getServiceLocator()->get('FormElementManager')->get('IntervenantDossier'); /* @var $form DossierForm */
        $dossierFieldset = $form->get('dossier'); /* @var $dossierFieldset DossierFieldset */
        
        $anneePrecedente            = $this->getServiceContext()->getAnneePrecedente();
        $vacExistantAnneePrecedente = $this->intervenantVacataireAnneesPrecedentes(1);
        $appExistaitAnneePrecedente = $this->getServiceContext()->applicationExists($anneePrecedente);
        $vacExistantAnneePrecedente = null;
        $appExistaitAnneePrecedente = true;
        
        if ($vacExistantAnneePrecedente) {            
            /**
             * Si l'intervenant était un vacataire connu l'année précédente, alors
             * la question "Avez-vous exercé une activité..." est retirée puisque la réponse est forcément OUI.
             */
            $dossierFieldset->remove('premierRecrutement');
            
            $hetd = Util::formattedFloat(
                    $this->getServiceService()->getTotalHetdIntervenant($vacExistantAnneePrecedente), 
                    NumberFormatter::DECIMAL, 
                    2);
            $this->messenger()->addMessage(
                    sprintf("Vous avez effectué %s HETD en %s.", $hetd, $vacExistantAnneePrecedente->getAnnee()), null, 100);
        } 
        elseif ($appExistaitAnneePrecedente) {
            /**
             * Si l'intervenant n'est pas trouvé comme vacataire l'année précédente
             * malgré que l'application était en service l'année précédente,
             * alors on ne propose pas le statut "Sans emploi et non étudiant".
             * En effet, le statut "Sans emploi et non étudiant" n'est pertinent que pour un intervenant
             * ayant été vacataire l'année précédente (et ayant perdu son activité principale).
             */
            $statutSelect = $dossierFieldset->get('statut'); /* @var $statut \Application\Form\Intervenant\StatutSelect */
            $statutSelect->getProxy()->setStatutsToRemove([ 
                $this->getServiceStatutIntervenant()->getRepo()->findOneBySourceCode(StatutIntervenant::SS_EMPLOI_NON_ETUD)
            ]);
        }
        
        /**
         * L'adresse mail perso n'est pas demandée aux BIATSS.
         */
        if ($this->intervenant->getStatut()->estBiatss()) {
            $dossierFieldset->remove('emailPerso');
        }
        
        return $form;
    }

    /**
     * Détermine si l'intervenant courant était connu comme vacataire les années précédentes
     * dans l'application.
     * 
     * @param int $x Si x = 3 par exemple, on recherche l'intervenant en N-1, N-2 et N-3.
     * @return Intervenant Intervenant de l'année précédente
     */
    private function intervenantVacataireAnneesPrecedentes($x = 1)
    {
        $sourceCode = $this->intervenant->getSourceCode();
        
        for ($i = 1; $i <= $x; $i++) {
            $annee       = $this->getServiceContext()->getAnneeNmoins($i);
            $qb          = $this->getServiceIntervenant()->finderBySourceCodeAndAnnee($sourceCode, $annee);
            $intervenant = $qb->getQuery()->getOneOrNullResult(); /* @var $intervenant Intervenant */
            
            if ($intervenant && $intervenant->getStatut()->estVacataire() && $intervenant->getStatut()->getPeutSaisirService()) {
                return $intervenant;
            }
        }
        
        return null;
    }
}