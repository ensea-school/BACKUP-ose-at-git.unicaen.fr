<?php

namespace Enseignement\Entity\Db;

/**
 * Description of TblServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TblServiceAwareTrait
{
    protected ?TblService $tblService = null;



    /**
     * @param TblService $tblService
     *
     * @return self
     */
    public function setTblService(?TblService $tblService)
    {
        $this->tblService = $tblService;

        return $this;
    }



    public function getTblService(): ?TblService
    {
        return $this->tblService;
    }
}