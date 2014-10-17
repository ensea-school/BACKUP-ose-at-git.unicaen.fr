<?php

namespace OSETest\Rule;

use Application\Entity\Db\IntervenantExterieur;
use Application\Entity\Db\IntervenantPermanent;
use Application\Entity\Db\StatutIntervenant;
use Application\Entity\Db\TypePieceJointe;
use Application\Entity\Db\TypePieceJointeStatut;
use Application\Entity\Db\TypeValidation;
use Application\Entity\Db\Service;
use Application\Rule\Intervenant\PiecesJointesFourniesRule;

/**
 * Test fonctionnel de la règle métier.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PiecesJointesFourniesRuleTest extends BaseRuleTest
{
    /**
     * @var PiecesJointesFourniesRule 
     */
    protected $rule;
    
    /**
     * @var IntervenantExterieur 
     */
    private $ie;
    
    /**
     * @var StatutIntervenant 
     */
    private $statut;
    
    /**
     * @var Service 
     */
    private $service;
    
    /**
     * @return string
     */
    protected function getRuleName()
    {
        return 'PiecesJointesFourniesRule';
    }
    
    /**
     * 
     */
    protected function setUp()
    {
        parent::setUp();
        
        $this->rule
                ->setAvecFichier(null) // pas de critère de fichier
                ->setAvecValidation(null) //  pas de critère de validation
                /*->setPiecesJointesObligatoiresUniquement(false)*/; // seules les PJ obligatoires nous intéressent
        
        // création d'un intervenant extérieur
        $this->ie = $this->getEntityProvider()->getIntervenantExterieur();
        $dossier = $this->getEntityProvider()->getDossier()->setStatut($this->getStatut());
        $this->ie->setDossier($dossier);
        $this->getEntityManager()->flush($dossier);
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
        
        $this->rule->setIntervenant(new IntervenantExterieur()); // intervenant extérieur sans dossier
        static::assertFalse($this->rule->isRelevant());
        
        $this->setExpectedException('Common\Exception\LogicException');
        $this->rule->setIntervenant(new IntervenantPermanent()); // intervenant permanent interdit
    }
    
    /**
     * @return array
     */
    public function getSettings()
    {
        return [
            // PJ facultative, 2e recrutement
            'PjFacultative_2eRecrut' => [
                'tpjsObligatoire'        => false,
                'tpjsPremierRecrutement' => false,
            ],
            // PJ facultative, 1er recrutement
            'PjFacultative_1erRecrut' => [
                'tpjsObligatoire'        => false,
                'tpjsPremierRecrutement' => true,
            ],
            // PJ obligatoire, 2e recrutement
            'PjObligatoire_2eRecrut' => [
                'tpjsObligatoire'        => true,
                'tpjsPremierRecrutement' => false,
            ],
            // PJ obligatoire, 1er recrutement
            'PjObligatoire_1erRecrut' => [
                'tpjsObligatoire'        => true,
                'tpjsPremierRecrutement' => true,
            ],
        ];
    }

    /**
     * @dataProvider getSettings
     */
    public function testExecuteIntervenantsSansPjOuPjNonAttendue($tpjsObligatoire, $tpjsPremierRecrutement)
    {
        // une seule PJ attendue
        $tpj = $this->getEntityProvider()->getTypePieceJointe();
        $this->getEntityManager()->flush($tpj);
        $this->createTpjs($tpj, $tpjsObligatoire, $tpjsPremierRecrutement);
        
        foreach ([false, true] as $intervenantPremierRecrutement) {
            $this->setPremierRecrutementIntervenant($intervenantPremierRecrutement);
            
            // lorsque le recrutement de l'intervenant ne correspond pas à celui de la config TypePieceJointeStatut,
            // l'intervenant n'a pas à fournir la PJ
            if ($intervenantPremierRecrutement !== $tpjsPremierRecrutement) {
                $this->assertIntervenantNotInResult($this->ie);
            }
            // lorsque la PJ est obligatoire, l'intervenant n'est pas dans la liste des intervenants ayant fourni la PJ
            elseif (true === $tpjsObligatoire) {
                $this->assertIntervenantNotInResult($this->ie);
            }
            // lorsque la PJ est facultative, l'intervenant est dans la liste des intervenants en règle avec leurs PJ
            else {
                $this->assertIntervenantInResult($this->ie);
            }
        }
    }

    /**
     * @dataProvider getSettings
     */
    public function testExecuteIntervenantsAvecUnePjAttendue($tpjsObligatoire, $tpjsPremierRecrutement)
    {
        // une seule PJ attendue
        $tpj = $this->getEntityProvider()->getTypePieceJointe();
        $this->getEntityManager()->flush($tpj);
        $this->createTpjs($tpj, $tpjsObligatoire, $tpjsPremierRecrutement);
        
        // l'intervenant a fourni l'unique PJ attendue...
        $tpjAttendu = $tpj;
        $this->addPieceJointeToIntervenant($tpjAttendu);
        
        foreach ([false, true] as $intervenantPremierRecrutement) {
            $this->setPremierRecrutementIntervenant($intervenantPremierRecrutement);
            
            // lorsque le recrutement de l'intervenant ne correspond pas à celui de la config TypePieceJointeStatut,
            // l'intervenant n'a pas à fournir la PJ
            if ($intervenantPremierRecrutement !== $tpjsPremierRecrutement) {
                $this->assertIntervenantNotInResult($this->ie);
            }
            // sinon l'intervenant est dans la liste des intervenants en règle avec leurs PJ
            else {
                $this->assertIntervenantInResult($this->ie);
            }
        }
    }

    /**
     * 
     */
    public function testExecuteAvecPjObligatoireSelonSeuil()
    {
        $premierRecrutement = true;
        $this->setPremierRecrutementIntervenant($premierRecrutement);
        
        // une PJ obligatoire au delà d'un seuil d'heures
        $tpj = $this->getEntityProvider()->getTypePieceJointe();
        $this->getEntityManager()->flush($tpj);
        $this->createTpjs($tpj, true, $premierRecrutement, 20);
        
        // si l'utilisateur n'a aucun service, la PJ n'est pas obligatoire
        // --> l'utilisateur est en règle
        $this->assertIntervenantInResult($this->ie);
        
        // si l'utilisateur a moins d'heures de service que le seuil requis, la PJ n'est pas obligatoire
        // --> l'utilisateur est en règle
        $this->setServiceIntervenant(15.0);
        $this->assertIntervenantInResult($this->ie);
        
        // si l'utilisateur a exactement le nombre d'heures de service que le seuil requis, la PJ devient obligatoire
        // --> l'utilisateur n'est plus en règle
        $this->setServiceIntervenant(20);
        $this->assertIntervenantNotInResult($this->ie);
        
        // si l'utilisateur a plus d'heures de service que le seuil requis, la PJ reste obligatoire
        // --> l'utilisateur n'est toujours pas en règle
        $this->setServiceIntervenant(20.01);
        $this->assertIntervenantNotInResult($this->ie);
        
        // maintenant, l'utilisateur fournit la PJ attendue
        // --> l'utilisateur est en règle
        $tpjAttendu = $tpj;
        $this->addPieceJointeToIntervenant($tpjAttendu);
        $this->assertIntervenantInResult($this->ie);
    }
    
    /**
     * @return array
     */
    public function getFichierEtValidationFlags()
    {
        return [
            'AvecOuSansFichier_AvecOuSansValidation' => [null, null],
            'AvecOuSansFichier_AvecValidation'       => [null, true],
            'AvecFichier_AvecOuSansValidation'       => [true, null],
            'AvecFichier_AvecValidation'             => [true, true],
        ];
    }

    /**
     * @dataProvider getFichierEtValidationFlags
     */
    public function testGetPiecesJointesFournies($avecFichier, $avecValidation)
    {
        $this->rule
                ->setIntervenant($this->ie)
                ->setAvecFichier($avecFichier)
                ->setAvecValidation($avecValidation);
                
        /**
         * Aucune PJ fournie
         */
        $fournies = $this->rule->getPiecesJointesFournies();
        $this->assertEquals([], $fournies);
        
        /**
         * PJ fournie: pas de fichier, pas de validation
         */
        $tpj = $this->getEntityProvider()->getTypePieceJointe();
        $pj = $this->addPieceJointeToIntervenant($tpj);
        $this->getEntityManager()->flush($tpj);
        $this->getEntityManager()->flush($pj);
        
        $fournies = $this->rule->getPiecesJointesFournies();
        if (true === $avecFichier || true === $avecValidation) {
            $this->assertEquals([], $fournies);
        }
        else {
            $this->assertCount(1, $fournies);
            $this->assertContains($pj, $fournies);
        }
        
        /**
         * PJ fournie: un fichier, pas de validation
         */
        $fichier = $this->getEntityProvider()->getFichier();
        $pj->addFichier($fichier);
        $this->getEntityManager()->flush($fichier);
        $this->getEntityManager()->flush($pj);
        
        $fournies = $this->rule->getPiecesJointesFournies();
        if (false === $avecFichier || true === $avecValidation) {
            $this->assertEquals([], $fournies);
        }
        else {
            $this->assertCount(1, $fournies);
            $this->assertContains($pj, $fournies);
        }
        
        /**
         * PJ fournie: un fichier, une validation
         */
        $typeValidation = $this->getEntityProvider()->getTypeValidationByCode(TypeValidation::CODE_PIECE_JOINTE);
        $validation = $this->getEntityProvider()->getValidation($typeValidation, $this->ie);
        $pj->setValidation($validation);
        $this->getEntityManager()->flush($validation);
        $this->getEntityManager()->flush($pj);
        
        $fournies = $this->rule->getPiecesJointesFournies();
        if (false === $avecFichier || false === $avecValidation) {
            $this->assertEquals([], $fournies);
        }
        else {
            $this->assertCount(1, $fournies);
            $this->assertContains($pj, $fournies);
        }
        
        /**
         * PJ fournie: pas de fichier, une validation
         */
        $pj->removeFichier($fichier);
        $this->getEntityManager()->remove($fichier);
        $this->getEntityManager()->flush($pj);
        
        $fournies = $this->rule->getPiecesJointesFournies();
        if (true === $avecFichier || false === $avecValidation) {
            $this->assertEquals([], $fournies);
        }
        else {
            $this->assertCount(1, $fournies);
            $this->assertContains($pj, $fournies);
        }
    }

    /**
     * 
     */
    public function testGetTypesPieceJointeObligatoiresNonFournis()
    {
        $this->rule->setIntervenant($this->ie);
        
        /**
         * 3 PJ attendues : 1 obligatoire sans seuil, 1 obligatoire au-delà de 20h, et 1 facultative
         */
        $tpjObl1 = $this->getEntityProvider()->getTypePieceJointe();
        $tpjObl2 = $this->getEntityProvider()->getTypePieceJointe();
        $tpjFac  = $this->getEntityProvider()->getTypePieceJointe();
        $this->getEntityManager()->flush($tpjObl1);
        $this->getEntityManager()->flush($tpjObl2);
        $this->getEntityManager()->flush($tpjFac);
        $this->createTpjs($tpjObl1, true, true);
        $this->createTpjs($tpjObl2, true, true, 20);
        $this->createTpjs($tpjFac, false, true);
        
        /**
         * Aucune PJ fournie, aucun service
         */
        $types = $this->rule->getTypesPieceJointeObligatoiresNonFournis();
        $this->assertCount(1, $types);
        $this->assertContains($tpjObl1, $types);
        
        /**
         * Aucune PJ fournie, service < seuil
         */
        $this->setServiceIntervenant(10);
        $types = $this->rule->getTypesPieceJointeObligatoiresNonFournis();
        $this->assertCount(1, $types);
        $this->assertContains($tpjObl1, $types);
        
        /**
         * Aucune PJ fournie, service > seuil
         */
        $this->setServiceIntervenant(25);
        $types = $this->rule->getTypesPieceJointeObligatoiresNonFournis();
        $this->assertCount(2, $types);
        $this->assertContains($tpjObl1, $types);
        $this->assertContains($tpjObl2, $types);
        
        /**
         * PJ facultative fournie
         */
        $this->addPieceJointeToIntervenant($tpjFac);
        
        $types = $this->rule->getTypesPieceJointeObligatoiresNonFournis();
        $this->assertCount(2, $types);
        $this->assertContains($tpjObl1, $types);
        $this->assertContains($tpjObl2, $types);
        
        /**
         * PJ obligatoire sans seuil fournie
         */
        $this->addPieceJointeToIntervenant($tpjObl1);
        
        $types = $this->rule->getTypesPieceJointeObligatoiresNonFournis();
        $this->assertCount(1, $types);
        $this->assertContains($tpjObl2, $types);
        
        /**
         * PJ obligatoire avec seuil fournie
         */
        $this->addPieceJointeToIntervenant($tpjObl2);
        
        $types = $this->rule->getTypesPieceJointeObligatoiresNonFournis();
        $this->assertEquals([], $types);
    }

    /**
     * 
     */
    public function testGetTypesPieceJointeObligatoiresSelonSeuilNonFournis()
    {
        $this->rule->setIntervenant($this->ie);
        
        /**
         * 2 PJ attendues : une obligatoire et une facultative
         */
        $tpjObl = $this->getEntityProvider()->getTypePieceJointe();
        $tpjFac = $this->getEntityProvider()->getTypePieceJointe();
        $this->getEntityManager()->flush($tpjObl);
        $this->getEntityManager()->flush($tpjFac);
        $this->createTpjs($tpjObl, true, true, null);
        $this->createTpjs($tpjFac, false, true, null);
        
        /**
         * Aucune PJ fournie
         */
        $types = $this->rule->getTypesPieceJointeObligatoiresNonFournis();
        $this->assertCount(1, $types);
        $this->assertContains($tpjObl, $types);
        
        /**
         * PJ facultative fournie
         */
        $pjFac = $this->addPieceJointeToIntervenant($tpjFac);
        $this->getEntityManager()->flush($pjFac);
        
        $types = $this->rule->getTypesPieceJointeObligatoiresNonFournis();
        $this->assertCount(1, $types);
        $this->assertContains($tpjObl, $types);
        
        /**
         * PJ obligatoire fournie
         */
        $pjObl = $this->addPieceJointeToIntervenant($tpjObl);
        $this->getEntityManager()->flush($pjObl);
        
        $types = $this->rule->getTypesPieceJointeObligatoiresNonFournis();
        $this->assertEquals([], $types);
    }
    
    /**
     * 
     * @param \Application\Entity\Db\IntervenantExterieur $ie
     */
    private function assertIntervenantNotInResult(IntervenantExterieur $ie)
    {
        $id = $ie->getId();
        
        /**
         * - Intervenant-filtre spécifié : aucun
         */
        $result = $this->rule->setIntervenant(null)->execute();
        $this->assertArrayNotHasKey($id, $result);
        $this->assertNotContains(['id' => $id], $result);
        $this->assertNull($this->rule->getMessage());
        
        /**
         * - Intervenant-filtre spécifié : IE
         */
        $result = $this->rule->setIntervenant($ie)->execute();
        $this->assertEquals([], $result);
        $this->assertNotNull($this->rule->getMessage());
    }
    
    /**
     * 
     * @param \Application\Entity\Db\IntervenantExterieur $ie
     */
    private function assertIntervenantInResult(IntervenantExterieur $ie)
    {
        $id = $ie->getId();
        
        /**
         * - Intervenant-filtre spécifié : aucun
         */
        $result = $this->rule->setIntervenant(null)->execute();
        $this->assertArrayHasKey($id, $result);
        $this->assertEquals(['id' => $id], $result[$id]);
        $this->assertNull($this->rule->getMessage());
        
        /**
         * - Intervenant-filtre spécifié : IE
         */
        $result = $this->rule->setIntervenant($ie)->execute();
        $this->assertEquals([$id => ['id' => $id]], $result);
        $this->assertNull($this->rule->getMessage());
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
        $this->getEntityManager()->flush();
        $this->getEntityProvider()->removeNewEntities();
    }
    
    /**
     * 
     * @param boolean $premierRecrutement
     * @return self
     */
    private function setPremierRecrutementIntervenant($premierRecrutement = true)
    {
        $dossier = $this->ie->getDossier();
        $dossier->setPremierRecrutement($premierRecrutement);
        
        $this->getEntityManager()->flush($dossier);
        
        return $this;
    }

    /**
     * 
     * @param \Application\Entity\Db\TypePieceJointe $tpj
     * @return PieceJointe
     */
    private function addPieceJointeToIntervenant(TypePieceJointe $tpj)
    {
        $dossier = $this->ie->getDossier();
        $pj      = $this->getEntityProvider()->getPieceJointe($tpj, $dossier);
        
        $dossier->addPieceJointe($pj);
        
        $this->getEntityManager()->flush($dossier);
        $this->getEntityManager()->flush($pj);
        
        return $pj;
    }

    /**
     * 
     * @param float $heures
     * @return Service
     */
    private function setServiceIntervenant($heures)
    {
        if (null === $this->service) {
            $this->service = $this->getEntityProvider()->getService($this->ie);
            $vh            = $this->getEntityProvider()->getVolumeHoraire($this->service, $heures);
            $this->service->addVolumeHoraire($vh);
            $this->ie->addService($this->service);
            
            $this->getEntityManager()->flush($this->ie);
        }
        
        $vh = $this->service->getVolumeHoraire()->first();
        $vh->setHeures($heures);
        
        $this->getEntityManager()->flush($this->service);
        $this->getEntityManager()->flush($vh);
        
        return $this->service;
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
     * @param \Application\Entity\Db\TypePieceJointe $tpjsTypePieceJointe
     * @param boolean $tpjsObligatoire
     * @param boolean $tpjsPremierRecrutement
     * @param float $tpjsSeuilHetd
     * @return TypePieceJointeStatut
     */
    private function createTpjs(TypePieceJointe $tpjsTypePieceJointe, $tpjsObligatoire, $tpjsPremierRecrutement, $tpjsSeuilHetd = null)
    {
        $tpjs = $this->getEntityProvider()->getTypePieceJointeStatut($this->getStatut(), $tpjsTypePieceJointe)
                ->setObligatoire($tpjsObligatoire)
                ->setPremierRecrutement($tpjsPremierRecrutement)
                ->setSeuilHetd($tpjsSeuilHetd);

        $this->getEntityManager()->flush($tpjs);
        
//        var_dump("TPJS: " . $this->tpjs);
        
        return $tpjs;
    }
}