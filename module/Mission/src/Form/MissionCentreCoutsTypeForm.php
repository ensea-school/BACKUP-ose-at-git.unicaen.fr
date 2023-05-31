<?php

namespace Mission\Form;

use Application\Form\AbstractForm;
use Mission\Entity\Db\CentreCoutTypeMission;
use Mission\Service\MissionTypeServiceAwareTrait;


/**
 * Description of MissionCentreCoutsTypeForm
 *
 * @author UnicaenCode
 */
class MissionCentreCoutsTypeForm extends AbstractForm
{
    use MissionTypeServiceAwareTrait;

    public function init()
    {

        $this->setAttribute('action', $this->getCurrentUrl());
        /* Ajoutez vos éléments de formulaire ici */

        $this->add([

            'name'  => 'centreCouts',
            'type'  => 'Select',
            'input' => [
                'required' => false,
            ],
        ]);
        $this->setValueOptions('centreCouts', $this->getServiceMissionType()->getCentreCouts());

        $this->addSubmit("Ajouter");
    }
}

