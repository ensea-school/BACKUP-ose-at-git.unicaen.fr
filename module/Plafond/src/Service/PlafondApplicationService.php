<?php

namespace Plafond\Service;

use Application\Service\AbstractEntityService;
use Plafond\Entity\Db\PlafondApplication;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Plafond\Entity\Db\PlafondEtat;

/**
 * Description of PlafondApplicationService
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 *
 * @method PlafondApplication get($id)
 * @method PlafondApplication[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method PlafondApplication newEntity()
 *
 */
class PlafondApplicationService extends AbstractEntityService
{
    use PlafondServiceAwareTrait;
    use AnneeServiceAwareTrait;

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return PlafondApplication::class;
    }



    /**
     * @param PlafondApplication $entity
     *
     * @return PlafondApplication
     */
    public function save($entity)
    {
        if (empty($entity->getEtatPrevu())) {
            $entity->setEtatPrevu($this->getServicePlafond()->getEtat(PlafondEtat::DESACTIVE));
        }
        if (empty($entity->getEtatRealise())) {
            $entity->setEtatRealise($this->getServicePlafond()->getEtat(PlafondEtat::DESACTIVE));
        }

        return parent::save($entity);
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'papp';
    }

}