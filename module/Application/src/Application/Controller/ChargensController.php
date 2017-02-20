<?php

namespace Application\Controller;

use Application\Entity\Db\Etape;
use Application\Entity\Db\Scenario;
use Application\Form\Chargens\Traits\FiltreFormAwareTrait;
use Application\Provider\Chargens\ChargensProviderAwareTrait;
use Application\Service\Traits\ContextAwareTrait;
use Application\Service\Traits\EtapeAwareTrait;
use Application\Service\Traits\ScenarioServiceAwareTrait;
use Application\Service\Traits\StructureAwareTrait;
use BjyAuthorize\Exception\UnAuthorizedException;
use Zend\View\Model\JsonModel;


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
        $etape = $this->context()->etapeFromQuery();
        /** @var Scenario $scenario */
        $scenario = $this->context()->scenarioFromQuery();

        $contextStructure = $this->getServiceContext()->getStructure();

        if ($etape) {
            $structure = $etape->getStructure();

            if ($contextStructure && $contextStructure !== $structure) {
                throw new UnAuthorizedException('La formation sélectionnée n\'est pas gérée par votre composante');
            }
        } else {
            $structure = $contextStructure;
        }

        $filtre   = $this->getFormChargensFiltre();
        if ($etape) $filtre->get('etape')->setValue($etape->getId());
        if ($scenario) $filtre->get('scenario')->setValue($scenario->getId());
        if ($structure) $filtre->get('structure')->setValue($structure->getId());

        return compact('structure', 'etape', 'scenario', 'filtre');
    }



    public function etapeJsonAction()
    {
        /** @var Etape $etape */
        $etape = $this->context()->etapeFromPost();
        /** @var Scenario $scenario */
        $scenario = $this->context()->scenarioFromPost();

        $result = ['errors' => []];

        if (!$etape){
            $result['errors'][] = 'La formation n\'est pas précisée';
        }

        if (!$scenario){
            $result['errors'][] = 'Le scénario n\'est pas précisé';
        }

        if (!empty($result['errors'])){
            $result['errors'] = implode( ', ', $result['errors'] );
        }

        if (empty($result['errors'])){
            $provider = $this->getProviderChargens();

            if ($data = $this->params()->fromPost('data')){
                $provider->enregistrer( $data );
            }

            $provider->loadEtape($etape);
            $provider->loadScenario($scenario);

            $result['noeuds'] = $provider->noeudsToArray();
            $result['liens'] = $provider->liensToArray();
        }

        return new JsonModel($result);
    }



    public function enregistrerAction()
    {
        return $this->etapeJsonAction();
    }

}