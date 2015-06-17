<?php

namespace Application\Rule\Validation\Referentiel;

use Application\Rule\Validation\ValidationEnsRefAbstractRule;
use Application\Acl\ComposanteRole;
use Application\Acl\AdministrateurRole;
use Application\Service\Workflow\Workflow;

/**
 * Tentative de centralisation des "règles métier" concernant la validation des enseignements RÉALISÉS.
 * 
 * Détermine en fonction du contexte courant les paramètres nécessaires à la validation
 * des enseignements RÉALISÉS.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ValidationRealiseRule extends ValidationEnsRefAbstractRule
{
    /**
     * Détermine selon le contexte la ou les composantes d'intervention (éventuelles) à utiliser comme
     * critère de recherche des enseignements déjà validés ou à valider.
     * 
     * @return self
     */
    protected function determineStructuresIntervention()
    {
        /**
         * Validation par chaque structure du référentiel.
         */
        $this->structuresIntervention = $this->structureRole;

        if ($this->structuresIntervention) {
            $this->addMessage(
                    "Seul le référentiel dont la structure est '{$this->structuresIntervention}' peuvt être validé.", 
                    'info');
        }
        
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
         * Validation par chaque structure du référentiel.
         */
        $this->structureValidation = $this->structureRole;
        
        $this->addMessage(
                "Le référentiel ne peut être validé que par la structure '{$this->structureValidation}'.", 
                'info');
        
        return $this;
    }
    
    /**
     * Indique si le rôle courant possède le privilège spécifié d'après le contexte courant.
     * 
     * @param string $privilege Ex: 'create', 'read'
     * @return boolean
     */
    public function isAllowed($privilege)
    {
        /**
         * Interrogation du workflow.
         */
//        if (!$this->isAllowedByWorkflow($privilege)) {
//            return false;
//        }
        
        if (!$this->isAllowedMiseEnPaiement($privilege)) {
            return false;
        }
        
        /*********************************************************
         *                      Rôle Composante
         *********************************************************/
        if (
                $this->role instanceof ComposanteRole ||
                $this->role instanceof AdministrateurRole
        ) {
            if ('read' === $privilege) {
                return true; // les composantes voient tout
            }

            /**
             * Validation par chaque structure du référentiel.
             */
            return $this->structureRole === $this->structureValidation;
        }

        /*********************************************************
         *                      Autres rôles
         *********************************************************/
        if ('read' === $privilege) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Retourne la clé de l'étape dans le workflow.
     * 
     * @return string
     */
    protected function getWorkflowStepKey()
    {
        return Workflow::REFERENTIEL_VALIDATION_REALISE;
    }
}