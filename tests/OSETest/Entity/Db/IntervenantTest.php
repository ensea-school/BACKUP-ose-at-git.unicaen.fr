<?php

namespace OSETest\Entity\Db;

/**
 * Tests concernant les entités Intervenant.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class IntervenantTest extends BaseTest
{
    private $source;
    private $typeStructure;
    private $structure;
    private $typePerm;
    private $typeExt;
    private $corps;
    private $regimeSecu;
    
    protected function setUp()
    {
        parent::setUp();
        
        $em = $this->getEntityManager();
        
        $this->source = $em->find('Application\Entity\Db\Source', $id = Asset::SOURCE_TEST);
        if (!$this->source) {
            $this->source = Asset::source()->setId($id);
            $em->persist($this->source);
        }
        Asset::setSource($this->source);
        
        $this->etablissement = Asset::etablissement();
        $em->persist($this->etablissement);
        
        $this->typeStructure = $em->find('Application\Entity\Db\TypeStructure', 'SCM');
        
        $this->structure = Asset::structure($this->typeStructure, $this->etablissement);
        $em->persist($this->structure);
        
        $this->typePerm = $em->find('Application\Entity\Db\TypeIntervenant', $id = 'P');
        if (!$this->typePerm) {
            $this->typePerm = Asset::typeIntervenantPerm();
            $em->persist($this->typePerm);
        }
        
        $this->typeExt = $em->find('Application\Entity\Db\TypeIntervenant', $id = 'E');
        if (!$this->typeExt) {
            $this->typeExt = Asset::typeIntervenantExt();
            $em->persist($this->typeExt);
        }
        
        $this->corps = Asset::corps();
        $em->persist($this->corps);
        
        $this->regimeSecu = $em->find('Application\Entity\Db\RegimeSecu', $id = '60');
        if (!$this->regimeSecu) {
            $this->regimeSecu = Asset::regimeSecu()->setId($id);
            $em->persist($this->regimeSecu);
        }
    }
    
    public function testIntervenantPermanentClassTableInheritance()
    {
        $em = $this->getEntityManager();
        
        $ip = Asset::intervenantPermanent($this->structure, $this->corps);
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
        
        $ie = Asset::intervenantExterieur($this->structure, $this->regimeSecu);
        $em->persist($ie);
        $em->flush();
        
        $i = $em->find('Application\Entity\Db\Intervenant', $ie->getId());
        $this->assertInstanceOf('Application\Entity\Db\Intervenant', $i);
        $this->assertInstanceOf('Application\Entity\Db\IntervenantExterieur', $i);
        $this->assertSame($ie, $i);
        
        $ip = $em->find('Application\Entity\Db\IntervenantPermanent', $ie->getId());
        $this->assertNull($ip);
        
        $em->remove($ie);
        $em->flush();
    }
    
    protected function tearDown()
    {
        parent::tearDown();
        
        $em = $this->getEntityManager();
        
        $em->remove($this->structure);
        $em->remove($this->corps);
        $em->remove($this->etablissement);
        $em->flush();
    }
}