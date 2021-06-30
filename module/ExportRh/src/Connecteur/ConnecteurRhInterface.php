<?php

namespace ExportRh\Connecteur;


use ExportRh\Entity\Intervenant;
use ExportRh\Entity\IntervenantRH;

interface ConnecteurRhInterface
{
    /**
     * Recherche dans le SIRH la liste des fiches intervenant qui peuvent correspondre à l'intervenant fourni
     *
     * @param Intervenant $intervenant
     *
     * @return array
     */
    public function rechercherIntervenant($nomUsuel, $prenom, $insee, $dateNaissance): ?IntervenantRH;



    public function trouverIntervenant(\Application\Entity\Db\Intervenant $intervenant): ?IntervenantRH;



    /**
     * @param Intervenant $intervenant
     *
     * @return bool
     */
    public function exporterIntervenant(Intervenant $intervenant): bool;
}