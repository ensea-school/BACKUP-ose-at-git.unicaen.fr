<?php

namespace Plafond\Entity\Db;

use Intervenant\Entity\Db\Statut;
use Intervenant\Entity\Db\StatutAwareTrait;
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
