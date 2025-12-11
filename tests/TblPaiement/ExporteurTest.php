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

        $this->assertArrayEquals($await, $calc);
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
                'annee_id'                   => 2017,
                'service_id'                 => 89226,
                'service_referentiel_id'     => NULL,
                'mission_id'                 => NULL,
                'intervenant_id'             => 20970,
                'structure_id'               => 229,
                'periode_ens_id'             => 12,
                'mise_en_paiement_id'        => NULL,
                'periode_paiement_id'        => NULL,
                'centre_cout_id'             => 360,
                'domaine_fonctionnel_id'     => 1,
                'taux_remu_id'               => 1,
                'taux_horaire'               => 41.41,
                'taux_conges_payes'          => 1.0,
                'heures_a_payer_aa'          => 3.25,
                'heures_a_payer_ac'          => 4.89,
                'heures_demandees_aa'        => 0.0,
                'heures_demandees_ac'        => 0.0,
                'heures_payees_aa'           => 0.0,
                'heures_payees_ac'           => 0.0,
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
                'annee_id'                   => 2017,
                'service_id'                 => 89226,
                'service_referentiel_id'     => NULL,
                'mission_id'                 => NULL,
                'intervenant_id'             => 20970,
                'structure_id'               => 229,
                'mise_en_paiement_id'        => 76197,
                'periode_paiement_id'        => 8,
                'centre_cout_id'             => 2401,
                'domaine_fonctionnel_id'     => 1,
                'taux_remu_id'               => 1,
                'taux_horaire'               => 41.41,
                'taux_conges_payes'          => 1.0,
                'heures_a_payer_aa'          => 0.05,
                'heures_a_payer_ac'          => 0.07,
                'heures_demandees_aa'        => 0.05,
                'heures_demandees_ac'        => 0.07,
                'heures_payees_aa'           => 0.05,
                'heures_payees_ac'           => 0.07,
            ],
            [
                'annee_id'                   => 2017,
                'service_id'                 => 89226,
                'service_referentiel_id'     => NULL,
                'mission_id'                 => NULL,
                'intervenant_id'             => 20970,
                'structure_id'               => 229,
                'mise_en_paiement_id'        => 77198,
                'periode_paiement_id'        => 9,
                'centre_cout_id'             => 2401,
                'domaine_fonctionnel_id'     => 1,
                'taux_remu_id'               => 1,
                'taux_horaire'               => 41.41,
                'taux_conges_payes'          => 1.0,
                'heures_a_payer_aa'          => 0.03,
                'heures_a_payer_ac'          => 0.04,
                'heures_demandees_aa'        => 0.03,
                'heures_demandees_ac'        => 0.04,
                'heures_payees_aa'           => 0.03,
                'heures_payees_ac'           => 0.04,
            ],
            [
                'annee_id'                   => 2017,
                'service_id'                 => 89226,
                'service_referentiel_id'     => NULL,
                'mission_id'                 => NULL,
                'intervenant_id'             => 20970,
                'structure_id'               => 229,
                'mise_en_paiement_id'        => 78455,
                'periode_paiement_id'        => 10,
                'centre_cout_id'             => 2401,
                'domaine_fonctionnel_id'     => 1,
                'taux_remu_id'               => 1,
                'taux_horaire'               => 41.41,
                'taux_conges_payes'          => 1.0,
                'heures_a_payer_aa'          => 0.07,
                'heures_a_payer_ac'          => 0.12,
                'heures_demandees_aa'        => 0.07,
                'heures_demandees_ac'        => 0.12,
                'heures_payees_aa'           => 0.07,
                'heures_payees_ac'           => 0.12,
            ],
            [
                'annee_id'                   => 2017,
                'service_id'                 => 89226,
                'service_referentiel_id'     => NULL,
                'mission_id'                 => NULL,
                'intervenant_id'             => 20970,
                'structure_id'               => 229,
                'mise_en_paiement_id'        => NULL,
                'periode_paiement_id'        => NULL,
                'centre_cout_id'             => 2401,
                'domaine_fonctionnel_id'     => 1,
                'taux_remu_id'               => 1,
                'taux_horaire'               => 41.41,
                'taux_conges_payes'          => 1.0,
                'heures_a_payer_aa'          => 0.13,
                'heures_a_payer_ac'          => 0.23,
                'heures_demandees_aa'        => 0.0,
                'heures_demandees_ac'        => 0.0,
                'heures_payees_aa'           => 0.0,
                'heures_payees_ac'           => 0.0,
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
                'annee_id'                   => 2017,
                'service_id'                 => 89226,
                'service_referentiel_id'     => NULL,
                'mission_id'                 => NULL,
                'intervenant_id'             => 20970,
                'structure_id'               => 229,
                'mise_en_paiement_id'        => 76197,
                'periode_paiement_id'        => 8,
                'centre_cout_id'             => 2401,
                'domaine_fonctionnel_id'     => 1,
                'taux_remu_id'               => 1,
                'taux_horaire'               => 41.41,
                'taux_conges_payes'          => 1.0,
                'heures_a_payer_aa'          => 0.28,
                'heures_a_payer_ac'          => 0.46,
                'heures_demandees_aa'        => 0.28,
                'heures_demandees_ac'        => 0.46,
                'heures_payees_aa'           => 0.28,
                'heures_payees_ac'           => 0.46,
            ],
            [
                'annee_id'                   => 2017,
                'service_id'                 => 89226,
                'service_referentiel_id'     => NULL,
                'mission_id'                 => NULL,
                'intervenant_id'             => 20970,
                'structure_id'               => 229,
                'mise_en_paiement_id'        => 76198,
                'periode_paiement_id'        => NULL,
                'centre_cout_id'             => 2401,
                'domaine_fonctionnel_id'     => 1,
                'taux_remu_id'               => NULL,
                'taux_horaire'               => NULL,
                'taux_conges_payes'          => 1.0,
                'heures_a_payer_aa'          => 0.0,
                'heures_a_payer_ac'          => 0.0,
                'heures_demandees_aa'        => 0.02,
                'heures_demandees_ac'        => 0.01,
                'heures_payees_aa'           => 0.0,
                'heures_payees_ac'           => 0.0,
            ],
        ];

        $this->process($data, $await);
    }
}