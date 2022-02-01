<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TblValidationReferentiel;

/**
 * Description of TblValidationReferentielAwareTrait
 *
 * @author UnicaenCode
 */
trait TblValidationReferentielAwareTrait
{
    protected ?TblValidationReferentiel $tblValidationReferentiel;



    /**
     * @param TblValidationReferentiel|null $tblValidationReferentiel
     *
     * @return self
     */
    public function setTblValidationReferentiel( ?TblValidationReferentiel $tblValidationReferentiel )
    {
        $this->tblValidationReferentiel = $tblValidationReferentiel;

        return $this;
    }



    public function getTblValidationReferentiel(): ?TblValidationReferentiel
    {
        return $this->tblValidationReferentiel;
    }
}