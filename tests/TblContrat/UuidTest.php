<?php declare(strict_types=1);

namespace tests\TblContrat;

use Application\Entity\Db\Parametre;

final class UuidTest extends TblContratTestCase
{

    public function testParametresCaen()
    {
        $parametres = [
            Parametre::AVENANT        => Parametre::AVENANT_AUTORISE,
            Parametre::CONTRAT_MIS    => Parametre::CONTRAT_MIS_MISSION,
            Parametre::CONTRAT_ENS    => Parametre::CONTRAT_ENS_COMPOSANTE,
        ];
        $this->useParametres($parametres);

        $dataset = [
            "contrat_id_1" => [
                'contratId' => 1,    // int|null,
            ],
            // autres cas à ajouter ...
        ];

        foreach ($dataset as $expected => $data) {
            extract($data);

            $uuid = $this->process->generateUUID(
                intervenant_id: $intervenantId ?? 1, // toujours 1 éventuellement
                contratId     : $contratId ?? null,
                structureId   : $structureId ?? null,
                missionId     : $missionId ?? null
            );

            $this->assertEquals($expected, $uuid);
        }
    }



    public function testParametresContratUnique()
    {
        $parametres = [
            Parametre::AVENANT        => Parametre::AVENANT_DESACTIVE,
            Parametre::CONTRAT_MIS    => Parametre::CONTRAT_MIS_GLOBALE,
            Parametre::CONTRAT_ENS    => Parametre::CONTRAT_ENS_GLOBALE,
        ];
        $this->useParametres($parametres);

        $dataset = [
            "contrat_id_1" => [ // à adapter...
                'contratId' => null,    // int|null,
            ],
            // autres cas à ajouter ...
        ];

        foreach ($dataset as $expected => $data) {
            extract($data);

            $uuid = $this->process->generateUUID(
                intervenant_id: $intervenantId ?? 1, // toujours 1 éventuellement
                contratId     : $contratId ?? null,
                structureId   : $structureId ?? null,
                missionId     : $missionId ?? null
            );

            $this->assertEquals($expected, $uuid);
        }
    }
}
