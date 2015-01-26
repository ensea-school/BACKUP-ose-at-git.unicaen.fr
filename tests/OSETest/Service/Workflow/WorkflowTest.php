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
        
        $this->wf = $this->getServiceManager()->get('WorkflowIntervenant');
    }
    
    public function testCreateSteps()
    {
        $this->assertNotEmpty($this->wf->getRules());
        $this->assertNotEmpty($this->wf->getSteps());
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
            // NB: Un service dû est créé par un trigger Oracle lorsqu'un intervenant permanent est créé.
            //     La suppression de l'intervenant est empêchée par la contrainte de clé étrangère correspondante dans la table SERVICE_DU.
            //     Il n'est pas possible d'ajouter un ON DELETE CASCADE dans la table SERVICE_DU pour une raison obscure.
            //     Donc on a ajouté un "cascade-remove" sur la relation "serviceDu" de l'entité Intervenant.
            //     Mais comme le service dû est créé dans le dos de Doctrine (trigger), un refresh est nécessaire pour que Doctrine 
            //     découvre le ServiceDu lié !
            $this->getEntityManager()->refresh($this->ip);
            
            $this->getEntityManager()->remove($this->ip);
        }
        $this->getEntityManager()->flush();
        $this->getEntityProvider()->removeNewEntities();
    }
}