<?php

namespace Plafond\Entity\Db;

use Application\Entity\Db\FonctionReferentiel;
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

    public function getEntity()
    {
        return $this->getFonctionReferentiel();
    }



    public function setEntity($entity): PlafondConfigInterface
    {
        if (!$entity instanceof FonctionReferentiel) {
            throw new \Exception('Une fonction référentielle doit être fournie');
        }
        $this->setFonctionReferentiel($entity);

        return $this;
    }



    public static function getEntityClass(): ?string
    {
        return FonctionReferentiel::class;
    }



    public static function getPerimetreCode(): ?string
    {
        return PlafondPerimetre::REFERENTIEL;
    }

}
