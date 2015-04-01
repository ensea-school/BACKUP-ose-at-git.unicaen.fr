<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;

/**
 * Description of ElementModulateur
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ElementModulateur extends AbstractEntityService
{
    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\ElementModulateur';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'epmod';
    }

    /**
     * Filtre la liste des services selon lecontexte courant
     *
     * @param QueryBuilder|null $qb
     * @param string|null $alias
     * @return QueryBuilder
     */
    public function finderByContext( QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);

        $this->join( $this->getServiceElementPedagogique(), $qb, 'elementPedagogique', false, $alias );

        $this->getServiceElementPedagogique()->finderByAnnee( $this->getContextProvider()->getGlobalContext()->getannee(), $qb ); // Filtre d'année obligatoire

        return $qb;
    }

    /**
     * @return ElementPedagogique
     */
    protected function getServiceElementPedagogique()
    {
        return $this->getServiceLocator()->get('applicationElementPedagogique');
    }
}