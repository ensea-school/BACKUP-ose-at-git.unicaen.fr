<?php

namespace Chargens\Form;

use Application\Form\AbstractForm;
use Chargens\Service\ScenarioServiceAwareTrait;


/**
 * Description of ScenarioFiltreForm
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class ScenarioFiltreForm extends AbstractForm
{
    use ScenarioServiceAwareTrait;


    public function init()
    {

        $this->setAttribute('action', $this->getCurrentUrl());

        $this->add([
            'name'       => 'scenario',
            'options'    => [
                'label'                     => "Scénario :",
                'disable_inarray_validator' => true,
                'label_attributes'          => [
                    'title' => "Scénario",
                ],
                'value_options'             => $this->getScenarioOptions(),
            ],
            'attributes' => [
                'id'               => 'scenario',
                'title'            => "Scénario ...",
                'class'            => 'selectpicker',
                'data-live-search' => "true",
            ],
            'type'       => 'Select',
        ]);

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => 'Sélectionner',
                'class' => 'btn btn-primary',
            ],
        ]);
    }



    private function getScenarioOptions()
    {
        $qb = $this->getServiceScenario()->finderByHistorique();
        $this->getServiceScenario()->finderByContext($qb);
        $scenarios = $this->getServiceScenario()->getList($qb);

        $options = [];
        foreach ($scenarios as $scenario) {
            if ($scenario->getStructure()) {
                $sid       = $scenario->getStructure()->getId();
                $structure = (string)$scenario->getStructure();
            } else {
                $sid       = 0;
                $structure = '000';
            }

            if (!isset($options[$sid])) {
                $options[$sid] = [
                    'label'   => $structure,
                    'options' => [],
                ];
            }
            $options[$sid]['options'][$scenario->getId()] = (string)$scenario;
        }

        uasort($options, function ($a, $b) {
            return $a['label'] > $b['label'] ? 1 : 0;
        });

        if (isset($options[0])) {
            $options[0]['label'] = 'Établissement';
        }

        return $options;
    }



    /**
     * Should return an array specification compatible with
     * {@link Laminas\InputFilter\Factory::createInputFilter()}.
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