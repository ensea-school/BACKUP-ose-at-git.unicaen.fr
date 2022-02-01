<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TblDossier;

/**
 * Description of TblDossierAwareTrait
 *
 * @author UnicaenCode
 */
trait TblDossierAwareTrait
{
    protected ?TblDossier $tblDossier = null;



    /**
     * @param TblDossier $tblDossier
     *
     * @return self
     */
    public function setTblDossier( TblDossier $tblDossier )
    {
        $this->tblDossier = $tblDossier;

        return $this;
    }



    public function getTblDossier(): ?TblDossier
    {
        return $this->tblDossier;
    }
}