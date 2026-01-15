<?php

namespace Mission\Controller;

use Application\Controller\AbstractController;
use Application\Provider\Privileges;
use Application\Provider\Tbl\TblProvider;
use Application\Service\Traits\ContextServiceAwareTrait;
use Unicaen\Framework\Authorize\UnAuthorizedException;
use Intervenant\Entity\Db\Intervenant;
use Laminas\View\Model\ViewModel;
use Mission\Entity\Db\Candidature;
use Mission\Entity\Db\Mission;
use Mission\Entity\Db\OffreEmploi;
use Mission\Form\OffreEmploiFormAwareTrait;
use Mission\Service\CandidatureServiceAwareTrait;
use Mission\Service\MissionServiceAwareTrait;
use Mission\Service\OffreEmploiServiceAwareTrait;
use Plafond\Processus\PlafondProcessusAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use UnicaenVue\View\Model\AxiosModel;
use Workflow\Entity\Db\WorkflowEtape;
use Workflow\Service\ValidationServiceAwareTrait;
use Workflow\Service\WorkflowServiceAwareTrait;


/**
 * Description of OffreEmploiController
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class  OffreEmploiController extends AbstractController
{
    use OffreEmploiServiceAwareTrait;
    use CandidatureServiceAwareTrait;
    use OffreEmploiFormAwareTrait;
    use ValidationServiceAwareTrait;
    use ContextServiceAwareTrait;
    use MissionServiceAwareTrait;
    use WorkflowServiceAwareTrait;
    use PlafondProcessusAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;


    public function indexAction()
    {

        return [];
    }



    public function saisirAction()
    {

        $offreEmploi = $this->getEvent()->getParam('offreEmploi');
        $form        = $this->getFormOffreEmploi();
        if (empty($offreEmploi)) {
            $title       = "Création d'une nouvelle offre d'emploi";
            $offreEmploi = $this->getServiceOffreEmploi()->newEntity();
        } else {
            $title = "Modification d'une offre d'emploi";
        }

        // A adapter pour utiliser le futur type de volume horaire "BESOIN"
        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getPrevu();

        $hDeb = $offreEmploi->getNombreHeures() * $offreEmploi->getNombrePostes();
        $form->bindRequestSave($offreEmploi, $this->getRequest(), function () use ($offreEmploi, $form, $hDeb, $typeVolumeHoraire) {

            $this->getProcessusPlafond()->beginTransaction();
            try {
                $this->getServiceOffreEmploi()->save($offreEmploi);
                $hFin = $offreEmploi->getNombreHeures() * $offreEmploi->getNombrePostes();
                $this->getProcessusPlafond()->endTransaction($offreEmploi->getStructure(), $typeVolumeHoraire, $hFin < $hDeb);

            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
                $this->em()->rollback();
            }


            $this->flashMessenger()->addSuccessMessage(
                "Enregistrement effectué"
            );
        });

        return compact('form', 'title');
    }



    public function supprimerAction()
    {
        /** @var Mission $mission */
        $offre = $this->getEvent()->getParam('offreEmploi');

        $this->getServiceOffreEmploi()->delete($offre);
        $this->flashMessenger()->addSuccessMessage("Offre d'emploi supprimée avec succès.");

        return true;
    }



    /**
     * Retourne la liste des offres d'emploi
     *
     * @return AxiosModel
     */
    public function listeAction()
    {
        $params          = [];
        $annee           = $this->getServiceContext()->getAnnee();
        $params['annee'] = $annee;

        $canEdit = $this->isAllowed(Privileges::getResourceId(Privileges::MISSION_OFFRE_EMPLOI_MODIFIER));
        $canVisu = $this->isAllowed(Privileges::getResourceId(Privileges::MISSION_OFFRE_EMPLOI_VISUALISATION));


        if ($canVisu || $canEdit) {
            if ($structure = $this->getServiceContext()->getStructure()) {
                $params['structure'] = $structure->idsFilter();
            }

            return $this->getServiceOffreEmploi()->data($params);
        }

        return $this->getServiceOffreEmploi()->dataPublic($params);
    }



    public function validerAction()
    {
        /** @var OffreEmploi $offre */
        $offre = $this->getEvent()->getParam('offreEmploi');

        if ($offre->isValide()) {
            $this->flashMessenger()->addInfoMessage('L\'offre est déjà validé');
        } else {
            $this->getServiceValidation()->validerOffreEmploi($offre);
            $this->getServiceOffreEmploi()->save($offre);
            $this->flashMessenger()->addSuccessMessage("Offre d'emploi validée");
        }

        return $this->getAction($offre);
    }



    /**
     * Retourne les données pour une offre d'emploi
     *
     * @return AxiosModel
     */
    public function getAction(?OffreEmploi $offreEmploi = null)
    {
        if (!$offreEmploi) {
            /** @var OffreEmploi $offreEmploi */
            $offreEmploi = $this->getEvent()->getParam('offreEmploi');
        }

        $this->em()->clear();

        $annee = $this->getServiceContext()->getAnnee();
        $model = $this->getServiceOffreEmploi()->data(['offreEmploi' => $offreEmploi,
                                                       'annee'       => $annee]);
        $model->returnFirstItem();

        return $model;
    }



    public function devaliderAction()
    {
        /** @var OffreEmploi $offre */
        $offre      = $this->getEvent()->getParam('offreEmploi');
        $validation = $offre->getValidation();
        if ($validation) {
            $offre->setAutoValidation(false);
            $offre->setValidation(null);
            $this->getServiceOffreEmploi()->save($offre);
            $this->getServiceValidation()->delete($validation);
            $this->flashMessenger()->addSuccessMessage("Offre d'emploi dévalidée");
        } else {
            $this->flashMessenger()->addInfoMessage("L'offre d'emploi n'était pas validée");
        }

        return $this->getAction($offre);
    }



    public function accepterCandidatureAction()
    {
        /** @var Candidature $candidature */
        $candidature = $this->getEvent()->getParam('candidature');

        $title = "Accepter candidature";

        $request = $this->getRequest();
        if ($request->isPost()) {

            $dateCommission = $this->getRequest()->getPost('date-commission');

            if (!empty($dateCommission)) {

                $dateCommission = new \DateTime($dateCommission);
                $candidature->setDateCommission($dateCommission);
                $this->getServiceValidation()->validerCandidature($candidature);
                $this->getServiceCandidature()->save($candidature);
                //Envoyer mail de confirmation d'acceptation de candidature
                $this->getServiceCandidature()->envoyerMail($candidature, Candidature::MODELE_MAIL_ACCEPTATION, Candidature::OBJET_MAIL_ACCEPTATION);
                $this->flashMessenger()->addSuccessMessage("La candidature est bien acceptée");
                $mission = $this->getServiceMission()->createMissionFromCandidature($candidature);
                $this->getServiceWorkflow()->calculerTableauxBord([], $candidature->getIntervenant());
            } else {
                $this->flashMessenger()->addErrorMessage("Vous n'avez pas renseigné la date de commission de recurtement");
            }
            //On valide la candidature

        }

        $vm = new ViewModel();
        $vm->setTemplate('mission/offre-emploi/accepter-candidature');
        $vm->setVariables(compact('title', 'candidature'));

        return $vm;

    }



    public function refuserCandidatureAction()
    {
        /** @var Candidature $candidature */
        $candidature = $this->getEvent()->getParam('candidature');
        $utilisateur = $this->getServiceContext()->getUtilisateur();


        if ($candidature->getMotif()) {
            $this->flashMessenger()->addInfoMessage('La candidature est déjà refusée');
        } else {
            $motif = "Refusée par " . $utilisateur->getDisplayName();
            $candidature->setMotif($motif);

            if ($candidature->isValide()) {
                $validation = $candidature->getValidation();
                $candidature->setValidation(null);
                $candidature->setDateCommission(null);
                $this->getServiceValidation()->delete($validation);
            }

            $this->getServiceCandidature()->save($candidature);
            $this->getServiceCandidature()->envoyerMail($candidature, Candidature::MODELE_MAIL_REFUS, Candidature::OBJET_MAIL_REFUS);
            $this->getServiceWorkflow()->calculerTableauxBord([], $candidature->getIntervenant());
            $this->flashMessenger()->addSuccessMessage("La candidature est bien refusée");
        }


        return $this->getAction($candidature->getOffre());
    }



    public function postulerAction()
    {
        /**
         * @var OffreEmploi $offreEmploi
         */
        $offreEmploi = $this->getEvent()->getParam('offreEmploi');

        $intervenant = $this->getServiceContext()->getIntervenant();
        if (!$offreEmploi->isCandidat($intervenant)) {
            $this->getServiceCandidature()->postuler($intervenant, $offreEmploi);
            $this->updateTableauxBord($intervenant);
            $this->flashMessenger()->addSuccessMessage("Votre candidature a bien été prise en compte. Vous pouvez maintenant renseigner vos données personnelles afin d'appuyer votre candidature.");
        } else {
            $this->flashMessenger()->addErrorMessage("Vous avez déjà postulé à cette offre d'emploi");
        }

        return $this->redirect()->toRoute('intervenant/candidature', ['intervenant' => $intervenant->getId()]);
    }



    private function updateTableauxBord(Intervenant $intervenant)
    {
        $this->getServiceWorkflow()->calculerTableauxBord([
                                                              TblProvider::CANDIDATURE,
                                                              TblProvider::MISSION,
                                                          ], $intervenant);
    }



    public function detailAction(?OffreEmploi $offreEmploi = null)
    {

        if (!$offreEmploi) {
            /** @var OffreEmploi $offreEmploi */
            $offreEmploi = $this->getEvent()->getParam('offreEmploi');
        }

        $utilisateur        = $this->getServiceContext()->getUtilisateur();
        $intervenant        = $this->getServiceContext()->getIntervenant();
        $canPostuler        = $this->isAllowed($offreEmploi, Privileges::MISSION_OFFRE_EMPLOI_POSTULER);
        $canVoirCandidature = (empty($intervenant)) ? $this->isAllowed(Privileges::getResourceId(Privileges::MISSION_CANDIDATURE_VISUALISATION)) : false;


        return compact('offreEmploi', 'utilisateur', 'canPostuler', 'canVoirCandidature');
    }



    public function candidatureAction()
    {
        /**
         * @var Intervenant   $intervenant
         * @var WorkflowEtape $etapeDonneesPersos
         */
        $canAcces = $this->isAllowed(Privileges::getResourceId(Privileges::MISSION_CANDIDATURE_VISUALISATION));
        if (!$canAcces) {
            throw new UnAuthorizedException();
        }

        $intervenant                   = $this->getEvent()->getParam('intervenant');
        $renseignerDonneesPersonnelles = false;
        $canValiderCandidature         = $this->isAllowed($intervenant, Privileges::MISSION_CANDIDATURE_VALIDER);
        $canRefuserCandidature         = $this->isAllowed($intervenant, Privileges::MISSION_CANDIDATURE_REFUSER);

        $feuilleDeRoute = $this->getServiceWorkflow()->getFeuilleDeRoute($intervenant);

        $etapeDonneesPersos = $feuilleDeRoute->get(WorkflowEtape::DONNEES_PERSO_SAISIE);
        if (!empty($etapeDonneesPersos)) {
            $renseignerDonneesPersonnelles = !$etapeDonneesPersos->isFranchie();
        }

        if (!$intervenant) {
            throw new \LogicException('Intervenant introuvable');
        }


        return compact('intervenant', 'canValiderCandidature', 'canRefuserCandidature', 'renseignerDonneesPersonnelles');
    }



    public function getCandidaturesAction()
    {
        $canAcces = $this->isAllowed(Privileges::getResourceId(Privileges::MISSION_CANDIDATURE_VISUALISATION));
        if (!$canAcces) {
            throw new UnAuthorizedException();
        }

        $intervenant = $this->getEvent()->getParam('intervenant');
        if (!$intervenant) {
            throw new \LogicException('Intervenant introuvable');
        }
        /**
         * @var Intervenant $intervenant
         */
        $candidatures = $this->getServiceCandidature()->data(['intervenant' => $intervenant]);


        return $candidatures;
    }

}