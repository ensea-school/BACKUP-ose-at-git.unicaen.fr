<?php

namespace Contrat\Service;


/**
 * Description of TblContratServiceAwareTrait
 *
 */
trait TblContratServiceAwareTrait
{
    protected ?TblContratService $serviceTblContrat= null;



    /**
     * @param TblContratService $serviceTblContrat
     *
     * @return self
     */
    public function setServiceTblContrat(?TblContratService $serviceTblContrat)
    {
        $this->serviceTblContrat = $serviceTblContrat;

        return $this;
    }



    public function getServiceTblContrat(): ?TblContratService
    {
        if (empty($this->serviceTblContrat)) {
            $this->serviceTblContrat = \Framework\Application\Application::getInstance()->container()->get(TblContratService::class);
        }

        return $this->serviceTblContrat;
    }
}