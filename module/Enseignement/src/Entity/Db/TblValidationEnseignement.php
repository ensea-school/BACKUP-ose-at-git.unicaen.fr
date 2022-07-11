<?php

namespace Enseignement\Entity\Db;

use Application\Entity\Db\Traits\AnneeAwareTrait;
use Application\Entity\Db\Traits\IntervenantAwareTrait;
use Application\Entity\Db\Traits\ServiceAwareTrait;
use Application\Entity\Db\Traits\StructureAwareTrait;
use Application\Entity\Db\Traits\TypeVolumeHoraireAwareTrait;
use Application\Entity\Db\Traits\ValidationAwareTrait;
use Application\Entity\Db\Traits\VolumeHoraireAwareTrait;

class TblValidationEnseignement
{
    use AnneeAwareTrait;
    use IntervenantAwareTrait;
    use StructureAwareTrait;
    use TypeVolumeHoraireAwareTrait;
    use ServiceAwareTrait;
    use VolumeHoraireAwareTrait;
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