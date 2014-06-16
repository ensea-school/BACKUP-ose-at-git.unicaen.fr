<?php

namespace Application\Service;

use Application\Entity\Db\TypeModulateur as TypeModulateurEntity;
use Application\Entity\Db\Structure as StructureEntity;
use Application\Entity\Db\ElementPedagogique as ElementPedagogiqueEntity;
use Doctrine\ORM\QueryBuilder;


/**
 * Description of TypeModulateur
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class TypeModulateur extends AbstractEntityService
{

    /**
     * Liste de tous les types de modulateurs
     *
     * @var TypeModulateurEntity
     */
    protected $all;

    /**
     * Cache du finder par structures
     *
     * @var array
     */
    protected $finderByStructureCache = array();


    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\TypeModulateur';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'typmodu';
    }

    /**
     * Ne récupère que les types de modulateurs associés à une structure donnée
     * 
     * @param StructureEntity $structure
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param type $alias
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function finderByStructure(StructureEntity $structure, QueryBuilder $qb=null, $alias=null)
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);

        if (! array_key_exists($structure->getId(), $this->finderByStructureCache)){
            $sql = "SELECT value(tm) tmid FROM table(OSE_DIVERS.GET_TYPE_MODULATEUR_IDS(:structure_id)) tm";
            $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql, array('structure_id' => $structure->getId()));
            $this->finderByStructureCache[$structure->getId()] = $stmt->fetchAll();
        }

        if (! empty($this->finderByStructureCache[$structure->getId()])){
            $or = $qb->expr()->orX();
            foreach( $this->finderByStructureCache[$structure->getId()] as $row ){
                $or->add($alias.'.id = '.(int)$row['TMID']);
            }
            $qb->andWhere($or);
        }else{
            $qb->andWhere( '1 = 2' ); // Pas de types de modulateurs trouvés
        }
        return $qb;
    }

    /**
     * Ne récupère que les types de modulateurs associés à un élément pédagogique donné
     *
     * @param ElementPedagogiqueEntity $element
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param type $alias
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function finderByElementPedagogique(ElementPedagogiqueEntity $element, QueryBuilder $qb=null, $alias=null)
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);

        /* Filtre par la structure de l'élément pédagogique */
        if ($element->getStructure()){
            $this->finderByStructure($element->getStructure());
        }

        /* Filtre par les paramètres intrinsèques à l'élément pédagogique */
        $codes = $this->getServiceLocator()->get('ProcessModulateur')->getTypeModulateurCodes($element);
        if (! empty($codes)){
            $or = $qb->expr()->orX();
            foreach( $codes as $code ){
                $or->add($alias.'.code = \''.(string)$code."'");
            }
            $qb->andWhere($or);
        }else{
            $qb->andWhere( '1 = 2' ); // Aucun modulateur ne doit être trouvé
        }
        return $qb;
    }

    /**
     * Retourne la liste des types de modulateurs
     *
     * @param QueryBuilder|null $qb
     * @param string|null $alias
     * @return TypeModulateurEntity[]
     */
    public function getList( QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.libelle");
        return parent::getList($qb, $alias);
    }

    /**
     * Retourne la liste de tous les types de modulateurs
     *
     * @return TypeModulateurEntity
     */
    public function getAll()
    {
        if (! $this->all){
            $this->all = $this->getList();
        }
        return $this->all;
    }
}