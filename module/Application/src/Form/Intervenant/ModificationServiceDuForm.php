<?php

namespace Application\Form\Intervenant;

use Application\Form\AbstractForm;
use Application\Form\Intervenant\Traits\ModificationServiceDuFieldsetAwareTrait;

/**
 * Formulaire de modification de service dû d'un intervenant.
 *
 */
class ModificationServiceDuForm extends AbstractForm
{
    use ModificationServiceDuFieldsetAwareTrait;

    /**
     * This function is automatically called when creating element with factory. It
     * allows to perform various operations (add elements...)
     */
    public function init()
    {
        $this->setAttribute('method', 'post')
            ->setAttribute('class', 'modification-service-du')
            ->setHydrator(new \Laminas\Hydrator\ClassMethodsHydrator(false))
            ->setInputFilter(new \Laminas\InputFilter\InputFilter());

        $fs = $this->getFieldsetIntervenantModificationServiceDu();
        $fs->setUseAsBaseFieldset(true);
        $this->add($fs, ['name' => 'fs']);

        $this->add([
            'type'       => 'Button',
            'name'       => 'ajouter',
            'options'    => [
                'label'         => '<i class="fas fa-plus"></i> Ajouter',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
            'attributes' => [
                'title' => "Ajouter une modification de service dû",
                'class' => 'modification-service-du modification-service-du-ajouter btn btn-default btn-xs',
            ],
        ]);

        $this->add(new \Laminas\Form\Element\Csrf('security'));

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => 'Enregistrer',
            ],
        ]);
    }



    /**
     * Should return an array specification compatible with
     * {@link Laminas\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [];
    }

}
