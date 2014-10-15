<?php

namespace OSETest\Rule;

use Application\Entity\Db\IntervenantExterieur;
use Application\Entity\Db\IntervenantPermanent;
use Application\Rule\Intervenant\PeutSaisirDossierRule;

/**
 * Test fonctionnel de la règle métier.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PeutSaisirDossierRuleTest extends BaseRuleTest
{
    /**
     * @var PeutSaisirDossierRule 
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
        return 'PeutSaisirDossierRule';
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
        $this->assertFalse($this->ip->getStatut()->getPeutSaisirDossier());
        $this->assertTrue($this->ie->getStatut()->getPeutSaisirDossier());
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
        $result = $this->rule->setIntervenant(null)->execute();
        $this->assertArrayHasKey($id = $this->ie->getId(), $result);
        $this->assertEquals(['id' => $id], $result[$id]);
        $this->assertArrayNotHasKey($id = $this->ip->getId(), $result);
        $this->assertNotContains(['id' => $id], $result);
        $this->assertNull($this->rule->getMessage());
        
        /**
         * - Intervenant spécifié : IP (ne peut pas saisir de données perso de par son statut)
         * => Le résultat doit être vide
         */
        $result = $this->rule->setIntervenant($this->ip)->execute();
        $this->assertEquals([], $result);
        $this->assertNotNull($this->rule->getMessage());
        
        // un intervenant extérieur, oui
        /**
         * - Intervenant spécifié : IE (peut saisir des données perso de par son statut)
         * => Le résultat ne doit contenir que l'IE
         */
        $result = $this->rule->setIntervenant($this->ie)->execute();
        $this->assertEquals([$id = $this->ie->getId() => ['id' => $id]], $result);
        $this->assertNull($this->rule->getMessage());
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