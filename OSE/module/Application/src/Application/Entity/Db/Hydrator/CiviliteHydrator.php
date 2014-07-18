<?php

namespace Application\Entity\Db\Hydrator;

/**
 * Description of CiviliteHydrator
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class CiviliteHydrator implements \Zend\Stdlib\Hydrator\HydratorInterface
{
    /**
     * 
     * @param array $civilites
     */
    public function __construct(array $civilites)
    {
        $this->setCivilites($civilites);
    }
    
    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  \Application\Entity\Db\Civilite $civilite
     * @return \Application\Entity\Db\Civilite
     */
    public function hydrate(array $data, $civilite)
    {
        $civilite = $this->getCivilites()[$data['id']];
        
        return $civilite;
    }
    
    /**
     * Extract values from an object
     *
     * @param  \Application\Entity\Db\Civilite $civilite
     * @return array
     */
    public function extract($civilite)
    {
        return array('id' => $civilite->getId());
    }
    
    private $civilites;
    
    public function getCivilites()
    {
        return $this->civilites;
    }

    public function setCivilites($civilites)
    {
        $this->civilites = $civilites;
        return $this;
    }
}