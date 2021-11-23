<?php

namespace ExportRh\Connecteur;


use ExportRh\Entity\IntervenantRh;
use Laminas\Form\Fieldset;

interface ConnecteurRhInterface
{

    public function rechercherIntervenantRh($nomUsuel = '', $prenom = '', $insee = ''): array;



    public function recupererIntervenantRh(\Application\Entity\Db\Intervenant $intervenant): ?IntervenantRh;



    public function recupererDonneesAdministrativesIntervenantRh(\Application\Entity\Db\Intervenant $intervenant): ?array;



    public function recupererAffectationEnCoursIntervenantRh(\Application\Entity\Db\Intervenant $intervenant): ?array;



    public function prendreEnChargeIntervenantRh(\Application\Entity\Db\Intervenant $intervenant, array $postData): ?string;



    public function renouvellerIntervenantRh(\Application\Entity\Db\Intervenant $intervenant, array $postData): ?string;



    public function synchroniserDonneesPersonnellesIntervenantRh(\Application\Entity\Db\Intervenant $intervenant, $datas): bool;



    public function recupererListeUO(): ?array;



    public function recupererListePositions(): ?array;



    public function recupererListeEmplois(): ?array;



    public function recupererListeStatuts(): ?array;



    public function recupererListeModalites(): ?array;



    public function getConnecteurName(): string;



    public function recupererFieldsetConnecteur(): ?Fieldset;

}