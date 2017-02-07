<?php

namespace Application\Form\Chargens;

use Application\Entity\Db\Etape;
use Application\Entity\Db\Scenario;
use Application\Entity\Db\Structure;
use Application\Form\AbstractForm;
use Application\Service\Traits\ContextAwareTrait;
use Application\Service\Traits\EtapeAwareTrait;
use Application\Service\Traits\ScenarioServiceAwareTrait;
use Application\Service\Traits\StructureAwareTrait;
use UnicaenApp\Util;


/**
 * Description of FiltreForm
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class FiltreForm extends AbstractForm
{
    use ContextAwareTrait;
    use StructureAwareTrait;
    use EtapeAwareTrait;
    use ScenarioServiceAwareTrait;

    /**
     * @var Etape[]
     */
    private $etapes;

    /**
     * @var Structure[]
     */
    private $structures;

    /**
     * @var Scenario[]
     */
    private $scenarios;

    /**
     * @var array
     */
    private $structuresEtapes;

    /**
     * @var array
     */
    private $structuresScenarios;


    public function init()
    {
        $this->loadData();

        $this->setAttributes([
            'action' => $this->getCurrentUrl(),
            'data-structures-etapes' => json_encode($this->structuresEtapes),
            'data-structures-scenarios' => json_encode($this->structuresScenarios),
        ]);

        $this->add([
            'name'       => 'structure',
            'options'    => [
                'label'                     => "Composante :",
                'disable_inarray_validator' => true,
                'label_attributes'          => [
                    'title' => "Structure gestionnaire de l'enseignement",
                ],
                'value_options' => Util::collectionAsOptions($this->structures),
            ],
            'attributes' => [
                'id'               => 'structure',
                'title'            => "Composante ...",
                'class'            => 'input-sm selectpicker',
                'data-width'       => "100%",
                'data-live-search' => "true",
            ],
            'type'       => 'Select',
        ]);

        $this->add([
            'name'       => 'etape',
            'options'    => [
                'label'                     => "Formation :",
                'disable_inarray_validator' => true,
                'label_attributes'          => [
                    'title' => "Formation",
                ],
                'value_options' => Util::collectionAsOptions($this->etapes),
            ],
            'attributes' => [
                'id'               => 'formation',
                'title'            => "Formation ...",
                'class'            => 'input-sm selectpicker',
                'data-width'       => "100%",
                'data-live-search' => "true",
            ],
            'type'       => 'Select',
        ]);

        $this->add([
            'name'       => 'scenario',
            'options'    => [
                'label'                     => "Scénario :",
                'disable_inarray_validator' => true,
                'label_attributes'          => [
                    'title' => "Scénario",
                ],
                'value_options' => Util::collectionAsOptions($this->scenarios),
            ],
            'attributes' => [
                'id'               => 'scenario',
                'title'            => "Scénario ...",
                'class'            => 'input-sm selectpicker',
                'data-width'       => "100%",
                'data-live-search' => "true",
            ],
            'type'       => 'Select',
        ]);


        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => 'Afficher',
                'class' => 'btn btn-primary',
            ],
        ]);
    }



    private function loadData()
    {
        $cStructure = $this->getServiceContext()->getStructure();

        if ($cStructure) {
            $this->structures = [$cStructure];
        } else {
            $qb = $this->getServiceStructure()->finderByHistorique();
            $this->getServiceStructure()->finderByEnseignement($qb);
            $this->structures = $this->getServiceStructure()->getList($qb);
        }

        $qb = $this->getServiceEtape()->finderByHistorique();
        $this->getServiceEtape()->finderByContext($qb);
        $this->etapes = $this->getServiceEtape()->getList($qb);

        $qb = $this->getServiceScenario()->finderByHistorique();
        $this->getServiceScenario()->finderByContext($qb);
        $this->scenarios = $this->getServiceScenario()->getList($qb);

        $sEtapes = [];
        foreach ($this->etapes as $etape) {
            $sid = $etape->getStructure()->getId();
            if (!isset($sEtapes[$sid])) {
                $sEtapes[$sid] = [];
            }
            $sEtapes[$sid][] = $etape->getId();
        }

        $sScenarios = [];
        foreach ($this->scenarios as $scenario) {
            $sid = $scenario->getStructure() ? $scenario->getStructure()->getId() : 0;
            if (!isset($sScenarios[$sid])) {
                $sScenarios[$sid] = [];
            }
            $sScenarios[$sid][] = $scenario->getId();
        }

        $this->structuresEtapes = $sEtapes;
        $this->structuresScenarios = $sScenarios;

        return $this;
    }



    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            /* Filtres et validateurs */
        ];
    }

}
