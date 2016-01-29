<?php

namespace OSETest\Rule;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Structure;

/**
 * Test fonctionnel de la règle métier.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PeutSaisirReferentielAvecStructureRuleTest extends BaseRuleTest
{
    /**
     * @var PeutSaisirReferentielRule 
     */
    protected $rule;
    
    /**
     * @var Intervenant
     */
    private $ipAffNiv2;
    
    /**
     * @var Intervenant
     */
    private $ipAffNiv3;
    
    /**
     * @var Structure
     */
    private $structureNiv2;
    
    /**
     * @var Structure
     */
    private $structureNiv3;
    
    /**
     * @var Structure
     */
    private $structureAutre;
    
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
        $this->structureNiv2  = $this->getEntityProvider()->getStructure();
        $this->structureNiv3  = $this->getEntityProvider()->getStructure()->setNiveau(3)->setParente($this->structureNiv2)->setParenteNiv2($this->structureNiv2);
        $this->structureAutre = $this->getEntityProvider()->getStructure();

        $statut = $this->getStatutBySaisieReferentiel(true);
        $this->ipAffNiv2 = $this->getEntityProvider()->getIntervenantPermanent()
                ->setStatut($statut)
                ->setStructure($this->structureNiv2);
        $this->ipAffNiv3 = $this->getEntityProvider()->getIntervenantPermanent()
                ->setStatut($statut)
                ->setStructure($this->structureNiv3);

        $this->getEntityManager()->flush();
    }
    
    public function testIsRelevant()
    {
        $this->assertTrue($this->rule->isRelevant());
    }
    
    /**
     * @expectedException \LogicException
     */
    public function testExceptionSiNiveauStructureIncorrect()
    {
        $this->rule->setStructure($this->structureNiv3);
    }
    
    public function testExecute()
    {
        /**
         * - Intervenant-filtre spécifié : aucun
         * - Structure-filtre spécifiée : structure de niveau 2 identique à l'affectation de l'IP
         * - Existe : un IP affecté à une structure de niveau 2 
         * - Existe : un IP affecté à une structure fille de la structure d'affectation de l'autre IP
         * ---> Les 2 IP doivent être dans le résultat
         */
        $result = $this->rule
                ->setIntervenant(null)
                ->setStructure($this->structureNiv2)
                ->execute();
        $this->assertArrayHasKey($id = $this->ipAffNiv2->getId(), $result);
        $this->assertEquals(['id' => $id], $result[$id]);
        $this->assertArrayHasKey($id = $this->ipAffNiv3->getId(), $result);
        $this->assertEquals(['id' => $id], $result[$id]);
        $this->assertNull($this->rule->getMessage());
        
        /**
         * - Intervenant-filtre spécifié : aucun
         * - Structure-filtre spécifiée : structure de niveau 2 différente de l'affectation de l'IP
         * - Existe : un IP affecté à une structure de niveau 2 
         * - Existe : un IP affecté à une structure fille de la Structure-filtre
         * ---> Aucun des 2 IP ne doivent être dans le résultat
         */
        $result = $this->rule
                ->setIntervenant(null)
                ->setStructure($this->structureAutre)
                ->execute();
        $this->assertArrayNotHasKey($id = $this->ipAffNiv2->getId(), $result);
        $this->assertNotContains(['id' => $id], $result);
        $this->assertArrayNotHasKey($id = $this->ipAffNiv3->getId(), $result);
        $this->assertNotContains(['id' => $id], $result);
        $this->assertNull($this->rule->getMessage());
        
        /**
         * - Intervenant-filtre spécifié : IP affecté à une structure de niveau 3 
         * - Structure-filtre spécifiée : structure de niveau 2 mère de la structure d'affectation de l'IP
         * - Existe : l'IP spécifié
         * ---> Seul l'Intervenant-filtre spécifié doit être dans le résultat
         */
        $result = $this->rule
                ->setIntervenant($this->ipAffNiv3)
                ->setStructure($this->structureNiv2)
                ->execute();
        $this->assertEquals([$id = $this->ipAffNiv3->getId() => ['id' => $id]], $result);
        $this->assertNull($this->rule->getMessage());
    }
    
    protected function tearDown()
    {
        parent::tearDown();
        
        /**
         * Suppression du jeu d'essai
         */
        // L'intervenant doit être supprimé avant sa structure d'affectation pour ne pas rencontrer l'erreur
        // ORA-02292: integrity constraint (OSE.INTERVENANT_STRUCTURE_FK) violated - child record found
        $this->getEntityManager()->remove($this->ipAffNiv2);
        $this->getEntityManager()->remove($this->ipAffNiv3);
        $this->getEntityManager()->flush();
        $this->getEntityProvider()->removeNewEntities();
    }
    
    /**
     * @return StatutIntervenant
     */
    protected function getStatutBySaisieReferentiel($peut = true)
    {
        $si = $this->getEntityManager()->getRepository('Application\Entity\Db\StatutIntervenant')->findOneByPeutSaisirReferentiel($peut);
        if (!$si) {
            throw new \RuntimeException("Aucun statut intervenant adéquat trouvé.");
        }
        
        return $si;
    }
}