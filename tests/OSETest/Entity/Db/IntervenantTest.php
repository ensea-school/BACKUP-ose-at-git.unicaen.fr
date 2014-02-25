<?php

namespace OSETest\Entity\Db;

use Application\Entity\Db\TypeIntervenant;

/**
 * Tests concernant les entités Intervenant.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class IntervenantTest extends BaseTest
{
    private $source;
    private $etablissement;
    private $civilite;
    private $typeStructure;
    private $structure;
    private $typePerm;
    private $typeExt;
    private $corps;
    private $sectionCnu;
    private $regimeSecu;
    
    protected function setUp()
    {
        parent::setUp();
        
        $em = $this->getEntityManager();
        
        $this->source = $em->getRepository('Application\Entity\Db\Source')->findOneBy(array('libelle' => "Test"));
        if (!$this->source) {
            $this->markTestIncomplete("Source de test (libelle = Test) introuvable.");
        }
        Asset::setSource($this->source);
        
        do {
            $this->etablissement = $em->getRepository('Application\Entity\Db\Etablissement')->findOneBy(array('sourceCode' => $sourceCode = uniqid()));
        } while ($this->etablissement);
        $this->etablissement = Asset::etablissement()->setSourceCode($sourceCode);
        $em->persist($this->etablissement);
        
        $this->typeStructure = $em->getRepository('Application\Entity\Db\TypeStructure')->find($id = 1);
        if (!$this->typeStructure) {
            $this->markTestIncomplete("Type de structure (id = $id) introuvable.");
        }
        
        do {
            $this->structure = $em->getRepository('Application\Entity\Db\Structure')->findOneBy(array('sourceCode' => $sourceCode = uniqid()));
        } while ($this->structure);
        $this->structure = Asset::structure($this->typeStructure, $this->etablissement)->setSourceCode($sourceCode);
        $em->persist($this->structure);

        $this->typePerm = $em->find('Application\Entity\Db\TypeIntervenant', $id = TypeIntervenant::TYPE_PERMANENT);
        if (!$this->typePerm) {
            $this->markTestIncomplete("Type intervenant permanent (id = $id) introuvable.");
        }
        
        $this->typeExt = $em->find('Application\Entity\Db\TypeIntervenant', $id = TypeIntervenant::TYPE_EXTERIEUR);
        if (!$this->typeExt) {
            $this->markTestIncomplete("Type intervenant extérieur (id = $id) introuvable.");
        }
        
        do {
            $this->corps = $em->getRepository('Application\Entity\Db\Corps')->findOneBy(array('sourceCode' => $sourceCode = uniqid()));
        } while ($this->corps);
        $this->corps = Asset::corps()->setSourceCode($sourceCode);
        $em->persist($this->corps);
        
        $this->sectionCnu = Asset::sectionCnu();
        $em->persist($this->sectionCnu);
        
        $this->regimeSecu = Asset::regimeSecu();
        $em->persist($this->regimeSecu);
        
        $this->civilite = $em->find('Application\Entity\Db\Civilite', $id = 1);
        if (!$this->civilite) {
            $this->markTestIncomplete("Civilité (id = $id) introuvable.");
        }
        
//        $em->flush();
    }
    
    public function testIntervenantPermanentClassTableInheritance()
    {
        $em = $this->getEntityManager();
        
        $ip = Asset::intervenantPermanent($this->civilite, $this->structure, $this->corps, $this->sectionCnu);
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
        
        $ie = Asset::intervenantExterieur($this->civilite, $this->structure, $this->regimeSecu);
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
        $em->remove($this->etablissement);
        $em->remove($this->corps);
        $em->remove($this->sectionCnu);
        $em->remove($this->regimeSecu);
        $em->flush();
    }
}