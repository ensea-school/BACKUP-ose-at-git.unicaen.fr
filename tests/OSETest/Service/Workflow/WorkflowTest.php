<?php

namespace OSETest\Service\Workflow;

use OSETest\BaseTestCase;
use Application\Service\Workflow\Workflow;
use Application\Entity\Db\IntervenantExterieur;

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
            [Workflow::KEY_SAISIE_DOSSIER], 
            [Workflow::KEY_SAISIE_SERVICE], 
            [Workflow::KEY_PIECES_JOINTES], 
            [Workflow::KEY_VALIDATION_DONNEES],
            [Workflow::KEY_VALIDATION_SERVICE],
        ];
    }
    
    /**
     * @dataProvider getRuleKeys
     */
    public function testGetCrossingQuerySQL($key)
    {
        $sql = $this->wf->getCrossingQuerySQL($key);
        $this->assertInternalType('string', $sql);
        $this->assertNotEmpty($sql);
    }
    
    /**
     * @dataProvider getRuleKeys
     */
    public function testExecuteNotCrossingQuerySQLSansRole($key)
    {
//        var_dump(PHP_EOL . PHP_EOL . $key);
        
        $result = $this->wf->executeNotCrossingQuerySQL($key);
        $this->assertInternalType('array', $result);
        
//        $em = $this->wf->getEntityManager();
//        $intervenantIds = array_keys($result);
//        $intervenants = [];
//        foreach ($intervenantIds as $id) {
//            $intervenants[] = "" . $em->find('Application\Entity\Db\Intervenant', $id);
//        }
//        var_dump($intervenants);
    }
    
    public function testSpecifierRole()
    {
        /**
         * Rôle intervenant permanent
         */
        $this->ip = $this->getEntityProvider()->getIntervenantPermanent();
        $this->getEntityManager()->flush();
        
        $roleIp = new \Application\Acl\IntervenantPermanentRole();
        $roleIp->setIntervenant($this->ip);
        
        $this->wf->setRole($roleIp);
        $this->assertNull($this->wf->getStructure());
        
        /**
         * Rôle intervenant extérieur
         */
        $this->ie = $this->getEntityProvider()->getIntervenantExterieur();
        $this->getEntityManager()->flush();
        
        $roleIe = new \Application\Acl\IntervenantPermanentRole();
        $roleIe->setIntervenant($this->ie);
        
        $this->wf->setRole($roleIe);
        $this->assertNull($this->wf->getStructure());
        
        /**
         * Rôle composante
         */
        $structure = $this->getEntityProvider()->getStructureEns();
        
        $roleComp = new \Application\Acl\ComposanteRole();
        $roleComp->setStructure($structure);
        
        $this->wf->setRole($roleComp);
        $this->assertSame($roleComp->getStructure(), $this->wf->getStructure());
    }
}