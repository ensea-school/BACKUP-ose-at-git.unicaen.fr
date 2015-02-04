<?php

namespace Application\Controller;

use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

/**
 *
 */
class PaiementController extends AbstractActionController implements ContextProviderAwareInterface
{
    use ContextProviderAwareTrait;

    /**
     *
     * @return type
     */
    public function indexAction()
    {
        return [];
    }

    public function miseEnPaiementAction()
    {
        $intervenant = $this->context()->mandatory()->intervenantFromRoute();

        return [];
    }
}