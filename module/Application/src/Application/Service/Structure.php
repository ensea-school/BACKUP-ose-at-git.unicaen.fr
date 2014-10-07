<?php

namespace Application\Service;

use Application\Service\AbstractService;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Func;
use Application\Entity\Db\Structure as EntityStructure;


/**
 * Description of Structure
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Structure extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\Structure';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'str';
    }

    /**
     * Retourne le contexte global des services
     *
     * @todo implémenter la notion de structure courante
     */
    public function getGlobalContext()
    {
//        $currentUser = $this->getServiceLocator()->get('authUserContext')->getDbUser();
        return array(
//            'structure'     => null,
        );
    }

    /**
     * Recherche les adresses mails de contact d'une structure.
     * 
     * Si une adresse de contact est spécifiée pour cette structure dans la table, on retourne cette adresse.
     * Sinon, on recherche les personnes ayant un rôle spécifique dans la structure, en remontant la hiérarchie 
     * des structures mères tant que personne n'est trouvé (et si demandé).
     * 
     * @param \Application\Entity\Db\Structure $structure Structure concernée
     * @param boolean $remonterStructures Remonter les structures mères tant que personne n'est trouvé ?
     * @return string[] mail => nom
     */
    public function getMailsContact(EntityStructure $structure, $remonterStructures = true)
    {
        if ($structure->getContactPj()) {
            return [ $structure->getContactPj() ];
        }
        
        $serviceRole = $this->getServiceLocator()->get('applicationRole');
        
        $str   = $structure;
        
        // recherche des rôles dans la structure, en remontant la hiérarchie des structures si besoin et demandé
        do {
            // recherche de "gestionnaires"
            $qb = $serviceRole->finderByTypeRole(\Application\Entity\Db\TypeRole::CODE_GESTIONNAIRE_COMPOSANTE);
            $serviceRole->finderByStructure($str, $qb);
            $roles = $serviceRole->getList($qb);
            
            // recherche de "responsables"
            $qb = $serviceRole->finderByTypeRole(\Application\Entity\Db\TypeRole::CODE_RESPONSABLE_COMPOSANTE);
            $serviceRole->finderByStructure($str, $qb);
            $roles += $serviceRole->getList($qb);
            
            // on grimpe la hiérarchie des structures
            $str = $str->getParente();
        }
        while ($remonterStructures && !count($roles) && $str);
        
        // mise en forme du résultat
        $contacts = [];
        foreach ($roles as $role) { /* @var $role \Application\Entity\Db\Role */
            $mail = $role->getPersonnel()->getEmail();
            $name = $role->getPersonnel()->getNomUsuel() . ' ' . $role->getPersonnel()->getPrenom();
            $contacts[$mail] = $name;
        }
        
        return $contacts;
    }
    
    /**
     * Retourne la liste des structures selon le contexte donné
     *
     * @param array $context
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByContext( array $context, QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);

        if (! empty($context['structure']) && $context['structure'] instanceof EntityStructure){
            $qb->andWhere("$alias.parente = :structure")->setParameter('structure', $context['structure']);
        }
        return $qb;
    }

    /**
     * Retourne la liste des structures pour lesquelles le rôle est autorisé à officier
     *
     * @param \Application\Acl\Role $role
     */
    public function finderByRole( $role, QueryBuilder $qb=null, $alias=null)
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);

        if (! $role instanceof \Application\Acl\Role) return $qb;
        $this->finderByStructure( $role->getStructure(), $qb, $alias );
        
        return $qb;
    }

    /**
     * Filtre par la structure et ses filles
     *
     *
     * @param \Application\Entity\Db\Structure $structure
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param string $alias
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function finderByStructure( EntityStructure $structure, QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);

        //$f = new Func('OSE_DIVERS.STRUCTURE_DANS_STRUCTURE', array("$alias.id", ":structure_cible"));

        $qb->andWhere( $alias.'.structureNiv2 = :structure_cible')->setParameter('structure_cible', $structure->getParenteNiv2()->getId());

        return $qb;
    }

    /**
     * Recherche par nom
     *
     * @param string $term
     * @param QueryBuilder|null $qb
     * @return QueryBuilder
     */
    public function finderByNom($term, QueryBuilder $qb=null, $alias=null)
    {
        $term = str_replace(' ', '', $term);

        list($qb,$alias) = $this->initQuery($qb, $alias);

        $libelleLong = new Func('CONVERT', array("$alias.libelleLong", '?3') );
        $libelleCourt = new Func('CONVERT', array("$alias.libelleCourt", '?3') );

        $qb
                ->where("$alias.sourceCode = ?1")
                ->orWhere($qb->expr()->like($qb->expr()->upper($libelleLong), $qb->expr()->upper('CONVERT(?2, ?3)')))
                ->orWhere($qb->expr()->like($qb->expr()->upper($libelleCourt), $qb->expr()->upper('CONVERT(?2, ?3)')))
                ->orderBy("$alias.libelleCourt");

        $qb->setParameters(array(1 => $term, 2 => "%$term%", 3 => 'US7ASCII'));

        //print_r($qb->getQuery()->getSQL()); var_dump($qb->getQuery()->getParameters());die;

        return $qb;
    }

    /**
     * Ne recherche que les structures où il y a des enseignements
     *
     * @todo à corriger pour palier au cas où une structure destinée à assurer des enseignements n'ai encore aucun enseignement
     * @todo prendre en compte l'année courante (tester utilisation d'un filtre Doctrine)
     *
     * @param QueryBuilder|null $qb
     * @param string|null $alias
     * @return QueryBuilder
     */
    public function finderByEnseignement(QueryBuilder $qb=null, $alias=null)
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb->andWhere( 'EXISTS (SELECT ep FROM Application\Entity\Db\ElementPedagogique ep WHERE ep.structure = '.$alias.')');
        return $qb;
    }

    /**
     * Retourne la liste des structures
     *
     * @param QueryBuilder|null $queryBuilder
     * @return Application\Entity\Db\TypeFormation[]
     */
    public function getList( QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.libelleCourt");
        return parent::getList($qb, $alias);
    }
}