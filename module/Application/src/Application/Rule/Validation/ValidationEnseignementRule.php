<?php

namespace Application\Rule\Validation;

use Application\Acl\ComposanteRole;
use Application\Acl\AdministrateurRole;
use Application\Entity\Db\TypeVolumeHoraire;
use Application\Service\Workflow\Workflow;

/**
 * Tentative de centralisation des "règles métier" concernant la validation des enseignements.
 * 
 * Détermine en fonction du contexte courant les paramètres nécessaires à la validation
 * des enseignements.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ValidationEnseignementRule extends ValidationEnsRefAbstractRule
{
    /**
     * Détermine la composante d'intervention (éventuelle) à prendre en compte
     * dans la recherche des enseignements à valider ou déjà validés.
     * 
     * @return self
     */
    protected function determineStructureIntervention()
    {
        /**
         * PRÉVU
         */
        if (TypeVolumeHoraire::CODE_PREVU === $this->typeVolumeHoraire->getCode()) {
            /**
             * Intervenant permanent : peu importe la structure d'intervention.
             */
            if ($this->intervenant->estPermanent()) {
                $this->structureIntervention = null;
            }
            /**
             * Intervenant vacataire : la structure d'intervention doit correspondre à la 
             * structure du rôle (i.e. structure de responsabilité).
             */
            else {
                $this->structureIntervention = $this->structureRole;
            }
        }
        /**
         * RÉALISÉ
         */
        elseif (TypeVolumeHoraire::CODE_REALISE === $this->typeVolumeHoraire->getCode()) {
            /**
             * La structure d'intervention doit correspondre à la 
             * structure du rôle (i.e. structure de responsabilité).
             */
            $this->structureIntervention = $this->structureRole;
        }
        
        if ($this->structureIntervention) {
            $this->addMessage(
                    "Seuls les enseignements dont la composante d'intervention est '{$this->structureIntervention}' peuvent être validés.", 
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
         * PRÉVU
         */
        if (TypeVolumeHoraire::CODE_PREVU === $this->typeVolumeHoraire->getCode()) {
            /**
             * Intervenant permanent : validation par la composante d'affectation de l'intervenant.
             */
            if ($this->intervenant->estPermanent()) {
                $this->structureValidation = $this->structureRole;
                
                if ($this->structureRole !== $this->intervenant->getStructure()) {
                    $this->structureValidation = $this->intervenant->getStructure();
                }
            }
            /**
             * Intervenant vacataire : validation par chaque composante d'intervention des enseignements la concernant.
             */
            else {
                $this->structureValidation = $this->structureRole;
            }
        }
        /**
         * RÉALISÉ
         */
        elseif (TypeVolumeHoraire::CODE_REALISE === $this->typeVolumeHoraire->getCode()) {
            /**
             * Validation par chaque composante d'intervention des enseignements la concernant.
             */
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
//        var_dump($this->typeVolumeHoraire->getCode(), $this->structureRole." ". $this->structureValidation);
        
        /**
         * Interrogation du workflow.
         */
        if (!$this->isAllowedByWorkflow($privilege)) {
            return false;
        }
        
        /*********************************************************
         *                      Rôle Composante
         *********************************************************/
        if (
                $this->role instanceof ComposanteRole 
                || $this->role instanceof AdministrateurRole && $this->structureRole
        ) {
            if ('read' === $privilege) {
                return true; // les composantes voient tout
            }
            
            /**
             * PRÉVU
             */
            if (TypeVolumeHoraire::CODE_PREVU === $this->typeVolumeHoraire->getCode()) {
                /**
                 * Intervenant permanent : validation par la composante d'affectation de l'intervenant ;
                 * Intervenant vacataire : validation par chaque composante d'intervention des enseignements la concernant.
                 */
                return
                         $this->intervenant->estPermanent() && $this->structureRole === $this->intervenant->getStructure() ||
                        !$this->intervenant->estPermanent() && $this->structureRole === $this->structureValidation;
            }
            /**
             * REALISE
             */
            elseif (TypeVolumeHoraire::CODE_REALISE === $this->typeVolumeHoraire->getCode()) {
                /**
                 * Validation par chaque composante d'intervention des enseignements la concernant.
                 */
                return $this->structureRole === $this->structureValidation;
            }
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
        $stepKeys = [
            TypeVolumeHoraire::CODE_PREVU   => Workflow::SERVICE_VALIDATION,
            TypeVolumeHoraire::CODE_REALISE => Workflow::SERVICE_VALIDATION_REALISE,
        ];
        
        return $stepKeys[$this->typeVolumeHoraire->getCode()];
    }
}