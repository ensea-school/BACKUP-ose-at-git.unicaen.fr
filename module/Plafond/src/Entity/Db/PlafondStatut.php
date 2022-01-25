<?php

namespace Plafond\Entity\Db;

use Application\Entity\Db\Statut;
use Application\Entity\Db\Traits\StatutAwareTrait;
use Plafond\Interfaces\PlafondConfigInterface;
use Plafond\Traits\PlafondConfigTrait;

/**
 * PlafondStatut
 */
class PlafondStatut implements PlafondConfigInterface
{
    use PlafondConfigTrait;
    use StatutAwareTrait;

    public function getEntity()
    {
        return $this->getStatutIntervenant();
    }



    public function setEntity($entity): PlafondConfigInterface
    {
        if (!$entity instanceof Statut) {
            throw new \Exception('Un statut doit être fourni');
        }
        $this->setStatutIntervenant($entity);

        return $this;
    }



    public static function getEntityClass(): ?string
    {
        return Statut::class;
    }



    public static function getPerimetreCode(): ?string
    {
        return PlafondPerimetre::INTERVENANT;
    }

}
