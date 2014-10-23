<?php

namespace OSETest\Rule;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\IntervenantExterieur;
use Application\Entity\Db\IntervenantPermanent;
use Application\Entity\Db\StatutIntervenant;
use Application\Entity\Db\TypeAgrement;
use Application\Entity\Db\TypeAgrementStatut;
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
     * @var IntervenantExterieur 
     */
    private $ie;
    
    /**
     * @var IntervenantPermanent
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
    
    /**
     * @expectedException LogicException
     */
    public function testExecuteThrowsExceptionWhenBadTypeSpecified()
    {
        $ta = $this->getEntityProvider()->getTypeAgrement();
        $this->rule->setTypeAgrement($ta);
    }
    
    /**
     * @expectedException LogicException
     */
    public function testExecuteThrowsExceptionWhenNoRoleSpecified()
    {
        $this->rule->setIntervenant($this->ie);
        $this->rule->execute();
    }
    
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
        $this->rule->setTypeAgrement($this->typeAgrementCR);
        $this->rule->setRole($this->roleIntervenant);
        
        // un seul type d'agrément attendu
        $this->createTAS($this->typeAgrementCR, $tasObligatoire, $tasPremierRecrutement);
        
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
                
                /**
                 * @todo Corriger la règle pour prendre en compte le caractère facultatif 
                 * dans AgrementAbstractRule
                 */
                $this->rule->setIntervenant($this->ie);
                $this->assertIntervenantInResult($this->ie);
            }
        }
    }

//    /**
//     * @dataProvider getSettings
//     */
//    public function testExecuteIntervenantsAvecUnePjAttendue($tpjsObligatoire, $tpjsPremierRecrutement)
//    {
//        // une seule PJ attendue
//        $tpj = $this->getEntityProvider()->getTypePieceJointe();
//        $this->getEntityManager()->flush($tpj);
//        $this->createTpjs($tpj, $tpjsObligatoire, $tpjsPremierRecrutement);
//        
//        // l'intervenant a fourni l'unique PJ attendue...
//        $tpjAttendu = $tpj;
//        $this->addPieceJointeToIntervenant($tpjAttendu);
//        
//        foreach ([false, true] as $intervenantPremierRecrutement) {
//            $this->setPremierRecrutementIntervenant($intervenantPremierRecrutement);
//            
//            // lorsque le recrutement de l'intervenant ne correspond pas à celui de la config TypePieceJointeStatut,
//            // l'intervenant n'a pas à fournir la PJ
//            if ($intervenantPremierRecrutement !== $tpjsPremierRecrutement) {
//                $this->rule->setIntervenant(null);
//                $this->assertIntervenantNotInResult($this->ie);
//                
//                $this->rule->setIntervenant($this->ie);
//                $this->assertIntervenantNotInResult($this->ie);
//            }
//            // sinon l'intervenant est dans la liste des intervenants en règle avec leurs PJ
//            else {
//                $this->rule->setIntervenant(null);
//                $this->assertIntervenantInResult($this->ie);
//                
//                $this->rule->setIntervenant($this->ie);
//                $this->assertIntervenantInResult($this->ie);
//            }
//        }
//    }
//
//    /**
//     * 
//     */
//    public function testExecuteAvecPjObligatoireSelonSeuil()
//    {
//        $premierRecrutement = true;
//        $this->setPremierRecrutementIntervenant($premierRecrutement);
//        
//        // une PJ obligatoire au delà d'un seuil d'heures
//        $tpj = $this->getEntityProvider()->getTypePieceJointe();
//        $this->getEntityManager()->flush($tpj);
//        $this->createTpjs($tpj, true, $premierRecrutement, 20);
//        
//        // si l'utilisateur n'a aucun service, la PJ n'est pas obligatoire
//        // --> l'utilisateur est en règle
//        $this->rule->setIntervenant(null);
//        $this->assertIntervenantInResult($this->ie);
//
//        $this->rule->setIntervenant($this->ie);
//        $this->assertIntervenantInResult($this->ie);
//        
//        // si l'utilisateur a moins d'heures de service que le seuil requis, la PJ n'est pas obligatoire
//        // --> l'utilisateur est en règle
//        $this->setServiceIntervenant($this->ie, ['CM' => 15.0], $this->service);
//        
//        $this->rule->setIntervenant(null);
//        $this->assertIntervenantInResult($this->ie);
//
//        $this->rule->setIntervenant($this->ie);
//        $this->assertIntervenantInResult($this->ie);
//        
//        // si l'utilisateur a exactement le nombre d'heures de service que le seuil requis, la PJ devient obligatoire
//        // --> l'utilisateur n'est plus en règle
//        $this->setServiceIntervenant($this->ie, ['CM' => 20], $this->service);
//        
//        $this->rule->setIntervenant(null);
//        $this->assertIntervenantNotInResult($this->ie);
//
//        $this->rule->setIntervenant($this->ie);
//        $this->assertIntervenantNotInResult($this->ie);
//        
//        // si l'utilisateur a plus d'heures de service que le seuil requis, la PJ reste obligatoire
//        // --> l'utilisateur n'est toujours pas en règle
//        $this->setServiceIntervenant($this->ie, ['CM' => 20.01], $this->service);
//        
//        $this->rule->setIntervenant(null);
//        $this->assertIntervenantNotInResult($this->ie);
//
//        $this->rule->setIntervenant($this->ie);
//        $this->assertIntervenantNotInResult($this->ie);
//        
//        // maintenant, l'utilisateur fournit la PJ attendue
//        // --> l'utilisateur est en règle
//        $tpjAttendu = $tpj;
//        $this->addPieceJointeToIntervenant($tpjAttendu);
//        
//        $this->rule->setIntervenant(null);
//        $this->assertIntervenantInResult($this->ie);
//
//        $this->rule->setIntervenant($this->ie);
//        $this->assertIntervenantInResult($this->ie);
//    }
//    
//    /**
//     * @return array
//     */
//    public function getFichierEtValidationFlags()
//    {
//        return [
//            'AvecOuSansFichier_AvecOuSansValidation' => [null, null],
//            'AvecOuSansFichier_AvecValidation'       => [null, true],
//            'AvecFichier_AvecOuSansValidation'       => [true, null],
//            'AvecFichier_AvecValidation'             => [true, true],
//        ];
//    }
//
//    /**
//     * @dataProvider getFichierEtValidationFlags
//     */
//    public function testGetPiecesJointesFournies($avecFichier, $avecValidation)
//    {
//        $this->rule
//                ->setIntervenant($this->ie)
//                ->setAvecFichier($avecFichier)
//                ->setAvecValidation($avecValidation);
//                
//        /**
//         * Aucune PJ fournie
//         */
//        $fournies = $this->rule->getPiecesJointesFournies();
//        $this->assertEquals([], $fournies);
//        
//        /**
//         * PJ fournie: pas de fichier, pas de validation
//         */
//        $tpj = $this->getEntityProvider()->getTypePieceJointe();
//        $pj = $this->addPieceJointeToIntervenant($tpj);
//        $this->getEntityManager()->flush($tpj);
//        $this->getEntityManager()->flush($pj);
//        
//        $fournies = $this->rule->getPiecesJointesFournies();
//        if (true === $avecFichier || true === $avecValidation) {
//            $this->assertEquals([], $fournies);
//        }
//        else {
//            $this->assertCount(1, $fournies);
//            $this->assertContains($pj, $fournies);
//        }
//        
//        /**
//         * PJ fournie: un fichier, pas de validation
//         */
//        $fichier = $this->getEntityProvider()->getFichier();
//        $pj->addFichier($fichier);
//        $this->getEntityManager()->flush($fichier);
//        $this->getEntityManager()->flush($pj);
//        
//        $fournies = $this->rule->getPiecesJointesFournies();
//        if (false === $avecFichier || true === $avecValidation) {
//            $this->assertEquals([], $fournies);
//        }
//        else {
//            $this->assertCount(1, $fournies);
//            $this->assertContains($pj, $fournies);
//        }
//        
//        /**
//         * PJ fournie: un fichier, une validation
//         */
//        $typeValidation = $this->getEntityProvider()->getTypeValidationByCode(TypeValidation::CODE_PIECE_JOINTE);
//        $validation = $this->getEntityProvider()->getValidation($typeValidation, $this->ie);
//        $pj->setValidation($validation);
//        $this->getEntityManager()->flush($validation);
//        $this->getEntityManager()->flush($pj);
//        
//        $fournies = $this->rule->getPiecesJointesFournies();
//        if (false === $avecFichier || false === $avecValidation) {
//            $this->assertEquals([], $fournies);
//        }
//        else {
//            $this->assertCount(1, $fournies);
//            $this->assertContains($pj, $fournies);
//        }
//        
//        /**
//         * PJ fournie: pas de fichier, une validation
//         */
//        $pj->removeFichier($fichier);
//        $this->getEntityManager()->remove($fichier);
//        $this->getEntityManager()->flush($pj);
//        
//        $fournies = $this->rule->getPiecesJointesFournies();
//        if (true === $avecFichier || false === $avecValidation) {
//            $this->assertEquals([], $fournies);
//        }
//        else {
//            $this->assertCount(1, $fournies);
//            $this->assertContains($pj, $fournies);
//        }
//    }
//
//    /**
//     * 
//     */
//    public function testGetTypesPieceJointeObligatoiresNonFournis()
//    {
//        $this->rule->setIntervenant($this->ie);
//        
//        /**
//         * 3 PJ attendues : 1 obligatoire sans seuil, 1 obligatoire au-delà de 20h, et 1 facultative
//         */
//        $tpjObl1 = $this->getEntityProvider()->getTypePieceJointe();
//        $tpjObl2 = $this->getEntityProvider()->getTypePieceJointe();
//        $tpjFac  = $this->getEntityProvider()->getTypePieceJointe();
//        $this->getEntityManager()->flush($tpjObl1);
//        $this->getEntityManager()->flush($tpjObl2);
//        $this->getEntityManager()->flush($tpjFac);
//        $this->createTpjs($tpjObl1, true, true);
//        $this->createTpjs($tpjObl2, true, true, 20);
//        $this->createTpjs($tpjFac, false, true);
//        
//        /**
//         * Aucune PJ fournie, aucun service
//         */
//        $types = $this->rule->getTypesPieceJointeObligatoiresNonFournis();
//        $this->assertCount(1, $types);
//        $this->assertContains($tpjObl1, $types);
//        
//        /**
//         * Aucune PJ fournie, service < seuil
//         */
//        $this->setServiceIntervenant($this->ie, ['CM' => 10], $this->service);
//        $types = $this->rule->getTypesPieceJointeObligatoiresNonFournis();
//        $this->assertCount(1, $types);
//        $this->assertContains($tpjObl1, $types);
//        
//        /**
//         * Aucune PJ fournie, service > seuil
//         */
//        $this->setServiceIntervenant($this->ie, ['CM' => 25], $this->service);
//        $types = $this->rule->getTypesPieceJointeObligatoiresNonFournis();
//        $this->assertCount(2, $types);
//        $this->assertContains($tpjObl1, $types);
//        $this->assertContains($tpjObl2, $types);
//        
//        /**
//         * PJ facultative fournie
//         */
//        $this->addPieceJointeToIntervenant($tpjFac);
//        
//        $types = $this->rule->getTypesPieceJointeObligatoiresNonFournis();
//        $this->assertCount(2, $types);
//        $this->assertContains($tpjObl1, $types);
//        $this->assertContains($tpjObl2, $types);
//        
//        /**
//         * PJ obligatoire sans seuil fournie
//         */
//        $this->addPieceJointeToIntervenant($tpjObl1);
//        
//        $types = $this->rule->getTypesPieceJointeObligatoiresNonFournis();
//        $this->assertCount(1, $types);
//        $this->assertContains($tpjObl2, $types);
//        
//        /**
//         * PJ obligatoire avec seuil fournie
//         */
//        $this->addPieceJointeToIntervenant($tpjObl2);
//        
//        $types = $this->rule->getTypesPieceJointeObligatoiresNonFournis();
//        $this->assertEquals([], $types);
//    }
//
//    /**
//     * 
//     */
//    public function testGetTypesPieceJointeObligatoiresSelonSeuilNonFournis()
//    {
//        $this->rule->setIntervenant($this->ie);
//        
//        /**
//         * 2 PJ attendues : une obligatoire et une facultative
//         */
//        $tpjObl = $this->getEntityProvider()->getTypePieceJointe();
//        $tpjFac = $this->getEntityProvider()->getTypePieceJointe();
//        $this->getEntityManager()->flush($tpjObl);
//        $this->getEntityManager()->flush($tpjFac);
//        $this->createTpjs($tpjObl, true, true, null);
//        $this->createTpjs($tpjFac, false, true, null);
//        
//        /**
//         * Aucune PJ fournie
//         */
//        $types = $this->rule->getTypesPieceJointeObligatoiresNonFournis();
//        $this->assertCount(1, $types);
//        $this->assertContains($tpjObl, $types);
//        
//        /**
//         * PJ facultative fournie
//         */
//        $pjFac = $this->addPieceJointeToIntervenant($tpjFac);
//        $this->getEntityManager()->flush($pjFac);
//        
//        $types = $this->rule->getTypesPieceJointeObligatoiresNonFournis();
//        $this->assertCount(1, $types);
//        $this->assertContains($tpjObl, $types);
//        
//        /**
//         * PJ obligatoire fournie
//         */
//        $pjObl = $this->addPieceJointeToIntervenant($tpjObl);
//        $this->getEntityManager()->flush($pjObl);
//        
//        $types = $this->rule->getTypesPieceJointeObligatoiresNonFournis();
//        $this->assertEquals([], $types);
//    }
    
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