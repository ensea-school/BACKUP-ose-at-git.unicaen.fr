<?php

namespace Paiement\Form;

use Application\Filter\DateTimeFromString;
use Application\Form\AbstractForm;
use Laminas\Hydrator\HydratorInterface;
use Paiement\Entity\Db\TauxRemu;
use Paiement\Service\TauxRemuServiceAwareTrait;

class TauxForm extends AbstractForm
{
    use TauxRemuServiceAwareTrait;

    public function init()
    {
        $hydratorForm = new TauxRemuHydrator($this->getEntityManager());
        $this->setHydrator($hydratorForm);

        $this->spec(TauxRemu::class);
        $this->spec([
            'tauxRemu' => [
                'input' => [
                    'required' => false,
                ],
            ],
        ]);
        $this->build();

        $this->setValueOptions('tauxRemu', $this->getServiceTauxRemu()->getTauxRemusIndexable());
        $this->get('tauxRemu')->setEmptyOption("");
        $this->get('tauxRemu')->setLabel('Taux de référence');
        $this->add([
                       'name'    => 'valeur',
                       'type'    => 'Number',
                       'options' => [
                           'label' => 'Valeur a la date d\'effet',
                       ],
                       'attributes' => [
                           'step' => 'any',
                           'inputmode' => 'decimal',
                       ],
                   ]);
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


class TauxRemuHydrator implements HydratorInterface
{
    use TauxRemuServiceAwareTrait;

    public function extract(object $object): array
    {
        $data = [
            'id'       => $object->getId(),
            'code'     => $object->getCode(),
            'libelle'  => $object->getLibelle(),
            'valeur'   => $object->getDerniereValeur(),
            'date'     => $object->getDerniereValeurDate(),
            'tauxRemu' => $object->getTauxRemu()?->getId(),
        ];


        return $data;
    }



    public function hydrate(array $data, object $object)
    {
        $object->setValeur(DateTimeFromString::run($data['date']), $data['valeur']);
        $object->setCode($data['code']);
        $object->setLibelle($data['libelle']);
        $object->setTauxRemu($this->getServiceTauxRemu()->get($data['tauxRemu']));
    }
}
