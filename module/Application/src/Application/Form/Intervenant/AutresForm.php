<?php

namespace Application\Form\Intervenant;

;

use Application\Form\AbstractForm;
use Application\Hydrator\DossierAutreHydrator;
use Application\Service\Traits\DossierAutreTypeServiceAwareTrait;
use Zend\Form\Element\Csrf;


/**
 * Description of AutresForm
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class AutresForm extends AbstractForm
{

    use DossierAutreTypeServiceAwareTrait;

    public function init()
    {

        $hydrator = new DossierAutreHydrator();
        $this->setHydrator($hydrator);

        $this->setAttribute('action', $this->getCurrentUrl());

        $this->add([
            'name'    => 'libelle',
            'options' => [
                'label' => "Libellé",
            ],
            'type'    => 'Text',
        ]);

        $this->add([
            'name'    => 'description',
            'options' => [
                'label' => "Description du champs",
            ],
            'type'    => 'Textarea',
        ]);

        $this->add([
            'name' => 'type',
            'type' => 'Select',
        ]);
        $this->get('type')
            ->setValueOptions(['' => '(Sélectionnez un type de champs...)'] + \UnicaenApp\Util::collectionAsOptions($this->getServiceDossierAutreType()->getList()));

        $this->add([
            'name'    => 'obligatoire',
            'options' => [
                'label' => 'Champs obligatoire',
            ],
            'type'    => 'Checkbox',
        ]);

        $this->add([
            'name' => 'id',
            'type' => 'Hidden',
        ]);

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => 'Appliquer',
                'class' => 'btn btn-primary',
            ],
        ]);

        $this->add(new Csrf('security'));
    }



    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [];
    }
}

