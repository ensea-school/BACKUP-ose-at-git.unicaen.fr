<?php declare(strict_types=1);

namespace TblContrat;

use Application\Entity\Db\Parametre;
use Application\Service\ParametresService;
use Contrat\Tbl\Process\ContratProcess;
use Paiement\Service\TauxRemuService;
use tests\OseTestCase;
use UnicaenTbl\Service\TableauBordService;

final class TraitementQueryTestOld extends OseTestCase
{

    protected ContratProcess $pp;



    protected function setUp(): void
    {
        $c = \AppAdmin::container()->get(TableauBordService::class);

        $tauxRemuMock = $this->getMockBuilder(TauxRemuService::class)->onlyMethods(['getTauxMap'])->getMock();

        $tauxRemuMock->expects($this->any())->method('getTauxMap')->willReturn([
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
                                                                               ]);

        $ptbl     = $c->getTableauBord('contrat');
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



    public function testQuerryAvecContratEtAvenantEtTauxUnique()
    {

        $this->useParametres([
                                 ['avenant', Parametre::AVENANT_AUTORISE],
                             ]);


        $data = [
            [
                'ID'                        => NULL,
                'ANNEE_ID'                  => 2022,
                'INTERVENANT_ID'            => 715890,
                'ACTIF'                     => 1,
                'UUID'                      => 'er_715890_594_30373',
                'STRUCTURE_ID'              => 594,
                'CONTRAT_ID'                => 30373,
                'CONTRAT_PARENT_ID'         => NULL,
                'TYPE_CONTRAT_ID'           => 1,
                'TYPE_SERVICE_ID'           => 1,
                'TYPE_SERVICE_CODE'         => 'ENS',
                'MISSION_ID'                => NULL,
                'SERVICE_ID'                => 239982,
                'SERVICE_REFERENTIEL_ID'    => NULL,
                'VOLUME_HORAIRE_MISSION_ID' => NULL,
                'VOLUME_HORAIRE_ID'         => 576325,
                'VOLUME_HORAIRE_REF_ID'     => NULL,
                'EDITE'                     => 1,
                'SIGNE'                     => 1,
                'DATE_DEBUT'                => NULL,
                'DATE_FIN'                  => NULL,
                'DATE_CREATION'             => '16/12/2022',
                'CM'                        => 10,
                'TD'                        => 0,
                'TP'                        => 0,
                'AUTRES'                    => 0,
                'HEURES'                    => 10,
                'HETD'                      => 15,
                'AUTRE_LIBELLE'             => NULL,
                'TAUX_REMU_ID'              => 1,
                'TAUX_REMU_MAJORE_ID'       => 1,
                'TAUX_CONGES_PAYES'         => 0,
                'PROCESS_ID'                => 0,
            ],
            [
                'ID'                        => NULL,
                'ANNEE_ID'                  => 2022,
                'INTERVENANT_ID'            => 715890,
                'ACTIF'                     => 1,
                'UUID'                      => 'er_715890_594_',
                'STRUCTURE_ID'              => 594,
                'CONTRAT_ID'                => NULL,
                'CONTRAT_PARENT_ID'         => NULL,
                'TYPE_CONTRAT_ID'           => NULL,
                'TYPE_SERVICE_ID'           => 1,
                'TYPE_SERVICE_CODE'         => 'ENS',
                'MISSION_ID'                => NULL,
                'SERVICE_ID'                => 239982,
                'SERVICE_REFERENTIEL_ID'    => NULL,
                'VOLUME_HORAIRE_MISSION_ID' => NULL,
                'VOLUME_HORAIRE_ID'         => 576326,
                'VOLUME_HORAIRE_REF_ID'     => NULL,
                'EDITE'                     => 0,
                'SIGNE'                     => 0,
                'DATE_DEBUT'                => NULL,
                'DATE_FIN'                  => NULL,
                'DATE_CREATION'             => NULL,
                'CM'                        => 10,
                'TD'                        => 0,
                'TP'                        => 0,
                'AUTRES'                    => 0,
                'HEURES'                    => 10,
                'HETD'                      => 15,
                'AUTRE_LIBELLE'             => NULL,
                'TAUX_REMU_ID'              => 1,
                'TAUX_REMU_MAJORE_ID'       => 1,
                'TAUX_CONGES_PAYES'         => 0,
                'PROCESS_ID'                => 0,
            ],
            [
                'ID'                        => NULL,
                'ANNEE_ID'                  => 2022,
                'INTERVENANT_ID'            => 715890,
                'ACTIF'                     => 1,
                'UUID'                      => 'er_715890_594_',
                'STRUCTURE_ID'              => 594,
                'CONTRAT_ID'                => NULL,
                'CONTRAT_PARENT_ID'         => NULL,
                'TYPE_CONTRAT_ID'           => NULL,
                'TYPE_SERVICE_ID'           => 1,
                'TYPE_SERVICE_CODE'         => 'ENS',
                'MISSION_ID'                => NULL,
                'SERVICE_ID'                => 239982,
                'SERVICE_REFERENTIEL_ID'    => NULL,
                'VOLUME_HORAIRE_MISSION_ID' => NULL,
                'VOLUME_HORAIRE_ID'         => 576327,
                'VOLUME_HORAIRE_REF_ID'     => NULL,
                'EDITE'                     => 0,
                'SIGNE'                     => 0,
                'DATE_DEBUT'                => NULL,
                'DATE_FIN'                  => NULL,
                'DATE_CREATION'             => NULL,
                'CM'                        => 10,
                'TD'                        => 0,
                'TP'                        => 0,
                'AUTRES'                    => 0,
                'HEURES'                    => 10,
                'HETD'                      => 15,
                'AUTRE_LIBELLE'             => NULL,
                'TAUX_REMU_ID'              => 1,
                'TAUX_REMU_MAJORE_ID'       => 1,
                'TAUX_CONGES_PAYES'         => 0,
                'PROCESS_ID'                => 0,
            ],
        ];

        $awaitContrat  = [
            'er_715890_594_' => true,

        ];
        $awaitTauxRemu = [

            'er_715890_594_30373' => true,
            'er_715890_594_'      => true,
        ];


        $this->pp->init();

        $taux_remu_temp = 0;
        $listeContrat   = [];
        foreach ($data as $casTest) {
            $res            = $this->pp->traitementQuery($casTest, $listeContrat, $taux_remu_temp);
            $listeContrat   = $res[0];
            $taux_remu_temp = $res[1];
        }

        $this->assertArrayEquals($awaitContrat, $this->pp->getIntervenantContrat());
        $this->assertArrayEquals($awaitTauxRemu, $this->pp->getTauxRemuUuid());
        $this->pp->clearAfterTest();

    }



    public function testQuerryAvecContratEtAvenantEtTauxDifferent()
    {

        $this->useParametres([
                                 ['avenant', Parametre::AVENANT_AUTORISE],
                             ]);


        $data = [
            [
                'ID'                        => NULL,
                'ANNEE_ID'                  => 2022,
                'INTERVENANT_ID'            => 715890,
                'ACTIF'                     => 1,
                'UUID'                      => 'er_715890_594_30373',
                'STRUCTURE_ID'              => 594,
                'CONTRAT_ID'                => 30373,
                'CONTRAT_PARENT_ID'         => NULL,
                'TYPE_CONTRAT_ID'           => 1,
                'TYPE_SERVICE_ID'           => 1,
                'TYPE_SERVICE_CODE'         => 'ENS',
                'MISSION_ID'                => NULL,
                'SERVICE_ID'                => 239982,
                'SERVICE_REFERENTIEL_ID'    => NULL,
                'VOLUME_HORAIRE_MISSION_ID' => NULL,
                'VOLUME_HORAIRE_ID'         => 576325,
                'VOLUME_HORAIRE_REF_ID'     => NULL,
                'EDITE'                     => 1,
                'SIGNE'                     => 1,
                'DATE_DEBUT'                => NULL,
                'DATE_FIN'                  => NULL,
                'DATE_CREATION'             => '16/12/2022',
                'CM'                        => 10,
                'TD'                        => 0,
                'TP'                        => 0,
                'AUTRES'                    => 0,
                'HEURES'                    => 10,
                'HETD'                      => 15,
                'AUTRE_LIBELLE'             => NULL,
                'TAUX_REMU_ID'              => 1,
                'TAUX_REMU_MAJORE_ID'       => 1,
                'TAUX_CONGES_PAYES'         => 0,
                'PROCESS_ID'                => 0,
            ],
            [
                'ID'                        => NULL,
                'ANNEE_ID'                  => 2022,
                'INTERVENANT_ID'            => 715890,
                'ACTIF'                     => 1,
                'UUID'                      => 'er_715890_594_',
                'STRUCTURE_ID'              => 594,
                'CONTRAT_ID'                => NULL,
                'CONTRAT_PARENT_ID'         => NULL,
                'TYPE_CONTRAT_ID'           => NULL,
                'TYPE_SERVICE_ID'           => 1,
                'TYPE_SERVICE_CODE'         => 'ENS',
                'MISSION_ID'                => NULL,
                'SERVICE_ID'                => 239982,
                'SERVICE_REFERENTIEL_ID'    => NULL,
                'VOLUME_HORAIRE_MISSION_ID' => NULL,
                'VOLUME_HORAIRE_ID'         => 576326,
                'VOLUME_HORAIRE_REF_ID'     => NULL,
                'EDITE'                     => 0,
                'SIGNE'                     => 0,
                'DATE_DEBUT'                => NULL,
                'DATE_FIN'                  => NULL,
                'DATE_CREATION'             => NULL,
                'CM'                        => 10,
                'TD'                        => 0,
                'TP'                        => 0,
                'AUTRES'                    => 0,
                'HEURES'                    => 10,
                'HETD'                      => 15,
                'AUTRE_LIBELLE'             => NULL,
                'TAUX_REMU_ID'              => 1,
                'TAUX_REMU_MAJORE_ID'       => 1,
                'TAUX_CONGES_PAYES'         => 0,
                'PROCESS_ID'                => 0,
            ],
            [
                'ID'                        => NULL,
                'ANNEE_ID'                  => 2022,
                'INTERVENANT_ID'            => 715890,
                'ACTIF'                     => 1,
                'UUID'                      => 'er_715890_594_',
                'STRUCTURE_ID'              => 594,
                'CONTRAT_ID'                => NULL,
                'CONTRAT_PARENT_ID'         => NULL,
                'TYPE_CONTRAT_ID'           => NULL,
                'TYPE_SERVICE_ID'           => 1,
                'TYPE_SERVICE_CODE'         => 'ENS',
                'MISSION_ID'                => NULL,
                'SERVICE_ID'                => 239982,
                'SERVICE_REFERENTIEL_ID'    => NULL,
                'VOLUME_HORAIRE_MISSION_ID' => NULL,
                'VOLUME_HORAIRE_ID'         => 576327,
                'VOLUME_HORAIRE_REF_ID'     => NULL,
                'EDITE'                     => 0,
                'SIGNE'                     => 0,
                'DATE_DEBUT'                => NULL,
                'DATE_FIN'                  => NULL,
                'DATE_CREATION'             => NULL,
                'CM'                        => 10,
                'TD'                        => 0,
                'TP'                        => 0,
                'AUTRES'                    => 0,
                'HEURES'                    => 10,
                'HETD'                      => 15,
                'AUTRE_LIBELLE'             => NULL,
                'TAUX_REMU_ID'              => 2,
                'TAUX_REMU_MAJORE_ID'       => 1,
                'TAUX_CONGES_PAYES'         => 0,
                'PROCESS_ID'                => 0,
            ],
        ];

        $awaitContrat  = [
            'er_715890_594_' => true,

        ];
        $awaitTauxRemu = [

            'er_715890_594_30373' => true,
            'er_715890_594_'      => false,
        ];


        $this->pp->init();

        $taux_remu_temp = 0;
        $listeContrat   = [];
        foreach ($data as $casTest) {
            $res            = $this->pp->traitementQuery($casTest, $listeContrat, $taux_remu_temp);
            $listeContrat   = $res[0];
            $taux_remu_temp = $res[1];
        }

        $this->assertArrayEquals($awaitContrat, $this->pp->getIntervenantContrat());
        $this->assertArrayEquals($awaitTauxRemu, $this->pp->getTauxRemuUuid());
        $this->pp->clearAfterTest();

    }



    public function testQuerrySansContratEtAvenantEtTauxDifferent()
    {

        $this->useParametres([
                                 ['avenant', Parametre::AVENANT_AUTORISE],
                             ]);


        $data = [
            [
                'ID'                        => NULL,
                'ANNEE_ID'                  => 2022,
                'INTERVENANT_ID'            => 715890,
                'ACTIF'                     => 1,
                'UUID'                      => 'er_715890_593_',
                'STRUCTURE_ID'              => 594,
                'CONTRAT_ID'                => NULL,
                'CONTRAT_PARENT_ID'         => NULL,
                'TYPE_CONTRAT_ID'           => NULL,
                'TYPE_SERVICE_ID'           => 1,
                'TYPE_SERVICE_CODE'         => 'ENS',
                'MISSION_ID'                => NULL,
                'SERVICE_ID'                => 239982,
                'SERVICE_REFERENTIEL_ID'    => NULL,
                'VOLUME_HORAIRE_MISSION_ID' => NULL,
                'VOLUME_HORAIRE_ID'         => 576325,
                'VOLUME_HORAIRE_REF_ID'     => NULL,
                'EDITE'                     => 1,
                'SIGNE'                     => 1,
                'DATE_DEBUT'                => NULL,
                'DATE_FIN'                  => NULL,
                'DATE_CREATION'             => NULL,
                'CM'                        => 10,
                'TD'                        => 0,
                'TP'                        => 0,
                'AUTRES'                    => 0,
                'HEURES'                    => 10,
                'HETD'                      => 15,
                'AUTRE_LIBELLE'             => NULL,
                'TAUX_REMU_ID'              => 1,
                'TAUX_REMU_MAJORE_ID'       => 1,
                'TAUX_CONGES_PAYES'         => 0,
                'PROCESS_ID'                => 0,
            ],
            [
                'ID'                        => NULL,
                'ANNEE_ID'                  => 2022,
                'INTERVENANT_ID'            => 715890,
                'ACTIF'                     => 1,
                'UUID'                      => 'er_715890_594_',
                'STRUCTURE_ID'              => 594,
                'CONTRAT_ID'                => NULL,
                'CONTRAT_PARENT_ID'         => NULL,
                'TYPE_CONTRAT_ID'           => NULL,
                'TYPE_SERVICE_ID'           => 1,
                'TYPE_SERVICE_CODE'         => 'ENS',
                'MISSION_ID'                => NULL,
                'SERVICE_ID'                => 239982,
                'SERVICE_REFERENTIEL_ID'    => NULL,
                'VOLUME_HORAIRE_MISSION_ID' => NULL,
                'VOLUME_HORAIRE_ID'         => 576326,
                'VOLUME_HORAIRE_REF_ID'     => NULL,
                'EDITE'                     => 0,
                'SIGNE'                     => 0,
                'DATE_DEBUT'                => NULL,
                'DATE_FIN'                  => NULL,
                'DATE_CREATION'             => NULL,
                'CM'                        => 10,
                'TD'                        => 0,
                'TP'                        => 0,
                'AUTRES'                    => 0,
                'HEURES'                    => 10,
                'HETD'                      => 15,
                'AUTRE_LIBELLE'             => NULL,
                'TAUX_REMU_ID'              => 1,
                'TAUX_REMU_MAJORE_ID'       => 1,
                'TAUX_CONGES_PAYES'         => 0,
                'PROCESS_ID'                => 0,
            ],
            [
                'ID'                        => NULL,
                'ANNEE_ID'                  => 2022,
                'INTERVENANT_ID'            => 715890,
                'ACTIF'                     => 1,
                'UUID'                      => 'er_715890_594_',
                'STRUCTURE_ID'              => 594,
                'CONTRAT_ID'                => NULL,
                'CONTRAT_PARENT_ID'         => NULL,
                'TYPE_CONTRAT_ID'           => NULL,
                'TYPE_SERVICE_ID'           => 1,
                'TYPE_SERVICE_CODE'         => 'ENS',
                'MISSION_ID'                => NULL,
                'SERVICE_ID'                => 239982,
                'SERVICE_REFERENTIEL_ID'    => NULL,
                'VOLUME_HORAIRE_MISSION_ID' => NULL,
                'VOLUME_HORAIRE_ID'         => 576327,
                'VOLUME_HORAIRE_REF_ID'     => NULL,
                'EDITE'                     => 0,
                'SIGNE'                     => 0,
                'DATE_DEBUT'                => NULL,
                'DATE_FIN'                  => NULL,
                'DATE_CREATION'             => NULL,
                'CM'                        => 10,
                'TD'                        => 0,
                'TP'                        => 0,
                'AUTRES'                    => 0,
                'HEURES'                    => 10,
                'HETD'                      => 15,
                'AUTRE_LIBELLE'             => NULL,
                'TAUX_REMU_ID'              => 2,
                'TAUX_REMU_MAJORE_ID'       => 1,
                'TAUX_CONGES_PAYES'         => 0,
                'PROCESS_ID'                => 0,
            ],
        ];

        $awaitContrat  = [
        ];
        $awaitTauxRemu = [
            'er_715890_593_' => true,
            'er_715890_594_' => false,
        ];


        $this->pp->init();

        $taux_remu_temp = 0;
        $listeContrat   = [];
        foreach ($data as $casTest) {
            $res            = $this->pp->traitementQuery($casTest, $listeContrat, $taux_remu_temp);
            $listeContrat   = $res[0];
            $taux_remu_temp = $res[1];
        }

        $this->assertArrayEquals($awaitContrat, $this->pp->getIntervenantContrat());
        $this->assertArrayEquals($awaitTauxRemu, $this->pp->getTauxRemuUuid());
        $this->pp->clearAfterTest();
    }

}
