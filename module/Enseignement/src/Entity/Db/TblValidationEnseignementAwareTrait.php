<?php

namespace Enseignement\Entity\Db;

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
    public function setTblValidationEnseignement(?TblValidationEnseignement $tblValidationEnseignement)
    {
        $this->tblValidationEnseignement = $tblValidationEnseignement;

        return $this;
    }



    public function getTblValidationEnseignement(): ?TblValidationEnseignement
    {
        return $this->tblValidationEnseignement;
    }
}