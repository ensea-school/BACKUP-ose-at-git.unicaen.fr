<?php

namespace Mission\Controller;

use Application\Constants;
use Application\Controller\AbstractController;
use Application\Entity\Db\Intervenant;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\TypeValidationServiceAwareTrait;
use Application\Service\Traits\ValidationServiceAwareTrait;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Mission\Entity\Db\Mission;
use Mission\Entity\Db\OffreEmploi;
use Mission\Entity\Db\VolumeHoraireMission;
use Mission\Form\MissionFormAwareTrait;
use Mission\Form\OffreEmploiFormAwareTrait;
use Mission\Service\MissionServiceAwareTrait;
use Mission\Service\OffreEmploiServiceAwareTrait;
use Service\Entity\Db\TypeVolumeHoraire;


/**
 * Description of OffreEmploiController
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class OffreEmploiController extends AbstractController
{
    use OffreEmploiServiceAwareTrait;
    use OffreEmploiFormAwareTrait;


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



    /**
     * Retourne la liste des offres d'emploi
     *
     * @return JsonModel
     */
    public function listeAction()
    {
        $query = $this->getServiceOffreEmploi()->query([]);

        return $this->axios()->send($query);
    }



    /**
     * Retourne les données pour une mission
     *
     * @return JsonModel
     */
    public function getAction(?OffreEmploi $offreEmploi = null)
    {
        if (!$offreEmploi) {
            /** @var OffreEmploi $offreEmploi */
            $offreEmploi = $this->getEvent()->getParam('offreEmploi');
        }

        $this->em()->clear();

        $query = $this->getServiceOffreEmploi()->query(['offreEmploi' => $offreEmploi]);

        return $this->axios()->send($this->axios()::extract($query)[0]);
    }

}