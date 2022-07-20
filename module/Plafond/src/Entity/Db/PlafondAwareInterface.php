<?php

namespace Plafond\Entity\Db;


/**
 * Description of PlafondAwareInterface
 *
 * @author UnicaenCode
 */
interface PlafondAwareInterface
{
    /**
     * @param Plafond|null $plafond
     *
     * @return self
     */
    public function setPlafond( ?Plafond $plafond );



    public function getPlafond(): ?Plafond;
}