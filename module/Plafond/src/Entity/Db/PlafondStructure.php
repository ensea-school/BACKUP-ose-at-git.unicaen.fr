<?php

namespace Plafond\Entity\Db;

use Lieu\Entity\Db\Structure;
use Plafond\Interfaces\PlafondConfigInterface;
use Plafond\Traits\PlafondConfigTrait;
use Lieu\Entity\Db\StructureAwareTrait;

/**
 * PlafondStructure
 */
class PlafondStructure implements PlafondConfigInterface
{
    use PlafondConfigTrait;
    use StructureAwareTrait;

    public function getEntity()
    {
        return $this->getStructure();
    }



    public function setEntity($entity): PlafondConfigInterface
    {
        if (!$entity instanceof Structure) {
            throw new \Exception('Une structure doit être fournie');
        }
        $this->setStructure($entity);

        return $this;
    }



    public static function getEntityClass(): ?string
    {
        return Structure::class;
    }



    public static function getPerimetreCode(): ?string
    {
        return PlafondPerimetre::STRUCTURE;
    }

}
