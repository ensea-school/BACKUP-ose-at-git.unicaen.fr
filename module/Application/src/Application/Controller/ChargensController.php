<?php

namespace Application\Controller;

use Application\Entity\Db\Etape;
use Application\Entity\Db\Scenario;
use Application\Entity\Db\SeuilCharge;
use Application\Form\Chargens\Traits\DuplicationScenarioFormAwareTrait;
use Application\Form\Chargens\Traits\FiltreFormAwareTrait;
use Application\Form\Chargens\Traits\ScenarioFiltreFormAwareTrait;
use Application\Form\Chargens\Traits\ScenarioFormAwareTrait;
use Application\Provider\Chargens\ChargensProviderAwareTrait;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\ContextAwareTrait;
use Application\Service\Traits\EtapeAwareTrait;
use Application\Service\Traits\ScenarioServiceAwareTrait;
use Application\Service\Traits\SeuilChargeServiceAwareTrait;
use Application\Service\Traits\StructureAwareTrait;
use BjyAuthorize\Exception\UnAuthorizedException;
use UnicaenApp\Util;
use UnicaenApp\View\Model\CsvModel;
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
    use ScenarioFiltreFormAwareTrait;
    use ScenarioFormAwareTrait;
    use DuplicationScenarioFormAwareTrait;
    use SeuilChargeServiceAwareTrait;



    public function indexAction()
    {
        return [];
    }



    public function formationAction()
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



    public function formationJsonAction()
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



    public function formationEnregistrerAction()
    {
        return $this->formationJsonAction();
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
        $vm->setVariables(compact('scenarios'));

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
        $vm->setVariables(compact('form', 'title'));

        return $vm;
    }



    public function scenarioDupliquerAction()
    {
        /** @var Scenario $oldScenario */
        $oldScenario = $this->getEvent()->getParam('scenario');

        $form  = $this->getFormChargensDuplicationScenario();
        $title = 'Duplication du scénario';

        /** @var Scenario $newScenario */
        $newScenario = $this->context()->scenarioFromPost('destination');

        if ($oldScenario == $newScenario) {
            $this->flashMessenger()->addErrorMessage('Les scénario d\'origine et de destination sont identiques : la duplication ne peut pas avoir lieu.');
            $newScenario = null;
        }

        $cStructure = $this->getServiceContext()->getStructure();
        $sStructure = $newScenario ? $newScenario->getStructure() : null;
        if ($cStructure && $sStructure && $cStructure != $sStructure) {
            $this->flashMessenger()->addErrorMessage('Vous ne pouvez pas dupliquer ces données vers un scénario qui n\'appartient pas à votre composante');
            $newScenario = null;
        }

        if ($newScenario) {
            $noeuds = $this->params()->fromPost('noeuds');
            $liens  = $this->params()->fromPost('liens');
            try {
                $this->getServiceScenario()->dupliquer($oldScenario, $newScenario, $noeuds, $liens);
                $this->flashMessenger()->addSuccessMessage('Le scénario a bien été dupliqué');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($e->getMessage());
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/chargens/scenario/saisir');
        $vm->setVariables(compact('form', 'title'));

        return $vm;
    }



    public function scenarioSupprimerAction()
    {
        /** @var Scenario $scenario */
        $scenario = $this->getEvent()->getParam('scenario');

        $form = $this->getFormChargensScenario();
        $form->delete($scenario, $this->getServiceScenario(), "Scénario supprimé avec succès.");

        return new MessengerViewModel();
    }



    public function seuilAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            SeuilCharge::class,
            Scenario::class,
        ]);

        /** @var Scenario $scenario */
        $scenario = $this->context()->scenarioFromRoute();

        $filtre = $this->getFormChargensScenarioFiltre();
        if ($scenario) $filtre->get('scenario')->setValue($scenario->getId());

        if ($scenario) {
            if (($ss = $scenario->getStructure()) && ($cs = $this->getServiceContext()->getStructure())) {
                if ($ss != $cs) {
                    throw new UnAuthorizedException('Les données appartiennent à une autre composante. Vous ne pouvez pas y accéder');
                }
            }

            $seuils = $this->getServiceSeuilCharge()->getSeuils($scenario);
        } else {
            $seuils = [];
        }

        return compact('scenario', 'seuils', 'filtre');
    }



    public function seuilModifierAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            SeuilCharge::class,
        ]);

        /** @var Scenario $scenario */
        $scenario = $this->context()->scenarioFromRoute();


        $typeIntervention    = stringToInt($this->params()->fromPost('typeIntervention'));
        $groupeTypeFormation = stringToInt($this->params()->fromPost('groupeTypeFormation'));
        $structure           = stringToInt($this->params()->fromPost('structure'));
        $dedoublement        = stringToInt($this->params()->fromPost('dedoublement'));

        $canEditEtab = $this->isAllowed(Privileges::getResourceId(Privileges::CHARGENS_SEUIL_ETABLISSEMENT_EDITION));
        $canEditcomp = $this->isAllowed(Privileges::getResourceId(Privileges::CHARGENS_SEUIL_COMPOSANTE_EDITION));

        $canEdit = ($structure && $canEditcomp) || (!$structure && $canEditEtab);

        if ($canEdit) {
            try {
                $this->getServiceSeuilCharge()->saveBy($scenario, $structure, $groupeTypeFormation, $typeIntervention, $dedoublement);
                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($e->getMessage());
            }
        } else {
            $this->flashMessenger()->addErrorMessage('Ce seuil ne peut pas être modifié');
        }

        return new MessengerViewModel();
    }



    public function seuilCalcHeuresAction()
    {
        /** @var Scenario $scenario */
        $scenario = $this->context()->scenarioFromRoute();

        $provider = $this->getProviderChargens();
        $provider->setScenario($scenario);

        $result = $provider->getHeuresFi();

        return new JsonModel($result);
    }



    public function exportAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            SeuilCharge::class,
            Scenario::class,
        ]);

        /** @var Scenario $scenario */
        $scenario = $this->context()->scenarioFromRoute();

        $filtre = $this->getFormChargensScenarioFiltre();
        if ($scenario) $filtre->get('scenario')->setValue($scenario->getId());

        if ($scenario) {
            if (($ss = $scenario->getStructure()) && ($cs = $this->getServiceContext()->getStructure())) {
                if ($ss != $cs) {
                    throw new UnAuthorizedException('Les données appartiennent à une autre composante. Vous ne pouvez pas y accéder');
                }
            }

            $seuils = $this->getServiceSeuilCharge()->getSeuils($scenario);
        } else {
            $seuils = [];
        }

        return compact('scenario', 'seuils', 'filtre');
    }



    public function exportCsvAction()
    {
        /** @var Scenario $scenario */
        $scenario = $this->context()->scenarioFromRoute();

        $annee     = $this->getServiceContext()->getAnnee();
        $structure = $this->getServiceContext()->getStructure();

        $sql    = 'SELECT * FROM V_CHARGENS_EXPORT_CSV WHERE scenario_id = :scenario AND annee_id = :annee';
        $params = [
            'scenario' => $scenario->getId(),
            'annee'    => $annee->getId(),
        ];
        if ($structure) {
            $sql                 .= ' AND structure_id = :structure';
            $params['structure'] = $structure->getId();
        }
        $data = $this->em()->getConnection()->fetchAll($sql, $params);

        $csvModel = new CsvModel();
        $csvModel->setHeader([
            'annee'                      => 'Année',
            'structure-porteuse-code'    => 'Composante porteuse (code)',
            'structure-porteuse-libelle' => 'Composante porteuse (libellé)',
            'etape-porteuse-code'        => 'Étape porteuse (code)',
            'etape-porteuse-libelle'     => 'Étape porteuse (libellé)',

            'structure-ins-code'    => 'Composante d\'inscription (code)',
            'structure-ins-libelle' => 'Composante d\'inscription (libellé)',
            'etape-ins-code'        => 'Étape d\'inscription (code)',
            'etape-ins-libelle'     => 'Étape d\'inscription (libellé)',

            'element-code'       => 'Ens. (code)',
            'element-libelle'    => 'Enseignement (libellé)',
            'discipline-code'    => 'Discipline (code)',
            'discipline-libelle' => 'Discipline (libellé)',
            'type-heures'        => 'Type d\'heures',
            'type-intervention'  => 'Type d\'intervention',

            'seuil-ouverture'    => 'Seuil d\'ouverture',
            'seuil-dedoublement' => 'Seuil de dédoublement',
            'effectif-etape'     => 'Effectifs (étape)',
            'effectif-element'   => 'Effectifs (élément)',
            'heures-ens'         => 'Vol. Horaire',
            'groupes'            => 'Groupes',
            'heures'             => 'Heures',
            'hetd'               => 'HETD',
        ]);

        foreach ($data as $d) {
            $l = [
                'annee'                      => $d['ANNEE'],
                'structure-porteuse-code'    => $d['STRUCTURE_PORTEUSE_CODE'],
                'structure-porteuse-libelle' => $d['STRUCTURE_PORTEUSE_LIBELLE'],
                'etape-porteuse-code'        => $d['ETAPE_PORTEUSE_CODE'],
                'etape-porteuse-libelle'     => $d['ETAPE_PORTEUSE_LIBELLE'],

                'structure-ins-code'    => $d['STRUCTURE_INS_CODE'],
                'structure-ins-libelle' => $d['STRUCTURE_INS_LIBELLE'],
                'etape-ins-code'        => $d['ETAPE_INS_CODE'],
                'etape-ins-libelle'     => $d['ETAPE_INS_LIBELLE'],

                'element-code'       => $d['ELEMENT_CODE'],
                'element-libelle'    => $d['ELEMENT_LIBELLE'],
                'discipline-code'    => $d['DISCIPLINE_CODE'],
                'discipline-libelle' => $d['DISCIPLINE_LIBELLE'],
                'type-heures'        => $d['TYPE_HEURES'],
                'type-intervention'  => $d['TYPE_INTERVENTION'],

                'seuil-ouverture'    => (int)$d['SEUIL_OUVERTURE'],
                'seuil-dedoublement' => (int)$d['SEUIL_DEDOUBLEMENT'],
                'effectif-etape'     => (int)$d['EFFECTIF_ETAPE'],
                'effectif-element'   => (int)$d['EFFECTIF_ELEMENT'],
                'heures-ens'         => (float)$d['HEURES_ENS'],
                'groupes'            => (float)$d['GROUPES'],
                'heures'             => (float)$d['HEURES'],
                'hetd'               => (float)$d['HETD'],
            ];
            $csvModel->addLine($l);
        }
        $csvModel->setFilename('charges-enseignement-' . $annee->getId() . '-' . Util::reduce($scenario->getLibelle()) . '.csv');

        return $csvModel;
    }
}