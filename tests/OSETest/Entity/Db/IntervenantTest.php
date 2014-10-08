<?php

namespace OSETest\Entity\Db;

/**
 * Tests concernant les entités Intervenant.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class IntervenantTest extends BaseTest
{
    /**
     * 
     */
    protected function setUp()
    {
        parent::setUp();
    }
    
    public function testIntervenantPermanentClassTableInheritance()
    {
        $em = $this->getEntityManager();
        
        $ip = $this->getEntityProvider()->getIntervenantPermanent();
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
        
        $ie = $this->getEntityProvider()->getIntervenantExterieur();
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
        
        /**
         * Suppression du jeu d'essai
         */
        $this->getEntityProvider()->removeNewEntities();
    }
}