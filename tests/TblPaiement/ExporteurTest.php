<?php declare(strict_types=1);

namespace TblPaiement;

use Paiement\Tbl\Process\Sub\Exporteur;
use Paiement\Tbl\Process\Sub\ServiceAPayer;
use tests\OseTestCase;

final class ExporteurTest extends OseTestCase
{
    protected Exporteur $exporteur;



    protected function setUp(): void
    {
        $this->exporteur = new Exporteur();
    }



    protected function process(array $data, array $await)
    {
        $sapObject = new ServiceAPayer();
        $sapObject->fromArray($data);

        $calc = [];
        $this->exporteur->exporter($sapObject, $calc);

        $this->assertArrayEquals($calc, $await);
    }



    public function testSansMep()
    {
        $data = [
            'key'                   => 'e-113957505-7',
            'annee'                 => 2017,
            'intervenant'           => 20970,
            'structure'             => 229,
            'service'               => 89226,
            'serviceReferentiel'    => NULL,
            'mission'               => NULL,
            'typeHeures'            => 7,
            'defDomaineFonctionnel' => 1,
            'defCentreCout'         => 360,
            'tauxCongesPayes'       => 1.0,
            'heures'                => 814,
            'lignesAPayer'          => [
                [
                    'tauxRemu'        => 1,
                    'tauxValeur'      => 41.41,
                    'heuresAA'        => 325,
                    'heuresAC'        => 489,
                    'periode'         => 12,
                    'misesEnPaiement' => [],
                ],
            ],
            'misesEnPaiement'       => [],
        ];

        $await = [
            [
                'ANNEE_ID'                   => 2017,
                'SERVICE_ID'                 => 89226,
                'SERVICE_REFERENTIEL_ID'     => NULL,
                'MISSION_ID'                 => NULL,
                'INTERVENANT_ID'             => 20970,
                'STRUCTURE_ID'               => 229,
                'PERIODE_ENS_ID'             => 12,
                'MISE_EN_PAIEMENT_ID'        => NULL,
                'PERIODE_PAIEMENT_ID'        => NULL,
                'CENTRE_COUT_ID'             => 360,
                'DOMAINE_FONCTIONNEL_ID'     => 1,
                'TAUX_REMU_ID'               => 1,
                'TAUX_HORAIRE'               => 41.41,
                'TAUX_CONGES_PAYES'          => 1.0,
                'HEURES_A_PAYER_AA'          => 3.25,
                'HEURES_A_PAYER_AC'          => 4.89,
                'HEURES_DEMANDEES_AA'        => 0.0,
                'HEURES_DEMANDEES_AC'        => 0.0,
                'HEURES_PAYEES_AA'           => 0.0,
                'HEURES_PAYEES_AC'           => 0.0,
            ],
        ];

        $this->process($data, $await);
    }



    public function testMultiMep()
    {
        $data = [
            'key'                   => 'e-113957505-6',
            'annee'                 => 2017,
            'intervenant'           => 20970,
            'structure'             => 229,
            'service'               => 89226,
            'serviceReferentiel'    => NULL,
            'mission'               => NULL,
            'typeHeures'            => 6,
            'defDomaineFonctionnel' => 1,
            'defCentreCout'         => 2401,
            'tauxCongesPayes'       => 1.0,
            'heures'                => 74,
            'lignesAPayer'          => [
                [
                    'tauxRemu'        => 1,
                    'tauxValeur'      => 41.41,
                    'heuresAA'        => 28,
                    'heuresAC'        => 46,
                    'misesEnPaiement' => [
                        76197 => [
                            'id'                 => 76197,
                            'heuresAA'           => 5,
                            'heuresAC'           => 7,
                            'date'               => '2018-05-31',
                            'periodePaiement'    => 8,
                            'centreCout'         => 2401,
                            'domaineFonctionnel' => 1,
                        ],
                        77198 => [
                            'id'                 => 77198,
                            'heuresAA'           => 3,
                            'heuresAC'           => 4,
                            'date'               => '2018-06-30',
                            'periodePaiement'    => 9,
                            'centreCout'         => 2401,
                            'domaineFonctionnel' => 1,
                        ],
                        78455 => [
                            'id'                 => 78455,
                            'heuresAA'           => 7,
                            'heuresAC'           => 12,
                            'date'               => '2018-07-31',
                            'periodePaiement'    => 10,
                            'centreCout'         => 2401,
                            'domaineFonctionnel' => 1,
                        ],
                    ],
                ],
            ],
        ];

        $await = [
            [
                'ANNEE_ID'                   => 2017,
                'SERVICE_ID'                 => 89226,
                'SERVICE_REFERENTIEL_ID'     => NULL,
                'MISSION_ID'                 => NULL,
                'INTERVENANT_ID'             => 20970,
                'STRUCTURE_ID'               => 229,
                'MISE_EN_PAIEMENT_ID'        => 76197,
                'PERIODE_PAIEMENT_ID'        => 8,
                'CENTRE_COUT_ID'             => 2401,
                'DOMAINE_FONCTIONNEL_ID'     => 1,
                'TAUX_REMU_ID'               => 1,
                'TAUX_HORAIRE'               => 41.41,
                'TAUX_CONGES_PAYES'          => 1.0,
                'HEURES_A_PAYER_AA'          => 0.05,
                'HEURES_A_PAYER_AC'          => 0.07,
                'HEURES_DEMANDEES_AA'        => 0.05,
                'HEURES_DEMANDEES_AC'        => 0.07,
                'HEURES_PAYEES_AA'           => 0.05,
                'HEURES_PAYEES_AC'           => 0.07,
            ],
            [
                'ANNEE_ID'                   => 2017,
                'SERVICE_ID'                 => 89226,
                'SERVICE_REFERENTIEL_ID'     => NULL,
                'MISSION_ID'                 => NULL,
                'INTERVENANT_ID'             => 20970,
                'STRUCTURE_ID'               => 229,
                'MISE_EN_PAIEMENT_ID'        => 77198,
                'PERIODE_PAIEMENT_ID'        => 9,
                'CENTRE_COUT_ID'             => 2401,
                'DOMAINE_FONCTIONNEL_ID'     => 1,
                'TAUX_REMU_ID'               => 1,
                'TAUX_HORAIRE'               => 41.41,
                'TAUX_CONGES_PAYES'          => 1.0,
                'HEURES_A_PAYER_AA'          => 0.03,
                'HEURES_A_PAYER_AC'          => 0.04,
                'HEURES_DEMANDEES_AA'        => 0.03,
                'HEURES_DEMANDEES_AC'        => 0.04,
                'HEURES_PAYEES_AA'           => 0.03,
                'HEURES_PAYEES_AC'           => 0.04,
            ],
            [
                'ANNEE_ID'                   => 2017,
                'SERVICE_ID'                 => 89226,
                'SERVICE_REFERENTIEL_ID'     => NULL,
                'MISSION_ID'                 => NULL,
                'INTERVENANT_ID'             => 20970,
                'STRUCTURE_ID'               => 229,
                'MISE_EN_PAIEMENT_ID'        => 78455,
                'PERIODE_PAIEMENT_ID'        => 10,
                'CENTRE_COUT_ID'             => 2401,
                'DOMAINE_FONCTIONNEL_ID'     => 1,
                'TAUX_REMU_ID'               => 1,
                'TAUX_HORAIRE'               => 41.41,
                'TAUX_CONGES_PAYES'          => 1.0,
                'HEURES_A_PAYER_AA'          => 0.07,
                'HEURES_A_PAYER_AC'          => 0.12,
                'HEURES_DEMANDEES_AA'        => 0.07,
                'HEURES_DEMANDEES_AC'        => 0.12,
                'HEURES_PAYEES_AA'           => 0.07,
                'HEURES_PAYEES_AC'           => 0.12,
            ],
            [
                'ANNEE_ID'                   => 2017,
                'SERVICE_ID'                 => 89226,
                'SERVICE_REFERENTIEL_ID'     => NULL,
                'MISSION_ID'                 => NULL,
                'INTERVENANT_ID'             => 20970,
                'STRUCTURE_ID'               => 229,
                'MISE_EN_PAIEMENT_ID'        => NULL,
                'PERIODE_PAIEMENT_ID'        => NULL,
                'CENTRE_COUT_ID'             => 2401,
                'DOMAINE_FONCTIONNEL_ID'     => 1,
                'TAUX_REMU_ID'               => 1,
                'TAUX_HORAIRE'               => 41.41,
                'TAUX_CONGES_PAYES'          => 1.0,
                'HEURES_A_PAYER_AA'          => 0.13,
                'HEURES_A_PAYER_AC'          => 0.23,
                'HEURES_DEMANDEES_AA'        => 0.0,
                'HEURES_DEMANDEES_AC'        => 0.0,
                'HEURES_PAYEES_AA'           => 0.0,
                'HEURES_PAYEES_AC'           => 0.0,
            ],
        ];

        $this->process($data, $await);
    }



    public function testTropMep()
    {
        $data = [
            'key'                   => 'e-113957505-6',
            'annee'                 => 2017,
            'intervenant'           => 20970,
            'structure'             => 229,
            'service'               => 89226,
            'serviceReferentiel'    => NULL,
            'mission'               => NULL,
            'typeHeures'            => 6,
            'defDomaineFonctionnel' => 1,
            'defCentreCout'         => 2401,
            'tauxCongesPayes'       => 1.0,
            'heures'                => 74,
            'lignesAPayer'          => [
                [
                    'tauxRemu'        => 1,
                    'tauxValeur'      => 41.41,
                    'heuresAA'        => 28,
                    'heuresAC'        => 46,
                    'misesEnPaiement' => [
                        76197 => [
                            'id'                 => 76197,
                            'heuresAA'           => 28,
                            'heuresAC'           => 46,
                            'date'               => '2018-05-31',
                            'periodePaiement'    => 8,
                            'centreCout'         => 2401,
                            'domaineFonctionnel' => 1,
                        ],
                    ],
                ],
            ],
            'misesEnPaiement'       => [
                76198 => [
                    'id'                 => 76198,
                    'heuresAA'           => 2,
                    'heuresAC'           => 1,
                    'date'               => '2018-05-31',
                    'periodePaiement'    => NULL,
                    'centreCout'         => 2401,
                    'domaineFonctionnel' => 1,
                ],
            ],
        ];

        $await = [
            [
                'ANNEE_ID'                   => 2017,
                'SERVICE_ID'                 => 89226,
                'SERVICE_REFERENTIEL_ID'     => NULL,
                'MISSION_ID'                 => NULL,
                'INTERVENANT_ID'             => 20970,
                'STRUCTURE_ID'               => 229,
                'MISE_EN_PAIEMENT_ID'        => 76197,
                'PERIODE_PAIEMENT_ID'        => 8,
                'CENTRE_COUT_ID'             => 2401,
                'DOMAINE_FONCTIONNEL_ID'     => 1,
                'TAUX_REMU_ID'               => 1,
                'TAUX_HORAIRE'               => 41.41,
                'TAUX_CONGES_PAYES'          => 1.0,
                'HEURES_A_PAYER_AA'          => 0.28,
                'HEURES_A_PAYER_AC'          => 0.46,
                'HEURES_DEMANDEES_AA'        => 0.28,
                'HEURES_DEMANDEES_AC'        => 0.46,
                'HEURES_PAYEES_AA'           => 0.28,
                'HEURES_PAYEES_AC'           => 0.46,
            ],
            [
                'ANNEE_ID'                   => 2017,
                'SERVICE_ID'                 => 89226,
                'SERVICE_REFERENTIEL_ID'     => NULL,
                'MISSION_ID'                 => NULL,
                'INTERVENANT_ID'             => 20970,
                'STRUCTURE_ID'               => 229,
                'MISE_EN_PAIEMENT_ID'        => 76198,
                'PERIODE_PAIEMENT_ID'        => NULL,
                'CENTRE_COUT_ID'             => 2401,
                'DOMAINE_FONCTIONNEL_ID'     => 1,
                'TAUX_REMU_ID'               => NULL,
                'TAUX_HORAIRE'               => NULL,
                'TAUX_CONGES_PAYES'          => 1.0,
                'HEURES_A_PAYER_AA'          => 0.0,
                'HEURES_A_PAYER_AC'          => 0.0,
                'HEURES_DEMANDEES_AA'        => 0.02,
                'HEURES_DEMANDEES_AC'        => 0.01,
                'HEURES_PAYEES_AA'           => 0.0,
                'HEURES_PAYEES_AC'           => 0.0,
            ],
        ];

        $this->process($data, $await);
    }
}