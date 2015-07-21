<?php

namespace Application\Rule\Validation\Referentiel\Prevu;

use Application\Acl\IntervenantRole;
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
        $rule = $this->ruleDependingOnCurrentRole($serviceLocator);

        $tvh = $serviceLocator->get('applicationTypeVolumeHoraire')->getPrevu();
        $rule->setTypeVolumeHoraire($tvh);
        
        return $rule;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return IntervenantRoleRule|Rule
     */
    private function ruleDependingOnCurrentRole(ServiceLocatorInterface $serviceLocator)
    {
        $role = $serviceLocator->get('applicationContext')->getSelectedIdentityRole();

        switch(TRUE) {
            case $role instanceof IntervenantRole:
                $rule = new IntervenantRoleRule();
                break;
            default:
                $rule = new Rule();
        }

        return $rule;
    }
}
