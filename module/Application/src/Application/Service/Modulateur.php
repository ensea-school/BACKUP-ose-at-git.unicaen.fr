<?php

namespace Application\Service;

use Application\Entity\Db\Modulateur as ModulateurEntity;
use Application\Entity\Db\ElementPedagogique as ElementPedagogiqueEntity;
use Doctrine\ORM\QueryBuilder;


/**
 * Description of Modulateur
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Modulateur extends AbstractEntityService
{

    /**
     * Liste de tous les modulateurs
     *
     * @var ModulateurEntity
     */
    protected $all;



    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\Modulateur';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'modu';
    }


    public function finderByElementPedagogique(ElementPedagogiqueEntity $element, QueryBuilder $qb=null, $alias=null)
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);

        $serviceElementPedagogique = $this->getServiceLocator()->get('ApplicationElementPedagogique');
        $serviceElementModulateur = $this->getServiceLocator()->get('ApplicationElementModulateur');
//        $serviceTypeModulateur = $this->getServiceLocator()->get('ApplicationTypeModulateur');

        $this->join( $serviceElementModulateur, $qb, 'id', 'modulateur' );
//        $this->join( $serviceTypeModulateur, $qb, 'typeModulateur' ); // pour éviter des sous-reqûetes intempestives par la suite !!
        $serviceElementModulateur->join( $serviceElementPedagogique, $qb, 'element' );

        return $qb;
    }

    /**
     * Retourne la liste des modulateurs
     *
     * @param QueryBuilder|null $qb
     * @param string|null $alias
     * @return ModulateurEntity[]
     */
    public function getList( QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.libelle");
        return parent::getList($qb, $alias);
    }

    /**
     * Retourne la liste de tous les modulateurs
     *
     * @return ModulateurEntity
     */
    public function getAll()
    {
        if (! $this->all){
            $this->all = $this->getList();
        }
        return $this->all;
    }
}