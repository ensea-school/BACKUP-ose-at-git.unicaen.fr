<?php

namespace Application\Service;

use Application\Entity\Db\Structure;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use Doctrine\ORM\Query\Expr\Func;
use Doctrine\ORM\QueryBuilder;
use Paiement\Service\MiseEnPaiementIntervenantStructureServiceAwareTrait;
use Paiement\Service\MiseEnPaiementServiceAwareTrait;


/**
 * Description of StructureService
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 *
 * @method Structure get($id)
 * @method Structure[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method Structure newEntity()
 */
class StructureService extends AbstractEntityService
{
    use Traits\AffectationServiceAwareTrait;
    use IntervenantServiceAwareTrait;
    use MiseEnPaiementServiceAwareTrait;
    use MiseEnPaiementIntervenantStructureServiceAwareTrait;


    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return Structure::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
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



    /**
     * Si un rôle est spécifié, retourne la liste des structures pour lesquelles ce rôle est autorisé à officier.
     * Si <code>true</code> est spécifié, retourne la liste des structures associées à des rôles.
     *
     * @param \Application\Acl\Role|true $role
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
     * @param \Application\Entity\Db\Structure $structure
     * @param \Doctrine\ORM\QueryBuilder       $qb
     * @param string                           $alias
     *
     * @return \Doctrine\ORM\QueryBuilder
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
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param string                     $alias
     *
     * @return \Doctrine\ORM\QueryBuilder
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
     * @param string|null       $alias
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
        $serviceIntervenant    = $this->getServiceIntervenant();

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
     * @param string|null       $alias
     */
    public function orderBy(QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.libelleCourt");

        return $qb;
    }
}