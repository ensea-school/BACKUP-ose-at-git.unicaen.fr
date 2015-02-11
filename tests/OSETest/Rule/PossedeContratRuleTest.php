<?php

namespace OSETest\Rule;

use Application\Entity\Db\Contrat;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\IntervenantExterieur;
use Application\Entity\Db\IntervenantPermanent;
use Application\Entity\Db\Structure;
use Application\Entity\Db\TypeContrat;
use Application\Entity\Db\TypeValidation;
use Application\Rule\Intervenant\PossedeContratRule;

/**
 * Test fonctionnel de la règle métier.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PossedeContratRuleTest extends BaseRuleTest
{
    /**
     * @var PossedeContratRule 
     */
    protected $rule;
    
    /**
     * @var IntervenantExterieur 
     */
    protected $ie;
    
    /**
     * @return string
     */
    protected function getRuleName()
    {
        return 'PossedeContratRule';
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
        $this->ie = $this->getEntityProvider()->getIntervenantExterieur();
        $this->getEntityManager()->flush();
    }
    
    public function testDataset()
    {
        $this->assertTrue($this->ie->getStatut()->getPeutSaisirDossier());
    }
    
    public function testIsRelevant()
    {
        $this->rule->setIntervenant(null);
        static::assertTrue($this->rule->isRelevant());
        
        $this->rule->setIntervenant($this->ie);
        static::assertTrue($this->rule->isRelevant());
        
        $this->rule->setIntervenant(new IntervenantPermanent());
        static::assertFalse($this->rule->isRelevant());
    }
    
    /**
     * @depends testDataset
     */
    public function testExecuteAvecOuSansStructure()
    {
        $typeContrat = $this->getEntityProvider()->getTypeContrat(false);
//        $typeAvenant = $this->getEntityProvider()->getTypeContrat(true);
        $structure = $this->getEntityProvider()->getStructure();
           
        $this->rule->setTypeContrat($typeContrat);
        
        /**
         * - Intervenant spécifié : aucun
         * - Un IE existe mais n'a pas de contrat
         * - Aucune structure précise transmise à la règle
         * => L'IE n'est pas dans le résultat
         */
        $this->rule->setIntervenant(null);
        $this->rule->setStructure(null);
        $this->assertIntervenantNotInResult($this->ie);
        
        /**
         * - Intervenant spécifié : IE sans contrat
         * => Le résultat est vide
         */
        $this->rule->setIntervenant($this->ie);
        $this->rule->setStructure(null);
        $this->assertIntervenantNotInResult($this->ie);
        
        /**
         * - Intervenant spécifié : IE sans contrat
         * - Une structure précise transmise à la règle
         * => Le résultat est vide
         */
        $this->rule->setIntervenant($this->ie);
        $this->rule->setStructure($this->getEntityProvider()->getStructureEns());
        $this->assertIntervenantNotInResult($this->ie);
        
        /**
         * - L'IE a un contrat
         * - Structure précise transmise à la règle != structure du contrat
         * => l'IE n'est pas dans le résultat
         */
        $contrat = $this->addContratToIntervenant($this->ie, $typeContrat, $structure);
        
        $this->rule->setIntervenant(null);
        $this->rule->setStructure($this->getEntityProvider()->getStructureEns());
        $this->assertIntervenantNotInResult($this->ie);
        
        /**
         * - Structure précise transmise à la règle = structure du contrat
         * => l'IE est dans le résultat
         */
        $this->rule->setIntervenant(null);
        $this->rule->setStructure($contrat->getStructure());
        $this->assertIntervenantInResult($this->ie);
        
        /**
         * - Intervenant spécifié : IE avec contrat
         * - Structure précise transmise à la règle != structure du contrat
         * => Le résultat est vide
         */
        $this->rule->setIntervenant($this->ie);
        $this->rule->setStructure($this->getEntityProvider()->getStructureEns());
        $this->assertIntervenantNotInResult($this->ie);
        
        /**
         * - Structure précise transmise à la règle = structure du contrat
         * => l'IE est dans le résultat
         */
        $this->rule->setIntervenant($this->ie);
        $this->rule->setStructure($contrat->getStructure());
        $this->assertIntervenantInResult($this->ie);
    }
    
    /**
     * @depends testDataset
     */
    public function testExecuteAvecTemoinValidite()
    {
        $typeContrat    = $this->getEntityProvider()->getTypeContrat(false);
        $structure      = $this->getEntityProvider()->getStructure();
        $typeValidation = $this->getEntityProvider()->getTypeValidationByCode(TypeValidation::CODE_CONTRAT);

        $this->rule->setTypeContrat($typeContrat);
        $this->rule->setStructure(null);
        
        /**
         * - L'IE a un contrat NON validé
         * - Aucun critère de validation transmis à la règle
         * => l'IE est dans le résultat
         */
        $contrat = $this->addContratToIntervenant($this->ie, $typeContrat, $structure);
        
        $this->rule->setValide(null);
        
        $this->rule->setIntervenant(null);
        $this->assertIntervenantInResult($this->ie);
        
        $this->rule->setIntervenant($this->ie);
        $this->assertIntervenantInResult($this->ie);
        
        /**
         * - Critère de validation transmis à la règle : contrat/avenant NON validé
         * => l'IE est dans le résultat
         */
        $this->rule->setValide(false);
        
        $this->rule->setIntervenant(null);
        $this->assertIntervenantInResult($this->ie);
        
        $this->rule->setIntervenant($this->ie);
        $this->assertIntervenantInResult($this->ie);
        
        /**
         * - Critère de validation transmis à la règle : contrat/avenant validé
         * => l'IE n'est pas dans le résultat
         */
        $this->rule->setValide(true);
        
        $this->rule->setIntervenant(null);
        $this->assertIntervenantNotInResult($this->ie);
        
        $this->rule->setIntervenant($this->ie);
        $this->assertIntervenantNotInResult($this->ie);
        
        /**
         * - L'IE a un contrat validé
         * - Aucun critère de validation transmis à la règle
         * => l'IE est dans le résultat
         */
        $contrat->setValidation($this->getEntityProvider()->getValidation($typeValidation, $this->ie));
        $this->getEntityManager()->flush();
        
        $this->rule->setValide(null);
        
        $this->rule->setIntervenant(null);
        $this->assertIntervenantInResult($this->ie);
        
        $this->rule->setIntervenant($this->ie);
        $this->assertIntervenantInResult($this->ie);
        
        /**
         * - Critère de validation transmis à la règle : contrat/avenant NON validé
         * => l'IE n'est pas dans le résultat
         */
        $this->rule->setValide(false);
        
        $this->rule->setIntervenant(null);
        $this->assertIntervenantNotInResult($this->ie);
        
        $this->rule->setIntervenant($this->ie);
        $this->assertIntervenantNotInResult($this->ie);
        
        /**
         * - Critère de validation transmis à la règle : contrat/avenant validé
         * => l'IE est dans le résultat
         */
        $this->rule->setValide(true);
        
        $this->rule->setIntervenant(null);
        $this->assertIntervenantInResult($this->ie);
        
        $this->rule->setIntervenant($this->ie);
        $this->assertIntervenantInResult($this->ie);
    }
    
    /**
     * @depends testDataset
     */
    public function testExecuteMauvaisTypeContrat()
    {
        $typeContrat        = $this->getEntityProvider()->getTypeContrat(false);
        $mauvaisTypeContrat = $this->getEntityProvider()->getTypeContrat(true);
        $structure          = $this->getEntityProvider()->getStructure();

        $this->rule->setStructure(null);
        
        /**
         * - Type de contrat transmis à la règle != type de contrat de l'intervenant
         */
        $this->rule->setTypeContrat($mauvaisTypeContrat);
        
        /**
         * - L'IE a un contrat mais pas d'avenant
         * => l'IE n'est pas dans le résultat
         */
        $this->addContratToIntervenant($this->ie, $typeContrat, $structure);
        
        $this->rule->setIntervenant(null);
        $this->assertIntervenantNotInResult($this->ie);
        
        /**
         * - Intervenant spécifié : IE avec contrat mais sans avenant
         * => Le résultat est vide
         */
        $this->rule->setIntervenant($this->ie);
        $this->assertIntervenantNotInResult($this->ie);
    }

    /**
     * 
     * @param Intervenant $intervenant
     * @param TypeContrat $typeContrat
     * @param Structure $structure
     * @return Contrat
     */
    private function addContratToIntervenant(Intervenant $intervenant, TypeContrat $typeContrat, Structure $structure = null)
    {
        $contrat = $this->getEntityProvider()->getContrat($typeContrat, $intervenant, $structure);
        
        $this->getEntityManager()->flush($contrat);
        
        return $contrat;
    }
    
    protected function tearDown()
    {
        parent::tearDown();
        
        /**
         * Suppression du jeu d'essai
         */
        $this->getEntityManager()->remove($this->ie);
        $this->getEntityManager()->flush();
        $this->getEntityProvider()->removeNewEntities();
    }
}