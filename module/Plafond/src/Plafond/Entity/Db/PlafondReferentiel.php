<?php

namespace Plafond\Entity\Db;

use Application\Entity\Db\Traits\FonctionReferentielAwareTrait;
use Plafond\Interfaces\PlafondConfigInterface;
use Plafond\Traits\PlafondConfigTrait;

/**
 * PlafondReferentiel
 */
class PlafondReferentiel implements PlafondConfigInterface
{
    use PlafondConfigTrait;
    use FonctionReferentielAwareTrait;
}
