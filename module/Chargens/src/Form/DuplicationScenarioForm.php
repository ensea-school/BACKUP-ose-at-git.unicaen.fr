<?php

namespace Chargens\Form;

use Application\Form\AbstractForm;
use Chargens\Entity\Db\Scenario;
use Chargens\Service\ScenarioServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;
use UnicaenApp\Util;


/**
 * Description of DuplicationScenarioForm
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class DuplicationScenarioForm extends AbstractForm
{
    use ScenarioServiceAwareTrait;

    /**
     * @var Scenario[]
     */
    private $scenarios;



    public function init()
    {
        $this->loadData();

        $hydrator = new DuplicationScenarioFormHydrator();
        $this->setHydrator($hydrator);

        $this->setAttribute('action', $this->getCurrentUrl());

        $this->add([
            'name' => 'noeuds',
            'type' => 'Hidden',
        ]);

        $this->add([
            'name' => 'liens',
            'type' => 'Hidden',
        ]);

        $this->add([
            'name'       => 'destination',
            'options'    => [
                'label'                     => "Scénario de destination :",
                'disable_inarray_validator' => true,
                'label_attributes'          => [
                    'title' => "Scénario",
                ],
                'value_options'             => Util::collectionAsOptions($this->scenarios),
            ],
            'attributes' => [
                'id'    => 'scenario',
                'title' => "Scénario ...",
            ],
            'type'       => 'Select',
        ]);

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => 'Dupliquer',
                'class' => 'btn btn-primary',
            ],
        ]);

        $this->add([
            'name'       => 'button',
            'type'       => 'Button',
            'options'    => [
                'label' => 'Annuler',
            ],
            'attributes' => [
                'class' => 'btn btn-secondary pop-ajax-hide',
            ],
        ]);
    }



    private function loadData()
    {
        $qb = $this->getServiceScenario()->finderByHistorique();
        $this->getServiceScenario()->finderByContext($qb);
        $this->scenarios = $this->getServiceScenario()->getList($qb);

        return $this;
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





class DuplicationScenarioFormHydrator implements HydratorInterface
{

    /**
     * @param array     $data
     * @param           $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        /* on peuple l'objet à partir du tableau de données */

        return $object;
    }



    /**
     * @param  $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            /* On peuple le tableau avec les données de l'objet */
        ];

        return $data;
    }
}