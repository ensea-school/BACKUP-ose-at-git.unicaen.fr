<?php

namespace Mission\Form;

use Application\Entity\Db\Structure;
use Application\Form\AbstractForm;
use Application\Service\Traits\ContextServiceAwareTrait;
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
    use ContextServiceAwareTrait;

    public function init()
    {
        $this->spec(Mission::class, ['intervenant']);

        $this->spec(['description' => ['type' => 'Textarea']]);

        $this->build();

        $tmDql       = "SELECT tm FROM " . TypeMission::class . " tm WHERE tm.histoDestruction IS NULL AND tm.annee = :annee";
        $tmDqlParams = ['annee' => $this->getServiceContext()->getAnnee()];
        $this->setValueOptions('typeMission', $tmDql, $tmDqlParams);

        $trDql = "SELECT mtr FROM " . MissionTauxRemu::class . " mtr";
        $this->setValueOptions('missionTauxRemu', $trDql);

        $sDql = "SELECT s FROM " . Structure::class . " s WHERE s.histoDestruction IS NULL";
        $this->setValueOptions('structure', $sDql);

        $this->setLabels([
            'structure'       => 'Composante en charge du suivi de mission',
            'typeMission'     => 'Type de mission',
            'missionTauxRemu' => 'Taux de rémunération',
            'dateDebut'       => 'Date de début',
            'dateFin'         => 'Date de fin',
            'description'     => 'Descriptif de la mission',
            'autoValidation'  => 'La mission n\'a pas besoin d\'être validée',
        ]);

        $this->addSubmit();
    }
}