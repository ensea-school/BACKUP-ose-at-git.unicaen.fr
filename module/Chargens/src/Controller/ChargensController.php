<?php

namespace Chargens\Controller;

use Application\Controller\AbstractController;
use Application\Entity\Db\Annee;
use Application\Provider\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Unicaen\Framework\Authorize\UnAuthorizedException;
use Chargens\Entity\Db\Scenario;
use Chargens\Entity\Db\SeuilCharge;
use Chargens\Form\DifferentielFormAwareTrait;
use Chargens\Form\DuplicationScenarioFormAwareTrait;
use Chargens\Form\FiltreFormAwareTrait;
use Chargens\Form\ScenarioFiltreFormAwareTrait;
use Chargens\Form\ScenarioFormAwareTrait;
use Chargens\Provider\ChargensProviderAwareTrait;
use Chargens\Service\ScenarioServiceAwareTrait;
use Chargens\Service\SeuilChargeServiceAwareTrait;
use Unicaen\Framework\Navigation\Navigation;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use OffreFormation\Entity\Db\Etape;
use OffreFormation\Service\Traits\EtapeServiceAwareTrait;
use UnicaenApp\Util;
use UnicaenApp\View\Model\CsvModel;
use UnicaenApp\View\Model\MessengerViewModel;


/**
 * Description of ChargensController
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class ChargensController extends AbstractController
{
    use ChargensProviderAwareTrait;
    use ContextServiceAwareTrait;
    use EtapeServiceAwareTrait;
    use ScenarioServiceAwareTrait;
    use FiltreFormAwareTrait;
    use ScenarioFiltreFormAwareTrait;
    use ScenarioFormAwareTrait;
    use DuplicationScenarioFormAwareTrait;
    use SeuilChargeServiceAwareTrait;
    use DifferentielFormAwareTrait;

    public function __construct(
        private readonly Navigation $navigation
    )
    {
    }



    public function indexAction(): ViewModel
    {
        $vm = new ViewModel();
        $vm->setTemplate('application/menu.phtml');

        return $vm;
    }



    public function formationAction()
    {
        /** @var Etape $etape */
        $etapeId = $this->params()->fromQuery('etape');
        $etape = $etapeId ? $this->em()->find(Etape::class, $etapeId) : null;

        /** @var Scenario $scenario */
        $scenarioId = $this->params()->fromQuery('scenario');
        $scenario = $scenarioId ? $this->em()->find(Scenario::class, $scenarioId) : null;

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
        $scenarioId = $this->params()->fromPost('scenario');
        $scenario = $this->em()->find(Scenario::class, $scenarioId);

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
            $provider = $this->getProviderChargensChargens();

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
        $vm->setTemplate('chargens/chargens/scenario/index');
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
        $vm->setTemplate('chargens/chargens/scenario/saisir');
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
        $newScenarioId = $this->params()->fromPost('destination');
        $newScenario = $newScenarioId ? $this->em()->find(Scenario::class,$newScenarioId ) : null;

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
        $vm->setTemplate('chargens/chargens/scenario/saisir');
        $vm->setVariables(compact('form', 'title'));

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
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        }

        return new MessengerViewModel();
    }



    public function seuilAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            SeuilCharge::class,
            Scenario::class,
        ]);

        /** @var Scenario $scenario */
        $scenario = $scenario = $this->getEvent()->getParam('scenario');

        $filtre = $this->getFormChargensScenarioFiltre();
        if ($scenario) $filtre->get('scenario')->setValue($scenario->getId());

        if ($scenario) {
            if (($ss = $scenario->getStructure()) && ($cs = $this->getServiceContext()->getStructure())) {
                if (!$ss->inStructure($cs)) {
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
        $scenario = $this->params()->fromRoute('scenario');


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
        $scenario = $scenario = $this->getEvent()->getParam('scenario');

        $provider = $this->getProviderChargensChargens();
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
        $scenario = $scenario = $this->getEvent()->getParam('scenario');

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



    public function differentielAction()
    {
        $form = $this->getFormChargensDifferentiel();

        $avantLibelle = null;
        $apresLibelle = null;

        $diff = null;
        if ($this->getRequest()->isPost()) {
            $post = array_merge_recursive(
                $this->getRequest()->getPost()->toArray(),
                $this->getRequest()->getFiles()->toArray()
            );

            $form->setData($post);
            if ($form->isValid()) {
                $data = $form->getData();
                $pce  = $this->getProviderChargensChargens()->getExport();

                try {
                    if ($data['avant'] == 'export' && isset($data['avant-fichier']['tmp_name'])) {
                        $avant        = $pce->fromCsv($data['avant-fichier']['tmp_name']);
                        $avantLibelle = $data['avant-fichier']['name'];
                    } else {
                        [$avantAnneeId, $avantScenarioId] = explode('-', $data['avant']);
                        /** @var $avantAnnee Annee */
                        $avantAnnee = $this->em()->find(Annee::class, $avantAnneeId);
                        /** @var $avantScenario Scenario */
                        $avantScenario = $this->em()->find(Scenario::class, $avantScenarioId);
                        $avant         = $pce->fromBdd($avantAnnee, $avantScenario, $this->getServiceContext()->getStructure());
                        $avantLibelle  = $avantScenario->getLibelle() . ' ' . $avantAnnee->getLibelle();
                    }

                    if ($data['apres'] == 'export' && isset($data['apres-fichier']['tmp_name'])) {
                        $apres        = $pce->fromCsv($data['apres-fichier']['tmp_name']);
                        $apresLibelle = $data['apres-fichier']['name'];
                    } else {
                        [$apresAnneeId, $apresScenarioId] = explode('-', $data['apres']);
                        /** @var $apresAnnee Annee */
                        $apresAnnee = $this->em()->find(Annee::class, $apresAnneeId);
                        /** @var $apresScenario Scenario */
                        $apresScenario = $this->em()->find(Scenario::class, $apresScenarioId);
                        $apres         = $pce->fromBdd($apresAnnee, $apresScenario, $this->getServiceContext()->getStructure());
                        $apresLibelle  = $apresScenario->getLibelle() . ' ' . $apresAnnee->getLibelle();
                    }

                    $diff = $pce->diff($avant, $apres);
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage($e->getMessage());
                }
            }
        }

        return compact('form', 'diff', 'avantLibelle', 'apresLibelle');
    }



    public function exportCsvAction()
    {
        /** @var Scenario $scenario */
        $scenario = $this->getEvent()->getParam('scenario');

        $annee     = $this->getServiceContext()->getAnnee();
        $structure = $this->getServiceContext()->getStructure();

        $pce = $this->getProviderChargensChargens()->getExport();

        $export   = $pce->fromBdd($annee, $scenario, $structure);
        $csvModel = $pce->toCsv($export);
        $csvModel->setFilename('charges-enseignement-' . $annee->getId() . '-' . Util::reduce($scenario->getLibelle()) . '.csv');

        return $csvModel;
    }



    public function depassementAction()
    {
        $annee     = $this->getServiceContext()->getAnnee();
        $structure = $this->getServiceContext()->getStructure();

        $sql = 'SELECT * FROM V_EXPORT_DEPASS_CHARGES WHERE annee_id = :annee';

        $params = [
            'annee' => $annee->getId(),
        ];
        if ($structure) {
            $sql                 .= ' AND structure_ids LIKE :structure';
            $params['structure'] = $structure->idsFilter();
        }
        $data = $this->em()->getConnection()->fetchAllAssociative($sql, $params);

        $csvModel = new CsvModel();
        $csvModel->setHeader([
            'annee'                         => 'Année',
            'type_volume_horaire_code'      => 'Prév/Réal',
            'intervenant_code'              => 'Code intervenant',
            'intervenant_nom'               => 'Intervenant',
            'intervenant_date_naissance'    => 'Date de naissance',
            'intervenant_statut_libelle'    => 'Statut intervenant',
            'intervenant_type_code'         => 'Type d\'intervenant (Code)',
            'intervenant_type_libelle'      => 'Type d\'intervenant',
            'structure_aff_libelle'         => 'Structure d\'affectation',
            'structure_ens_libelle'         => 'Structure d\'enseignement',
            'groupe_type_formation_libelle' => 'Groupe de type de formation',
            'type_formation_libelle'        => 'Type de formation',
            'etape_niveau'                  => 'Niveau',
            'etape_code'                    => 'Code formation',
            'etape_libelle'                 => 'Formation',
            'element_code'                  => 'Code enseignement',
            'element_libelle'               => 'Enseignement',
            'element_taux_fi'               => 'Taux FI',
            'element_taux_fc'               => 'Taux FC',
            'element_taux_fa'               => 'Taux FA',
            'element_source_libelle'        => 'Source enseignement',
            'periode'                       => 'Période',
            'type_intervention_code'        => 'Type d\'intervention',
            'heures_service'                => 'Heures (service)',
            'source_charges'                => 'Origine des charges',
            'heures_charges'                => 'Volume horaire (charges)',
            'groupes_charges'               => 'Groupes (charges)',
            'heures_depassement'            => 'Dépassement',
        ]);

        foreach ($data as $d) {
            $l = [
                'annee'                         => $d['ANNEE'],
                'type_volume_horaire_code'      => $d['TYPE_VOLUME_HORAIRE_CODE'],
                'intervenant_code'              => $d['INTERVENANT_CODE'],
                'intervenant_nom'               => $d['INTERVENANT_NOM'],
                'intervenant_date_naissance'    => \DateTime::createFromFormat('Y-m-d', substr($d['INTERVENANT_DATE_NAISSANCE'], 0, 10)),
                'intervenant_statut_libelle'    => $d['INTERVENANT_STATUT_LIBELLE'],
                'intervenant_type_code'         => $d['INTERVENANT_TYPE_CODE'],
                'intervenant_type_libelle'      => $d['INTERVENANT_TYPE_LIBELLE'],
                'structure_aff_libelle'         => $d['STRUCTURE_AFF_LIBELLE'],
                'structure_ens_libelle'         => $d['STRUCTURE_ENS_LIBELLE'],
                'groupe_type_formation_libelle' => $d['GROUPE_TYPE_FORMATION_LIBELLE'],
                'type_formation_libelle'        => $d['TYPE_FORMATION_LIBELLE'],
                'etape_niveau'                  => (int)$d['ETAPE_NIVEAU'],
                'etape_code'                    => $d['ETAPE_CODE'],
                'etape_libelle'                 => $d['ETAPE_LIBELLE'],
                'element_code'                  => $d['ELEMENT_CODE'],
                'element_libelle'               => $d['ELEMENT_LIBELLE'],
                'element_taux_fi'               => (float)$d['ELEMENT_TAUX_FI'],
                'element_taux_fc'               => (float)$d['ELEMENT_TAUX_FC'],
                'element_taux_fa'               => (float)$d['ELEMENT_TAUX_FA'],
                'element_source_libelle'        => $d['ELEMENT_SOURCE_LIBELLE'],
                'periode'                       => $d['PERIODE'],
                'type_intervention_code'        => $d['TYPE_INTERVENTION_CODE'],
                'heures_service'                => (float)$d['HEURES_SERVICE'],
                'source_charges'                => $d['SOURCE_CHARGES'],
                'heures_charges'                => (float)$d['HEURES_CHARGES'],
                'groupes_charges'               => (float)$d['GROUPES_CHARGES'],
                'heures_depassement'            => (float)$d['HEURES_DEPASSEMENT'],
            ];

            $csvModel->addLine($l);
        }
        $csvModel->setFilename('depassement-charges-services-' . $annee->getId() . '.csv');

        return $csvModel;
    }
}