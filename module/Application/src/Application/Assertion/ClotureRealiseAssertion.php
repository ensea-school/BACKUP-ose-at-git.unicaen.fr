<?php

namespace Application\Assertion;

use Application\Rule\Validation\ClotureRealiseRule;
use Application\Entity\Db\TypeVolumeHoraire as TypeVolumeHoraireEntity;

/**
 * Assertions concernant la clôture du réalisé.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ClotureRealiseAssertion extends ValidationEnsRefAbstractAssertion
{
    use \Application\Service\Traits\TypeVolumeHoraireAwareTrait;
    
    /**
     * @return boolean
     */
    protected function assertEntityOld()
    {return true; // @todo à revoir...
        if (! $this->assertCRUD()) {
            return false;
        }
        
        $rule = $this->getServiceLocator()->get('ClotureRealiseRule') /* @var $rule ClotureRealiseRule */
                ->setIntervenant($this->resource->getIntervenant())
                ->setTypeVolumeHoraire($this->getTypeVolumeHoraire())
                ->setRole($this->role)
                ->execute();
//        var_dump("isAllowed({$this->privilege}) = " . (int)$rule->isAllowed($this->privilege));

        return $rule->isAllowed($this->privilege);
    }
    
    /**
     * @return TypeVolumeHoraireEntity
     */
    protected function getTypeVolumeHoraire()
    {
        return $this->getServiceTypeVolumeHoraire()->getRealise();
    }
}