<?php

namespace Intervenant\Form;

use Administration\Service\ParametresServiceAwareTrait;
use Application\Form\AbstractForm;
use Dossier\Service\Traits\DossierAutreServiceAwareTrait;
use EtatSortie\Entity\Db\EtatSortie;
use Intervenant\Entity\Db\Statut;
use Intervenant\Service\TypeIntervenantServiceAwareTrait;
use Paiement\Entity\Db\TauxRemu;
use Utilisateur\Service\RoleServiceAwareTrait;

/**
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class StatutSaisieForm extends AbstractForm
{
    use TypeIntervenantServiceAwareTrait;
    use ParametresServiceAwareTrait;
    use DossierAutreServiceAwareTrait;
    use RoleServiceAwareTrait;


    public function init()
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
            'dossierSituationMatrimoniale'  => 'La situation matrimoniale est obligatoire pour le dossier',
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
            'missionIndemnitees'            => 'Gestion des indémnitées de fin de contrat',
            'modeEnseignementPrevisionnel'  => 'Mode de saisie pour les enseignements prévisionnels',
            'modeEnseignementRealise'       => 'Mode de saisie pour les enseignements réalisés',
            'modeCalcul'                    => 'Mode de calcul de la paie (ex : A, B etc...)',
            'codeIndemnite'                 => 'Code indemnité de la paie (ex : 0204, 2251 etc...)',
            'typePaie'                      => 'Code du type ou carte de la paie (ex : 20, P etc...)',
            'modeCalculPrime'               => 'Mode de calcul primes mission (ex : A, B etc...)',
            'codeIndemnitePrime'            => 'Code indémnité primes mission (ex : 0204, 2251 etc...)',
            'typePaiePrime'                 => 'Type ou carte primes mission (ex : 20, P etc...)',
            'missionDecret'                 => 'Decret nécessaire pour postuler',

        ];

        $dveElements = [
            'dossier',
            'pieceJustificative',
            'servicePrevu',
            'serviceRealise',
            'referentielPrevu',
            'referentielRealise',
            'mission',
            'paiement',
        ];

        $dpElements = [
            'dossierIdentiteComplementaire',
            'dossierContact',
            'dossierAdresse',
            'dossierBanque',
            'dossierInsee',
            'dossierStatut',
            'dossierEmployeur',
        ];

        $ignored = [
            'id',
            'ordre',
            'conseilRestreintVisualisation',
            'conseilAcademiqueVisualisation',
            'contratVisualisation',
            'contratDepot',
            'contratGeneration',
            'modificationServiceDuVisualisation',
            'missionRealiseEdition',
            'offreEmploiPostuler',
            'missionIndemnitees',
            'modeEnseignementPrevisionnel',
            'modeEnseignementRealise',
            'dossierIdentiteComplementaire',
            'dossierContact',
            'dossierAdresse',
            'dossierBanque',
            'dossierInsee',
            'dossierStatut',
            'dossierEmployeur',

        ];

        for ($i = 1; $i <= 5; $i++) {
            $champsAutresElements[]      = 'dossierAutre' . $i;
            $champAutre                  = $this->getServiceDossierAutre()->get($i);
            $labels['dossierAutre' . $i] = $champAutre->getLibelle();
            if ($champAutre->isObligatoire()) {
                $labels['dossierAutre' . $i] .= ' (Obligatoire)';
            }
            $ignored[] = 'dossierAutre' . $i;
            $ignored[] = 'dossierAutre' . $i . 'Visualisation';
            $ignored[] = 'dossierAutre' . $i . 'Edition';
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

        $this->spec(['missionDecret' => [
            'name'       => 'missionDecret',
            'options'    => [
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
            'attributes' => [
                'rows' => 2,
            ],
            'type'       => 'Textarea',
        ]]);

        foreach ($dpElements as $dpElement) {
            $valueOptions = [
                Statut::DONNEES_PERSONNELLES_NON_DEMANDEES              => 'Non demandé(e)',
                Statut::DONNEES_PERSONNELLES_DEMANDEES                  => 'Demandé(e) avant le recrutement',
                Statut::DONNEES_PERSONNELLES_DEMANDEES_POST_RECRUTEMENT => 'Demandé(e) aprés le recrutement',
            ];

            $this->spec([$dpElement => [
                'type'     => 'Select',
                'name'     => $dpElement,
                'options'  => [
                    'value_options' => $valueOptions,
                ],
                'hydrator' => [
                    'getter' => function (Statut $statut, string $name) {
                        $getter = 'get' . ucfirst($name);
                        return $statut->{$getter}();
                    },
                    'setter' => function (Statut $statut, $value, string $name) {

                        $setter = 'set' . ucfirst($name);
                        $statut->{$setter}($value);
                    },
                ],
            ]]);
        }

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
                        $edit   = method_exists($statut, $getter . 'Edition')
                            ? $statut->{$getter . 'Edition'}()
                            : false;

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
                                // no break
                            case 'visualisation':
                                $visu = true;
                                // no break
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

        //Gestion des champs autres

        foreach ($champsAutresElements as $champsAutresElement) {
            $valueOptions = [
                'desactive'      => 'Non demandé(e)',
                'active1'        => 'Activé(e) mais non visible par l\'intervenant avant son recrutement',
                'visualisation1' => 'Activé(e) et visible par l\'intervenant avant son recrutement',
                'edition1'       => 'Activé(e) et modifiable par l\'intervenant avant son recrutement',
                'active2'        => 'Activé(e) mais non visible par l\'intervenant après son recrutement',
                'visualisation2' => 'Activé(e) et visible par l\'intervenant après son recrutement',
                'edition2'       => 'Activé(e) et modifiable par l\'intervenant après son recrutement',
            ];


            $this->spec([$champsAutresElement => [
                'type'     => 'Select',
                'name'     => $champsAutresElement,
                'options'  => [
                    'value_options' => $valueOptions,
                ],
                'hydrator' => [
                    'getter' => function (Statut $statut, string $name) {
                        $getter = 'get' . ucfirst($name);

                        $access = $statut->{$getter}();
                        $visu   = $statut->{$getter . 'Visualisation'}();
                        $edit   = method_exists($statut, $getter . 'Edition') ? $statut->{$getter . 'Edition'}() : false;

                        if ($edit && $visu && $access != 0) {
                            return ($access == 1) ? 'edition1' : 'edition2';
                        } elseif ($visu && $access != 0) {
                            return ($access == 1) ? 'visualisation1' : 'visualisation2';
                        } elseif ($access != 0) {
                            return ($access == 1) ? 'active1' : 'active2';
                        } else {
                            return 'desactive';
                        }
                    },
                    'setter' => function (Statut $statut, $value, string $name) {
                        $access = Statut::DONNEES_PERSONNELLES_NON_DEMANDEES;
                        $visu   = false;
                        $edit   = false;

                        if (str_contains($value, 'edition')) {
                            $edit   = true;
                            $visu   = true;
                            $access = str_contains($value, '1')
                                ? Statut::DONNEES_PERSONNELLES_DEMANDEES
                                : Statut::DONNEES_PERSONNELLES_DEMANDEES_POST_RECRUTEMENT;
                        } elseif (str_contains($value, 'visualisation')) {
                            $visu   = true;
                            $access = str_contains($value, '1')
                                ? Statut::DONNEES_PERSONNELLES_DEMANDEES
                                : Statut::DONNEES_PERSONNELLES_DEMANDEES_POST_RECRUTEMENT;
                        } elseif (str_contains($value, 'active') && !str_contains($value, 'desactive')) {
                            $access = str_contains($value, '1')
                                ? Statut::DONNEES_PERSONNELLES_DEMANDEES
                                : Statut::DONNEES_PERSONNELLES_DEMANDEES_POST_RECRUTEMENT;
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
                                            // no break
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
                                            // no break
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
                                            // no break
                                        case 'depot':
                                            $depot = true;
                                            // no break
                                        case 'visualisation':
                                            $visu = true;
                                            // no break
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
                        'contratEtatSortie'     => [
                            'input' => [
                                'required' => false,
                            ],
                        ],
                        'avenantEtatSortie'     => [
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
                                            // no break
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
                        'missionIndemnitees'    => [
                            'type'     => 'Select',
                            'name'     => 'missionIndemnitees',
                            'options'  => [
                                'value_options' => [
                                    'desactive' => 'Désactivé',
                                    'edition'   => 'Activé pour l\'intervenant',
                                ],
                            ],
                            'hydrator' => [
                                'getter' => function (Statut $statut, string $name) {
                                    $indemnitees = $statut->getMissionIndemnitees();

                                    if ($indemnitees) {
                                        return 'edition';
                                    } else {
                                        return 'desactive';
                                    }
                                },
                                'setter' => function (Statut $statut, $value, string $name) {
                                    $indemnitees = false;
                                    switch ($value) {
                                        case 'edition':
                                            $indemnitees = true;
                                            break;
                                        case 'desactive':
                                            $indemnitees = false;
                                            break;
                                    }
                                    $statut->setMissionIndemnitees($indemnitees);
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
