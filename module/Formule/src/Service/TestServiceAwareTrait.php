<?php

namespace Formule\Service;

/**
 * Description of FormuleTestIntervenantServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TestServiceAwareTrait
{
    protected ?TestService $serviceTest = null;



    /**
     * @param TestService $serviceTest
     *
     * @return self
     */
    public function setServiceTest(?TestService $serviceTest)
    {
        $this->serviceTest = $serviceTest;

        return $this;
    }



    public function getServiceTest(): ?TestService
    {
        if (empty($this->serviceTest)) {
            $this->serviceTest =\Unicaen\Framework\Application\Application::getInstance()->container()->get(TestService::class);
        }

        return $this->serviceTest;
    }
}