<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Agrement;

/**
 * Description of AgrementAwareTrait
 *
 * @author UnicaenCode
 */
trait AgrementAwareTrait
{
    protected ?Agrement $agrement = null;



    /**
     * @param Agrement $agrement
     *
     * @return self
     */
    public function setAgrement( Agrement $agrement )
    {
        $this->agrement = $agrement;

        return $this;
    }



    public function getAgrement(): ?Agrement
    {
        return $this->agrement;
    }
}