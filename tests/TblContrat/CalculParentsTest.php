<?php declare(strict_types=1);

namespace tests\TblContrat;

use Application\Entity\Db\Parametre;

final class CalculParentsTest extends TblContratTestCase
{
    protected function assertContrats(array $contrats, array $expected): void
    {
        $contratsModels = [];
        foreach ($contrats as $contrat) {
            $contratsModels[] = $this->hydrateContrat($contrat);
        }
        $this->process->calculParentsIds($contratsModels);
        foreach ($contratsModels as $index => $model) {
            $contratsModels[$index] = $this->extractContrat($model);
        }
        $this->assertArrayEquals($expected, $contratsModels, false);
    }



    public function testCasSimple(): void
    {
        $parametres = [
            Parametre::AVENANT     => Parametre::AVENANT_AUTORISE,
            Parametre::CONTRAT_MIS => Parametre::CONTRAT_MIS_GLOBAL,
            Parametre::CONTRAT_ENS => Parametre::CONTRAT_ENS_GLOBAL,
        ];
        $this->useParametres($parametres);

        /* Jeu de données initial */
        $contrat1 = [
            'id'        => 1,
            'isMission' => false,
            'edite'     => true,
            'avenants'  => [],
        ];

        $contrat2 = [
            'id'        => null,
            'isMission' => false,
            'avenants'  => [],
        ];


        $contrats = [
            $contrat1,
            $contrat2,
        ];

        /* Calculs manuels */

        $contrat1['avenants'] = [&$contrat2];
        $contrat2['parent']   = &$contrat1;


        /* Expected */
        $expected = [
            $contrat1,
            $contrat2,
        ];

        $this->assertContrats($contrats, $expected);
    }



    public function testCasComplique(): void
    {
        $parametres = [
            Parametre::AVENANT     => Parametre::AVENANT_AUTORISE,
            Parametre::CONTRAT_MIS => Parametre::CONTRAT_MIS_MISSION,
            Parametre::CONTRAT_ENS => Parametre::CONTRAT_ENS_GLOBAL,
        ];
        $this->useParametres($parametres);

        /* Jeu de données initial */
        $contrat1 = [
            'id'              => 1,
            'isMission'       => true,
            'structureId'     => null,
            'edite'           => true,
            'avenants'        => [],
            'volumesHoraires' => [
                [
                    ['missionId' => 1],
                    ['missionId' => 2],
                    ['missionId' => 3],
                ],
            ],
        ];

        $contrat2 = [
            'id'              => 2,
            'isMission'       => true,
            'parents'         => &$contrat1,
            'structureId'     => 1,
            'avenants'        => [],
            'volumesHoraires' => [
                ['missionId' => 1],
            ],
        ];

        $contrat1['avenants'][] = &$contrat2;

        $contrat3 = [
            'id'              => null,
            'isMission'       => true,
            'parents'         => null,
            'structureId'     => 2,
            'avenants'        => [],
            'volumesHoraires' => [
                ['missionId' => 1],
            ],
        ];


        $contrats = [
            $contrat1,
            $contrat2,
            $contrat3,
        ];

        /* Calculs manuels */

        $contrat1['avenants'] = [&$contrat2, &$contrat3];
        $contrat2['parent']   = &$contrat1;
        $contrat3['parent']   = &$contrat1;


        /* Expected */
        $expected = [
            $contrat1,
            $contrat2,
            $contrat3,
        ];

        $this->assertContrats($contrats, $expected);
    }



    public function testMissionComposante(): void
    {
        $parametres = [
            Parametre::AVENANT     => Parametre::AVENANT_AUTORISE,
            Parametre::CONTRAT_MIS => Parametre::CONTRAT_MIS_COMPOSANTE,
            Parametre::CONTRAT_ENS => Parametre::CONTRAT_ENS_GLOBAL,
        ];
        $this->useParametres($parametres);

        /* Jeu de données initial */

        //deux contrats anciennement créer sous un parametrage contrat_mis_mission
        $contrat1 = [
            'id'              => 1,
            'isMission'       => true,
            'structureId'     => 3,
            'edite'           => true,
            'avenants'        => [],
            'volumesHoraires' => [
                ['missionId' => 18],
            ],
        ];

        $contrat2 = [
            'id'              => 2,
            'isMission'       => true,
            'parents'         => null,
            'structureId'     => 3,
            'edite'           => true,
            'avenants'        => [],
            'volumesHoraires' => [
                ['missionId' => 1],
            ],
        ];

        //un nouveau contrat a créer apres changement de parametrage en contrat_mis_composante
        $contrat3 = [
            'id'              => null,
            'isMission'       => true,
            'parents'         => null,
            'structureId'     => 3,
            'avenants'        => [],
            'volumesHoraires' => [
                ['missionId' => 1],
            ],
        ];


        $contrats = [
            $contrat1,
            $contrat2,
            $contrat3,
        ];

        /* Calculs manuels */

        $contrat2['avenants'] = [&$contrat3];
        $contrat3['parent']   = &$contrat2;


        /* Expected */
        $expected = [
            $contrat1,
            $contrat2,
            $contrat3,
        ];

        $this->assertContrats($contrats, $expected);
    }



    public function testMissionComposanteContratEtProjet(): void
    {
        $parametres = [
            Parametre::AVENANT     => Parametre::AVENANT_AUTORISE,
            Parametre::CONTRAT_MIS => Parametre::CONTRAT_MIS_COMPOSANTE,
            Parametre::CONTRAT_ENS => Parametre::CONTRAT_ENS_GLOBAL,
        ];
        $this->useParametres($parametres);

        /* Jeu de données initial */

        //deux contrats anciennement créer sous un parametrage contrat_mis_mission, l'un est un projet l'autre non
        $contrat1 = [
            'id'              => 1,
            'isMission'       => true,
            'structureId'     => 3,
            'edite'           => true,
            'avenants'        => [],
            'volumesHoraires' => [
                ['missionId' => 18],
            ],
        ];

        $contrat2 = [
            'id'              => 2,
            'isMission'       => true,
            'parents'         => null,
            'structureId'     => 3,
            'edite'           => false,
            'avenants'        => [],
            'volumesHoraires' => [
                ['missionId' => 1],
            ],
        ];

        //un nouveau contrat a créer apres changement de parametrage en contrat_mis_composante
        $contrat3 = [
            'id'              => null,
            'isMission'       => true,
            'parents'         => null,
            'structureId'     => 3,
            'avenants'        => [],
            'volumesHoraires' => [
                ['missionId' => 1],
            ],
        ];


        $contrats = [
            $contrat1,
            $contrat2,
            $contrat3,
        ];

        /* Calculs manuels */

        $contrat1['avenants'] = [&$contrat3];
        $contrat3['parent']   = &$contrat1;


        /* Expected */
        $expected = [
            $contrat1,
            $contrat2,
            $contrat3,
        ];

        $this->assertContrats($contrats, $expected);
    }

    public function testContratGlobalParamParMission(): void
    {
        $parametres = [
            Parametre::AVENANT     => Parametre::AVENANT_AUTORISE,
            Parametre::CONTRAT_MIS => Parametre::CONTRAT_MIS_MISSION,
            Parametre::CONTRAT_ENS => Parametre::CONTRAT_ENS_GLOBAL,
        ];
        $this->useParametres($parametres);

        /* Jeu de données initial */

        //deux contrats anciennement créer sous un parametrage contrat_mis_mission, l'un est un projet l'autre non
        $contrat1 = [
            'id'              => 1,
            'isMission'       => true,
            'structureId'     => null,
            'edite'           => true,
            'avenants'        => [],
            'volumesHoraires' => [
                [
                    'missionId' => 1,
                ],
                [
                    'missionId' => 2,
                ],
                [
                    'missionId' => 3,
                ],
            ],
        ];

        $contrat2 = [
            'id'              => null,
            'isMission'       => true,
            'parents'         => null,
            'structureId'     => 3,
            'avenants'        => [],
            'volumesHoraires' => [
                ['missionId' => 4],
            ],
        ];

        //un nouveau contrat a créer apres changement de parametrage en contrat_mis_composante
        $contrat3 = [
            'id'              => null,
            'isMission'       => true,
            'parents'         => null,
            'structureId'     => 3,
            'avenants'        => [],
            'volumesHoraires' => [
                ['missionId' => 1],
            ],
        ];


        $contrats = [
            $contrat1,
            $contrat2,
            $contrat3,
        ];

        /* Calculs manuels */

        $contrat1['avenants'] = [&$contrat2,&$contrat3];
        $contrat2['parent']   = &$contrat1;
        $contrat3['parent']   = &$contrat1;


        /* Expected */
        $expected = [
            $contrat1,
            $contrat2,
            $contrat3,
        ];

        $this->assertContrats($contrats, $expected);
    }
}
