<?php

namespace Mission\Form;

use Application\Entity\Db\Structure;
use Application\Form\AbstractForm;
use Mission\Entity\Db\Mission;
use Mission\Entity\Db\MissionTauxRemu;
use Mission\Entity\Db\TypeMission;


/**
 * Description of MissionForm
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class MissionForm extends AbstractForm
{

    public function init()
    {
        $this->spec(Mission::class, ['intervenant']);
        $this->build();
        $this->setValueOptions('typeMission', "SELECT tm FROM " . TypeMission::class . " tm WHERE tm.histoDestruction IS NULL");
        $this->setValueOptions('missionTauxRemu', "SELECT mtr FROM " . MissionTauxRemu::class . " mtr");
        $this->setValueOptions('structure', "SELECT s FROM " . Structure::class . " s WHERE s.histoDestruction IS NULL");

        $this->setLabels([
            'typeMission'     => '',
            'missionTauxRemu' => 'Taux de rémunération',
            'dateDebut'       => '',
            'dateFin'         => '',
            'description'     => 'Descriptif de la mission',
            'autoValidation'  => 'La mission n\'a pas besoin d\'être validée',
            'structure'       => 'Composante en charge du suivi de mission',

        ]);

        $this->addSubmit();
    }
}