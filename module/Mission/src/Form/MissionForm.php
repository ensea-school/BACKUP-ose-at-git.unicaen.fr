<?php

namespace Mission\Form;

use Application\Entity\Db\Structure;
use Application\Form\AbstractForm;
use Application\Service\Traits\ContextServiceAwareTrait;
use Mission\Entity\Db\Mission;
use Paiement\Entity\Db\TauxRemu;
use Mission\Entity\Db\TypeMission;
use UnicaenApp\Util;


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
        $tmDql       = "SELECT tm FROM " . TypeMission::class . " tm WHERE tm.histoDestruction IS NULL AND tm.annee = :annee";
        $tmDqlParams = ['annee' => $this->getServiceContext()->getAnnee()];
        /** @var TypeMission[] $typesMissions */
        $typesMissions = $this->getEntityManager()->createQuery($tmDql)->setParameters($tmDqlParams)->getResult();

        $tmAccEtu = [];
        $besoinFormation = [];
        foreach ($typesMissions as $typeMission)
        {
            $tmAccEtu[$typeMission->getId()] = $typeMission->isAccompagnementEtudiants();
            $besoinFormation[$typeMission->getId()] = $typeMission->isBesoinFormation();
        }

        $this->setAttribute('id', uniqid('fm'));

        $this->spec(Mission::class, ['intervenant', 'autoValidation']);
        $this->spec([
            'description' => ['type' => 'Textarea'],
            'etudiantsSuivis' => ['type' => 'Textarea'],
            'tauxRemuMajore' => ['input' => ['required' => false]],
            'heuresFormation' => ['input' => ['required' => false]],
        ]);
        $this->build();

        $this->setValueOptions('typeMission', Util::collectionAsOptions($typesMissions));
        $this->get('typeMission')->setAttribute('data-accompagnement-etudiants', json_encode($tmAccEtu));
        $this->get('typeMission')->setAttribute('data-besoin-formation', json_encode($besoinFormation));

        $trDql = "SELECT mtr FROM " . TauxRemu::class . " mtr WHERE mtr.histoDestruction IS NULL";
        $this->setValueOptions('tauxRemu', $trDql);
        $this->setValueOptions('tauxRemuMajore', $trDql);
        $this->get('tauxRemuMajore')->setEmptyOption('- Aucune majoration -');

        $sDql = "SELECT s FROM " . Structure::class . " s WHERE s.histoDestruction IS NULL";
        $this->setValueOptions('structure', $sDql);

        $this->setLabels([
            'structure'       => 'Composante en charge du suivi de mission',
            'typeMission'     => 'Type de mission',
            'tauxRemu'        => 'Taux de rémunération',
            'tauxRemuMajore'  => 'Taux majoré (heures nocturnes et dimanches/jf)',
            'dateDebut'       => 'Date de début',
            'dateFin'         => 'Date de fin',
            'description'     => 'Descriptif de la mission',
            'etudiantsSuivis' => 'Noms des étudiants suivis',
            'heuresFormation' => 'Heures de formation prévues',
        ]);

        $this->addSubmit();
    }
}