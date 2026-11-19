<?php declare(strict_types=1);

namespace TblWorkflow;

use Application\Entity\Db\Perimetre;
use Intervenant\Entity\Db\TypeIntervenant;
use tests\WorkflowTestCase;
use UnicaenTbl\Service\TableauBordService;
use Workflow\Entity\Db\WorkflowEtape;
use Workflow\Tbl\Process\Model\IntervenantEtape;
use Workflow\Tbl\Process\Model\IntervenantEtapeStructure;
use Workflow\Tbl\Process\WorkflowProcess;

/**
 * Calcul des dépendances entre étapes (WorkflowProcess::calculDependances) sur des workflows construits en mémoire.
 */
final class CalculDependancesTest extends WorkflowTestCase
{
    protected WorkflowProcess $wp;



    protected function setUp(): void
    {
        $c        = \Unicaen\Framework\Application\Application::getInstance()->container()->get(TableauBordService::class);
        $this->wp = $c->getTableauBord('workflow')->getProcess();
    }



    /**
     * @param array|IntervenantEtapeStructure[] $structures indexées par id de structure
     */
    private function ie(WorkflowEtape $etape, array $structures, string $type = TypeIntervenant::CODE_EXTERIEUR, bool $cloture = false): IntervenantEtape
    {
        $ie                      = new IntervenantEtape($etape, $this->typeIntervenant($type));
        $ie->cloture             = $cloture;
        $ie->structures          = $structures;

        return $ie;
    }



    /** @param array|IntervenantEtape[] $workflow */
    private function calcul(array $workflow): array
    {
        $m = new \ReflectionMethod($this->wp, 'calculDependances');
        $m->setAccessible(true);
        $args = [&$workflow];
        $m->invokeArgs($this->wp, $args);

        return $workflow;
    }



    private function atteignable(array $workflow, string $etape, int $structure): bool
    {
        return $workflow[$etape]->structures[$structure]->atteignable;
    }



    public function testEtapeSansDependanceResteAtteignable(): void
    {
        $a  = $this->etape('a');
        $wf = $this->calcul(['a' => $this->ie($a, [10 => $this->etapeStructure(10, 1, 0, 0)])]);

        $this->assertTrue($this->atteignable($wf, 'a', 10));
    }



    public function testDependanceEtablissementSatisfaite(): void
    {
        $dp = $this->etape('dp', Perimetre::ETABLISSEMENT);
        $a  = $this->etape('a');
        $this->dependance($a, $dp, self::AVANCEMENT_DEBUTE, Perimetre::ETABLISSEMENT);

        $wf = $this->calcul([
                                'dp' => $this->ie($dp, [0 => $this->etapeStructure(0, 1, 1, 1)]),
                                'a'  => $this->ie($a, [10 => $this->etapeStructure(10, 1, 0, 0)]),
                            ]);

        $this->assertTrue($this->atteignable($wf, 'a', 10));
    }



    public function testDependanceEtablissementNonSatisfaiteBloqueEtDonneLeMotif(): void
    {
        $dp = $this->etape('dp', Perimetre::ETABLISSEMENT);
        $a  = $this->etape('a');
        $this->dependance($a, $dp, self::AVANCEMENT_DEBUTE, Perimetre::ETABLISSEMENT);

        $wf = $this->calcul([
                                'dp' => $this->ie($dp, [0 => $this->etapeStructure(0, 1, 0, 0)]),
                                'a'  => $this->ie($a, [10 => $this->etapeStructure(10, 1, 0, 0)]),
                            ]);

        $this->assertFalse($this->atteignable($wf, 'a', 10));
        $this->assertSame(['Non franchie : dp'], $wf['a']->structures[10]->whyNonAtteignable);
    }



    public function testDependanceComposanteMemeStructure(): void
    {
        $cr = $this->etape('cr');
        $c  = $this->etape('c');
        $this->dependance($c, $cr, self::AVANCEMENT_INTEGRAL, Perimetre::COMPOSANTE);

        $wf = $this->calcul([
                                'cr' => $this->ie($cr, [10 => $this->etapeStructure(10, 1, 1, 1)]),
                                'c'  => $this->ie($c, [10 => $this->etapeStructure(10, 1, 0, 0)]),
                            ]);

        $this->assertTrue($this->atteignable($wf, 'c', 10));
    }



    public function testDependanceComposanteAutreStructureNeCompteurPas(): void
    {
        $cr = $this->etape('cr');
        $c  = $this->etape('c');
        $this->dependance($c, $cr, self::AVANCEMENT_INTEGRAL, Perimetre::COMPOSANTE);

        // l'agrément est fait en composante 10, le contrat est évalué en composante 20 : pas d'agrément pour 20
        $wf = $this->calcul([
                                'cr' => $this->ie($cr, [10 => $this->etapeStructure(10, 1, 1, 1)]),
                                'c'  => $this->ie($c, [20 => $this->etapeStructure(20, 1, 0, 0)]),
                            ]);

        $this->assertFalse($this->atteignable($wf, 'c', 20));
    }



    public function testPrecedenteSansComposanteValantPourToutesLesComposantes(): void
    {
        $cr = $this->etape('cr', Perimetre::ETABLISSEMENT);
        $c  = $this->etape('c');
        $this->dependance($c, $cr, self::AVANCEMENT_INTEGRAL, Perimetre::COMPOSANTE);

        $wf = $this->calcul([
                                'cr' => $this->ie($cr, [0 => $this->etapeStructure(0, 1, 1, 1)]),
                                'c'  => $this->ie($c, [10 => $this->etapeStructure(10, 1, 0, 0), 20 => $this->etapeStructure(20, 1, 0, 0)]),
                            ]);

        $this->assertTrue($this->atteignable($wf, 'c', 10));
        $this->assertTrue($this->atteignable($wf, 'c', 20));
    }



    public function testEtapeSansComposanteExigeToutesLesComposantesDeLaPrecedente(): void
    {
        // contrat sans composante (0) : tous les agréments de composante sont requis
        $cr = $this->etape('cr');
        $c  = $this->etape('c');
        $this->dependance($c, $cr, self::AVANCEMENT_INTEGRAL, Perimetre::COMPOSANTE);

        $wf = $this->calcul([
                                'cr' => $this->ie($cr, [10 => $this->etapeStructure(10, 1, 1, 1), 20 => $this->etapeStructure(20, 1, 0, 0)]),
                                'c'  => $this->ie($c, [0 => $this->etapeStructure(0, 1, 0, 0)]),
                            ]);

        $this->assertFalse($this->atteignable($wf, 'c', 0));
    }



    public function testEtapeSansComposanteAvecToutesLesComposantesFinies(): void
    {
        $cr = $this->etape('cr');
        $c  = $this->etape('c');
        $this->dependance($c, $cr, self::AVANCEMENT_INTEGRAL, Perimetre::COMPOSANTE);

        $wf = $this->calcul([
                                'cr' => $this->ie($cr, [10 => $this->etapeStructure(10, 1, 1, 1), 20 => $this->etapeStructure(20, 1, 1, 1)]),
                                'c'  => $this->ie($c, [0 => $this->etapeStructure(0, 1, 0, 0)]),
                            ]);

        $this->assertTrue($this->atteignable($wf, 'c', 0));
    }



    public function testToutesLesDependancesSontRequises(): void
    {
        $a = $this->etape('a', Perimetre::ETABLISSEMENT);
        $b = $this->etape('b', Perimetre::ETABLISSEMENT);
        $c = $this->etape('c');
        $this->dependance($c, $a, self::AVANCEMENT_DEBUTE, Perimetre::ETABLISSEMENT);
        $this->dependance($c, $b, self::AVANCEMENT_DEBUTE, Perimetre::ETABLISSEMENT);

        $wf = $this->calcul([
                                'a' => $this->ie($a, [0 => $this->etapeStructure(0, 1, 1, 1)]),
                                'b' => $this->ie($b, [0 => $this->etapeStructure(0, 1, 0, 0)]),
                                'c' => $this->ie($c, [10 => $this->etapeStructure(10, 1, 0, 0)]),
                            ]);

        $this->assertFalse($this->atteignable($wf, 'c', 10));
        $this->assertSame(['Non franchie : b'], $wf['c']->structures[10]->whyNonAtteignable);
    }



    public function testDependanceInactiveIgnoree(): void
    {
        $dp = $this->etape('dp', Perimetre::ETABLISSEMENT);
        $a  = $this->etape('a');
        $this->dependance($a, $dp, self::AVANCEMENT_DEBUTE, Perimetre::ETABLISSEMENT, null, false);

        $wf = $this->calcul([
                                'dp' => $this->ie($dp, [0 => $this->etapeStructure(0, 1, 0, 0)]),
                                'a'  => $this->ie($a, [10 => $this->etapeStructure(10, 1, 0, 0)]),
                            ]);

        $this->assertTrue($this->atteignable($wf, 'a', 10));
    }



    public function testDependanceAutreTypeIntervenantIgnoree(): void
    {
        // la dépendance ne vaut que pour les extérieurs : un permanent n'est pas bloqué
        $dp = $this->etape('dp', Perimetre::ETABLISSEMENT);
        $a  = $this->etape('a');
        $this->dependance($a, $dp, self::AVANCEMENT_DEBUTE, Perimetre::ETABLISSEMENT, TypeIntervenant::CODE_EXTERIEUR);

        $wf = $this->calcul([
                                'dp' => $this->ie($dp, [0 => $this->etapeStructure(0, 1, 0, 0)], TypeIntervenant::CODE_PERMANENT),
                                'a'  => $this->ie($a, [10 => $this->etapeStructure(10, 1, 0, 0)], TypeIntervenant::CODE_PERMANENT),
                            ]);

        $this->assertTrue($this->atteignable($wf, 'a', 10));
    }



    public function testDependanceMemeTypeIntervenantAppliquee(): void
    {
        $dp = $this->etape('dp', Perimetre::ETABLISSEMENT);
        $a  = $this->etape('a');
        $this->dependance($a, $dp, self::AVANCEMENT_DEBUTE, Perimetre::ETABLISSEMENT, TypeIntervenant::CODE_EXTERIEUR);

        $wf = $this->calcul([
                                'dp' => $this->ie($dp, [0 => $this->etapeStructure(0, 1, 0, 0)], TypeIntervenant::CODE_EXTERIEUR),
                                'a'  => $this->ie($a, [10 => $this->etapeStructure(10, 1, 0, 0)], TypeIntervenant::CODE_EXTERIEUR),
                            ]);

        $this->assertFalse($this->atteignable($wf, 'a', 10));
    }



    public function testPrecedenteAbsenteDuWorkflowIgnoree(): void
    {
        $dp = $this->etape('dp', Perimetre::ETABLISSEMENT);
        $a  = $this->etape('a');
        $this->dependance($a, $dp, self::AVANCEMENT_DEBUTE, Perimetre::ETABLISSEMENT);

        // l'étape 'dp' n'est pas active pour ce statut : le test n'est pas pertinent
        $wf = $this->calcul(['a' => $this->ie($a, [10 => $this->etapeStructure(10, 1, 0, 0)])]);

        $this->assertTrue($this->atteignable($wf, 'a', 10));
    }



    public function testClotureNonRequiseParLeStatutIgnoree(): void
    {
        $cl = $this->etape(WorkflowEtape::CLOTURE_REALISE, Perimetre::ETABLISSEMENT);
        $a  = $this->etape('a');
        $this->dependance($a, $cl, self::AVANCEMENT_INTEGRAL, Perimetre::ETABLISSEMENT);

        $wf = $this->calcul([
                                WorkflowEtape::CLOTURE_REALISE => $this->ie($cl, [0 => $this->etapeStructure(0, 1, 0, 0)], TypeIntervenant::CODE_EXTERIEUR, false),
                                'a'                            => $this->ie($a, [10 => $this->etapeStructure(10, 1, 0, 0)], TypeIntervenant::CODE_EXTERIEUR, false),
                            ]);

        $this->assertTrue($this->atteignable($wf, 'a', 10));
    }



    public function testClotureRequiseParLeStatutBloque(): void
    {
        $cl = $this->etape(WorkflowEtape::CLOTURE_REALISE, Perimetre::ETABLISSEMENT);
        $a  = $this->etape('a');
        $this->dependance($a, $cl, self::AVANCEMENT_INTEGRAL, Perimetre::ETABLISSEMENT);

        $wf = $this->calcul([
                                WorkflowEtape::CLOTURE_REALISE => $this->ie($cl, [0 => $this->etapeStructure(0, 1, 0, 0)], TypeIntervenant::CODE_EXTERIEUR, true),
                                'a'                            => $this->ie($a, [10 => $this->etapeStructure(10, 1, 0, 0)], TypeIntervenant::CODE_EXTERIEUR, true),
                            ]);

        $this->assertFalse($this->atteignable($wf, 'a', 10));
    }



    public function testAvancementPartielSuffitUnContratTermine(): void
    {
        // "au moins un contrat terminé" : un des deux contrats suffit pour l'avancement partiel
        $c = $this->etape('c');
        $r = $this->etape('r');
        $this->dependance($r, $c, self::AVANCEMENT_PARTIEL, Perimetre::COMPOSANTE);

        $wf = $this->calcul([
                                'c' => $this->ie($c, [10 => $this->etapeStructure(10, 2, 1, 1)]),
                                'r' => $this->ie($r, [10 => $this->etapeStructure(10, 1, 0, 0)]),
                            ]);

        $this->assertTrue($this->atteignable($wf, 'r', 10));
    }



    public function testAvancementIntegralNeSuffitPasUnContratSurDeux(): void
    {
        $c = $this->etape('c');
        $r = $this->etape('r');
        $this->dependance($r, $c, self::AVANCEMENT_INTEGRAL, Perimetre::COMPOSANTE);

        $wf = $this->calcul([
                                'c' => $this->ie($c, [10 => $this->etapeStructure(10, 2, 1, 1)]),
                                'r' => $this->ie($r, [10 => $this->etapeStructure(10, 1, 0, 0)]),
                            ]);

        $this->assertFalse($this->atteignable($wf, 'r', 10));
    }



    public function testEvaluerAvancementEstStatiqueEtCoherentAvecIsDependanceOk(): void
    {
        $precs = [10 => $this->etapeStructure(10, 1, 1, 1)];

        foreach ([self::AVANCEMENT_DEBUTE, self::AVANCEMENT_PARTIEL, self::AVANCEMENT_INTEGRAL] as $avancement) {
            $this->assertSame(
                $this->wp->isDependanceOk($avancement, $precs),
                WorkflowProcess::evaluerAvancement($avancement, $precs)
            );
        }
        $this->assertFalse(WorkflowProcess::evaluerAvancement(self::AVANCEMENT_DEBUTE, []));
    }
}
