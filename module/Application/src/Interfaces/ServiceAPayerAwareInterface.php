<?php

namespace Application\Interfaces;

use Application\Entity\Db\ServiceAPayerInterface;

/**
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
interface ServiceAPayerAwareInterface
{

    /**
     * Spécifie le service à payer concerné.
     *
     * @param ServiceAPayerInterface $serviceAPayer le service à payer concerné
     *
     * @return self
     */
    public function setServiceAPayer(ServiceAPayerInterface $serviceAPayer = null);



    /**
     * Retourne le service à payer concerné.
     *
     * @return ServiceAPayerInterface
     */
    public function getServiceAPayer(): ServiceAPayerInterface;
}