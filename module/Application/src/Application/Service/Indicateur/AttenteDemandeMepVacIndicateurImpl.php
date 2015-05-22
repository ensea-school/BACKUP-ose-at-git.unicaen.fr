<?php

namespace Application\Service\Indicateur;

use Application\Entity\Db\TypeIntervenant as TypeIntervenantEntity;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AttenteDemandeMepVacIndicateurImpl extends AttenteDemandeMepAbstractIndicateurImpl
{
    /**
     * Retourne le type d'intervenant utile à cet indicateur.
     * 
     * @return TypeIntervenantEntity
     */
    public function getTypeIntervenant()
    {
        if (null === $this->typeIntervenant) {
            $this->typeIntervenant = 
                    $this->getServiceLocator()->get('ApplicationTypeIntervenant')->getByCode(TypeIntervenantEntity::CODE_EXTERIEUR);
        }
        
        return $this->typeIntervenant;
    }
}