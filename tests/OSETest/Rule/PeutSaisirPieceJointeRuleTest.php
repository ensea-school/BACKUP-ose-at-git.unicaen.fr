<?php

namespace OSETest\Rule;

use Application\Entity\Db\IntervenantExterieur;
use Application\Entity\Db\StatutIntervenant;
use Application\Entity\Db\TypePieceJointe;
use Application\Rule\Intervenant\PeutSaisirPieceJointeRule;

/**
 * Test fonctionnel de la règle métier.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PeutSaisirPieceJointeRuleTest extends BaseRuleTest
{
    /**
     * @var PeutSaisirPieceJointeRule 
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
     * @return string
     */
    protected function getRuleName()
    {
        return 'PeutSaisirPieceJointeRule';
    }
    
    /**
     * 
     */
    protected function setUp()
    {
        parent::setUp();
        
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
    public function testExecute($tpjsObligatoire, $tpjsPremierRecrutement)
    {
        /**
         * Aucune config dans TypePieceJointeStatut
         */
        $this->assertIntervenantNotInResult($this->ie);
        
        /**
         * Création d'une config TypePieceJointeStatut
         */
        $tpj = $this->getEntityProvider()->getTypePieceJointe();
        $this->getEntityManager()->flush($tpj);
        $this->createTpjs($tpj, $tpjsObligatoire, $tpjsPremierRecrutement);
        
        /**
         * Si au moins une PJ obligatoire OU facultative est requise, l'intervenant "satisfait" la règle
         */
        $this->assertIntervenantInResult($this->ie);
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