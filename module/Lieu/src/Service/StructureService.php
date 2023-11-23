<?php

namespace Lieu\Service;

use Application\Acl\Role;
use Application\Provider\Privilege\Privileges;
use Application\Service\AbstractEntityService;
use Application\Service\Traits\AffectationServiceAwareTrait;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use Application\Util;
use Doctrine\ORM\QueryBuilder;
use Intervenant\Entity\Db\TypeIntervenant;
use Lieu\Entity\Db\Structure;
use Paiement\Service\MiseEnPaiementIntervenantStructureServiceAwareTrait;
use Paiement\Service\MiseEnPaiementServiceAwareTrait;
use UnicaenVue\View\Model\AxiosModel;


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


    public function getEntityClass ()
    {
        return Structure::class;
    }



    public function getAlias ()
    {
        return 'str';
    }



    /**
     * Retourne la structure racine (i.e. qui n'a pas de structure parente).
     *
     * @return Structure
     */
    public function getRacine ()
    {
        return $this->getRepo()->findOneBySourceCode('UNIV');
    }



    public function getTreeArray (): array
    {
        $tree = $this->getTree();

        $ta = [];
        foreach ($tree as $structure) {
            $ta[$structure->getId()] = $this->getStructureArray($structure);
        }

        return $ta;
    }



    /**
     * @return array|Structure[]
     */
    public function getTree (?Structure $root = null, bool $onlyEnseignement = false, bool $contextFilter = true): array
    {
        if ($contextFilter) {
            $cStructure = $this->getServiceContext()->getStructure();
        } else {
            $cStructure = null;
        }

        if ($root && $cStructure) {
            if ($cStructure->inStructure($root)) {
                // le ROOT est une sous-structure des structures autorisées
                $root = $cStructure;
            }
            if (!$root->inStructure($cStructure)) {
                // le root n'est pas une sous-structure autorisée => liste vide
                return [];
            }
        } elseif (!$root && $cStructure) {
            // pas de root définie => on se base sur la structure du contexte
            $root = $cStructure;
        }

        if ($root) {
            $id = $root->idsFilter();

            $pFilter   = "AND p.ids LIKE '$id'";
            $strFilter = "AND str.ids LIKE '$id'";
            $subFilter = "AND sub.ids LIKE '$id'";
        } else {
            $pFilter   = "";
            $strFilter = "";
            $subFilter = "";
        }

        if ($onlyEnseignement) {
            $pFilter   .= ' AND p.enseignement = true';
            $strFilter .= ' AND str.enseignement = true';
            $subFilter .= ' AND sub.enseignement = true';
        }

        $dql = "
        SELECT 
            str, p, sub
        FROM
            " . Structure::class . " str
            LEFT JOIN str.structure p WITH p.histoDestruction IS NULL $pFilter
            LEFT JOIN str.structures sub WITH sub.histoDestruction IS NULL $subFilter
        WHERE
            str.histoDestruction IS NULL
            $strFilter 
        ORDER BY
            str.libelleCourt
        ";

        /** @var Structure[] $strs */
        $strs   = $this->getEntityManager()->createQuery($dql)->getResult();
        $result = [];
        foreach ($strs as $str) {
            $found = false;
            foreach ($strs as $sstr) {
                foreach ($sstr->getStructures() as $ssstr) {
                    if ($str == $ssstr) {
                        $found = true; // trouvé comme sous-structure
                    }
                }
            }
            if (!$found) {
                $result[$str->getId()] = $str;
            }
        }

        return $result;
    }



    protected function getStructureArray (Structure $structure): array
    {
        $canEdit   = $this->getAuthorize()->isAllowed($structure, Privileges::STRUCTURES_ADMINISTRATION_EDITION);
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
            'structures'        => [],
            'canEdit'           => $canEdit,
            'canDelete'         => $canDelete,
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
    public function finderByRole ($role, QueryBuilder $qb = null, $alias = null)
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
     * @param Structure    $structure
     * @param QueryBuilder $qb
     * @param string       $alias
     *
     * @return QueryBuilder
     */
    public function finderByStructure (?Structure $structure, ?QueryBuilder $qb = null, $alias = null): QueryBuilder
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);

        //$f = new Func('OSE_DIVERS.STRUCTURE_DANS_STRUCTURE', array("$alias.id", ":structure_cible"));

        $qb->andWhere($alias . '.ids LIKE :structure_cible')->setParameter('structure_cible', $structure->idsFilter());

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
     * @param string|null       $alias
     *
     * @return QueryBuilder
     */
    public function finderByEnseignement (QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $qb->andWhere('(' . $alias . '.enseignement = 1 OR EXISTS (SELECT ep FROM OffreFormation\Entity\Db\ElementPedagogique ep WHERE ep.structure = ' . $alias . '))');

        return $qb;
    }



    public function finderByMiseEnPaiement (QueryBuilder $qb = null, $alias = null)
    {
        $serviceMIS = $this->getServiceMiseEnPaiementIntervenantStructure();

        $serviceMiseEnPaiement = $this->getServiceMiseEnPaiement();
        $serviceIntervenant    = $this->getServiceIntervenant();

        [$qb, $alias] = $this->initQuery($qb, $alias);

        $this->join($serviceMIS, $qb, 'miseEnPaiementIntervenantStructure', false, $alias);
        $serviceMIS->join($serviceMiseEnPaiement, $qb, 'miseEnPaiement');
        $serviceMIS->join($serviceIntervenant, $qb, 'intervenant', false);

        $serviceIntervenant->finderByAnnee($this->getServiceContext()->getAnnee(), $qb);

        return $qb;
    }



    /**
     * Méthode qui renvoient la liste des structures pour lesquelles il reste des demandes de mise en paiement
     * d'heures à faire
     *
     * @return array
     */

    public function getStructuresDemandeMiseEnPaiement (?TypeIntervenant $typeIntervenant = null): AxiosModel
    {
        $structures = [];
        $sql        = "
            SELECT
                str.id    			        structure_id,
                MAX(str.code)  			    structure_code,
                MAX(str.libelle_court)  	structure_libelle
            from tbl_paiement tp 
            JOIN structure str ON str.id = tp.structure_id 
            JOIN intervenant i ON i.id = tp.intervenant_id 
            JOIN statut s ON s.id = i.statut_id 
            JOIN type_intervenant ti ON ti.id = s.type_intervenant_id 
            WHERE (tp.heures_a_payer_aa > tp.heures_demandees_aa  OR tp.heures_a_payer_ac > tp.heures_a_payer_ac) 
            AND tp.annee_id = :annee ";
        //On ajoute le filtre par type d'intervenant
        if ($typeIntervenant instanceof TypeIntervenant) {
            $sql .= "AND ti.code = :typeIntervenant";
        }
        $sql .= "
            GROUP BY str.id
            ORDER BY MAX(str.libelle_court) ASC
        ";


        $params = ['annee' => $this->getServiceContext()->getAnnee()->getId()];
        $stmt   = $this->getEntityManager()->getConnection()->executeQuery($sql, $params);

        while ($r = $stmt->fetchAssociative()) {
            $structures[] =
                [
                    'id'      => $r['STRUCTURE_ID'],
                    'libelle' => $r['STRUCTURE_LIBELLE'],
                    'code'    => $r['STRUCTURE_CODE'],
                ];
        }

        $data = new AxiosModel($structures);

        return $data;
    }



    /**
     * @param Structure $entity
     *
     * @return mixed
     * @throws \Doctrine\DBAL\Exception
     */
    public function save ($entity)
    {
        parent::save($entity); // TODO: Change the autogenerated stub

        $cStructure = $this->getServiceContext()->getStructure();
        if ($cStructure) {
            if (!$entity->getStructure() || !$entity->getStructure()->inStructure($cStructure)) {
                throw new \Exception('La nouvelle structure doit hériter de ' . $cStructure);
            }
        }

        $this->getEntityManager()->getConnection()->executeStatement('BEGIN OSE_DIVERS.UPDATE_STRUCTURES(); END;');

        return $entity;
    }



    /**
     *
     * @param QueryBuilder|null $qb
     * @param string|null       $alias
     */
    public function orderBy (QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.libellesCourts");

        return $qb;
    }
}