<?php

namespace Application\Service;

use Application\Entity\Db\CentreCoutEp;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\SourceServiceAwareTrait;
use BjyAuthorize\Exception\UnAuthorizedException;

/**
 * Description of CentreCoutEpService
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class CentreCoutEpService extends AbstractEntityService
{
    use SourceServiceAwareTrait;

    /**
     * retourne la classe des entités
     *
     * @return string
     */
    public function getEntityClass()
    {
        return CentreCoutEp::class;
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
     * Retourne une nouvelle entité de la classe donnée
     *
     * @return mixed
     */
    public function newEntity()
    {
        $entity = parent::newEntity();
        $entity->setSource( $this->getServiceSource()->getOse() );
        return $entity;
    }



    /**
     * Sauvegarde un centre de coûts
     *
     * @param CentreCoutEp $entity
     * @throws \RuntimeException
     * @return CentreCoutEp
     */
    public function save($entity)
    {
        if (! $this->getAuthorize()->isAllowed($entity,Privileges::ODF_CENTRES_COUT_EDITION)){
            throw new UnAuthorizedException('Vous n\'avez pas les droits requis pour associer/dissocier un centre de coûts de cet enseignement');
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