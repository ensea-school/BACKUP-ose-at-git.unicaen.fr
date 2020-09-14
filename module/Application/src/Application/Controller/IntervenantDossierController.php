<?php

namespace Application\Controller;

use Application\Constants;
use Application\Entity\Db\IndicModifDossier;
use Application\Entity\Db\Intervenant;
use Application\Form\Intervenant\DossierValidation;
use Application\Form\Intervenant\Traits\AutresFormAwareTrait;
use Application\Form\Intervenant\Traits\IntervenantDossierFormAwareTrait;
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
        $this->initFilters();

        /* Initialisation */
        $role        = $this->getServiceContext()->getSelectedIdentityRole();
        $intervenant = $role->getIntervenant() ?: $this->getEvent()->getParam('intervenant');
        if (!$intervenant) {
            throw new \LogicException('Intervenant non précisé ou inexistant');
        }
        /* Récupération du dossier de l'intervenant */
        $intervenantDossier           = $this->getServiceDossier()->getByIntervenant($intervenant);
        $intervenantDossierValidation = $this->getServiceDossier()->getValidation($intervenant);
        /* Initialisation du formulaire */
        $form = $this->getIntervenantDossierForm($intervenant);
        $form->bind($intervenantDossier);

        //si on vient de post
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                /* Traitement du formulaire */
                $completude = $this->getServiceDossier()->isComplete($intervenantDossier);
                $intervenantDossier->setCompletude($completude);
                $intervenantDossier = $this->getServiceDossier()->save($intervenantDossier);
                //Alimentation de la table INDIC_MODIF_DOSSIER
                $this->getServiceDossier()->updateIndicModifDossier($intervenant, $intervenantDossier);
                //Recalcul des tableaux de bord nécessaires
                $this->updateTableauxBord($intervenantDossier->getIntervenant());
                /*On reinitialise le formulaire car le statut du dossier a
                pu être changé donc les règles d'affichage ne sont plus les mêmes*/
                $form = $this->getIntervenantDossierForm($intervenant);
                $form->bind($intervenantDossier);
                $this->flashMessenger()->addSuccessMessage('Enregistrement de vos données effectué');
            } else {
                $this->flashMessenger()->addErrorMessage("Vos données n'ont pas été enregistré, veuillez vérifier les erreurs.");
            }
        }

        $intervenantDossierStatut = $intervenantDossier->getStatut();
        //Règles pour afficher ou non les fieldsets
        $champsAutres                 = $intervenantDossier->getStatut()->getChampsAutres();
        $fieldsetRules                = [
            'fieldset-identite-complementaire' => $intervenantDossier->getStatut()->getDossierIdentiteComplementaire(),
            'fieldset-adresse'                 => $intervenantDossier->getStatut()->getDossierAdresse(),
            'fieldset-contact'                 => $intervenantDossier->getStatut()->getDossierContact(),
            'fieldset-iban'                    => $intervenantDossier->getStatut()->getDossierIban(),
            'fieldset-insee'                   => $intervenantDossier->getStatut()->getDossierInsee(),
            'fieldset-employeur'               => $intervenantDossier->getStatut()->getDossierEmployeur(),
            'fieldset-autres'                  => (!empty($champsAutres)) ? 1 : 0,//Si le statut intervenant a au moins 1 champs autre
        ];
        $intervenantDossierCompletude = $this->getServiceDossier()->getCompletude($intervenantDossier);

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
            $this->flashMessenger()->addSuccessMessage("Validation des données personnelles <strong>enregistrée</strong> avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }

        return new MessengerViewModel;
    }



    public
    function devaliderAction()
    {
        $this->initFilters();

        $role        = $this->getServiceContext()->getSelectedIdentityRole();
        $intervenant = $role->getIntervenant() ?: $this->getEvent()->getParam('intervenant');
        $validation  = $this->getServiceDossier()->getValidation($intervenant);
        try {
            $this->getServiceValidation()->delete($validation);
            $this->updateTableauxBord($intervenant, true);
            $this->flashMessenger()->addSuccessMessage("Validation des données personnelles <strong>supprimée</strong> avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }

        return new MessengerViewModel;
    }



    public
    function supprimerAction()
    {
        $this->initFilters();

        $role        = $this->getServiceContext()->getSelectedIdentityRole();
        $intervenant = $role->getIntervenant() ?: $this->getEvent()->getParam('intervenant');
        $dossier     = $this->getServiceDossier()->getByIntervenant($intervenant);

        try {
            $this->getServiceDossier()->delete($dossier);
            $this->updateTableauxBord($intervenant);
            $this->flashMessenger()->addSuccessMessage("Suppression des données personnelles <strong>effectuée</strong> avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }

        return new MessengerViewModel;
    }



    public function differencesAction()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');

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
}