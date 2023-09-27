<?php declare(strict_types=1);

namespace TblPaiement;

use Application\Entity\Db\Periode;
use Paiement\Tbl\Process\Sub\Repartiteur;
use tests\OseTestCase;

final class RepartiteurTest extends OseTestCase
{
    protected Repartiteur $repartiteur;



    protected function setUp(): void
    {
        $this->repartiteur = new Repartiteur();
    }



    public function testAll()
    {
        $tests = [
            [
                'reglePaiementAnneeCiv'  => Repartiteur::PAIEMENT_ANNEE_CIV_4_10_6_10,
                'pourcS1PourAnneeCivile' => 2 / 3,
                'pourcAAReferentiel'     => 4 / 10,
                'semestriel'             => true,
                'periodeCode'            => 'S1',
                'anneeId'                => 2023,
                'horaireDebut'           => '2022-09-01',
                'horaireFin'             => '2023-08-31',
                'await'                  => 0.4,
            ],
            [
                'reglePaiementAnneeCiv'  => Repartiteur::PAIEMENT_ANNEE_CIV_SEMESTRE_DATE,
                'pourcS1PourAnneeCivile' => 2 / 3,
                'pourcAAReferentiel'     => 4 / 10,
                'semestriel'             => true,
                'periodeCode'            => Periode::SEMESTRE_1,
                'anneeId'                => 2023,
                'horaireDebut'           => '2022-09-01',
                'horaireFin'             => '2023-08-31',
                'await'                  => 2 / 3,
            ],
            [
                'reglePaiementAnneeCiv'  => Repartiteur::PAIEMENT_ANNEE_CIV_SEMESTRE_DATE,
                'pourcS1PourAnneeCivile' => 2 / 3,
                'pourcAAReferentiel'     => 4 / 10,
                'semestriel'             => true,
                'periodeCode'            => Periode::SEMESTRE_2,
                'anneeId'                => 2023,
                'horaireDebut'           => '2022-09-01',
                'horaireFin'             => '2023-08-31',
                'await'                  => 0,
            ],
            [ // heures de référentiel
                'reglePaiementAnneeCiv'  => Repartiteur::PAIEMENT_ANNEE_CIV_SEMESTRE_DATE,
                'pourcS1PourAnneeCivile' => 2 / 3,
                'pourcAAReferentiel'     => 2 / 10,
                'semestriel'             => true,
                'periodeCode'            => null,
                'anneeId'                => 2023,
                'horaireDebut'           => '2022-09-01',
                'horaireFin'             => '2023-08-31',
                'await'                  => 2 / 10,
            ],
            [
                'reglePaiementAnneeCiv'  => Repartiteur::PAIEMENT_ANNEE_CIV_SEMESTRE_DATE,
                'pourcS1PourAnneeCivile' => 2 / 3,
                'pourcAAReferentiel'     => 4 / 10,
                'semestriel'             => false,
                'periodeCode'            => null,
                'anneeId'                => 2023,
                'horaireDebut'           => '2023-09-25',
                'horaireFin'             => '2023-09-25',
                'await'                  => 1,
            ],
            [
                'reglePaiementAnneeCiv'  => Repartiteur::PAIEMENT_ANNEE_CIV_SEMESTRE_DATE,
                'pourcS1PourAnneeCivile' => 2 / 3,
                'pourcAAReferentiel'     => 4 / 10,
                'semestriel'             => false,
                'periodeCode'            => null,
                'anneeId'                => 2023,
                'horaireDebut'           => '2024-01-15',
                'horaireFin'             => '2024-01-15',
                'await'                  => 0,
            ],
            [
                'reglePaiementAnneeCiv'  => Repartiteur::PAIEMENT_ANNEE_CIV_4_10_6_10,
                'pourcS1PourAnneeCivile' => 2 / 3,
                'pourcAAReferentiel'     => 4 / 10,
                'semestriel'             => false,
                'periodeCode'            => null,
                'anneeId'                => 2023,
                'horaireDebut'           => '2022-12-21',
                'horaireFin'             => '2025-01-11',
                'await'                  => 0.5,
            ],
        ];

        foreach ($tests as $test) {
            $this->repartiteur->setReglePaiementAnneeCiv($test['reglePaiementAnneeCiv']);
            $this->repartiteur->setPourcS1PourAnneeCivile($test['pourcS1PourAnneeCivile']);
            $this->repartiteur->setPourAAReferentiel($test['pourcAAReferentiel']);
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
