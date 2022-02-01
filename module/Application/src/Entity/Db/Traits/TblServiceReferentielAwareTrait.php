<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TblServiceReferentiel;

/**
 * Description of TblServiceReferentielAwareTrait
 *
 * @author UnicaenCode
 */
trait TblServiceReferentielAwareTrait
{
    protected ?TblServiceReferentiel $tblServiceReferentiel = null;



    /**
     * @param TblServiceReferentiel $tblServiceReferentiel
     *
     * @return self
     */
    public function setTblServiceReferentiel( ?TblServiceReferentiel $tblServiceReferentiel )
    {
        $this->tblServiceReferentiel = $tblServiceReferentiel;

        return $this;
    }



    public function getTblServiceReferentiel(): ?TblServiceReferentiel
    {
        return $this->tblServiceReferentiel;
    }
}