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

        $canAddMission = true;

        return compact('intervenant', 'canAddMission');
    }



    public function listeAction()
    {
        /* @var $intervenant Intervenant */
        /* @var $missions Mission[] */


        $intervenant = $this->getEvent()->getParam('intervenant');
        $missionForm = $this->getFormMission();

        $dql = "SELECT m FROM " . Mission::class . " m WHERE m.histoDestruction IS NULL AND m.intervenant = :intervenant";

        $missions = $this->em()->createQuery($dql)->setParameters([
            'intervenant' => $intervenant,
        ])->getResult();

        foreach ($missions as $k => $mission) {
            $missions[$k] = $missionForm->getHydrator()->extract($mission);
        }

        return new JsonModel($missions);
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
        $missionForm->bind($mission);
        $missionForm->setData($data);
        $result = [];

        if ($missionForm->isValid()) {
            try {
                $this->getServiceMission()->save($mission);
            } catch (\Exception $e) {
                $result = [
                    'error' => $e->getMessage(),
                ];
            }
        } else {
            $result['form-errors'] = [];
            foreach ($missionForm->getElements() as $element) {
                if ($messages = $element->getMessages()) {
                    $result['form-errors'][$element->getName()] = [];
                    foreach ($messages as $message) {
                        $result['form-errors'][$element->getName()][] = $message;
                    }
                }
            }
            if (empty($result['form-errors'])) {
                unset($result['form-errors']);
            }
        }

        $result['data'] = $missionForm->getHydrator()->extract($mission);

        return new JsonModel($result);
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