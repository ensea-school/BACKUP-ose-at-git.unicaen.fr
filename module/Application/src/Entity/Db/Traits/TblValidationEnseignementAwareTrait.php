<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TblValidationEnseignement;

/**
 * Description of TblValidationEnseignementAwareTrait
 *
 * @author UnicaenCode
 */
trait TblValidationEnseignementAwareTrait
{
    /**
     * @var TblValidationEnseignement
     */
    private $tblValidationEnseignement;





    /**
     * @param TblValidationEnseignement $tblValidationEnseignement
     * @return self
     */
    public function setTblValidationEnseignement( TblValidationEnseignement $tblValidationEnseignement = null )
    {
        $this->tblValidationEnseignement = $tblValidationEnseignement;
        return $this;
    }



    /**
     * @return TblValidationEnseignement
     */
    public function getTblValidationEnseignement()
    {
        return $this->tblValidationEnseignement;
    }
}