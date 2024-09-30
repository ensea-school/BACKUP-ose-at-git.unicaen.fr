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

        $this->assertArrayEquals($await, $calc );
    }



    public function testSansArrondi()
    {
        $data = [
            'heures'       => 8,
            'lignesAPayer' => [
                ['heuresAA' => 5],
                ['heuresAA' => 3],
            ],
        ];

        $await = [
            'lignesAPayer' => [
                ['heuresAA' => 5],
                ['heuresAA' => 3],
            ],
        ];

        $this->process($data, $await);
    }



    public function testArrondiInf()
    {
        $data = [
            'heures'       => 5,
            'lignesAPayer' => [
                ['heuresAA' => 5],
                ['heuresAA' => 3],
            ],
        ];

        $await = [
            'lignesAPayer' => [
                ['heuresAA' => 4],
                ['heuresAA' => 1],
            ],
        ];

        $this->process($data, $await);
    }



    public function testArrondiSup()
    {
        $data = [
            'heures'       => 10,
            'lignesAPayer' => [
                ['heuresAA' => 5],
                ['heuresAA' => 3],
            ],
        ];

        $await = [
            'lignesAPayer' => [
                ['heuresAA' => 6],
                ['heuresAA' => 4],
            ],
        ];

        $this->process($data, $await);
    }
}
