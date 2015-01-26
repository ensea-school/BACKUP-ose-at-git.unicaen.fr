<?php

namespace OSETest\Rule;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\IntervenantExterieur;
use Application\Entity\Db\IntervenantPermanent;
use Application\Entity\Db\TypeAgrement;
use Application\Entity\Db\TypeAgrementStatut;
use Application\Entity\Db\Agrement;
use Application\Entity\Db\StatutIntervenant;
use Application\Rule\Intervenant\NecessiteAgrementRule;

/**
 * Test fonctionnel de la règle métier.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class NecessiteAgrementRuleTest extends BaseRuleTest
{
    /**
     * @var NecessiteAgrementRule 
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
     * @var TypeAgrement 
     */
    protected $typeAgrement;
    
    /**
     * @var StatutIntervenant 
     */
    protected $statut;
    
    /**
     * @return string
     */
    protected function getRuleName()
    {
        return 'NecessiteAgrementRule';
    }
    
    /**
     * 
     */
    protected function setUp()
    {
        parent::setUp();
        
        $this->typeAgrement = $this->getEntityProvider()->getTypeAgrementByCode(TypeAgrement::CODE_CONSEIL_RESTREINT);
        
        /**
         * Création du jeu d'essai
         */
        $this->ip = $this->getEntityProvider()->getIntervenantPermanent()->setStatut($this->getStatut());
        $this->getEntityManager()->flush($this->ip);
        
        $this->ie = $this->getEntityProvider()->getIntervenantExterieur()->setStatut($this->getStatut());
        $this->getEntityManager()->flush($this->ie);
    }
    
    public function testIsRelevant()
    {
        $this->rule->setIntervenant(null);
        static::assertTrue($this->rule->isRelevant());
        
        $this->rule->setIntervenant($this->ip);
        static::assertTrue($this->rule->isRelevant());
        
        $this->rule->setIntervenant($this->ie);
        static::assertTrue($this->rule->isRelevant());
    }
    
    /**
     * @return array
     */
    public function getSettings()
    {
        return [
            // agrément facultatif, 2e recrutement
            'facultatif_2eRecrut' => [
                'tasObligatoire'        => false,
                'tasPremierRecrutement' => false,
            ],
            // agrément facultatif, 1er recrutement
            'facultatif_1erRecrut' => [
                'tasObligatoire'        => false,
                'tasPremierRecrutement' => true,
            ],
            // agrément obligatoire, 2e recrutement
            'obligatoire_2eRecrut' => [
                'tasObligatoire'        => true,
                'tasPremierRecrutement' => false,
            ],
            // agrément obligatoire, 1er recrutement
            'obligatoire_1erRecrut' => [
                'tasObligatoire'        => true,
                'tasPremierRecrutement' => true,
            ],
        ];
    }

    public function testExecuteAucuneConfig()
    {
        $this->rule->setIntervenant(null);
        $this->assertIntervenantNotInResult($this->ie);
        $this->assertIntervenantNotInResult($this->ip);

        $this->rule->setIntervenant($this->ie);
        $this->assertIntervenantNotInResult($this->ie);

        $this->rule->setIntervenant($this->ip);
        $this->assertIntervenantNotInResult($this->ip);
    }

    /**
     * @dataProvider getSettings
     */
    public function testExecuteTypesAgrementDifferents($tasObligatoire, $tasPremierRecrutement)
    {
        $this->rule->setTypeAgrement($this->typeAgrement);
        
        // un type d'agrément attendu différent de celui spécifié dans la règle
        $autreTypeAgrement = $this->getEntityProvider()->getTypeAgrement();
        $this->getEntityManager()->flush($autreTypeAgrement);
        $this->createTAS($autreTypeAgrement, $tasObligatoire, $tasPremierRecrutement);
            
        $this->rule->setIntervenant(null);
        $this->assertIntervenantNotInResult($this->ie);
        $this->assertIntervenantNotInResult($this->ip);

        $this->rule->setIntervenant($this->ie);
        $this->assertIntervenantNotInResult($this->ie);

        $this->rule->setIntervenant($this->ip);
        $this->assertIntervenantNotInResult($this->ip);
    }

    /**
     * @dataProvider getSettings
     */
    public function testExecuteTypesAgrementPareils($tasObligatoire, $tasPremierRecrutement)
    {
        $this->rule->setTypeAgrement($this->typeAgrement);
        
        // un type d'agrément attendu identique à celui spécifié dans la règle
        $this->createTAS($this->typeAgrement, $tasObligatoire, $tasPremierRecrutement);
        
        foreach ([false, true] as $intervenantPremierRecrutement) {
            $this->setPremierRecrutementIntervenant($this->ie, $intervenantPremierRecrutement);
            
            // lorsque le recrutement de l'intervenant ne correspond pas à celui de la config TypeAgrementStatut,
            // l'intervenant n'est pas dans la liste résultat
            if ($intervenantPremierRecrutement !== $tasPremierRecrutement) {
                $this->rule->setIntervenant(null);
                $this->assertIntervenantNotInResult($this->ie);
                
                $this->rule->setIntervenant($this->ie);
                $this->assertIntervenantNotInResult($this->ie);
            }
            // sinon, l'intervenant est dans la liste résultat
            else {
                $this->rule->setIntervenant(null);
                $this->assertIntervenantInResult($this->ie);
                
                $this->rule->setIntervenant($this->ie);
                $this->assertIntervenantInResult($this->ie);
            }
        }
    }
    

    /**
     * @dataProvider getSettings
     */
    public function testExecuteIntervenantPermanent($tasObligatoire, $tasPremierRecrutement)
    {
        $this->rule->setTypeAgrement($this->typeAgrement);
        
        // un type d'agrément attendu pour le statut de l'IP
        $tas = $this->createTAS($this->typeAgrement, $tasObligatoire, $tasPremierRecrutement);
        $tas->setStatut($this->ip->getStatut());
        $this->getEntityManager()->flush($tas);
        
        // l'intervenant est dans la liste résultat, peu importe le flag PremierRecrutement qui n'a pas de sens pour un permanent
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
        $this->getEntityManager()->remove($this->ip);
        $this->getEntityManager()->flush();
        $this->getEntityProvider()->removeNewEntities();
    }
    
    /**
     * 
     * @param Intervenant $intervenant
     * @param boolean $premierRecrutement
     * @return self
     */
    private function setPremierRecrutementIntervenant(Intervenant $intervenant, $premierRecrutement = true)
    {
        $intervenant->setPremierRecrutement($premierRecrutement);
        
        $this->getEntityManager()->flush($intervenant);
        
        return $this;
    }

    /**
     * 
     * @param TypeAgrement $typeAgrement
     * @param Intervenant $intervenant
     * @return Agrement
     */
    private function addAgrementToIntervenant(TypeAgrement $typeAgrement, Intervenant $intervenant)
    {
        $agrement = $this->getEntityProvider()->getAgrement($typeAgrement, $intervenant);
        $intervenant->addAgrement($agrement);
        
        $this->getEntityManager()->flush($agrement);
        $this->getEntityManager()->flush($intervenant);
        
        return $agrement;
    }
    
    /**
     * 
     * @return StatutIntervenant
     */
    private function getStatut()
    {
        if (null === $this->statut) {
            $this->statut = $this->getEntityProvider()->getStatutIntervenant(false);
            
            $this->getEntityManager()->flush($this->statut);
        }
        
        return $this->statut;
    }
    
    /**
     * 
     * @param \Application\Entity\Db\TypePieceJointe $tasTypeAgrement
     * @param boolean $tasObligatoire
     * @param boolean $tasPremierRecrutement
     * @param float $tasSeuilHetd
     * @return TypeAgrementStatut
     */
    private function createTAS(TypeAgrement $tasTypeAgrement, $tasObligatoire, $tasPremierRecrutement, $tasSeuilHetd = null)
    {
        $tas = $this->getEntityProvider()->getTypeAgrementStatut($this->getStatut(), $tasTypeAgrement)
                ->setObligatoire($tasObligatoire)
                ->setPremierRecrutement($tasPremierRecrutement)
                ->setSeuilHetd($tasSeuilHetd);

        $this->getEntityManager()->flush($tas);
        
//        var_dump("TAS: " . $tas);
        
        return $tas;
    }
}