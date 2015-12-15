<?php

namespace Application\Controller;

use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\PilotageServiceAwareTrait;
use UnicaenApp\View\Model\CsvModel;
use Zend\Mvc\Controller\AbstractActionController;


/**
 * @method \Application\Controller\Plugin\Context     context()
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class PilotageController extends AbstractActionController
{
    use PilotageServiceAwareTrait;




    public function indexAction()
    {
        return [];
    }



    public function ecartsEtatsACtion()
    {

        $csvModel = new CsvModel();
        $csvModel->setHeader([
            'Année',
            'État',
            'Type d\'heures',
            'Structure',
            'Intervenant (code)',
            'Intervenant',
            'HETD payables'
        ]);

        $data = $this->getServicePilotage()->getEcartsEtats();
        foreach ($data as $d) {
            $csvModel->addLine($d);
        }
        $csvModel->setFilename('pilotage-ecarts-etats.csv');

        return $csvModel;
    }
}