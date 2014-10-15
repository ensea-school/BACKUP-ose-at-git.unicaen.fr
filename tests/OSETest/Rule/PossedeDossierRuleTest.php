<?php

namespace OSETest\Rule;

use Application\Entity\Db\IntervenantExterieur;
use Application\Entity\Db\IntervenantPermanent;
use Application\Rule\Intervenant\PossedeDossierRule;

/**
 * Test fonctionnel de la règle métier.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PossedeDossierRuleTest extends BaseRuleTest
{
    /**
     * @var PossedeDossierRule 
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
     * @var IntervenantExterieur 
     */
    protected $ieAvecDossier;
    
    /**
     * @return string
     */
    protected function getRuleName()
    {
        return 'PossedeDossierRule';
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
        
        $dossier = $this->getEntityProvider()->getDossier(); // NB: doit être instancié avant l'intervenant!
        $this->ieAvecDossier = $this->getEntityProvider()->getIntervenantExterieur()->setDossier($dossier);
        
        $this->getEntityManager()->flush();
    }
    
    public function testDataset()
    {
        $this->assertFalse($this->ip->getStatut()->getPeutSaisirDossier());
        $this->assertTrue($this->ie->getStatut()->getPeutSaisirDossier());
    }
    
    public function testIsRelevant()
    {
        $this->rule->setIntervenant(null);
        static::assertTrue($this->rule->isRelevant());
        
        $this->rule->setIntervenant($this->ie);
        static::assertTrue($this->rule->isRelevant());
        
        $this->rule->setIntervenant($this->ip);
        static::assertFalse($this->rule->isRelevant());
    }
    
    /**
     * @depends testDataset
     */
    public function testExecute()
    {
        /**
         * - Intervenant spécifié : aucun
         * - Un IE existe mais n'a pas de données perso 
         * - Un IP existe
         * => L'IE n'est pas dans le résultat, encore moins l'IP
         */
        $result = $this->rule->setIntervenant(null)->execute();
        $this->assertArrayNotHasKey($id = $this->ie->getId(), $result);
        $this->assertNotContains(['id' => $id], $result);
        $this->assertArrayNotHasKey($id = $this->ip->getId(), $result);
        $this->assertNotContains(['id' => $id], $result);
        
        /**
         * - Intervenant spécifié : aucun
         * - Un IE ayant des données perso existe 
         * - Un IP existe
         * => l'IE est dans le résultat, pas l'IP
         */
        $result = $this->rule->setIntervenant(null)->execute();
        $this->assertArrayHasKey($id = $this->ieAvecDossier->getId(), $result);
        $this->assertEquals(['id' => $id], $result[$id]);
        $this->assertArrayNotHasKey($id = $this->ip->getId(), $result);
        $this->assertNotContains(['id' => $id], $result);
        $this->assertNull($this->rule->getMessage());
        
        /**
         * - Intervenant spécifié : IE sans données perso
         * - Un IP existe
         * => Le résultat est vide
         */
        $result = $this->rule->setIntervenant($this->ie)->execute();
        $this->assertEquals([], $result);
        $this->assertNotNull($this->rule->getMessage());
        
        /**
         * - Intervenant spécifié : IE avec données perso
         * - Un IP existe
         * => Le résultat ne contient que l'IE
         */
        $result = $this->rule->setIntervenant($this->ieAvecDossier)->execute();
        $this->assertEquals([$id = $this->ieAvecDossier->getId() => ['id' => $id]], $result);
        $this->assertNull($this->rule->getMessage());
    }
    
    protected function tearDown()
    {
        parent::tearDown();
        
        /**
         * Suppression du jeu d'essai
         */
        $this->getEntityManager()->remove($this->ie);
        $this->getEntityManager()->remove($this->ieAvecDossier);
        $this->getEntityManager()->remove($this->ip);
        $this->getEntityManager()->flush();
        $this->getEntityProvider()->removeNewEntities();
    }
}