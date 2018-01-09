<?php

namespace Application\Processus\Traits;

use Application\Processus\IndicateurProcessus;

/**
 * Description of IndicateurProcessusAwareTrait
 *
 * @author UnicaenCode
 */
trait IndicateurProcessusAwareTrait
{
    /**
     * @var IndicateurProcessus
     */
    private $processusIndicateur;



    /**
     * @param IndicateurProcessus $processusIndicateur
     *
     * @return self
     */
    public function setProcessusIndicateur(IndicateurProcessus $processusIndicateur)
    {
        $this->processusIndicateur = $processusIndicateur;

        return $this;
    }



    /**
     * @return IndicateurProcessus
     */
    public function getProcessusIndicateur()
    {
        if (empty($this->processusIndicateur)) {
            $this->processusIndicateur = \Application::$container->get(IndicateurProcessus::class);
        }

        return $this->processusIndicateur;
    }
}