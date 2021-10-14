<?php

namespace Plafond\Service;

use Application\Service\AbstractEntityService;
use Plafond\Entity\Db\PlafondStructure;

/**
 * Description of PlafondStructureService
 *
 * @author UnicaenCode
 *
 * @method PlafondStructure get($id)
 * @method PlafondStructure[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 *
 */
class PlafondStructureService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass(): string
    {
        return PlafondStructure::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(): string
    {
        return 'plastruct';
    }



    /**
     * Retourne une nouvelle entité de la classe donnée
     *
     * @return PlafondStructure
     */
    public function newEntity()
    {
        /** @var $entity \Plafond\Entity\Db\PlafondStructure */
        $entity = parent::newEntity();
        $entity->setAnnee($this->getServiceContext()->getAnnee());

        return $entity;
    }

}