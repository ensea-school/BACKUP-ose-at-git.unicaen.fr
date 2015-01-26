<?php

namespace OSETest\Rule;

use Application\Entity\Db\IntervenantExterieur;
use Application\Entity\Db\IntervenantPermanent;
use Application\Entity\Db\ServiceReferentiel;
use Application\Rule\Intervenant\PossedeReferentielRule;

/**
 * Test fonctionnel de la règle métier.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PossedeReferentielRuleTest extends BaseRuleTest
{
    /**
     * @var PossedeReferentielRule
     */
    protected $rule;
    
    /**
     * @var IntervenantPermanent 
     */
    protected $ip;
    
    /**
     * @var IntervenantPermanent 
     */
    protected $ipAvecRef;
    
    /**
     * @var IntervenantExterieur 
     */
    protected $ie;
    
    /**
     * @var ServiceReferentiel
     */
    protected $serviceReferentiel;
    
    /**
     * @return string
     */
    protected function getRuleName()
    {
        return 'PossedeReferentielRule';
    }
    
    /**
     * 
     */
    protected function setUp()
    {
        parent::setUp();
        
        $this->rule->setAnnee($this->getEntityProvider()->getAnnee());
        
        /**
         * Création du jeu d'essai
         */
        $this->ip        = $this->getEntityProvider()->getIntervenantPermanent();
        $this->ipAvecRef = $this->getEntityProvider()->getIntervenantPermanent();
        $this->ie        = $this->getEntityProvider()->getIntervenantExterieur();
        
        $this->serviceReferentiel = $this->getEntityProvider()->getServiceReferentiel($this->ipAvecRef); // NB: doit être instancié avant l'intervenant!
        $this->ipAvecRef->addServiceReferentiel($this->serviceReferentiel);
        
        $this->getEntityManager()->flush();
    }
    
    public function testDataset()
    {
        $this->assertCount(0, $this->ip->getServiceReferentiel());
        
        $this->assertContains($this->serviceReferentiel, $this->ipAvecRef->getServiceReferentiel());
        $this->assertCount(1, $this->ipAvecRef->getServiceReferentiel());
    }
    
    public function testIsRelevant()
    {
        $this->rule->setIntervenant(null);
        $this->assertTrue($this->rule->isRelevant());
        
        $this->rule->setIntervenant($this->ip);
        $this->assertTrue($this->rule->isRelevant());
        
        $this->rule->setIntervenant($this->ie);
        $this->assertFalse($this->rule->isRelevant());
    }
    
    /**
     * @expectedException Common\Exception\LogicException
     */
    public function testIntervenantExterieurInterdit()
    {
        $this->rule->setIntervenant($this->ie)->execute();
    }
    
    /**
     * @depends testDataset
     */
    public function testExecute()
    {
        /**
         * - Intervenant spécifié : aucun
         * - Un IE existe (sans référentiel car c'est interdit)
         * - Un IP existe sans référentiel
         * - Un IP existe avec du référentiel
         * => Le résultat doit contenir l'IP avec référentiel mais ni l'IE sans référentiel ni l'IP
         */
        $result = $this->rule->setIntervenant(null)->execute();
        $this->assertArrayHasKey($id = $this->ipAvecRef->getId(), $result);
        $this->assertEquals(['id' => $id], $result[$id]);
        $this->assertArrayNotHasKey($id = $this->ip->getId(), $result);
        $this->assertNotContains(['id' => $id], $result);
        $this->assertArrayNotHasKey($id = $this->ie->getId(), $result);
        $this->assertNotContains(['id' => $id], $result);
        $this->assertNull($this->rule->getMessage());
        
        /**
         * - Intervenant spécifié : IP sans référentiel
         * => Le résultat doit être vide
         */
        $result = $this->rule->setIntervenant($this->ip)->execute();
        $this->assertEquals([], $result);
        $this->assertNotNull($this->rule->getMessage());
        
        /**
         * - Intervenant spécifié : IP avec référentiel
         * => Le résultat ne doit contenir que cet IP
         */
        $result = $this->rule->setIntervenant($this->ipAvecRef)->execute();
        $this->assertEquals([$id = $this->ipAvecRef->getId() => ['id' => $id]], $result);
        $this->assertNull($this->rule->getMessage());
    }
    
    protected function tearDown()
    {
        parent::tearDown();
        
        /**
         * Suppression du jeu d'essai
         */
        $this->getEntityManager()->remove($this->ie);
        $this->getEntityManager()->remove($this->ip);
        $this->getEntityManager()->remove($this->ipAvecRef);
        $this->getEntityManager()->flush();
        $this->getEntityProvider()->removeNewEntities();
    }
}