<?php

namespace Plafond\Entity\Db;

/**
 * Description of PlafondAwareInterface
 *
 * @author UnicaenCode
 */
interface PlafondAwareInterface
{
    public function setPlafond(Plafond $plafond = null);



    public function getPlafond(): ?Plafond;
}