<?php declare(strict_types=1);

namespace TblPaiement;

use Paiement\Tbl\Process\Sub\Rapprocheur;
use Paiement\Tbl\Process\Sub\ServiceAPayer;
use tests\OseTestCase;

final class RapprocheurTest extends OseTestCase
{
    protected Rapprocheur $rapprocheur;



    protected function setUp(): void
    {
        $this->rapprocheur = new Rapprocheur();
    }



    protected function process(string $regle, array $data, array $await)
    {
        $sapObject = new ServiceAPayer();
        $sapObject->fromArray($data);

        $this->rapprocheur->setRegle($regle);
        $this->rapprocheur->rapprocher($sapObject);
        $calc = $sapObject->toArray();
        $res = $this->assertArrayEquals($await, $calc);
    }



    public function testMepEqLap()
    {
        $data = [
            'lignesAPayer'    => [
                ['heuresAA' => 5, 'heuresAC' => 4],
            ],
            'misesEnPaiement' => [
                1 => ['heuresAA' => 9]
            ],
        ];

        $await = [
            'key'                   => null,
            'annee'                 => null,
            'typeIntervenant'       => null,
            'intervenant'           => null,
            'structure'             => null,
            'service'               => null,
            'mission'               => null,
            'serviceReferentiel'    => null,
            'typeHeures'            => null,
            'defDomaineFonctionnel' => null,
            'defCentreCout'         => null,
            'tauxCongesPayes'       => null,
            'heures'                => 0,
            'lignesAPayer' => [
                ['id'              => 0,
                 'key'             => null,
                 'volumeHoraireId' => null,
                 'periode'         => null,
                 'tauxRemu'        => null,
                 'tauxValeur'      => null,
                 'pourcAA'         => null,
                 'heuresAA'        => 5,
                 'heuresAC'        => 4,
                 'intBuffer1'      => null,
                 'intBuffer2'      => null,
                 'misesEnPaiement' => [

                     0 => [
                         'id'                 => 1,
                         'heuresAA'           => 5,
                         'heuresAC'           => 4,
                         'date'               => null,
                         'periodePaiement'    => null,
                         'centreCout'         => null,
                         'domaineFonctionnel' => null,
                     ],
                    ],
                ],
            ],
        ];

        $this->process(Rapprocheur::REGLE_PRORATA, $data, $await);
        $this->process(Rapprocheur::REGLE_ORDRE_SAISIE, $data, $await);
    }



    public function testLapZero()
    {
        $data = [
            'lignesAPayer'    => [
                [
                    'heuresAA' => 5,
                    'heuresAC' => 4],
            ],
            'misesEnPaiement' => [
                1 => ['heuresAA' => 0]
            ],
        ];

        $await = [
            'key'                   => null,
            'annee'                 => null,
            'typeIntervenant'       => null,
            'intervenant'           => null,
            'structure'             => null,
            'service'               => null,
            'mission'               => null,
            'serviceReferentiel'    => null,
            'typeHeures'            => null,
            'defDomaineFonctionnel' => null,
            'defCentreCout'         => null,
            'tauxCongesPayes'       => null,
            'heures'                => 0,
            'lignesAPayer' => [
                [
                    'id'              => 0,
                    'key'             => null,
                    'volumeHoraireId' => null,
                    'periode'         => null,
                    'tauxRemu'        => null,
                    'tauxValeur'      => null,
                    'pourcAA'         => null,
                    'heuresAA'        => 5,
                    'heuresAC'        => 4,
                    'misesEnPaiement' => [
                        0 => [
                            'id'                 => 1,
                            'heuresAA'           => 0,
                            'heuresAC'           => 0,
                            'date'               => null,
                            'periodePaiement'    => null,
                            'centreCout'         => null,
                            'domaineFonctionnel' => null,
                        ],
                    ],
                ],
            ],
        ];

        $this->process(Rapprocheur::REGLE_PRORATA, $data, $await);
        $this->process(Rapprocheur::REGLE_ORDRE_SAISIE, $data, $await);
    }



    public function testMepInfLap()
    {
        $data = [
            'lignesAPayer'    => [
                ['heuresAA' => 5, 'heuresAC' => 4],
            ],
            'misesEnPaiement' => [
                1 => ['heuresAA' => 6],
            ],
        ];

        $awaitProrata = [
            'key'                   => null,
            'annee'                 => null,
            'typeIntervenant'       => null,
            'intervenant'           => null,
            'structure'             => null,
            'service'               => null,
            'mission'               => null,
            'serviceReferentiel'    => null,
            'typeHeures'            => null,
            'defDomaineFonctionnel' => null,
            'defCentreCout'         => null,
            'tauxCongesPayes'       => null,
            'heures'                => 0,
            'lignesAPayer' => [
                [
                    'id'              => 0,
                    'key'             => null,
                    'volumeHoraireId' => null,
                    'periode'         => null,
                    'tauxRemu'        => null,
                    'tauxValeur'      => null,
                    'pourcAA'         => null,
                    'heuresAA'        => 5,
                    'heuresAC'        => 4,
                    'misesEnPaiement' => [
                        0 => [
                            'id'                 => 1,
                            'heuresAA'           => 3,
                            'heuresAC'           => 3,
                            'date'               => null,
                            'periodePaiement'    => null,
                            'centreCout'         => null,
                            'domaineFonctionnel' => null,
                        ],
                    ],
                ],
            ],
        ];

        $awaitOrdre = [
            'key'                   => null,
            'annee'                 => null,
            'typeIntervenant'       => null,
            'intervenant'           => null,
            'structure'             => null,
            'service'               => null,
            'mission'               => null,
            'serviceReferentiel'    => null,
            'typeHeures'            => null,
            'defDomaineFonctionnel' => null,
            'defCentreCout'         => null,
            'tauxCongesPayes'       => null,
            'heures'                => 0,
            'lignesAPayer' => [
                [
                    'id'              => 0,
                    'key'             => null,
                    'volumeHoraireId' => null,
                    'periode'         => null,
                    'tauxRemu'        => null,
                    'tauxValeur'      => null,
                    'pourcAA'         => null,
                    'heuresAA'        => 5,
                    'heuresAC'        => 4,
                    'misesEnPaiement' => [
                        0 => [
                            'id'                 => 1,
                            'heuresAA'           => 5,
                            'heuresAC'           => 1,
                            'date'               => null,
                            'periodePaiement'    => null,
                            'centreCout'         => null,
                            'domaineFonctionnel' => null,
                        ],
                    ],
                ],
            ],
        ];

        $this->process(Rapprocheur::REGLE_PRORATA, $data, $awaitProrata);
        $this->process(Rapprocheur::REGLE_ORDRE_SAISIE, $data, $awaitOrdre);
    }



    public function testMepInfLapMultiMep()
    {
        $data = [
            'lignesAPayer'    => [
                ['heuresAA' => 5, 'heuresAC' => 4],
            ],
            'misesEnPaiement' => [
                1 => ['heuresAA' => 2],
                2 => ['heuresAA' => 3],
                3 => ['heuresAA' => 1]
            ],
        ];

        $awaitProrata = [
            'key'                   => null,
            'annee'                 => null,
            'typeIntervenant'       => null,
            'intervenant'           => null,
            'structure'             => null,
            'service'               => null,
            'mission'               => null,
            'serviceReferentiel'    => null,
            'typeHeures'            => null,
            'defDomaineFonctionnel' => null,
            'defCentreCout'         => null,
            'tauxCongesPayes'       => null,
            'heures'                => 0,
            'lignesAPayer' => [
                [
                    'id'              => 0,
                    'key'             => null,
                    'volumeHoraireId' => null,
                    'periode'         => null,
                    'tauxRemu'        => null,
                    'tauxValeur'      => null,
                    'pourcAA'         => null,
                    'heuresAA'        => 5,
                    'heuresAC'        => 4,
                    'misesEnPaiement' => [
                        0 => [
                            'id'                 => 1,
                            'heuresAA'           => 1,
                            'heuresAC'           => 1,
                            'date'               => null,
                            'periodePaiement'    => null,
                            'centreCout'         => null,
                            'domaineFonctionnel' => null,
                        ],
                        1 => [
                            'id'                 => 2,
                            'heuresAA'           => 2,
                            'heuresAC'           => 1,
                            'date'               => null,
                            'periodePaiement'    => null,
                            'centreCout'         => null,
                            'domaineFonctionnel' => null,
                        ],
                        2 => [
                            'id'                 => 3,
                            'heuresAA'           => 1,
                            'heuresAC'           => 0,
                            'date'               => null,
                            'periodePaiement'    => null,
                            'centreCout'         => null,
                            'domaineFonctionnel' => null,],
                    ],
                ],
            ],
        ];

        $awaitOrdre = [
            'key'                   => null,
            'annee'                 => null,
            'typeIntervenant'       => null,
            'intervenant'           => null,
            'structure'             => null,
            'service'               => null,
            'mission'               => null,
            'serviceReferentiel'    => null,
            'typeHeures'            => null,
            'defDomaineFonctionnel' => null,
            'defCentreCout'         => null,
            'tauxCongesPayes'       => null,
            'heures'                => 0,
            'lignesAPayer' => [
                [
                    'id'              => 0,
                    'key'             => null,
                    'volumeHoraireId' => null,
                    'periode'         => null,
                    'tauxRemu'        => null,
                    'tauxValeur'      => null,
                    'pourcAA'         => null,
                    'heuresAA'        => 5,
                    'heuresAC'        => 4,
                    'misesEnPaiement' => [
                        0 => [
                            'id'                 => 1,
                            'heuresAA'           => 2,
                            'heuresAC'           => 0,
                            'date'               => null,
                            'periodePaiement'    => null,
                            'centreCout'         => null,
                            'domaineFonctionnel' => null,
                        ],
                        1 => [
                            'id'                 => 2,
                            'heuresAA'           => 3,
                            'heuresAC'           => 0,
                            'date'               => null,
                            'periodePaiement'    => null,
                            'centreCout'         => null,
                            'domaineFonctionnel' => null,
                        ],
                        2 => [
                            'id'                 => 3,
                            'heuresAA'           => 0,
                            'heuresAC'           => 1,
                            'date'               => null,
                            'periodePaiement'    => null,
                            'centreCout'         => null,
                            'domaineFonctionnel' => null,],
                    ],
                ],
            ],
        ];

        $this->process(Rapprocheur::REGLE_PRORATA, $data, $awaitProrata);
        $this->process(Rapprocheur::REGLE_ORDRE_SAISIE, $data, $awaitOrdre);
    }



    public function testMultiLap()
    {
        $data = [
            'lignesAPayer'    => [
                ['heuresAA' => 5, 'heuresAC' => 4],
                ['heuresAA' => 3, 'heuresAC' => 21],
            ],
            'misesEnPaiement' => [
                1 => ['heuresAA' => 33]
            ],
        ];

        $await = [
            'key'                   => null,
            'annee'                 => null,
            'typeIntervenant'       => null,
            'intervenant'           => null,
            'structure'             => null,
            'service'               => null,
            'mission'               => null,
            'serviceReferentiel'    => null,
            'typeHeures'            => null,
            'defDomaineFonctionnel' => null,
            'defCentreCout'         => null,
            'tauxCongesPayes'       => null,
            'heures'                => 0,
            'lignesAPayer' => [
                [
                    'id'              => 0,
                    'key'             => null,
                    'volumeHoraireId' => null,
                    'periode'         => null,
                    'tauxRemu'        => null,
                    'tauxValeur'      => null,
                    'pourcAA'         => null,
                    'heuresAA'        => 5,
                    'heuresAC'        => 4,
                    'misesEnPaiement' => [
                        0 => [
                            'id'                 => 1,
                            'heuresAA'           => 5,
                            'heuresAC'           => 4,
                            'date'               => null,
                            'periodePaiement'    => null,
                            'centreCout'         => null,
                            'domaineFonctionnel' => null,
                        ],
                    ],
                ],
                [
                    'id'              => 1,
                    'key'             => null,
                    'volumeHoraireId' => null,
                    'periode'         => null,
                    'tauxRemu'        => null,
                    'tauxValeur'      => null,
                    'pourcAA'         => null,
                    'heuresAA'        => 3,
                    'heuresAC'        => 21,
                    'misesEnPaiement' => [
                        0 => [
                            'id'                 => 1,
                            'heuresAA'           => 3,
                            'heuresAC'           => 21,
                            'date'               => null,
                            'periodePaiement'    => null,
                            'centreCout'         => null,
                            'domaineFonctionnel' => null,
                        ],
                    ],
                ],
            ],
        ];

        $this->process(Rapprocheur::REGLE_PRORATA, $data, $await);
        $this->process(Rapprocheur::REGLE_ORDRE_SAISIE, $data, $await);
    }



    public function testMultiLapMep()
    {
        $data = [
            'lignesAPayer'    => [
                ['heuresAA' => 5, 'heuresAC' => 4],
                ['heuresAA' => 3, 'heuresAC' => 21],
            ],
            'misesEnPaiement' => [
                1 => ['heuresAA' => 18],
                2 => ['heuresAA' => 15],
            ],
        ];

        $awaitProrata = [
            'key'                   => null,
            'annee'                 => null,
            'typeIntervenant'       => null,
            'intervenant'           => null,
            'structure'             => null,
            'service'               => null,
            'mission'               => null,
            'serviceReferentiel'    => null,
            'typeHeures'            => null,
            'defDomaineFonctionnel' => null,
            'defCentreCout'         => null,
            'tauxCongesPayes'       => null,
            'heures'                => 0,
            'lignesAPayer' => [
                [
                    'id'              => 0,
                    'key'             => null,
                    'volumeHoraireId' => null,
                    'periode'         => null,
                    'tauxRemu'        => null,
                    'tauxValeur'      => null,
                    'pourcAA'         => null,
                    'heuresAA'        => 5,
                    'heuresAC'        => 4,
                    'misesEnPaiement' => [
                        0 => [
                            'id'                 => 1,
                            'heuresAA'           => 5,
                            'heuresAC'           => 4,
                            'date'               => null,
                            'periodePaiement'    => null,
                            'centreCout'         => null,
                            'domaineFonctionnel' => null,
                        ],
                    ],
                ],
                [
                    'id'              => 1,
                    'key'             => null,
                    'volumeHoraireId' => null,
                    'periode'         => null,
                    'tauxRemu'        => null,
                    'tauxValeur'      => null,
                    'pourcAA'         => null,
                    'heuresAA'        => 3,
                    'heuresAC'        => 21,
                    'misesEnPaiement' => [
                        0 => [
                            'id'                 => 1,
                            'heuresAA'           => 1,
                            'heuresAC'           => 8,
                            'date'               => null,
                            'periodePaiement'    => null,
                            'centreCout'         => null,
                            'domaineFonctionnel' => null,
                        ],
                        1 => [
                            'id'                 => 2,
                            'heuresAA'           => 2,
                            'heuresAC'           => 13,
                            'date'               => null,
                            'periodePaiement'    => null,
                            'centreCout'         => null,
                            'domaineFonctionnel' => null,
                        ],
                    ],
                ],
            ],
        ];

        $awaitOrdre = [
            'key'                   => null,
            'annee'                 => null,
            'typeIntervenant'       => null,
            'intervenant'           => null,
            'structure'             => null,
            'service'               => null,
            'mission'               => null,
            'serviceReferentiel'    => null,
            'typeHeures'            => null,
            'defDomaineFonctionnel' => null,
            'defCentreCout'         => null,
            'tauxCongesPayes'       => null,
            'heures'                => 0,
            'lignesAPayer' => [
                [
                    'id'              => 0,
                    'key'             => null,
                    'volumeHoraireId' => null,
                    'periode'         => null,
                    'tauxRemu'        => null,
                    'tauxValeur'      => null,
                    'pourcAA'         => null,
                    'heuresAA'        => 5,
                    'heuresAC'        => 4,
                    'misesEnPaiement' => [
                        0 => [
                            'id'                 => 1,
                            'heuresAA'           => 5,
                            'heuresAC'           => 4,
                            'date'               => null,
                            'periodePaiement'    => null,
                            'centreCout'         => null,
                            'domaineFonctionnel' => null,
                        ],
                ],
                ],

                [
                    'id'              => 1,
                    'key'             => null,
                    'volumeHoraireId' => null,
                    'periode'         => null,
                    'tauxRemu'        => null,
                    'tauxValeur'      => null,
                    'pourcAA'         => null,
                    'heuresAA'        => 3,
                    'heuresAC'        => 21,
                    'misesEnPaiement' => [
                        0 => [
                            'id'                 => 1,
                            'heuresAA'           => 3,
                            'heuresAC'           => 6,
                            'date'               => null,
                            'periodePaiement'    => null,
                            'centreCout'         => null,
                            'domaineFonctionnel' => null,
                        ],
                        1 => [
                            'id'                 => 2,
                            'heuresAA'           => 0,
                            'heuresAC'           => 15,
                            'date'               => null,
                            'periodePaiement'    => null,
                            'centreCout'         => null,
                            'domaineFonctionnel' => null,
                        ],

                    ],
                ],]
        ];

        $this->process(Rapprocheur::REGLE_PRORATA, $data, $awaitProrata);
        $this->process(Rapprocheur::REGLE_ORDRE_SAISIE, $data, $awaitOrdre);
    }



    public function testMultiLapSupMep()
    {
        $data = [
            'lignesAPayer'    => [
                ['heuresAA' => 5, 'heuresAC' => 4],
                ['heuresAA' => 3, 'heuresAC' => 21],
            ],
            'misesEnPaiement' => [
                1 => ['heuresAA' => 6],
                2 => ['heuresAA' => 10],
                3 => ['heuresAA' => 31],
            ],
        ];

        $awaitProrata = [
            'key'                   => null,
            'annee'                 => null,
            'typeIntervenant'       => null,
            'intervenant'           => null,
            'structure'             => null,
            'service'               => null,
            'mission'               => null,
            'serviceReferentiel'    => null,
            'typeHeures'            => null,
            'defDomaineFonctionnel' => null,
            'defCentreCout'         => null,
            'tauxCongesPayes'       => null,
            'heures'                => 0,
            'lignesAPayer'          => [
                [
                    'id'              => 0,
                    'key'             => null,
                    'volumeHoraireId' => null,
                    'periode'         => null,
                    'tauxRemu'        => null,
                    'tauxValeur'      => null,
                    'pourcAA'         => null,
                    'heuresAA'        => 5,
                    'heuresAC'        => 4,
                    'misesEnPaiement' => [
                        0 => [
                            'id'                 => 1,
                            'heuresAA'           => 3,
                            'heuresAC'           => 3,
                            'date'               => null,
                            'periodePaiement'    => null,
                            'centreCout'         => null,
                            'domaineFonctionnel' => null,
                        ],
                        1 => [
                            'id'                 => 2,
                            'heuresAA'           => 2,
                            'heuresAC'           => 1,
                            'date'               => null,
                            'periodePaiement'    => null,
                            'centreCout'         => null,
                            'domaineFonctionnel' => null,
                        ],
                    ],
                ],
                [
                    'id'              => 1,
                    'key'             => null,
                    'volumeHoraireId' => null,
                    'periode'         => null,
                    'tauxRemu'        => null,
                    'tauxValeur'      => null,
                    'pourcAA'         => null,
                    'heuresAA'        => 3,
                    'heuresAC'        => 21,
                    'misesEnPaiement' => [
                        0 => [
                            'id'                 => 2,
                            'heuresAA'           => 1,
                            'heuresAC'           => 6,
                            'date'               => null,
                            'periodePaiement'    => null,
                            'centreCout'         => null,
                            'domaineFonctionnel' => null,
                        ],
                        1 => [
                            'id'                 => 3,
                            'heuresAA'           => 2,
                            'heuresAC'           => 15,
                            'date'               => null,
                            'periodePaiement'    => null,
                            'centreCout'         => null,
                            'domaineFonctionnel' => null,
                        ],
                    ],
                ],
            ],
            'misesEnPaiement'       => [
                0 => [

                    'id'                 => 3,
                    'heuresAA'           => 14,
                    'heuresAC'           => 0,
                    'date'               => null,
                    'periodePaiement'    => null,
                    'centreCout'         => null,
                    'domaineFonctionnel' => null,],
            ],
        ];


        $awaitOrdre = [
            'key'                   => null,
            'annee'                 => null,
            'typeIntervenant'       => null,
            'intervenant'           => null,
            'structure'             => null,
            'service'               => null,
            'mission'               => null,
            'serviceReferentiel'    => null,
            'typeHeures'            => null,
            'defDomaineFonctionnel' => null,
            'defCentreCout'         => null,
            'tauxCongesPayes'       => null,
            'heures'                => 0,
            'lignesAPayer'          => [
                [
                    'id'              => 0,
                    'key'             => null,
                    'volumeHoraireId' => null,
                    'periode'         => null,
                    'tauxRemu'        => null,
                    'tauxValeur'      => null,
                    'pourcAA'         => null,
                    'heuresAA'        => 5,
                    'heuresAC'        => 4,
                    'misesEnPaiement' => [
                        0 => [
                            'id'                 => 1,
                            'heuresAA'           => 5,
                            'heuresAC'           => 1,
                            'date'               => null,
                            'periodePaiement'    => null,
                            'centreCout'         => null,
                            'domaineFonctionnel' => null,
                        ],
                        1 => [
                            'id'                 => 2,
                            'heuresAA'           => 0,
                            'heuresAC'           => 3,
                            'date'               => null,
                            'periodePaiement'    => null,
                            'centreCout'         => null,
                            'domaineFonctionnel' => null,
                        ],
                    ],
                ],
                [
                    'id'              => 1,
                    'key'             => null,
                    'volumeHoraireId' => null,
                    'periode'         => null,
                    'tauxRemu'        => null,
                    'tauxValeur'      => null,
                    'pourcAA'         => null,
                    'heuresAA'        => 3,
                    'heuresAC'        => 21,
                    'misesEnPaiement' => [
                        0 => [
                            'id'                 => 2,
                            'heuresAA'           => 3,
                            'heuresAC'           => 4,
                            'date'               => null,
                            'periodePaiement'    => null,
                            'centreCout'         => null,
                            'domaineFonctionnel' => null,
                        ],
                        1 => [
                            'id'                 => 3,
                            'heuresAA'           => 0,
                            'heuresAC'           => 17,
                            'date'               => null,
                            'periodePaiement'    => null,
                            'centreCout'         => null,
                            'domaineFonctionnel' => null,
                        ],
                    ],
                ],
            ],
            'misesEnPaiement'       => [
                0 => [

                    'id'                 => 3,
                    'heuresAA'           => 14,
                    'heuresAC'           => 0,
                    'date'               => null,
                    'periodePaiement'    => null,
                    'centreCout'         => null,
                    'domaineFonctionnel' => null,],
            ],

        ];

        $this->process(Rapprocheur::REGLE_PRORATA, $data, $awaitProrata);
        $this->process(Rapprocheur::REGLE_ORDRE_SAISIE, $data, $awaitOrdre);
    }



    public function testTropDmepHeuresNegatives()
    {
        $data = [
            'lignesAPayer'    => [
                ['periode' => 12, 'heuresAA' => 15],
                ['periode' => 13, 'heuresAC' => 21],
                ['periode' => 13, 'heuresAC' => -15],
            ],
            'misesEnPaiement' => [
                1 => ['heuresAA' => 10],
                2 => ['heuresAA' => 24],
            ],
        ];

        $await = [
            'key'                   => null,
            'annee'                 => null,
            'typeIntervenant'       => null,
            'intervenant'           => null,
            'structure'             => null,
            'service'               => null,
            'mission'               => null,
            'serviceReferentiel'    => null,
            'typeHeures'            => null,
            'defDomaineFonctionnel' => null,
            'defCentreCout'         => null,
            'tauxCongesPayes'       => null,
            'heures'                => 0,
            'lignesAPayer'    => [
                [
                    'id'              => 0,
                    'key'             => null,
                    'volumeHoraireId' => null,
                    'periode'         => 12,
                    'tauxRemu'        => null,
                    'tauxValeur'      => null,
                    'pourcAA'         => null,
                    'heuresAA'        => 15,
                    'heuresAC'        => 0,
                    'misesEnPaiement' => [
                        0 => [
                            'id'                 => 1,
                            'heuresAA'           => 10,
                            'heuresAC'           => 0,
                            'date'               => null,
                            'periodePaiement'    => null,
                            'centreCout'         => null,
                            'domaineFonctionnel' => null,
                        ],
                        1 => [
                            'id'                 => 2,
                            'heuresAA'           => 5,
                            'heuresAC'           => 0,
                            'date'               => null,
                            'periodePaiement'    => null,
                            'centreCout'         => null,
                            'domaineFonctionnel' => null,
                        ],
                    ],
                ],
                [
                    'id'              => 1,
                    'key'             => null,
                    'volumeHoraireId' => null,
                    'periode'         => 13,
                    'tauxRemu'        => null,
                    'tauxValeur'      => null,
                    'pourcAA'         => null,
                    'heuresAA'        => 0,
                    'heuresAC'        => 21,
                    'misesEnPaiement' => [
                        0 => [
                            'id'                 => 2,
                            'heuresAA'           => 0,
                            'heuresAC'           => 6,
                            'date'               => null,
                            'periodePaiement'    => null,
                            'centreCout'         => null,
                            'domaineFonctionnel' => null,
                        ],

                    ],
                ],
                [

                    'id'              => 2,
                    'key'             => null,
                    'volumeHoraireId' => null,
                    'periode'         => 13,
                    'tauxRemu'        => null,
                    'tauxValeur'      => null,
                    'pourcAA'         => null,
                    'heuresAA'        => 0,
                    'heuresAC'        => -15,

                ],
            ],

            'misesEnPaiement' => [
                0 => [

                    'id'                 => 2,
                    'heuresAA'           => 13,
                    'heuresAC'           => 0,
                    'date'               => null,
                    'periodePaiement'    => null,
                    'centreCout'         => null,
                    'domaineFonctionnel' => null,],
            ],
        ];

        $this->process(Rapprocheur::REGLE_PRORATA, $data, $await);
        $this->process(Rapprocheur::REGLE_ORDRE_SAISIE, $data, $await);
    }



    public function testHeuresNegativesPbSemestre()
    {
        $data = [
            'lignesAPayer'    => [
                ['periode' => 12, 'heuresAA' => 3360],
                ['periode' => 12, 'heuresAA' => 3360],
                ['periode' => 12, 'heuresAA' => 3360],
                ['periode' => 12, 'heuresAA' => -3360],
                ['periode' => 12, 'heuresAA' => 5040],
                ['periode' => 13, 'heuresAA' => 1680],
            ],
            'misesEnPaiement' => [
                1 => ['heuresAA' => 13440],
            ],
        ];

        $await = [
            'key'                   => null,
            'annee'                 => null,
            'typeIntervenant'       => null,
            'intervenant'           => null,
            'structure'             => null,
            'service'               => null,
            'mission'               => null,
            'serviceReferentiel'    => null,
            'typeHeures'            => null,
            'defDomaineFonctionnel' => null,
            'defCentreCout'         => null,
            'tauxCongesPayes'       => null,
            'heures'                => 0,
            'lignesAPayer' => [
                [
                    'id'              => 0,
                    'key'             => null,
                    'volumeHoraireId' => null,
                    'periode'         => 12,
                    'tauxRemu'        => null,
                    'tauxValeur'      => null,
                    'pourcAA'         => null,
                    'heuresAA'        => 3360,
                    'heuresAC'        => 0,
                    'misesEnPaiement' => [
                        0 => [
                            'id'                 => 1,
                            'heuresAA'           => 3360,
                            'heuresAC'           => 0,
                            'date'               => null,
                            'periodePaiement'    => null,
                            'centreCout'         => null,
                            'domaineFonctionnel' => null,
                        ],

                    ],
                ],
                [
                    'id'              => 1,
                    'key'             => null,
                    'volumeHoraireId' => null,
                    'periode'         => 12,
                    'tauxRemu'        => null,
                    'tauxValeur'      => null,
                    'pourcAA'         => null,
                    'heuresAA'        => 3360,
                    'heuresAC'        => 0,
                    'misesEnPaiement' => [
                        0 => [
                            'id'                 => 1,
                            'heuresAA'           => 3360,
                            'heuresAC'           => 0,
                            'date'               => null,
                            'periodePaiement'    => null,
                            'centreCout'         => null,
                            'domaineFonctionnel' => null,
                        ],

                    ],
                ],
                [
                    'id'              => 2,
                    'key'             => null,
                    'volumeHoraireId' => null,
                    'periode'         => 12,
                    'tauxRemu'        => null,
                    'tauxValeur'      => null,
                    'pourcAA'         => null,
                    'heuresAA'        => 3360,
                    'heuresAC'        => 0,

                ],
                [
                    'id'              => 3,
                    'key'             => null,
                    'volumeHoraireId' => null,
                    'periode'         => 12,
                    'tauxRemu'        => null,
                    'tauxValeur'      => null,
                    'pourcAA'         => null,
                    'heuresAA'        => -3360,
                    'heuresAC'        => 0,

                ],
                [
                    'id'              => 4,
                    'key'             => null,
                    'volumeHoraireId' => null,
                    'periode'         => 12,
                    'tauxRemu'        => null,
                    'tauxValeur'      => null,
                    'pourcAA'         => null,
                    'heuresAA'        => 5040,
                    'heuresAC'        => 0,
                    'misesEnPaiement' => [
                        0 => [
                            'id'                 => 1,
                            'heuresAA'           => 5040,
                            'heuresAC'           => 0,
                            'date'               => null,
                            'periodePaiement'    => null,
                            'centreCout'         => null,
                            'domaineFonctionnel' => null,
                        ],

                    ],
                ],
                [
                    'id'              => 5,
                    'key'             => null,
                    'volumeHoraireId' => null,
                    'periode'         => 13,
                    'tauxRemu'        => null,
                    'tauxValeur'      => null,
                    'pourcAA'         => null,
                    'heuresAA'        => 1680,
                    'heuresAC'        => 0,
                    'misesEnPaiement' => [
                        0 => [
                            'id'                 => 1,
                            'heuresAA'           => 1680,
                            'heuresAC'           => 0,
                            'date'               => null,
                            'periodePaiement'    => null,
                            'centreCout'         => null,
                            'domaineFonctionnel' => null,
                        ],

                    ],
                ],


            ],
        ];

        $this->process(Rapprocheur::REGLE_PRORATA, $data, $await);
        $this->process(Rapprocheur::REGLE_ORDRE_SAISIE, $data, $await);
    }



    public function testHeuresNegativesPbSemestre2()
    {
        $data = [
            'lignesAPayer'    => [
                ['periode' => 12, 'heuresAA' => -3360],
                ['periode' => 12, 'heuresAA' => 1360],
                ['periode' => 12, 'heuresAA' => 2000],
                ['periode' => 12, 'heuresAA' => 3360],
                ['periode' => 12, 'heuresAA' => 3360],
                ['periode' => 12, 'heuresAA' => 5040],
                ['periode' => 13, 'heuresAA' => 1680],
            ],
            'misesEnPaiement' => [
                1 => ['heuresAA' => 13440],
            ],
        ];

        $await = [
            'key'                   => null,
            'annee'                 => null,
            'typeIntervenant'       => null,
            'intervenant'           => null,
            'structure'             => null,
            'service'               => null,
            'mission'               => null,
            'serviceReferentiel'    => null,
            'typeHeures'            => null,
            'defDomaineFonctionnel' => null,
            'defCentreCout'         => null,
            'tauxCongesPayes'       => null,
            'heures'                => 0,
            'lignesAPayer'          => [
                [
                    'id'              => 0,
                    'key'             => null,
                    'volumeHoraireId' => null,
                    'periode'         => 12,
                    'tauxRemu'        => null,
                    'tauxValeur'      => null,
                    'pourcAA'         => null,
                    'heuresAA'        => -3360,
                    'heuresAC'        => 0,

                ],
                [
                    'id'              => 1,
                    'key'             => null,
                    'volumeHoraireId' => null,
                    'periode'         => 12,
                    'tauxRemu'        => null,
                    'tauxValeur'      => null,
                    'pourcAA'         => null,
                    'heuresAA'        => 1360,
                    'heuresAC'        => 0,

                ],
                [
                    'id'              => 2,
                    'key'             => null,
                    'volumeHoraireId' => null,
                    'periode'         => 12,
                    'tauxRemu'        => null,
                    'tauxValeur'      => null,
                    'pourcAA'         => null,
                    'heuresAA'        => 2000,
                    'heuresAC'        => 0,

                ],
                [
                    'id'              => 3,
                    'key'             => null,
                    'volumeHoraireId' => null,
                    'periode'         => 12,
                    'tauxRemu'        => null,
                    'tauxValeur'      => null,
                    'pourcAA'         => null,
                    'heuresAA'        => 3360,
                    'heuresAC'        => 0,
                    'misesEnPaiement' => [
                        0 => [
                            'id'                 => 1,
                            'heuresAA'           => 3360,
                            'heuresAC'           => 0,
                            'date'               => null,
                            'periodePaiement'    => null,
                            'centreCout'         => null,
                            'domaineFonctionnel' => null,
                        ],

                    ],
                ],
                [
                    'id'              => 4,
                    'key'             => null,
                    'volumeHoraireId' => null,
                    'periode'         => 12,
                    'tauxRemu'        => null,
                    'tauxValeur'      => null,
                    'pourcAA'         => null,
                    'heuresAA'        => 3360,
                    'heuresAC'        => 0,
                    'misesEnPaiement' => [
                        0 => [
                            'id'                 => 1,
                            'heuresAA'           => 3360,
                            'heuresAC'           => 0,
                            'date'               => null,
                            'periodePaiement'    => null,
                            'centreCout'         => null,
                            'domaineFonctionnel' => null,
                        ],

                    ],
                ],
                [
                    'id'              => 5,
                    'key'             => null,
                    'volumeHoraireId' => null,
                    'periode'         => 12,
                    'tauxRemu'        => null,
                    'tauxValeur'      => null,
                    'pourcAA'         => null,
                    'heuresAA'        => 5040,
                    'heuresAC'        => 0,
                    'misesEnPaiement' => [
                        0 => [
                            'id'                 => 1,
                            'heuresAA'           => 5040,
                            'heuresAC'           => 0,
                            'date'               => null,
                            'periodePaiement'    => null,
                            'centreCout'         => null,
                            'domaineFonctionnel' => null,
                        ],

                    ],
                ],

                [
                    'id'              => 6,
                    'key'             => null,
                    'volumeHoraireId' => null,
                    'periode'         => 13,
                    'tauxRemu'        => null,
                    'tauxValeur'      => null,
                    'pourcAA'         => null,
                    'heuresAA'        => 1680,
                    'heuresAC'        => 0,
                    'misesEnPaiement' => [
                        0 => [
                            'id'                 => 1,
                            'heuresAA'           => 1680,
                            'heuresAC'           => 0,
                            'date'               => null,
                            'periodePaiement'    => null,
                            'centreCout'         => null,
                            'domaineFonctionnel' => null,
                        ],

                    ],
                ],

            ],
        ];

        $this->process(Rapprocheur::REGLE_PRORATA, $data, $await);
        $this->process(Rapprocheur::REGLE_ORDRE_SAISIE, $data, $await);
    }
}