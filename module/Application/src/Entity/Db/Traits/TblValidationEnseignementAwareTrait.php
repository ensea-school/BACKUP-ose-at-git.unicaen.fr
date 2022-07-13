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
    protected ?TblValidationEnseignement $tblValidationEnseignement = null;



    /**
     * @param TblValidationEnseignement $tblValidationEnseignement
     *
     * @return self
     */
    public function setTblValidationEnseignement( ?TblValidationEnseignement $tblValidationEnseignement )
    {
        $this->tblValidationEnseignement = $tblValidationEnseignement;

        return $this;
    }



    public function getTblValidationEnseignement(): ?TblValidationEnseignement
    {
        return $this->tblValidationEnseignement;
    }
}