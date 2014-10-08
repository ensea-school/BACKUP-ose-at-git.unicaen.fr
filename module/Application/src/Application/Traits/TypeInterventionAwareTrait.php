<?php

namespace Application\Traits;

use Application\Entity\Db\TypeIntervention;

/**
 * Description of TypeInterventionAwareTrait
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
trait TypeInterventionAwareTrait
{
    /**
     * @var TypeIntervention
     */
    protected $typeIntervention;

    /**
     * Spécifie le type de validation concerné.
     *
     * @param TypeIntervention $typeIntervention type de validation concerné
     */
    public function setTypeIntervention(TypeIntervention $typeIntervention)
    {
        $this->typeIntervention = $typeIntervention;

        return $this;
    }

    /**
     * Retourne le type de validation concerné.
     *
     * @return TypeIntervention
     */
    public function getTypeIntervention()
    {
        return $this->typeIntervention;
    }
}