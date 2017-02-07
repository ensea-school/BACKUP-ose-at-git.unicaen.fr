<?php

namespace Application\Controller;

use Application\Entity\Db\Etape;
use Application\Entity\Db\Scenario;
use Application\Entity\Db\Structure;
use Application\Form\Chargens\Traits\FiltreFormAwareTrait;
use Application\Provider\Chargens\ChargensProviderAwareTrait;
use Application\Service\Traits\ContextAwareTrait;
use Application\Service\Traits\EtapeAwareTrait;
use Application\Service\Traits\ScenarioServiceAwareTrait;
use Application\Service\Traits\StructureAwareTrait;


/**
 * Description of ChargensController
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class ChargensController extends AbstractController
{
    use ChargensProviderAwareTrait;
    use ContextAwareTrait;
    use StructureAwareTrait;
    use EtapeAwareTrait;
    use ScenarioServiceAwareTrait;
    use FiltreFormAwareTrait;



    public function indexAction()
    {
        /** @var Etape $etape */
        $etape = $this->getEvent()->getParam('etape');
        /** @var Scenario $scenario */
        $scenario = $this->getEvent()->getParam('scenario');

        if ($etape){
            $structure = $etape->getStructure();
        }else{
            /** @var Structure $structure */
            $structure = $this->getEvent()->getParam('structure');
        }

        $provider = $this->getProviderChargens();
        $filtre = $this->getFormChargensFiltre();

        if ($etape){
            $provider->loadEtape($etape);
            $filtre->get('etape')->setValue($etape->getId());
        }
        if ($scenario){
            $provider->loadScenario( $scenario );
            $filtre->get('scenario')->setValue($scenario->getId());
        }
        if ($structure){
            $filtre->get('structure')->setValue($structure->getId());
        }

        return compact('structure', 'etape', 'scenario', 'provider', 'filtre');
    }



    public function scenarioAction()
    {
        $noeuds = $this->params()->fromPost('noeuds');


        /** @var Scenario $scenario */
        $scenario = $this->getEvent()->getParam('scenario');
    }

}