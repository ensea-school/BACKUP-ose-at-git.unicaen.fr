<?php

namespace Application\Service;

use Application\Entity\Db\Repository\ElementPedagogiqueRepository;

/**
 * Service métier dédié à l'offre de formation.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class OffreFormation extends AbstractService
{
    /**
     * @var ElementPedagogiqueRepository
     */
    protected $repoElementPedagogique;
    
    
    
    
    /**
     * 
     * @return ElementPedagogiqueRepository
     */
    public function getRepoElementPedagogique()
    {
        if (null === $this->repoElementPedagogique) {
            $this->repoElementPedagogique = $this->getEntityManager()->getRepository('Application\Entity\Db\ElementPedagogique');
        }
        return $this->repoElementPedagogique;
    }


}