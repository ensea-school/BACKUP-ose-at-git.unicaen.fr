<?php

namespace Plafond\Form;

use Application\Form\AbstractForm;
use Plafond\Entity\Db\Plafond;
use Plafond\Entity\Db\PlafondPerimetre;
use Plafond\Service\PlafondServiceAwareTrait;
use UnicaenApp\Util;


/**
 * Description of PlafondStructureForm
 *
 * @author UnicaenCode
 */
class PlafondStructureForm extends AbstractForm
{
    use PlafondServiceAwareTrait;

    protected $hydratorElements = [
        'id'      => ['type' => 'int'],
        'plafond' => ['type' => Plafond::class],
        'heures'  => ['type' => 'float'],
    ];



    public function init()
    {
        $this->setAttribute('action', $this->getCurrentUrl());
        $this->useGenericHydrator($this->hydratorElements);

        $this->add([
            'type'       => 'Select',
            'name'       => 'plafond',
            'options'    => [
                'label'         => 'Plafond',
                'value_options' => Util::collectionAsOptions($this->getPlafonds()),
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
        ]);

        $this->add([
            'name'       => 'heures',
            'type'       => 'Text',
            'options'    => [
                'label' => "Heures",
            ],
            'attributes' => [
                'title' => "Nombre d'heures",
            ],
        ]);

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => 'Enregistrer',
                'class' => 'btn btn-primary',
            ],
        ]);
    }



    /**
     * @return Plafond[]
     */
    protected function getPlafonds(): array
    {
        $plafonds = $this->getServicePlafond()->getList(
            $this->getServicePlafond()->finderByPlafondPerimetre($this->getServicePlafond()->getPerimetre(PlafondPerimetre::STRUCTURE))
        );

        return $plafonds;
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
            'plafond'     => ['required' => true],
            'plafondEtat' => ['required' => true],
            'heures'      => ['required' => true],
        ];
    }

}