<?php

namespace Application\Controller;

use Application\Constants;
use Application\Entity\Db\IndicModifDossier;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\WfEtape;
use Application\Form\Intervenant\DossierValidation;
use Application\Form\Intervenant\Traits\AutresFormAwareTrait;
use Application\Form\Intervenant\Traits\IntervenantDossierFormAwareTrait;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\DossierAutreServiceAwareTrait;
use Application\Service\Traits\DossierServiceAwareTrait;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use Application\Service\Traits\ServiceServiceAwareTrait;
use Application\Service\Traits\StatutIntervenantServiceAwareTrait;
use Application\Service\Traits\ValidationServiceAwareTrait;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use UnicaenApp\Util;
use UnicaenApp\View\Model\MessengerViewModel;
use UnicaenAuth\Service\Traits\UserContextServiceAwareTrait;
use UnicaenImport\Processus\Traits\ImportProcessusAwareTrait;


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
    use IntervenantServiceAwareTrait;
    use AnneeServiceAwareTrait;
    use StatutIntervenantServiceAwareTrait;
    use ImportProcessusAwareTrait;


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
        $intervenantDossier = $this->getServiceDossier()->getByIntervenant($intervenant);

        /*Si dossier n'a pas encore d'id alors on le save et on calcule la completude*/
        if (!$intervenantDossier->getId()) {
            $this->getServiceDossier()->save($intervenantDossier);
            $this->updateTableauxBord($intervenantDossier->getIntervenant());
        }
        $intervenantDossierValidation = $this->getServiceDossier()->getValidation($intervenant);
        $tblDossier                   = $intervenantDossier->getTblDossier();

        $lastCompleted = $tblDossier->getCompletude();
        /* Initialisation du formulaire */
        $form = $this->getIntervenantDossierForm($intervenant);
        $form->bind($intervenantDossier);

        //si on vient de post
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                /* Traitement du formulaire */
                $intervenantDossier = $this->getServiceDossier()->save($intervenantDossier);
                //Alimentation de la table INDIC_MODIF_DOSSIER
                $this->getServiceDossier()->updateIndicModifDossier($intervenant, $intervenantDossier);
                //Recalcul des tableaux de bord nécessaires
                $this->updateTableauxBord($intervenantDossier->getIntervenant());
                $this->em()->refresh($tblDossier);

                /*On reinitialise le formulaire car le statut du dossier a
                pu être changé donc les règles d'affichage ne sont plus les mêmes*/
                $form = $this->getIntervenantDossierForm($intervenant);
                $form->bind($intervenantDossier);
                $this->flashMessenger()->addSuccessMessage('Enregistrement de vos données effectué');
                //return $this->redirect()->toUrl($this->url()->fromRoute('intervenant/dossier', [], [], true));

                if (!$lastCompleted && $tblDossier->getCompletude() && $role->getIntervenant()) { // on ne redirige que pour l'intervenant et seulement si le dossier a été nouvellement créé
                    $nextEtape = $this->getServiceWorkflow()->getNextEtape(WfEtape::CODE_DONNEES_PERSO_SAISIE, $intervenant);
                    if ($nextEtape && $url = $nextEtape->getUrl()) {
                        return $this->redirect()->toUrl($url);
                    }
                }
            } else {
                $this->flashMessenger()->addErrorMessage("Vos données n'ont pas été enregistré, veuillez vérifier les erreurs.");
            }
        }

        $intervenantDossierStatut = $intervenantDossier->getStatut();
        //Règles pour afficher ou non les fieldsets
        $champsAutres  = $intervenantDossier->getStatut()->getChampsAutres();
        $fieldsetRules = [
            'fieldset-identite-complementaire' => $intervenantDossier->getStatut()->getDossierIdentiteComplementaire(),
            'fieldset-adresse'                 => $intervenantDossier->getStatut()->getDossierAdresse(),
            'fieldset-contact'                 => $intervenantDossier->getStatut()->getDossierContact(),
            'fieldset-iban'                    => $intervenantDossier->getStatut()->getDossierIban(),
            'fieldset-insee'                   => $intervenantDossier->getStatut()->getDossierInsee(),
            'fieldset-employeur'               => $intervenantDossier->getStatut()->getDossierEmployeur(),
            'fieldset-autres'                  => (!empty($champsAutres)) ? 1 : 0,//Si le statut intervenant a au moins 1 champs autre
        ];

        $iPrec    = $this->getServiceDossier()->intervenantVacataireAnneesPrecedentes($intervenant, 1);
        $lastHETD = $iPrec ? $this->getServiceService()->getTotalHetdIntervenant($iPrec) : 0;

        if ($lastHETD > 0) {
            $hetd = Util::formattedFloat(
                $lastHETD,
                \NumberFormatter::DECIMAL,
                2);
            $this->flashMessenger()->addInfoMessage(
                $role->getIntervenant() ?
                    sprintf("Vous avez effectué %s HETD en %s.", $hetd, $iPrec->getAnnee())
                    : sprintf("L'intervenant a effectué %s HETD en %s.", $hetd, $iPrec->getAnnee())
            );
        }


        return compact(
            'form',
            'role',
            'intervenant',
            'intervenantDossier',
            'intervenantDossierValidation',
            'intervenantDossierStatut',
            'tblDossier',
            'champsAutres',
            'fieldsetRules'
        );
    }



    public function changeStatutDossierAction()
    {
        if ($this->getRequest()->isPost()) {
            $data        = $this->getRequest()->getPost();
            $role        = $this->getServiceContext()->getSelectedIdentityRole();
            $intervenant = $role->getIntervenant() ?: $this->getEvent()->getParam('intervenant');
            if (!$intervenant) {
                throw new \LogicException('Intervenant non précisé ou inexistant');
            }

            $intervenantDossier = $this->getServiceDossier()->getByIntervenant($intervenant);
            $statutIntervenant  = $this->getServiceStatutIntervenant()->get($data['DossierStatut']['statut']);
            if ($statutIntervenant) {
                $intervenantDossier->setStatut($statutIntervenant);
                $this->getServiceDossier()->save($intervenantDossier);
                $intervenant->setStatut($statutIntervenant);
                $intervenant->setSyncStatut(false);
                $this->getServiceIntervenant()->save($intervenant);
                $this->updateTableauxBord($intervenant);

                // Lorsqu'un intervenant modifie son dossier, le rôle à sélectionner à la prochine requête doit correspondre
                // au statut choisi dans le dossier.
                if ($role->getIntervenant()) {
                    $this->serviceUserContext->clearIdentityRoles();
                    \Application::$container->get(\Application\Provider\Identity\IdentityProvider::class)->clearIdentityRoles();
                    \Application::$container->get(\Application\Provider\Role\RoleProvider::class)->clearRoles();
                    $this->serviceUserContext->setSelectedIdentityRole($statutIntervenant->getRoleId());
                }
            }
        }

        return $this->redirect()->toUrl($this->url()->fromRoute('intervenant/dossier', [], [], true));
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
            if ($intervenant->getSourceCode() && $intervenant->getSource()->getImportable()) {
                $this->getProcessusImport()->execMaj('INTERVENANT', 'SOURCE_CODE', $intervenant->getSourceCode());
            }
            $this->updateTableauxBord($intervenant, true);
            $this->flashMessenger()->addSuccessMessage("Validation des données personnelles <strong>enregistrée</strong> avec succès.");
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
            $this->flashMessenger()->addSuccessMessage("Validation des données personnelles <strong>supprimée</strong> avec succès.");
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