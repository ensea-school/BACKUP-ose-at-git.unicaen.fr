<?php

namespace Application\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\View\HelperPluginManager;
use Application\Service\Context;

/**
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
trait ContextAwareTrait
{
    /**
     * @var Context
     */
    protected $context;
    
    /**
     * Retourne le service fournissant le context global de l'application.
     *
     * @return Context
     */
    public function getContext()
    {
        if (null === $this->context && $this instanceof ServiceLocatorAwareInterface) {
            $sl = $this->getServiceLocator();
            if ($sl instanceof HelperPluginManager) {
                $sl = $sl->getServiceLocator();
            }
            $this->context = $sl->get('ApplicationContext');
        }
        return $this->context;
    }
}