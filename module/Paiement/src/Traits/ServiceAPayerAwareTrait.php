<?php

namespace Paiement\Traits;

use Paiement\Entity\Db\ServiceAPayerInterface;

/**
 * Description of ServiceAPayerAwareTrait
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
trait ServiceAPayerAwareTrait
{
    /**
     * @var ServiceAPayerInterface
     */
    protected $serviceAPayer;



    /**
     * Spécifie le service à payer concerné.
     *
     * @param ServiceAPayerInterface $serviceAPayer le service à payer concerné
     */
    public function setServiceAPayer(ServiceAPayerInterface $serviceAPayer = null)
    {
        $this->serviceAPayer = $serviceAPayer;

        return $this;
    }



    /**
     * Retourne le service à payer concerné.
     *
     * @return ServiceAPayerInterface
     */
    public function getServiceAPayer(): ServiceAPayerInterface
    {
        return $this->serviceAPayer;
    }
}