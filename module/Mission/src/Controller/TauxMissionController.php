<?php

namespace Mission\Controller;

use Application\Controller\AbstractController;
use Mission\Entity\Db\MissionTauxRemu;
use Mission\Service\MissionTauxServiceAwareTrait;
/**
 * Description of TauxMissionController
 *
 * @author Florian Joriot <florian.joriot at unicaen.fr>
 */
class TauxMissionController extends AbstractController
{
    use MissionTauxServiceAwareTrait;


    public function indexAction()
    {

        $tauxMissions = $this->getServiceMissionTaux()->getTauxRemus();


        return ['tauxMissions'];
    }
}

