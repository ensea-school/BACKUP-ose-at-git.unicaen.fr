<?php

namespace Application\Controller;

use Application\Form\Plafond\Traits\PlafondApplicationFormAwareTrait;
use Application\Service\Traits\PlafondApplicationServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;


/**
 * Description of PlafondController
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class PlafondController extends AbstractActionController
{
    use PlafondApplicationServiceAwareTrait;
    use PlafondApplicationFormAwareTrait;



    public function indexAction()
    {
        $title    = 'Gestion des plafonds';
        $plafonds = $this->getServicePlafondApplication()->getList();

        return compact('title', 'plafonds');
    }



    public function saisirAction()
    {
        $title = 'Modification d\'un plafond';

        $form = $this->getFormPlafondPlafondApplication();

        return compact('title','form');
    }

}