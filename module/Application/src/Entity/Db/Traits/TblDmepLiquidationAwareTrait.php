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
    protected ?TblDmepLiquidation $tblDmepLiquidation = null;



    /**
     * @param TblDmepLiquidation $tblDmepLiquidation
     *
     * @return self
     */
    public function setTblDmepLiquidation( ?TblDmepLiquidation $tblDmepLiquidation )
    {
        $this->tblDmepLiquidation = $tblDmepLiquidation;

        return $this;
    }



    public function getTblDmepLiquidation(): ?TblDmepLiquidation
    {
        return $this->tblDmepLiquidation;
    }
}