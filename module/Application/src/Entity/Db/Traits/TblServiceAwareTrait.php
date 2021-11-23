<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TblService;

/**
 * Description of TblServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TblServiceAwareTrait
{
    /**
     * @var TblService
     */
    private $tblService;





    /**
     * @param TblService $tblService
     * @return self
     */
    public function setTblService( TblService $tblService = null )
    {
        $this->tblService = $tblService;
        return $this;
    }



    /**
     * @return TblService
     */
    public function getTblService()
    {
        return $this->tblService;
    }
}