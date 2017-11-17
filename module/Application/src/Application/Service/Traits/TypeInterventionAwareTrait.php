<?php

namespace Application\Service\Traits;

use Application\Service\TypeIntervention;

/**
 * Description of TypeInterventionAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeInterventionAwareTrait
{
    /**
     * @var TypeIntervention
     */
    private $serviceTypeIntervention;



    /**
     * @param TypeIntervention $serviceTypeIntervention
     *
     * @return self
     */
    public function setServiceTypeIntervention(TypeIntervention $serviceTypeIntervention)
    {
        $this->serviceTypeIntervention = $serviceTypeIntervention;

        return $this;
    }



    /**
     * @return TypeIntervention
     */
    public function getServiceTypeIntervention()
    {
        if (empty($this->serviceTypeIntervention)) {
            $this->serviceTypeIntervention = \Application::$container->get('ApplicationTypeIntervention');
        }

        return $this->serviceTypeIntervention;
    }
}