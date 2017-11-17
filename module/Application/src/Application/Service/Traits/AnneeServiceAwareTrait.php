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
    /**
     * @var AnneeService
     */
    private $serviceAnnee;



    /**
     * @param AnneeService $serviceAnnee
     *
     * @return self
     */
    public function setServiceAnnee(AnneeService $serviceAnnee)
    {
        $this->serviceAnnee = $serviceAnnee;

        return $this;
    }



    /**
     * @return AnneeService
     */
    public function getServiceAnnee()
    {
        if (empty($this->serviceAnnee)) {
            $this->serviceAnnee = \Application::$container->get(AnneeService::class);
        }

        return $this->serviceAnnee;
    }
}