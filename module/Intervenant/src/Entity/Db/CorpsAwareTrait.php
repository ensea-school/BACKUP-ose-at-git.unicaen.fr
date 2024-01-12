<?php

namespace Intervenant\Entity\Db;

/**
 * Description of CorpsAwareTrait
 *
 * @author UnicaenCode
 */
trait CorpsAwareTrait
{
    protected ?Corps $corps = null;



    /**
     * @param Corps $corps
     *
     * @return self
     */
    public function setCorps( ?Corps $corps )
    {
        $this->corps = $corps;

        return $this;
    }



    public function getCorps(): ?Corps
    {
        return $this->corps;
    }
}