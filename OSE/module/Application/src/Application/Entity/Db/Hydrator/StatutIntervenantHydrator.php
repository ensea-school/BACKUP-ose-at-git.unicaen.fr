<?php

namespace Application\Entity\Db\Hydrator;

/**
 * Description of Intervenant
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class StatutIntervenantHydrator implements \Zend\Stdlib\Hydrator\HydratorInterface
{
    /**
     * 
     * @param array $statuts
     */
    public function __construct(array $statuts)
    {
        $this->setStatuts($statuts);
    }
    
    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  \Application\Entity\Db\StatutIntervenant $statut
     * @return \Application\Entity\Db\StatutIntervenant
     */
    public function hydrate(array $data, $statut)
    {
        if (!$data['id']) {
            return null;
        }
        $statut = $this->getStatuts()[$data['id']];
        
        return $statut;
    }
    
    /**
     * Extract values from an object
     *
     * @param  \Application\Entity\Db\StatutIntervenant $statut
     * @return array
     */
    public function extract($statut)
    {
        return array('id' => $statut->getId());
    }
    
    private $statuts;
    
    public function getStatuts()
    {
        return $this->statuts;
    }

    public function setStatuts($statuts)
    {
        $this->statuts = $statuts;
        return $this;
    }
}