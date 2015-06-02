<?php

namespace Application\Rule\Validation;

use Application\Acl\ComposanteRole;
use Application\Entity\Db\TypeVolumeHoraire;
use Application\Service\Workflow\Workflow;
use Common\Exception\LogicException;

/**
 * Tentative de centralisation des "règles métier" concernant la validation du référentiel.
 * 
 * Détermine en fonction du contexte courant les paramètres nécessaires à la validation
 * du référentiel.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ValidationReferentielRule extends ValidationEnsRefAbstractRule
{
    /**
     * Détermine selon le contexte les composantes d'intervention (éventuelles) à utiliser comme
     * critère de recherche des enseignements déjà validés ou à valider.
     * 
     * @return ValidationReferentielRule
     */
    protected function determineStructuresIntervention()
    {
        /**
         * PRÉVU
         */
        if ($this->isInContextePrevu()) {
            /**
             * Intervenant permanent : validation par la composante d'affectation de l'intervenant.
             */
            if ($this->intervenant->estPermanent()) {
                $this->structuresIntervention = null; // toutes structures
            }
            /**
             * Intervenant vacataire : impossible.
             */
            else {
                throw new LogicException("Les vacataires ne peuvent pas avoir de référentiel.");
            }
        }
        /**
         * RÉALISÉ
         */
        elseif ($this->isInContexteRealise()) {
            /**
             * Validation par chaque structure du référentiel.
             */
            $this->structuresIntervention = $this->structureRole;
        }

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
     * @return ValidationReferentielRule
     */
    protected function determineStructureValidation()
    {
        /**
         * PRÉVU
         */
        if ($this->isInContextePrevu()) {
            /**
             * Intervenant permanent : validation par la composante d'affectation de l'intervenant.
             */
            if ($this->intervenant->estPermanent()) {
                if ($this->structureRole !== $this->intervenant->getStructure()) {
                    $this->structureValidation = $this->intervenant->getStructure();
                }
                else {
                    $this->structureValidation = $this->structureRole;
                }
            }
            /**
             * Intervenant vacataire : validation par chaque structure du référentiel.
             */
            else {
                $this->structureValidation = $this->structureRole;
            }
        }
        /**
         * RÉALISÉ
         */
        elseif ($this->isInContexteRealise()) {
            /**
             * Validation par chaque structure du référentiel.
             */
            $this->structureValidation = $this->structureRole;
        }
        
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
        if (!$this->isAllowedByWorkflow($privilege)) {
            return false;
        }
        
        if (!$this->isAllowedMiseEnPaiement($privilege)) {
            return false;
        }
        
        /*********************************************************
         *                      Rôle Composante
         *********************************************************/
        if ($this->role instanceof ComposanteRole) {
            if ('read' === $privilege) {
                return true; // les composantes voient tout
            }

            /**
             * PRÉVU
             */
            if ($this->isInContextePrevu()) {
                /**
                 * Intervenant permanent : validation par la composante d'affectation de l'intervenant ;
                 * Intervenant vacataire : validation par chaque structure du référentiel.
                 */
                $flag =
                         $this->intervenant->estPermanent() && $this->structureRole === $this->intervenant->getStructure() ||
                        !$this->intervenant->estPermanent() && $this->structureRole === $this->structureValidation;
                        
                return $flag;
            }
            /**
             * REALISE
             */
            elseif ($this->isInContexteRealise()) {
                /**
                 * Validation par chaque structure du référentiel.
                 */
                return $this->structureRole === $this->structureValidation;
                
            }
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
        $stepKeys = [
            TypeVolumeHoraire::CODE_PREVU   => Workflow::REFERENTIEL_VALIDATION,
            TypeVolumeHoraire::CODE_REALISE => Workflow::REFERENTIEL_VALIDATION_REALISE,
        ];
        
        return $stepKeys[$this->typeVolumeHoraire->getCode()];
    }
}