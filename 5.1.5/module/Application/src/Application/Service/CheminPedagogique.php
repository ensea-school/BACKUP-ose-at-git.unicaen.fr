<?php

namespace Application\Service;
use Application\Service\Traits\SourceAwareTrait;

/**
 * Description of CheminPedagogique
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class CheminPedagogique extends AbstractEntityService
{
    use SourceAwareTrait;

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return \Application\Entity\Db\CheminPedagogique::class;
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias() 
    {
        return 'cp';
    }

    /**
     * Retourne une nouvelle entité, initialisée avec les bons paramètres
     * @return EtapeEntity
     */
    public function newEntity()
    {
        $entity = parent::newEntity();
        // toutes les entités créées ont OSE pour source!!
        $entity->setSource( $this->getServiceSource()->getOse() );
        $entity->setOrdre(1);
        return $entity;
    }
}