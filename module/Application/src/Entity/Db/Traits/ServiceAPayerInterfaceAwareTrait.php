<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\ServiceAPayerInterface;

/**
 * Description of ServiceAPayerInterfaceAwareTrait
 *
 * @author UnicaenCode
 */
trait ServiceAPayerInterfaceAwareTrait
{
    protected ?ServiceAPayerInterface $serviceAPayerInterface = null;



    /**
     * @param ServiceAPayerInterface $serviceAPayerInterface
     *
     * @return self
     */
    public function setServiceAPayerInterface( ?ServiceAPayerInterface $serviceAPayerInterface )
    {
        $this->serviceAPayerInterface = $serviceAPayerInterface;

        return $this;
    }



    public function getServiceAPayerInterface(): ?ServiceAPayerInterface
    {
        return $this->serviceAPayerInterface;
    }
}