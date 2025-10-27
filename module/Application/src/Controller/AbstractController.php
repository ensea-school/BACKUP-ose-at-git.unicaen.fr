<?php

namespace Application\Controller;

use Application\Form\Supprimer;
use Application\Traits\TranslatorTrait;
use Doctrine\ORM\EntityManager;
use Laminas\Mvc\Controller\AbstractActionController;
use Unicaen\Framework\Controller\Plugin\IsAllowed;

/**
 * Description of AbstractController
 *
 * @method \Application\Controller\Plugin\Context context()
 * @method \Application\Controller\Plugin\Axios axios()
 * @method \Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger flashMessenger()
 * @method IsAllowed isAllowed($resource, $privilege = null)
 *
 */
abstract class AbstractController extends AbstractActionController
{
    use TranslatorTrait;

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
                $this->flashMessenger()->addErrorMessage($this->translate($e));

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
        return \Unicaen\Framework\Application\Application::getInstance()->container()->get(EntityManager::class);
    }

}