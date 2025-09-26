<?php

namespace Mission\Form;

use Application\Form\AbstractForm;
use Application\Provider\Privileges;
use Doctrine\Common\Collections\ArrayCollection;
use Intervenant\Entity\Db\IntervenantAwareTrait;
use Mission\Entity\Db\Mission;
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
        $this->setAttribute('id', uniqid('fms'));
        $this->setHydrator(new MissionSuiviFormHydrator());

        $this->add([
            'name' => 'mode',
            'type' => 'Hidden',
        ]);



        $missions = $this->getMissions();
        $besoinFormation = [];
        foreach ($missions as $mission)
        {
            $besoinFormation[$mission->getId()] = ($mission->getHeuresFormation() && $mission->getHeuresFormation() > 0);
        }
        $this->add([
            'name'    => 'mission',
            'type'    => 'Select',
            'options' => [
                'label'         => 'Mission',
                'empty_option'  => '- Non renseignée -',
                'value_options' => Util::collectionAsOptions($missions, false, function ($m) {
                    return $m->getLibelleMission() ?? $m->getLibelleCourt();
                }),
            ],
            'attributes' => [
                'data-besoin-formation' => json_encode($besoinFormation),
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



    /**
     * @return array|Mission[]
     */
    private function getMissions(): ArrayCollection
    {
        $missions = $this->getIntervenant()->getMissions();

        foreach ($missions as $i => $mission) {
            if (!$mission->canAddSuivi($this->date)) {
                unset($missions[$i]);
            }
            if (!$this->getAuthorize()->isAllowed($mission, Privileges::MISSION_EDITION_REALISE)){
                unset($missions[$i]);
            }
        }

        return $missions;
    }

}
