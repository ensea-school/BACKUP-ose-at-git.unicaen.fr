<?php

namespace Paiement\Service;


/**
 * Description of TblPaiementServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TblPaiementServiceAwareTrait
{
    protected ?TblPaiementService $serviceTblPaiement= null;



    /**
     * @param TblPaiementService $serviceTblPaiement
     *
     * @return self
     */
    public function setServiceTblPaiement(?TblPaiementService $serviceTblPaiement)
    {
        $this->serviceTblPaiement = $serviceTblPaiement;

        return $this;
    }



    public function getServiceTblPaiement(): ?TblPaiementService
    {
        if (empty($this->serviceTblPaiement)) {
            $this->serviceTblPaiement = \Framework\Application\Application::getInstance()->container()->get(TblPaiementService::class);
        }

        return $this->serviceTblPaiement;
    }
}