<?php

namespace Referentiel\Entity\Db;

use Application\Entity\Db\Traits\AnneeAwareTrait;
use Intervenant\Entity\Db\IntervenantAwareTrait;
use Lieu\Entity\Db\StructureAwareTrait;
use Service\Entity\Db\TypeVolumeHoraireAwareTrait;
use Workflow\Entity\Db\ValidationAwareTrait;

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

