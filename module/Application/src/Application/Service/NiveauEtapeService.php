<?php

namespace Application\Service;

use Application\Entity\NiveauEtape;

/**
 * Description of NiveauEtape
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class NiveauEtapeService extends AbstractService
{
    /**
     *
     * @param string $id
     * @return \Application\Entity\NiveauEtape
     */
    public function get($id)
    {
        if (null === $id || 0 === $id || '-' === $id){
            return null;
        }
        $tiretPos = strrpos($id,'-');
        $groupeTypeFormationLibelleCourt = substr( $id, 0, $tiretPos );
        $niv = substr( $id, $tiretPos+1 );
        if ($niv === false) $niv = null;
        
        $niveau = new NiveauEtape();
        $niveau->setLib($groupeTypeFormationLibelleCourt);
        $niveau->setNiv($niv);
        return $niveau;
    }

}