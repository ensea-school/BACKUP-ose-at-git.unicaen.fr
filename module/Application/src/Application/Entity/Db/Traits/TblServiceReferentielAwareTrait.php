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
    /**
     * @var TblServiceReferentiel
     */
    private $tblServiceReferentiel;





    /**
     * @param TblServiceReferentiel $tblServiceReferentiel
     * @return self
     */
    public function setTblServiceReferentiel( TblServiceReferentiel $tblServiceReferentiel = null )
    {
        $this->tblServiceReferentiel = $tblServiceReferentiel;
        return $this;
    }



    /**
     * @return TblServiceReferentiel
     */
    public function getTblServiceReferentiel()
    {
        return $this->tblServiceReferentiel;
    }
}