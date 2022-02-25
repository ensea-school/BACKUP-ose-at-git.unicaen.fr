<?php

namespace Application\Form\Etablissement;

use Application\Form\AbstractForm;
use Application\Hydrator\GenericHydrator;
use Application\Service\Traits\SourceServiceAwareTrait;
use Laminas\Form\Element\Csrf;


/**
 * Description of EtablissementForm
 *
 * @author Florian JORIOT <florian.joriot at unicaen.fr>
 */
class EtablissementSaisieForm extends AbstractForm
{
    use SourceServiceAwareTrait;

    protected $hydratorElements = [
        'id'           => ['type' => 'int'],
        'departement'  => ['type' => 'string'],
        'localisation' => ['type' => 'string'],
        'libelle'      => ['type' => 'string'],
    ];



    public function init()
    {

        $this->setAttribute('action', $this->getCurrentUrl());

        $hydrator = new GenericHydrator($this->getServiceSource()->getEntityManager(), $this->hydratorElements);
        $this->setHydrator($hydrator);

        $this->setAttribute('action', $this->getCurrentUrl());

        $this->add([
            'name' => 'id',
            'type' => 'Hidden',
        ]);

        $this->add([
            'name'    => 'departement',
            'options' => [
                'label' => "Département de l'établissement",
            ],
            'type'    => 'Text',
        ]);

        $this->add([
            'name'    => 'localisation',
            'options' => [
                'label' => "Localisation de l'établissement",
            ],
            'type'    => 'Text',
        ]);
        $this->add([
            'name'    => 'libelle',
            'options' => [
                'label' => "Nom de l'établissement",
            ],
            'type'    => 'Text',
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
                'required' => true,
            ],
        ];
    }

}
