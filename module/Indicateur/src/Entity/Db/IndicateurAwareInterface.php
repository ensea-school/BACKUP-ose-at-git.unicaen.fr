<?php

namespace Indicateur\Entity\Db;


/**
 * Description of IndicateurAwareInterface
 *
 * @author UnicaenCode
 */
interface IndicateurAwareInterface
{
    /**
     * @param Indicateur|null $indicateur
     *
     * @return self
     */
    public function setIndicateur( ?Indicateur $indicateur );



    public function getIndicateur(): ?Indicateur;
}