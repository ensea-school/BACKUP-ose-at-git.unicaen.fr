<?php

namespace OSETest\Rule;

use Application\Entity\Db\IntervenantExterieur;
use Application\Entity\Db\IntervenantPermanent;
use Application\Entity\Db\TypeValidation;
use Application\Rule\Intervenant\ServiceValideRule;
use LogicException;

/**
 * Test fonctionnel de la règle métier.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ServiceValideRuleTest extends BaseRuleTest
{
    /**
     * @var ServiceValideRule 
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
     * @var TypeValidation 
     */
    protected $typeValidation;
    
    /**
     * @return string
     */
    protected function getRuleName()
    {
        return 'ServiceValideRule';
    }
    
    /**
     * 
     */
    protected function setUp()
    {
        parent::setUp();
        
        $this->typeValidation = $this->getEntityProvider()->getTypeValidationByCode(TypeValidation::CODE_ENSEIGNEMENT);
        $this->rule->setTypeValidation($this->typeValidation);
        
        /**
         * Création du jeu d'essai
         */
        $this->ip = $this->getEntityProvider()->getIntervenantPermanent();
        $this->ie = $this->getEntityProvider()->getIntervenantExterieur();
        
        $this->getEntityManager()->flush();
    }
    
    public function testIsRelevant()
    {
        $this->rule->setIntervenant(null);
        static::assertTrue($this->rule->isRelevant());
        
        $this->rule->setIntervenant($this->ip);
        static::assertTrue($this->rule->isRelevant());
        
        // intervenant dont le statut NE permet PAS la saisie de service
        $this->ie->getStatut()->setPeutSaisirService(false);
        $this->getEntityManager()->flush($this->ie->getStatut());
        $this->rule->setIntervenant($this->ie);
        static::assertFalse($this->rule->isRelevant());
        
        // intervenant dont le statut permet la saisie de service
        $this->ie->getStatut()->setPeutSaisirService(true);
        $this->getEntityManager()->flush($this->ie->getStatut());
        $this->rule->setIntervenant($this->ie);
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
    
    public function testExecuteAvecAucunServiceOuServiceNonValide()
    {
        /** 
         * aucun service
         */
        $this->rule->setIntervenant(null);
        $this->assertIntervenantNotInResult($this->ie);
        
        $this->rule->setIntervenant($this->ie);
        $this->assertIntervenantNotInResult($this->ie);
        
        /**
         * service sans volume horaire (possible!)
         */
        $service = $this->setServiceIntervenant($this->ie);
        
        $this->rule->setIntervenant(null);
        $this->assertIntervenantNotInResult($this->ie);
        
        $this->rule->setIntervenant($this->ie);
        $this->assertIntervenantNotInResult($this->ie);
        
        /**
         * service avec 2 VH non validés
         */
        $this->setServiceIntervenant($this->ie, ['CM' => 15, 'TP' => 10], $service);
        
        $this->rule->setIntervenant(null);
        $this->assertIntervenantNotInResult($this->ie);
        
        $this->rule->setIntervenant($this->ie);
        $this->assertIntervenantNotInResult($this->ie);
    }
    
    public function testExecuteAvecTypeValidationIncorrect()
    {
        /**
         * service avec 2 VH
         */
        $service = $this->setServiceIntervenant($this->ie, ['CM' => 15, 'TP' => 10]);
        
        /**
         * validation incorrecte d'1 VH sur 2
         */
        $autreTypeValidation = $this->getEntityProvider()->getTypeValidationByCode(TypeValidation::CODE_DONNEES_PERSO);
        $autreValidation = $this->getEntityProvider()->getValidation($autreTypeValidation, $this->ie);
        $vh1 = $service->getVolumeHoraire()->first();
        $vh1->addValidation($autreValidation); 
        // NB: $autreValidation->addVolumeHoraire($vh) est sans doute inutile car provoque une erreur
        // ORA-00001: unique constraint (OSE.VALIDATION_VOL_HORAIRE_PK) violated
        $this->getEntityManager()->flush($vh1);
        
        /**
         * validation partielle autorisée (NB: ce critère n'est pas pris en compte par la règle lorsqu'aucun intervenant n'est spécifié)
         */
        $this->rule->setMemePartiellement(true);
        
        $this->rule->setIntervenant(null);
        $this->assertIntervenantNotInResult($this->ie);
        
        $this->rule->setIntervenant($this->ie);
        $this->assertIntervenantNotInResult($this->ie);
        
        /**
         * validation partielle interdite (NB: ce critère n'est pas pris en compte par la règle lorsqu'aucun intervenant n'est spécifié)
         */
        $this->rule->setMemePartiellement(false);
        
        $this->rule->setIntervenant(null);
        $this->assertIntervenantNotInResult($this->ie);
        
        $this->rule->setIntervenant($this->ie);
        $this->assertIntervenantNotInResult($this->ie);
        
        /**
         * suppression validation indispensable pour pouvoir supprimer les VH
         */
        $vh1->removeValidation($autreValidation);
        $this->getEntityManager()->flush($vh1);
        $this->getEntityManager()->remove($autreValidation);
        $this->getEntityManager()->flush($autreValidation);
    } 
    
    public function testExecuteAvecValidationSansStructure()
    { 
        /**
         * service avec 2 VH
         */
        $service = $this->setServiceIntervenant($this->ie, ['CM' => 15, 'TP' => 10]);
        
        /***************************************************************************/
        
        /**
         * validation d'1 VH sur 2
         */
        $validation = $this->getEntityProvider()->getValidation($this->typeValidation, $this->ie);
        $this->getEntityManager()->flush($validation);
        $vh1 = $service->getVolumeHoraire()->first();
        $vh1->addValidation($validation);
        // NB: $validation->addVolumeHoraire($vh) est sans doute inutile car provoque une erreur
        // ORA-00001: unique constraint (OSE.VALIDATION_VOL_HORAIRE_PK) violated
        $this->getEntityManager()->flush($vh1);
        
        /**
         * validation partielle autorisée (NB: ce critère n'est pas pris en compte par la règle lorsqu'aucun intervenant n'est spécifié)
         */
        $this->rule->setMemePartiellement(true);
        
        $this->rule->setIntervenant(null);
        $this->assertIntervenantInResult($this->ie);
        
        $this->rule->setIntervenant($this->ie);
        $this->assertIntervenantInResult($this->ie);
        
        /**
         * validation partielle interdite (NB: ce critère n'est pas pris en compte par la règle lorsqu'aucun intervenant n'est spécifié)
         */
        $this->rule->setMemePartiellement(false);
        
        $this->rule->setIntervenant(null);
        $this->assertIntervenantInResult($this->ie);
        
        $this->rule->setIntervenant($this->ie);
        $this->assertIntervenantNotInResult($this->ie);
        
        // suppression validation indispensable pour pouvoir supprimer les VH
        $vh1->removeValidation($validation);
        $this->getEntityManager()->flush($vh1);
        $this->getEntityManager()->remove($validation);
        $this->getEntityManager()->flush($validation);
        
        /***************************************************************************/
        
        /**
         * validation des 2 VH
         */
        $validation = $this->getEntityProvider()->getValidation($this->typeValidation, $this->ie);
        $this->getEntityManager()->flush($validation);
        $vh1 = $service->getVolumeHoraire()->first();
        $vh1->addValidation($validation);
        $vh2 = $service->getVolumeHoraire()->next();
        $vh2->addValidation($validation);
        // NB: $validation->addVolumeHoraire($vh) est sans doute inutile car provoque une erreur
        // ORA-00001: unique constraint (OSE.VALIDATION_VOL_HORAIRE_PK) violated
        $this->getEntityManager()->flush($vh2);
        
        /**
         * validation partielle autorisée (NB: ce critère n'est pas pris en compte par la règle lorsqu'aucun intervenant n'est spécifié)
         */
        $this->rule->setMemePartiellement(true);
        
        $this->rule->setIntervenant(null);
        $this->assertIntervenantInResult($this->ie);
        
        $this->rule->setIntervenant($this->ie);
        $this->assertIntervenantInResult($this->ie);
        
        /**
         * validation partielle interdite (NB: ce critère n'est pas pris en compte par la règle lorsqu'aucun intervenant n'est spécifié)
         */
        $this->rule->setMemePartiellement(false);
        
        $this->rule->setIntervenant(null);
        $this->assertIntervenantInResult($this->ie);
        
        $this->rule->setIntervenant($this->ie);
        $this->assertIntervenantInResult($this->ie);
        
        /***************************************************************************/
        
        /**
         * suppression validation indispensable pour pouvoir supprimer les VH
         */
        $vh1->removeValidation($validation);
        $vh2->removeValidation($validation);
        $this->getEntityManager()->flush($vh1);
        $this->getEntityManager()->flush($vh2);
        $this->getEntityManager()->remove($validation);
        $this->getEntityManager()->flush($validation);
    } 
    
    public function testExecuteAvecValidationAvecStructure()
    { 
        /**
         * 2 services sur 2 composantes d'enseignement différentes
         */
        $structure1 = $this->getEntityProvider()->getStructure();
        $service1   = $this->setServiceIntervenant($this->ie, ['CM' => 15, 'TP' => 10])->setStructureEns($structure1);
        $this->getEntityManager()->flush($structure1);
        $this->getEntityManager()->flush($service1);
        
        $structure2 = $this->getEntityProvider()->getStructure();
        $service2   = $this->setServiceIntervenant($this->ie, ['CM' => 7, 'TD' => 13])->setStructureEns($structure2);
        $this->getEntityManager()->flush($structure2);
        $this->getEntityManager()->flush($service2);
        
        /***************************************************************************/
        
        /**
         * validation complète du service sur la composante d'enseignement 1
         */
        $validation = $this->getEntityProvider()->getValidation($this->typeValidation, $this->ie);
        $this->getEntityManager()->flush($validation);
        
        $vh1 = $service1->getVolumeHoraire()->first();
        $vh1->addValidation($validation);
        // NB: $validation->addVolumeHoraire($vh) est sans doute inutile car provoque une erreur
        // ORA-00001: unique constraint (OSE.VALIDATION_VOL_HORAIRE_PK) violated
        $this->getEntityManager()->flush($vh1);
        
        $vh2 = $service1->getVolumeHoraire()->next();
        $vh2->addValidation($validation);
        // NB: $validation->addVolumeHoraire($vh) est sans doute inutile car provoque une erreur
        // ORA-00001: unique constraint (OSE.VALIDATION_VOL_HORAIRE_PK) violated
        $this->getEntityManager()->flush($vh2);
        
        /**
         * - Validation partielle autorisée (NB: ce critère n'est pas pris en compte par la règle lorsqu'aucun intervenant n'est spécifié)
         * - Aucune structure spécifiée
         */
        $this->rule->setMemePartiellement(true);
        $this->rule->setStructure(null);
        
        $this->rule->setIntervenant(null);
        $this->assertIntervenantInResult($this->ie);
        
        $this->rule->setIntervenant($this->ie);
        $this->assertIntervenantInResult($this->ie);
        
        /**
         * - Validation partielle autorisée (NB: ce critère n'est pas pris en compte par la règle lorsqu'aucun intervenant n'est spécifié)
         * - Structure spécifiée = composante d'enseignement du service
         */
        $this->rule->setMemePartiellement(true);
        $this->rule->setStructure($structure1);
        
        $this->rule->setIntervenant(null);
        $this->assertIntervenantInResult($this->ie);
        
        $this->rule->setIntervenant($this->ie);
        $this->assertIntervenantInResult($this->ie);
        
        /**
         * - Validation partielle autorisée (NB: ce critère n'est pas pris en compte par la règle lorsqu'aucun intervenant n'est spécifié)
         * - Structure spécifiée != composante d'enseignement du service
         */
        $this->rule->setMemePartiellement(true);
        $this->rule->setStructure($structure2);
        
        $this->rule->setIntervenant(null);
        $this->assertIntervenantNotInResult($this->ie);
        
        $this->rule->setIntervenant($this->ie);
        $this->assertIntervenantNotInResult($this->ie);
        
        /**
         * - Validation partielle interdite (NB: ce critère n'est pas pris en compte par la règle lorsqu'aucun intervenant n'est spécifié)
         * - Aucune structure spécifiée
         */
        $this->rule->setMemePartiellement(false);
        $this->rule->setStructure(null);
        
        $this->rule->setIntervenant(null);
        $this->assertIntervenantInResult($this->ie);
        
        $this->rule->setIntervenant($this->ie);
        $this->assertIntervenantNotInResult($this->ie);
        
        /**
         * - Validation partielle interdite (NB: ce critère n'est pas pris en compte par la règle lorsqu'aucun intervenant n'est spécifié)
         * - Structure spécifiée = composante d'enseignement du service
         */
        $this->rule->setMemePartiellement(false);
        $this->rule->setStructure($structure1);
        
        $this->rule->setIntervenant(null);
        $this->assertIntervenantInResult($this->ie);
        
        $this->rule->setIntervenant($this->ie);
        $this->assertIntervenantInResult($this->ie);
        
        /**
         * - Validation partielle interdite (NB: ce critère n'est pas pris en compte par la règle lorsqu'aucun intervenant n'est spécifié)
         * - Structure spécifiée != composante d'enseignement du service
         */
        $this->rule->setMemePartiellement(false);
        $this->rule->setStructure($structure2);
        
        $this->rule->setIntervenant(null);
        $this->assertIntervenantNotInResult($this->ie);
        
        $this->rule->setIntervenant($this->ie);
        $this->assertIntervenantNotInResult($this->ie);
        
        // suppression validation indispensable pour pouvoir supprimer les VH
        $vh1->removeValidation($validation);
        $vh2->removeValidation($validation);
        $this->getEntityManager()->flush($vh1);
        $this->getEntityManager()->flush($vh2);
        $this->getEntityManager()->remove($validation);
        $this->getEntityManager()->flush($validation);
    }
    
    protected function tearDown()
    {
        parent::tearDown();
        
        /**
         * Suppression du jeu d'essai
         */
        $this->getEntityManager()->remove($this->ie);
        $this->getEntityManager()->remove($this->ip);
        $this->getEntityManager()->flush();
        $this->getEntityProvider()->removeNewEntities();
    }
}