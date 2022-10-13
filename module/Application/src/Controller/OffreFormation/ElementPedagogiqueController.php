<?php

namespace Application\Controller\OffreFormation;

use Application\Controller\AbstractController;
use Application\Entity\Db\ElementPedagogique;
use Application\Filter\FloatFromString;
use Application\Form\OffreFormation\Traits\ElementModulateurCentreCoutFormAwareTrait;
use Application\Form\OffreFormation\Traits\ElementPedagogiqueSaisieAwareTrait;
use Application\Form\OffreFormation\Traits\ElementPedagogiqueSynchronisationFormAwareTrait;
use Application\Form\OffreFormation\Traits\VolumeHoraireEnsFormAwareTrait;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\CentreCoutEpServiceAwareTrait;
use Application\Service\Traits\ElementModulateurServiceAwareTrait;
use Application\Service\Traits\ElementPedagogiqueServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\StructureServiceAwareTrait;
use Application\Service\Traits\VolumeHoraireEnsServiceAwareTrait;
use UnicaenImport\Service\Traits\SchemaServiceAwareTrait;

/**
 * Description of ElementPedagogiqueController
 *
 */
class ElementPedagogiqueController extends AbstractController
{
    use ElementPedagogiqueServiceAwareTrait;
    use ContextServiceAwareTrait;
    use ElementPedagogiqueSaisieAwareTrait;
    use VolumeHoraireEnsFormAwareTrait;
    use VolumeHoraireEnsServiceAwareTrait;
    use ElementModulateurCentreCoutFormAwareTrait;
    use ElementModulateurServiceAwareTrait;
    use CentreCoutEpServiceAwareTrait;
    use ElementPedagogiqueSynchronisationFormAwareTrait;
    use StructureServiceAwareTrait;
    use SchemaServiceAwareTrait;


    public function voirAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            \Application\Entity\Db\CheminPedagogique::class,
            \Enseignement\Entity\Db\VolumeHoraire::class,
        ]);
        $element       = $this->getEvent()->getParam('elementPedagogique');
        $title         = $element->getLibelle() . ' (' . $element->getCode() . ')';
        $serviceSchema = $this->getServiceSchema();

        return compact('element', 'title', 'serviceSchema');
    }



    protected function saisirAction()
    {
        $element = $this->getEvent()->getParam('elementPedagogique');
        $title   = $element ? "Modification d'un enseignement" : "Création d'un enseignement";
        $form    = $this->getFormOffreFormationElementPedagogiqueSaisie();
        $errors  = [];

        if ($element) {
            $form->bind($element);
        } else {
            $element = $this->getServiceElementPedagogique()->newEntity();
            $form->setObject($element);
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                try {
                    $this->getServiceElementPedagogique()->save($element);
                    $form->get('id')->setValue($element->getId()); // transmet le nouvel ID
                } catch (\Exception $e) {
                    $errors[] = $this->translate($e);
                }
            }
        }

        return compact('form', 'title', 'errors');
    }



    public function supprimerAction()
    {
        $element = $this->getEvent()->getParam('elementPedagogique');
        if (!$element) {
            throw new \RuntimeException('L\'identifiant n\'est pas bon ou n\'a pas été fourni');
        }

        $title = "Suppression d'enseignement";
        $form  = $this->makeFormSupprimer(function () use ($element) {
            $this->getServiceElementPedagogique()->delete($element);
        });

        return compact('element', 'title', 'form');
    }



    /**
     * Action pour rechercher des éléments pédagogiques.
     *
     * Les filtres pris en compte sont :
     * - structure du contexte local,
     * - niveau du contexte local,
     * - étape du contexte local,
     * Éventuellement écrasés par ceux-là :
     * - paramètre GET 'structure' (id d'une structure),
     * - paramètre GET 'niveau' (ex: 'L-2'),
     * - paramètre GET 'etape' (id d'une étape),
     *
     * NB: Les résultats sont renvoyés au format JSON.
     *
     * @return \Laminas\View\Model\JsonModel
     */
    public function searchAction()
    {
        $this->em()->getFilters()->enable('annee')->init([
            \Application\Entity\Db\ElementPedagogique::class,
            \Application\Entity\Db\Etape::class,
        ]);

        $structure = $this->context()->structureFromQuery();
        $niveau    = $this->context()->niveauFromQuery();
        $etape     = $this->context()->etapeFromQuery();
        $element   = $this->context()->elementPedagogiqueFromQuery();
        $term      = $this->params()->fromQuery('term');

        if (!$etape && !$term) {
            exit;
        }

        $limit = $etape ? 9999999 : 101;

        // respect des filtres éventuels spécifiés en GET ou sinon en session
        $params              = [];
        $params['structure'] = $structure;
        $params['niveau']    = $niveau;
        $params['etape']     = $etape;
        $params['element']   = $element;
        $params['term']      = $term;
        $params['limit']     = $limit;

        // fetch
        $found = $this->getServiceElementPedagogique()->getSearchResultByTerm($params, $term === null ? 'ep.code' : 'gtf.ordre, e.niveau, ep.code');

        $result        = [];
        $codeIteration = [];
        foreach ($found as $item) {
            if (null === $term) {
                if (0 === strpos($item['LIBELLE'], $item['CODE'])) {
                    $label = $item['LIBELLE'];
                } else {
                    $label = $item['CODE'] . ' ' . $item['LIBELLE'];
                }
                $result[] = [
                    'id'    => $item['ID'],
                    'label' => $label,
                    'extra' => $item['LIBELLE_PE'] ?: '',
                ];
            } else {
                $label = $item['CODE'] . ' ' . $item['LIBELLE'];
                if (in_array($label, $codeIteration)) {
                    continue;
                }
                $codeIteration[] = $item['CODE'] . ' ' . $item['LIBELLE'];

                if ($item['NB_CH'] > 1) {
                    $item['LIBELLE_ETAPE'] = 'Enseignement commun à plusieurs parcours';
                }

                $extra = '';
                if (!$niveau) {
                    $extra .= sprintf('<span class="element-rech niveau" title="%s">%s</span>', "Niveau", $item['LIBELLE_GTF'] . $item['NIVEAU']);
                }
                if (!$etape) {
                    $extra .= sprintf('<span class="element-rech etape" title="%s">%s</span>', "Formation", $item['LIBELLE_ETAPE']);
                }
                $extra .= "Année" !== $item['LIBELLE_PE'] ? sprintf('<span class="element-rech periode" title="%s">%s</span>', "Période", $item['LIBELLE_PE']) : null;

                $template = sprintf('<span class="element-rech extra">{extra}</span><span class="element-rech element" title="%s">{label}</span>', "Enseignement");
                $result[] = [
                    'id'       => $item['ID'],
                    'label'    => $item['CODE'] . ' ' . $item['LIBELLE'],
                    'extra'    => $extra,
                    'template' => $template,
                ];
            }
        };

        $result = \UnicaenApp\Form\Element\SearchAndSelect::truncatedResult($result, $limit - 1);

        return new \Laminas\View\Model\JsonModel($result);
    }



    public function getPeriodeAction()
    {
        $elementPedagogique = $this->params()->fromRoute('elementPedagogique');
        $code               = null;
        if ($elementPedagogique) {
            if ($periode = $elementPedagogique->getPeriode()) {
                $code = $periode->getCode();
            }
        }
        $result = ['periode' => ['code' => $code]];

        return new \Laminas\View\Model\JsonModel($result);
    }



    public function volumeHoraireAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            \Enseignement\Entity\Db\VolumeHoraireEns::class,
        ]);

        $title = 'Volumes horaires';

        /** @var ElementPedagogique $element */
        $element = $this->getEvent()->getParam('elementPedagogique');

        $ev         = $element->getVolumeHoraireEns();
        $existsVhes = [];
        foreach ($ev as $vhe) {
            $existsVhes[$vhe->getTypeIntervention()->getId()] = $vhe;
        }

        $saisie = $this->params()->fromPost('vhes');

        $tis  = $element->getTypesInterventionPossibles();
        $vhes = [];
        foreach ($tis as $typeIntervention) {
            if (!isset($existsVhes[$typeIntervention->getId()])) {
                $vhe = $this->getServiceVolumeHoraireEns()->newEntity($element, $typeIntervention);
            } else {
                $vhe = $existsVhes[$typeIntervention->getId()];
            }

            if ($this->getRequest()->isPost() && $this->isAllowed($vhe, Privileges::ODF_ELEMENT_VH_EDITION)) {
                if (isset($saisie[$vhe->getTypeIntervention()->getId()]['heures'])) {
                    $heures = FloatFromString::run($saisie[$vhe->getTypeIntervention()->getId()]['heures']);
                } else {
                    $heures = null;
                }
                if (isset($saisie[$vhe->getTypeIntervention()->getId()]['groupes'])) {
                    $groupes = FloatFromString::run($saisie[$vhe->getTypeIntervention()->getId()]['groupes']);
                } else {
                    $groupes = null;
                }
                try {
                    $this->getServiceVolumeHoraireEns()->changeHeuresGroupes($vhe, $heures, $groupes);
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage($this->translate($e));
                }
            }

            if (!$vhe->estNonHistorise()) {
                $vhe = $this->getServiceVolumeHoraireEns()->newEntity($element, $typeIntervention);
            }

            $vhes[$typeIntervention->getId()] = $vhe;
        }

        $form = $this->getFormOffreFormationVolumeHoraireEns();
        $form->setAttribute('action', $this->url()->fromRoute('of/element/volume-horaire', ['elementPedagogique' => $element->getId()]));
        $form->build($vhes);

        return compact('title', 'vhes', 'form');
    }



    public function modulateursCentresCoutsAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            \Application\Entity\Db\ElementModulateur::class,
            \Application\Entity\Db\CentreCout::class,
            \Application\Entity\Db\CentreCoutEp::class,
        ]);

        $element = $this->getEvent()->getParam('elementPedagogique');
        $form    = $this->getFormOffreFormationElementModulateurCentreCout();
        //Traitement retour formulaire
        $request = $this->getRequest();
        if ($request->isPost()) {
            $datasPost = $request->getPost();
            //Modulateur
            foreach ($datasPost as $name => $value) {
                if (strstr($name, 'modulateur')) {
                    $element = $this->getServiceElementModulateur()->addElementModulateur($element, $datasPost[$name]);
                }
            }
            //Centres de coûts
            $centreCouts = [
                'fi' => $datasPost['fi'],
                'fa' => $datasPost['fa'],
                'fc' => $datasPost['fc'],
            ];
            $element     = $this->getServiceCentreCoutEp()->addElementCentreCout($element, $centreCouts);
        }

        $form->setElementPedagogique($element);
        $form->setAttribute('action', $this->url()->fromRoute('of/element/modulateurs-centres-couts', ['elementPedagogique' => $element->getId()]));
        $form->buildElements();

        return [
            'form' => $form,
        ];
    }



    public function synchronisationAction()
    {
        $element = $this->getEvent()->getParam('elementPedagogique');
        $this->getServiceElementPedagogique()->synchronisation($element);

        return $this->redirect()->toRoute('of/element/voir', [], ['query' => ['modal' => 1]], true);
    }



    public function synchronisationParCodeAction()
    {
        $title = 'Import d\'un nouvel élément pédagogique';

        if (!$this->getRequest()->isPost()) {
            $structure = $this->params()->fromQuery('structure');
            if ($structure) $structure = $this->getServiceStructure()->get($structure);
            $form = $this->getFormOffreFormationElementPedagogiqueSynchronisation();
            $form->setStructure($this->getServiceContext()->getStructure(true) ?: $structure);
            $form->populate();

            return compact('form', 'title');
        } else {
            $form = null;
            $code = $this->params()->fromPost('code');
            $this->getServiceElementPedagogique()->synchronisation($code);

            return compact('form', 'title');
        }
    }
}
