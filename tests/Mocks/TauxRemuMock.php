<?php

namespace tests\Mocks;

use Paiement\Service\TauxRemuService;
use PHPUnit\Framework\TestCase;

class TauxRemuMock
{

    public static function create(TestCase $test, array $taux = []): mixed
    {
        if (empty($taux)){
            $taux = [
                1 => [ // HETD
                    'parent'  => NULL,
                    'valeurs' => [
                        '2009-07-27' => '40.58',
                        '2014-06-25' => '40.91',
                        '2017-02-01' => '41.41',
                        '2022-07-01' => '42.86',
                        '2023-07-01' => '43.5',
                    ],
                ],
                2 => [ // SMIC
                    'parent'  => NULL,
                    'valeurs' => [
                        '2014-01-01' => '9.53',
                        '2015-01-01' => '9.61',
                        '2016-01-01' => '9.67',
                        '2017-01-01' => '9.76',
                        '2018-01-01' => '9.88',
                        '2019-01-01' => '10.03',
                        '2020-01-01' => '10.15',
                        '2021-01-01' => '10.25',
                        '2021-10-01' => '10.48',
                        '2022-01-01' => '10.57',
                        '2022-05-01' => '10.85',
                        '2022-08-01' => '11.07',
                        '2023-01-01' => '11.27',
                        '2023-02-01' => '11.27',
                        '2023-05-01' => '11.52',
                    ],
                ],
                3 => [ // Taux 1
                    'parent'  => 2,
                    'valeurs' => [
                        '2014-01-01' => '1',
                    ],
                ],
                4 => [ // Taux 2
                    'parent'  => 2,
                    'valeurs' => [
                        '2014-01-01' => '2',
                    ],
                ],
                5 => [ // Taux 3
                    'parent'  => 2,
                    'valeurs' => [
                        '2014-01-01' => '2.5',
                    ],
                ],
            ];
        }

        $tauxRemuMock = $test->getMockBuilder(TauxRemuService::class)->onlyMethods(['getTauxMap'])->getMock();
        $tauxRemuMock->expects($test->any())->method('getTauxMap')->willReturn($taux);

        return $tauxRemuMock;
    }

}