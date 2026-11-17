<?php declare(strict_types=1);

namespace TblWorkflow;

use tests\OseTestCase;
use UnicaenTbl\Service\TableauBordService;
use Workflow\Entity\Db\WorkflowEtape;
use Workflow\Entity\Db\WorkflowEtapeDependance;
use Workflow\Service\WorkflowService;
use Workflow\Tbl\Process\Model\IntervenantEtape;
use Workflow\Tbl\Process\Model\IntervenantEtapeStructure;
use Workflow\Tbl\Process\WorkflowProcess;

final class ProcessTest extends OseTestCase
{
    protected WorkflowProcess $wp;



    protected function setUp(): void
    {
        $c = \Unicaen\Framework\Application\Application::getInstance()->container()->get(TableauBordService::class);

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



    public function testIntegralSansPrecedent()
    {
        $avancement = WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT;

        $precs = [];

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



    /**
     * Construit un workflow candidature_validation -> mission_saisie et retourne,
     * pour chaque structure de mission_saisie, si elle est atteignable après calcul des dépendances.
     *
     * @param array $candidatures [structureId => realisation (0 = en attente de réponse, 1 = répondue)]
     * @param array $missions     [structureId, ...]
     */
    protected function missionAtteignable(array $candidatures, array $missions): array
    {
        $etapes = \Unicaen\Framework\Application\Application::getInstance()->container()
            ->get(WorkflowService::class)->getEtapes(2025);

        $etapeMission     = $etapes[WorkflowEtape::MISSION_SAISIE] ?? null;
        $etapeCandidature = $etapes[WorkflowEtape::CANDIDATURE_VALIDATION] ?? null;
        $dependanceActive = false;
        foreach ($etapeMission?->getDependances() ?? [] as $dep) {
            if ($dep->isActive() && $dep->getEtapePrecedante()->getCode() === WorkflowEtape::CANDIDATURE_VALIDATION) {
                $dependanceActive = true;
            }
        }
        if (!$etapeCandidature || !$dependanceActive) {
            $this->markTestSkipped('Dépendance mission_saisie -> candidature_validation non paramétrée en 2025');
        }

        $make = function (WorkflowEtape $etape, array $structures): IntervenantEtape {
            $ie                    = new IntervenantEtape($etape);
            $ie->typeIntervenantId = 0;
            $ie->cloture           = false;
            foreach ($structures as $strId => $realisation) {
                $ies = new IntervenantEtapeStructure();
                $ies->createfromArray(['structure' => $strId, 'objectif' => 1, 'realisation' => $realisation]);
                $ie->structures[$strId] = $ies;
            }

            return $ie;
        };

        $workflow = [
            WorkflowEtape::CANDIDATURE_VALIDATION => $make($etapeCandidature, $candidatures),
            WorkflowEtape::MISSION_SAISIE         => $make($etapeMission, array_fill_keys($missions, 1)),
        ];

        $m = new \ReflectionMethod($this->wp, 'calculDependances');
        $m->invokeArgs($this->wp, [&$workflow]);

        return array_map(fn($s) => $s->atteignable, $workflow[WorkflowEtape::MISSION_SAISIE]->structures);
    }



    public function testMissionBloqueeParCandidatureNonValideeMemeComposante()
    {
        $this->assertSame([35 => false], $this->missionAtteignable([35 => 0], [35]));
    }



    public function testMissionOkCandidatureValideeMemeComposante()
    {
        $this->assertSame([35 => true], $this->missionAtteignable([35 => 1], [35]));
    }



    public function testMissionManuelleAutreComposanteNonBloquee()
    {
        // Mission créée à la DRH (21) sans candidature, seule candidature au Carré International (35)
        $this->assertSame([21 => true], $this->missionAtteignable([35 => 1], [21]));
        $this->assertSame([21 => true], $this->missionAtteignable([35 => 0], [21]));
    }



    public function testMissionsPlusieursComposantes()
    {
        // Candidature en attente au 35 : la mission du 35 est bloquée, pas celle de la DRH
        $this->assertSame([35 => false, 21 => true], $this->missionAtteignable([35 => 0], [35, 21]));
    }
}
