<?php

namespace Mission\Controller;

use Application\Controller\AbstractController;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\ValidationServiceAwareTrait;
use Doctrine\ORM\Query;
use Mission\Entity\Db\Mission;
use Mission\Entity\Db\OffreEmploi;
use Mission\Form\OffreEmploiFormAwareTrait;
use Mission\Service\CandidatureServiceAwareTrait;
use Mission\Service\OffreEmploiServiceAwareTrait;
use UnicaenVue\Axios\AxiosExtractor;
use UnicaenVue\View\Model\AxiosModel;


/**
 * Description of OffreEmploiController
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class OffreEmploiController extends AbstractController
{
    use OffreEmploiServiceAwareTrait;
    use CandidatureServiceAwareTrait;
    use OffreEmploiFormAwareTrait;
    use ValidationServiceAwareTrait;
    use ContextServiceAwareTrait;


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
        $form->bindRequestSave($offreEmploi, $this->getRequest(), function () use ($offreEmploi, $form) {

            $this->getServiceOffreEmploi()->save($offreEmploi);

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

        return $this->axios()->send([]);
    }



    /**
     * Retourne la liste des offres d'emploi
     *
     * @return AxiosModel
     */
    public function listeAction()
    {

        /**
         * @var Query $query
         */
        $query = $this->getServiceOffreEmploi()->query([]);

        $properties = ['typeMission',
                       'dateDebut',
                       'dateFin',
                       'structure',
                       'titre',
                       'description',
                       'nombreHeures',
                       'nombrePostes',
                       'histoCreation',
                       'histoCreateur',
                       'validation',
                       'candidats',
                       'candidaturesValides',
                       ['candidatures', ['intervenant', 'validation']],
        ];


        return new AxiosModel($query, $properties, $this->getServiceOffreEmploi()->getOffreEmploiPrivileges());
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



    public function postulerAction()
    {
        /**
         * @var OffreEmploi $offreEmploi
         */
        $offreEmploi = $this->getEvent()->getParam('offreEmploi');

        $intervenant = $this->getServiceContext()->getIntervenant();
        if (!$offreEmploi->isCandidat($intervenant)) {
            $this->getServiceCandidature()->postuler($intervenant, $offreEmploi);
            $this->flashMessenger()->addSuccessMessage("Votre candidature a bien été prise en compte. Vous pouvez maintenant renseigner vos données personnelles afin d'appuyer votre candidature.");
        } else {
            $this->flashMessenger()->addErrorMessage("Vous avez déjà postulé à cette offre d'emploi");
        }


        return $this->redirect()->toRoute('offre-emploi/public');
    }



    public function publicAction()
    {
        /**
         * @var OffreEmploi $offreEmploi
         */
        $offreEmploi = $this->getEvent()->getParam('offreEmploi');
        $utilisateur = $this->getServiceContext()->getUtilisateur();
        $intervenant = ($this->getServiceContext()->getIntervenant()) ?: false;

        return compact('offreEmploi', 'utilisateur', 'intervenant');
    }



    /**
     * Retourne les données pour une mission
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

        $properties = ['typeMission',
                       'dateDebut',
                       'dateFin',
                       'structure',
                       'titre',
                       'description',
                       'nombreHeures',
                       'nombrePostes',
                       'histoCreation',
                       'histoCreateur',
                       'validation',
                       'candidats',
                       'candidaturesValides',
                       ['candidatures', ['intervenant', 'validation']],
        ];

        $query = $this->getServiceOffreEmploi()->query(['offreEmploi' => $offreEmploi]);

        return new AxiosModel(AxiosExtractor::extract($query, $properties, $this->getServiceOffreEmploi()->getOffreEmploiPrivileges())[0]);
    }

}