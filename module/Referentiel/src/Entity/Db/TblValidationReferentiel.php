<?php

namespace Referentiel\Entity\Db;

use Application\Entity\Db\Traits\AnneeAwareTrait;
use Application\Entity\Db\Traits\IntervenantAwareTrait;
use Application\Entity\Db\Traits\StructureAwareTrait;
use Service\Entity\Db\TypeVolumeHoraireAwareTrait;
use Application\Entity\Db\Traits\ValidationAwareTrait;

class TblValidationReferentiel
{
    use AnneeAwareTrait;
    use IntervenantAwareTrait;
    use StructureAwareTrait;
    use TypeVolumeHoraireAwareTrait;
    use ServiceReferentielAwareTrait;
    use VolumeHoraireReferentielAwareTrait;
    use ValidationAwareTrait;

    protected int  $id;

    protected bool $autoValidation = false;



    public function getId(): int
    {
        return $this->id;
    }



    public function getAutoValidation(): bool
    {
        return $this->autoValidation;
    }
}

