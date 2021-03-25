<?php

namespace ExportRh\Connecteur;


use ExportRh\Entity\Intervenant;

interface ExportRhInterface
{
    /**
     * Recherche dans le SIRH la liste des fiches intervenant qui peuvent correspondre à l'intervenant fourni
     *
     * @param Intervenant $intervenant
     *
     * @return array
     */
    public function intervenantEquivalents(\Application\Entity\Db\Intervenant $intervenant): Intervenant[];



    /**
     * @param Intervenant $intervenant
     *
     * @return bool
     */
    public function intervenantExport(Intervenant $intervenant): bool;
}