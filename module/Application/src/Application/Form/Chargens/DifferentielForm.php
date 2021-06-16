<?php

namespace Application\Form\Chargens;

use Application\Form\AbstractForm;

/**
 * Description of DifferentielForm
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class DifferentielForm extends AbstractForm
{
    public function init()
    {
        $this->setAttributes([
            'action'  => $this->getCurrentUrl(),
            'class'   => 'differentiel',
            'enctype' => 'multipart/form-data',
        ]);

        $this->add([
            'type'       => 'File',
            'name'       => 'avant',
            'options'    => [
                'label' => "Premier export des charges",
            ],
            'attributes' => [
                'id'       => 'fichier',
                'multiple' => false,
                'accept'   => 'application/csv',
            ],
        ]);

        $this->add([
            'type'       => 'File',
            'name'       => 'apres',
            'options'    => [
                'label' => "Export des charges le plus récent",
            ],
            'attributes' => [
                'id'       => 'fichier',
                'multiple' => false,
                'accept'   => 'application/csv',
            ],
        ]);

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => 'Comparer',
                'class' => 'btn btn-primary btn-save',
            ],
        ]);
    }



    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        $filters = [
        ];

        return $filters;
    }
}
