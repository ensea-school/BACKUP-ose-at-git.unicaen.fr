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
use Mission\Entity\Db\VolumeHoraireMission;
use Mission\Form\MissionFormAwareTrait;
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

    public function indexAction()
    {


        return [];
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

}