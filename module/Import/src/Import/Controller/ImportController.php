<?php
namespace Import\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 *
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ImportController extends AbstractActionController
{

    public function indexAction()
    {
        $data = array(
            'PRENOM' => 'Laurent',
            'DATE_DE_NAISSANCE' => '1980-09-27',
            'NUMERO_INSEE_PROVISOIRE' => '1',
        );

        $entity = new \Import\Model\Entity\Intervenant\Intervenant();
        $hydrator = new \Import\Model\Hydrator\Oracle();
        $hydrator->makeStrategies($entity);
        $hydrator->hydrate($data, $entity);

        var_dump($entity);

        return new ViewModel(array('msg' => $msg));
    }

}