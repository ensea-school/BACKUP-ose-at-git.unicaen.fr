<?php

namespace Application\Controller;

use Application\Constants;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\WfEtape;
use Application\Exception\DbException;
use Application\Form\Intervenant\DossierValidation;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\ContextAwareTrait;
use Application\Service\Traits\DossierAwareTrait;
use Application\Service\Traits\ServiceAwareTrait;
use Application\Service\Traits\ValidationAwareTrait;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use RuntimeException;
use NumberFormatter;
use UnicaenApp\Util;
use UnicaenApp\View\Model\MessengerViewModel;
use UnicaenAuth\Service\UserContext;
use Zend\View\Model\ViewModel;


class DossierController extends AbstractController
{
    use ContextAwareTrait;
    use ServiceAwareTrait;
    use DossierAwareTrait;
    use WorkflowServiceAwareTrait;
    use ValidationAwareTrait;
    use \Application\Form\Intervenant\Traits\DossierAwareTrait;



    /**
     * Initialisation des filtres Doctrine pour les historique.
     * Objectif : laisser passer les enregistrements passés en historique pour mettre en évidence ensuite les erreurs
     * éventuelles
     * (services sur des enseignements fermés, etc.)
     *
     * @see \Common\ORM\Filter\HistoriqueFilter
     */
    protected function initFilters()
    {
        $this->em()->getFilters()->enable('historique')->init([
            \Application\Entity\Db\Intervenant::class,
            \Application\Entity\Db\Validation::class,
            \Application\Entity\Db\Dossier::class,
        ]);
    }



    /**
     * Modification du dossier d'un intervenant.
     *
     * @return ViewModel
     * @throws RuntimeException
     */
    public function indexAction()
    {
        $this->initFilters();

        /* Initialisation */
        $role        = $this->getServiceContext()->getSelectedIdentityRole();
        $intervenant = $role->getIntervenant() ?: $this->getEvent()->getParam('intervenant');
        $iPrec       = $this->getServiceDossier()->intervenantVacataireAnneesPrecedentes($intervenant, 1);
        /* @var $intervenant Intervenant */
        $validation  = $this->getServiceDossier()->getValidation($intervenant);
        $form        = $this->getFormIntervenantDossier();

        if (!($dossier = $intervenant->getDossier()) || !$dossier->estNonHistorise()) {
            $dossier = $this->getServiceDossier()->newEntity()->fromIntervenant($intervenant);
            $intervenant->setDossier($dossier);
        }

        $privEdit      = $this->isAllowed(Privileges::getResourceId(Privileges::DOSSIER_EDITION));
        $privValider   = $this->isAllowed(Privileges::getResourceId(Privileges::DOSSIER_VALIDATION));
        $privDevalider = $this->isAllowed(Privileges::getResourceId(Privileges::DOSSIER_DEVALIDATION));
        $privSupprimer = $this->isAllowed(Privileges::getResourceId(Privileges::DOSSIER_SUPPRESSION));

        $canValider   = !$validation && $dossier->getId() && $privValider;
        $canDevalider = $validation && $privDevalider;
        $canEdit      = !$validation && $privEdit;
        $canSupprimer = !$validation && $dossier->getId() && $privSupprimer;

        /* Mise en place du formulaires */
        $form->personnaliser($intervenant, $iPrec);
        $form->bind($intervenant);
        // le formulaire est en lecture seule si les données ont été validées ou si on n'a pas le droit de le modifier!!
        $form->setReadOnly(!$canEdit);


        /* Affichage de messages informatifs*/

        /* Si l'intervenant a effectué des heures avant */
        if ($iPrec) {
            $hetd = Util::formattedFloat(
                $this->getServiceService()->getTotalHetdIntervenant($iPrec),
                NumberFormatter::DECIMAL,
                2);
            $this->flashMessenger()->addInfoMessage(
                $role->getIntervenant() ?
                    sprintf("Vous avez effectué %s HETD en %s.", $hetd, $iPrec->getAnnee())
                    : sprintf("L'intervenant a effectué %s HETD en %s.", $hetd, $iPrec->getAnnee())
            );
        }

        /* Si les données personnelles ont été saisies et/ou validées */
        if ($dossier->getId() && $validation) {
            $v = $validation->getHistoCreateur() . " le " . $validation->getHistoCreation()->format(Constants::DATE_FORMAT);
            if ($role->getIntervenant()) {
                $this->flashMessenger()->addInfoMessage("Vos données personnelles ont été saisies et validées par $v.");
            } else {
                $this->flashMessenger()->addInfoMessage("Les données personnelles de $intervenant ont été saisies et validées par $v.");
            }
        } elseif ($dossier->getId()) {
            if ($role->getIntervenant()) {
                $this->flashMessenger()->addInfoMessage("Vos données personnelles ont été saisies.");
            } else {
                $this->flashMessenger()->addInfoMessage("Les données personnelles de $intervenant ont été saisies.");
            }
        }



        /* Action d'enregistrement du dossier */
        if ($this->params()->fromPost('enregistrer') && $canEdit && $this->getRequest()->isPost()) {
            if ($validation) {
                throw new \LogicException('Il est impossible de modifier des données personnelles si elles ont été validées.');
            }

            $data = $this->getRequest()->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $lastDossierId = $dossier->getId();
                try {
                    $this->getServiceDossier()->enregistrerDossier($dossier, $intervenant);
                    $this->flashMessenger()->addSuccessMessage("Données personnelles enregistrées avec succès.");
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage(DbException::translate($e));
                }

                // Lorsqu'un intervenant modifie son dossier, le rôle à sélectionner à la prochine requête doit correspondre
                // au statut choisi dans le dossier.
                if ($role->getIntervenant()) {
                    $this->getServiceUserContext()->setNextSelectedIdentityRole($dossier->getStatut()->getRoleId());
                }

                if (!$lastDossierId && $role->getIntervenant()) { // on ne redirige que pour l'intervenant et seulement si le dossier a été nouvellement créé
                    $nextEtape = $this->getServiceWorkflow()->getNextEtape(WfEtape::CODE_DONNEES_PERSO_SAISIE, $intervenant);
                    if ($nextEtape && $url = $nextEtape->getUrl()) {
                        return $this->redirect()->toUrl($url);
                    }
                }
                return $this->redirect()->toUrl($this->url()->fromRoute('intervenant/dossier', [], [], true));
            }
        }

        return compact('role', 'form', 'validation', 'canValider', 'canDevalider', 'canSupprimer');
    }



    public function validerAction()
    {
        $this->initFilters();

        $role        = $this->getServiceContext()->getSelectedIdentityRole();
        $intervenant = $role->getIntervenant() ?: $this->getEvent()->getParam('intervenant');
        $dossier     = $intervenant->getDossier();
        try {
            $this->getServiceValidation()->validerDossier($dossier);
            $this->flashMessenger()->addSuccessMessage("Validation <strong>enregistrée</strong> avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage(DbException::translate($e));
        }

        return new MessengerViewModel;
    }



    public function devaliderAction()
    {
        $this->initFilters();

        $role        = $this->getServiceContext()->getSelectedIdentityRole();
        $intervenant = $role->getIntervenant() ?: $this->getEvent()->getParam('intervenant');
        $validation  = $this->getServiceDossier()->getValidation($intervenant);
        try {
            $this->getServiceValidation()->delete($validation);
            $this->flashMessenger()->addSuccessMessage("Validation <strong>supprimée</strong> avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage(DbException::translate($e));
        }

        return new MessengerViewModel;
    }



    public function supprimerAction()
    {
        $this->initFilters();

        $role        = $this->getServiceContext()->getSelectedIdentityRole();
        $intervenant = $role->getIntervenant() ?: $this->getEvent()->getParam('intervenant');

        try {
            $this->getServiceDossier()->delete($intervenant->getDossier());
            $this->flashMessenger()->addSuccessMessage("Validation <strong>supprimée</strong> avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage(DbException::translate($e));
        }

        return new MessengerViewModel;
    }



    /**
     * @return UserContext
     */
    private function getServiceUserContext()
    {
        return $this->getServiceLocator()->get('authUserContext');
    }

}