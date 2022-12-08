<?php

namespace Mission\Controller;

use Application\Controller\AbstractController;
use Application\Entity\Db\Intervenant;


/**
 * Description of MissionController
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class MissionController extends AbstractController
{

    public function indexAction()
    {
        /* @var $intervenant Intervenant */

        $intervenant = $this->getEvent()->getParam('intervenant');

        return compact('intervenant');
    }

}