<?php

namespace Plafond\Entity\Db;

use Plafond\Interfaces\PlafondConfigInterface;
use Plafond\Traits\PlafondConfigTrait;

/**
 * PlafondApplication
 */
class PlafondApplication implements PlafondConfigInterface
{
    use PlafondConfigTrait;

    public function getEntity()
    {
        return null;
    }



    public function setEntity($entity): PlafondConfigInterface
    {
        return $this;
    }



    public static function getEntityClass(): ?string
    {
        return null;
    }



    public static function getPerimetreCode(): ?string
    {
        return null;
    }

}
