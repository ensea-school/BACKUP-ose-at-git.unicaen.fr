<?php

namespace Plafond\Entity\Db;

use Application\Entity\Db\Traits\StatutIntervenantAwareTrait;
use Plafond\Interfaces\PlafondConfigInterface;
use Plafond\Traits\PlafondConfigTrait;

/**
 * PlafondStatut
 */
class PlafondStatut implements PlafondConfigInterface
{
    use PlafondConfigTrait;
    use StatutIntervenantAwareTrait;
}
