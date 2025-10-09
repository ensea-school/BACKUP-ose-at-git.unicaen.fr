<?php

namespace Application\Service\Traits;

use Application\Service\AnneeService;

/**
 * Description of AnneeServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait AnneeServiceAwareTrait
{
    protected ?AnneeService $serviceAnnee = null;



    /**
     * @param AnneeService $serviceAnnee
     *
     * @return self
     */
    public function setServiceAnnee(?AnneeService $serviceAnnee)
    {
        $this->serviceAnnee = $serviceAnnee;

        return $this;
    }



    public function getServiceAnnee(): ?AnneeService
    {
        if (empty($this->serviceAnnee)) {
            $this->serviceAnnee = \Unicaen\Framework\Application\Application::getInstance()->container()->get(AnneeService::class);
        }

        return $this->serviceAnnee;
    }
}