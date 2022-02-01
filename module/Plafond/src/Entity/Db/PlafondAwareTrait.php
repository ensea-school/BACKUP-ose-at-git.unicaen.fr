<?php

namespace Plafond\Entity\Db;


/**
 * Description of PlafondAwareTrait
 *
 * @author UnicaenCode
 */
trait PlafondAwareTrait
{
    protected ?Plafond $plafond;



    /**
     * @param Plafond|null $plafond
     *
     * @return self
     */
    public function setPlafond( ?Plafond $plafond )
    {
        $this->plafond = $plafond;

        return $this;
    }



    public function getPlafond(): ?Plafond
    {
        return $this->plafond;
    }
}