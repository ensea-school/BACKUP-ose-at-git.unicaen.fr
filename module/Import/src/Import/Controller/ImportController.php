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
        $msg = 'TODO';
        return new ViewModel(array('msg' => $msg));
    }

}