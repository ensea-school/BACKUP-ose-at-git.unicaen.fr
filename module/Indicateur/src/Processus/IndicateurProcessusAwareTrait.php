<?php

namespace Indicateur\Processus;


/**
 * Description of IndicateurProcessusAwareTrait
 *
 * @author UnicaenCode
 */
trait IndicateurProcessusAwareTrait
{
    protected ?IndicateurProcessus $processusIndicateur = null;



    /**
     * @param IndicateurProcessus $processusIndicateur
     *
     * @return self
     */
    public function setProcessusIndicateur(?IndicateurProcessus $processusIndicateur)
    {
        $this->processusIndicateur = $processusIndicateur;

        return $this;
    }



    public function getProcessusIndicateur(): ?IndicateurProcessus
    {
        if (empty($this->processusIndicateur)) {
            $this->processusIndicateur = \Framework\Application\Application::getInstance()->container()->get(IndicateurProcessus::class);
        }

        return $this->processusIndicateur;
    }
}