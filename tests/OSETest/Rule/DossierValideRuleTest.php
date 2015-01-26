<?php

namespace OSETest\Rule;

use Application\Entity\Db\IntervenantExterieur;
use Application\Entity\Db\IntervenantPermanent;
use Application\Entity\Db\TypeValidation;
use Application\Rule\Intervenant\DossierValideRule;
use Common\Exception\LogicException;

/**
 * Test fonctionnel de la règle métier.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class DossierValideRuleTest extends BaseRuleTest
{
    /**
     * @var DossierValideRule 
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
     * @var TypeValidation 
     */
    protected $typeValidation;
    
    /**
     * @return string
     */
    protected function getRuleName()
    {
        return 'DossierValideRule';
    }
    
    /**
     * 
     */
    protected function setUp()
    {
        parent::setUp();
        
        $this->typeValidation = $this->getEntityProvider()->getTypeValidationByCode(TypeValidation::CODE_DONNEES_PERSO_PAR_COMP);
        $this->rule->setTypeValidation($this->typeValidation);
        
        /**
         * Création du jeu d'essai
         */
        $this->ip = $this->getEntityProvider()->getIntervenantPermanent();
        $this->ie = $this->getEntityProvider()->getIntervenantExterieur();
        
        $dossier = $this->getEntityProvider()->getDossier(); // NB: doit être instancié avant l'intervenant!
        $this->ieAvecDossier = $this->getEntityProvider()->getIntervenantExterieur()->setDossier($dossier);
        
        $this->getEntityManager()->flush();
    }
    
    public function testIsRelevant()
    {
        $this->rule->setIntervenant(null);
        static::assertTrue($this->rule->isRelevant());
        
        $this->rule->setIntervenant($this->ip);
        static::assertFalse($this->rule->isRelevant());
        
        $this->rule->setIntervenant($this->ie);
        static::assertFalse($this->rule->isRelevant());
        
        $this->rule->setIntervenant($this->ieAvecDossier);
        static::assertTrue($this->rule->isRelevant());
    }
    
    /**
     * @expectedException LogicException
     */
    public function testExecuteThrowsExceptionWhenNoTypeValidationSpecified()
    {
        $this->rule->setTypeValidation(null);
        $this->rule->execute();
    }
    
    public function testExecute()
    {
        /**
         * aucune données perso validées
         */
        $this->rule->setIntervenant(null);
        $this->assertIntervenantNotInResult($this->ieAvecDossier);
        $this->assertIntervenantNotInResult($this->ie);
        $this->assertIntervenantNotInResult($this->ip);
        
        $this->rule->setIntervenant($this->ieAvecDossier);
        $this->assertIntervenantNotInResult($this->ieAvecDossier);
        
        $this->rule->setIntervenant($this->ie);
        $this->assertIntervenantNotInResult($this->ie);
        
        $this->rule->setIntervenant($this->ip);
        $this->assertIntervenantNotInResult($this->ip);
        
        /**
         * mauvais type de validation des données perso de l'IE 
         */
        $autreTypeValidation = $this->getEntityProvider()->getTypeValidationByCode(TypeValidation::CODE_SERVICES_PAR_COMP);
        $validation = $this->getEntityProvider()->getValidation($autreTypeValidation, $this->ie);
        $this->getEntityManager()->flush($validation);
        
        $this->rule->setIntervenant(null);
        $this->assertIntervenantNotInResult($this->ie);
        
        $this->rule->setIntervenant($this->ie);
        $this->assertIntervenantNotInResult($this->ie);
        
        /**
         * validation des données perso de l'IE SANS données perso (c'est bizarre mais pas interdit!)
         */
        $validation = $this->getEntityProvider()->getValidation($this->typeValidation, $this->ie);
        $this->getEntityManager()->flush($validation);
        
        $this->rule->setIntervenant(null);
        $this->assertIntervenantInResult($this->ie);
        
        $this->rule->setIntervenant($this->ie);
        $this->assertIntervenantInResult($this->ie);
        
        /**
         * validation des données perso de l'IE AVEC données perso
         */
        $validation = $this->getEntityProvider()->getValidation($this->typeValidation, $this->ieAvecDossier);
        $this->getEntityManager()->flush($validation);
        
        $this->rule->setIntervenant(null);
        $this->assertIntervenantInResult($this->ieAvecDossier);
        
        $this->rule->setIntervenant($this->ieAvecDossier);
        $this->assertIntervenantInResult($this->ieAvecDossier);
        
        /**
         * validation des données perso de l'IP qui ne peux pas avoir de données perso (c'est bizarre mais pas interdit!)
         */
        $validation = $this->getEntityProvider()->getValidation($this->typeValidation, $this->ip);
        $this->getEntityManager()->flush($validation);
        
        $this->rule->setIntervenant(null);
        $this->assertIntervenantInResult($this->ip);
        
        $this->rule->setIntervenant($this->ip);
        $this->assertIntervenantInResult($this->ip);
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