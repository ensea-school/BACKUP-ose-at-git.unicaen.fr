<?php

namespace OSETest\Rule;

use Application\Entity\Db\IntervenantExterieur;
use Application\Entity\Db\IntervenantPermanent;
use Application\Entity\Db\StatutIntervenant;
use Application\Rule\Intervenant\PeutSaisirServiceRule;

/**
 * Test fonctionnel de la règle métier.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PeutSaisirServiceRuleTest extends BaseRuleTest
{
    /**
     * @var PeutSaisirServiceRule 
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
    protected $ieSansSaisieService;
    
    /**
     * @return string
     */
    protected function getRuleName()
    {
        return 'PeutSaisirServiceRule';
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
        $this->ip                  = $this->getEntityProvider()->getIntervenantPermanent();
        $this->ie                  = $this->getEntityProvider()->getIntervenantExterieur();
        $this->ieSansSaisieService = $this->getEntityProvider()->getIntervenantExterieur()->setStatut($this->getStatutSansSaisieService());
        $this->getEntityManager()->flush();
    }
    
    /**
     * 
     * @return StatutIntervenant
     */
    protected function getStatutSansSaisieService ()
    {
        return $this->getEntityManager()->getRepository(get_class($this->ip->getStatut()))->findOneByPeutSaisirService(0);
    }
    
    public function testDataset()
    {
        $this->assertTrue($this->ip->getStatut()->getPeutSaisirService());
        $this->assertTrue($this->ie->getStatut()->getPeutSaisirService());
        $this->assertFalse($this->ieSansSaisieService->getStatut()->getPeutSaisirService());
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
         * - Un IE pouvant saisir des services existe
         * - Un IE ne pouvant pas saisir de service existe
         * - Un IP existe (pouvant obligatoirement saisir des services)
         * ---> L'IE et l'IP doivent être dans le résultat
         */
        $result = $this->rule->setIntervenant(null)->execute();
        $this->assertArrayHasKey($id = $this->ie->getId(), $result);
        $this->assertEquals(['id' => $id], $result[$id]);
        $this->assertArrayHasKey($id = $this->ip->getId(), $result);
        $this->assertEquals(['id' => $id], $result[$id]);
        $this->assertArrayNotHasKey($id = $this->ieSansSaisieService->getId(), $result);
        $this->assertNotContains(['id' => $id], $result);
        $this->assertNull($this->rule->getMessage());
        
        /**
         * - Intervenant spécifié : IE pouvant saisir des services
         * ---> Le résultat doit contenir uniquement l'IE
         */
        $result = $this->rule->setIntervenant($this->ie)->execute();
        $this->assertEquals([$id = $this->ie->getId() => ['id' => $id]], $result);
        $this->assertNull($this->rule->getMessage());
        
        /**
         * - Intervenant spécifié : IE ne pouvant pas saisir de service
         * ---> Le résultat doit être vide
         */
        $result = $this->rule->setIntervenant($this->ieSansSaisieService)->execute();
        $this->assertEquals([], $result);
        $this->assertNotNull($this->rule->getMessage());
        
        /**
         * - Intervenant spécifié : IP
         * ---> Le résultat doit contenir uniquement l'IP
         */
        $result = $this->rule->setIntervenant($this->ip)->execute();
        $this->assertEquals([$id = $this->ip->getId() => ['id' => $id]], $result);
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
        $this->getEntityManager()->remove($this->ieSansSaisieService);
        $this->getEntityManager()->flush();
        $this->getEntityProvider()->removeNewEntities();
    }
}