<?php

namespace Mission\Form;

use Application\Filter\DateTimeFromString;
use Application\Form\AbstractForm;
use Application\Hydrator\GenericHydrator;
use Mission\Entity\Db\MissionTauxRemu;
use Mission\Service\MissionTauxServiceAwareTrait;
use UnicaenImport\Service\Traits\SchemaServiceAwareTrait;

class MissionTauxForm extends AbstractForm
{
    use SchemaServiceAwareTrait;
    use MissionTauxServiceAwareTrait;

    public function init()
    {
        $hydratorForm = new missionTauxRemuHydrator($this->getEntityManager());
        $this->setHydrator($hydratorForm);

        $this->spec(MissionTauxRemu::class);
        $this->build();

        $this->setValueOptions('missionTauxRemu', $this->getServiceMissionTaux()->getTauxRemusIndexable());
        $this->get('missionTauxRemu')->setEmptyOption("");
        $this->get('missionTauxRemu')->setLabel('Taux de référence');

        $this->add([
            'name'    => 'date',
            'type'    => 'Date',
            'options' => [
                'label' => 'Date d\'effet',
            ],
        ]);
        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => 'Enregistrer',
                'class' => 'btn btn-primary btn-save',
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
            'missionTauxRemu'     => [
                'required' => false,
            ],
            'libelle'      => [
                'required' => true,
            ],
            'code'  => [
                'required' => true,
            ],
            'date' => [
                'required' => true,
            ],
        ];
    }

}





class missionTauxRemuHydrator extends GenericHydrator
{
    use MissionTauxServiceAwareTrait;

    public function extract($object): array
    {

        $data = [
            'id'              => $object->getId(),
            'code'            => $object->getCode(),
            'libelle'         => $object->getLibelle(),
            'valeur'          => $object->getDerniereValeur(),
            'date'            => $object->getDerniereValeurDate(),
            'missionTauxRemu' => $object->getMissionTauxRemu()?->getId(),
        ];


        return $data;
    }



    public function hydrate(array $data, $object)
    {
        $object->setValeur(DateTimeFromString::run($data['date']), $data['valeur']);
        $object->setCode($data['code']);
        $object->setLibelle($data['libelle']);
        $object->setMissionTauxRemu($this->getServiceMissionTaux()->get($data['missionTauxRemu']));
    }
}
