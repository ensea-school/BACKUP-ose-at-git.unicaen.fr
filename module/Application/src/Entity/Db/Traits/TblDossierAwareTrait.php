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
    /**
     * @var TblDossier
     */
    private $tblDossier;





    /**
     * @param TblDossier $tblDossier
     * @return self
     */
    public function setTblDossier( TblDossier $tblDossier = null )
    {
        $this->tblDossier = $tblDossier;
        return $this;
    }



    /**
     * @return TblDossier
     */
    public function getTblDossier()
    {
        return $this->tblDossier;
    }
}