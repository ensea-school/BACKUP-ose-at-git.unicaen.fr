<?php

namespace Application\Service;

use Application\Service\Traits\IntervenantAwareTrait;
use Application\Service\Traits\MiseEnPaiementAwareTrait;
use Application\Service\Traits\MiseEnPaiementIntervenantStructureAwareTrait;
use Application\Service\Traits\ServiceAwareTrait;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Func;
use Application\Entity\Db\Structure as EntityStructure;


/**
 * Description of Structure
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 *
 * @method EntityStructure get($id)
 * @method EntityStructure[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method EntityStructure newEntity()
 */
class Structure extends AbstractEntityService
{
    use Traits\AffectationAwareTrait;
    use ServiceAwareTrait;
    use IntervenantAwareTrait;
    use MiseEnPaiementAwareTrait;
    use MiseEnPaiementIntervenantStructureAwareTrait;



    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return EntityStructure::class;
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
     * @return EntityStructure
     */
    public function getRacine()
    {
        return $this->getRepo()->findOneBySourceCode('UNIV');
    }



    /**
     * Recherche les adresses mails de contact d'une structure.
     *
     * Si une adresse de contact est spécifiée pour cette structure dans la table, on retourne cette adresse.
     * Sinon, on recherche les personnes ayant un rôle spécifique dans la structure, en remontant la hiérarchie
     * des structures mères tant que personne n'est trouvé (et si demandé).
     *
     * @param \Application\Entity\Db\Structure $structure          Structure concernée
     * @param boolean                          $remonterStructures Remonter les structures mères tant que personne n'est
     *                                                             trouvé ?
     *
     * @return string[] mail => nom
     */
    public function getMailsContact(EntityStructure $structure, $remonterStructures = true)
    {
        if ($structure->getContactPj()) {
            return [$structure->getContactPj()];
        }

        $serviceAffectation = $this->getServiceAffectation();

        $str = $structure;

        // recherche des rôles dans la structure, en remontant la hiérarchie des structures si besoin et demandé
        do {
            // recherche de "gestionnaires"
            $qb = $serviceAffectation->finderByRole(\Application\Entity\Db\Role::CODE_GESTIONNAIRE_COMPOSANTE);
            $serviceAffectation->finderByStructure($str, $qb);
            $roles = $serviceAffectation->getList($qb);

            // recherche de "responsables"
            $qb = $serviceAffectation->finderByRole(\Application\Entity\Db\Role::CODE_RESPONSABLE_COMPOSANTE);
            $serviceAffectation->finderByStructure($str, $qb);
            $roles += $serviceAffectation->getList($qb);

            // on grimpe la hiérarchie des structures
            $str = $str->getParente();
        } while ($remonterStructures && !count($roles) && $str);

        // mise en forme du résultat
        $contacts = [];
        foreach ($roles as $role) {
            /* @var $role \Application\Entity\Db\Role */
            $mail            = $role->getPersonnel()->getEmail();
            $name            = $role->getPersonnel()->getNomUsuel() . ' ' . $role->getPersonnel()->getPrenom();
            $contacts[$mail] = $name;
        }

        return $contacts;
    }



    /**
     * Si un rôle est spécifié, retourne la liste des structures pour lesquelles ce rôle est autorisé à officier.
     * Si <code>true</code> est spécifié, retourne la liste des structures associées à des rôles.
     *
     * @param \Application\Acl\Role|true $role
     */
    public function finderByRole($role, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

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
    public function finderByStructure(EntityStructure $structure, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

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
        list($qb, $alias) = $this->initQuery($qb, $alias);

        if ($cStructure = $this->getServiceContext()->getStructure()) {
            $this->finderById($cStructure->getId(), $qb, $alias);
        }

        return $qb;
    }



    /**
     * Recherche par nom
     *
     * @param string            $term
     * @param QueryBuilder|null $qb
     *
     * @return QueryBuilder
     */
    public function finderByNom($term, QueryBuilder $qb = null, $alias = null)
    {
        $term = str_replace(' ', '', $term);

        list($qb, $alias) = $this->initQuery($qb, $alias);

        $libelleLong  = new Func('CONVERT', ["$alias.libelleLong", '?3']);
        $libelleCourt = new Func('CONVERT', ["$alias.libelleCourt", '?3']);

        $qb
            ->where("$alias.sourceCode = ?1")
            ->orWhere($qb->expr()->like($qb->expr()->upper($libelleLong), $qb->expr()->upper('CONVERT(?2, ?3)')))
            ->orWhere($qb->expr()->like($qb->expr()->upper($libelleCourt), $qb->expr()->upper('CONVERT(?2, ?3)')))
            ->orderBy("$alias.libelleCourt");

        $qb->setParameters([1 => $term, 2 => "%$term%", 3 => 'US7ASCII']);

        //print_r($qb->getQuery()->getSQL()); var_dump($qb->getQuery()->getParameters());die;

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
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $qb->andWhere('EXISTS (SELECT ep FROM Application\Entity\Db\ElementPedagogique ep WHERE ep.structure = ' . $alias . ')');
        $this->finderByNiveau(2, $qb, $alias);

        return $qb;
    }



    public function finderByMiseEnPaiement(QueryBuilder $qb = null, $alias = null)
    {
        $serviceMIS = $this->getServiceMiseEnPaiementIntervenantStructure();

        $serviceMiseEnPaiement = $this->getServiceMiseEnPaiement();
        $serviceIntervenant    = $this->getServiceIntervenant();

        list($qb, $alias) = $this->initQuery($qb, $alias);

        $this->join($serviceMIS, $qb, 'miseEnPaiementIntervenantStructure', false, $alias);
        $serviceMIS->join($serviceMiseEnPaiement, $qb, 'miseEnPaiement');
        $serviceMIS->join($serviceIntervenant, $qb, 'intervenant', false);

        $serviceIntervenant->finderByAnnee($this->getServiceContext()->getAnnee(), $qb);

        return $qb;
    }



    /**
     * Fetch des structures d'enseignement distinctes d'un intervenant.
     *
     * @param \Application\Service\IntervenantEntity $intervenant
     */
    public function getListStructuresEnseignIntervenant(IntervenantEntity $intervenant)
    {
        $serviceService = $this->getServiceService();

        $qb = $this->finderByEnseignement();
        $this->join($serviceService, $qb, 'service');
        $serviceService->finderByIntervenant($intervenant, $qb);

        return $this->getList($qb);
    }



    /**
     *
     * @param QueryBuilder|null $qb
     * @param string|null       $alias
     */
    public function orderBy(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.libelleCourt");

        return $qb;
    }
}