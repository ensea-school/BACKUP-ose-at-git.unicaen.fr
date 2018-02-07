<?php

namespace Application\Controller;

use Application\Exception\DbException;
use Application\Form\Supprimer;
use Doctrine\ORM\EntityManager;
use UnicaenApp\Exporter\Pdf;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Description of AbstractController
 *
 * @method \Application\Controller\Plugin\Context context()
 *
 */
abstract class AbstractController extends AbstractActionController
{

    /**
     * @param $saveFnc
     *
     * @return Supprimer
     */
    protected function makeFormSupprimer($saveFnc)
    {
        $form = new \Application\Form\Supprimer('supprimer');
        $form->init();

        if ($this->getRequest()->isPost()) {
            try {
                $saveFnc();
            } catch (\Exception $e) {
                $e = DbException::translate($e);
                $this->flashMessenger()->addErrorMessage($e->getMessage());
                return null;
            }
        }

        return $form;
    }



    /**
     * @return EntityManager
     */
    protected function em()
    {
        return \Application::$container->get(\Application\Constants::BDD);
    }



    /**
     * @return Pdf
     */
    protected function pdf()
    {
        return new Pdf(\Application::$container->get('view_manager')->getRenderer());
    }
}