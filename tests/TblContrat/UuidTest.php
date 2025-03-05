<?php declare(strict_types=1);

namespace tests\TblContrat;

final class UuidTest extends TblContratTestCase
{

    public function testEssai1()
    {
        $dataset = [
            "contrat_id_1" => [
                'intervenantId' => 1,    // int
                'contratId'     => 1,    // int|null,
                'structureId'   => null, // int|null
                //'missionId'     => null, // int|null

                //'expected' => ,
            ],
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
