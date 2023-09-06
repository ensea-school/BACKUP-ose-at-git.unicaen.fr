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
        $this->assertArrayEquals($calc, $await);
    }



    /*
     * $data = [
        2 => [ // mep < lap
            'lap' => [
                ['aa' => 5, 'ac' => 4],
            ],
            'mep' => [
                ['h' => 6],
            ],
        ],
        3 => [ // 3 mep
            'lap' => [
                ['aa' => 5, 'ac' => 4],
            ],
            'mep' => [
                ['h' => 2],
                ['h' => 3],
                ['h' => 1],
            ],
        ],
        4 => [ // n mep, = lap
            'lap' => [
                ['aa' => 5, 'ac' => 4],
                ['aa' => 3, 'ac' => 21],
            ],
            'mep' => [
                ['h' => 33],
            ],
        ],
        5 => [ // n mep, =  2 lap
            'lap' => [
                ['aa' => 5, 'ac' => 4],
                ['aa' => 3, 'ac' => 21],
            ],
            'mep' => [
                ['h' => 18],
                ['h' => 15],
            ],
        ],
        6 => [ // n mep, <  2 lap
            'lap' => [
                ['aa' => 5, 'ac' => 4],
                ['aa' => 3, 'ac' => 21],
            ],
            'mep' => [
                ['h' => 3],
                ['h' => 10],
                ['h' => 31],
            ],
        ],
    ];
     *
     */


    public function testMepEqLap()
    {
        $data = [
            'lignesAPayer'    => [
                [
                    'heuresAA' => 5,
                    'heuresAC' => 4,
                ],
            ],
            'misesEnPaiement' => [
                [
                    'id'     => 1,
                    'heures' => 9,
                ]
            ],
        ];

        $await = [
            'lignesAPayer' => [
                [
                    'heuresAA'        => 5,
                    'heuresAC'        => 4,
                    'misesEnPaiement' => [
                        1 => [
                            'id' => 1,
                            'heuresAA' => 5,
                            'heuresAC' => 4,
                        ]
                    ],
                ],
            ],
        ];

        $this->process(Rapprocheur::REGLE_PRORATA, $data, $await);
        $this->process(Rapprocheur::REGLE_ORDRE_SAISIE, $data, $await);
    }

}