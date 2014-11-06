<?php

namespace OSETest\Rule;

use Application\Entity\Db\IntervenantExterieur;
use Application\Entity\Db\IntervenantPermanent;
use Application\Rule\Intervenant\NecessiteContratRule;

/**
 * Test fonctionnel de la règle métier.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class NecessiteContratRuleTest extends BaseRuleTest
{
    /**
     * @var NecessiteContratRule 
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
     * @return string
     */
    protected function getRuleName()
    {
        return 'NecessiteContratRule';
    }
    
    /**
     * 
     */
    protected function setUp()
    {
        parent::setUp();
        
        /**
         * Création du jeu d'essai
         */
        $this->ip = $this->getEntityProvider()->getIntervenantPermanent();
        $this->ie = $this->getEntityProvider()->getIntervenantExterieur();
        $this->getEntityManager()->flush();
    }
    
    public function testDataset()
    {
        $this->assertFalse($this->ip->getStatut()->getPeutAvoirContrat());
        $this->assertTrue($this->ie->getStatut()->getPeutAvoirContrat());
    }
    
    public function testIsRelevant()
    {
        static::assertTrue($this->rule->isRelevant());
    }
    
    /**
     * @depends testDataset
     */
    public function testExecute()
    {
        /**
         * - Intervenant spécifié : aucun
         * - Un IE existe
         * - Un IP existe
         * => L'IE doit être dans le résultat, mais pas l'IP
         */
        $this->rule->setIntervenant(null);
        $this->assertIntervenantNotInResult($this->ip);
        $this->assertIntervenantInResult($this->ie);
        
        /**
         * - Intervenant spécifié : IP (ne peut pas avoir un contrat de par son statut)
         * => Le résultat doit être vide
         */
        $this->rule->setIntervenant($this->ip);
        $this->assertIntervenantNotInResult($this->ip);
        
        /**
         * - Intervenant spécifié : IE (peut avoir un contrat de par son statut)
         * => Le résultat ne doit contenir que l'IE
         */
        $this->rule->setIntervenant($this->ie);
        $this->assertIntervenantInResult($this->ie);
    }
    
    protected function tearDown()
    {
        parent::tearDown();
        
        /**
         * Suppression du jeu d'essai
         */
        $this->getEntityManager()->remove($this->ip);
        $this->getEntityManager()->remove($this->ie);
        $this->getEntityManager()->flush();
        $this->getEntityProvider()->removeNewEntities();
    }
}