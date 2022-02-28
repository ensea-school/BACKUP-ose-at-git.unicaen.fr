<?php

namespace Intervenant\Form;

use Application\Form\AbstractForm;
use Application\Service\Traits\DossierAutreServiceAwareTrait;
use Intervenant\Entity\Db\Statut;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Application\Service\Traits\TypeAgrementServiceAwareTrait;
use Intervenant\Service\TypeIntervenantServiceAwareTrait;

/**
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class StatutSaisieForm extends AbstractForm
{
    use TypeIntervenantServiceAwareTrait;
    use TypeAgrementServiceAwareTrait;
    use ParametresServiceAwareTrait;
    use DossierAutreServiceAwareTrait;

    public function init()
    {
        $labels = [
            'libelle'                       => 'Libellé',
            'prioritaireIndicateurs'        => 'Ces intervenants, prioritaires, seront mis en évidence au niveau des indicateurs',
            'serviceStatutaire'             => 'Nombre d\'heures de service statutaire',
            'depassementServiceDuSansHC'    => 'Le dépassement du service statutaire n\'occasionne aucune heure complémentaire',
            'tauxChargesPatronales'         => 'Taux de charges patronales',
            'dossier'                       => '',
            'servicePrevu'                  => 'Prévisionnel',
            'serviceRealise'                => 'Réalisé',
            'referentielPrevu'              => 'Prévisionnel',
            'referentielRealise'            => 'Réalisé',
            'dossierSelectionnable'         => 'Le statut pourra être sélectionné lors de la saisie des données personnelles',
            'dossierIdentiteComplementaire' => 'Identité complémentaire',
            'dossierContact'                => 'Contact',
            'dossierTelPerso'               => 'Téléphone personnel obligatoire même si le téléphone pro est renseigné',
            'dossierEmailPerso'             => 'Email personnel obligatoire même si l\'email établissement est renseigné',
            'dossierAdresse'                => 'Adresse',
            'dossierBanque'                 => 'Banque',
            'dossierInsee'                  => 'Numéro INSEE',
            'dossierEmployeur'              => 'Employeur',
            'pieceJustificative'            => '',
            'conseilRestreint'              => 'Conseil restreint',
            'conseilRestreintDureeVie'      => 'Durée de vie du CR',
            'conseilAcademique'             => 'Conseil académique',
            'conseilAcademiqueDureeVie'     => 'Durée de vie du CAC',
            'contrat'                       => '',
            'serviceExterieur'              => 'L\'intervenant pourra assurer des services dans d\'autres établissements',
            'cloture'                       => 'Le service réalisé devra être clôturé avant d\'accéder aux demandes de mise en paiement',
            'modificationServiceDu'         => 'Modifications de service dû',
            'paiementVisualisation'         => 'Visibilité par l\'intervenant des mises en paiement',
            'motifNonPaiement'              => 'Le gestionnaire peut déclarer des heures comme non payables',
            'formuleVisualisation'          => 'Visibilité par l\'intervenant du détail des heures pour le calcul des HETD',
            'typeIntervenant'               => 'Type d\'intervenant',
        ];

        $dveElements = [
            'dossier',
            'servicePrevu',
            'serviceRealise',
            'referentielPrevu',
            'referentielRealise',
        ];

        $ignored = [
            'id',
            'ordre',
            'pieceJustificativeVisualisation',
            'pieceJustificativeEdition',
            'conseilRestreintVisualisation',
            'conseilAcademiqueVisualisation',
            'contratVisualisation',
            'contratDepot',
            'modificationServiceDuVisualisation',
        ];

        for ($i = 1; $i <= 5; $i++) {
            $champAutre = $this->getServiceDossierAutre()->get($i);
            if ($champAutre->getLibelle()) {
                $labels['dossierAutre' . $i] = $champAutre->getLibelle();
                if ($champAutre->isObligatoire()) {
                    $labels['dossierAutre' . $i] .= ' (Obligatoire)';
                }
                $dveElements[] = 'dossierAutre' . $i;
            } else {
                $ignored[] = 'dossierAutre' . $i;
                $ignored[] = 'dossierAutre' . $i . 'Visualisation';
                $ignored[] = 'dossierAutre' . $i . 'Edition';
            }
        }

        foreach ($dveElements as $dveElement) {
            $ignored[] = $dveElement;
            $ignored[] = $dveElement . 'Visualisation';
            $ignored[] = $dveElement . 'Edition';
        }


        for ($i = 1; $i <= 4; $i++) {
            $ccLabel = $this->getServiceParametres()->get("statut_intervenant_codes_corresp_{$i}_libelle");
            if ($ccLabel) {
                $labels['codesCorresp' . $i] = $ccLabel;
            } else {
                $ignored[] = 'codesCorresp' . $i;
            }
        }


        $this->spec(Statut::class, $ignored);

        foreach ($dveElements as $dveElement) {
            $this->spec([$dveElement => [
                'type'     => 'Select',
                'name'     => $dveElement,
                'options'  => [
                    'value_options' => [
                        'desactive'     => 'Désactivé',
                        'active'        => 'Activé mais non visible par l\'intervenant',
                        'visualisation' => 'Activé et visible par l\'intervenant',
                        'edition'       => 'Activé et modifiable par l\'intervenant',
                    ],
                ],
                'hydrator' => [
                    'getter' => function (Statut $statut, string $name) {
                        $getter = 'get' . ucfirst($name);

                        $access = $statut->{$getter}();
                        $visu   = $statut->{$getter . 'Visualisation'}();
                        $edit   = $statut->{$getter . 'Edition'}();

                        if ($edit && $visu && $access) {
                            return 'edition';
                        } elseif ($visu && $access) {
                            return 'visualisation';
                        } elseif ($access) {
                            return 'active';
                        } else {
                            return 'desactive';
                        }
                    },
                    'setter' => function (Statut $statut, $value, string $name) {
                        $access = false;
                        $visu   = false;
                        $edit   = false;
                        switch ($value) {
                            case 'edition':
                                $edit = true;
                            case 'visualisation':
                                $visu = true;
                            case 'active':
                                $access = true;
                        }
                        $setter = 'set' . ucfirst($name);
                        $statut->{$setter}($access);
                        $statut->{$setter . 'Visualisation'}($visu);
                        $statut->{$setter . 'Edition'}($edit);
                    },
                ],
            ]]);
        }

        $this->spec([
            'pieceJustificative'    => [
                'type'     => 'Select',
                'name'     => 'pieceJustificative',
                'options'  => [
                    'value_options' => [
                        'active'        => 'Activé mais non visible par l\'intervenant',
                        'visualisation' => 'Activé et visible par l\'intervenant',
                        'edition'       => 'Activé et modifiable par l\'intervenant',
                    ],
                ],
                'hydrator' => [
                    'getter' => function (Statut $statut, string $name) {
                        $visu = $statut->getPieceJustificativeVisualisation();
                        $edit = $statut->getPieceJustificativeEdition();
                        if ($edit && $visu) {
                            return 'edition';
                        } elseif ($visu) {
                            return 'visualisation';
                        } else {
                            return 'active';
                        }
                    },
                    'setter' => function (Statut $statut, $value, string $name) {
                        $visu = false;
                        $edit = false;
                        switch ($value) {
                            case 'edition':
                                $edit = true;
                            case 'visualisation':
                                $visu = true;
                        }
                        $statut->setPieceJustificativeVisualisation($visu);
                        $statut->setPieceJustificativeEdition($edit);
                    },
                ],
            ],
            'conseilRestreint'      => [
                'type'     => 'Select',
                'name'     => 'conseilRestreint',
                'options'  => [
                    'value_options' => [
                        'desactive'     => 'Désactivé',
                        'active'        => 'Activé mais non visible par l\'intervenant',
                        'visualisation' => 'Activé et visible par l\'intervenant',
                    ],
                ],
                'hydrator' => [
                    'getter' => function (Statut $statut, string $name) {
                        $access = $statut->getConseilRestreint();
                        $visu   = $statut->getConseilRestreintVisualisation();

                        if ($visu && $access) {
                            return 'visualisation';
                        } elseif ($access) {
                            return 'active';
                        } else {
                            return 'desactive';
                        }
                    },
                    'setter' => function (Statut $statut, $value, string $name) {
                        $access = false;
                        $visu   = false;
                        switch ($value) {
                            case 'visualisation':
                                $visu = true;
                            case 'active':
                                $access = true;
                        }
                        $statut->setConseilRestreint($access);
                        $statut->setConseilRestreintVisualisation($visu);
                    },
                ],
            ],
            'conseilAcademique'     => [
                'type'     => 'Select',
                'name'     => 'conseilAcademique',
                'options'  => [
                    'value_options' => [
                        'desactive'     => 'Désactivé',
                        'active'        => 'Activé mais non visible par l\'intervenant',
                        'visualisation' => 'Activé et visible par l\'intervenant',
                    ],
                ],
                'hydrator' => [
                    'getter' => function (Statut $statut, string $name) {
                        $access = $statut->getConseilAcademique();
                        $visu   = $statut->getConseilAcademiqueVisualisation();

                        if ($visu && $access) {
                            return 'visualisation';
                        } elseif ($access) {
                            return 'active';
                        } else {
                            return 'desactive';
                        }
                    },
                    'setter' => function (Statut $statut, $value, string $name) {
                        $access = false;
                        $visu   = false;
                        switch ($value) {
                            case 'visualisation':
                                $visu = true;
                            case 'active':
                                $access = true;
                        }
                        $statut->setConseilAcademique($access);
                        $statut->setConseilAcademiqueVisualisation($visu);
                    },
                ],
            ],
            'contrat'               => [
                'type'     => 'Select',
                'name'     => 'contrat',
                'options'  => [
                    'value_options' => [
                        'desactive'     => 'Désactivé',
                        'active'        => 'Activé mais non visible par l\'intervenant',
                        'visualisation' => 'Activé et visible par l\'intervenant',
                        'depot'         => 'Activé et contrat téléversable par l\'intervenant',
                    ],
                ],
                'hydrator' => [
                    'getter' => function (Statut $statut, string $name) {
                        $access = $statut->getContrat();
                        $visu   = $statut->getContratVisualisation();
                        $depot  = $statut->getContratDepot();

                        if ($depot && $visu && $access) {
                            return 'depot';
                        } elseif ($visu && $access) {
                            return 'visualisation';
                        } elseif ($access) {
                            return 'active';
                        } else {
                            return 'desactive';
                        }
                    },
                    'setter' => function (Statut $statut, $value, string $name) {
                        $access = false;
                        $visu   = false;
                        $depot  = false;
                        switch ($value) {
                            case 'depot':
                                $depot = true;
                            case 'visualisation':
                                $visu = true;
                            case 'active':
                                $access = true;
                        }
                        $statut->setContrat($access);
                        $statut->setContratVisualisation($visu);
                        $statut->setContratDepot($depot);
                    },
                ],
            ],
            'modificationServiceDu' => [
                'type'     => 'Select',
                'name'     => 'modificationServiceDu',
                'options'  => [
                    'value_options' => [
                        'desactive'     => 'Désactivé',
                        'active'        => 'Activé mais non visible par l\'intervenant',
                        'visualisation' => 'Activé et visible par l\'intervenant',
                    ],
                ],
                'hydrator' => [
                    'getter' => function (Statut $statut, string $name) {
                        $access = $statut->getModificationServiceDu();
                        $visu   = $statut->getModificationServiceDuVisualisation();

                        if ($visu && $access) {
                            return 'visualisation';
                        } elseif ($access) {
                            return 'active';
                        } else {
                            return 'desactive';
                        }
                    },
                    'setter' => function (Statut $statut, $value, string $name) {
                        $access = false;
                        $visu   = false;
                        switch ($value) {

                            case 'visualisation':
                                $visu = true;

                            case 'active':
                                $access = true;
                        }
                        $statut->setModificationServiceDu($access);
                        $statut->setModificationServiceDuVisualisation($visu);
                    },
                ],
            ],
        ]);

        $this->build();
        $this->setAttribute('class', 'statut');

        $this->addSubmit();
        $this->get('serviceStatutaire')->setOption('suffix', 'HETD');
        $this->get('tauxChargesPatronales')->setOption('suffix', '%');
        $this->get('conseilRestreintDureeVie')->setOption('suffix', 'an(s)');
        $this->get('conseilAcademiqueDureeVie')->setOption('suffix', 'an(s)');
        $this->setLabels($labels);

        $this->setValueOptions('typeIntervenant', $this->getServiceTypeIntervenant()->getList());

        return $this;
    }

}
