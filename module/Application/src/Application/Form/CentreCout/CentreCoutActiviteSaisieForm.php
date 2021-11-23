<?php
namespace Application\Form\CentreCout;

use Application\Form\AbstractForm;
use Application\Hydrator\CentreCoutActiviteHydrator;
use Laminas\Form\Element\Csrf;

/**
 * Description of CentreCoutActiviteSaisieForm
 *
 * @author LE COURTES Antony <antony.lecourtes at unicaen.fr>
 */
class CentreCoutActiviteSaisieForm extends AbstractForm
{

    public function init()
    {
        $hydrator = new CentreCoutActiviteHydrator();
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
                'label' => 'FI',
            ],
            'type' => 'Checkbox',
        ]);
        $this->add([
            'name' => 'fa',
            'options' => [
                'label' => 'FA',
            ],
            'type' => 'Checkbox',
        ]);
        $this->add([
            'name' => 'fc',
            'options' => [
                'label' => 'FC',
            ],
            'type' => 'Checkbox',
        ]);

        $this->add([
            'name' => 'fc_majore',
            'options' => [
                'label' => 'FC Majoré',
            ],
            'type' => 'Checkbox',
        ]);

        $this->add([
            'name' => 'referentiel',
            'options' => [
                'label' => 'Référentiel',
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
     * {@link Laminas\InputFilter\Factory::createInputFilter()}.
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
