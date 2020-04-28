<?php

namespace Application\Form\Intervenant;

use Application\Form\AbstractForm;
use Application\Hydrator\RegleStructureValidationHydrator;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use Application\Service\Traits\TypeIntervenantServiceAwareTrait;
use Application\Service\Traits\TypeVolumeHoraireServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

/**
 * Description of RegleStructureValidationForm
 *
 * @author Antony LE COURTES <antony.lecourtes at unicaen.fr>
 */
class RegleStructureValidationForm extends AbstractForm
{
    use TypeIntervenantServiceAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;

    public function init()
    {

        $hydrator = new RegleStructureValidationHydrator();
        $this->setHydrator($hydrator);

        $this->setAttribute('action', $this->getCurrentUrl() );

        $this->add([
            'name'       => 'type-intervenant',
            'options'    => [
                'label' => 'Type intervenant',
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'false',
            ],
            'type'       => 'Select',
        ]);

        $this->add([
            'name'       => 'type-volume-horaire',
            'options'    => [
                'label' => 'Type volume horaire',
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'false',
            ],
            'type'       => 'Select',
        ]);
        $this->get('type-volume-horaire')
             ->setValueOptions(\UnicaenApp\Util::collectionAsOptions($this->getServiceTypeVolumeHoraire()->getList()));

        $this->add([
            'name'    => 'message',
            'options' => [
                'label' => "Message",
            ],
            'type'    => 'Textarea',
        ]);

        $this->add([
            'name'       => 'type-intervenant',
            'options'    => [
                'label' => 'Type intervenant',
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'false',
            ],
            'type'       => 'Select',
        ]);
        $this->get('type-intervenant')
             ->setValueOptions(\UnicaenApp\Util::collectionAsOptions($this->getServiceTypeIntervenant()->getList()));

        $this->add([
            'name'       => 'priorite',
            'options'    => [
                'label' => 'Priorité',
                'value_options' => [
                    'affection'    => 'Affection',
                    'enseignement' => 'Enseignement',
                ],
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'false',
            ],
            'type'       => 'Select',
        ]);

        $this->add(new \Zend\Form\Element\Csrf('security'));

        $this->add([
            'name' => 'submit',
            'type'  => 'Submit',
            'attributes' => [
                'value' => 'Enregistrer',
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
        return [];
    }

}