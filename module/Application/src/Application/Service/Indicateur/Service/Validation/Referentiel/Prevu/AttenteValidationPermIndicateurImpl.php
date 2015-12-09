<?php

namespace Application\Service\Indicateur\Service\Validation\Referentiel\Prevu;

use Application\Entity\Db\TypeIntervenant as TypeIntervenantEntity;
use Application\Service\Traits\TypeIntervenantAwareTrait;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AttenteValidationPermIndicateurImpl extends AttenteValidationAbstractIndicateurImpl
{
    use TypeIntervenantAwareTrait;

    /**
     * Retourne le type d'intervenant utile à cet indicateur.
     * 
     * @return TypeIntervenantEntity
     */
    public function getTypeIntervenant()
    {
        if (! parent::getTypeIntervenant()) {
            $this->setTypeIntervenant( $this->getServiceTypeIntervenant()->getPermanent() );
        }

        return parent::getTypeIntervenant();
    }
}