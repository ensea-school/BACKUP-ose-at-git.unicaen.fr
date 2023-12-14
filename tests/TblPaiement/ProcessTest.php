<?php declare(strict_types=1);

namespace TblPaiement;

use Application\Service\ParametresService;
use Paiement\Service\TauxRemuService;
use Paiement\Tbl\Process\PaiementProcess;
use Paiement\Tbl\Process\Sub\Rapprocheur;
use Paiement\Tbl\Process\Sub\Repartiteur;
use tests\OseTestCase;
use UnicaenTbl\Service\TableauBordService;

final class ProcessTest extends OseTestCase
{
    protected PaiementProcess $pp;

    const HORAIRE_DEBUT_AA = '2023-09-01';
    const HORAIRE_DEBUT_AC = '2024-01-01';



    protected function setUp(): void
    {
        /** @var \UnicaenTbl\Service\TableauBordService $c */
        $c = \OseAdmin::instance()->container()->get(TableauBordService::class);

        $tauxRemuMock = $this->getMockBuilder(TauxRemuService::class)->getMock();
        $tauxRemuMock->expects($this->any())
            ->method('tauxValeur')
            ->willReturnMap([
                [1, self::HORAIRE_DEBUT_AA, 41.41],
                [1, self::HORAIRE_DEBUT_AC, 42.86],
                [2, self::HORAIRE_DEBUT_AA, 11],
                [2, self::HORAIRE_DEBUT_AC, 12],
            ]);

        $ptbl = $c->getTableauBord('paiement');
        $this->pp = $ptbl->getProcess();
        $this->pp->setServiceTauxRemu($tauxRemuMock);

        $parametresMock = $this->getMockBuilder(ParametresService::class)->getMock();
        $this->pp->setServiceParametres($parametresMock);
    }



    protected function useParametres(array $parametres)
    {
        $this->pp->getServiceParametres()->expects($this->any())
            ->method('get')
            ->willReturnMap($parametres);

    }



    protected function process(array $data, array $await)
    {
        $calc = $this->pp->testData($data);

        $this->assertArrayEquals($calc, $await);
    }



    public function testReelSansMep()
    {
        $this->useParametres([
            ['regle_repartition_annee_civile', Rapprocheur::REGLE_PRORATA],
            ['regle_paiement_annee_civile', Repartiteur::PAIEMENT_ANNEE_CIV_4_10_6_10],
            ['pourc_s1_pour_annee_civile', '0.67'],
            ['pourc_aa_referentiel', '0.4'],
        ]);

        $data = [
            [
                'KEY'                        => 'r-121085149-10',
                'CALCUL_SEMESTRIEL'          => '1',
                'A_PAYER_ID'                 => '18483',
                'ANNEE_ID'                   => '2022',
                'SERVICE_ID'                 => NULL,
                'SERVICE_REFERENTIEL_ID'     => '18483',
                'MISSION_ID'                 => NULL,
                'FORMULE_RES_SERVICE_ID'     => NULL,
                'FORMULE_RES_SERVICE_REF_ID' => '121085149',
                'INTERVENANT_ID'             => '882254',
                'STRUCTURE_ID'               => '594',
                'TYPE_HEURES_ID'             => '10',
                'DEF_DOMAINE_FONCTIONNEL_ID' => '26',
                'DEF_CENTRE_COUT_ID'         => NULL,
                'TAUX_REMU_ID'               => '1',
                'TAUX_CONGES_PAYES'          => '1',
                'HEURES'                     => NULL,
                'LAP_HEURES'                 => '8',
                'PERIODE_ENS_ID'             => NULL,
                'PERIODE_ENS_CODE'           => NULL,
                'HORAIRE_DEBUT'              => self::HORAIRE_DEBUT_AA,
                'HORAIRE_FIN'                => self::HORAIRE_DEBUT_AA,
                'MISE_EN_PAIEMENT_ID'        => NULL,
                'DATE_MISE_EN_PAIEMENT'      => NULL,
                'PERIODE_PAIEMENT_ID'        => NULL,
                'MEP_CENTRE_COUT_ID'         => NULL,
                'MEP_HEURES'                 => NULL,
                'MEP_DOMAINE_FONCTIONNEL_ID' => NULL,
            ],
            [
                'KEY'                        => 'e-121085147-6',
                'CALCUL_SEMESTRIEL'          => '0',
                'A_PAYER_ID'                 => '624904',
                'ANNEE_ID'                   => '2022',
                'SERVICE_ID'                 => '258201',
                'SERVICE_REFERENTIEL_ID'     => NULL,
                'MISSION_ID'                 => NULL,
                'FORMULE_RES_SERVICE_ID'     => '121085147',
                'FORMULE_RES_SERVICE_REF_ID' => NULL,
                'INTERVENANT_ID'             => '882254',
                'STRUCTURE_ID'               => '594',
                'TYPE_HEURES_ID'             => '6',
                'DEF_DOMAINE_FONCTIONNEL_ID' => '1',
                'DEF_CENTRE_COUT_ID'         => '2615',
                'TAUX_REMU_ID'               => '1',
                'TAUX_CONGES_PAYES'          => '1',
                'HEURES'                     => '36',
                'LAP_HEURES'                 => '15',
                'PERIODE_ENS_ID'             => '12',
                'PERIODE_ENS_CODE'           => 'S1',
                'HORAIRE_DEBUT'              => self::HORAIRE_DEBUT_AA,
                'HORAIRE_FIN'                => self::HORAIRE_DEBUT_AA,
                'MISE_EN_PAIEMENT_ID'        => NULL,
                'DATE_MISE_EN_PAIEMENT'      => NULL,
                'PERIODE_PAIEMENT_ID'        => NULL,
                'MEP_CENTRE_COUT_ID'         => NULL,
                'MEP_HEURES'                 => NULL,
                'MEP_DOMAINE_FONCTIONNEL_ID' => NULL,
            ],
            [
                'KEY'                        => 'e-121085147-6',
                'CALCUL_SEMESTRIEL'          => '0',
                'A_PAYER_ID'                 => '624905',
                'ANNEE_ID'                   => '2022',
                'SERVICE_ID'                 => '258201',
                'SERVICE_REFERENTIEL_ID'     => NULL,
                'MISSION_ID'                 => NULL,
                'FORMULE_RES_SERVICE_ID'     => '121085147',
                'FORMULE_RES_SERVICE_REF_ID' => NULL,
                'INTERVENANT_ID'             => '882254',
                'STRUCTURE_ID'               => '594',
                'TYPE_HEURES_ID'             => '6',
                'DEF_DOMAINE_FONCTIONNEL_ID' => '1',
                'DEF_CENTRE_COUT_ID'         => '2615',
                'TAUX_REMU_ID'               => '1',
                'TAUX_CONGES_PAYES'          => '1',
                'HEURES'                     => '36',
                'LAP_HEURES'                 => '21',
                'PERIODE_ENS_ID'             => '13',
                'PERIODE_ENS_CODE'           => 'S2',
                'HORAIRE_DEBUT'              => self::HORAIRE_DEBUT_AC,
                'HORAIRE_FIN'                => self::HORAIRE_DEBUT_AC,
                'MISE_EN_PAIEMENT_ID'        => NULL,
                'DATE_MISE_EN_PAIEMENT'      => NULL,
                'PERIODE_PAIEMENT_ID'        => NULL,
                'MEP_CENTRE_COUT_ID'         => NULL,
                'MEP_HEURES'                 => NULL,
                'MEP_DOMAINE_FONCTIONNEL_ID' => NULL,
            ],
        ];

        $await = [
            [
                'FORMULE_RES_SERVICE_REF_ID' => 121085149,
                'MISE_EN_PAIEMENT_ID'        => NULL,
                'PERIODE_PAIEMENT_ID'        => NULL,
                'TAUX_REMU_ID'               => 1,
                'TAUX_HORAIRE'               => 41.41,
                'PERIODE_ENS_ID'             => NULL,
                'TAUX_CONGES_PAYES'          => 1.0,
                'HEURES_A_PAYER_AA'          => 3.2,
                'HEURES_A_PAYER_AC'          => 4.8,
                'HEURES_DEMANDEES_AA'        => 0.0,
                'HEURES_DEMANDEES_AC'        => 0.0,
                'HEURES_PAYEES_AA'           => 0.0,
                'HEURES_PAYEES_AC'           => 0.0,
            ],
            [
                'FORMULE_RES_SERVICE_ID' => 121085147,
                'MISE_EN_PAIEMENT_ID'    => NULL,
                'PERIODE_PAIEMENT_ID'    => NULL,
                'CENTRE_COUT_ID'         => 2615,
                'DOMAINE_FONCTIONNEL_ID' => 1,
                'TAUX_REMU_ID'           => 1,
                'TAUX_HORAIRE'           => 41.41,
                'PERIODE_ENS_ID'         => 12,
                'TAUX_CONGES_PAYES'      => 1.0,
                'HEURES_A_PAYER_AA'      => 0.0,
                'HEURES_A_PAYER_AC'      => 15.0,
                'HEURES_DEMANDEES_AA'    => 0.0,
                'HEURES_DEMANDEES_AC'    => 0.0,
                'HEURES_PAYEES_AA'       => 0.0,
                'HEURES_PAYEES_AC'       => 0.0,
            ],
            [
                'FORMULE_RES_SERVICE_ID' => 121085147,
                'MISE_EN_PAIEMENT_ID'    => NULL,
                'PERIODE_PAIEMENT_ID'    => NULL,
                'TAUX_REMU_ID'           => 1,
                'TAUX_HORAIRE'           => 42.86,
                'PERIODE_ENS_ID'         => 13,
                'TAUX_CONGES_PAYES'      => 1.0,
                'HEURES_A_PAYER_AA'      => 0.0,
                'HEURES_A_PAYER_AC'      => 21.0,
                'HEURES_DEMANDEES_AA'    => 0.0,
                'HEURES_DEMANDEES_AC'    => 0.0,
                'HEURES_PAYEES_AA'       => 0.0,
                'HEURES_PAYEES_AC'       => 0.0,
            ],
        ];

        $this->process($data, $await);
    }



    public function testReelAvecMep()
    {
        $this->useParametres([
            ['regle_repartition_annee_civile', Rapprocheur::REGLE_PRORATA],
            ['regle_paiement_annee_civile', Repartiteur::PAIEMENT_ANNEE_CIV_4_10_6_10],
            ['pourc_s1_pour_annee_civile', '0.67'],
            ['pourc_aa_referentiel', '0.4'],
        ]);

        $data = [
            [
                'KEY'                        => 'e-121085147-6',
                'CALCUL_SEMESTRIEL'          => '0',
                'A_PAYER_ID'                 => '624904',
                'ANNEE_ID'                   => '2022',
                'SERVICE_ID'                 => '258201',
                'SERVICE_REFERENTIEL_ID'     => NULL,
                'MISSION_ID'                 => NULL,
                'FORMULE_RES_SERVICE_ID'     => '121085147',
                'FORMULE_RES_SERVICE_REF_ID' => NULL,
                'INTERVENANT_ID'             => '882254',
                'STRUCTURE_ID'               => '594',
                'TYPE_HEURES_ID'             => '6',
                'DEF_DOMAINE_FONCTIONNEL_ID' => '1',
                'DEF_CENTRE_COUT_ID'         => '2615',
                'TAUX_REMU_ID'               => '1',
                'TAUX_CONGES_PAYES'          => '1',
                'HEURES'                     => '36',
                'LAP_HEURES'                 => '15',
                'PERIODE_ENS_ID'             => '12',
                'PERIODE_ENS_CODE'           => 'S1',
                'HORAIRE_DEBUT'              => self::HORAIRE_DEBUT_AA,
                'HORAIRE_FIN'                => self::HORAIRE_DEBUT_AA,
                'MISE_EN_PAIEMENT_ID'        => '229683',
                'DATE_MISE_EN_PAIEMENT'      => NULL,
                'PERIODE_PAIEMENT_ID'        => NULL,
                'MEP_CENTRE_COUT_ID'         => '2615',
                'MEP_HEURES'                 => '10',
                'MEP_DOMAINE_FONCTIONNEL_ID' => '1',
            ],
            [
                'KEY'                        => 'e-121085147-6',
                'CALCUL_SEMESTRIEL'          => '0',
                'A_PAYER_ID'                 => '624904',
                'ANNEE_ID'                   => '2022',
                'SERVICE_ID'                 => '258201',
                'SERVICE_REFERENTIEL_ID'     => NULL,
                'MISSION_ID'                 => NULL,
                'FORMULE_RES_SERVICE_ID'     => '121085147',
                'FORMULE_RES_SERVICE_REF_ID' => NULL,
                'INTERVENANT_ID'             => '882254',
                'STRUCTURE_ID'               => '594',
                'TYPE_HEURES_ID'             => '6',
                'DEF_DOMAINE_FONCTIONNEL_ID' => '1',
                'DEF_CENTRE_COUT_ID'         => '2615',
                'TAUX_REMU_ID'               => '1',
                'TAUX_CONGES_PAYES'          => '1',
                'HEURES'                     => '36',
                'LAP_HEURES'                 => '15',
                'PERIODE_ENS_ID'             => '12',
                'PERIODE_ENS_CODE'           => 'S1',
                'HORAIRE_DEBUT'              => self::HORAIRE_DEBUT_AA,
                'HORAIRE_FIN'                => self::HORAIRE_DEBUT_AA,
                'MISE_EN_PAIEMENT_ID'        => '229684',
                'DATE_MISE_EN_PAIEMENT'      => NULL,
                'PERIODE_PAIEMENT_ID'        => NULL,
                'MEP_CENTRE_COUT_ID'         => '2614',
                'MEP_HEURES'                 => '24',
                'MEP_DOMAINE_FONCTIONNEL_ID' => '1',
            ],
            [
                'KEY'                        => 'e-121085147-6',
                'CALCUL_SEMESTRIEL'          => '0',
                'A_PAYER_ID'                 => '624905',
                'ANNEE_ID'                   => '2022',
                'SERVICE_ID'                 => '258201',
                'SERVICE_REFERENTIEL_ID'     => NULL,
                'MISSION_ID'                 => NULL,
                'FORMULE_RES_SERVICE_ID'     => '121085147',
                'FORMULE_RES_SERVICE_REF_ID' => NULL,
                'INTERVENANT_ID'             => '882254',
                'STRUCTURE_ID'               => '594',
                'TYPE_HEURES_ID'             => '6',
                'DEF_DOMAINE_FONCTIONNEL_ID' => '1',
                'DEF_CENTRE_COUT_ID'         => '2615',
                'TAUX_REMU_ID'               => '1',
                'TAUX_CONGES_PAYES'          => '1',
                'HEURES'                     => '36',
                'LAP_HEURES'                 => '21',
                'PERIODE_ENS_ID'             => '13',
                'PERIODE_ENS_CODE'           => 'S2',
                'HORAIRE_DEBUT'              => self::HORAIRE_DEBUT_AC,
                'HORAIRE_FIN'                => self::HORAIRE_DEBUT_AC,
                'MISE_EN_PAIEMENT_ID'        => '229683',
                'DATE_MISE_EN_PAIEMENT'      => NULL,
                'PERIODE_PAIEMENT_ID'        => NULL,
                'MEP_CENTRE_COUT_ID'         => '2615',
                'MEP_HEURES'                 => '10',
                'MEP_DOMAINE_FONCTIONNEL_ID' => '1',
            ],
            [
                'KEY'                        => 'e-121085147-6',
                'CALCUL_SEMESTRIEL'          => '0',
                'A_PAYER_ID'                 => '624905',
                'ANNEE_ID'                   => '2022',
                'SERVICE_ID'                 => '258201',
                'SERVICE_REFERENTIEL_ID'     => NULL,
                'MISSION_ID'                 => NULL,
                'FORMULE_RES_SERVICE_ID'     => '121085147',
                'FORMULE_RES_SERVICE_REF_ID' => NULL,
                'INTERVENANT_ID'             => '882254',
                'STRUCTURE_ID'               => '594',
                'TYPE_HEURES_ID'             => '6',
                'DEF_DOMAINE_FONCTIONNEL_ID' => '1',
                'DEF_CENTRE_COUT_ID'         => '2615',
                'TAUX_REMU_ID'               => '1',
                'TAUX_CONGES_PAYES'          => '1',
                'HEURES'                     => '36',
                'LAP_HEURES'                 => '21',
                'PERIODE_ENS_ID'             => '13',
                'PERIODE_ENS_CODE'           => 'S2',
                'HORAIRE_DEBUT'              => self::HORAIRE_DEBUT_AC,
                'HORAIRE_FIN'                => self::HORAIRE_DEBUT_AC,
                'MISE_EN_PAIEMENT_ID'        => '229684',
                'DATE_MISE_EN_PAIEMENT'      => NULL,
                'PERIODE_PAIEMENT_ID'        => NULL,
                'MEP_CENTRE_COUT_ID'         => '2614',
                'MEP_HEURES'                 => '24',
                'MEP_DOMAINE_FONCTIONNEL_ID' => '1',
            ],
        ];

        $await = [
            [
                'CENTRE_COUT_ID'         => 2615,
                'FORMULE_RES_SERVICE_ID' => 121085147,
                'HEURES_A_PAYER_AA'      => 0.0,
                'HEURES_A_PAYER_AC'      => 10.0,
                'HEURES_DEMANDEES_AA'    => 0.0,
                'HEURES_DEMANDEES_AC'    => 10.0,
                'HEURES_PAYEES_AA'       => 0.0,
                'HEURES_PAYEES_AC'       => 0.0,
                'MISE_EN_PAIEMENT_ID'    => 229683,
                'PERIODE_ENS_ID'         => 12,
            ],
            [
                'CENTRE_COUT_ID'         => 2614,
                'FORMULE_RES_SERVICE_ID' => 121085147,
                'HEURES_A_PAYER_AA'      => 0.0,
                'HEURES_A_PAYER_AC'      => 5.0,
                'HEURES_DEMANDEES_AA'    => 0.0,
                'HEURES_DEMANDEES_AC'    => 5.0,
                'HEURES_PAYEES_AA'       => 0.0,
                'HEURES_PAYEES_AC'       => 0.0,
                'MISE_EN_PAIEMENT_ID'    => 229684,
                'PERIODE_ENS_ID'         => 12,
            ],
            [
                'CENTRE_COUT_ID'         => 2614,
                'FORMULE_RES_SERVICE_ID' => 121085147,
                'HEURES_A_PAYER_AA'      => 0.0,
                'HEURES_A_PAYER_AC'      => 19.0,
                'HEURES_DEMANDEES_AA'    => 0.0,
                'HEURES_DEMANDEES_AC'    => 19.0,
                'HEURES_PAYEES_AA'       => 0.0,
                'HEURES_PAYEES_AC'       => 0.0,
                'MISE_EN_PAIEMENT_ID'    => NULL,
                'PERIODE_ENS_ID'         => 13,
            ],
            [
                'FORMULE_RES_SERVICE_ID' => 121085147,
                'MISE_EN_PAIEMENT_ID'    => NULL,
                'CENTRE_COUT_ID'         => 2615,
                'HEURES_A_PAYER_AA'      => 0.0,
                'HEURES_A_PAYER_AC'      => 2.0,
                'HEURES_DEMANDEES_AA'    => 0.0,
                'HEURES_DEMANDEES_AC'    => 0.0,
                'HEURES_PAYEES_AA'       => 0.0,
                'HEURES_PAYEES_AC'       => 0.0,
                'PERIODE_ENS_ID'         => 13,
            ],
        ];

        $this->process($data, $await);
    }



    public function testSansMep()
    {
        $this->useParametres([
            ['regle_repartition_annee_civile', Rapprocheur::REGLE_PRORATA],
            ['regle_paiement_annee_civile', Repartiteur::PAIEMENT_ANNEE_CIV_4_10_6_10],
            ['pourc_s1_pour_annee_civile', '0.67'],
            ['pourc_aa_referentiel', '0.4'],
        ]);

        $data = [
            [
                'KEY'                        => 'e-120624024-9',
                'CALCUL_SEMESTRIEL'          => '1',
                'A_PAYER_ID'                 => '322879',
                'ANNEE_ID'                   => '2018',
                'SERVICE_ID'                 => '131918',
                'SERVICE_REFERENTIEL_ID'     => NULL,
                'MISSION_ID'                 => NULL,
                'FORMULE_RES_SERVICE_ID'     => '120624024',
                'FORMULE_RES_SERVICE_REF_ID' => NULL,
                'INTERVENANT_ID'             => '58961',
                'STRUCTURE_ID'               => '287',
                'TYPE_HEURES_ID'             => '9',
                'DEF_DOMAINE_FONCTIONNEL_ID' => '14',
                'DEF_CENTRE_COUT_ID'         => NULL,
                'TAUX_REMU_ID'               => '1',
                'TAUX_CONGES_PAYES'          => '1',
                'HEURES'                     => '38.4',
                'LAP_HEURES'                 => '38.4',
                'PERIODE_ENS_ID'             => '12',
                'PERIODE_ENS_CODE'           => 'S1',
                'HORAIRE_DEBUT'              => self::HORAIRE_DEBUT_AA,
                'HORAIRE_FIN'                => self::HORAIRE_DEBUT_AA,
                'MISE_EN_PAIEMENT_ID'        => NULL,
                'DATE_MISE_EN_PAIEMENT'      => NULL,
                'PERIODE_PAIEMENT_ID'        => NULL,
                'MEP_CENTRE_COUT_ID'         => NULL,
                'MEP_HEURES'                 => NULL,
                'MEP_DOMAINE_FONCTIONNEL_ID' => NULL,
            ]
        ];

        $await = [
            [
                'ANNEE_ID'                   => 2018,
                'SERVICE_ID'                 => 131918,
                'SERVICE_REFERENTIEL_ID'     => NULL,
                'MISSION_ID'                 => NULL,
                'FORMULE_RES_SERVICE_ID'     => 120624024,
                'FORMULE_RES_SERVICE_REF_ID' => NULL,
                'INTERVENANT_ID'             => 58961,
                'STRUCTURE_ID'               => 287,
                'MISE_EN_PAIEMENT_ID'        => NULL,
                'PERIODE_PAIEMENT_ID'        => NULL,
                'CENTRE_COUT_ID'             => NULL,
                'DOMAINE_FONCTIONNEL_ID'     => 14,
                'TAUX_REMU_ID'               => 1,
                'TAUX_HORAIRE'               => 41.41,
                'TAUX_CONGES_PAYES'          => 1.0,
                'HEURES_A_PAYER_AA'          => 15.36,
                'HEURES_A_PAYER_AC'          => 23.04,
                'HEURES_DEMANDEES_AA'        => 0.0,
                'HEURES_DEMANDEES_AC'        => 0.0,
                'HEURES_PAYEES_AA'           => 0.0,
                'HEURES_PAYEES_AC'           => 0.0,
                'PERIODE_ENS_ID'             => 12,
            ],
        ];

        $this->process($data, $await);
    }



    public function testAvecDMep()
    {
        $this->useParametres([
            ['regle_repartition_annee_civile', Rapprocheur::REGLE_PRORATA],
            ['regle_paiement_annee_civile', Repartiteur::PAIEMENT_ANNEE_CIV_4_10_6_10],
            ['pourc_s1_pour_annee_civile', '0.67'],
            ['pourc_aa_referentiel', '0.4'],
        ]);

        $data = [
            [
                'KEY'                        => 'e-120624024-9',
                'CALCUL_SEMESTRIEL'          => '1',
                'A_PAYER_ID'                 => '322879',
                'ANNEE_ID'                   => '2018',
                'SERVICE_ID'                 => '131918',
                'SERVICE_REFERENTIEL_ID'     => NULL,
                'MISSION_ID'                 => NULL,
                'FORMULE_RES_SERVICE_ID'     => '120624024',
                'FORMULE_RES_SERVICE_REF_ID' => NULL,
                'INTERVENANT_ID'             => '58961',
                'STRUCTURE_ID'               => '287',
                'TYPE_HEURES_ID'             => '9',
                'DEF_DOMAINE_FONCTIONNEL_ID' => '14',
                'DEF_CENTRE_COUT_ID'         => NULL,
                'TAUX_REMU_ID'               => '1',
                'TAUX_CONGES_PAYES'          => '1',
                'HEURES'                     => '38.4',
                'LAP_HEURES'                 => '38.4',
                'PERIODE_ENS_ID'             => '12',
                'PERIODE_ENS_CODE'           => 'S1',
                'HORAIRE_DEBUT'              => self::HORAIRE_DEBUT_AA,
                'HORAIRE_FIN'                => self::HORAIRE_DEBUT_AA,
                'MISE_EN_PAIEMENT_ID'        => '1',
                'DATE_MISE_EN_PAIEMENT'      => NULL,
                'PERIODE_PAIEMENT_ID'        => NULL,
                'MEP_CENTRE_COUT_ID'         => '348',
                'MEP_HEURES'                 => '38.4',
                'MEP_DOMAINE_FONCTIONNEL_ID' => '14',
            ]
        ];

        $await = [
            [
                'ANNEE_ID'                   => 2018,
                'SERVICE_ID'                 => 131918,
                'SERVICE_REFERENTIEL_ID'     => NULL,
                'MISSION_ID'                 => NULL,
                'FORMULE_RES_SERVICE_ID'     => 120624024,
                'FORMULE_RES_SERVICE_REF_ID' => NULL,
                'INTERVENANT_ID'             => 58961,
                'STRUCTURE_ID'               => 287,
                'MISE_EN_PAIEMENT_ID'        => 1,
                'PERIODE_PAIEMENT_ID'        => NULL,
                'CENTRE_COUT_ID'             => 348,
                'DOMAINE_FONCTIONNEL_ID'     => 14,
                'TAUX_REMU_ID'               => 1,
                'TAUX_HORAIRE'               => 41.41,
                'TAUX_CONGES_PAYES'          => 1.0,
                'HEURES_A_PAYER_AA'          => 15.36,
                'HEURES_A_PAYER_AC'          => 23.04,
                'HEURES_DEMANDEES_AA'        => 15.36,
                'HEURES_DEMANDEES_AC'        => 23.04,
                'HEURES_PAYEES_AA'           => 0.0,
                'HEURES_PAYEES_AC'           => 0.0,
                'PERIODE_ENS_ID'             => 12,
            ],
        ];

        $this->process($data, $await);
    }



    public function testAvecMep()
    {
        $this->useParametres([
            ['regle_repartition_annee_civile', Rapprocheur::REGLE_PRORATA],
            ['regle_paiement_annee_civile', Repartiteur::PAIEMENT_ANNEE_CIV_4_10_6_10],
            ['pourc_s1_pour_annee_civile', '0.67'],
            ['pourc_aa_referentiel', '0.4'],
        ]);

        $data = [
            [
                'KEY'                        => 'e-120624024-9',
                'CALCUL_SEMESTRIEL'          => '1',
                'A_PAYER_ID'                 => '322879',
                'ANNEE_ID'                   => '2018',
                'SERVICE_ID'                 => '131918',
                'SERVICE_REFERENTIEL_ID'     => NULL,
                'MISSION_ID'                 => NULL,
                'FORMULE_RES_SERVICE_ID'     => '120624024',
                'FORMULE_RES_SERVICE_REF_ID' => NULL,
                'INTERVENANT_ID'             => '58961',
                'STRUCTURE_ID'               => '287',
                'TYPE_HEURES_ID'             => '9',
                'DEF_DOMAINE_FONCTIONNEL_ID' => '14',
                'DEF_CENTRE_COUT_ID'         => NULL,
                'TAUX_REMU_ID'               => '1',
                'TAUX_CONGES_PAYES'          => '1',
                'HEURES'                     => '38.4',
                'LAP_HEURES'                 => '38.4',
                'PERIODE_ENS_ID'             => '12',
                'PERIODE_ENS_CODE'           => 'S1',
                'HORAIRE_DEBUT'              => self::HORAIRE_DEBUT_AA,
                'HORAIRE_FIN'                => self::HORAIRE_DEBUT_AA,
                'MISE_EN_PAIEMENT_ID'        => '1',
                'DATE_MISE_EN_PAIEMENT'      => '2019-02-01 00:00:00',
                'PERIODE_PAIEMENT_ID'        => '12',
                'MEP_CENTRE_COUT_ID'         => '348',
                'MEP_HEURES'                 => '38.4',
                'MEP_DOMAINE_FONCTIONNEL_ID' => '14',
            ]
        ];

        $await = [
            [
                'ANNEE_ID'                   => 2018,
                'SERVICE_ID'                 => 131918,
                'SERVICE_REFERENTIEL_ID'     => NULL,
                'MISSION_ID'                 => NULL,
                'FORMULE_RES_SERVICE_ID'     => 120624024,
                'FORMULE_RES_SERVICE_REF_ID' => NULL,
                'INTERVENANT_ID'             => 58961,
                'STRUCTURE_ID'               => 287,
                'MISE_EN_PAIEMENT_ID'        => 1,
                'PERIODE_PAIEMENT_ID'        => 12,
                'CENTRE_COUT_ID'             => 348,
                'DOMAINE_FONCTIONNEL_ID'     => 14,
                'TAUX_REMU_ID'               => 1,
                'TAUX_HORAIRE'               => 41.41,
                'TAUX_CONGES_PAYES'          => 1.0,
                'HEURES_A_PAYER_AA'          => 15.36,
                'HEURES_A_PAYER_AC'          => 23.04,
                'HEURES_DEMANDEES_AA'        => 15.36,
                'HEURES_DEMANDEES_AC'        => 23.04,
                'HEURES_PAYEES_AA'           => 15.36,
                'HEURES_PAYEES_AC'           => 23.04,
                'PERIODE_ENS_ID'             => 12,
            ],
        ];

        $this->process($data, $await);
    }



    public function testMepAZero()
    {
        $this->useParametres([
            ['regle_repartition_annee_civile', Rapprocheur::REGLE_PRORATA],
            ['regle_paiement_annee_civile', Repartiteur::PAIEMENT_ANNEE_CIV_4_10_6_10],
            ['pourc_s1_pour_annee_civile', '0.67'],
            ['pourc_aa_referentiel', '0.4'],
        ]);

        $data = [
            [
                'KEY'                        => 'e-120624024-9',
                'CALCUL_SEMESTRIEL'          => '1',
                'A_PAYER_ID'                 => '322879',
                'ANNEE_ID'                   => '2018',
                'SERVICE_ID'                 => '131918',
                'SERVICE_REFERENTIEL_ID'     => NULL,
                'MISSION_ID'                 => NULL,
                'FORMULE_RES_SERVICE_ID'     => '120624024',
                'FORMULE_RES_SERVICE_REF_ID' => NULL,
                'INTERVENANT_ID'             => '58961',
                'STRUCTURE_ID'               => '287',
                'TYPE_HEURES_ID'             => '9',
                'DEF_DOMAINE_FONCTIONNEL_ID' => '14',
                'DEF_CENTRE_COUT_ID'         => NULL,
                'TAUX_REMU_ID'               => '1',
                'TAUX_CONGES_PAYES'          => '1',
                'HEURES'                     => '38.4',
                'LAP_HEURES'                 => '38.4',
                'PERIODE_ENS_ID'             => '12',
                'PERIODE_ENS_CODE'           => 'S1',
                'HORAIRE_DEBUT'              => self::HORAIRE_DEBUT_AA,
                'HORAIRE_FIN'                => self::HORAIRE_DEBUT_AA,
                'MISE_EN_PAIEMENT_ID'        => '109871',
                'DATE_MISE_EN_PAIEMENT'      => '2019-07-31 10:45:52',
                'PERIODE_PAIEMENT_ID'        => '10',
                'MEP_CENTRE_COUT_ID'         => '2491',
                'MEP_HEURES'                 => '0',
                'MEP_DOMAINE_FONCTIONNEL_ID' => '14',
            ]
        ];

        $await = [
            [
                'ANNEE_ID'                   => 2018,
                'SERVICE_ID'                 => 131918,
                'SERVICE_REFERENTIEL_ID'     => NULL,
                'MISSION_ID'                 => NULL,
                'FORMULE_RES_SERVICE_ID'     => 120624024,
                'FORMULE_RES_SERVICE_REF_ID' => NULL,
                'INTERVENANT_ID'             => 58961,
                'STRUCTURE_ID'               => 287,
                'MISE_EN_PAIEMENT_ID'        => 109871,
                'PERIODE_PAIEMENT_ID'        => 10,
                'CENTRE_COUT_ID'             => 2491,
                'DOMAINE_FONCTIONNEL_ID'     => 14,
                'TAUX_REMU_ID'               => 1,
                'TAUX_HORAIRE'               => 41.41,
                'TAUX_CONGES_PAYES'          => 1.0,
                'HEURES_A_PAYER_AA'          => 0.0,
                'HEURES_A_PAYER_AC'          => 0.0,
                'HEURES_DEMANDEES_AA'        => 0.0,
                'HEURES_DEMANDEES_AC'        => 0.0,
                'HEURES_PAYEES_AA'           => 0.0,
                'HEURES_PAYEES_AC'           => 0.0,
                'PERIODE_ENS_ID'             => 12,
            ],
            [
                'ANNEE_ID'                   => 2018,
                'SERVICE_ID'                 => 131918,
                'SERVICE_REFERENTIEL_ID'     => NULL,
                'MISSION_ID'                 => NULL,
                'FORMULE_RES_SERVICE_ID'     => 120624024,
                'FORMULE_RES_SERVICE_REF_ID' => NULL,
                'INTERVENANT_ID'             => 58961,
                'STRUCTURE_ID'               => 287,
                'MISE_EN_PAIEMENT_ID'        => NULL,
                'PERIODE_PAIEMENT_ID'        => NULL,
                'CENTRE_COUT_ID'             => NULL,
                'DOMAINE_FONCTIONNEL_ID'     => 14,
                'TAUX_REMU_ID'               => 1,
                'TAUX_HORAIRE'               => 41.41,
                'TAUX_CONGES_PAYES'          => 1.0,
                'HEURES_A_PAYER_AA'          => 15.36,
                'HEURES_A_PAYER_AC'          => 23.04,
                'HEURES_DEMANDEES_AA'        => 0.0,
                'HEURES_DEMANDEES_AC'        => 0.0,
                'HEURES_PAYEES_AA'           => 0.0,
                'HEURES_PAYEES_AC'           => 0.0,
                'PERIODE_ENS_ID'             => 12,
            ],
        ];

        $this->process($data, $await);
    }



    public function testHeuresNegatives()
    {
        $this->useParametres([
            ['regle_repartition_annee_civile', Rapprocheur::REGLE_PRORATA],
            ['regle_paiement_annee_civile', Repartiteur::PAIEMENT_ANNEE_CIV_4_10_6_10],
            ['pourc_s1_pour_annee_civile', '0.67'],
            ['pourc_aa_referentiel', '0.4'],
        ]);

        $data = [
            [
                'KEY'                        => 'e-121085147-12',
                'CALCUL_SEMESTRIEL'          => '0',
                'A_PAYER_ID'                 => '624905',
                'ANNEE_ID'                   => '2022',
                'SERVICE_ID'                 => '258201',
                'SERVICE_REFERENTIEL_ID'     => NULL,
                'MISSION_ID'                 => NULL,
                'FORMULE_RES_SERVICE_ID'     => '121085147',
                'FORMULE_RES_SERVICE_REF_ID' => NULL,
                'INTERVENANT_ID'             => '882254',
                'STRUCTURE_ID'               => '594',
                'TYPE_HEURES_ID'             => '12',
                'DEF_DOMAINE_FONCTIONNEL_ID' => '1',
                'DEF_CENTRE_COUT_ID'         => '2615',
                'TAUX_REMU_ID'               => '1',
                'TAUX_CONGES_PAYES'          => '1',
                'HEURES'                     => '6',
                'LAP_HEURES'                 => '21',
                'PERIODE_ENS_ID'             => '13',
                'PERIODE_ENS_CODE'           => 'S2',
                'HORAIRE_DEBUT'              => self::HORAIRE_DEBUT_AC,
                'HORAIRE_FIN'                => self::HORAIRE_DEBUT_AC,
                'MISE_EN_PAIEMENT_ID'        => NULL,
                'DATE_MISE_EN_PAIEMENT'      => NULL,
                'PERIODE_PAIEMENT_ID'        => NULL,
                'MEP_CENTRE_COUT_ID'         => NULL,
                'MEP_HEURES'                 => NULL,
                'MEP_DOMAINE_FONCTIONNEL_ID' => NULL,
            ],
            [
                'KEY'                        => 'e-121085147-12',
                'CALCUL_SEMESTRIEL'          => '0',
                'A_PAYER_ID'                 => '624906',
                'ANNEE_ID'                   => '2022',
                'SERVICE_ID'                 => '258201',
                'SERVICE_REFERENTIEL_ID'     => NULL,
                'MISSION_ID'                 => NULL,
                'FORMULE_RES_SERVICE_ID'     => '121085147',
                'FORMULE_RES_SERVICE_REF_ID' => NULL,
                'INTERVENANT_ID'             => '882254',
                'STRUCTURE_ID'               => '594',
                'TYPE_HEURES_ID'             => '12',
                'DEF_DOMAINE_FONCTIONNEL_ID' => '1',
                'DEF_CENTRE_COUT_ID'         => '2615',
                'TAUX_REMU_ID'               => '1',
                'TAUX_CONGES_PAYES'          => '1',
                'HEURES'                     => '6',
                'LAP_HEURES'                 => '-15',
                'PERIODE_ENS_ID'             => '13',
                'PERIODE_ENS_CODE'           => 'S2',
                'HORAIRE_DEBUT'              => self::HORAIRE_DEBUT_AC,
                'HORAIRE_FIN'                => self::HORAIRE_DEBUT_AC,
                'MISE_EN_PAIEMENT_ID'        => NULL,
                'DATE_MISE_EN_PAIEMENT'      => NULL,
                'PERIODE_PAIEMENT_ID'        => NULL,
                'MEP_CENTRE_COUT_ID'         => NULL,
                'MEP_HEURES'                 => NULL,
                'MEP_DOMAINE_FONCTIONNEL_ID' => NULL,
            ],
        ];

        $await = [
            [
                'HEURES_A_PAYER_AA'   => 0.0,
                'HEURES_A_PAYER_AC'   => 6.0,
                'HEURES_DEMANDEES_AA' => 0.0,
                'HEURES_DEMANDEES_AC' => 0.0,
                'HEURES_PAYEES_AA'    => 0.0,
                'HEURES_PAYEES_AC'    => 0.0,
                'PERIODE_ENS_ID'      => 13,
            ],
        ];

        $this->process($data, $await);
    }



    public function testTropDmepHeuresNegatives()
    {
        $this->useParametres([
            ['regle_repartition_annee_civile', Rapprocheur::REGLE_PRORATA],
            ['regle_paiement_annee_civile', Repartiteur::PAIEMENT_ANNEE_CIV_4_10_6_10],
            ['pourc_s1_pour_annee_civile', '0.67'],
            ['pourc_aa_referentiel', '0.4'],
        ]);

        $data = [
            [
                'KEY'                        => 'e-121085147-6',
                'CALCUL_SEMESTRIEL'          => '0',
                'A_PAYER_ID'                 => '624904',
                'ANNEE_ID'                   => '2022',
                'SERVICE_ID'                 => '258201',
                'SERVICE_REFERENTIEL_ID'     => NULL,
                'MISSION_ID'                 => NULL,
                'FORMULE_RES_SERVICE_ID'     => '121085147',
                'FORMULE_RES_SERVICE_REF_ID' => NULL,
                'INTERVENANT_ID'             => '882254',
                'STRUCTURE_ID'               => '594',
                'TYPE_HEURES_ID'             => '6',
                'DEF_DOMAINE_FONCTIONNEL_ID' => '1',
                'DEF_CENTRE_COUT_ID'         => '2615',
                'TAUX_REMU_ID'               => '1',
                'TAUX_CONGES_PAYES'          => '1',
                'HEURES'                     => '21',
                'LAP_HEURES'                 => '15',
                'PERIODE_ENS_ID'             => '12',
                'PERIODE_ENS_CODE'           => 'S1',
                'HORAIRE_DEBUT'              => self::HORAIRE_DEBUT_AA,
                'HORAIRE_FIN'                => self::HORAIRE_DEBUT_AA,
                'MISE_EN_PAIEMENT_ID'        => '229683',
                'DATE_MISE_EN_PAIEMENT'      => NULL,
                'PERIODE_PAIEMENT_ID'        => NULL,
                'MEP_CENTRE_COUT_ID'         => '2615',
                'MEP_HEURES'                 => '10',
                'MEP_DOMAINE_FONCTIONNEL_ID' => '1',
            ],
            [
                'KEY'                        => 'e-121085147-6',
                'CALCUL_SEMESTRIEL'          => '0',
                'A_PAYER_ID'                 => '624904',
                'ANNEE_ID'                   => '2022',
                'SERVICE_ID'                 => '258201',
                'SERVICE_REFERENTIEL_ID'     => NULL,
                'MISSION_ID'                 => NULL,
                'FORMULE_RES_SERVICE_ID'     => '121085147',
                'FORMULE_RES_SERVICE_REF_ID' => NULL,
                'INTERVENANT_ID'             => '882254',
                'STRUCTURE_ID'               => '594',
                'TYPE_HEURES_ID'             => '6',
                'DEF_DOMAINE_FONCTIONNEL_ID' => '1',
                'DEF_CENTRE_COUT_ID'         => '2615',
                'TAUX_REMU_ID'               => '1',
                'TAUX_CONGES_PAYES'          => '1',
                'HEURES'                     => '21',
                'LAP_HEURES'                 => '15',
                'PERIODE_ENS_ID'             => '12',
                'PERIODE_ENS_CODE'           => 'S1',
                'HORAIRE_DEBUT'              => self::HORAIRE_DEBUT_AA,
                'HORAIRE_FIN'                => self::HORAIRE_DEBUT_AA,
                'MISE_EN_PAIEMENT_ID'        => '229684',
                'DATE_MISE_EN_PAIEMENT'      => NULL,
                'PERIODE_PAIEMENT_ID'        => NULL,
                'MEP_CENTRE_COUT_ID'         => '2614',
                'MEP_HEURES'                 => '24',
                'MEP_DOMAINE_FONCTIONNEL_ID' => '1',
            ],
            [
                'KEY'                        => 'e-121085147-6',
                'CALCUL_SEMESTRIEL'          => '0',
                'A_PAYER_ID'                 => '624905',
                'ANNEE_ID'                   => '2022',
                'SERVICE_ID'                 => '258201',
                'SERVICE_REFERENTIEL_ID'     => NULL,
                'MISSION_ID'                 => NULL,
                'FORMULE_RES_SERVICE_ID'     => '121085147',
                'FORMULE_RES_SERVICE_REF_ID' => NULL,
                'INTERVENANT_ID'             => '882254',
                'STRUCTURE_ID'               => '594',
                'TYPE_HEURES_ID'             => '6',
                'DEF_DOMAINE_FONCTIONNEL_ID' => '1',
                'DEF_CENTRE_COUT_ID'         => '2615',
                'TAUX_REMU_ID'               => '1',
                'TAUX_CONGES_PAYES'          => '1',
                'HEURES'                     => '21',
                'LAP_HEURES'                 => '21',
                'PERIODE_ENS_ID'             => '13',
                'PERIODE_ENS_CODE'           => 'S2',
                'HORAIRE_DEBUT'              => self::HORAIRE_DEBUT_AC,
                'HORAIRE_FIN'                => self::HORAIRE_DEBUT_AC,
                'MISE_EN_PAIEMENT_ID'        => '229683',
                'DATE_MISE_EN_PAIEMENT'      => NULL,
                'PERIODE_PAIEMENT_ID'        => NULL,
                'MEP_CENTRE_COUT_ID'         => '2615',
                'MEP_HEURES'                 => '10',
                'MEP_DOMAINE_FONCTIONNEL_ID' => '1',
            ],
            [
                'KEY'                        => 'e-121085147-6',
                'CALCUL_SEMESTRIEL'          => '0',
                'A_PAYER_ID'                 => '624905',
                'ANNEE_ID'                   => '2022',
                'SERVICE_ID'                 => '258201',
                'SERVICE_REFERENTIEL_ID'     => NULL,
                'MISSION_ID'                 => NULL,
                'FORMULE_RES_SERVICE_ID'     => '121085147',
                'FORMULE_RES_SERVICE_REF_ID' => NULL,
                'INTERVENANT_ID'             => '882254',
                'STRUCTURE_ID'               => '594',
                'TYPE_HEURES_ID'             => '6',
                'DEF_DOMAINE_FONCTIONNEL_ID' => '1',
                'DEF_CENTRE_COUT_ID'         => '2615',
                'TAUX_REMU_ID'               => '1',
                'TAUX_CONGES_PAYES'          => '1',
                'HEURES'                     => '21',
                'LAP_HEURES'                 => '21',
                'PERIODE_ENS_ID'             => '13',
                'PERIODE_ENS_CODE'           => 'S2',
                'HORAIRE_DEBUT'              => self::HORAIRE_DEBUT_AC,
                'HORAIRE_FIN'                => self::HORAIRE_DEBUT_AC,
                'MISE_EN_PAIEMENT_ID'        => '229684',
                'DATE_MISE_EN_PAIEMENT'      => NULL,
                'PERIODE_PAIEMENT_ID'        => NULL,
                'MEP_CENTRE_COUT_ID'         => '2614',
                'MEP_HEURES'                 => '24',
                'MEP_DOMAINE_FONCTIONNEL_ID' => '1',
            ],
            [
                'KEY'                        => 'e-121085147-6',
                'CALCUL_SEMESTRIEL'          => '0',
                'A_PAYER_ID'                 => '624906',
                'ANNEE_ID'                   => '2022',
                'SERVICE_ID'                 => '258201',
                'SERVICE_REFERENTIEL_ID'     => NULL,
                'MISSION_ID'                 => NULL,
                'FORMULE_RES_SERVICE_ID'     => '121085147',
                'FORMULE_RES_SERVICE_REF_ID' => NULL,
                'INTERVENANT_ID'             => '882254',
                'STRUCTURE_ID'               => '594',
                'TYPE_HEURES_ID'             => '6',
                'DEF_DOMAINE_FONCTIONNEL_ID' => '1',
                'DEF_CENTRE_COUT_ID'         => '2615',
                'TAUX_REMU_ID'               => '1',
                'TAUX_CONGES_PAYES'          => '1',
                'HEURES'                     => '21',
                'LAP_HEURES'                 => '-15',
                'PERIODE_ENS_ID'             => '13',
                'PERIODE_ENS_CODE'           => 'S2',
                'HORAIRE_DEBUT'              => self::HORAIRE_DEBUT_AC,
                'HORAIRE_FIN'                => self::HORAIRE_DEBUT_AC,
                'MISE_EN_PAIEMENT_ID'        => '229683',
                'DATE_MISE_EN_PAIEMENT'      => NULL,
                'PERIODE_PAIEMENT_ID'        => NULL,
                'MEP_CENTRE_COUT_ID'         => '2615',
                'MEP_HEURES'                 => '10',
                'MEP_DOMAINE_FONCTIONNEL_ID' => '1',
            ],
            [
                'KEY'                        => 'e-121085147-6',
                'CALCUL_SEMESTRIEL'          => '0',
                'A_PAYER_ID'                 => '624906',
                'ANNEE_ID'                   => '2022',
                'SERVICE_ID'                 => '258201',
                'SERVICE_REFERENTIEL_ID'     => NULL,
                'MISSION_ID'                 => NULL,
                'FORMULE_RES_SERVICE_ID'     => '121085147',
                'FORMULE_RES_SERVICE_REF_ID' => NULL,
                'INTERVENANT_ID'             => '882254',
                'STRUCTURE_ID'               => '594',
                'TYPE_HEURES_ID'             => '6',
                'DEF_DOMAINE_FONCTIONNEL_ID' => '1',
                'DEF_CENTRE_COUT_ID'         => '2615',
                'TAUX_REMU_ID'               => '1',
                'TAUX_CONGES_PAYES'          => '1',
                'HEURES'                     => '21',
                'LAP_HEURES'                 => '-15',
                'PERIODE_ENS_ID'             => '13',
                'PERIODE_ENS_CODE'           => 'S2',
                'HORAIRE_DEBUT'              => self::HORAIRE_DEBUT_AC,
                'HORAIRE_FIN'                => self::HORAIRE_DEBUT_AC,
                'MISE_EN_PAIEMENT_ID'        => '229684',
                'DATE_MISE_EN_PAIEMENT'      => NULL,
                'PERIODE_PAIEMENT_ID'        => NULL,
                'MEP_CENTRE_COUT_ID'         => '2614',
                'MEP_HEURES'                 => '24',
                'MEP_DOMAINE_FONCTIONNEL_ID' => '1',
            ],
        ];

        $await = [
            [
                'MISE_EN_PAIEMENT_ID' => 229683,
                'CENTRE_COUT_ID'      => 2615,
                'TAUX_HORAIRE'        => 41.41,
                'HEURES_A_PAYER_AA'   => 0.0,
                'HEURES_A_PAYER_AC'   => 10.0,
                'HEURES_DEMANDEES_AA' => 0.0,
                'HEURES_DEMANDEES_AC' => 10.0,
                'HEURES_PAYEES_AA'    => 0.0,
                'HEURES_PAYEES_AC'    => 0.0,
                'PERIODE_ENS_ID'      => 12,
            ],
            [
                'MISE_EN_PAIEMENT_ID' => 229684,
                'CENTRE_COUT_ID'      => 2614,
                'TAUX_HORAIRE'        => 41.41,
                'HEURES_A_PAYER_AA'   => 0.0,
                'HEURES_A_PAYER_AC'   => 5.0,
                'HEURES_DEMANDEES_AA' => 0.0,
                'HEURES_DEMANDEES_AC' => 5.0,
                'HEURES_PAYEES_AA'    => 0.0,
                'HEURES_PAYEES_AC'    => 0.0,
                'PERIODE_ENS_ID'      => 12,
            ],
            [
                'MISE_EN_PAIEMENT_ID' => 229684,
                'CENTRE_COUT_ID'      => 2614,
                'TAUX_HORAIRE'        => 42.86,
                'HEURES_A_PAYER_AA'   => 0.0,
                'HEURES_A_PAYER_AC'   => 6.0,
                'HEURES_DEMANDEES_AA' => 0.0,
                'HEURES_DEMANDEES_AC' => 6.0,
                'HEURES_PAYEES_AA'    => 0.0,
                'HEURES_PAYEES_AC'    => 0.0,
                'PERIODE_ENS_ID'      => 13,
            ],
            [
                'MISE_EN_PAIEMENT_ID' => 229684,
                'CENTRE_COUT_ID'      => 2614,
                'TAUX_HORAIRE'        => 42.86,
                'HEURES_A_PAYER_AA'   => 0.0,
                'HEURES_A_PAYER_AC'   => 0.0,
                'HEURES_DEMANDEES_AA' => 13.0,
                'HEURES_DEMANDEES_AC' => 0.0,
                'HEURES_PAYEES_AA'    => 0.0,
                'HEURES_PAYEES_AC'    => 0.0,
                'PERIODE_ENS_ID'      => NULL,
            ],
        ];

        $this->process($data, $await);
    }



    public function testMultiSemestres()
    {
        $this->useParametres([
            ['regle_repartition_annee_civile', Rapprocheur::REGLE_PRORATA],
            ['regle_paiement_annee_civile', Repartiteur::PAIEMENT_ANNEE_CIV_SEMESTRE_DATE],
            ['pourc_s1_pour_annee_civile', '0.67'],
            ['pourc_aa_referentiel', '0.4'],
        ]);

        $data = [
            [
                'KEY' => 'e-121085157-12-12',
                'CALCUL_SEMESTRIEL' => '1',
                'A_PAYER_ID' => '624918',
                'ANNEE_ID' => '2022',
                'SERVICE_ID' => '258206',
                'SERVICE_REFERENTIEL_ID' => NULL,
                'MISSION_ID' => NULL,
                'FORMULE_RES_SERVICE_ID' => '121085157',
                'FORMULE_RES_SERVICE_REF_ID' => NULL,
                'INTERVENANT_ID' => '882254',
                'STRUCTURE_ID' => '594',
                'TYPE_HEURES_ID' => '12',
                'DEF_DOMAINE_FONCTIONNEL_ID' => '1',
                'DEF_CENTRE_COUT_ID' => NULL,
                'TAUX_REMU_ID' => '1',
                'TAUX_CONGES_PAYES' => '1',
                'HEURES' => '2',
                'LAP_HEURES' => '2',
                'PERIODE_ENS_ID' => '12',
                'PERIODE_ENS_CODE' => 'S1',
                'HORAIRE_DEBUT' => self::HORAIRE_DEBUT_AC,
                'HORAIRE_FIN' => self::HORAIRE_DEBUT_AC,
                'MISE_EN_PAIEMENT_ID' => NULL,
                'DATE_MISE_EN_PAIEMENT' => NULL,
                'PERIODE_PAIEMENT_ID' => NULL,
                'MEP_CENTRE_COUT_ID' => NULL,
                'MEP_HEURES' => NULL,
                'MEP_DOMAINE_FONCTIONNEL_ID' => NULL,
            ],
            [
                'KEY' => 'e-121085157-12-13',
                'CALCUL_SEMESTRIEL' => '1',
                'A_PAYER_ID' => '624919',
                'ANNEE_ID' => '2022',
                'SERVICE_ID' => '258206',
                'SERVICE_REFERENTIEL_ID' => NULL,
                'MISSION_ID' => NULL,
                'FORMULE_RES_SERVICE_ID' => '121085157',
                'FORMULE_RES_SERVICE_REF_ID' => NULL,
                'INTERVENANT_ID' => '882254',
                'STRUCTURE_ID' => '594',
                'TYPE_HEURES_ID' => '12',
                'DEF_DOMAINE_FONCTIONNEL_ID' => '1',
                'DEF_CENTRE_COUT_ID' => NULL,
                'TAUX_REMU_ID' => '1',
                'TAUX_CONGES_PAYES' => '1',
                'HEURES' => '3',
                'LAP_HEURES' => '3',
                'PERIODE_ENS_ID' => '13',
                'PERIODE_ENS_CODE' => 'S2',
                'HORAIRE_DEBUT' => self::HORAIRE_DEBUT_AC,
                'HORAIRE_FIN' => self::HORAIRE_DEBUT_AC,
                'MISE_EN_PAIEMENT_ID' => NULL,
                'DATE_MISE_EN_PAIEMENT' => NULL,
                'PERIODE_PAIEMENT_ID' => NULL,
                'MEP_CENTRE_COUT_ID' => NULL,
                'MEP_HEURES' => NULL,
                'MEP_DOMAINE_FONCTIONNEL_ID' => NULL,
            ],
        ];

        $await = [
            [
                'ANNEE_ID'                   => 2022,
                'SERVICE_ID'                 => 258206,
                'SERVICE_REFERENTIEL_ID'     => NULL,
                'MISSION_ID'                 => NULL,
                'FORMULE_RES_SERVICE_ID'     => 121085157,
                'FORMULE_RES_SERVICE_REF_ID' => NULL,
                'INTERVENANT_ID'             => 882254,
                'STRUCTURE_ID'               => 594,
                'TYPE_HEURES_ID'             => 12,
                'PERIODE_ENS_ID'             => 12,
                'MISE_EN_PAIEMENT_ID'        => NULL,
                'PERIODE_PAIEMENT_ID'        => NULL,
                'CENTRE_COUT_ID'             => NULL,
                'DOMAINE_FONCTIONNEL_ID'     => 1,
                'TAUX_REMU_ID'               => 1,
                'TAUX_HORAIRE'               => 42.86,
                'TAUX_CONGES_PAYES'          => 1.0,
                'HEURES_A_PAYER_AA'          => 1.34,
                'HEURES_A_PAYER_AC'          => 0.66,
                'HEURES_DEMANDEES_AA'        => 0.0,
                'HEURES_DEMANDEES_AC'        => 0.0,
                'HEURES_PAYEES_AA'           => 0.0,
                'HEURES_PAYEES_AC'           => 0.0,
            ],
            [
                'ANNEE_ID'                   => 2022,
                'SERVICE_ID'                 => 258206,
                'SERVICE_REFERENTIEL_ID'     => NULL,
                'MISSION_ID'                 => NULL,
                'FORMULE_RES_SERVICE_ID'     => 121085157,
                'FORMULE_RES_SERVICE_REF_ID' => NULL,
                'INTERVENANT_ID'             => 882254,
                'STRUCTURE_ID'               => 594,
                'TYPE_HEURES_ID'             => 12,
                'PERIODE_ENS_ID'             => 13,
                'MISE_EN_PAIEMENT_ID'        => NULL,
                'PERIODE_PAIEMENT_ID'        => NULL,
                'CENTRE_COUT_ID'             => NULL,
                'DOMAINE_FONCTIONNEL_ID'     => 1,
                'TAUX_REMU_ID'               => 1,
                'TAUX_HORAIRE'               => 42.86,
                'TAUX_CONGES_PAYES'          => 1.0,
                'HEURES_A_PAYER_AA'          => 0.0,
                'HEURES_A_PAYER_AC'          => 3.0,
                'HEURES_DEMANDEES_AA'        => 0.0,
                'HEURES_DEMANDEES_AC'        => 0.0,
                'HEURES_PAYEES_AA'           => 0.0,
                'HEURES_PAYEES_AC'           => 0.0,
            ],
        ];

        $this->process($data, $await);
    }
}
