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
     * Détermine selon le contexte la ou les composantes d'intervention (éventuelles) à utiliser comme
     * critère de recherche des enseignements déjà validés ou à valider.
     * 
     * @return self
     */
    protected function determineStructuresIntervention()
    {
        /***************************************************************************
         *                                  PRÉVU
         ***************************************************************************/
        if (TypeVolumeHoraire::CODE_PREVU === $this->typeVolumeHoraire->getCode()) {
            /**
             * Intervenant permanent : peu importe la structure d'intervention.
             */
            if ($this->intervenant->estPermanent()) {
                $this->structuresIntervention = null;
            }
            /**
             * Intervenant vacataire : la structure d'intervention doit correspondre à la 
             * structure du rôle (i.e. structure de responsabilité).
             */
            else {
                $this->structuresIntervention = [ (string) $this->structureRole => $this->structureRole ];
            }
        }
        /***************************************************************************
         *                                  RÉALISÉ
         ***************************************************************************/
        elseif (TypeVolumeHoraire::CODE_REALISE === $this->typeVolumeHoraire->getCode()) {
            /**
             * La structure d'intervention doit correspondre à la 
             * structure du rôle (i.e. structure de responsabilité) ou être null (si enseignement hors UCBN).
             */
            $this->structuresIntervention = [ (string) $this->structureRole => $this->structureRole ];
            
            /**
             * Le réalisé hors UCBN d'un permanent est validé par sa structure d'affectation
             * (hors UCBN <=> structure d'intervention = null).
             */
            if ($this->intervenant->estPermanent() && $this->structureRole === $this->intervenant->getStructure()) {
                $this->structuresIntervention["hors UCBN"] = null;
            }
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
        /***************************************************************************
         *                                  PRÉVU
         ***************************************************************************/
        if (TypeVolumeHoraire::CODE_PREVU === $this->typeVolumeHoraire->getCode()) {
            /**
             * Intervenant permanent : validation par la composante d'affectation de l'intervenant.
             */
            if ($this->intervenant->estPermanent()) {
//                $this->structureValidation = $this->structureRole;
//                
//                if ($this->structureRole !== $this->intervenant->getStructure()) {
//                    $this->structureValidation = $this->intervenant->getStructure();
//                }
                $this->structureValidation = $this->intervenant->getStructure();
            }
            /**
             * Intervenant vacataire : validation par chaque composante d'intervention des enseignements la concernant.
             */
            else {
                $this->structureValidation = $this->structureRole;
            }
        }
        /***************************************************************************
         *                                  RÉALISÉ
         ***************************************************************************/
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
            
//            var_dump(implode(" ; ", (array)$this->structuresIntervention));
//            var_dump("".$this->structureRole);
//            var_dump("".$this->structureValidation);
            
            /***************************************************************************
             *                                  PRÉVU
             ***************************************************************************/
            if (TypeVolumeHoraire::CODE_PREVU === $this->typeVolumeHoraire->getCode()) {
                /**
                 * Intervenant permanent : validation par la composante d'affectation de l'intervenant ;
                 * Intervenant vacataire : validation par chaque composante d'intervention des enseignements la concernant.
                 */
//                return
//                         $this->intervenant->estPermanent() && $this->structureRole === $this->intervenant->getStructure() ||
//                        !$this->intervenant->estPermanent() && $this->structureRole === $this->structureValidation;
                return $this->structureRole === $this->structureValidation;
            }
            /***************************************************************************
             *                                  REALISE
             ***************************************************************************/
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