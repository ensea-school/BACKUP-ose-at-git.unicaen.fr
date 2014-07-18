<?php

namespace Application\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;

/**
 * Description of Workflow
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ContextProvider extends AbstractHelper implements ContextProviderAwareInterface
{
    use ContextProviderAwareTrait;

    /**
     * Retourne le fournisseur de contexte.
     * 
     * @return \Application\Service\ContextProvider
     */
    public function __invoke()
    {
        return $this->getContextProvider();
    }
}