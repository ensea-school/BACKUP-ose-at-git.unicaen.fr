<?php

namespace Application\Rule\Validation\Referentiel\Realise;

use Application\Rule\Validation\ValidationEnsRefAbstractRule;
use Application\Acl\ComposanteRole;
use Application\Acl\AdministrateurRole;
use Application\Service\Workflow\Workflow;

/**
 * Spécificités de la validation du référentiel réalisé du point de vue du rôle Intervenant.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class IntervenantRoleRule extends Rule
{
    /**
     * Détermine selon le contexte la ou les composantes d'intervention (éventuelles) à utiliser comme
     * critère de recherche du référentiel déjà validé ou à valider.
     * 
     * @return self
     */
    protected function determineStructuresIntervention()
    {
        /**
         * Aucun critère particulier concernant la composante d'intervention.
         */
        $this->structuresIntervention = null;

        return $this;
    }

    /**
     * Détermine la structure auteure de la validation à créer ou des validations recherchées.
     * 
     * @return self
     */
    protected function determineStructureValidation()
    {
        /**
         * Aucun critère particulier concernant la structure auteure de la validation.
         */
        $this->structureValidation = null;

        return $this;
    }
}