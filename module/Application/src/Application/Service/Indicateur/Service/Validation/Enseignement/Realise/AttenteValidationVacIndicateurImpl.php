<?php

namespace Application\Service\Indicateur\Service\Validation\Enseignement\Realise;

use Application\Entity\Db\TypeIntervenant as TypeIntervenantEntity;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AttenteValidationVacIndicateurImpl extends AttenteValidationAbstractIndicateurImpl
{
    /**
     * Retourne le type d'intervenant utile à cet indicateur.
     * 
     * @return TypeIntervenantEntity
     */
    public function getTypeIntervenant()
    {
        if (! parent::getTypeIntervenant()) {
            $sTi = $this->getServiceLocator()->get('ApplicationTypeIntervenant');
            /* @var $sTi \Application\Service\TypeIntervenant */
            $this->setTypeIntervenant( $sTi->getExterieur() );
        }

        return parent::getTypeIntervenant();
    }
}