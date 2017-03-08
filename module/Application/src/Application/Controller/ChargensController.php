<?php

namespace Application\Controller;

use Application\Entity\Db\Etape;
use Application\Entity\Db\Scenario;
use Application\Exception\DbException;
use Application\Form\Chargens\Traits\DuplicationScenarioFormAwareTrait;
use Application\Form\Chargens\Traits\FiltreFormAwareTrait;
use Application\Form\Chargens\Traits\ScenarioFormAwareTrait;
use Application\Provider\Chargens\ChargensProviderAwareTrait;
use Application\Service\Traits\ContextAwareTrait;
use Application\Service\Traits\EtapeAwareTrait;
use Application\Service\Traits\ScenarioServiceAwareTrait;
use Application\Service\Traits\StructureAwareTrait;
use BjyAuthorize\Exception\UnAuthorizedException;
use UnicaenApp\View\Model\MessengerViewModel;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;


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
    use ScenarioFormAwareTrait;
    use DuplicationScenarioFormAwareTrait;



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

        $filtre = $this->getFormChargensFiltre();
        if ($etape) $filtre->get('etape')->setValue($etape->getId());
        if ($scenario) $filtre->get('scenario')->setValue($scenario->getId());
        if ($structure) $filtre->get('structure')->setValue($structure->getId());

        return compact('structure', 'etape', 'scenario', 'filtre');
    }



    public function etapeJsonAction()
    {
        $etapesIds = (array)$this->params()->fromPost('etape');

        /** @var Scenario $scenario */
        $scenario = $this->context()->scenarioFromPost();

        $result = ['errors' => []];

        if (empty($etapesIds)) {
            $result['errors'][] = 'La formation n\'est pas précisée';
        }

        if (!$scenario) {
            $result['errors'][] = 'Le scénario n\'est pas précisé';
        }

        if (!empty($result['errors'])) {
            $result['errors'] = implode(', ', $result['errors']);
        }

        if (empty($result['errors'])) {
            $provider = $this->getProviderChargens();

            foreach ($etapesIds as $etapeId) {
                $etape = $this->getServiceEtape()->get($etapeId);
                if ($etape) {
                    $provider->loadEtape($etape);
                }
            }

            $provider->setScenario($scenario);

            if ($data = $this->params()->fromPost('data')) {
                $provider->updateDiagrammeData($data);
            }

            $result = $provider->getDiagrammeData();
        }

        return new JsonModel($result);
    }



    public function enregistrerAction()
    {
        return $this->etapeJsonAction();
    }



    public function scenarioAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            Scenario::class,
        ]);

        $qb = $this->getServiceScenario()->finderByHistorique();
        $this->getServiceScenario()->finderByContext($qb);
        $scenarios = $this->getServiceScenario()->getList($qb);

        $vm = new ViewModel();
        $vm->setTemplate('application/chargens/scenario/index');
        $vm->setVariables( compact('scenarios') );
        return $vm;
    }



    public function scenarioSaisirAction()
    {
        /** @var Scenario $scenario */
        $scenario = $this->getEvent()->getParam('scenario');

        $form = $this->getFormChargensScenario();
        if (empty($scenario)) {
            $title    = 'Création d\'un nouveau scénario';
            $scenario = $this->getServiceScenario()->newEntity();
        } else {
            $title = 'Édition d\'un scénario';
        }

        $form->bindRequestSave($scenario, $this->getRequest(), $this->getServiceScenario());

        $vm = new ViewModel();
        $vm->setTemplate('application/chargens/scenario/saisir');
        $vm->setVariables( compact('form', 'title') );
        return $vm;
    }



    public function scenarioDupliquerAction()
    {
        /** @var Scenario $oldScenario */
        $oldScenario = $this->getEvent()->getParam('scenario');

        $form = $this->getFormChargensDuplicationScenario();
        $title    = 'Duplication du scénario';

        $newScenario = $this->context()->scenarioFromPost('destination');

        if ($oldScenario == $newScenario){
            $this->flashMessenger()->addErrorMessage('Les scénario d\'origine et de destination sont identiques : la duplication ne peut pas avoir lieu.');
            $newScenario = null;
        }

        if ($newScenario){
            $noeuds = $this->params()->fromPost('noeuds');
            $liens = $this->params()->fromPost('liens');
            $this->getServiceScenario()->dupliquer($oldScenario, $newScenario, $noeuds, $liens);
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/chargens/scenario/saisir');
        $vm->setVariables( compact('form', 'title') );
        return $vm;
    }



    public function scenarioSupprimerAction()
    {
        /** @var Scenario $scenario */
        $scenario = $this->getEvent()->getParam('scenario');

        try {
            $this->getServiceScenario()->delete($scenario);
            $this->flashMessenger()->addSuccessMessage("Scénario supprimé avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage(DbException::translate($e)->getMessage());
        }

        return new MessengerViewModel();
    }
}