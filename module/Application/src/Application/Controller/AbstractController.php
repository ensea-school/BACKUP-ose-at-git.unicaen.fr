<?php

namespace Application\Controller;

use Application\Exception\DbException;
use Application\Form\Supprimer;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Description of AbstractController
 *
 * @method \Doctrine\ORM\EntityManager            em()
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
        $form->setServiceLocator($this->getServiceLocator()->get('formElementManager'));
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

}