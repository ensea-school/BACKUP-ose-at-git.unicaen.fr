<?php

namespace Application\Controller\Plugin;

use Common\Controller\Plugin\BasePlugin;

/**
 * Description of Etablissement
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Etablissement extends BasePlugin
{
    public function __invoke()
    {
        return $this;
    }

    /**
     * Retourne le repository.
     *
     * @return \Application\Entity\Db\Repository\EtablissementRepository
     */
    public function getRepo()
    {
        return $this->getEntityManager()->getRepository('Application\Entity\Db\Etablissement');
    }
}