<?php

namespace OSETest\Service\Workflow;

use Application\Acl\ComposanteRole;
use Application\Acl\IntervenantPermanentRole;
use Application\Entity\Db\IntervenantPermanent;
use Application\Entity\Db\IntervenantExterieur;
use Application\Service\Workflow\Workflow;
use OSETest\BaseTestCase;

/**
 * Description of WorkflowTest
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class WorkflowTest extends BaseTestCase
{
    /**
     * @var Workflow
     */
    protected $wf;
    
    /**
     * @var IntervenantExterieur 
     */
    protected $ie;
    
    /**
     * @var IntervenantPermanent
     */
    protected $ip;
    
    /**
     * 
     */
    protected function setUp()
    {
        parent::setUp();
        
        $this->wf = $this->getServiceManager()->get('Workflow');
    }
    
    public function getRuleKeys()
    {
        return [
//            [Workflow::KEY_DONNEES_PERSO_SAISIE], 
//            [Workflow::KEY_SERVICE_SAISIE], 
//            [Workflow::KEY_PIECES_JOINTES], 
//            [Workflow::KEY_DONNEES_PERSO_VALIDATION],
//            [Workflow::KEY_SERVICE_VALIDATION],
            [Workflow::KEY_CONSEIL_RESTREINT],
            [Workflow::KEY_CONSEIL_ACADEMIQUE],
            [Workflow::KEY_CONTRAT],
        ];
    }
    
    /**
     * @dataProvider getRuleKeys
     */
    public function testGetCrossingQuerySQL($stepKey)
    {
        $sql = $this->wf->getCrossingQuerySQL($stepKey);
        $this->assertInternalType('string', $sql);
        $this->assertNotEmpty($sql);
        
//        var_dump($this->wf->getNotCrossingQuerySQL(Workflow::KEY_CONSEIL_RESTREINT));
    }
    
    /**
     * @dataProvider getRuleKeys
     */
    public function testExecuteNotCrossingQuerySQLSansRole($stepKey)
    {
        $result = $this->wf->executeNotCrossingQuerySQL($stepKey);
        $this->assertInternalType('array', $result);
        
        var_dump(PHP_EOL . "=================== " . $stepKey . " =================== " . PHP_EOL);
        var_dump($this->wf->getNotCrossingQuerySQL($stepKey));
        $em = $this->wf->getEntityManager();
        $intervenantIds = array_keys($result);
        $intervenants = [];
        foreach ($intervenantIds as $id) {
            $intervenants[] = "" . $em->find('Application\Entity\Db\Intervenant', $id);
        }
        var_dump($intervenants);
    }
    
    public function testSpecifierUnRoleImpacteLaStructure()
    {
        /**
         * Rôle intervenant permanent
         */
        $this->ip = $this->getEntityProvider()->getIntervenantPermanent();
        $this->getEntityManager()->flush();
        
        $roleIp = new IntervenantPermanentRole();
        $roleIp->setIntervenant($this->ip);
        
        $this->wf->setRole($roleIp);
        $this->assertNull($this->wf->getStructure());
        
        /**
         * Rôle intervenant extérieur
         */
        $this->ie = $this->getEntityProvider()->getIntervenantExterieur();
        $this->getEntityManager()->flush();
        
        $roleIe = new IntervenantPermanentRole();
        $roleIe->setIntervenant($this->ie);
        
        $this->wf->setRole($roleIe);
        $this->assertNull($this->wf->getStructure());
        
        /**
         * Rôle composante
         */
        $structure = $this->getEntityProvider()->getStructureEns();
        
        $roleComp = new ComposanteRole();
        $roleComp->setStructure($structure);
        
        $this->wf->setRole($roleComp);
        $this->assertSame($roleComp->getStructure(), $this->wf->getStructure());
    }
    
    protected function tearDown()
    {
        parent::tearDown();
        
        /**
         * Suppression du jeu d'essai
         */
        if ($this->ie) {
            $this->getEntityManager()->remove($this->ie);
        }
        if ($this->ip) {
            $this->getEntityManager()->remove($this->ip);
        }
        $this->getEntityManager()->flush();
        $this->getEntityProvider()->removeNewEntities();
    }
}