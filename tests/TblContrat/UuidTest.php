<?php declare(strict_types=1);

namespace tests\TblContrat;

use Application\Entity\Db\Parametre;

final class UuidTest extends TblContratTestCase
{
    public function assertUuidDataset(array $dataset): void
    {
        foreach ($dataset as $expected => $data) {
            extract($data);

            $uuid = $this->process->generateUUID(
                intervenantId: $intervenantId ?? 1, // toujours 1 éventuellement
                contratId    : $contratId ?? null,
                structureId  : $structureId ?? null,
                missionId    : $missionId ?? null
            );

            self::assertEquals($expected, $uuid);
        }
    }



    public function testParametresCaen()
    {
        $parametres = [
            Parametre::AVENANT     => Parametre::AVENANT_AUTORISE,
            Parametre::CONTRAT_MIS => Parametre::CONTRAT_MIS_MISSION,
            Parametre::CONTRAT_ENS => Parametre::CONTRAT_ENS_COMPOSANTE,
        ];
        $this->useParametres($parametres);

        $dataset = [
            'contrat_id_1' => [
                'contratId' => 1,    // int|null,
            ],
            'ens_structure_18_2' => [
                'intervenantId' => 18,      // int|null,
                'contratId'     => null,    // int|null,
                'structureId'   => 2,       // int|null,
            ],
            'mis_mission_18_5' => [
                'intervenantId' => 18,      // int|null,
                'contratId'     => null,    // int|null,
                'structureId'   => 2,       // int|null,
                'missionId'     => 5,       // int|null,
            ],
            'contrat_id_8' => [
                'intervenantId' => 18,    // int|null,
                'contratId'     => 8,     // int|null,
                'structureId'   => 2,     // int|null,
                'missionId'     => 5,     // int|null,
            ],
            // autres cas à ajouter ...
        ];
        $this->assertUuidDataset($dataset);
    }



    public function testParametresContratUnique()
    {
        $parametres = [
            Parametre::AVENANT     => Parametre::AVENANT_DESACTIVE,
            Parametre::CONTRAT_MIS => Parametre::CONTRAT_MIS_GLOBAL,
            Parametre::CONTRAT_ENS => Parametre::CONTRAT_ENS_GLOBAL,
        ];
        $this->useParametres($parametres);

        $dataset = [
            'ens_global_1' => [                                                                                                                                           // à adapter...
                'contratId'   => null,                                                                                                                                    // int|null,
                'structureId' => 20,                                                                                                                                      // int|null,
            ],
            'ens_global_18' => [            // à adapter...
                'intervenantId' => 18,      // int|null,
                'contratId'     => null,    // int|null,
                'structureId'   => 20,      // int|null,
            ],
            'mis_global_18' => [            // à adapter...
                'intervenantId' => 18,      // int|null,
                'contratId'     => null,    // int|null,
                'structureId'   => 20,      // int|null,
                'missionId'     => 15,      // int|null,
            ],
            'contrat_id_2' => [             // à adapter...
                'intervenantId' => 18,      // int|null,
                'contratId'     => 2,       // int|null,
                'structureId'   => 20,      // int|null,
                'missionId'     => 15,      // int|null,
            ],
            // autres cas à ajouter ...
        ];

        $this->assertUuidDataset($dataset);
    }

}
