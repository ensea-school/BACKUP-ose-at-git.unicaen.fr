<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;

/**
 * Description of IntervenantController
 *
 * @method \Doctrine\ORM\EntityManager                em()
 * @method \Application\Controller\Plugin\Context     context()
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class GestionController extends AbstractActionController
{
    use \Application\Service\Traits\ContextAwareTrait;

    /**
     *
     * @return type
     */
    public function droitsAction()
    {
        $annee = $this->getServiceContext()->getAnnee();

        return compact('annee');
    }
}