<?php

namespace Dossier\Controller;

use Application\Constants;
use Application\Controller\AbstractController;
use Application\Provider\Tbl\TblProvider;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Dossier\Entity\Db\IntervenantDossier;
use Dossier\Entity\Db\TblDossier;
use Dossier\Service\Traits\TblDossierServiceAwareTrait;
use Intervenant\Entity\Db\Statut;
use Dossier\Form\Traits\AutresFormAwareTrait;
use Dossier\Form\Traits\IntervenantDossierFormAwareTrait;
use Dossier\Service\Traits\DossierAutreServiceAwareTrait;
use Dossier\Service\Traits\DossierServiceAwareTrait;
use Enseignement\Service\ServiceServiceAwareTrait;
use Indicateur\Entity\Db\IndicModifDossier;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Service\IntervenantServiceAwareTrait;
use Intervenant\Service\StatutServiceAwareTrait;
use Laminas\Http\Response;
use UnicaenApp\Util;
use UnicaenApp\View\Model\MessengerViewModel;
use UnicaenImport\Processus\Traits\ImportProcessusAwareTrait;
use Workflow\Entity\Db\Validation;
use Workflow\Entity\Db\WorkflowEtape;
use Workflow\Service\ValidationServiceAwareTrait;
use Workflow\Service\WorkflowServiceAwareTrait;


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
    use TblDossierServiceAwareTrait;


    protected function initFilters()
    {
        $this->em()->getFilters()->enable('historique')->init([
                                                                  Intervenant::class,
                                                                  Validation::class,
                                                                  IntervenantDossier::class,
                                                              ]);
    }



    public function indexAction(): array|Response
    {
        $this->initFilters();
        $intervenant = $this->getServiceContext()->getIntervenant() ?: $this->getEvent()->getParam('intervenant');
        if (!$intervenant) {
            throw new \LogicException('Intervenant non précisé ou inexistant');
        }
        /* Récupération du dossier de l'intervenant */
        /**
         * @var TblDossier $tblDossier
         */
        $tblDossier = $this->getServiceTblDossier()->finderByIntervenant($intervenant)->getQuery()->getOneOrNullResult();

        if (!$tblDossier || empty($tblDossier->getDossier())) {
            $dossier = $this->getServiceDossier()->initDossierIntervenant($intervenant);
            $this->em()->refresh($dossier);
            $tblDossier = $dossier->getTblDossier();
        }
        $intervenantDossier = $tblDossier->getDossier();

        $form = $this->getFormIntervenantIntervenantDossier()->setIntervenant($intervenant)->initForm();
        $form->bind($intervenantDossier);

        //si on vient de post et que le dossier n'est pas encore validé
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                /* Traitement du formulaire */
                if (empty($intervenantDossier->getStatut()) && $intervenant->getStatut()->getCode() != 'AUTRES') {
                    $intervenantDossier->setStatut($intervenant->getStatut());
                }
                /*On reinitialise le formulaire car le statut du dossier a
                pu être changé donc les règles d'affichage ne sont plus les mêmes */
                $form = $this->getFormIntervenantIntervenantDossier()->setIntervenant($intervenant)->initForm();
                $form->bind($intervenantDossier);
                //Alimentation de la table INDIC_MODIF_DOSSIER
                $this->getServiceDossier()->updateIndicModifDossier($intervenant, $intervenantDossier);
                //Recalcul des tableaux de bord nécessaires
                $this->updateTableauxBord($intervenantDossier->getIntervenant());
                $this->em()->refresh($intervenantDossier);
                $this->em()->refresh($tblDossier);
                $this->flashMessenger()->addSuccessMessage('Enregistrement de vos données effectué');

                if ($tblDossier->isCompletAvantRecrutement() && $this->getServiceContext()->getIntervenant()) { // on ne redirige que pour l'intervenant et seulement si le dossier a été nouvellement créé
                    $feuilleDeRoute = $this->getServiceWorkflow()->getFeuilleDeRoute($this->getServiceContext()->getIntervenant());
                    $nextEtape      = $feuilleDeRoute->getNext(WorkflowEtape::DONNEES_PERSO_SAISIE);
                    if ($nextEtape && $url = $nextEtape->url) {
                        return $this->redirect()->toUrl($url);
                    }
                }
            } else {
                $this->flashMessenger()->addErrorMessage("Vos données n'ont pas été enregistré, veuillez vérifier les erreurs.");
            }
        }

        $champsAutresAvantRecrutement = $intervenantDossier->getStatut()->getChampsAutres(Statut::DONNEES_PERSONNELLES_DEMANDEES);
        $champsAutresApresRecrutement = $intervenantDossier->getStatut()->getChampsAutres(Statut::DONNEES_PERSONNELLES_DEMANDEES_POST_RECRUTEMENT);

        $fieldsetRules = [
            'fieldset-statut'                   => $intervenantDossier->getStatut()->getDossierStatut(),
            'fieldset-identite-complementaire'  => $intervenantDossier->getStatut()->getDossierIdentiteComplementaire(),
            'fieldset-adresse'                  => $intervenantDossier->getStatut()->getDossierAdresse(),
            'fieldset-contact'                  => $intervenantDossier->getStatut()->getDossierContact(),
            'fieldset-iban'                     => $intervenantDossier->getStatut()->getDossierBanque(),
            'fieldset-insee'                    => $intervenantDossier->getStatut()->getDossierInsee(),
            'fieldset-employeur'                => $intervenantDossier->getStatut()->getDossierEmployeur(),
            'fieldset-autres-avant-recrutement' => (!empty($champsAutresAvantRecrutement)) ? 1 : 0,
            'fieldset-autres-apres-recrutement' => (!empty($champsAutresApresRecrutement)) ? 2 : 0,

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
                $this->getServiceContext()->getIntervenant() ?
                    sprintf("Vous avez effectué %s HETD en %s.", $hetd, $iPrec->getAnnee())
                    : sprintf("L'intervenant a effectué %s HETD en %s.", $hetd, $iPrec->getAnnee())
            );
        }


        return compact(
            'form',
            'tblDossier',
            'champsAutresAvantRecrutement',
            'champsAutresApresRecrutement',
            'fieldsetRules'
        );
    }



    public function changeStatutDossierAction(): Response
    {
        if ($this->getRequest()->isPost()) {
            $data        = $this->getRequest()->getPost();
            $intervenant = $this->getServiceContext()->getIntervenant() ?: $this->getEvent()->getParam('intervenant');
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
                }
            }
        }

        return $this->redirect()->toUrl($this->url()->fromRoute('intervenant/dossier', [], [], true));
    }



    public function validerAction(): MessengerViewModel
    {
        $this->initFilters();

        $typeValidation     = $this->getEvent()->getParam('typeValidation');
        $intervenant        = $this->getServiceContext()->getIntervenant() ?: $this->getEvent()->getParam('intervenant');
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



    public function validerComplementaireAction(): MessengerViewModel
    {
        $this->initFilters();

        $intervenant              = $this->getServiceContext()->getIntervenant() ?: $this->getEvent()->getParam('intervenant');
        $intervenantDossier       = $this->getServiceDossier()->getByIntervenant($intervenant);
        $validationComplementaire = $intervenantDossier->getTblDossier()->getValidationComplementaire();

        if ($validationComplementaire) {
            throw new \Exception('Vos données complémentaires a déjà été validées par ' . $validationComplementaire->getHistoCreateur() . ' le ' . $validationComplementaire->getHistoCreation()->format(Constants::DATE_FORMAT));
        }
        try {
            $this->getServiceValidation()->validerDossier($intervenantDossier, true);
            $this->updateTableauxBord($intervenant, true);

            $this->flashMessenger()->addSuccessMessage("Validation des données personnelles complémentaires <strong>enregistrée</strong> avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }

        return new MessengerViewModel;
    }



    public function devaliderAction(): MessengerViewModel
    {
        $this->initFilters();

        $intervenant        = $this->getServiceContext()->getIntervenant() ?: $this->getEvent()->getParam('intervenant');
        $intervenantDossier = $this->getServiceDossier()->getByIntervenant($intervenant);

        $validation = $intervenantDossier->getTblDossier()->getValidation();

        try {
            $this->getServiceValidation()->delete($validation);
            $this->updateTableauxBord($intervenant, true);
            $this->flashMessenger()->addSuccessMessage("Validation des données personnelles <strong>supprimée</strong> avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }

        return new MessengerViewModel;
    }



    public function devaliderComplementaireAction(): MessengerViewModel
    {
        $this->initFilters();

        $intervenant        = $this->getServiceContext()->getIntervenant() ?: $this->getEvent()->getParam('intervenant');
        $intervenantDossier = $this->getServiceDossier()->getByIntervenant($intervenant);

        $validationDonneesComplementaires = $intervenantDossier->getTblDossier()->getValidationComplementaire();

        try {
            $this->getServiceValidation()->delete($validationDonneesComplementaires);
            $this->updateTableauxBord($intervenant, true);
            $this->flashMessenger()->addSuccessMessage("Validation des données personnelles complémentaires <strong>supprimée</strong> avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }

        return new MessengerViewModel;
    }



    public function supprimerAction(): MessengerViewModel
    {
        $this->initFilters();

        $intervenant = $this->getServiceContext()->getIntervenant() ?: $this->getEvent()->getParam('intervenant');
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



    public function differencesAction(): array
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



    public function purgerDifferencesAction(): MessengerViewModel|array
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



    private function updateTableauxBord(Intervenant $intervenant, $validation = false): void
    {
        $this->getServiceWorkflow()->calculerTableauxBord([
                                                              TblProvider::DOSSIER,
                                                              TblProvider::PIECE_JOINTE,
                                                          ], $intervenant);
    }
}