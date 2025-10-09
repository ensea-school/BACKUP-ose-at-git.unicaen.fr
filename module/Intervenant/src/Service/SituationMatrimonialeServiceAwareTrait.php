<?php

namespace Intervenant\Service;

/**
 * Description of SituationMatrimonialeServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait SituationMatrimonialeServiceAwareTrait
{
    protected ?SituationMatrimonialeService $serviceSituationMatrimoniale = null;



    /**
     * @param SituationMatrimonialeService $serviceSituationMatrimoniale
     *
     * @return self
     */
    public function setServiceSituationMatrimoniale(?SituationMatrimonialeService $serviceSituationMatrimoniale)
    {
        $this->serviceSituationMatrimoniale = $serviceSituationMatrimoniale;

        return $this;
    }



    public function getServiceSituationMatrimoniale(): ?SituationMatrimonialeService
    {
        if (empty($this->serviceSituationMatrimoniale)) {
            $this->serviceSituationMatrimoniale = \Unicaen\Framework\Application\Application::getInstance()->container()->get(SituationMatrimonialeService::class);
        }

        return $this->serviceSituationMatrimoniale;
    }
}