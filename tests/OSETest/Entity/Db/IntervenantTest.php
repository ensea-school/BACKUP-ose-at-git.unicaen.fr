<?php

namespace OSETest\Entity\Db;

use Application\Entity\Db\Corps;
use Application\Entity\Db\Etablissement;
use Application\Entity\Db\IntervenantExterieur;
use Application\Entity\Db\IntervenantPermanent;
use Application\Entity\Db\RegimeSecu;
use Application\Entity\Db\Structure;
use Application\Entity\Db\TypeIntervenant;
use Application\Entity\Db\TypeStructure;
use DateTime;

/**
 * Tests concernant les entités Intervenant.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class IntervenantTest extends BaseTest
{
    protected $typeStructure;
    protected $structure;
    protected $typePerm;
    protected $typeExt;
    protected $corps;
    
    protected function setUp()
    {
        parent::setUp();
        
        $em = $this->getEntityManager();
        
        $this->etablissement = new Etablissement();
        $this->etablissement
                ->setLibelle('UCBN');
        $em->persist($this->etablissement);
        
        $this->typeStructure = new TypeStructure();
        $this->typeStructure
                ->setLibelle('Service central');
        $em->persist($this->typeStructure);
        
        $this->structure = new Structure();
        $this->structure
                ->setEtablissement($this->etablissement)
                ->setLibelleCourt('DSI')
                ->setLibelleLong('Dir Syst Info')
                ->setType($this->typeStructure)
                ->setParente(null);
        $em->persist($this->structure);
        
        $this->typePerm = $em->find('Application\Entity\Db\TypeIntervenant', $id = 'P');
        if (!$this->typePerm) {
//            $this->markTestSkipped("Aucun enregistrement TypeIntervenant trouvé avec l'id $id.");
            $this->typePerm = new TypeIntervenant();
            $this->typePerm
                    ->setId($id)
                    ->setLibelle("Intervenant permanent");
            $em->persist($this->typePerm);
        }
        
        $this->typeExt = $em->find('Application\Entity\Db\TypeIntervenant', $id = 'E');
        if (!$this->typeExt) {
//            $this->markTestSkipped("Aucun enregistrement TypeIntervenant trouvé avec l'id $id.");
            $this->typeExt = new TypeIntervenant();
            $this->typeExt
                    ->setId($id)
                    ->setLibelle("Intervenant extérieur");
            $em->persist($this->typeExt);
        }
        
        $this->corps = new Corps();
        $this->corps
                ->setLibelleCourt("CDR")
                ->setLibelleLong("Corps de rêve");
        $em->persist($this->corps);
    }
    
    public function testIntervenantPermanentClassTableInheritance()
    {
        $em = $this->getEntityManager();
        
        $ip = new IntervenantPermanent();
        $ip
                ->setCorps($this->corps)
                ->setCivilite()
                ->setDateNaissance(new DateTime())
                ->setDepNaissanceCodeInsee('75')
                ->setDepNaissanceLibelle('IDF')
                ->setEmail('paul.hochon@unicaen.fr')
                ->setNomPatronymique('Hochon')
                ->setNomUsuel('Hochon')
                ->setPaysNaissanceCodeInsee('12')
                ->setPaysNaissanceLibelle('France')
                ->setPaysNationaliteCodeInsee('12')
                ->setPaysNationaliteLibelle('Française')
                ->setPersonnelId(null)
                ->setPrenom('Paul')
                ->setPrimeExcellenceScientifique(null)
                ->setStructure($this->structure)
                ->setTelMobile(null)
                ->setVilleNaissanceCodeInsee('75019')
                ->setVilleNaissanceLibelle('CF');
        $em->persist($ip);
        $em->flush();
        
        $i = $em->find('Application\Entity\Db\Intervenant', $ip->getId());
        $this->assertInstanceOf('Application\Entity\Db\Intervenant', $i);
        $this->assertInstanceOf('Application\Entity\Db\IntervenantPermanent', $i);
        $this->assertSame($ip, $i);
        
        $ie = $em->find('Application\Entity\Db\IntervenantExterieur', $ip->getId());
        $this->assertNull($ie);
        
        $em->remove($ip);
        $em->flush();
    }
    
    public function testIntervenantExterieurClassTableInheritance()
    {
        $em = $this->getEntityManager();
        
        $regime = new RegimeSecu();
        $regime
                ->setLibelle("Taux")
                ->setTauxTaxe(0);
        $em->persist($regime);
        
        $ie = new IntervenantExterieur();
        $ie
                ->setRegimeSecu($regime)
                ->setProfession("Vigneron")
                ->setCivilite()
                ->setDateNaissance(new DateTime())
                ->setDepNaissanceCodeInsee('75')
                ->setDepNaissanceLibelle('IDF')
                ->setEmail('paul.hochon@unicaen.fr')
                ->setNomPatronymique('Hochon')
                ->setNomUsuel('Hochon')
                ->setPaysNaissanceCodeInsee('12')
                ->setPaysNaissanceLibelle('France')
                ->setPaysNationaliteCodeInsee('12')
                ->setPaysNationaliteLibelle('Française')
                ->setPersonnelId(null)
                ->setPrenom('Paul')
                ->setPrimeExcellenceScientifique(null)
                ->setStructure($this->structure)
                ->setTelMobile(null)
                ->setVilleNaissanceCodeInsee('75019')
                ->setVilleNaissanceLibelle('CF');
        $em->persist($ie);
        $em->flush();
        
        $i = $em->find('Application\Entity\Db\Intervenant', $ie->getId());
        $this->assertInstanceOf('Application\Entity\Db\Intervenant', $i);
        $this->assertInstanceOf('Application\Entity\Db\IntervenantExterieur', $i);
        $this->assertSame($ie, $i);
        
        $ip = $em->find('Application\Entity\Db\IntervenantPermanent', $ie->getId());
        $this->assertNull($ip);
        
        $em->remove($ie);
        $em->remove($regime);
        $em->flush();
    }
    
    protected function tearDown()
    {
        parent::tearDown();
        
        $em = $this->getEntityManager();
        
        $em->remove($this->structure);
        $em->remove($this->typeStructure);
        $em->remove($this->corps);
        $em->remove($this->etablissement);
        $em->flush();
    }
}