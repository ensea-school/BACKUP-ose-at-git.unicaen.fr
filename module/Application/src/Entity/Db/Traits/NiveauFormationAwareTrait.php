<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\NiveauFormation;

/**
 * Description of NiveauFormationAwareTrait
 *
 * @author UnicaenCode
 */
trait NiveauFormationAwareTrait
{
    protected ?NiveauFormation $entityDbNiveauFormation;



    /**
     * @param NiveauFormation|null $entityDbNiveauFormation
     *
     * @return self
     */
    public function setEntityDbNiveauFormation( ?NiveauFormation $entityDbNiveauFormation )
    {
        $this->entityDbNiveauFormation = $entityDbNiveauFormation;

        return $this;
    }



    public function getEntityDbNiveauFormation(): ?NiveauFormation
    {
        if (!$this->entityDbNiveauFormation){
            $this->entityDbNiveauFormation = \Application::$container->get('FormElementManager')->get(NiveauFormation::class);
        }

        return $this->entityDbNiveauFormation;
    }
}