<?php

namespace Mission\Form;


use Application\Form\AbstractForm;
use Application\Service\Traits\ContextServiceAwareTrait;
use Mission\Entity\Db\Mission;
use Mission\Entity\Db\TypeMission;
use Mission\Validator\DateMissionValidator;
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
            'dateDebut'       => [
                'options'    => [
                    'label_attributes' => [
                        'class' => 'form-label',
                    ],
                ],
                'attributes' => [
                    'class' => 'form-control',

                ],
            ],
            'dateFin'         => [
                'options'    => [
                    'label_attributes' => [
                        'class' => 'form-label',
                    ],
                ],
                'attributes' => [
                    'class' => 'form-control',

                ],
            ],
        ]);
        $this->build();

        $valueOptions = ['' => '- Sélectionner un type de mission -'] + Util::collectionAsOptions($typesMissions);
        $this->setValueOptions('typeMission', $valueOptions);
        $this->get('typeMission')->setAttribute('data-tm', json_encode($tmData));

        $trDql = "SELECT mtr FROM " . TauxRemu::class . " mtr WHERE mtr.histoDestruction IS NULL";
        $this->setValueOptions('tauxRemu', $trDql);
        $this->setValueOptions('tauxRemuMajore', $trDql);
        $this->get('tauxRemuMajore')->setEmptyOption('- Aucune majoration -');
        $this->get('tauxRemu')->setEmptyOption('- Aucun -');


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

        $dateDebut = new \DateTime($this->get('dateDebut')->getValue());
        $dateFin   = new \DateTime($this->get('dateFin')->getValue());
        $annee     = $this->getServiceContext()->getAnnee();

        return [
            'dateDebut'      => [
                'required'   => true,
                'validators' => [
                    new DateMissionValidator(['annee'        => $annee,
                                              'dateDebut'    => $dateDebut,
                                              'dateFin'      => $dateFin,
                                              'dateVerifiee' => $dateDebut,
                    ]),
                ],
            ],
            'dateFin'        => [
                'required' => true,
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