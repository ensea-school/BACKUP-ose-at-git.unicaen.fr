<?php

namespace Mission\Form;

use Application\Entity\Db\Traits\IntervenantAwareTrait;
use Application\Form\AbstractForm;
use Laminas\Hydrator\HydratorInterface;
use Mission\Entity\Db\VolumeHoraireMission;
use Mission\Service\MissionServiceAwareTrait;
use UnicaenApp\Util;


/**
 * Description of MissionSuiviForm
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class MissionSuiviForm extends AbstractForm
{
    use IntervenantAwareTrait;

    public \DateTime $date;

    public function build()
    {

        $this->setAttribute('action', $this->getCurrentUrl());
        $this->setHydrator(new MissionSuiviHydrator());

        $this->add([
            'name' => 'mode',
            'type' => 'Hidden',
        ]);

        $this->add([
            'name'    => 'mission',
            'type'    => 'Select',
            'options' => [
                'label'         => 'Mission',
                'empty_option'  => '- Non renseignée -',
                'value_options' => Util::collectionAsOptions($this->getMissionsOptions()),
            ],
        ]);

        $this->add([
            'name'    => 'date',
            'type'    => 'Date',
            'options' => [
                'label' => 'Date',
            ],
        ]);

        $this->add([
            'name'    => 'heureDebut',
            'type'    => 'Time',
            'options' => [
                'label'  => 'Horaire de début',
                'format' => 'H:i',
            ],
        ]);

        $this->add([
            'name'    => 'heureFin',
            'type'    => 'Time',
            'options' => [
                'label'  => 'Horaire de fin',
                'format' => 'H:i',
            ],
        ]);

        $this->add([
            'name'    => 'formation',
            'options' => [
                'label' => 'Formation',
            ],
            'type'    => 'Checkbox',
        ]);

        $this->add([
            'name'       => 'description',
            'type'       => 'Textarea',
            'options'    => [
                'label' => 'Description',
            ],
            'attributes' => [
                'rows'     => 3,
                'max-rows' => 6,
            ],
        ]);

        $this->get('mode')->setValue('mission');

        $this->addSubmit();
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
            'mission'                => [
                'required' => true,
            ],
            'date'             => [
                'required' => true,
            ],
            'heureDebut'      => [
                'required' => true,
            ],
            'heureFin'              => [
                'required'   => true,
                'validators' => [
                    new \Laminas\Validator\Callback([
                        'messages' => [\Laminas\Validator\Callback::INVALID_VALUE => 'L\'heure de fin doit être postérieure à l\'heure de début'],
                        'callback' => function ($value, array $options) {
                            return $value >= $options['heureDebut'];
                        }]),
                ],
            ],
            'formation'           => [
                'required' => true,
            ],
            'description'           => [
                'required' => false,
            ],
        ];
    }


    private function getMissionsOptions()
    {
        $missions = $this->getIntervenant()->getMissions();

        $options = [];
        foreach ($missions as $mission) {
            if ($mission->canAddSuivi($this->date)) {
                $options[$mission->getId()] = $mission->getLibelleCourt();
            }
        }

        return $options;
    }

}





/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class MissionSuiviHydrator implements HydratorInterface
{
    use MissionServiceAwareTrait;


    /**
     * Hydrate $object with the provided $data.
     *
     * @param array        $data
     * @param VolumeHoraireMission $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setMission($this->getServiceMission()->get($data['mission']));
        $object->setDate($data['date']);
        $object->setHeureDebut($data['heureDebut']);
        $object->setHeureFin($data['heureFin']);
        $object->setFormation($data['formation']);
        $object->setDescription($data['description']);

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param VolumeHoraireMission $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'mission'     => $object->getMission()?->getId(),
            'date'        => $object->getDate(),
            'heureDebut'  => $object->getHeureDebut(),
            'heureFin'    => $object->getHeureFin(),
            'formation'   => $object->isFormation(),
            'description' => $object->getDescription(),
        ];

        return $data;
    }
}