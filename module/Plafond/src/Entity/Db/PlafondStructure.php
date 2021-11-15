<?php

namespace Plafond\Entity\Db;

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
}
