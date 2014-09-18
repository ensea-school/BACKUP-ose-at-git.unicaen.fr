<?php

namespace Application\Service;

use Application\Entity\NiveauEtape as NiveauEtapeEntity;

/**
 * Description of NiveauEtape
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class NiveauEtape extends AbstractService
{
    /**
     *
     * @param string $id
     * @return \Application\Entity\NiveauEtape
     */
    public function get($id)
    {
        list( $groupeTypeFormationLibelleCourt, $niv) = explode( '-', $id );
        
        $niveau = new NiveauEtapeEntity();
        $niveau->setLib($groupeTypeFormationLibelleCourt);
        $niveau->setNiv($niv);
        return $niveau;
    }

    /**
     * @return GroupeTypeFormation
     */
    protected function getServiceGroupeTypeformation()
    {
        return $this->getServiceLocator()->get('applicationGroupeTypeFormation');
    }

    /**
     * @return serviceClass
     */
    protected function getfunctionName()
    {
        return $this->getServiceLocator()->get('serviceName');
    }
}