<?php

namespace Mission\Form;

use Application\Form\AbstractForm;
use Application\Service\Traits\ContextServiceAwareTrait;
use Laminas\Validator\Between;
use Lieu\Entity\Db\Structure;
use Mission\Entity\Db\Mission;
use Mission\Entity\Db\TypeMission;
use Paiement\Entity\Db\TauxRemu;
use UnicaenApp\Util;


/**
 * Description of MissionForm
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class MissionForm extends AbstractForm
{
    protected bool $valide = false;

    use ContextServiceAwareTrait;

    public function init()
    {
        $tmDql       = "SELECT tm FROM " . TypeMission::class . " tm WHERE tm.histoDestruction IS NULL AND tm.annee = :annee";
        $tmDqlParams = ['annee' => $this->getServiceContext()->getAnnee()];
        /** @var TypeMission[] $typesMissions */
        $typesMissions = $this->getEntityManager()->createQuery($tmDql)->setParameters($tmDqlParams)->getResult();

        $tmAccEtu = [];
        $tmData   = [];
        foreach ($typesMissions as $typeMission) {
            $tmData[$typeMission->getId()] = [
                'accompagnementEtudiant' => $typeMission->isAccompagnementEtudiants(),
                'besoinFormation'        => $typeMission->isBesoinFormation(),
                'tauxRemu'               => $typeMission->getTauxRemu()?->getId(),
                'tauxRemuMajore'         => $typeMission->getTauxRemuMajore()?->getId(),
            ];
        }

        $this->setAttribute('id', uniqid('fm'));

        $this->spec(Mission::class, ['intervenant', 'autoValidation', 'prime', 'primeActive']);
        $this->spec([
            'description'     => ['type' => 'Textarea'],
            'etudiantsSuivis' => ['type' => 'Textarea'],
            'structure'       => ['type' => \Lieu\Form\Element\Structure::class],
            'tauxRemuMajore'  => ['input' => ['required' => false]],
            'heuresFormation' => ['input' => ['required' => false]],
        ]);
        $this->build();

        $this->setValueOptions('typeMission', Util::collectionAsOptions($typesMissions));
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
        ]);

        $this->addSubmit();
    }



    public function getInputFilterSpecification()
    {
        $annee     = $this->getServiceContext()->getAnnee();
        $dateDebut = $annee->getDateDebut()->format('Y-m-d');
        $dateFin   = $annee->getDateFin()->format('Y-m-d');

        return [
            'dateDebut'      => [
                'validators' => [
                    [
                        'name'    => Between::class,
                        'options' => [
                            'min'       => $annee->getDateDebut()->format('Y-m-d'),
                            'max'       => $annee->getDateFin()->format('Y-m-d'),
                            'inclusive' => true,
                            'messages'  => [
                                Between::NOT_BETWEEN => 'La date de début de la mission doit être comprise entre ' . $annee->getDateDebut()->format('d/m/Y') . ' et le ' . $annee->getDateFin()->format('d/m/Y') . ' (Année universitaire)',
                            ],
                        ],
                    ],
                ],
            ],
            'dateFin'        => [
                'validators' => [
                    [
                        'name'    => Between::class,
                        'options' => [
                            'min'       => $annee->getDateDebut()->format('Y-m-d'),
                            'max'       => $annee->getDateFin()->format('Y-m-d'),
                            'inclusive' => true,
                            'messages'  => [
                                Between::NOT_BETWEEN => 'La date de fin de la mission doit être comprise entre ' . $annee->getDateDebut()->format('d/m/Y') . ' et le ' . $annee->getDateFin()->format('d/m/Y') . ' (Année universitaire)',
                            ],
                        ],
                    ],
                ],
            ],
            'tauxRemuMajore' => [
                'required' => false,
            ],

        ];
    }



    public function editValide(): self
    {
        $this->valide = true;

        $elements = $this->getElements();
        unset($elements['heures']);
        unset($elements['dateFin']);

        // On met en lecture seule tout le formulaire, sauf le nombre d'heures & la date de fin
        // ces deux données uniquement sont modifiables après validation
        $elements = array_keys($elements);
        $this->readOnly(true, $elements);

        return $this;
    }



    public function isValide(): bool
    {
        return $this->valide;
    }
}