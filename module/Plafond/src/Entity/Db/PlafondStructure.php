<?php

namespace Plafond\Entity\Db;

use Application\Entity\Db\Structure;
use Application\Entity\Db\Traits\StructureAwareTrait;
use Plafond\Interfaces\PlafondConfigInterface;
use Plafond\Traits\PlafondConfigTrait;

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
