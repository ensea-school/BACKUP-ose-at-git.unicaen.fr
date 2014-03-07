<?php

namespace Application\Controller\Plugin;

use Common\Controller\Plugin\BasePlugin;

/**
 * Description of ServiceReferentiel
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ServiceReferentiel extends BasePlugin
{
    public function __invoke()
    {
        return $this;
    }
    
    /**
     * Retourne le repository.
     * 
     * @return \Application\Entity\Db\Repository\IntervenantRepository
     */
    public function getRepo()
    {
        return $this->getEntityManager()->getRepository('Application\Entity\Db\ServiceReferentiel');
    }
}