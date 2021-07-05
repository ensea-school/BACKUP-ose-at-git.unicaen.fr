<?php

namespace ExportRh\Connecteur;


use ExportRh\Entity\IntervenantRh;

interface ConnecteurRhInterface
{
    /**
     * Recherche dans le SIRH la liste des fiches intervenant qui peuvent correspondre à l'intervenant fourni
     *
     * @param Intervenant $intervenant
     *
     * @return array
     */
    public function rechercherIntervenantRh($nomUsuel = '', $prenom = '', $insee = ''): array;



    public function trouverIntervenantRh(\Application\Entity\Db\Intervenant $intervenant): ?IntervenantRh;



    public function prendreEnChargeIntervenantRh(\Application\Entity\Db\Intervenant $intervenant): ?IntervenantRh;



    public function recupererListeUO(): ?array;



    public function recupererListePositions(): ?array;

}