<?php

namespace Agrement\Entity\Db\Traits;

use Agrement\Entity\Db\TypeAgrement;

/**
 * Description of TypeAgrementAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeAgrementAwareTrait
{
    protected ?TypeAgrement $typeAgrement = null;



    /**
     * @param TypeAgrement $typeAgrement
     *
     * @return self
     */
    public function setTypeAgrement(?TypeAgrement $typeAgrement)
    {
        $this->typeAgrement = $typeAgrement;

        return $this;
    }



    public function getTypeAgrement(): ?TypeAgrement
    {
        return $this->typeAgrement;
    }
}