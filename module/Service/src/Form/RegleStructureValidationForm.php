<?php

namespace Service\Form;

use Application\Form\AbstractForm;
use Intervenant\Service\TypeIntervenantServiceAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use Service\Entity\Db\RegleStructureValidation;
use Laminas\Hydrator\HydratorInterface;

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

        $this->setAttribute('action', $this->getCurrentUrl());

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
                'disabled'         => 'disabled',
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
                'disabled'         => 'disabled',
            ],
            'type'       => 'Select',
        ]);
        $this->get('type-intervenant')
            ->setValueOptions(\UnicaenApp\Util::collectionAsOptions($this->getServiceTypeIntervenant()->getList()));

        $this->add([
            'name'       => 'priorite',
            'options'    => [
                'label'         => 'Priorité',
                'value_options' => [
                    'affectation'  => 'Affectation',
                    'enseignement' => 'Enseignement',
                ],
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'false',
            ],
            'type'       => 'Select',
        ]);

        $this->add(new \Laminas\Form\Element\Csrf('security'));

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => 'Enregistrer',
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
        $spec = [
            'type-intervenant'    => [
                'required' => false,
            ],
            'type-volume-horaire' => [
                'required' => false,
            ],
        ];

        return $spec;
    }

}





class RegleStructureValidationHydrator implements HydratorInterface
{
    use TypeVolumeHoraireServiceAwareTrait;
    use TypeIntervenantServiceAwareTrait;

    /**
     * Hydrate $object with the provided $data.
     *
     * @param array                    $data
     * @param RegleStructureValidation $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setMessage($data['message']);
        $object->setPriorite($data['priorite']);
        if (array_key_exists('type-volume-horaire', $data)) {
            $object->setTypeVolumeHoraire($this->getServiceTypeVolumeHoraire()->get($data['type-volume-horaire']));
        }
        if (array_key_exists('type-intervenant', $data)) {
            $object->setTypeIntervenant($this->getServiceTypeIntervenant()->get($data['type-intervenant']));
        }

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param RegleStructureValidation $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'id'                  => $object->getId(),
            'priorite'            => $object->getPriorite(),
            'message'             => $object->getMessage(),
            'type-volume-horaire' => ($tvh = $object->getTypeVolumeHoraire()) ? $tvh->getId() : null,
            'type-intervenant'    => ($ti = $object->getTypeIntervenant()) ? $ti->getId() : null,
        ];

        return $data;
    }
}
