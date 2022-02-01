<?php

namespace Indicateur\Processus;


/**
 * Description of IndicateurProcessusAwareTrait
 *
 * @author UnicaenCode
 */
trait IndicateurProcessusAwareTrait
{
    protected ?IndicateurProcessus $processusIndicateur;



    /**
     * @param IndicateurProcessus|null $processusIndicateur
     *
     * @return self
     */
    public function setProcessusIndicateur( ?IndicateurProcessus $processusIndicateur )
    {
        $this->processusIndicateur = $processusIndicateur;

        return $this;
    }



    public function getProcessusIndicateur(): ?IndicateurProcessus
    {
        if (!$this->processusIndicateur){
            $this->processusIndicateur = \Application::$container->get(IndicateurProcessus::class);
        }

        return $this->processusIndicateur;
    }
}