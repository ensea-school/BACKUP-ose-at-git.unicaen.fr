<?php declare(strict_types=1);

namespace tests\TblPaiement;

use Paiement\Tbl\Process\Sub\Arrondisseur;
use Paiement\Tbl\Process\Sub\ServiceAPayer;
use tests\OseTestCase;

final class ArrondisseurTest extends OseTestCase
{
    protected Arrondisseur $arrondisseur;



    protected function setUp(): void
    {
        $this->arrondisseur = new Arrondisseur();
    }



    protected function process(array $data, array $await)
    {
        $sapObject = new ServiceAPayer();
        $sapObject->fromArray($data);

        $this->arrondisseur->arrondir($sapObject);
        $calc = $sapObject->toArray();

        $this->assertArrayEquals($calc, $await);
    }



    public function testSansArrondi()
    {
        $data = [
            'heures'       => 8,
            'lignesAPayer' => [
                ['heures' => 5,],
                ['heures' => 3,],
            ],
        ];

        $await = [
            'heures'       => 8,
            'lignesAPayer' => [
                ['heures' => 5,],
                ['heures' => 3,],
            ],
        ];

        $this->process($data, $await);
    }



    public function testArrondiInf()
    {
        $data = [
            'heures'       => 5,
            'lignesAPayer' => [
                ['heures' => 5,],
                ['heures' => 3,],
            ],
        ];

        $await = [
            'heures'       => 5,
            'lignesAPayer' => [
                ['heures' => 4,],
                ['heures' => 1,],
            ],
        ];

        $this->process($data, $await);
    }



    public function testArrondiSup()
    {
        $data = [
            'heures'       => 10,
            'lignesAPayer' => [
                ['heures' => 5,],
                ['heures' => 3,],
            ],
        ];

        $await = [
            'heures'       => 10,
            'lignesAPayer' => [
                ['heures' => 6,],
                ['heures' => 4,],
            ],
        ];

        $this->process($data, $await);
    }
}
