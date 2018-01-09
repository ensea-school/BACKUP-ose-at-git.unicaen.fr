<?php

namespace Application\Service\Traits;

use Application\Service\ParametresService;

/**
 * Description of ParametresAwareTrait
 *
 * @author UnicaenCode
 */
trait ParametresServiceAwareTrait
{
    /**
     * @var ParametresService
     */
    private $serviceParametres;



    /**
     * @param ParametresService $serviceParametres
     *
     * @return self
     */
    public function setServiceParametres(ParametresService $serviceParametres)
    {
        $this->serviceParametres = $serviceParametres;

        return $this;
    }



    /**
     * @return ParametresService
     */
    public function getServiceParametres()
    {
        if (empty($this->serviceParametres)) {
            $this->serviceParametres = \Application::$container->get(ParametresService::class);
        }

        return $this->serviceParametres;
    }
}