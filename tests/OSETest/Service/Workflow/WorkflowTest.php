<?php

namespace OSETest\Service\Workflow;

use OSETest\Service\BaseTest;
use Application\Service\Workflow\Workflow;

/**
 * Description of WorkflowTest
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class WorkflowTest extends BaseTest
{
    /**
     * @var Workflow
     */
    protected $wf;
    
    /**
     * 
     */
    protected function setUp()
    {
        parent::setUp();
        
        $this->wf = $this->getServiceManager()->get('Workflow');
    }
    
    public function testWf()
    {
        $steps = $this->wf->getSteps();
        $this->assertNotEmpty($steps);
        
//        var_dump("------------------------------", $this->wf->getCrossingQuerySQL(Workflow::KEY_SAISIE_DOSSIER));
//        var_dump("------------------------------", $this->wf->getNotCrossingQuerySQL(Workflow::KEY_SAISIE_DOSSIER));
//        var_dump("------------------------------", $this->wf->getCrossingQuerySQL(Workflow::KEY_SAISIE_SERVICE));
//        var_dump("------------------------------", $this->wf->getNotCrossingQuerySQL(Workflow::KEY_SAISIE_SERVICE));
//        var_dump("------------------------------", $this->wf->getCrossingQuerySQL(Workflow::KEY_PIECES_JOINTES));
//        var_dump("------------------------------", $this->wf->getNotCrossingQuerySQL(Workflow::KEY_PIECES_JOINTES));
        
//        var_dump($this->wf->executeNotCrossingQuerySQL(Workflow::KEY_SAISIE_DOSSIER));
//        var_dump("------------------------------", $this->wf->getNotCrossingQuerySQL(Workflow::KEY_SAISIE_SERVICE));
//        var_dump($this->wf->executeNotCrossingQuerySQL(Workflow::KEY_SAISIE_SERVICE));
//        var_dump($this->wf->executeNotCrossingQuerySQL(Workflow::KEY_PIECES_JOINTES));
        
        $em = $this->wf->getEntityManager();
        
        foreach ([Workflow::KEY_SAISIE_DOSSIER, Workflow::KEY_SAISIE_SERVICE, Workflow::KEY_PIECES_JOINTES] as $key) {
            var_dump(PHP_EOL . PHP_EOL . $key);
            $intervenantIds = array_keys($this->wf->executeNotCrossingQuerySQL($key));
            $intervenants = [];
            foreach ($intervenantIds as $id) {
                $intervenants[] = "" . $em->find('Application\Entity\Db\Intervenant', $id);
            }
            var_dump($intervenants);
        }
    }
}