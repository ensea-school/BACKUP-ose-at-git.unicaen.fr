<?php

namespace Plafond\Controller;

use Application\Entity\Db\Structure;
use Plafond\Entity\Db\PlafondStructure;
use Plafond\Form\PlafondStructureFormAwareTrait;
use Plafond\Service\PlafondStructureServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;


/**
 * Description of PlafondStructureController
 *
 * @author UnicaenCode
 */
class PlafondStructureController extends AbstractActionController
{
    use PlafondStructureServiceAwareTrait;
    use PlafondStructureFormAwareTrait;

    public function indexAction()
    {
        /* @var $structure Structure */
        $structure = $this->getEvent()->getParam('structure');

        $plafonds = $this->getServicePlafondStructure()->getList(
            $this->getServicePlafondStructure()->finderByStructure($structure)
        );

        return compact('plafonds', 'structure');
    }



    public function editerAction()
    {
        /* @var $structure Structure */
        $structure = $this->getEvent()->getParam('structure');

        /* @var $plafondStructure PlafondStructure */
        $plafondStructure = $this->getEvent()->getParam('plafondStructure');

        if ($plafondStructure) {
            $title = 'Modification du plafond';
        } else {
            $title            = 'Création d\'un nouveau plafond';
            $plafondStructure = $this->getServicePlafondStructure()->newEntity();
            $plafondStructure->setStructure($structure);
        }

        $form = $this->getFormPlafondStructure();
        $form->bindRequestSave($plafondStructure, $this->getRequest(), function (PlafondStructure $ps) {
            try {
                $this->getServicePlafondStructure()->save($ps);

                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
            } catch (Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });

        return compact('structure', 'plafondStructure', 'form', 'title');
    }
}