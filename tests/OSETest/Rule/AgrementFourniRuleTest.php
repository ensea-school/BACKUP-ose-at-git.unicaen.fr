<?php

namespace OSETest\Rule;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\StatutIntervenant;
use Application\Entity\Db\TypeAgrement;
use Application\Entity\Db\TypeAgrementStatut;
use Application\Entity\Db\Structure;
use Application\Rule\Intervenant\AgrementFourniRule;
use Application\Acl\ComposanteRole;
use Application\Acl\IntervenantRole;
use Common\Exception\LogicException;

/**
 * Test fonctionnel de la règle métier.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AgrementFourniRuleTest extends BaseRuleTest
{
    /**
     * @var AgrementFourniRule 
     */
    protected $rule;
    
    /**
     * @var Intervenant
     */
    private $ie;
    
    /**
     * @var Intervenant
     */
    private $ip;
    
    /**
     * @var StatutIntervenant 
     */
    private $statut;
    
    /**
     * @var TypeAgrement 
     */
    private $typeAgrementCR;
    
    /**
     * @var TypeAgrement 
     */
    private $typeAgrementCA;
    
    /**
     * @var ComposanteRole 
     */
    private $roleComposante;
    
    /**
     * @var IntervenantRole 
     */
    private $roleIntervenant;
    
    /**
     * @return string
     */
    protected function getRuleName()
    {
        return 'AgrementFourniRule';
    }
    
    /**
     * 
     */
    protected function setUp()
    {
        parent::setUp();
        
        $this->typeAgrementCR = $this->getEntityProvider()->getTypeAgrementByCode(TypeAgrement::CODE_CONSEIL_RESTREINT);
        $this->typeAgrementCA = $this->getEntityProvider()->getTypeAgrementByCode(TypeAgrement::CODE_CONSEIL_ACADEMIQUE);
        
        $this->roleIntervenant = new \Application\Acl\IntervenantRole();
        $this->roleComposante  = new \Application\Acl\ComposanteRole();

        /**
         * Création du jeu d'essai
         */
        $this->ip = $this->getEntityProvider()->getIntervenantPermanent()->setStatut($this->getStatut());
        $this->getEntityManager()->flush($this->ip);
        
        $this->ie = $this->getEntityProvider()->getIntervenantExterieur()->setStatut($this->getStatut());
        $this->getEntityManager()->flush($this->ie);
    }
    
    /**
     * 
     */
    public function testIsRelevant()
    {
        $this->rule->setIntervenant(null);
        static::assertTrue($this->rule->isRelevant());
        
        $this->rule->setIntervenant($this->ie);
        static::assertTrue($this->rule->isRelevant());
        
        $this->rule->setIntervenant($this->ip);
        static::assertTrue($this->rule->isRelevant());
    }
    
    /**
     * @expectedException LogicException
     */
    public function testExecuteThrowsExceptionWhenNoTypeSpecified()
    {
        $this->rule->setTypeAgrement(null);
        $this->rule->execute();
    }
//    
//    /**
//     * @expectedException LogicException
//     */
//    public function testExecuteThrowsExceptionWhenNoRoleSpecified()
//    {
//        $this->rule->setIntervenant($this->ie);
//        $this->rule->execute();
//    }
    
    /**
     * @return array
     */
    public function getSettings()
    {
        return [
            // PJ facultative, 2e recrutement
            'facultatif_2eRecrut' => [
                'tasObligatoire'        => false,
                'tasPremierRecrutement' => false,
            ],
            // PJ facultative, 1er recrutement
            'facultatif_1erRecrut' => [
                'tasObligatoire'        => false,
                'tasPremierRecrutement' => true,
            ],
            // PJ obligatoire, 2e recrutement
            'obligatoire_2eRecrut' => [
                'tasObligatoire'        => true,
                'tasPremierRecrutement' => false,
            ],
            // PJ obligatoire, 1er recrutement
            'obligatoire_1erRecrut' => [
                'tasObligatoire'        => true,
                'tasPremierRecrutement' => true,
            ],
        ];
    }
    
    public function testQuery()
    {
//        $this->rule->setTypeAgrement($this->typeAgrementCR);
//        var_dump($this->rule->getQuerySQL());
//        $this->rule->setTypeAgrement($this->typeAgrementCA);
//        var_dump($this->rule->getQuerySQL());
        
//        $this->rule->setTypeAgrement($this->typeAgrementCR);
//        $this->rule->setStructure($this->getEntityProvider()->getStructureEns());
//        var_dump($this->rule->getQuerySQL());
    }

    /**
     * @dataProvider getSettings
     */
    public function testExecuteIntervenantsSansAgrementOuAgrementNonAttendu($tasObligatoire, $tasPremierRecrutement)
    {
        $this->_testExecuteIntervenantsSansAgrementOuAgrementNonAttendu($this->typeAgrementCR, $tasObligatoire, $tasPremierRecrutement);
        $this->_testExecuteIntervenantsSansAgrementOuAgrementNonAttendu($this->typeAgrementCA, $tasObligatoire, $tasPremierRecrutement);
    }

    private function _testExecuteIntervenantsSansAgrementOuAgrementNonAttendu($typeAgrement, $tasObligatoire, $tasPremierRecrutement)
    {
        $this->rule->setTypeAgrement($typeAgrement);
        $this->rule->setRole($this->roleIntervenant);
        
        // un seul type d'agrément attendu
        $this->createTAS($typeAgrement, $tasObligatoire, $tasPremierRecrutement);
        
        foreach ([false, true] as $intervenantPremierRecrutement) {
            $this->setPremierRecrutementIntervenant($this->ie, $intervenantPremierRecrutement);
            
            // lorsque le recrutement de l'intervenant ne correspond pas à celui de la config TypeAgrementStatut,
            // l'intervenant n'est pas concerné
            if ($intervenantPremierRecrutement !== $tasPremierRecrutement) {
                $this->rule->setIntervenant(null);
                $this->assertIntervenantNotInResult($this->ie);
                
                $this->rule->setIntervenant($this->ie);
                $this->assertIntervenantNotInResult($this->ie);
            }
            // lorsque le type d'agrément est obligatoire, l'intervenant n'est pas dans la liste des intervenants en règle
            elseif (true === $tasObligatoire) {
                $this->rule->setIntervenant(null);
                $this->assertIntervenantNotInResult($this->ie);
                
                $this->rule->setIntervenant($this->ie);
                $this->assertIntervenantNotInResult($this->ie);
            }
            // lorsque le type d'agrément est facultatif, l'intervenant est dans la liste des intervenants en règle
            else {
                $this->rule->setIntervenant(null);
                $this->assertIntervenantInResult($this->ie);
                
                $this->rule->setIntervenant($this->ie);
                $this->assertIntervenantInResult($this->ie);
            }
        }
    }
    
    public function testExecuteIntervenantsAvecUnAgrementConseilRestreintAttenduEtFourni()
    {
        $typeAgrement          = $this->typeAgrementCR;
        $tasObligatoire        = true;
        $tasPremierRecrutement = true;
            
        $this->setPremierRecrutementIntervenant($this->ie, $tasPremierRecrutement);
        
        $this->rule->setRole($this->roleIntervenant);
        $this->rule->setTypeAgrement($typeAgrement);
        
        $typeStructureEns = $this->getEntityProvider()->getTypeStructureEns();
        $composante1      = $this->getEntityProvider()->getStructure()->setType($typeStructureEns);
        $composante2      = $this->getEntityProvider()->getStructure()->setType($typeStructureEns);
        $this->getEntityManager()->flush($composante1);
        $this->getEntityManager()->flush($composante2);

        $this->createTAS($typeAgrement, $tasObligatoire, $tasPremierRecrutement);
        $this->addAgrementToIntervenant($this->ie, $typeAgrement, $composante1);
        
        /**
         * - aucune structure précise transmise à la règle métier
         * - 1 agrément Conseil Restreint attendu a été fourni
         * - aucun enseignement saisi donc aucune composante d'intervention
         * --> l'intervenant figure dans la liste des intervenants en règle
         */
        $this->rule->setStructure(null);
        
        $this->rule->setIntervenant(null);
        $this->assertIntervenantInResult($this->ie);
        $this->rule->setIntervenant($this->ie);
        $this->assertIntervenantInResult($this->ie);
        
        /**
         * - aucune structure précise transmise à la règle métier
         * - 1 agrément Conseil Restreint attendu a été fourni
         * - 2 enseignements saisis dans 2 composantes d'intervention différentes : doit exister autant d'agréments que de composantes
         * --> 1 agrément < 2 composantes : l'intervenant n'est pas en règle
         */
        $this->rule->setStructure(null);
        
        $service1 = $this->getEntityProvider()->getService($this->ie, $composante1);
        $service2 = $this->getEntityProvider()->getService($this->ie, $composante2);
        $this->setServiceIntervenant($this->ie, ['CM' => 10], $service1);
        $this->setServiceIntervenant($this->ie, ['CM' => 20], $service2);
        $this->getEntityManager()->flush($service1);
        $this->getEntityManager()->flush($service2);
        
        $this->rule->setIntervenant(null);
        $this->assertIntervenantNotInResult($this->ie);
        $this->rule->setIntervenant($this->ie);
        $this->assertIntervenantNotInResult($this->ie);
        
        /**
         * - une structure précise est transmise à la règle métier : composante 2
         * - 1 agrément Conseil Restreint attendu a été fourni concernant la composante 1
         * - 2 enseignements saisis dans 2 composantes d'intervention différentes : un agrément concernant la structure doit exister
         * --> structure transmise à la règle != celle de l'agrément : l'intervenant n'est donc pas en règle
         */
        $this->rule->setStructure($composante2);
        
        $this->rule->setIntervenant(null);
        $this->assertIntervenantNotInResult($this->ie);
        $this->rule->setIntervenant($this->ie);
        $this->assertIntervenantNotInResult($this->ie);
        
        /**
         * - une structure précise est transmise à la règle métier : composante 1
         * - 1 agrément Conseil Restreint attendu a été fourni concernant la composante 1
         * - 2 enseignements saisis dans 2 composantes d'intervention différentes : un agrément concernant la structure doit exister
         * --> structure transmise à la règle = celle de l'agrément : l'intervenant est donc en règle
         */
        $this->rule->setStructure($composante1);
        
        $this->rule->setIntervenant(null);
        $this->assertIntervenantInResult($this->ie);
        $this->rule->setIntervenant($this->ie);
        $this->assertIntervenantInResult($this->ie);
        
        /**
         * - aucune structure précise transmise à la règle métier
         * - 2 agréments Conseil Restreint attendus ont été fournis
         * - 2 enseignements saisis dans 2 composantes d'intervention différentes
         * --> 2 agréments = 2 composantes : l'intervenant est en règle
         */
        $this->rule->setStructure(null);
        
        $this->addAgrementToIntervenant($this->ie, $typeAgrement, $composante2);
        
        $this->rule->setIntervenant(null);
        $this->assertIntervenantInResult($this->ie);
        $this->rule->setIntervenant($this->ie);
        $this->assertIntervenantInResult($this->ie);
    }
    
    
    public function testExecuteIntervenantsAvecUnAgrementConseilAcademiqueAttenduEtFourni()
    {
        $typeAgrement          = $this->typeAgrementCA;
        $tasObligatoire        = true;
        $tasPremierRecrutement = true;
            
        $this->setPremierRecrutementIntervenant($this->ie, $tasPremierRecrutement);
        
        $this->rule->setRole($this->roleIntervenant);
        $this->rule->setTypeAgrement($typeAgrement);
        
        $typeStructureEns = $this->getEntityProvider()->getTypeStructureEns();
        $composante1      = $this->getEntityProvider()->getStructure()->setType($typeStructureEns);
        $composante2      = $this->getEntityProvider()->getStructure()->setType($typeStructureEns);
        $this->getEntityManager()->flush($composante1);
        $this->getEntityManager()->flush($composante2);

        $this->createTAS($typeAgrement, $tasObligatoire, $tasPremierRecrutement);
        $this->addAgrementToIntervenant($this->ie, $typeAgrement, $composante1);
        
        /**
         * - aucune structure précise transmise à la règle métier
         * - 1 agrément Conseil Academique attendu a été fourni
         * - aucun enseignement saisi donc aucune composante d'intervention
         * --> l'intervenant figure dans la liste des intervenants en règle
         */
        $this->rule->setStructure(null);
        
        $this->rule->setIntervenant(null);
        $this->assertIntervenantInResult($this->ie);
        $this->rule->setIntervenant($this->ie);
        $this->assertIntervenantInResult($this->ie);
        
        /**
         * - aucune structure précise transmise à la règle métier
         * - 1 agrément Conseil Academique attendu a été fourni
         * - 2 enseignements saisis dans 2 composantes d'intervention différentes : 1 agrément pour toutes les composantes suffit
         * --> l'intervenant est en règle
         */
        $this->rule->setStructure(null);
        
        $service1 = $this->getEntityProvider()->getService($this->ie, $composante1);
        $service2 = $this->getEntityProvider()->getService($this->ie, $composante2);
        $this->setServiceIntervenant($this->ie, ['CM' => 10], $service1);
        $this->setServiceIntervenant($this->ie, ['CM' => 20], $service2);
        $this->getEntityManager()->flush($service1);
        $this->getEntityManager()->flush($service2);
        
        $this->rule->setIntervenant(null);
        $this->assertIntervenantInResult($this->ie);
        $this->rule->setIntervenant($this->ie);
        $this->assertIntervenantInResult($this->ie);
        
        /**
         * - une structure précise est transmise à la règle métier (composante 2) mais elle n'est pas prise en compte
         * - 1 agrément Conseil Academique attendu a été fourni concernant la composante 1
         * - 2 enseignements saisis dans 2 composantes d'intervention différentes : 1 agrément pour toutes les composantes suffit
         * --> l'intervenant est en règle
         */
        $this->rule->setStructure($composante2);
        
        $this->rule->setIntervenant(null);
        $this->assertIntervenantInResult($this->ie);
        $this->rule->setIntervenant($this->ie);
        $this->assertIntervenantInResult($this->ie);
        
        /**
         * - une structure précise est transmise à la règle métier (composante 2) mais elle n'est pas prise en compte
         * - 1 agrément Conseil Academique attendu a été fourni concernant la composante 1
         * - 2 enseignements saisis dans 2 composantes d'intervention différentes : 1 agrément pour toutes les composantes suffit
         * --> l'intervenant est en règle
         */
        $this->rule->setStructure($composante1);
        
        $this->rule->setIntervenant(null);
        $this->assertIntervenantInResult($this->ie);
        $this->rule->setIntervenant($this->ie);
        $this->assertIntervenantInResult($this->ie);
        
        /**
         * - aucune structure précise transmise à la règle métier
         * - 2 agréments Conseil Academique attendus ont été fournis
         * - 2 enseignements saisis dans 2 composantes d'intervention différentes : 1 agrément pour toutes les composantes suffit
         * --> l'intervenant est en règle
         */
        $this->rule->setStructure(null);
        
        $this->addAgrementToIntervenant($this->ie, $typeAgrement, $composante2);
        
        $this->rule->setIntervenant(null);
        $this->assertIntervenantInResult($this->ie);
        $this->rule->setIntervenant($this->ie);
        $this->assertIntervenantInResult($this->ie);
    }
    
    /**
     * 
     */
    protected function tearDown()
    {
        parent::tearDown();
        
        /**
         * Suppression du jeu d'essai
         */
        // L'intervenant doit être supprimé avant son dossier pour ne pas rencontrer l'erreur
        // ORA-02292: integrity constraint (OSE.INTERVENANT_EXTERIEUR_DOSSIER) violated - child record found
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
     * @param Intervenant $intervenant
     * @param TypeAgrement $typeAgrement
     * @param Structure $structure
     * @return Agrement
     */
    private function addAgrementToIntervenant(Intervenant $intervenant, TypeAgrement $typeAgrement, Structure $structure = null)
    {
        $agrement = $this->getEntityProvider()->getAgrement($typeAgrement, $intervenant, $structure);
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