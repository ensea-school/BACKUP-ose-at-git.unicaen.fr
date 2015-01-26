<?php

namespace OSETest\Rule;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\StatutIntervenant;
use Application\Rule\Intervenant\PeutSaisirReferentielRule;

/**
 * Test fonctionnel de la règle métier.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PeutSaisirReferentielSansStructureRuleTest extends BaseRuleTest
{
    /**
     * @var PeutSaisirReferentielRule 
     */
    protected $rule;
    
    /**
     * @var Intervenant
     */
    private $ipAvec;
    
    /**
     * @var Intervenant
     */
    private $ipSans;
    
    /**
     * @return string
     */
    protected function getRuleName()
    {
        return 'PeutSaisirReferentielRule';
    }
    
    /**
     * 
     */
    protected function setUp()
    {
        parent::setUp();
        
        $this->rule
                ->setIntervenant(null)
                ->setStructure(null);
        
        /**
         * Création du jeu d'essai
         */
        $this->ipAvec = $this->getEntityProvider()->getIntervenantPermanent()->setStatut($this->getStatutBySaisieReferentiel(true));
        $this->ipSans = $this->getEntityProvider()->getIntervenantExterieur()->setStatut($this->getStatutBySaisieReferentiel(false));
        $this->getEntityManager()->flush();
    }
    
    public function testIsRelevant()
    {
        $this->assertTrue($this->rule->isRelevant());
    }
    
    public function testExecute()
    {
        /**
         * - Intervenant spécifié : aucun
         * - Un IP pouvant saisir du référentiel existe
         * - Un IP ne pouvant pas saisir de référentiel existe
         * ---> L'IP pouvant saisir du référentiel doit être dans le résultat
         */
        $result = $this->rule->setIntervenant(null)->execute();
        $this->assertArrayHasKey($id = $this->ipAvec->getId(), $result);
        $this->assertEquals(['id' => $id], $result[$id]);
        $this->assertArrayNotHasKey($id = $this->ipSans->getId(), $result);
        $this->assertNotContains(['id' => $id], $result);
        $this->assertNull($this->rule->getMessage());
        
        /**
         * - Intervenant spécifié : IP pouvant saisir du réf
         * ---> Le résultat doit contenir uniquement cet IP
         */
        $result = $this->rule->setIntervenant($this->ipAvec)->execute();
        $this->assertEquals([$id = $this->ipAvec->getId() => ['id' => $id]], $result);
        $this->assertNull($this->rule->getMessage());
        
        /**
         * - Intervenant spécifié : IP ne pouvant pas saisir du réf
         * ---> Le résultat doit être vide
         */
        $result = $this->rule->setIntervenant($this->ipSans)->execute();
        $this->assertEquals([], $result);
        $this->assertNotNull($this->rule->getMessage());
    }
    
    protected function tearDown()
    {
        parent::tearDown();
        
        /**
         * Suppression du jeu d'essai
         */
        $this->getEntityManager()->remove($this->ipAvec);
        $this->getEntityManager()->remove($this->ipSans);
        $this->getEntityManager()->flush();
        $this->getEntityProvider()->removeNewEntities();
    }
    
    /**
     * @return StatutIntervenant
     */
    protected function getStatutBySaisieReferentiel($peut = true)
    {
        return $this->getEntityManager()->getRepository('Application\Entity\Db\StatutIntervenant')->findOneByPeutSaisirReferentiel($peut);
    }
}