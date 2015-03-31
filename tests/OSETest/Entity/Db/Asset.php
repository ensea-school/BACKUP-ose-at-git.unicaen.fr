<?php

namespace OSETest\Entity\Db;

use Application\Entity\Db\Civilite;
use Application\Entity\Db\Corps;
use Application\Entity\Db\StatutIntervenant;
use Application\Entity\Db\Etablissement;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\IntervenantExterieur;
use Application\Entity\Db\IntervenantPermanent;
use Application\Entity\Db\RegimeSecu;
use Application\Entity\Db\Source;
use Application\Entity\Db\Utilisateur;
use Application\Entity\Db\Structure;
use Application\Entity\Db\TypeIntervenant;
use Application\Entity\Db\TypeStructure;
use Application\Entity\Db\Dossier;
use Application\Entity\Db\Service;
use Application\Entity\Db\ServiceReferentiel;
use Application\Entity\Db\Annee;
use Application\Entity\Db\Etape;
use Application\Entity\Db\ElementPedagogique;
use Application\Entity\Db\VolumeHoraire;
use Application\Entity\Db\Periode;
use Application\Entity\Db\TypeVolumeHoraire;
USE Application\Entity\Db\TypeIntervention;
use Application\Entity\Db\FonctionReferentiel;
use Application\Entity\Db\PieceJointe;
use Application\Entity\Db\TypePieceJointeStatut;
use Application\Entity\Db\TypePieceJointe;
use Application\Entity\Db\Fichier;
use Application\Entity\Db\Agrement;
use Application\Entity\Db\TypeAgrement;
use Application\Entity\Db\TypeAgrementStatut;
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
    
    /**
     * @var Source
     */
    static protected $source;
    
    static public function setSource(Source $source)
    {
        static::$source = $source;
    }
    
    static public function getSource()
    {
        if (null === static::$source) {
            throw new \LogicException("Vous devez spécifier une source par défaut avec " . __CLASS__ . "::setSource().");
        }
        return static::$source;
    }
    
    static public function newSource()
    {
        $e = new Source();
        $e->setLibelle('Source de test');
        
        return $e;
    }
    
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
        
    static public function newTypeStructure()
    {
        $e = new TypeStructure();
        $e->setLibelle('Type de test');
        
        return $e;
    }
        
    static public function newStructure(TypeStructure $typeStructure, Etablissement $etablissement, Structure $parente)
    {
        $e = new Structure();
        $e
                ->setEtablissement($etablissement)
                ->setLibelleCourt(uniqid('TEST '))
                ->setLibelleLong(uniqid('Structure de test'))
                ->setNiveau(2)
                ->setType($typeStructure)
                ->setParente($parente)
                ->setParenteNiv2($e)
                ->setSource(static::getSource())
                ->setSourceCode(uniqid());
        
        return $e;
    }
        
    static public function newStatutIntervenant(TypeIntervenant $typeIntervenant)
    {
        $e = new StatutIntervenant();
        $e
                ->setLibelle("Statut TEST " . $typeIntervenant)
                ->setTypeIntervenant($typeIntervenant)
                ->setServiceStatutaire(100)
                ->setDepassement(false)
                ->setFonctionEC(true)
                ->setMaximumHETD(0)
                ->setNonAutorise(false)
                ->setPeutChoisirDansDossier(true)
                ->setPeutSaisirDossier(true)
                ->setPeutSaisirReferentiel(true)
                ->setPeutSaisirService(true)
                ->setPeutAvoirContrat(0)
                ->setPlafondReferentiel(100)
                ->setOrdre(1)
                ->setSource(static::getSource())
                ->setSourceCode(uniqid());
        
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
            StatutIntervenant $statutIntervenant)
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
                ->setStatut($statutIntervenant);
        
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
                ->setEtablissement($intervenant->getStructure()->getEtablissement())
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
                ->setAnnee($annee)
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
        
    static public function newIntervenantPermanent(
            Civilite $civilite, 
            StatutIntervenant $statut, 
            Structure $structure, 
            Corps $corps)
    {
        $e = new IntervenantPermanent();
        $e
                ->setStructure($structure)
                ->setStatut($statut)
                ->setCorps($corps)
                ->setCivilite($civilite)
                ->setDateNaissance(new DateTime())
                ->setDepNaissanceCodeInsee('75')
                ->setDepNaissanceLibelle('IDF')
                ->setEmail(uniqid() . '@unicaen.fr')
                ->setNomPatronymique(uniqid('Nom patro '))
                ->setNomUsuel(uniqid('Nom '))
                ->setPaysNaissanceCodeInsee('12')
                ->setPaysNaissanceLibelle('France')
                ->setPaysNationaliteCodeInsee('12')
                ->setPaysNationaliteLibelle('Française')
                ->setPrenom(uniqid('Prénom '))
                ->setSource(static::getSource())
                ->setSourceCode(uniqid())
                ->setTelMobile(null)
                ->setVilleNaissanceCodeInsee('75019')
                ->setVilleNaissanceLibelle('CF');
        
        return $e;
    }
    
    static public function newIntervenantExterieur(
            Civilite $civilite, 
            StatutIntervenant $statut, 
            Structure $structure, 
            RegimeSecu $regimeSecu)
    {
        $e = new IntervenantExterieur();
        $e
                ->setStructure($structure)
                ->setStatut($statut)
                ->setRegimeSecu($regimeSecu)
                ->setCivilite($civilite)
                ->setDateNaissance(new DateTime())
                ->setDepNaissanceCodeInsee('75')
                ->setDepNaissanceLibelle('IDF')
                ->setEmail(uniqid() . '@unicaen.fr')
                ->setNomPatronymique(uniqid('Nom patro '))
                ->setNomUsuel(uniqid('Nom '))
                ->setPaysNaissanceCodeInsee('12')
                ->setPaysNaissanceLibelle('France')
                ->setPaysNationaliteCodeInsee('12')
                ->setPaysNationaliteLibelle('Française')
                ->setPrenom(uniqid('Prénom '))
                ->setSource(static::getSource())
                ->setSourceCode(uniqid())
                ->setTelMobile(null)
                ->setVilleNaissanceCodeInsee('75019')
                ->setVilleNaissanceLibelle('CF');
        
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
    
    static public function newTypePieceJointeStatut(StatutIntervenant $statut, TypePieceJointe $type)
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
    
    static public function newTypeAgrementStatut(StatutIntervenant $statut, TypeAgrement $type)
    {
        $e = new TypeAgrementStatut();
        $e
                ->setType($type)
                ->setStatut($statut);
        
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