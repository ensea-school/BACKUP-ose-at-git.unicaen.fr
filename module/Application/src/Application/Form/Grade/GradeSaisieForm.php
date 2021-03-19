<?php

namespace Application\Form\Grade;


use Application\Entity\Db\Corps;
use Application\Form\AbstractForm;
use Application\Hydrator\GenericHydrator;
use Application\Service\Traits\CorpsServiceAwareTrait;
use Application\Service\Traits\SourceServiceAwareTrait;
use UnicaenApp\Util;
use UnicaenImport\Entity\Db\Source;
use Zend\Form\Element\Csrf;

/**
 * Description of GradeForm
 */
class GradeSaisieForm extends AbstractForm
{
    use SourceServiceAwareTrait;
    use CorpsServiceAwareTrait;


    protected $hydratorElements = [
        'id'           => ['type' => 'int'],
        'libelleCourt' => ['type' => 'string'],
        'libelleLong'  => ['type' => 'string'],
        'corps'        => ['type' => Corps::class],
        'source'       => ['type' => Source::class],
        'sourceCode'   => ['type' => 'string'],
    ];



    public function init()
    {
        $hydrator = new GenericHydrator($this->getServiceSource()->getEntityManager(), $this->hydratorElements);
        $this->setHydrator($hydrator);

        $this->setAttribute('action', $this->getCurrentUrl());

        $this->add([
            'name'       => 'libelleCourt',
            'options'    => [
                'label' => 'Libellé court',
            ],
            'attributes' => [

            ],
            'type'       => 'Text',
        ]);

        $this->add([
            'name'       => 'libelleLong',
            'options'    => [
                'label' => 'Libellé long',
            ],
            'attributes' => [

            ],
            'type'       => 'Text',
        ]);

        $this->add([
            'name'    => 'corps',
            'type'    => 'Select',
            'options' => [
                'label'         => 'Corps',
                'value_options' => Util::collectionAsOptions($this->getServiceCorps()->getList()),
            ],
        ]);

        $this->add([
            'name'    => 'source',
            'type'    => 'Select',
            'options' => [
                'label'         => 'Source des données',
                'value_options' => Util::collectionAsOptions($this->getServiceSource()->getList()),
            ],
        ]);

        $this->add([
            'name'    => 'sourceCode',
            'type'    => 'Text',
            'options' => [
                'label' => 'Code source',
            ],
        ]);


        $this->add(new Csrf('security'));
        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => "Enregistrer",
                'class' => 'btn btn-primary',
            ],
        ]);

        $this->add([
            'name' => 'id',
            'type' => 'Hidden',
        ]);


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
            'libelleCourt' => [
                'required' => true,
            ],
            'libelleLong'  => [
                'required' => true,
            ],
            'sourceCode'   => [
                'required' => true,
            ],
        ];
    }

}



