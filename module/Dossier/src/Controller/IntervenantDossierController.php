<?php

namespace Dossier\Controller;

use Application\Constants;
use Application\Controller\AbstractController;
use Application\Entity\Db\WfEtape;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\ValidationServiceAwareTrait;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use DoctrineORMModule\Proxy\__CG__\Intervenant\Entity\Db\Statut;
use Dossier\Form\Traits\AutresFormAwareTrait;
use Dossier\Form\Traits\IntervenantDossierFormAwareTrait;
use Dossier\Service\Traits\DossierAutreServiceAwareTrait;
use Dossier\Service\Traits\DossierServiceAwareTrait;
use Enseignement\Service\ServiceServiceAwareTrait;
use Indicateur\Entity\Db\IndicModifDossier;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Service\IntervenantServiceAwareTrait;
use Intervenant\Service\StatutServiceAwareTrait;
use UnicaenApp\Util;
use UnicaenApp\View\Model\MessengerViewModel;
use UnicaenImport\Processus\Traits\ImportProcessusAwareTrait;

class IntervenantDossierController extends AbstractController
{
    use ContextServiceAwareTrait;
    use ServiceServiceAwareTrait;
    use DossierServiceAwareTrait;
    use WorkflowServiceAwareTrait;
    use ValidationServiceAwareTrait;
    use IntervenantDossierFormAwareTrait;
    use DossierServiceAwareTrait;
    use DossierAutreServiceAwareTrait;
    use AutresFormAwareTrait;
    use DossierAutreServiceAwareTrait;
    use IntervenantServiceAwareTrait;
    use AnneeServiceAwareTrait;
    use StatutServiceAwareTrait;
    use ImportProcessusAwareTrait;


    protected function initFilters()
    {
        $this->em()->getFilters()->enable('historique')->init([
            \Intervenant\Entity\Db\Intervenant::class,
            \Application\Entity\Db\Validation::class,
            \Dossier\Entity\Db\IntervenantDossier::class,
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
        /* Récupération de la validation du dossier si elle existe */
        $intervenantDossierValidation = $this->getServiceDossier()->getValidation($intervenant);
        $tblDossier                   = $intervenantDossier->getTblDossier();
        if (!$tblDossier and $intervenantDossier->getId()) {
            //$this->em()->refresh($intervenantDossier);
            $tblDossier = $intervenantDossier->getTblDossier();
        }
        $lastCompleted = (!empty($tblDossier)) ? $tblDossier->getCompletudeAvantRecrutement() : '';

        /* Initialisation du formulaire */
        $form = $this->getFormIntervenantIntervenantDossier()->setIntervenant($intervenant)->initForm();
        $form->bind($intervenantDossier);

        //si on vient de post et que le dossier n'est pas encore validé
        if ($this->getRequest()->isPost() && empty($intervenantDossierValidation)) {
            $data = $this->getRequest()->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                /* Traitement du formulaire */
                if (empty($intervenantDossier->getStatut()) && $intervenant->getStatut()->getCode() != 'AUTRES') {
                    $intervenantDossier->setStatut($intervenant->getStatut());
                }
                $intervenantDossier = $this->getServiceDossier()->save($intervenantDossier);

                /*On reinitialise le formulaire car le statut du dossier a
                pu être changé donc les règles d'affichage ne sont plus les mêmes */
                $form = $this->getFormIntervenantIntervenantDossier()->setIntervenant($intervenant)->initForm();
                $form->bind($intervenantDossier);
                //Alimentation de la table INDIC_MODIF_DOSSIER
                $this->getServiceDossier()->updateIndicModifDossier($intervenant, $intervenantDossier);
                //Recalcul des tableaux de bord nécessaires
                $this->updateTableauxBord($intervenantDossier->getIntervenant());
                $this->em()->refresh($intervenantDossier);
                $tblDossier    = $intervenantDossier->getTblDossier();
                $lastCompleted = $tblDossier->getCompletudeAvantRecrutement();

                $this->flashMessenger()->addSuccessMessage('Enregistrement de vos données effectué');
                //return $this->redirect()->toUrl($this->url()->fromRoute('intervenant/dossier', [], [], true));

                if (!$lastCompleted && $tblDossier->getCompletudeAvantRecrutement() && $role->getIntervenant()) { // on ne redirige que pour l'intervenant et seulement si le dossier a été nouvellement créé
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
            'fieldset-statut'                  => $intervenantDossier->getStatut()->getDossierStatut(),
            'fieldset-identite-complementaire' => $intervenantDossier->getStatut()->getDossierIdentiteComplementaire(),
            'fieldset-adresse'                 => $intervenantDossier->getStatut()->getDossierAdresse(),
            'fieldset-contact'                 => $intervenantDossier->getStatut()->getDossierContact(),
            'fieldset-iban'                    => $intervenantDossier->getStatut()->getDossierBanque(),
            'fieldset-insee'                   => $intervenantDossier->getStatut()->getDossierInsee(),
            'fieldset-employeur'               => $intervenantDossier->getStatut()->getDossierEmployeur(),
            'fieldset-autres'                  => (!empty($champsAutres)) ? 1 : 0,//Si le statut intervenant a au moins 1 champ autre
        ];

        $iPrec    = $this->getServiceDossier()->intervenantVacataireAnneesPrecedentes($intervenant, 1);
        $lastHETD = $iPrec ? $this->getServiceService()->getTotalHetdIntervenant($iPrec) : 0;

        if ($lastHETD > 0) {
            $hetd = Util::formattedFloat(
                $lastHETD,
                \NumberFormatter::DECIMAL,
                2
            );
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
            /**
             * @var Statut $statut
             */
            $statut = $this->getServiceStatut()->get($data['DossierStatut']['statut']);

            if ($statut) {
                //On vérifie que l'année du statut est bien la même année que celle de l'intervenant que l'on veut modifier
                if ($statut->getAnnee()->getId() != $intervenant->getAnnee()->getId()) {
                    $this->flashMessenger()->addErrorMessage("L'année du statut ( " . $statut->getAnnee()->getLibelle() . ") et celle de l'intervenant ( " . $intervenant->getAnnee()->getLibelle() . ") sont différentes. Impossible de mettre à jour le statut.");
                } else {
                    $intervenantDossier->setStatut($statut);
                    $this->getServiceDossier()->save($intervenantDossier);
                    $intervenant->setStatut($statut);
                    $intervenant->setSyncStatut(false);
                    $this->getServiceIntervenant()->save($intervenant);
                    $this->updateTableauxBord($intervenant);
                    // Lorsqu'un intervenant modifie son dossier, le rôle à sélectionner à la prochine requête doit correspondre
                    // au statut choisi dans le dossier.
                    if ($role->getIntervenant()) {
                        $this->getServiceContext()->refreshRoleStatut($statut);
                    }
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
            $this->updateTableauxBord($intervenant, true);
            $this->getProcessusImport()->execMaj('INTERVENANT', 'CODE', $intervenant->getCode());
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
                    $intervenant
                ));

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
