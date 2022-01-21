<?php

namespace Plafond\Entity\Db;

use Application\Entity\Db\StatutIntervenant;
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

    public function getEntity()
    {
        return $this->getStatutIntervenant();
    }



    public function setEntity($entity): PlafondConfigInterface
    {
        if (!$entity instanceof StatutIntervenant) {
            throw new \Exception('Un statut doit être fourni');
        }
        $this->setStatutIntervenant($entity);

        return $this;
    }



    public static function getEntityClass(): ?string
    {
        return StatutIntervenant::class;
    }



    public static function getPerimetreCode(): ?string
    {
        return PlafondPerimetre::INTERVENANT;
    }

}
