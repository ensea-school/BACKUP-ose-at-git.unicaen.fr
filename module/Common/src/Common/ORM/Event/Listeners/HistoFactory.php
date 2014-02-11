<?php
namespace Common\ORM\Event\Listeners;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of HistoFactory
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class HistoFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
//        $authenticationService = $serviceLocator->get('Zend\Authentication\AuthenticationService');
//        return new Histo($authenticationService->getIdentity());
        /**
         * On est obligé de passer le service locator et non le service d'auth car cela provoque une boucle d'appels infinie!! :-(
         */
        return new Histo($serviceLocator);
    }
}