<?php

namespace Mission\Form;

use Application\Entity\Db\Structure;
use Application\Form\AbstractForm;
use Application\Service\Traits\ContextServiceAwareTrait;
use Mission\Entity\Db\Mission;
use Paiement\Entity\Db\TauxRemu;
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
        $this->spec(Mission::class, ['intervenant', 'autoValidation']);

        $this->spec(['description' => ['type' => 'Textarea']]);

        $this->build();

        $tmDql       = "SELECT tm FROM " . TypeMission::class . " tm WHERE tm.histoDestruction IS NULL AND tm.annee = :annee";
        $tmDqlParams = ['annee' => $this->getServiceContext()->getAnnee()];
        $this->setValueOptions('typeMission', $tmDql, $tmDqlParams);

        $trDql = "SELECT mtr FROM " . TauxRemu::class . " mtr";
        $this->setValueOptions('tauxRemu', $trDql);

        $sDql = "SELECT s FROM " . Structure::class . " s WHERE s.histoDestruction IS NULL";
        $this->setValueOptions('structure', $sDql);

        $this->setLabels([
            'structure'       => 'Composante en charge du suivi de mission',
            'typeMission'     => 'Type de mission',
            'tauxRemu' => 'Taux de rémunération',
            'dateDebut'       => 'Date de début',
            'dateFin'         => 'Date de fin',
            'description'     => 'Descriptif de la mission',
        ]);

        $this->addSubmit();
    }
}