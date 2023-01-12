<?php

namespace Mission\Form;

use Application\Form\AbstractForm;
use Laminas\Form\FormInterface;
use Mission\Entity\Db\MissionTauxRemuValeur;
use UnicaenImport\Service\Traits\SchemaServiceAwareTrait;

class MissionTauxValeurForm extends AbstractForm
{
    use SchemaServiceAwareTrait;

    public function init()
    {
        $ignore = ['missionTauxRemu'];
        $this->spec(MissionTauxRemuValeur::class, $ignore);
        $this->build();
        $this->addSecurity();
        $this->addSubmit();

        return $this;
    }


    public function bind($object, $flags = FormInterface::VALUES_NORMALIZED)
    {
        /* @var $object MissionTauxRemuValeur */
        parent::bind($object, $flags);
        return $this;
    }
}
