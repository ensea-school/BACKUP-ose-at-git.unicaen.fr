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
     * @var bool
     */
    protected $autoValidation = false;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * @return bool
     */
    public function getAutoValidation(): bool
    {
        return $this->autoValidation;
    }



    /**
     * @param bool $autoValidation
     *
     * @return TblValidationEnseignement
     */
    public function setAutoValidation(bool $autoValidation): TblValidationEnseignement
    {
        $this->autoValidation = $autoValidation;

        return $this;
    }


}