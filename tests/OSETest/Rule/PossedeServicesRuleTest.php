<?php

namespace OSETest\Rule;

use Application\Entity\Db\IntervenantExterieur;
use Application\Entity\Db\IntervenantPermanent;
use Application\Entity\Db\Service;
use Application\Rule\Intervenant\PossedeServicesRule;

/**
 * Test fonctionnel de la règle métier.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PossedeServicesRuleTest extends BaseRuleTest
{
    /**
     * @var PossedeServicesRule 
     */
    protected $rule;
    
    /**
     * @var IntervenantPermanent 
     */
    protected $ip;
    
    /**
     * @var IntervenantExterieur 
     */
    protected $ie;
    
    /**
     * @var Service 
     */
    protected $service;
    
    /**
     * @return string
     */
    protected function getRuleName()
    {
        return 'PossedeServicesRule';
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
        $this->ip = $this->getEntityProvider()->getIntervenantPermanent();
        $this->ie = $this->getEntityProvider()->getIntervenantExterieur();
        
        $this->service = $this->getEntityProvider()->getService($this->ie); // NB: doit être instancié avant l'intervenant!
        $this->ie->addService($this->service);
        
        $this->getEntityManager()->flush();
    }
    
    public function testDataset()
    {
        $this->assertContains($this->service, $this->ie->getService());
        $this->assertCount(1, $this->ie->getService());
    }
    
    public function testIsRelevant()
    {
        $this->rule->setIntervenant(null);
        $this->assertTrue($this->rule->isRelevant());
        
        $this->rule->setIntervenant($this->ie);
        $this->assertTrue($this->rule->isRelevant());
        
        $this->rule->setIntervenant($this->ip);
        $this->assertTrue($this->rule->isRelevant());
    }
    
    /**
     * @depends testDataset
     */
    public function testExecute()
    {
        /**
         * - Intervenant spécifié : aucun
         * - Un IE existe avec des services
         * - Un IP existe sans service
         * => Le résultat doit contenir l'IE mais pas l'IP
         */
        $result = $this->rule->setIntervenant(null)->execute();
        $this->assertNotContains(['id' => $this->ip->getId()], $result);
        $this->assertNull($this->rule->getMessage());
        
        /**
         * - Intervenant spécifié : IE avec services
         * => Le résultat ne contient que l'IE
         */
        $result = $this->rule->setIntervenant($this->ie)->execute();
        $this->assertEquals([0 => ['id' => $this->ie->getId()]], $result);
        $this->assertNull($this->rule->getMessage());
        
        /**
         * - Intervenant spécifié : IP sans service
         * => Le résultat est vide
         */
        $result = $this->rule->setIntervenant($this->ip)->execute();
        $this->assertEquals([], $result);
        $this->assertNotNull($this->rule->getMessage());
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