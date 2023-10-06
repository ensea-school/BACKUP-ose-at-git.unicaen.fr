<?php

namespace Lieu\Service;

use Application\Acl\Role;
use Application\Provider\Privilege\Privileges;
use Application\Service\AbstractEntityService;
use Application\Service\Traits\AffectationServiceAwareTrait;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use Doctrine\ORM\QueryBuilder;
use Lieu\Entity\Db\Structure;
use Paiement\Service\MiseEnPaiementIntervenantStructureServiceAwareTrait;
use Paiement\Service\MiseEnPaiementServiceAwareTrait;


/**
 * Description of StructureService
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 *
 * @method Structure get($id)
 * @method Structure[] getList(QueryBuilder $qb = null, $alias = null)
 * @method Structure newEntity()
 */
class StructureService extends AbstractEntityService
{
    use AffectationServiceAwareTrait;
    use IntervenantServiceAwareTrait;
    use MiseEnPaiementServiceAwareTrait;
    use MiseEnPaiementIntervenantStructureServiceAwareTrait;

    /** @var Structure[] */
    private array $treeStructures;



    public function getEntityClass()
    {
        return Structure::class;
    }



    public function getAlias()
    {
        return 'str';
    }



    /**
     * Retourne la structure racine (i.e. qui n'a pas de structure parente).
     *
     * @return Structure
     */
    public function getRacine()
    {
        return $this->getRepo()->findOneBySourceCode('UNIV');
    }



    public function getTreeArray(): array
    {
        $dql = "
        SELECT 
            s, src, sp
        FROM 
            " . Structure::class . " s 
            JOIN s.source src
            LEFT JOIN s.structure sp WITH sp.histoDestruction IS NULL
        WHERE s.histoDestruction IS NULL 
        ORDER BY s.libelleCourt
        ";

        $this->treeStructures = [];
        $query = $this->getEntityManager()->createQuery($dql);

        /** @var Structure[] $r */
        $r = $query->getResult();
        $this->treeStructures = [];
        foreach ($r as $str) {
            $this->treeStructures[$str->getId()] = $str;
        }
        unset($r);

        $ta = [];
        foreach ($this->treeStructures as $structure) {
            if (null === $structure->getStructure()) {
                $ta[$structure->getId()] = $this->getStructureArray($structure);
            }
        }

        return $ta;
    }



    protected function getStructureArray(Structure $structure): array
    {
        $canEdit = $this->getAuthorize()->isAllowed($structure, Privileges::STRUCTURES_ADMINISTRATION_EDITION);
        $canDelete = $canEdit && !$structure->getSource()->getImportable() && $structure->getStructures()->count() == 0;

        $a = [
            'id'                => $structure->getId(),
            'code'              => $structure->getCode(),
            'source'            => ['libelle' => $structure->getSource()->getLibelle()],
            'libelleCourt'      => $structure->getLibelleCourt(),
            'libelleLong'       => $structure->getLibelleLong(),
            'enseignement'      => $structure->isEnseignement(),
            'affAdresseContrat' => $structure->isAffAdresseContrat(),
            'adresse'           => $structure->getAdresse(false),
        'structures'   => [],
            'canEdit'      => $canEdit,
            'canDelete'    => $canDelete,
        ];

        foreach ($structure->getStructures() as $subStr) {
            $a['structures'][$subStr->getId()] = $this->getStructureArray($subStr);
        }

        return $a;
    }



    /**
     * Si un rôle est spécifié, retourne la liste des structures pour lesquelles ce rôle est autorisé à officier.
     * Si <code>true</code> est spécifié, retourne la liste des structures associées à des rôles.
     *
     * @param Role|true $role
     */
    public function finderByRole($role, QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);

        if (true === $role) {
            $qb->andWhere("EXISTS ( SELECT a from Application\Entity\Db\Affectation a WHERE a.structure = $alias)");
        } elseif ($role->getStructure()) {
            $this->finderByStructure($role->getStructure(), $qb, $alias);
        }

        return $qb;
    }



    /**
     * Filtre par la structure et ses filles
     *
     *
     * @param Structure $structure
     * @param QueryBuilder $qb
     * @param string $alias
     *
     * @return QueryBuilder
     */
    public function finderByStructure(Structure $structure, QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);

        //$f = new Func('OSE_DIVERS.STRUCTURE_DANS_STRUCTURE', array("$alias.id", ":structure_cible"));

        $qb->andWhere($alias . ' = :structure_cible')->setParameter('structure_cible', $structure);

        return $qb;
    }



    /**
     *
     * @param QueryBuilder $qb
     * @param string $alias
     *
     * @return QueryBuilder
     */
    public function finderByContext(QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);

        if ($cStructure = $this->getServiceContext()->getStructure()) {
            $this->finderById($cStructure->getId(), $qb, $alias);
        }

        return $qb;
    }



    /**
     * Ne recherche que les structures où il y a des enseignements
     *
     * @todo à corriger pour palier au cas où une structure destinée à assurer des enseignements n'ai encore aucun
     *       enseignement
     * @todo prendre en compte l'année courante (tester utilisation d'un filtre Doctrine)
     *
     * @param QueryBuilder|null $qb
     * @param string|null $alias
     *
     * @return QueryBuilder
     */
    public function finderByEnseignement(QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $qb->andWhere('(' . $alias . '.enseignement = 1 OR EXISTS (SELECT ep FROM OffreFormation\Entity\Db\ElementPedagogique ep WHERE ep.structure = ' . $alias . '))');

        return $qb;
    }



    public function finderByMiseEnPaiement(QueryBuilder $qb = null, $alias = null)
    {
        $serviceMIS = $this->getServiceMiseEnPaiementIntervenantStructure();

        $serviceMiseEnPaiement = $this->getServiceMiseEnPaiement();
        $serviceIntervenant = $this->getServiceIntervenant();

        [$qb, $alias] = $this->initQuery($qb, $alias);

        $this->join($serviceMIS, $qb, 'miseEnPaiementIntervenantStructure', false, $alias);
        $serviceMIS->join($serviceMiseEnPaiement, $qb, 'miseEnPaiement');
        $serviceMIS->join($serviceIntervenant, $qb, 'intervenant', false);

        $serviceIntervenant->finderByAnnee($this->getServiceContext()->getAnnee(), $qb);

        return $qb;
    }



    /**
     *
     * @param QueryBuilder|null $qb
     * @param string|null $alias
     */
    public function orderBy(QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.libelleCourt");

        return $qb;
    }
}