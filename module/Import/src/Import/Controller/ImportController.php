<?php
namespace Import\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class ImportController extends AbstractActionController
{

    public function indexAction()
    {
        $msg = 'TODO';
        return new ViewModel(array('msg' => $msg));
    }

}