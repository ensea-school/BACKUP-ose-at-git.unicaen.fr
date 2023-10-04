<?php

namespace Enseignement\Entity\Db;

use Application\Entity\Db\Traits\AnneeAwareTrait;
use Application\Entity\Db\Traits\IntervenantAwareTrait;
use Application\Entity\Db\Traits\ValidationAwareTrait;
use Service\Entity\Db\TypeVolumeHoraireAwareTrait;
use Lieu\Entity\Db\StructureAwareTrait;

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