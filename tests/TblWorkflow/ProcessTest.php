<?php declare(strict_types=1);

namespace TblWorkflow;

use tests\OseTestCase;
use UnicaenTbl\Service\TableauBordService;
use Workflow\Entity\Db\WorkflowEtapeDependance;
use Workflow\Tbl\Process\Model\IntervenantEtapeStructure;
use Workflow\Tbl\Process\WorkflowProcess;

final class ProcessTest extends OseTestCase
{
    protected WorkflowProcess $wp;



    protected function setUp(): void
    {
        $c = \AppAdmin::container()->get(TableauBordService::class);

        $this->wp = $c->getTableauBord('workflow')->getProcess();
    }



    protected function processDep(int $avancement, array $precs, bool $expected): void
    {
        $precsObj = [];
        foreach ($precs as $strId => $precData) {
            $precData['structure'] = $strId;
            $precObj               = new IntervenantEtapeStructure();
            $precObj->createfromArray($precData);
            $precsObj[$strId] = $precObj;
        }

        $result = $this->wp->isDependanceOk($avancement, $precsObj);

        $this->assertEquals($expected, $result);
    }



    public function testSimple()
    {
        $avancement = WorkflowEtapeDependance::AVANCEMENT_DEBUTE;

        $precs = [
            [
                'objectif'    => 1,
                'partiel'     => 1,
                'realisation' => 1,
            ],
        ];

        $expected = true;

        $this->processDep($avancement, $precs, $expected);
    }



    public function testSimpleMultiPrec()
    {
        $avancement = WorkflowEtapeDependance::AVANCEMENT_DEBUTE;

        $precs = [
            [
                'objectif'    => 1,
                'partiel'     => 1,
                'realisation' => 1,
            ],
            [
                'objectif'    => 1,
                'partiel'     => 1,
                'realisation' => 1,
            ],
        ];

        $expected = true;

        $this->processDep($avancement, $precs, $expected);
    }



    public function testSimpleMultiPrecNo()
    {
        $avancement = WorkflowEtapeDependance::AVANCEMENT_DEBUTE;

        $precs = [
            [
                'objectif'    => 1,
                'partiel'     => 0,
                'realisation' => 0,
            ],
            [
                'objectif'    => 1,
                'partiel'     => 0,
                'realisation' => 0,
            ],
        ];

        $expected = false;

        $this->processDep($avancement, $precs, $expected);
    }



    public function testSimpleMultiPrecPartiel()
    {
        $avancement = WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT;

        $precs = [
            [
                'objectif'    => 1,
                'partiel'     => 1,
                'realisation' => 1,
            ],
            [
                'objectif'    => 2,
                'partiel'     => 0,
                'realisation' => 0,
            ],
        ];

        $expected = true;

        $this->processDep($avancement, $precs, $expected);
    }



    public function testSimpleMultiPrecPartielNo()
    {
        $avancement = WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT;

        $precs = [
            [
                'objectif'    => 2,
                'partiel'     => 0,
                'realisation' => 1,
            ],
            [
                'objectif'    => 2,
                'partiel'     => 0,
                'realisation' => 1,
            ],
        ];

        $expected = false;

        $this->processDep($avancement, $precs, $expected);
    }



    public function testIntegral()
    {
        $avancement = WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT;

        $precs = [
            [
                'objectif'    => 2,
                'partiel'     => 1,
                'realisation' => 2,
            ],
        ];

        $expected = true;

        $this->processDep($avancement, $precs, $expected);
    }



    public function testIntegralMultiple()
    {
        $avancement = WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT;

        $precs = [
            [
                'objectif'    => 2,
                'partiel'     => 1,
                'realisation' => 2,
            ],
            [
                'objectif'    => 2,
                'partiel'     => 0,
                'realisation' => 6,
            ],
        ];

        $expected = true;

        $this->processDep($avancement, $precs, $expected);
    }



    public function testIntegralNo()
    {
        $avancement = WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT;

        $precs = [
            [
                'objectif'    => 2,
                'partiel'     => 1,
                'realisation' => 1,
            ],
        ];

        $expected = false;

        $this->processDep($avancement, $precs, $expected);
    }



    public function testIntegralMultipleNo()
    {
        $avancement = WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT;

        $precs = [
            [
                'objectif'    => 2,
                'partiel'     => 1,
                'realisation' => 3,
            ],
            [
                'objectif'    => 2,
                'partiel'     => 0,
                'realisation' => 1,
            ],
        ];

        $expected = false;

        $this->processDep($avancement, $precs, $expected);
    }
}