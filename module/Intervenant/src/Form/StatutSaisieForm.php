<?php

namespace Intervenant\Form;

use Application\Entity\Db\EtatSortie;
use Application\Entity\Db\Parametre;
use Application\Form\AbstractForm;
use Dossier\Service\Traits\DossierAutreServiceAwareTrait;
use Intervenant\Entity\Db\Statut;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Intervenant\Service\TypeIntervenantServiceAwareTrait;
use Paiement\Entity\Db\TauxRemu;

/**
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class StatutSaisieForm extends AbstractForm
{
    use TypeIntervenantServiceAwareTrait;
    use ParametresServiceAwareTrait;
    use DossierAutreServiceAwareTrait;

    public function init ()
    {
        $labels = [
            'libelle'                       => 'Libellé',
            'prioritaireIndicateurs'        => 'Ces intervenants, prioritaires, seront mis en évidence au niveau des indicateurs',
            'serviceStatutaire'             => 'Nombre d\'heures de service statutaire',
            'depassementServiceDuSansHC'    => 'Le dépassement du service statutaire n\'occasionne aucune heure complémentaire',
            'tauxChargesPatronales'         => 'Taux de charges patronales',
            'tauxChargesTTC'                => 'Taux de charges TTC',
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
            'dossierEmployeurFacultatif'    => 'L\'employeur peut être facultatif',
            'dossierAdresse'                => 'Adresse',
            'dossierBanque'                 => 'Banque',
            'dossierInsee'                  => 'Numéro INSEE',
            'dossierStatut'                 => 'Statut',
            'dossierEmployeur'              => 'Employeur',
            'pieceJustificative'            => '',
            'conseilRestreint'              => 'Conseil restreint',
            'conseilRestreintDureeVie'      => 'Durée de vie du CR',
            'conseilAcademique'             => 'Conseil académique',
            'conseilAcademiqueDureeVie'     => 'Durée de vie du CAC',
            'contrat'                       => '',
            'contratEtatSortie'             => 'État de sortie à utiliser pour générer le contrat',
            'avenantEtatSortie'             => 'État de sortie à utiliser pour générer d\'éventuels avenants aux contrats',
            'serviceExterieur'              => 'L\'intervenant pourra assurer des services dans d\'autres établissements',
            'cloture'                       => 'Le service réalisé devra être clôturé avant d\'accéder aux demandes de mise en paiement',
            'modificationServiceDu'         => 'Modifications de service dû',
            'paiement'                      => '',
            'motifNonPaiement'              => 'Le gestionnaire peut déclarer des heures comme non payables',
            'formuleVisualisation'          => 'Visibilité par l\'intervenant du détail des heures pour le calcul des HETD',
            'tauxRemu'                      => 'Taux de rémunération',
            'typeIntervenant'               => 'Type d\'intervenant',
            'mission'                       => 'Visualisation/Modification de mission',
            'missionRealise'                => 'Suivi de mission',
            'offreEmploiPostuler'           => 'Postuler à une offre d\'emploi',
            'modeEnseignementPrevisionnel'  => 'Mode de saisie pour les enseignements prévisionnels',
            'modeEnseignementRealise'       => 'Mode de saisie pour les enseignements réalisés',
            'modeCalcul'                    => 'Mode de calcul de la paie (ex : A, B etc...)',
            'codeIndemnite'                 => 'Code indémnité de la paie (ex : 0204, 2251 etc...)',
            'typePaie'                      => 'Code du type ou carte de la paie (ex : 20, P etc...)',
            'modeCalculPrime'               => 'Mode de calcul primes mission (ex : A, B etc...)',
            'codeIndemnitePrime'            => 'Code indémnité primes mission (ex : 0204, 2251 etc...)',
            'typePaiePrime'                 => 'Type ou carte primes mission (ex : 20, P etc...)',

        ];

        $dveElements = [
            'dossier',
            'servicePrevu',
            'serviceRealise',
            'referentielPrevu',
            'referentielRealise',
            'mission',
            'paiement',
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
            'contratGeneration',
            'modificationServiceDuVisualisation',
            'missionRealiseEdition',
            'offreEmploiPostuler',
            'modeEnseignementPrevisionnel',
            'modeEnseignementRealise',

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

        $this->spec(['modeEnseignementPrevisionnel' => [
            'type'     => 'Select',
            'name'     => 'modeEnseignementPrevisionnel',
            'options'  => [
                'value_options' => [
                    'semestriel' => Statut::ENSEIGNEMENT_MODALITE_SEMESTRIEL,
                    'calendaire' => Statut::ENSEIGNEMENT_MODALITE_CALENDAIRE,
                ],
            ],
            'hydrator' => [
                'getter' => function (Statut $statut, string $name) {
                    return $statut->getModeEnseignementPrevisionnel();
                },
                'setter' => function (Statut $statut, $value, string $name) {
                    $statut->setModeEnseignementPrevisionnel($value);
                },
            ],


        ]]);

        $this->spec(['modeEnseignementRealise' => [
            'type'     => 'Select',
            'name'     => 'modeEnseignementRealise',
            'options'  => [
                'value_options' => [
                    'semestriel' => Statut::ENSEIGNEMENT_MODALITE_SEMESTRIEL,
                    'calendaire' => Statut::ENSEIGNEMENT_MODALITE_CALENDAIRE,
                ],
            ],
            'hydrator' => [
                'getter' => function (Statut $statut, string $name) {
                    return $statut->getModeEnseignementRealise();
                },
                'setter' => function (Statut $statut, $value, string $name) {
                    $statut->setModeEnseignementRealise($value);
                },
            ],

        ]]);


        $this->spec(['tauxChargesPatronales' => [
            'type'       => 'Text',
            'name'       => 'tauxChargesPatronales',
            'attributes' => [
                'pattern' => '[0-9]+([,.][0-9]+)?',
            ],
            'hydrator'   => [
                'getter' => function (Statut $statut, string $name) {
                    $taux = $statut->getTauxChargesPatronales();

                    return $taux * 100;
                },
                'setter' => function (Statut $statut, $value, string $name) {
                    $taux = $value / 100;
                    $statut->setTauxChargesPatronales($taux);
                },
            ],
        ]]);

        $this->spec(['tauxChargesTTC' => [
            'type'       => 'Text',
            'name'       => 'tauxChargesTTC',
            'attributes' => [
                'pattern' => '[0-9]+([,.][0-9]+)?',
            ],
            'hydrator'   => [
                'getter' => function (Statut $statut, string $name) {
                    $taux = $statut->getTauxChargesTTC();

                    return $taux * 100;
                },
                'setter' => function (Statut $statut, $value, string $name) {
                    $taux = $value / 100;
                    $statut->setTauxChargesTTC($taux);
                },
            ],
        ]]);

        $this->spec(['missionRealise' => [
            'type'     => 'Select',
            'name'     => 'missionRealise',
            'options'  => [
                'value_options' => [
                    'visualisation' => 'Visible par l\'intervenant',
                    'edition'       => 'Modifiable par l\'intervenant',
                ],
            ],
            'hydrator' => [
                'getter' => function (Statut $statut, string $name) {
                    $real = $statut->getMissionRealiseEdition() ? 'edition' : 'visualisation';

                    return $real;
                },
                'setter' => function (Statut $statut, $value, string $name) {
                    $statut->setMissionRealiseEdition($value === 'edition');
                },
            ],
        ]]);


        foreach ($dveElements as $dveElement) {
            $valueOptions = [
                'desactive'     => 'Désactivé',
                'active'        => 'Activé mais non visible par l\'intervenant',
                'visualisation' => 'Activé et visible par l\'intervenant',
                'edition'       => 'Activé et modifiable par l\'intervenant',
            ];
            if ($dveElement == 'paiement') {
                unset($valueOptions['edition']);
            }

            $this->spec([$dveElement => [
                'type'     => 'Select',
                'name'     => $dveElement,
                'options'  => [
                    'value_options' => $valueOptions,
                ],
                'hydrator' => [
                    'getter' => function (Statut $statut, string $name) {
                        $getter = 'get' . ucfirst($name);

                        $access = $statut->{$getter}();
                        $visu   = $statut->{$getter . 'Visualisation'}();
                        $edit   = method_exists($statut, $getter . 'Edition') ? $statut->{$getter . 'Edition'}() : false;

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
                        if (method_exists($statut, $setter . 'Edition')) {
                            $statut->{$setter . 'Edition'}($edit);
                        }
                    },
                ],
            ]]);
        }

        $this->spec([
            'pieceJustificative' => [
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
            'conseilRestreint'   => [
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
            'conseilAcademique'  => [
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
            'contrat'            => [
                'type'     => 'Select',
                'name'     => 'contrat',
                'options'  => [
                    'value_options' => [
                        'desactive'     => 'Désactivé',
                        'active'        => 'Activé mais non visible par l\'intervenant',
                        'visualisation' => 'Activé et visible par l\'intervenant',
                        'depot'         => 'Activé et contrat téléversable par l\'intervenant',
                        'generation'    => 'Activé et contrat téléchargeable et téléversable par l\'intervenant',
                    ],
                ],
                'hydrator' => [
                    'getter' => function (Statut $statut, string $name) {
                        $access     = $statut->getContrat();
                        $visu       = $statut->getContratVisualisation();
                        $depot      = $statut->getContratDepot();
                        $generation = $statut->getContratGeneration();

                        if ($generation && $depot && $visu && $access) {
                            return 'generation';
                        } elseif ($depot && $visu && $access) {
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
                        $access     = false;
                        $visu       = false;
                        $depot      = false;
                        $generation = false;
                        switch ($value) {
                            case 'generation':
                                $generation = true;
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
                        $statut->setContratGeneration($generation);
                    },
                ],
            ],
            //TODO : Créer un validateur pour le rendre false que quand contrat desactivé
            'contratEtatSortie'  => [
                'input' => [
                    'required' => false,
                ],
            ],
            'avenantEtatSortie'  => [
                'input' => [
                    'required' => false,
                ],
            ],
            'tauxRemu'           => [
                'input' => [
                    'required' => false,
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
            'tauxRemu'              => [
                'input' => [
                    'required' => false,
                ],
            ],
            'offreEmploiPostuler'   => [
                'type'     => 'Select',
                'name'     => 'offreEmploiPostuler',
                'options'  => [
                    'value_options' => [
                        'desactive' => 'Désactivé',
                        'edition'   => 'Activé pour l\'intervenant',
                    ],
                ],
                'hydrator' => [
                    'getter' => function (Statut $statut, string $name) {
                        $postuler = $statut->getOffreEmploiPostuler();

                        if ($postuler) {
                            return 'edition';
                        } else {
                            return 'desactive';
                        }
                    },
                    'setter' => function (Statut $statut, $value, string $name) {
                        $postuler = false;
                        switch ($value) {
                            case 'edition':
                                $postuler = true;
                            break;
                            case 'desactive':
                                $postuler = false;
                            break;
                        }
                        $statut->setOffreEmploiPostuler($postuler);
                    },
                ],
            ],
        ]);

        $this->build();
        $this->setAttribute('class', 'statut');

        $this->addSubmit();
        $this->get('serviceStatutaire')->setOption('suffix', 'HETD');
        $this->get('tauxChargesPatronales')->setOption('suffix', '%');
        $this->get('tauxChargesTTC')->setOption('suffix', '%');
        $this->get('conseilRestreintDureeVie')->setOption('suffix', 'an(s)');
        $this->get('conseilAcademiqueDureeVie')->setOption('suffix', 'an(s)');
        $this->setLabels($labels);

        $this->setValueOptions('typeIntervenant', $this->getServiceTypeIntervenant()->getList());
        $this->setValueOptions('contratEtatSortie', 'SELECT es FROM ' . EtatSortie::class . ' es ORDER BY es.libelle');
        $this->setValueOptions('avenantEtatSortie', 'SELECT es FROM ' . EtatSortie::class . ' es ORDER BY es.libelle');
        $this->setValueOptions('tauxRemu', 'SELECT tr FROM ' . TauxRemu::class . ' tr WHERE tr.histoDestruction is NULL');

        $this->get('contratEtatSortie')->setEmptyOption('- Aucun état de sortie n\'est spécifié -');
        $this->get('avenantEtatSortie')->setEmptyOption('- Utiliser le même modèle que pour les contrats -');
        $this->get('tauxRemu')->setEmptyOption('- Utilisation du taux légal standard -');

        return $this;
    }

}
