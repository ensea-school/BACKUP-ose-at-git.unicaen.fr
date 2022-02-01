<?php

namespace Plafond\Entity\Db;

/**
 * Description of PlafondAwareInterface
 *
 * @author UnicaenCode
 */
interface PlafondAwareInterface
{
    public function setPlafond(Plafond $plafond);



    public function getPlafond(): ?Plafond;
}