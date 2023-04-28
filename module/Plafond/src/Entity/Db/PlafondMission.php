<?php

namespace Plafond\Entity\Db;

use Mission\Entity\Db\TypeMission;
use Mission\Entity\Db\TypeMissionAwareTrait;
use Referentiel\Entity\Db\FonctionReferentiel;
use Referentiel\Entity\Db\FonctionReferentielAwareTrait;
use Plafond\Interfaces\PlafondConfigInterface;
use Plafond\Traits\PlafondConfigTrait;

/**
 * PlafondMission
 */
class PlafondMission implements PlafondConfigInterface
{
    use PlafondConfigTrait;
    use TypeMissionAwareTrait;

    public function getEntity()
    {
        return $this->getTypeMission();
    }



    public function setEntity($entity): PlafondConfigInterface
    {
        if (!$entity instanceof TypeMission) {
            throw new \Exception('Un type de mission doit être fourni');
        }
        $this->setTypeMission($entity);

        return $this;
    }



    public static function getEntityClass(): ?string
    {
        return TypeMission::class;
    }



    public static function getPerimetreCode(): ?string
    {
        return PlafondPerimetre::MISSION;
    }

}
