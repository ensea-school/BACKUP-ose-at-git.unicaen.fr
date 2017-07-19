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
    /**
     * @var TblValidationReferentiel
     */
    private $tblValidationReferentiel;





    /**
     * @param TblValidationReferentiel $tblValidationReferentiel
     * @return self
     */
    public function setTblValidationReferentiel( TblValidationReferentiel $tblValidationReferentiel = null )
    {
        $this->tblValidationReferentiel = $tblValidationReferentiel;
        return $this;
    }



    /**
     * @return TblValidationReferentiel
     */
    public function getTblValidationReferentiel()
    {
        return $this->tblValidationReferentiel;
    }
}