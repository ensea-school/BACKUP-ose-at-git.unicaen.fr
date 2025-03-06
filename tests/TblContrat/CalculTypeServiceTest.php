<?php declare(strict_types=1);

namespace TblContrat;

use Application\Entity\Db\Parametre;
use Contrat\Tbl\Process\Model\Contrat;
use tests\TblContrat\TblContratTestCase;
use UnicaenTbl\Service\TableauBordService;

final class CalculTypeServiceTest extends TblContratTestCase
{
    public function testTypeServiceEnseignementTest()
    {

        $data = [
            'id' => 1,
            'structureId' => 2,
            'isMission' => false,
            'anneeDateDebut' => '2025-03-06',
            'volumesHoraires' => [
                [
                  'structureId' => 2,
                  'serviceId' => 10,
                  //'serviceId' => 1,
                  'volumeHoraireRefId' => 15,
                  'tauxRemuId' => 1,
                  'tauxRemuMajoreId' => 2,
                  'cm' => 10.0,
                  'td' => 10.0,
                  'tp' => 20.0
                ],
            ]
        ];

        $await = [
            'id' => 1,
            'structureId' => 2,
            'isMission' => false,
            'anneeDateDebut' => '2025-03-06',
            'volumesHoraires' => [
                [
                    'structureId' => 2,
                    'serviceId' => 10,
                    'volumeHoraireRefId' => 15,
                    'tauxRemuId' => 1,
                    'tauxRemuMajoreId' => 2,
                    'cm' => 10.0,
                    'td' => 10.0,
                    'tp' => 20.0
                ],
            ]
        ];

        $contrat = $this->hydrateContrat($data);
        $this->process->calculTypeService($contrat);
        $calc = $this->extractContrat($contrat);
        $this->assertArrayEquals($await, $calc, false);

    }

    public function testTypeServiceMissionTest()
    {

        $data = [
            'id' => 1,
            'structureId' => 2,
            'isMission' => false,
            'anneeDateDebut' => '2025-03-06',
            'volumesHoraires' => [
                [
                    'structureId' => 2,
                    'missionId' => 10,
                    'volumeHoraireRefId' => 15,
                    'tauxRemuId' => 1,
                    'tauxRemuMajoreId' => 2,
                    'cm' => 10.0,
                    'td' => 10.0,
                    'tp' => 20.0
                ],
            ]
        ];

        $await = [
            'id' => 1,
            'structureId' => 2,
            'isMission' => true,
            'anneeDateDebut' => '2025-03-06',
            'volumesHoraires' => [
                [
                    'structureId' => 2,
                    'missionId' => 10,
                    'volumeHoraireRefId' => 15,
                    'tauxRemuId' => 1,
                    'tauxRemuMajoreId' => 2,
                    'cm' => 10.0,
                    'td' => 10.0,
                    'tp' => 20.0
                ],
            ]
        ];

        $contrat = $this->hydrateContrat($data);
        $this->process->calculTypeService($contrat);
        $calc = $this->extractContrat($contrat);
        $this->assertArrayEquals($await, $calc, false);

    }





}
