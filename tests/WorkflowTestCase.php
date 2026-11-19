<?php declare(strict_types=1);

namespace tests;

use Application\Entity\Db\Perimetre;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Entity\Db\Statut;
use Intervenant\Entity\Db\TypeIntervenant;
use Lieu\Entity\Db\Structure;
use Workflow\Entity\Db\WorkflowEtape;
use Workflow\Entity\Db\WorkflowEtapeDependance;
use Workflow\Tbl\Process\Model\IntervenantEtapeStructure;

/**
 * Fabriques d'entités du workflow, construites en mémoire (sans base de données ni Doctrine).
 */
abstract class WorkflowTestCase extends OseTestCase
{
    protected const AVANCEMENT_DEBUTE   = WorkflowEtapeDependance::AVANCEMENT_DEBUTE;
    protected const AVANCEMENT_PARTIEL  = WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT;
    protected const AVANCEMENT_INTEGRAL = WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT;

    private static int $nextId = 1;



    /** Affecte une propriété (même privée/protégée, y compris héritée) */
    protected function setProp(object $object, string $property, mixed $value): void
    {
        $class = new \ReflectionClass($object);
        while (!$class->hasProperty($property)) {
            $class = $class->getParentClass();
            if (!$class) {
                throw new \LogicException('Propriété ' . $property . ' introuvable sur ' . get_class($object));
            }
        }
        $prop = $class->getProperty($property);
        $prop->setAccessible(true);
        $prop->setValue($object, $value);
    }



    protected function perimetre(string $code): Perimetre
    {
        $p = new Perimetre();
        $p->setCode($code);

        return $p;
    }



    protected function typeIntervenant(string $code): TypeIntervenant
    {
        $t = new TypeIntervenant();
        $this->setProp($t, 'id', $code === TypeIntervenant::CODE_EXTERIEUR ? 2 : 1);
        $this->setProp($t, 'code', $code);
        $this->setProp($t, 'libelle', $code);

        return $t;
    }



    protected function etape(string $code, string $perimetre = Perimetre::COMPOSANTE, int $ordre = 1): WorkflowEtape
    {
        $e = new WorkflowEtape();
        $this->setProp($e, 'id', self::$nextId++);
        $this->setProp($e, 'code', $code);
        $this->setProp($e, 'perimetre', $this->perimetre($perimetre));
        $this->setProp($e, 'route', 'route/' . $code);
        $this->setProp($e, 'routeIntervenant', null);
        $this->setProp($e, 'libelleIntervenant', 'Intervenant ' . $code);
        $this->setProp($e, 'libelleAutres', 'Autres ' . $code);
        $this->setProp($e, 'descNonFranchie', 'Non franchie : ' . $code);
        $this->setProp($e, 'descSansObjectif', null);
        $this->setProp($e, 'ordre', $ordre);

        return $e;
    }



    /** Ajoute à $suivante une dépendance vers $precedante */
    protected function dependance(
        WorkflowEtape $suivante,
        WorkflowEtape $precedante,
        int           $avancement,
        string        $perimetre = Perimetre::COMPOSANTE,
        ?string       $typeIntervenant = null,
        bool          $active = true,
    ): WorkflowEtapeDependance
    {
        $d = new WorkflowEtapeDependance();
        $d->setEtapeSuivante($suivante);
        $d->setEtapePrecedante($precedante);
        $d->setAvancement($avancement);
        $d->setPerimetre($this->perimetre($perimetre));
        $d->setTypeIntervenant($typeIntervenant ? $this->typeIntervenant($typeIntervenant) : null);
        $d->setActive($active);
        $suivante->addDependance($d);

        return $d;
    }



    protected function etapeStructure(int $structure, float $objectif, float $partiel, float $realisation, bool $atteignable = true): IntervenantEtapeStructure
    {
        $s              = new IntervenantEtapeStructure();
        $s->structure   = $structure;
        $s->atteignable = $atteignable;
        $s->objectif    = $objectif;
        $s->partiel     = $partiel;
        $s->realisation = $realisation;

        return $s;
    }



    protected function structure(int $id, string $ids): Structure
    {
        $s = new Structure();
        $this->setProp($s, 'id', $id);
        $this->setProp($s, 'ids', $ids);

        return $s;
    }



    protected function intervenant(string $typeCode = TypeIntervenant::CODE_EXTERIEUR, bool $cloture = false): Intervenant
    {
        $statut = new Statut();
        $this->setProp($statut, 'typeIntervenant', $this->typeIntervenant($typeCode));
        $this->setProp($statut, 'cloture', $cloture);

        $i = new Intervenant();
        $this->setProp($i, 'id', 100);
        $this->setProp($i, 'statut', $statut);

        return $i;
    }
}
