<?php

namespace Paiement\Entity\Db;

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