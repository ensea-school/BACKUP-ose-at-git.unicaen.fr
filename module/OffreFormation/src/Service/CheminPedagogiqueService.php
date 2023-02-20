<?php

namespace OffreFormation\Service;

use Application\Service\AbstractEntityService;
use Application\Service\Traits\SourceServiceAwareTrait;
use OffreFormation\Entity\Db\CheminPedagogique;

/**
 * Description of CheminPedagogique
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class CheminPedagogiqueService extends AbstractEntityService
{
    use SourceServiceAwareTrait;

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return \OffreFormation\Entity\Db\CheminPedagogique::class;
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
     *
     * @return Etape
     */
    public function newEntity()
    {
        $entity = parent::newEntity();
        // toutes les entités créées ont OSE pour source!!
        $entity->setSource($this->getServiceSource()->getOse());
        $entity->setOrdre(1);

        return $entity;
    }



    /**
     * @param CheminPedagogique $entity
     *
     * @return CheminPedagogique
     */
    public function save($entity)
    {
        if (!$entity->getSource()) {
            $entity->setSource($this->getServiceSource()->getOse());
        }
        if (!$entity->getSourceCode()) {
            $prefix = 'EP' . $entity->getEtape()->getId() . $entity->getElementPedagogique()->getId();
            $entity->setSourceCode(uniqid($prefix));
        }

        return parent::save($entity); // TODO: Change the autogenerated stub
    }
}