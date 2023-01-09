<?php

namespace Mission\Controller;

use Application\Controller\AbstractController;
use Application\Entity\Db\Intervenant;
use Laminas\View\Model\JsonModel;
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

        foreach ($missions as $k => $mission) {
            $missions[$k] = $missionForm->getHydrator()->extract($mission);
        }

        return compact('intervenant', 'missions', 'missionForm');
    }



    public function modifierAction()
    {
        $data                = $this->params()->fromPost();
        $id                  = (int)$data['id'];
        $data['description'] = str_replace(' ', '', $data['description']);
        if ($id <= 0) {
            $mission = $this->getServiceMission()->newEntity();
        } else {
            $mission = $this->getServiceMission()->get($id);
        }

        $missionForm = $this->getFormMission();
        $missionForm->getHydrator()->hydrate($data, $mission);

        return new JsonModel(['error' => 'c\'est la merde']);
        //*
        try {
            $this->getServiceMission()->save($mission);
            $result = [
                'data' => $missionForm->getHydrator()->extract($mission),
            ];

            return new JsonModel($result);
        } catch (\Exception $e) {
            return new JsonModel(['error' => $e->getMessage()]);
        }
        /**/
//        try {
//            $this->getServiceMission()->save($mission);
//            $result['msg'] = 'Enregistrement effectué';
//        } catch (\Exception $e) {
        //$result['error'] = 'il y a une erreur';

//        }


    }



    public function supprimerAction()
    {
        /** @todo */
    }



    public function validerAction()
    {

    }



    public function devaliderAction()
    {

    }
}