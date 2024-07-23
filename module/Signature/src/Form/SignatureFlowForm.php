<?php

namespace Signature\Form;

use Application\Form\AbstractForm;
use Application\Service\Traits\ContextServiceAwareTrait;
use Lieu\Entity\Db\Structure;
use Mission\Entity\Db\Mission;
use Mission\Entity\Db\TypeMission;
use Paiement\Entity\Db\TauxRemu;
use UnicaenApp\Util;
use UnicaenSignature\Entity\Db\SignatureFlow;


/**
 * Description of MissionForm
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class SignatureFlowForm extends AbstractForm
{

    public function init()
    {


        $this->setAttribute('id', uniqid('fm'));

        $this->spec(SignatureFlow::class);
        /*        $this->spec([
                    'description'     => ['type' => 'Textarea'],
                    'etudiantsSuivis' => ['type' => 'Textarea'],
                    'structure'       => ['type' => \Lieu\Form\Element\Structure::class],
                    'tauxRemuMajore'  => ['input' => ['required' => false]],
                    'heuresFormation' => ['input' => ['required' => false]],
                ]);*/
        $this->build();

        /* $this->setValueOptions('typeMission', Util::collectionAsOptions($typesMissions));
         $this->get('typeMission')->setAttribute('data-tm', json_encode($tmData));

         $trDql = "SELECT mtr FROM " . TauxRemu::class . " mtr WHERE mtr.histoDestruction IS NULL";
         $this->setValueOptions('tauxRemu', $trDql);
         $this->setValueOptions('tauxRemuMajore', $trDql);
         $this->get('tauxRemuMajore')->setEmptyOption('- Aucune majoration -');

         $this->setLabels([
             'structure'       => 'Composante en charge du suivi de mission',
             'typeMission'     => 'Type de mission',
             'tauxRemu'        => 'Taux de rémunération',
             'tauxRemuMajore'  => 'Taux majoré (heures nocturnes et dimanches/jf)',
             'dateDebut'       => 'Date de début',
             'dateFin'         => 'Date de fin',
             'description'     => 'Descriptif de la mission',
             'libelleMission'  => 'Libelle mission',
             'etudiantsSuivis' => 'Noms des étudiants suivis',
             'heuresFormation' => 'Heures de formation prévues',
             'heures'          => 'Heures',
         ]);*/

        $this->addSubmit();
    }

}