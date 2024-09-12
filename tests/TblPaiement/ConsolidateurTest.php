<?php declare(strict_types=1);

namespace TblPaiement;

use Paiement\Tbl\Process\Sub\Consolidateur;
use Paiement\Tbl\Process\Sub\ServiceAPayer;
use tests\OseTestCase;

final class ConsolidateurTest extends OseTestCase
{
    protected Consolidateur $consolidateur;



    protected function setUp(): void
    {
        $this->consolidateur = new Consolidateur();
    }



    protected function process(array $data, array $await)
    {
        $sapObject = new ServiceAPayer();
        $sapObject->fromArray($data);

        $this->consolidateur->consolider($sapObject);
        $calc = $sapObject->toArray();
        $this->assertArrayEquals($await, $calc);
    }



    public function testSimple()
    {
        $data = [
            'heures'       => 8,
            'lignesAPayer' => [
                1 => [
                    'tauxRemu'   => 1,
                    'tauxValeur' => 40.91,
                    'pourcAA'    => 4 / 10,
                    'heuresAA'   => 5,
                ],
                2 => [
                    'tauxRemu'   => 1,
                    'tauxValeur' => 40.91,
                    'pourcAA'    => 4 / 10,
                    'heuresAA'   => 3,
                ],
            ],
        ];

        $await = [
            'heures'       => 8,
            'lignesAPayer' => [
                0 => [
                    'tauxRemu'   => 1,
                    'tauxValeur' => 40.91,
                    'heuresAA'   => 8,
                ],
            ],
        ];

        $this->process($data, $await);
    }



    public function testChangementTaux()
    {
        $data = [
            'heures'       => 8,
            'lignesAPayer' => [
                1 => [
                    'tauxRemu'   => 1,
                    'tauxValeur' => 40.91,
                    'pourcAA'    => 4 / 10,
                    'heuresAA'   => 5,
                ],
                2 => [
                    'tauxRemu'   => 1,
                    'tauxValeur' => 41.41,
                    'pourcAA'    => 4 / 10,
                    'heuresAA'   => 3,
                ],
            ],
        ];

        $await = [
            'heures'       => 8,
            'lignesAPayer' => [
                0 => [
                    'tauxRemu'   => 1,
                    'tauxValeur' => 40.91,
                    'heuresAA'   => 5,
                ],
                1 => [
                    'tauxRemu'   => 1,
                    'tauxValeur' => 41.41,
                    'heuresAA'   => 3,
                ],
            ],
        ];

        $this->process($data, $await);
    }



    public function testTropDmepHeuresNegatives()
    {
        $data = [
            'lignesAPayer' => [
                [
                    'periode'         => 12,
                    'heuresAA'        => 15,
                    'misesEnPaiement' => [
                        1 => ['heuresAA' => 10],
                        2 => ['heuresAA' => 5],
                    ],
                ],
                [
                    'periode'         => 13,
                    'heuresAC'        => 21,
                    'misesEnPaiement' => [
                        2 => ['heuresAC' => 19],
                    ],
                ],
                [
                    'periode'  => 13,
                    'heuresAC' => -15,
                ],
            ],
        ];

        $await = [
            'heures'          => 0,
            'lignesAPayer'    => [
                [
                    'periode'         => 12,
                    'heuresAA'        => 15,
                    'misesEnPaiement' => [
                        1 => ['heuresAA' => 10],
                        2 => ['heuresAA' => 5],
                    ],
                ],
                [
                    'periode'         => 13,
                    'heuresAC'        => 6,
                    'misesEnPaiement' => [
                        2 => ['heuresAC' => 6],
                    ],
                ],
            ],
            'misesEnPaiement' => [
                2 => ['heuresAC' => 13],
            ],
        ];

        $this->process($data, $await);
    }
}
