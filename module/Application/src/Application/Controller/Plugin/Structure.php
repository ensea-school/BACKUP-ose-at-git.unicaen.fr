<?php

namespace Application\Controller\Plugin;

use Common\Controller\Plugin\BasePlugin;

/**
 * Description of Structure
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Structure extends BasePlugin
{
    public function __invoke()
    {
        return $this;
    }

    /**
     * Retourne le repository.
     *
     * @return \Application\Entity\Db\Repository\StructureRepository
     */
    public function getRepo()
    {
        return $this->getEntityManager()->getRepository('Application\Entity\Db\Structure');
    }
}