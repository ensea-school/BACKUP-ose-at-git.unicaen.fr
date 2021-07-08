<?php

namespace ExportRh\Connecteur;


use ExportRh\Entity\IntervenantRh;
use Zend\Form\Fieldset;

interface ConnecteurRhInterface
{

    public function rechercherIntervenantRh($nomUsuel = '', $prenom = '', $insee = ''): array;



    public function trouverIntervenantRh(\Application\Entity\Db\Intervenant $intervenant): ?IntervenantRh;



    public function prendreEnChargeIntervenantRh(\Application\Entity\Db\Intervenant $intervenant, array $postData): ?string;



    public function getConnecteurName(): string;



    public function recupererFieldsetConnecteur(): ?Fieldset;

}