<?php

namespace Dossier\Service\Traits;


use Dossier\Service\TblDossierService;

trait TblDossierServiceAwareTrait
{
    protected ?TblDossierService $serviceTblDossier = null;



    /**
     * @param TblDossierService $serviceTblDossier
     *
     * @return self
     */
    public function setServiceTblDossier(?TblDossierService $serviceTblDossier)
    {
        $this->serviceTblDossier = $serviceTblDossier;

        return $this;
    }



    public function getServiceTblDossier(): ?TblDossierService
    {
        return $this->serviceTblDossier;
    }
}