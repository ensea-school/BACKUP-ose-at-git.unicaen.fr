<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TblDmepLiquidation;

/**
 * Description of TblDmepLiquidationAwareTrait
 *
 * @author UnicaenCode
 */
trait TblDmepLiquidationAwareTrait
{
    /**
     * @var TblDmepLiquidation
     */
    private $tblDmepLiquidation;





    /**
     * @param TblDmepLiquidation $tblDmepLiquidation
     * @return self
     */
    public function setTblDmepLiquidation( TblDmepLiquidation $tblDmepLiquidation = null )
    {
        $this->tblDmepLiquidation = $tblDmepLiquidation;
        return $this;
    }



    /**
     * @return TblDmepLiquidation
     */
    public function getTblDmepLiquidation()
    {
        return $this->tblDmepLiquidation;
    }
}