<?php declare(strict_types=1);

namespace TblWorkflow;

use Application\Entity\Db\Perimetre;
use Application\Service\ContextService;
use Intervenant\Entity\Db\TypeIntervenant;
use Lieu\Entity\Db\Structure;
use tests\WorkflowTestCase;
use Unicaen\BddAdmin\Bdd;
use Unicaen\BddAdmin\SelectParser;
use Workflow\Entity\Db\WorkflowEtape;
use Workflow\Model\FeuilleDeRoute;
use Workflow\Service\WorkflowService;

/**
 * Feuille de route vue depuis un contexte (sans composante = admin / rôle établissement, ou une composante),
 * à partir de lignes de tbl_workflow simulées.
 *
 * Composantes : CHIMIE (id 10), IUT (id 20), MATHS (id 30).
 */
final class FeuilleDeRouteTest extends WorkflowTestCase
{
    private const CHIMIE = 10;
    private const IUT    = 20;
    private const MATHS  = 30;

    /** @var array|WorkflowEtape[] */
    private array $etapes = [];

    /** @var array<int,array> lignes simulées de tbl_workflow */
    private array $rows = [];



    protected function setUp(): void
    {
        $this->etapes = [];
        $this->rows   = [];
    }



    // ------------------------------------------------------------------ fabriques

    private function def(string $code, string $perimetre = Perimetre::COMPOSANTE): WorkflowEtape
    {
        return $this->etapes[$code] = $this->etape($code, $perimetre, count($this->etapes) + 1);
    }



    /** Ajoute une ligne tbl_workflow. structure 0 = sans composante. */
    private function row(string $etape, int $structure, bool $atteignable, float $objectif = 1, float $partiel = 0, float $realisation = 0, ?string $why = null): void
    {
        $libelles = [self::CHIMIE => 'Chimie', self::IUT => 'IUT', self::MATHS => 'Maths'];

        $this->rows[] = [
            'etape_code'          => $etape,
            'structure_id'        => $structure ?: null,
            'structure_libelle'   => $structure ? $libelles[$structure] : null,
            'structure_ids'       => $structure ? '-1-' . $structure . '-' : null,
            'atteignable'         => $atteignable ? 1 : 0,
            'objectif'            => $objectif,
            'partiel'             => $partiel,
            'realisation'         => $realisation,
            'why_non_atteignable' => $atteignable ? null : ($why ?? '["blocage en base"]'),
        ];
    }



    private function feuille(?int $structureContexte, string $typeIntervenant = TypeIntervenant::CODE_EXTERIEUR, bool $cloture = false): FeuilleDeRoute
    {
        $rows = $this->rows;
        usort($rows, fn($a, $b) => $this->etapes[$a['etape_code']]->getOrdre() <=> $this->etapes[$b['etape_code']]->getOrdre());

        $parser = $this->createMock(SelectParser::class);
        $parser->method('next')->willReturnOnConsecutiveCalls(...array_merge($rows, [null]));

        $bdd = $this->createMock(Bdd::class);
        $bdd->method('selectEach')->willReturn($parser);

        $service = $this->createMock(WorkflowService::class);
        $service->method('getBdd')->willReturn($bdd);
        $service->method('getServiceContext')->willReturn($this->createMock(ContextService::class));
        $service->method('getUrl')->willReturn('/url');

        $feuille = new FeuilleDeRoute($service, $this->intervenant($typeIntervenant, $cloture), $this->etapes);
        if ($structureContexte) {
            $feuille->setStructure($this->structureParId($structureContexte));
        }

        return $feuille;
    }



    private function structureParId(int $id): Structure
    {
        return $this->structure($id, '-1-' . $id . '-');
    }



    private function allowed(FeuilleDeRoute $feuille, string $etape): bool
    {
        $e = $feuille->get($etape);
        $this->assertNotNull($e, 'étape ' . $etape . ' absente de la feuille de route');

        return $e->isAllowed();
    }



    // ------------------------------------------------------------------ contrat sans composante

    /** Étapes : agrément par composante -> contrat (sans composante, un contrat couvre toutes les composantes) */
    private function contratMultiComposantes(bool $chimieAgree, bool $iutAgree, bool $contratTermine = false): void
    {
        $cr = $this->def('conseil_restreint');
        $c  = $this->def(WorkflowEtape::CONTRAT);
        $this->dependance($c, $cr, self::AVANCEMENT_INTEGRAL, Perimetre::COMPOSANTE);

        $this->row('conseil_restreint', self::CHIMIE, true, 1, (int)$chimieAgree, (int)$chimieAgree);
        $this->row('conseil_restreint', self::IUT, true, 1, (int)$iutAgree, (int)$iutAgree);

        // tbl_workflow : le contrat sans composante exige TOUS les agréments => non atteignable tant qu'il en manque
        $tousAgrees = $chimieAgree && $iutAgree;
        $this->row(WorkflowEtape::CONTRAT, 0, $tousAgrees, 84, (int)$contratTermine, $contratTermine ? 84 : 0, '["Non franchie : conseil_restreint"]');
    }



    public function testAdminUnSeulAgrementSuffitPourLeContratSansComposante(): void
    {
        $this->contratMultiComposantes(chimieAgree: true, iutAgree: false);

        $this->assertTrue($this->allowed($this->feuille(null), WorkflowEtape::CONTRAT));
    }



    public function testAdminAucunAgrementContratBloque(): void
    {
        $this->contratMultiComposantes(chimieAgree: false, iutAgree: false);

        $feuille = $this->feuille(null);
        $this->assertFalse($this->allowed($feuille, WorkflowEtape::CONTRAT));
        $this->assertContains('Non franchie : conseil_restreint', $feuille->get(WorkflowEtape::CONTRAT)->whyNonAtteignable);
    }



    public function testGestionnaireDeComposanteSonAgrementSuffit(): void
    {
        $this->contratMultiComposantes(chimieAgree: true, iutAgree: false);

        $this->assertTrue($this->allowed($this->feuille(self::CHIMIE), WorkflowEtape::CONTRAT));
    }



    public function testGestionnaireDeComposanteSansSonAgrementBloque(): void
    {
        $this->contratMultiComposantes(chimieAgree: true, iutAgree: false);

        $this->assertFalse($this->allowed($this->feuille(self::IUT), WorkflowEtape::CONTRAT));
    }



    public function testContratDejaTermineAccessibleMemeSansAgrementDeLaComposante(): void
    {
        $this->contratMultiComposantes(chimieAgree: false, iutAgree: false, contratTermine: true);

        $this->assertTrue($this->allowed($this->feuille(self::IUT), WorkflowEtape::CONTRAT));
        $this->assertTrue($this->allowed($this->feuille(null), WorkflowEtape::CONTRAT));
    }



    public function testReevaluationLimiteAuContratLesAutresEtapesGardentLeurEtatEnBase(): void
    {
        // une étape sans composante non atteignable en base qui n'est PAS le contrat n'est pas ré-évaluée
        $cr = $this->def('conseil_restreint');
        $x  = $this->def('autre_etape');
        $this->dependance($x, $cr, self::AVANCEMENT_INTEGRAL, Perimetre::COMPOSANTE);

        $this->row('conseil_restreint', self::CHIMIE, true, 1, 1, 1);
        $this->row('autre_etape', 0, false);

        $this->assertFalse($this->allowed($this->feuille(self::CHIMIE), 'autre_etape'));
    }



    // ------------------------------------------------------------------ étapes sans ligne pour la composante

    public function testSaisiePrevisionnelAccessibleDansUneComposanteSansLigne(): void
    {
        $dp = $this->def('donnees_perso_saisie', Perimetre::ETABLISSEMENT);
        $s  = $this->def('enseignement_saisie');
        $this->dependance($s, $dp, self::AVANCEMENT_DEBUTE, Perimetre::ETABLISSEMENT, TypeIntervenant::CODE_EXTERIEUR);

        $this->row('donnees_perso_saisie', 0, true, 1, 1, 1);
        $this->row('enseignement_saisie', self::CHIMIE, true, 10, 10, 10); // du prévisionnel seulement en chimie

        $this->assertTrue($this->allowed($this->feuille(self::MATHS), 'enseignement_saisie'));
    }



    public function testSaisiePrevisionnelBloqueeSiDonneesPersoNonSaisies(): void
    {
        $dp = $this->def('donnees_perso_saisie', Perimetre::ETABLISSEMENT);
        $s  = $this->def('enseignement_saisie');
        $this->dependance($s, $dp, self::AVANCEMENT_DEBUTE, Perimetre::ETABLISSEMENT, TypeIntervenant::CODE_EXTERIEUR);

        $this->row('donnees_perso_saisie', 0, true, 1, 0, 0);
        $this->row('enseignement_saisie', self::CHIMIE, true, 10, 10, 10);

        $feuille = $this->feuille(self::MATHS);
        $this->assertFalse($this->allowed($feuille, 'enseignement_saisie'));
        $this->assertSame(['Non franchie : donnees_perso_saisie'], $feuille->get('enseignement_saisie')->whyNonAtteignable);
    }



    public function testEtapeSansDependanceApplicableAccessibleDansUneComposanteSansLigne(): void
    {
        // dépendance réservée aux extérieurs : un permanent n'a aucune dépendance applicable => accessible
        $dp = $this->def('donnees_perso_saisie', Perimetre::ETABLISSEMENT);
        $s  = $this->def('enseignement_saisie');
        $this->dependance($s, $dp, self::AVANCEMENT_DEBUTE, Perimetre::ETABLISSEMENT, TypeIntervenant::CODE_EXTERIEUR);

        $this->row('donnees_perso_saisie', 0, true, 1, 0, 0);
        $this->row('enseignement_saisie', self::CHIMIE, true, 10, 10, 10);

        $feuille = $this->feuille(self::MATHS, TypeIntervenant::CODE_PERMANENT);
        $this->assertTrue($this->allowed($feuille, 'enseignement_saisie'));
    }



    public function testEtapeSansAucuneDependanceAccessibleDansUneComposanteSansLigne(): void
    {
        $this->def('referentiel_saisie_realise'); // aucune dépendance
        $this->row('referentiel_saisie_realise', self::CHIMIE, true, 1, 0, 0);

        $this->assertTrue($this->allowed($this->feuille(self::IUT), 'referentiel_saisie_realise'));
    }



    public function testRealiseDansUneComposanteSansPrevisionnelGraceAuContratSansComposante(): void
    {
        // prévisionnel en chimie, une heure de réalisé à l'IUT, contrat unique multi-composantes terminé
        $c = $this->def(WorkflowEtape::CONTRAT, Perimetre::COMPOSANTE);
        $r = $this->def('enseignement_saisie_realise');
        $this->dependance($r, $c, self::AVANCEMENT_PARTIEL, Perimetre::COMPOSANTE, TypeIntervenant::CODE_EXTERIEUR);

        $this->row(WorkflowEtape::CONTRAT, 0, true, 84, 1, 84);
        $this->row('enseignement_saisie_realise', self::CHIMIE, true, 10, 0, 0);

        $this->assertTrue($this->allowed($this->feuille(self::IUT), 'enseignement_saisie_realise'));
    }



    public function testRealiseBloqueSiLeContratEstPropreAUneAutreComposante(): void
    {
        $c = $this->def(WorkflowEtape::CONTRAT, Perimetre::COMPOSANTE);
        $r = $this->def('enseignement_saisie_realise');
        $this->dependance($r, $c, self::AVANCEMENT_PARTIEL, Perimetre::COMPOSANTE, TypeIntervenant::CODE_EXTERIEUR);

        $this->row(WorkflowEtape::CONTRAT, self::CHIMIE, true, 84, 1, 84); // contrat de la chimie seulement
        $this->row('enseignement_saisie_realise', self::CHIMIE, true, 10, 0, 0);

        $feuille = $this->feuille(self::IUT);
        $this->assertFalse($this->allowed($feuille, 'enseignement_saisie_realise'));
        $this->assertSame(['Non franchie : ' . WorkflowEtape::CONTRAT], $feuille->get('enseignement_saisie_realise')->whyNonAtteignable);
    }



    public function testRealiseAccessibleSiLeContratDeLaComposanteEstTermine(): void
    {
        $c = $this->def(WorkflowEtape::CONTRAT, Perimetre::COMPOSANTE);
        $r = $this->def('enseignement_saisie_realise');
        $this->dependance($r, $c, self::AVANCEMENT_PARTIEL, Perimetre::COMPOSANTE, TypeIntervenant::CODE_EXTERIEUR);

        $this->row(WorkflowEtape::CONTRAT, self::CHIMIE, true, 84, 1, 84);
        $this->row(WorkflowEtape::CONTRAT, self::IUT, true, 10, 1, 10);
        $this->row('enseignement_saisie_realise', self::CHIMIE, true, 10, 0, 0);

        $this->assertTrue($this->allowed($this->feuille(self::IUT), 'enseignement_saisie_realise'));
    }



    public function testRealiseBloqueSiLeContratNEstPasTermine(): void
    {
        $c = $this->def(WorkflowEtape::CONTRAT, Perimetre::COMPOSANTE);
        $r = $this->def('enseignement_saisie_realise');
        $this->dependance($r, $c, self::AVANCEMENT_PARTIEL, Perimetre::COMPOSANTE, TypeIntervenant::CODE_EXTERIEUR);

        $this->row(WorkflowEtape::CONTRAT, 0, true, 84, 0, 0); // contrat en projet
        $this->row('enseignement_saisie_realise', self::CHIMIE, true, 10, 0, 0);

        $this->assertFalse($this->allowed($this->feuille(self::IUT), 'enseignement_saisie_realise'));
    }



    public function testValidationBloqueeDansUneComposanteOuRienNEstSaisi(): void
    {
        // la validation dépend de la saisie de la même composante : rien saisi en maths => bloquée
        $s = $this->def('enseignement_saisie');
        $v = $this->def('enseignement_validation');
        $this->dependance($v, $s, self::AVANCEMENT_DEBUTE, Perimetre::COMPOSANTE);

        $this->row('enseignement_saisie', self::CHIMIE, true, 10, 10, 10);
        $this->row('enseignement_validation', self::CHIMIE, true, 10, 10, 10);

        $feuille = $this->feuille(self::MATHS);
        $this->assertFalse($this->allowed($feuille, 'enseignement_validation'));
    }



    public function testConseilRestreintBloqueDansUneComposanteSansPrevisionnel(): void
    {
        $v  = $this->def('enseignement_validation');
        $cr = $this->def('conseil_restreint');
        $this->dependance($cr, $v, self::AVANCEMENT_DEBUTE, Perimetre::COMPOSANTE);

        $this->row('enseignement_validation', self::CHIMIE, true, 10, 10, 10);
        $this->row('conseil_restreint', self::CHIMIE, true, 1, 0, 0);

        $this->assertFalse($this->allowed($this->feuille(self::IUT), 'conseil_restreint'));
    }



    // ------------------------------------------------------------------ comportement de base conservé

    public function testComposanteToutesLesLignesDeLaComposanteDoiventEtreAtteignables(): void
    {
        $this->def('enseignement_saisie');
        $this->row('enseignement_saisie', self::CHIMIE, true, 10, 0, 0);
        $this->row('enseignement_saisie', self::IUT, false, 10, 0, 0);

        $this->assertTrue($this->allowed($this->feuille(self::CHIMIE), 'enseignement_saisie'));
        $this->assertFalse($this->allowed($this->feuille(self::IUT), 'enseignement_saisie'));
    }



    public function testAdminUneLigneAtteignableSuffit(): void
    {
        $this->def('enseignement_saisie');
        $this->row('enseignement_saisie', self::CHIMIE, false, 10, 0, 0);
        $this->row('enseignement_saisie', self::IUT, true, 10, 0, 0);

        $this->assertTrue($this->allowed($this->feuille(null), 'enseignement_saisie'));
    }



    public function testEtapeEtablissementToujoursVisibleDepuisUneComposante(): void
    {
        $this->def('conseil_academique', Perimetre::ETABLISSEMENT);
        $this->row('conseil_academique', 0, true, 1, 1, 1);

        $this->assertTrue($this->allowed($this->feuille(self::IUT), 'conseil_academique'));
    }
}
