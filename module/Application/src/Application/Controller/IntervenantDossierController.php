<?php

namespace Application\Controller;

use Application\Assertion\IntervenantDossierAssertion;
use Application\Constants;
use Application\Entity\Db\DossierAutre;
use Application\Entity\Db\IndicModifDossier;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\TypeRessource;
use Application\Form\Intervenant\DossierValidation;
use Application\Form\Intervenant\Traits\AutresFormAwareTrait;
use Application\Form\Intervenant\Traits\IntervenantDossierFormAwareTrait;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\DossierAutreServiceAwareTrait;
use Application\Service\Traits\DossierServiceAwareTrait;
use Application\Service\Traits\ServiceServiceAwareTrait;
use Application\Service\Traits\ValidationServiceAwareTrait;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use UnicaenApp\Util;
use UnicaenApp\View\Model\MessengerViewModel;
use UnicaenAuth\Service\Traits\UserContextServiceAwareTrait;


class IntervenantDossierController extends AbstractController
{
    use ContextServiceAwareTrait;
    use ServiceServiceAwareTrait;
    use DossierServiceAwareTrait;
    use WorkflowServiceAwareTrait;
    use ValidationServiceAwareTrait;
    use IntervenantDossierFormAwareTrait;
    use UserContextServiceAwareTrait;
    use DossierServiceAwareTrait;
    use DossierAutreServiceAwareTrait;
    use AutresFormAwareTrait;
    use DossierAutreServiceAwareTrait;


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



    public function indexAction()
    {

        /**
         * TODO :
         * Remettre en place les bon required et les validator
         * Sortir la gestion des champs autres de ce controller
         *
         */
        $this->initFilters();

        /* Initialisation */
        $role = $this->getServiceContext()->getSelectedIdentityRole();
        /* @var $intervenant Intervenant */
        $intervenant = $role->getIntervenant() ?: $this->getEvent()->getParam('intervenant');
        if (!$intervenant) {
            throw new \LogicException('Intervenant non précisé ou inexistant');
        }
        /* Récupération du dossier de l'intervenant */
        $intervenantDossier           = $this->getServiceDossier()->getByIntervenant($intervenant);
        $intervenantDossierValidation = $this->getServiceDossier()->getValidation($intervenant);
        $intervenantDossierStatut     = $intervenantDossier->getStatut();
        $intervenantDossierCompletude = $this->getServiceDossier()->isComplete($intervenant);
        $champsAutres                 = $intervenantDossier->getStatut()->getChampsAutres();
        /* Règles pour afficher ou non les fieldsets */
        $fieldsetRules = [
            'fieldset-identite'  => $intervenantDossier->getStatut()->getDossierIdentite(),
            'fieldset-adresse'   => $intervenantDossier->getStatut()->getDossierAdresse(),
            'fieldset-contact'   => $intervenantDossier->getStatut()->getDossierContact(),
            'fieldset-iban'      => $intervenantDossier->getStatut()->getDossierIban(),
            'fieldset-insee'     => $intervenantDossier->getStatut()->getDossierInsee(),
            'fieldset-employeur' => $intervenantDossier->getStatut()->getDossierEmployeur(),
            'fieldset-autres'    => (!empty($champsAutres)) ? 1 : 0,//Si le statut intervenant a au moins 1 champs autre
        ];

        /* Initialisation du formulaire */
        $form = $this->getIntervenantDossierForm($intervenant);
        /* Traitement du formulaire */

        $form->bindRequestSave($intervenantDossier, $this->getRequest(), function (\Application\Entity\Db\IntervenantDossier $intervenantDossier) use ($intervenant) {
            try {
                /* Sauvegarde du dossier de l'intervenant */
                $this->getServiceDossier()->save($intervenantDossier);

                /* Recalcul des tableaux de bord nécessaires */
                $this->updateTableauxBord($intervenant);
                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });


        $iPrec    = $this->getServiceDossier()->intervenantVacataireAnneesPrecedentes($intervenant, 1);
        $lastHETD = $iPrec ? $this->getServiceService()->getTotalHetdIntervenant($iPrec) : 0;

        if ($lastHETD > 0) {
            $hetd = Util::formattedFloat(
                $lastHETD,
                NumberFormatter::DECIMAL,
                2);
            $this->flashMessenger()->addInfoMessage(
                $role->getIntervenant() ?
                    sprintf("Vous avez effectué %s HETD en %s.", $hetd, $iPrec->getAnnee())
                    : sprintf("L'intervenant a effectué %s HETD en %s.", $hetd, $iPrec->getAnnee())
            );
        }
        //Si on vient de poster le form alors on redirige pour rafraichir le form après le bindRequestSave
        if ($this->getRequest()->isPost()) {
            return $this->redirect()->toUrl($this->url()->fromRoute('intervenant/dossier', [], [], true));
        }


        return compact(
            ['form',
             'role',
             'intervenant',
             'intervenantDossier',
             'intervenantDossierValidation',
             'intervenantDossierStatut',
             'intervenantDossierCompletude',
             'champsAutres',
             'fieldsetRules']
        );
    }



    public function validerAction()
    {
        $this->initFilters();

        $role               = $this->getServiceContext()->getSelectedIdentityRole();
        $intervenant        = $role->getIntervenant() ?: $this->getEvent()->getParam('intervenant');
        $intervenantDossier = $this->getServiceDossier()->getByIntervenant($intervenant);
        $validation         = $this->getServiceDossier()->getValidation($intervenant);
        if ($validation) {
            throw new \Exception('Ce dossier a déjà été validé par ' . $validation->getHistoCreateur() . ' le ' . $validation->getHistoCreation()->format(Constants::DATE_FORMAT));
        }
        try {
            $this->getServiceValidation()->validerDossier($intervenantDossier);
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



    public function dossierAutreInfoAction()
    {
        $dossierAutreListe = $this->getServiceDossierAutre()->getList();

        return compact('dossierAutreListe');
    }



    public function dossierAutreSaisieAction()
    {
        $dossierAutre = $this->getEvent()->getParam('dossierAutre');
        $form         = $this->getAutresForm();
        $title        = 'Édition d\'un type de ressource';

        $form->bindRequestSave($dossierAutre, $this->getRequest(), function (DossierAutre $autre) {
            try {
                $this->getServiceDossierAutre()->save($autre);
                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });

        return compact('form', 'title');

        return [];
    }



    public function dossierAutreDeleteAction()
    {
        $dossierAutreList = $this->getServiceDossierAutre()->getList();

        return [];
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