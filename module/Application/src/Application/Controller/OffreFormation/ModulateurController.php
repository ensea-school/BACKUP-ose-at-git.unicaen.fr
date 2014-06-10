<?php

namespace Application\Controller\OffreFormation;

use Zend\Mvc\Controller\AbstractActionController;
use Application\Service\ElementPedagogique as ElementPedagogiqueService;
use Application\Service\Etape as EtapeService;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;

/**
 * Description of ModulateurController
 *
 * @method \Doctrine\ORM\EntityManager            em()
 * @method \Application\Controller\Plugin\Context context()
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ModulateurController extends AbstractActionController implements ContextProviderAwareInterface
{
    use ContextProviderAwareTrait;

    protected function saisirAction()
    {

    }

    /**
     * Retourne le service ElementPedagogique.
     *
     * @return ElementPedagogiqueService
     */
    protected function getServiceElementPedagogique()
    {
        return $this->getServiceLocator()->get('applicationElementPedagogique');
    }

    /**
     * Retourne le service Etape
     *
     * @return EtapeService
     */
    protected function getServiceEtape()
    {
        return $this->getServiceLocator()->get('applicationEtape');
    }
}