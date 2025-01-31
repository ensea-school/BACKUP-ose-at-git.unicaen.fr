<?php

namespace Service\Form;

use Application\Filter\DateTimeFromString;
use Application\Form\AbstractForm;
use Service\Entity\Db\CampagneSaisie;
use Laminas\Hydrator\HydratorInterface;


/**
 * Description of CampagneSaisieForm
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class CampagneSaisieForm extends AbstractForm
{

    public function init()
    {
        $hydrator = new CampagneSaisieFormHydrator;
        $this->setHydrator($hydrator);

        $this->setAttribute('action', $this->getCurrentUrl());

        $this->add([
            'name' => 'id',
            'type' => 'Hidden',
        ]);

        $this->add([
            'name' => 'typeIntervenant',
            'type' => 'Hidden',
        ]);

        $this->add([
            'name' => 'typeVolumeHoraire',
            'type' => 'Hidden',
        ]);

        $this->add([
            'type'    => 'Date',
            'name'    => 'dateDebut',
            'options' => [
                'label' => 'Date de début',
            ],
        ]);

        $this->add([
            'type'    => 'Date',
            'name'    => 'dateFin',
            'options' => [
                'label' => 'Date de fin',
            ],
        ]);

        $this->add([
            'name'    => 'messageIntervenant',
            'type'    => 'Text',
            'options' => [
                'label' => 'Message informatif affiché pour les intervenants uniquement hors période de campagne',
            ],
        ]);

        $this->add([
            'name'    => 'messageAutres',
            'type'    => 'Text',
            'options' => [
                'label' => 'Message informatif affiché pour les personnels uniquement hors période de campagne',
            ],
        ]);

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => 'Enregistrer',
                'class' => 'btn btn-primary',
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
        return [
            'dateDebut'          => [
                'required' => false,
            ],
            'dateFin'            => [
                'required' => false,
            ],
            'messageIntervenant' => [
                'required' => false,
            ],
            'messageAutres'      => [
                'required' => false,
            ],
        ];
    }

}





class CampagneSaisieFormHydrator implements HydratorInterface
{

    /**
     * @param array          $data
     * @param CampagneSaisie $object
     *
     * @return CampagneSaisie
     */
    public function hydrate(array $data, $object)
    {
        $object->setDateDebut(DateTimeFromString::run($data['dateDebut'] ?? null));
        $object->setDateFin(DateTimeFromString::run($data['dateFin'] ?? null));
        $object->setMessageIntervenant($data['messageIntervenant']);
        $object->setMessageAutres($data['messageAutres']);

        return $object;
    }



    /**
     * @param CampagneSaisie $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'id'                 => $object->getId(),
            'typeIntervenant'    => $object->getTypeIntervenant()->getId(),
            'typeVolumeHoraire'  => $object->getTypeVolumeHoraire()->getId(),
            'dateDebut'          => $object->getDateDebut(),
            'dateFin'            => $object->getDateFin(),
            'messageIntervenant' => $object->getMessageIntervenant(),
            'messageAutres'      => $object->getMessageAutres(),
        ];

        return $data;
    }
}