<?php
namespace Import\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Import\Model\Service\Intervenant;

/**
 *
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class IntervenantController extends AbstractActionController
{

    public function searchAction()
    {
        if (($term = $this->params()->fromQuery('term'))) {
            /* @var $intervenant Intervenant */
            $intervenant = $this->getServiceLocator()->get('importServiceIntervenant');
            $result = $intervenant->search( $term );
            return new \Zend\View\Model\JsonModel($result);
        }
        exit;
    }

}