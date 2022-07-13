<?php

namespace Referentiel\Entity\Db;

/**
 * Description of TblValidationReferentielAwareTrait
 *
 * @author UnicaenCode
 */
trait TblValidationReferentielAwareTrait
{
    protected ?TblValidationReferentiel $tblValidationReferentiel = null;



    /**
     * @param TblValidationReferentiel $tblValidationReferentiel
     *
     * @return self
     */
    public function setTblValidationReferentiel(?TblValidationReferentiel $tblValidationReferentiel)
    {
        $this->tblValidationReferentiel = $tblValidationReferentiel;

        return $this;
    }



    public function getTblValidationReferentiel(): ?TblValidationReferentiel
    {
        return $this->tblValidationReferentiel;
    }
}