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
        $this->assertArrayEquals($calc, $await);
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
                    'heures'     => 5,
                ],
                2 => [
                    'tauxRemu'   => 1,
                    'tauxValeur' => 40.91,
                    'pourcAA'    => 4 / 10,
                    'heures'     => 3,
                ],
            ],
        ];

        $await = [
            'heures'       => 8,
            'lignesAPayer' => [
                0 => [
                    'tauxRemu'   => 1,
                    'tauxValeur' => 40.91,
                    'heures'     => 8,
                    'heuresAA'   => 3,
                    'heuresAC'   => 5,
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
                    'heures'     => 5,
                ],
                2 => [
                    'tauxRemu'   => 1,
                    'tauxValeur' => 41.41,
                    'pourcAA'    => 4 / 10,
                    'heures'     => 3,
                ],
            ],
        ];

        $await = [
            'heures'       => 8,
            'lignesAPayer' => [
                0 => [
                    'tauxRemu'   => 1,
                    'tauxValeur' => 40.91,
                    'heures'     => 5,
                    'heuresAA'   => 2,
                    'heuresAC'   => 3,
                ],
                1 => [
                    'tauxRemu'   => 1,
                    'tauxValeur' => 41.41,
                    'heures'     => 3,
                    'heuresAA'   => 1,
                    'heuresAC'   => 2,
                ],
            ],
        ];

        $this->process($data, $await);
    }

}
