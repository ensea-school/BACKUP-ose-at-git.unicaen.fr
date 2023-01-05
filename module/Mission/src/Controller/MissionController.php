<?php

namespace Mission\Controller;

use Application\Controller\AbstractController;
use Application\Entity\Db\Intervenant;
use Mission\Entity\Db\Mission;
use Mission\Form\MissionFormAwareTrait;
use Mission\Service\MissionServiceAwareTrait;


/**
 * Description of MissionController
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class MissionController extends AbstractController
{
    use MissionServiceAwareTrait;
    use MissionFormAwareTrait;


    public function indexAction()
    {
        /* @var $intervenant Intervenant */

        $intervenant = $this->getEvent()->getParam('intervenant');

        $missionForm = $this->getFormMission();

        $dql = "SELECT m FROM " . Mission::class . " m WHERE m.histoDestruction IS NULL AND m.intervenant = :intervenant";

        /* @var $missions Mission[] */
        $missions = $this->em()->createQuery($dql)->setParameters([
            'intervenant' => $intervenant,
        ])->getResult();

        $result = [];
        foreach ($missions as $k => $mission) {
            $missions[$k] = $missionForm->getHydrator()->extract($mission);
        }

        return compact('intervenant', 'missions', 'missionForm');
    }

}