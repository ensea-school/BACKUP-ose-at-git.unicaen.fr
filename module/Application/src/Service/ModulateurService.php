<?php

namespace Application\Service;

use Application\Entity\Db\Modulateur;
use Application\Entity\Db\ElementPedagogique;
use OffreFormation\Service\Traits\ElementModulateurServiceAwareTrait;
use OffreFormation\Service\Traits\ElementPedagogiqueServiceAwareTrait;
use Doctrine\ORM\QueryBuilder;


/**
 * Description of Modulateur
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ModulateurService extends AbstractEntityService
{
    use ElementPedagogiqueServiceAwareTrait;
    use ElementModulateurServiceAwareTrait;

    /**
     * Liste de tous les modulateurs
     *
     * @var Modulateur
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
        return \Application\Entity\Db\Modulateur::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'modu';
    }



    public function finderByElementPedagogique(ElementPedagogique $element, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $serviceElementModulateur = $this->getServiceElementModulateur();

        $this->join($serviceElementModulateur, $qb, 'elementModulateur');
        $serviceElementModulateur->join($this->getServiceElementPedagogique(), $qb, 'element');
        $qb->andWhere($this->getServiceElementPedagogique()->getAlias() . '.id = ' . $element->getId());

        $serviceElementModulateur->finderByContext($qb);

        return $qb;
    }



    /**
     * Retourne la liste des modulateurs
     *
     * @param QueryBuilder|null $qb
     * @param string|null       $alias
     *
     * @return Modulateur[]
     */
    public function orderBy(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $qb->addOrderBy("$alias.libelle");

        return $qb;
    }



    /**
     * Retourne la liste de tous les modulateurs
     *
     * @return Modulateur
     */
    public function getAll()
    {
        if (!$this->all) {
            $this->all = $this->getList();
        }

        return $this->all;
    }
}