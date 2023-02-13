<?php

namespace Mission\Form;

use Application\Form\AbstractForm;
use Laminas\Form\FormInterface;
use Mission\Entity\Db\TypeMission;
use Paiement\Service\TauxRemuServiceAwareTrait;
use UnicaenImport\Service\Traits\SchemaServiceAwareTrait;

class MissionTypeForm extends AbstractForm
{
    use SchemaServiceAwareTrait;
    use TauxRemuServiceAwareTrait;

    public function init()
    {
        $ignore = [];
        $this->spec(TypeMission::class, $ignore);
        $this->spec([
            'tauxRemu' => [
                'input' => [
                    'required' => false,
                ],
            ],
        ]);
        $this->build();
        $this->setValueOptions('tauxRemu', $this->getServiceTaux()->getTauxRemus());
        $this->get('tauxRemu')->setEmptyOption("");
        $this->get('tauxRemu')->setLabel('Taux par défaut');

        $this->addSecurity();
        $this->addSubmit();

        return $this;
    }



    public function bind($object, $flags = FormInterface::VALUES_NORMALIZED)
    {
        /* @var $object TypeMission */
        parent::bind($object, $flags);

        return $this;
    }
}
