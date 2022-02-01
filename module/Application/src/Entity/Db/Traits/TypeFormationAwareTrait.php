<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TypeFormation;

/**
 * Description of TypeFormationAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeFormationAwareTrait
{
    protected ?TypeFormation $entityDbTypeFormation;



    /**
     * @param TypeFormation|null $entityDbTypeFormation
     *
     * @return self
     */
    public function setEntityDbTypeFormation( ?TypeFormation $entityDbTypeFormation )
    {
        $this->entityDbTypeFormation = $entityDbTypeFormation;

        return $this;
    }



    public function getEntityDbTypeFormation(): ?TypeFormation
    {
        if (!$this->entityDbTypeFormation){
            $this->entityDbTypeFormation = \Application::$container->get('FormElementManager')->get(TypeFormation::class);
        }

        return $this->entityDbTypeFormation;
    }
}