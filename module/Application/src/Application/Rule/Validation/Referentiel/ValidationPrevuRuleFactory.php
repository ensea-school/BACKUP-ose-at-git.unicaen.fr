<?php

namespace Application\Rule\Validation\Referentiel;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 */
class ValidationPrevuRuleFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     *
     * @return ValidationEnsPrevuRule
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $tvh = $serviceLocator->get('applicationTypeVolumeHoraire')->getPrevu();
        
        $rule = new ValidationPrevuRule();
        $rule->setTypeVolumeHoraire($tvh);
        
        return $rule;
    }
}
