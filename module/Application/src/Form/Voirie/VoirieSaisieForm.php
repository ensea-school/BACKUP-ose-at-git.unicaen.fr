<?php

namespace Application\Form\Voirie;


use Application\Form\AbstractForm;
use Application\Hydrator\GenericHydrator;
use Application\Service\Traits\SourceServiceAwareTrait;
use UnicaenApp\Util;
use UnicaenImport\Entity\Db\Source;
use Laminas\Form\Element\Csrf;

/**
 * Description of VoirieForm
 */
class VoirieSaisieForm extends AbstractForm
{
    use SourceServiceAwareTrait;


    protected $hydratorElements = [
        'id'         => ['type' => 'int'],
        'libelle'    => ['type' => 'string'],
        'code'       => ['type' => 'string'],
        'source'     => ['type' => Source::class],
        'sourceCode' => ['type' => 'string'],
    ];



    public function init()
    {
        $hydrator = new GenericHydrator($this->getServiceSource()->getEntityManager(), $this->hydratorElements);
        $this->setHydrator($hydrator);

        $this->setAttribute('action', $this->getCurrentUrl());

        $this->add([
            'name'       => 'libelle',
            'options'    => [
                'label' => 'Libellé',
            ],
            'attributes' => [

            ],
            'type'       => 'Text',
        ]);

        $this->add([
            'name'       => 'code',
            'options'    => [
                'label' => "Code",
            ],
            'attributes' => [
                'id' => uniqid('code'),
            ],
            'type'       => 'Text',
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
     * {@link Laminas\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            'libelle' => [
                'required' => false,
            ],
            'code'    => [
                'required' => false,
            ],
        ];
    }

}
