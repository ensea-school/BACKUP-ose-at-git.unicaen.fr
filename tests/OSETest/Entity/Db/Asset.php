<?php

namespace OSETest\Entity\Db;

use Application\Entity\Db\Civilite;
use Application\Entity\Db\Corps;
use Intervenant\Entity\Db\Statut;
use Application\Entity\Db\Etablissement;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\IntervenantExterieur;
use Application\Entity\Db\IntervenantPermanent;
use Application\Entity\Db\RegimeSecu;
use Application\Entity\Db\Utilisateur;
use Application\Entity\Db\Structure;
use Intervenant\Entity\Db\TypeIntervenant;
use Application\Entity\Db\Dossier;
use Enseignement\Entity\Db\Service;
use Referentiel\Entity\Db\ServiceReferentiel;
use Application\Entity\Db\Annee;
use Application\Entity\Db\Etape;
use Application\Entity\Db\ElementPedagogique;
use Enseignement\Entity\Db\VolumeHoraire;
use Application\Entity\Db\Periode;
use Service\Entity\Db\TypeVolumeHoraire;
use Application\Entity\Db\TypeIntervention;
use Referentiel\Entity\Db\FonctionReferentiel;
use Application\Entity\Db\PieceJointe;
use Application\Entity\Db\TypePieceJointeStatut;
use Application\Entity\Db\TypePieceJointe;
use Application\Entity\Db\Fichier;
use Application\Entity\Db\Agrement;
use Application\Entity\Db\TypeAgrement;
use Application\Entity\Db\TypeValidation;
use Application\Entity\Db\Validation;
use Application\Entity\Db\Contrat;
use Application\Entity\Db\TypeContrat;
use DateTime;

/**
 * Données de tests.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class Asset
{
    const SOURCE_TEST = 'Test';



    static public function newUser()
    {
        $e = new Utilisateur();
        $e
            ->setDisplayName('Alco TEST')
            ->setEmail('test@domain.fr')
            ->setPassword('azerty')
            ->setState(1)
            ->setUsername(uniqid());

        return $e;
    }



    static public function newEtablissement()
    {
        $e = new Etablissement();
        $e
            ->setLibelle('Établissement de test')
            ->setSource(static::getSource())
            ->setSourceCode(uniqid());

        return $e;
    }



    static public function newStructure()
    {
        $e = new Structure();
        $e
            ->setLibelleCourt(uniqid('TEST '))
            ->setLibelleLong(uniqid('Structure de test'))
            ->setSource(static::getSource())
            ->setSourceCode(uniqid());

        return $e;
    }



    static public function newStatut(TypeIntervenant $typeIntervenant)
    {
        $e = new Statut();
        $e
            ->setLibelle("Statut TEST " . $typeIntervenant)
            ->setTypeIntervenant($typeIntervenant)
            ->setServiceStatutaire(100)
            ->setDossier(true)
            ->setOrdre(1)
            ->setCode(uniqid());

        return $e;
    }



    static public function newTypeIntervenant()
    {
        $e = new TypeIntervenant();
        $e
            ->setCode('|')
            ->setLibelle(uniqid("Type Intervenant "));

        return $e;
    }



    static public function newCorps()
    {
        $e = new Corps();
        $e
            ->setLibelleCourt("C1")
            ->setLibelleLong("Corps de test")
            ->setSource(static::getSource())
            ->setSourceCode(uniqid());

        return $e;
    }



    static public function newRegimeSecu()
    {
        $e = new RegimeSecu();
        $e
            ->setCode('' . rand(1, 99))
            ->setLibelle("Taux de test")
            ->setTauxTaxe(5.5);

        return $e;
    }



    static public function newCivilite()
    {
        $e = new Civilite();
        $e
            ->setLibelle("Mister")
            ->setSexe('M');

        return $e;
    }



    static public function newDossier(
        Civilite $civilite,
        $premierRecrutement,
        $perteEmploi,
        Statut $statut)
    {
        $e = new Dossier();
        $e
            ->setNomPatronymique(uniqid('Nom patro '))
            ->setNomUsuel(uniqid('Nom '))
            ->setPrenom(uniqid('Prénom '))
            ->setEmail(uniqid() . '@unicaen.fr')
            ->setCivilite($civilite)
            ->setNumeroInsee("16511146232746")
            ->setNumeroInseeEstProvisoire(false)
            ->setAdresse("16-18 rue de l'Equerre - 75019 Paris")
            ->setTelephone("0102030405")
            ->setRib("YYYYFRPPXXX-FR6541107987540063147191234")
            ->setPremierRecrutement($premierRecrutement)
            ->setPerteEmploi($perteEmploi)
            ->setStatut($statut);

        return $e;
    }



    static public function newService(
        Intervenant $intervenant,
        Structure $structureEns,
        ElementPedagogique $elementPedagogique,
        TypeVolumeHoraire $typeVolumeHoraire,
        Annee $annee)
    {
        $e = new Service();
        $e
            ->setElementPedagogique($elementPedagogique)
            ->setIntervenant($intervenant)
            ->setTypeVolumeHoraire($typeVolumeHoraire);

        return $e;
    }



    static public function newServiceReferentiel(
        Intervenant $intervenant,
        FonctionReferentiel $fonction,
        Structure $structure,
        Annee $annee)
    {
        $e = new ServiceReferentiel();
        $e
            ->setFonction($fonction)
            ->setHeures(rand(1, 50))
            ->setIntervenant($intervenant)
            ->setStructure($structure);

        return $e;
    }



    static public function newElementPedagogique(
        Structure $structure,
        Etape $etape,
        Periode $periode)
    {
        $e = new ElementPedagogique();
        $e
            ->setEtape($etape)
            ->setLibelle(uniqid("EP "))
            ->setPeriode($periode)
            ->setSource(self::getSource())
            ->setSourceCode(uniqid())
            ->setStructure($structure);

        return $e;
    }



    static public function newVolumeHoraire(
        Service $service,
        TypeIntervention $typeIntervention,
        Periode $periode,
        $heures)
    {
        $e = new VolumeHoraire();
        $e
            ->setHeures($heures)
            ->setContrat(null)
            ->setMotifNonPaiement(null)
            ->setPeriode($periode)
            ->setService($service)
            ->setTypeIntervention($typeIntervention)
            ->setTypeVolumeHoraire($service->getTypeVolumeHoraire());

        return $e;
    }



    static public function newTypePieceJointe()
    {
        $e = new TypePieceJointe();
        $e
            ->setCode(uniqid())
            ->setLibelle(uniqid("TPJ "));

        return $e;
    }



    static public function newTypePieceJointeStatut(Statut $statut, TypePieceJointe $type)
    {
        $e = new TypePieceJointeStatut();
        $e
            ->setType($type)
            ->setStatut($statut);

        return $e;
    }



    static public function newPieceJointe(TypePieceJointe $type, Dossier $dossier = null)
    {
        $e = new PieceJointe();
        $e
            ->setType($type)
            ->setDossier($dossier);

        return $e;
    }



    static public function newFichier()
    {
        $e = new Fichier();
        $e
            ->setNom(uniqid('Fichier '))
            ->setDescription(null)
            ->setType("image/png")
            ->setTaille(1024)
            ->setContenu("binary data");

        return $e;
    }



    static public function newTypeAgrement()
    {
        $e = new TypeAgrement();
        $e
            ->setCode(uniqid())
            ->setLibelle(uniqid("TA "));

        return $e;
    }



    static public function newAgrement(TypeAgrement $type, Intervenant $intervenant, Structure $structure, Annee $annee)
    {
        $e = new Agrement();
        $e
            ->setType($type)
            ->setAnnee($annee)
            ->setIntervenant($intervenant)
            ->setStructure($structure)
            ->setDateDecision(new \DateTime());

        return $e;
    }



    static public function newValidation(TypeValidation $type, Intervenant $intervenant, Structure $structure = null)
    {
        $e = new Validation();
        $e
            ->setIntervenant($intervenant)
            ->setStructure($structure ?: $intervenant->getStructure())
            ->setTypeValidation($type);

        return $e;
    }



    static public function newContrat(TypeContrat $type, Intervenant $intervenant, Structure $structure)
    {
        $e = new Contrat();
        $e
            ->setTypeContrat($type)
            ->setIntervenant($intervenant)
            ->setStructure($structure)
            ->setNumeroAvenant($type->estUnAvenant() ? 1 : 0);

        return $e;
    }



    static public function newTypeContrat($avenant = false)
    {
        $e = new TypeContrat();
        $e
            ->setCode($avenant ? TypeContrat::CODE_AVENANT : TypeContrat::CODE_CONTRAT)
            ->setLibelle(uniqid("TC "));

        return $e;
    }
}