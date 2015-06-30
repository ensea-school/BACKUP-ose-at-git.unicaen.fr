<?php

namespace Application\Rule\Validation\Enseignement\Prevu;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 */
class RuleFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return Rule
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $tvh = $serviceLocator->get('applicationTypeVolumeHoraire')->getPrevu();

        $rule = new Rule();
        $rule->setTypeVolumeHoraire($tvh);
        
        return $rule;
    }


}
