<?php

namespace Application\Service;

use Application\Entity\Db\CentreCoutEp as CentreCoutEpEntity;
use Application\Service\Traits\SourceAwareTrait;

/**
 * Description of CentreCoutEp
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class CentreCoutEp extends AbstractEntityService
{
    use SourceAwareTrait;

    /**
     * retourne la classe des entités
     *
     * @return string
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\CentreCoutEp';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'ccep';
    }

    /**
     * Sauvegarde un centre de coûts
     *
     * @param CentreCoutEpEntity $entity
     * @throws \Common\Exception\RuntimeException
     * @return CentreCoutEpEntity
     */
    public function save($entity)
    {
        if (! $entity->getSource()){
            $entity->setSource( $this->getServiceSource()->getOse() );
        }
        if ( ! $entity->getSourceCode()
            && ($cc = $entity->getCentreCout())
            && ($th = $entity->getTypeHeures())
            && ($ep = $entity->getElementPedagogique())
        ){
            $entity->setSourceCode( uniqid($cc->getId().'_'.$th->getId().'_'.$ep->getId()) );
        }
        return parent::save($entity);
    }
}