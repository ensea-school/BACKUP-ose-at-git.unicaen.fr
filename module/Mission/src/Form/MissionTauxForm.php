<?php

namespace Mission\Form;

use Application\Filter\DateTimeFromString;
use Application\Form\AbstractForm;
use Application\Hydrator\GenericHydrator;
use DateTime;
use Laminas\Form\FormInterface;
use Mission\Entity\Db\MissionTauxRemu;
use Mission\Entity\Db\MissionTauxRemuValeur;
use UnicaenImport\Service\Traits\SchemaServiceAwareTrait;

class MissionTauxForm extends AbstractForm
{
    use SchemaServiceAwareTrait;

    public function init()
    {
        $hydratorForm = new missionTauxRemuHydrator($this->getEntityManager());
        $this->setHydrator($hydratorForm);
        $this->spec(MissionTauxRemu::class);
        $this->build();
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
}





class missionTauxRemuHydrator extends GenericHydrator
{
    public function extract($object): array
    {

        $data = [
            'id'      => $object->getId(),
            'code'    => $object->getCode(),
            'libelle' => $object->getLibelle(),
            'valeur'  => $object->getDerniereValeur(),
            'date'    => $object->getDerniereValeurDate(),
        ];


        return $data;
    }



    public function hydrate(array $data, $object)
    {
        $object->setValeur(DateTimeFromString::run($data['date']), $data['valeur']);
        $object->setCode($data['code']);
        $object->setLibelle($data['libelle']);
    }
}
