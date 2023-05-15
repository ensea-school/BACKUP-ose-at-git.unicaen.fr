<?php

namespace Paiement\Entity\Db\Interfaces;

use Enseignement\Entity\Db\ServiceAPayerInterface;

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
    public function setServiceAPayerInterface(?ServiceAPayerInterface $serviceAPayerInterface);



    public function getServiceAPayerInterface(): ?ServiceAPayerInterface;
}