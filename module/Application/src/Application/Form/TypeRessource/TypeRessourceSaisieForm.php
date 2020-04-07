<?php
namespace Application\Form\TypeRessource;

use Application\Form\AbstractForm;
use Application\Hydrator\TypeRessourceHydrator;
use Zend\Form\Element\Csrf;

/**
 * Description of TypeRessourceSaisieForm
 *
 * @author LE COURTES Antony <antony.lecourtes at unicaen.fr>
 */
class TypeRessourceSaisieForm extends AbstractForm
{

    public function init()
    {
        $hydrator = new TypeRessourceHydrator();
        $this->setHydrator($hydrator);

        $this->setAttribute('action', $this->getCurrentUrl());
        $this->add([
            'name' => 'code',
            'options' => [
                'label' => "Code",
            ],
            'type' => 'Text',
        ]);
        $this->add([
            'name' => 'libelle',
            'options' => [
                'label' => "Libellé",
            ],
            'type' => 'Text',
        ]);

        $this->add([
            'name' => 'fi',
            'options' => [
                'label' => 'Fi',
            ],
            'type' => 'Checkbox',
        ]);
        $this->add([
            'name' => 'fa',
            'options' => [
                'label' => 'Fa ?',
            ],
            'type' => 'Checkbox',
        ]);
        $this->add([
            'name' => 'fc',
            'options' => [
                'label' => 'Fc',
            ],
            'type' => 'Checkbox',
        ]);

        $this->add(new Csrf('security'));
        $this->add([
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => [
                'value' => "Enregistrer",
                'class' => 'btn btn-primary'
            ],
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
            'code' => [
                'required' => true,
            ],
            'libelle' => [
                'required' => true,
            ],
        ];
    }

}
