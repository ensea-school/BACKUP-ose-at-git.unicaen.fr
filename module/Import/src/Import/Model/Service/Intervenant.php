<?php

namespace Import\Model\Service;

class Intervenant extends Service {

    /**
     *
     * @var type 
     */
    protected $mapper;

    /**
     * Retourne le mapper LDAP à utiliser.
     *
     * @return \UnicaenApp\Mapper\Ldap\Group
     */
    protected function getMapper()
    {
        if (null === $this->mapper) {
            $this->mapper = new \Import\Model\Mapper\Intervenant\Harpege();
            $this->mapper->setServiceManager( $this->getServiceManager() );
        }
        return $this->mapper;
    }

    /**
     * recherche un ensemble d'enseignants
     *
     * @param string $term
     * @return array[]
     */
    public function search( $term, $limit=100 )
    {
        return $this->getMapper()->search($term, $limit);
    }

}