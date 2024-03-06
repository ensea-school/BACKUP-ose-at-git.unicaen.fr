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
                1 => ['heuresAA' => 9]
            ],
        ];

        $await = [
            'lignesAPayer' => [
                [
                    'heuresAA'        => 5, 'heuresAC' => 4,
                    'misesEnPaiement' => [
                        1 => ['heuresAA' => 5, 'heuresAC' => 4]
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
                1 => ['heuresAA' => 0]
            ],
        ];

        $await = [
            'lignesAPayer' => [
                [
                    'heuresAA'        => 5, 'heuresAC' => 4,
                    'misesEnPaiement' => [
                        1 => ['heuresAA' => 0, 'heuresAC' => 0]
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
                1 => ['heuresAA' => 6]
            ],
        ];

        $awaitProrata = [
            'lignesAPayer' => [
                [
                    'heuresAA'        => 5, 'heuresAC' => 4,
                    'misesEnPaiement' => [
                        1 => ['heuresAA' => 3, 'heuresAC' => 3]
                    ],
                ],
            ],
        ];

        $awaitOrdre = [
            'lignesAPayer' => [
                [
                    'heuresAA'        => 5, 'heuresAC' => 4,
                    'misesEnPaiement' => [
                        1 => ['heuresAA' => 5, 'heuresAC' => 1]
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
            'lignesAPayer' => [
                [
                    'heuresAA'        => 5, 'heuresAC' => 4,
                    'misesEnPaiement' => [
                        1 => ['heuresAA' => 1, 'heuresAC' => 1],
                        2 => ['heuresAA' => 2, 'heuresAC' => 1],
                        3 => ['heuresAA' => 1, 'heuresAC' => 0],
                    ],
                ],
            ],
        ];

        $awaitOrdre = [
            'lignesAPayer' => [
                [
                    'heuresAA'        => 5, 'heuresAC' => 4,
                    'misesEnPaiement' => [
                        1 => ['heuresAA' => 2, 'heuresAC' => 0],
                        2 => ['heuresAA' => 3, 'heuresAC' => 0],
                        3 => ['heuresAA' => 0, 'heuresAC' => 1],
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
            'lignesAPayer' => [
                [
                    'heuresAA'        => 5, 'heuresAC' => 4,
                    'misesEnPaiement' => [
                        1 => ['heuresAA' => 5, 'heuresAC' => 4]
                    ],
                ],
                [
                    'heuresAA'        => 3, 'heuresAC' => 21,
                    'misesEnPaiement' => [
                        1 => ['heuresAA' => 3, 'heuresAC' => 21]
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
            'lignesAPayer' => [
                [
                    'heuresAA'        => 5,
                    'heuresAC'        => 4,
                    'misesEnPaiement' => [
                        1 => ['heuresAA' => 5, 'heuresAC' => 4],
                    ],
                ],
                [
                    'heuresAA'        => 3,
                    'heuresAC'        => 21,
                    'misesEnPaiement' => [
                        1 => ['heuresAA' => 1, 'heuresAC' => 8],
                        2 => ['heuresAA' => 2, 'heuresAC' => 13],
                    ],
                ],
            ],
        ];

        $awaitOrdre = [
            'lignesAPayer' => [
                [
                    'heuresAA'        => 5,
                    'heuresAC'        => 4,
                    'misesEnPaiement' => [
                        1 => ['heuresAA' => 5, 'heuresAC' => 4],
                    ],
                ],
                [
                    'heuresAA'        => 3,
                    'heuresAC'        => 21,
                    'misesEnPaiement' => [
                        1 => ['heuresAA' => 3, 'heuresAC' => 6],
                        2 => ['heuresAA' => 0, 'heuresAC' => 15],
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
                1 => ['heuresAA' => 6],
                2 => ['heuresAA' => 10],
                3 => ['heuresAA' => 31],
            ],
        ];

        $awaitProrata = [
            'lignesAPayer'    => [
                [
                    'heuresAA'        => 5, 'heuresAC' => 4,
                    'misesEnPaiement' => [
                        1 => ['heuresAA' => 3, 'heuresAC' => 3],
                        2 => ['heuresAA' => 2, 'heuresAC' => 1],
                    ],
                ],
                [
                    'heuresAA'        => 3, 'heuresAC' => 21,
                    'misesEnPaiement' => [
                        2 => ['heuresAA' => 1, 'heuresAC' => 6],
                        3 => ['heuresAA' => 2, 'heuresAC' => 15],
                    ],
                ],
            ],
            'misesEnPaiement' => [
                3 => ['id' => 3, 'heuresAA' => 14],
            ],
        ];

        $awaitOrdre = [
            'lignesAPayer'    => [
                [
                    'heuresAA'        => 5,
                    'heuresAC'        => 4,
                    'misesEnPaiement' => [
                        1 => ['heuresAA' => 5, 'heuresAC' => 1],
                        2 => ['heuresAA' => 0, 'heuresAC' => 3],
                    ],
                ],
                [
                    'heuresAA'        => 3,
                    'heuresAC'        => 21,
                    'misesEnPaiement' => [
                        2 => ['heuresAA' => 3, 'heuresAC' => 4],
                        3 => ['heuresAA' => 0, 'heuresAC' => 17],
                    ],
                ],
            ],
            'misesEnPaiement' => [
                3 => ['heuresAA' => 14],
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
            'lignesAPayer'    => [
                [
                    'periode'         => 12, 'heuresAA' => 15,
                    'misesEnPaiement' => [
                        1 => ['heuresAA' => 10],
                        2 => ['heuresAA' => 5],
                    ],
                ],
                [
                    'periode'         => 13, 'heuresAC' => 21,
                    'misesEnPaiement' => [
                        2 => ['heuresAC' => 6],
                    ],
                ],
                ['periode' => 13, 'heuresAC' => -15],
            ],
            'misesEnPaiement' => [
                2 => ['heuresAA' => 13, 'heuresAC' => 0],
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
            'heures'       => 0,
            'lignesAPayer' => [
                [
                    'periode'         => 12,
                    'heuresAA'        => 3360,
                    'misesEnPaiement' => [1 => ['heuresAA' => 3360]],
                ],
                [
                    'periode'         => 12,
                    'heuresAA'        => 3360,
                    'misesEnPaiement' => [1 => ['heuresAA' => 3360]],
                ],
                [
                    'periode'  => 12,
                    'heuresAA' => 3360,
                ],
                [
                    'periode'  => 12,
                    'heuresAA' => -3360,
                ],
                [
                    'periode'         => 12,
                    'heuresAA'        => 5040,
                    'misesEnPaiement' => [1 => ['heuresAA' => 5040]],
                ],
                [
                    'periode'         => 13,
                    'heuresAA'        => 1680,
                    'misesEnPaiement' => [1 => ['heuresAA' => 1680]],
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
            'heures'       => 0,
            'lignesAPayer' => [
                [
                    'periode'  => 12,
                    'heuresAA' => -3360,
                ],
                [
                    'periode'  => 12,
                    'heuresAA' => 1360,

                ],
                [
                    'periode'  => 12,
                    'heuresAA' => 2000,

                ],
                [
                    'periode'         => 12,
                    'heuresAA'        => 3360,
                    'misesEnPaiement' => [1 => ['heuresAA' => 3360]],
                ],
                [
                    'periode'         => 12,
                    'heuresAA'        => 3360,
                    'misesEnPaiement' => [1 => ['heuresAA' => 3360]],
                ],
                [
                    'periode'         => 12,
                    'heuresAA'        => 5040,
                    'misesEnPaiement' => [1 => ['heuresAA' => 5040]],
                ],
                [
                    'periode'         => 13,
                    'heuresAA'        => 1680,
                    'misesEnPaiement' => [1 => ['heuresAA' => 1680]],
                ],
            ],
        ];

        $this->process(Rapprocheur::REGLE_PRORATA, $data, $await);
        $this->process(Rapprocheur::REGLE_ORDRE_SAISIE, $data, $await);
    }
}