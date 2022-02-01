<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\VServiceNonValide;

/**
 * Description of VServiceNonValideAwareTrait
 *
 * @author UnicaenCode
 */
trait VServiceNonValideAwareTrait
{
    protected ?VServiceNonValide $entityDbVServiceNonValide;



    /**
     * @param VServiceNonValide|null $entityDbVServiceNonValide
     *
     * @return self
     */
    public function setEntityDbVServiceNonValide( ?VServiceNonValide $entityDbVServiceNonValide )
    {
        $this->entityDbVServiceNonValide = $entityDbVServiceNonValide;

        return $this;
    }



    public function getEntityDbVServiceNonValide(): ?VServiceNonValide
    {
        if (!$this->entityDbVServiceNonValide){
            $this->entityDbVServiceNonValide = \Application::$container->get(VServiceNonValide::class);
        }

        return $this->entityDbVServiceNonValide;
    }
}