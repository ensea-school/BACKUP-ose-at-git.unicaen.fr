<?php

namespace OSETest\Entity\Db;

use Application\Entity\Db\Civilite;
use Application\Entity\Db\Corps;
use Application\Entity\Db\SectionCnu;
use Application\Entity\Db\Etablissement;
use Application\Entity\Db\IntervenantExterieur;
use Application\Entity\Db\IntervenantPermanent;
use Application\Entity\Db\RegimeSecu;
use Application\Entity\Db\Source;
use Application\Entity\Db\Utilisateur;
use Application\Entity\Db\Structure;
use Application\Entity\Db\TypeIntervenant;
use Application\Entity\Db\TypeStructure;
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
            throw new \LogicException("Vous devez spécifier une source par défaut avec Asset::setSource().");
        }
        return static::$source;
    }
    
    static public function source()
    {
        $e = new Source();
        $e->setLibelle('Source de test');
        
        return $e;
    }
    
    static public function user()
    {
        $e = new Utilisateur();
        $e->setDisplayName('Alco TEST')
                ->setEmail('test@domain.fr')
                ->setPassword('azerty')
                ->setState(1)
                ->setUsername(uniqid());
        
        return $e;
    }
    
    static public function etablissement()
    {
        $e = new Etablissement();
        $e
                ->setLibelle('Établissement de test')
                ->setSource(static::getSource())
                ->setSourceCode(rand(1, 999));
        
        return $e;
    }
        
    static public function typeStructure()
    {
        $e = new TypeStructure();
        $e->setLibelle('Type de test');
        
        return $e;
    }
        
    static public function structure(TypeStructure $typeStructure, Etablissement $etablissement)
    {
        $e = new Structure();
        $e
                ->setEtablissement($etablissement)
                ->setLibelleCourt('TEST')
                ->setLibelleLong('Structure de test')
                ->setType($typeStructure)
                ->setParente(null)
                ->setSource(static::getSource())
                ->setSourceCode(rand(1, 9999));
        
        return $e;
    }
        
    static public function typeIntervenantPerm()
    {
        $e = new TypeIntervenant();
        $e
                ->setId(TypeIntervenant::TYPE_PERMANENT)
                ->setLibelle("Intervenant permanent");
        
        return $e;
    }
        
    static public function typeIntervenantExt()
    {
        $e = new TypeIntervenant();
        $e
                ->setId(TypeIntervenant::TYPE_EXTERIEUR)
                ->setLibelle("Intervenant extérieur");
        
        return $e;
    }
        
    static public function corps()
    {
        $e = new Corps();
        $e
                ->setLibelleCourt("C1")
                ->setLibelleLong("Corps de test")
                ->setSource(static::getSource())
                ->setSourceCode("" . rand(1, 99999));
        
        return $e;
    }
        
    static public function sectionCnu()
    {
        $e = new SectionCnu();
        $e
                ->setCode("" . rand(1, 99))
                ->setLibelle("Section d'assaut");
        
        return $e;
    }
        
    static public function regimeSecu()
    {
        $e = new RegimeSecu();
        $e
                ->setCode('' . rand(1, 99))
                ->setLibelle("Taux de test")
                ->setTauxTaxe(5.5);
        
        return $e;
    }
        
    static public function civilite()
    {
        $e = new Civilite();
        $e
                ->setLibelle("Mister")
                ->setSexe('M');
        
        return $e;
    }
        
    static public function intervenantPermanent(Civilite $civilite, Structure $structure, Corps $corps, SectionCnu $sectionCnu)
    {
        $e = new IntervenantPermanent();
        $e
                ->setCorps($corps)
                ->setCivilite($civilite)
                ->setDateNaissance(new DateTime())
                ->setDepNaissanceCodeInsee('75')
                ->setDepNaissanceLibelle('IDF')
                ->setEmail('alco.test@unicaen.fr')
                ->setNomPatronymique('Test')
                ->setNomUsuel('Test')
                ->setPaysNaissanceCodeInsee('12')
                ->setPaysNaissanceLibelle('France')
                ->setPaysNationaliteCodeInsee('12')
                ->setPaysNationaliteLibelle('Française')
                ->setPrenom('Alco')
                ->setSectionCnu($sectionCnu)
                ->setSource(static::getSource())
                ->setSourceCode(rand(1, 99999))
                ->setTelMobile(null)
                ->setVilleNaissanceCodeInsee('75019')
                ->setVilleNaissanceLibelle('CF');
        
        return $e;
    }
    
    static public function intervenantExterieur(Civilite $civilite, Structure $structure, RegimeSecu $regimeSecu)
    {
        $e = new IntervenantExterieur();
        $e
                ->setRegimeSecu($regimeSecu)
                ->setCivilite($civilite)
                ->setDateNaissance(new DateTime())
                ->setDepNaissanceCodeInsee('75')
                ->setDepNaissanceLibelle('IDF')
                ->setEmail('alco.test@unicaen.fr')
                ->setNomPatronymique('Test')
                ->setNomUsuel('Test')
                ->setPaysNaissanceCodeInsee('12')
                ->setPaysNaissanceLibelle('France')
                ->setPaysNationaliteCodeInsee('12')
                ->setPaysNationaliteLibelle('Française')
                ->setPrenom('Alco')
                ->setSource(static::getSource())
                ->setSourceCode(rand(1, 99999))
                ->setTelMobile(null)
                ->setVilleNaissanceCodeInsee('75019')
                ->setVilleNaissanceLibelle('CF');
        
        return $e;
    }
}