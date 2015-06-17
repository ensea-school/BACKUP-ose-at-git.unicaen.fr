<?php

namespace Application\Rule\Validation\Enseignement;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 */
class ValidationRealiseRuleFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     *
     * @return ValidationEnsPrevuRule
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $tvh = $serviceLocator->get('applicationTypeVolumeHoraire')->getRealise();
        
        $rule = new ValidationRealiseRule();
        $rule->setTypeVolumeHoraire($tvh);
        
        return $rule;
    }
}
