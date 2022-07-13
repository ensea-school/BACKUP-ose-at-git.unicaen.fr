<?php

namespace Referentiel\Entity\Db;

use Application\Entity\Db\Traits\AnneeAwareTrait;
use Application\Entity\Db\Traits\IntervenantAwareTrait;
use Application\Entity\Db\Traits\StructureAwareTrait;
use Service\Entity\Db\TypeVolumeHoraireAwareTrait;
use Application\Entity\Db\Traits\ValidationAwareTrait;

/**
 * TblValidationReferentiel
 */
class TblValidationReferentiel
{
    use AnneeAwareTrait;
    use IntervenantAwareTrait;
    use StructureAwareTrait;
    use TypeVolumeHoraireAwareTrait;
    use ServiceReferentielAwareTrait;
    use VolumeHoraireReferentielAwareTrait;
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

