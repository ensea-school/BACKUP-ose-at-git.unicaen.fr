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
        $c = \Application::$container->get(TableauBordService::class);

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
        $calc = $this->pp->calcData($data);

        $this->assertArrayEquals($calc, $await);
    }



    public function estMock()
    {
        $this->useParametres([
            ['regle_repartition_annee_civile', Rapprocheur::REGLE_PRORATA],
            ['regle_paiement_annee_civile', Repartiteur::PAIEMENT_ANNEE_CIV_4_10_6_10],
            ['pourc_s1_pour_annee_civile', '0.67'],
        ]);

        $p = $this->pp->getServiceParametres()->get('pourc_s1_pour_annee_civile');
        var_dump($p);
    }



    public function testSansMep()
    {
        $this->useParametres([
            ['regle_repartition_annee_civile', Rapprocheur::REGLE_PRORATA],
            ['regle_paiement_annee_civile', Repartiteur::PAIEMENT_ANNEE_CIV_4_10_6_10],
            ['pourc_s1_pour_annee_civile', '0.67'],
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
            ],
        ];

        $this->process($data, $await);
    }

}
