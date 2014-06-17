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

        $this->finderByAnnee( $this->getContextProvider()->getGlobalContext()->getannee(), $qb, $alias ); // Filtre d'année obligatoire

        return $qb;
    }

    /**
     * Retourne une nouvelle entité, initialisée avec les bons paramètres
     * @return \Application\Entity\Db\ElementModulateur
     */
    public function newEntity()
    {
        $entity = parent::newEntity();
        // Initialisation de l'année en cours
        $entity->setAnnee( $this->getContextProvider()->getGlobalContext()->getAnnee() );
        return $entity;
    }
}