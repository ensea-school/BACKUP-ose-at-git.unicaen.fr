<?php

namespace Mission\Form;

use Application\Form\AbstractForm;
use Laminas\Form\FormInterface;
use Mission\Entity\Db\TypeMission;
use Mission\Service\MissionTauxServiceAwareTrait;
use UnicaenImport\Service\Traits\SchemaServiceAwareTrait;

class MissionTypeForm extends AbstractForm
{
    use SchemaServiceAwareTrait;
    use MissionTauxServiceAwareTrait;

    public function init()
    {
        $ignore = [];
        $this->spec(TypeMission::class, $ignore);
        $this->build();
        $this->setValueOptions('missionTauxRemu', $this->getServiceMissionTaux()->getTauxRemus());
        $this->get('missionTauxRemu')->setEmptyOption("");
        $this->get('missionTauxRemu')->setLabel('Taux par défaut');

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
