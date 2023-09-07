<?php declare(strict_types=1);

namespace TblPaiement;

use Paiement\Tbl\Process\Sub\Repartiteur;
use Paiement\Tbl\Process\Sub\ServiceAPayer;
use tests\OseTestCase;

final class RepartiteurTest extends OseTestCase
{
    protected Repartiteur $repartiteur;



    protected function setUp(): void
    {
        $this->repartiteur = new Repartiteur();
    }



    protected function process(array $data)
    {


        $this->assertArrayEquals($calc, $await);
    }



    public function testAll()
    {
        $tests = [
            [
                'reglePaiementAnneeCiv'  => ,
                'pourcS1PourAnneeCivile' => ,
                'semestriel'             => ,
                'periodeCode'            => ,
                'anneeId'                => ,
                'horaireDebut'           => ,
                'horaireFin'             => ,
                'await'                  => ,
            ],
        ];

        foreach ($tests as $test) {

            $this->repartiteur->setPourcS1PourAnneeCivile($test['reglePaiementAnneeCiv']);
            $this->repartiteur->setPourcS1PourAnneeCivile($test['pourcS1PourAnneeCivile']);
            $calc = $this->repartiteur->calculPourcAA(
                semestriel: $test['semestriel'],
                periodeCode: $test['periodeCode'],
                anneeId: $test['anneeId'],
                horaireDebut: $test['horaireDebut'],
                horaireFin: $test['horaireFin']
            );

            $this->assertEquals($test['await'], $calc);
        }
    }
}
