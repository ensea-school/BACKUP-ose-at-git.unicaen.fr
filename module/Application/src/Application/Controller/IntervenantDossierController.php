<?php

namespace Application\Controller;

use Application\Constants;
use Application\Entity\Db\IndicModifDossier;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\WfEtape;
use Application\Form\Intervenant\DossierValidation;
use Application\Form\Intervenant\IntervenantDossier;
use Application\Form\Intervenant\Traits\DossierAwareTrait;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\DossierServiceAwareTrait;
use Application\Service\Traits\IntervenantDossierServiceAwareTrait;
use Application\Service\Traits\ServiceServiceAwareTrait;
use Application\Service\Traits\ValidationServiceAwareTrait;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use RuntimeException;
use NumberFormatter;
use UnicaenApp\Util;
use UnicaenApp\View\Model\MessengerViewModel;
use UnicaenAuth\Service\Traits\UserContextServiceAwareTrait;
use Zend\View\Model\ViewModel;


class IntervenantDossierController extends AbstractController
{
    use ContextServiceAwareTrait;
    use ServiceServiceAwareTrait;
    use DossierServiceAwareTrait;
    use WorkflowServiceAwareTrait;
    use ValidationServiceAwareTrait;
    use DossierAwareTrait;
    use UserContextServiceAwareTrait;
    use IntervenantDossierServiceAwareTrait;



    /**
     * Initialisation des filtres Doctrine pour les historique.
     * Objectif : laisser passer les enregistrements passés en historique pour mettre en évidence ensuite les erreurs
     * éventuelles
     * (services sur des enseignements fermés, etc.)
     *
     * @see \Application\ORM\Filter\HistoriqueFilter
     */
    protected function initFilters()
    {
        $this->em()->getFilters()->enable('historique')->init([
            \Application\Entity\Db\Intervenant::class,
            \Application\Entity\Db\Validation::class,
            \Application\Entity\Db\Dossier::class,
        ]);
    }

    public function indexAction(){
        $this->initFilters();

        /* Initialisation */
        $role = $this->getServiceContext()->getSelectedIdentityRole();
        /* @var $intervenant Intervenant */
        $intervenant = $role->getIntervenant() ?: $this->getEvent()->getParam('intervenant');
        if (!$intervenant) {
            throw new \LogicException('Intervenant non précisé ou inexistant');
        }
        /* Récupération du dossier de l'intervenant */
        $intervenantDossier = $this->getServiceIntervenantDossier()->getByIntervenant($intervenant);
        /* Priviliege */
            $privEditIdentite = $this->isAllowed(Privileges::getResourceId(Privileges::DOSSIER_IDENTITE_SUITE_EDITION));
        /*$privEdit      = $this->isAllowed(Privileges::getResourceId(Privileges::DOSSIER_EDITION));
        $privValider   = $this->isAllowed(Privileges::getResourceId(Privileges::DOSSIER_VALIDATION));
        $privDevalider = $this->isAllowed(Privileges::getResourceId(Privileges::DOSSIER_DEVALIDATION));
        $privSupprimer = $this->isAllowed(Privileges::getResourceId(Privileges::DOSSIER_SUPPRESSION));*/

        /* Initialisation du formulaire */
        $form = $this->getFormIntervenantDossier();
        if(!$privEditIdentite)
        {
            $form->remove('DossierIdentite');
        }
        /* Traitement du formulaire */
        $form->bindRequestSave($intervenantDossier, $this->getRequest(), function (\Application\Entity\Db\IntervenantDossier $intervenantDossier) use ($intervenant) {
            try {
                /* Sauvegarde du dossier de l'intervenant */
                $this->getServiceIntervenantDossier()->save($intervenantDossier);
                /* Recalcul des tableaux de bord nécessaires */
                $this->updateTableauxBord($intervenant);
                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });

        return compact('form', 'role', 'intervenant');
    }



    public function validerAction()
    {
        $this->initFilters();

        $role        = $this->getServiceContext()->getSelectedIdentityRole();
        $intervenant = $role->getIntervenant() ?: $this->getEvent()->getParam('intervenant');
        $dossier     = $this->getServiceDossier()->getByIntervenant($intervenant);
        $validation  = $this->getServiceDossier()->getValidation($intervenant);
        if ($validation) {
            throw new \Exception('Ce dossier a déjà été validé par ' . $validation->getHistoCreateur() . ' le ' . $validation->getHistoCreation()->format(Constants::DATE_FORMAT));
        }
        try {
            $this->getServiceValidation()->validerDossier($dossier);
            $this->updateTableauxBord($intervenant, true);
            $this->flashMessenger()->addSuccessMessage("Validation <strong>enregistrée</strong> avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
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
            $this->updateTableauxBord($intervenant, true);
            $this->flashMessenger()->addSuccessMessage("Validation <strong>supprimée</strong> avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }

        return new MessengerViewModel;
    }



    public function supprimerAction()
    {
        $this->initFilters();

        $role        = $this->getServiceContext()->getSelectedIdentityRole();
        $intervenant = $role->getIntervenant() ?: $this->getEvent()->getParam('intervenant');
        $dossier     = $this->getServiceDossier()->getByIntervenant($intervenant);

        try {
            $this->getServiceDossier()->delete($dossier);
            $this->updateTableauxBord($intervenant);
            $this->flashMessenger()->addSuccessMessage("Validation <strong>supprimée</strong> avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }

        return new MessengerViewModel;
    }



    public function differencesAction()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */

        $dql = "
        SELECT
          vi
        FROM
          " . IndicModifDossier::class . " vi
        WHERE
          vi.histoDestruction IS NULL
          AND vi.intervenant = :intervenant
        ORDER BY
          vi.attrName, vi.histoCreation
        ";

        // refetch intervenant avec jointures
        $query = $this->em()->createQuery($dql);
        $query->setParameter('intervenant', $intervenant);

        $differences = $query->getResult();
        $title       = "Historique des modifications d'informations importantes dans les données personnelles";

        return compact('title', 'intervenant', 'differences');
    }



    public function purgerDifferencesAction()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */

        if ($this->getRequest()->isPost()) {
            try {
                $utilisateur = $this->getServiceContext()->getUtilisateur();
                $this->getServiceDossier()->purgerDonneesPersoModif($intervenant, $utilisateur);

                $this->flashMessenger()->addSuccessMessage(sprintf(
                    "L'historique des modifications d'informations importantes dans les données personnelles de %s a été effacé avec succès.",
                    $intervenant));

                $this->flashMessenger()->addSuccessMessage("Action effectuée avec succès.");
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }

            return new MessengerViewModel();
        } else {
            return compact('intervenant');
        }
    }



    private function updateTableauxBord(Intervenant $intervenant, $validation = false)
    {
        $this->getServiceWorkflow()->calculerTableauxBord([
            'dossier',
            'piece_jointe_demande',
        ], $intervenant);
    }

    private function personnaliser()
    {

    }
}