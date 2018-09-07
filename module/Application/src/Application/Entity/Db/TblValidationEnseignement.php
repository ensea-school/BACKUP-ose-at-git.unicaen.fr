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
     * @var boolen
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
     * @return boolen
     */
    public function getAutoValidation(): boolen
    {
        return $this->autoValidation;
    }



    /**
     * @param boolen $autoValidation
     *
     * @return TblValidationEnseignement
     */
    public function setAutoValidation(boolen $autoValidation): TblValidationEnseignement
    {
        $this->autoValidation = $autoValidation;

        return $this;
    }


}