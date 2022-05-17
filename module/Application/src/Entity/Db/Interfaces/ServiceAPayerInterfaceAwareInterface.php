<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\ServiceAPayerInterface;

/**
 * Description of ServiceAPayerInterfaceAwareInterface
 *
 * @author UnicaenCode
 */
interface ServiceAPayerInterfaceAwareInterface
{
    /**
     * @param ServiceAPayerInterface|null $serviceAPayerInterface
     *
     * @return self
     */
    public function setServiceAPayerInterface( ?ServiceAPayerInterface $serviceAPayerInterface );



    public function getServiceAPayerInterface(): ?ServiceAPayerInterface;
}