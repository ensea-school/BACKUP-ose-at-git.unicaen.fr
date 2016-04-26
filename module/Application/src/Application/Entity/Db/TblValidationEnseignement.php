<?php

namespace Application\Entity\Db;
use Application\Entity\Db\Traits\AnneeAwareTrait;
use Application\Entity\Db\Traits\IntervenantAwareTrait;
use Application\Entity\Db\Traits\ServiceAwareTrait;
use Application\Entity\Db\Traits\StructureAwareTrait;
use Application\Entity\Db\Traits\TypeVolumeHoraireAwareTrait;
use Application\Entity\Db\Traits\ValidationAwareTrait;
use Application\Entity\Db\Traits\VolumeHoraireAwareTrait;

/**
 * TblValidationEnseignement
 */
class TblValidationEnseignement
{
    use AnneeAwareTrait;
    use IntervenantAwareTrait;
    use StructureAwareTrait;
    use TypeVolumeHoraireAwareTrait;
    use ServiceAwareTrait;
    use VolumeHoraireAwareTrait;
    use ValidationAwareTrait;

    protected $id;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}