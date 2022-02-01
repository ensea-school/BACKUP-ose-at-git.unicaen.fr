<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\VServiceValide;

/**
 * Description of VServiceValideAwareTrait
 *
 * @author UnicaenCode
 */
trait VServiceValideAwareTrait
{
    protected ?VServiceValide $entityDbVServiceValide;



    /**
     * @param VServiceValide|null $entityDbVServiceValide
     *
     * @return self
     */
    public function setEntityDbVServiceValide( ?VServiceValide $entityDbVServiceValide )
    {
        $this->entityDbVServiceValide = $entityDbVServiceValide;

        return $this;
    }



    public function getEntityDbVServiceValide(): ?VServiceValide
    {
        if (!$this->entityDbVServiceValide){
            $this->entityDbVServiceValide = \Application::$container->get(VServiceValide::class);
        }

        return $this->entityDbVServiceValide;
    }
}