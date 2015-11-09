<?php

namespace Application\Rule\Validation\Enseignement\Prevu;

use Application\Rule\Validation\ValidationEnsRefAbstractRule;
use Application\Acl\ComposanteRole;
use Application\Acl\AdministrateurRole;
use Application\Service\Workflow\Workflow;

/**
 * Spécificités de la validation des enseignements PREVUS.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class Rule extends ValidationEnsRefAbstractRule
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
         * Intervenant permanent : peu importe la structure d'intervention.
         */
        if ($this->getIntervenant()->estPermanent()) {
            $this->structuresIntervention = null;
        }
        /**
         * Intervenant vacataire : la structure d'intervention doit correspondre à la 
         * structure du rôle (i.e. structure de responsabilité).
         */
        else {
            $this->structuresIntervention = [ (string) $this->structureRole => $this->structureRole ];
        }
        
        if ($this->structuresIntervention) {
            $this->addMessage(
                    sprintf("Seuls les enseignements dont la structure d'intervention est %s peuvent être validés.", 
                            implode(" ou ", array_keys($this->structuresIntervention))),
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
         * Intervenant permanent : validation par la composante d'affectation de l'intervenant.
         */
        if ($this->getIntervenant()->estPermanent()) {
            $this->structureValidation = $this->getIntervenant()->getStructure();
        }
        /**
         * Intervenant vacataire : validation par chaque composante d'intervention des enseignements la concernant.
         */
        else {
            $this->structureValidation = $this->structureRole;
        }
        
        $this->addMessage(
                "Les enseignements ne peuvent être validés que par la structure '{$this->structureValidation}'.", 
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
                $this->role instanceof AdministrateurRole && $this->structureRole
        ) {
            if ('read' === $privilege) {
                return true; // les composantes voient tout
            }
            
            /**
             * Intervenant permanent : validation par la composante d'affectation de l'intervenant ;
             * Intervenant vacataire : validation par chaque composante d'intervention des enseignements la concernant.
             */
            return $this->structureRole === $this->structureValidation;
        }

        /*********************************************************
         *                      Autres cas
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
        return Workflow::SERVICE_VALIDATION;
    }
}