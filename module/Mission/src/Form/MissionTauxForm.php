<?php

namespace Mission\Form;

use Application\Form\AbstractForm;
use Laminas\Form\FormInterface;
use Mission\Entity\Db\MissionTauxRemu;
use UnicaenImport\Service\Traits\SchemaServiceAwareTrait;

class MissionTauxForm extends AbstractForm
{
    use SchemaServiceAwareTrait;

    public function init()
    {
//        $ignore = [""];
//        $this->add([
//            'type'    => 'Text',
//            'name'    => 'Libelle',
//            'options' => [
//                'label' => 'Libelle',
//            ],
//        ]);
//        $this->add([
//            'type'    => 'Text',
//            'name'    => 'Code',
//            'options' => [
//                'label' => 'Code',
//            ],
//        ]);
//        $this->add([
//            'type'    => 'Text',
//            'name'    => 'Valeur',
//            'options' => [
//                'label' => 'Valeur',
//            ],
//        ]);        $this->add([
//            'type'    => 'DateTime',
//            'name'    => 'Date',
//            'options' => [
//                'label' => 'Date',
//            ],
//        ]);

        $this->spec(MissionTauxRemu::class);

        $this->build();
        $this->addSecurity();
        $this->addSubmit();

        return $this;
    }


    public function bind($object, $flags = FormInterface::VALUES_NORMALIZED)
    {
        /* @var $object MissionTauxRemu */
        parent::bind($object, $flags);
        return $this;
    }
}
