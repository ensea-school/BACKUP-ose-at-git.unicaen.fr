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



    protected function initMepData(array &$mep)
    {
        $mep['domaineFonctionnel'] = 1;
        $mep['centreCout'] = 1;
        $mep['periodePaiement'] = 1;
        $mep['date'] = '1980-09-27';
    }



    protected function process(string $regle, array $data, array $await)
    {
        // données initialisées automatiquement
        if (isset($data['misesEnPaiement'])) {
            foreach ($data['misesEnPaiement'] as $mid => $mep) {
                $this->initMepData($data['misesEnPaiement'][$mid]);
            }
        }
        if (isset($await['misesEnPaiement'])) {
            foreach ($await['misesEnPaiement'] as $mid => $mep) {
                $this->initMepData($await['misesEnPaiement'][$mid]);
            }
        }
        foreach ($await['lignesAPayer'] as $lapId => $lap) {
            if (isset($lap['misesEnPaiement'])) {
                foreach ($await['lignesAPayer'][$lapId]['misesEnPaiement'] as $mid => $mep) {
                    $this->initMepData($await['lignesAPayer'][$lapId]['misesEnPaiement'][$mid]);
                }
            }
        }

        $sapObject = new ServiceAPayer();
        $sapObject->fromArray($data);

        $this->rapprocheur->setRegle($regle);
        $this->rapprocheur->rapprocher($sapObject);
        $calc = $sapObject->toArray();
//        var_dump($regle);
//        arrayDump($calc);
        $res = $this->assertArrayEquals($calc, $await);
    }



    public function testMepEqLap()
    {
        $data = [
            'lignesAPayer'    => [
                ['heuresAA' => 5, 'heuresAC' => 4],
            ],
            'misesEnPaiement' => [
                ['id' => 1, 'heures' => 9]
            ],
        ];

        $await = [
            'lignesAPayer' => [
                [
                    'heuresAA'        => 5, 'heuresAC' => 4,
                    'misesEnPaiement' => [
                        1 => ['id' => 1, 'heuresAA' => 5, 'heuresAC' => 4]
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
                ['heuresAA' => 5, 'heuresAC' => 4],
            ],
            'misesEnPaiement' => [
                ['id' => 1, 'heures' => 0]
            ],
        ];

        $await = [
            'lignesAPayer' => [
                [
                    'heuresAA'        => 5, 'heuresAC' => 4,
                    'misesEnPaiement' => [
                        1 => ['id' => 1, 'heuresAA' => 0, 'heuresAC' => 0]
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
                ['id' => 1, 'heures' => 6]
            ],
        ];

        $awaitProrata = [
            'lignesAPayer' => [
                [
                    'heuresAA'        => 5, 'heuresAC' => 4,
                    'misesEnPaiement' => [
                        1 => ['id' => 1, 'heuresAA' => 3, 'heuresAC' => 3]
                    ],
                ],
            ],
        ];

        $awaitOrdre = [
            'lignesAPayer' => [
                [
                    'heuresAA'        => 5, 'heuresAC' => 4,
                    'misesEnPaiement' => [
                        1 => [
                            'id' => 1, 'heuresAA' => 5, 'heuresAC' => 1]
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
                ['id' => 1, 'heures' => 2],
                ['id' => 2, 'heures' => 3],
                ['id' => 3, 'heures' => 1]
            ],
        ];

        $awaitProrata = [
            'lignesAPayer' => [
                [
                    'heuresAA'        => 5, 'heuresAC' => 4,
                    'misesEnPaiement' => [
                        1 => ['id' => 1, 'heuresAA' => 1, 'heuresAC' => 1],
                        2 => ['id' => 2, 'heuresAA' => 2, 'heuresAC' => 1],
                        3 => ['id' => 3, 'heuresAA' => 1, 'heuresAC' => 0],
                    ],
                ],
            ],
        ];

        $awaitOrdre = [
            'lignesAPayer' => [
                [
                    'heuresAA'        => 5, 'heuresAC' => 4,
                    'misesEnPaiement' => [
                        1 => ['id' => 1, 'heuresAA' => 2, 'heuresAC' => 0],
                        2 => ['id' => 2, 'heuresAA' => 3, 'heuresAC' => 0],
                        3 => ['id' => 3, 'heuresAA' => 0, 'heuresAC' => 1],
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
                ['id' => 1, 'heures' => 33]
            ],
        ];

        $await = [
            'lignesAPayer' => [
                [
                    'heuresAA'        => 5, 'heuresAC' => 4,
                    'misesEnPaiement' => [
                        1 => ['id' => 1, 'heuresAA' => 5, 'heuresAC' => 4]
                    ],
                ],
                [
                    'heuresAA'        => 3, 'heuresAC' => 21,
                    'misesEnPaiement' => [
                        1 => ['id' => 1, 'heuresAA' => 3, 'heuresAC' => 21]
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
                ['id' => 1, 'heures' => 18],
                ['id' => 2, 'heures' => 15],
            ],
        ];

        $awaitProrata = [
            'lignesAPayer' => [
                [
                    'heuresAA'        => 5, 'heuresAC' => 4,
                    'misesEnPaiement' => [
                        1 => ['id' => 1, 'heuresAA' => 5, 'heuresAC' => 4],
                    ],
                ],
                [
                    'heuresAA'        => 3, 'heuresAC' => 21,
                    'misesEnPaiement' => [
                        1 => ['id' => 1, 'heuresAA' => 1, 'heuresAC' => 8],
                        2 => ['id' => 2, 'heuresAA' => 2, 'heuresAC' => 13],
                    ],
                ],
            ],
        ];

        $awaitOrdre = [
            'lignesAPayer' => [
                [
                    'heuresAA'        => 5, 'heuresAC' => 4,
                    'misesEnPaiement' => [
                        1 => ['id' => 1, 'heuresAA' => 5, 'heuresAC' => 4],
                    ],
                ],
                [
                    'heuresAA'        => 3, 'heuresAC' => 21,
                    'misesEnPaiement' => [
                        1 => ['id' => 1, 'heuresAA' => 3, 'heuresAC' => 6],
                        2 => ['id' => 2, 'heuresAA' => 0, 'heuresAC' => 15],
                    ],
                ],
            ],
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
                1 => ['id' => 1, 'heures' => 6],
                2 => ['id' => 2, 'heures' => 10],
                3 => ['id' => 3, 'heures' => 31],
            ],
        ];

        $awaitProrata = [
            'lignesAPayer' => [
                [
                    'heuresAA'        => 5, 'heuresAC' => 4,
                    'misesEnPaiement' => [
                        1 => ['id' => 1, 'heuresAA' => 3, 'heuresAC' => 3],
                        2 => ['id' => 2, 'heuresAA' => 2, 'heuresAC' => 1],
                    ],
                ],
                [
                    'heuresAA'        => 3, 'heuresAC' => 21,
                    'misesEnPaiement' => [
                        2 => ['id' => 2, 'heuresAA' => 1, 'heuresAC' => 6],
                        3 => ['id' => 3, 'heuresAA' => 2, 'heuresAC' => 15],
                    ],
                ],
            ],
            'misesEnPaiement' => [
                3 => ['id' => 3, 'heures' => 14],
            ],
        ];

        $awaitOrdre = [
            'lignesAPayer' => [
                [
                    'heuresAA'        => 5, 'heuresAC' => 4,
                    'misesEnPaiement' => [
                        1 => ['id' => 1, 'heuresAA' => 5, 'heuresAC' => 1],
                        2 => ['id' => 2, 'heuresAA' => 0, 'heuresAC' => 3],
                    ],
                ],
                [
                    'heuresAA'        => 3, 'heuresAC' => 21,
                    'misesEnPaiement' => [
                        2 => ['id' => 2, 'heuresAA' => 3, 'heuresAC' => 4],
                        3 => ['id' => 3, 'heuresAA' => 0, 'heuresAC' => 17],
                    ],
                ],
            ],
            'misesEnPaiement' => [
                3 => ['id' => 3, 'heures' => 14],
            ],
        ];

        $this->process(Rapprocheur::REGLE_PRORATA, $data, $awaitProrata);
        $this->process(Rapprocheur::REGLE_ORDRE_SAISIE, $data, $awaitOrdre);
    }
}