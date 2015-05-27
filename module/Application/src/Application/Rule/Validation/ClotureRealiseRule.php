<?php

namespace Application\Rule\Validation;

use Application\Acl\IntervenantRole;
use Application\Acl\AdministrateurRole;
use Application\Acl\ComposanteRole;
use Application\Entity\Db\TypeVolumeHoraire;
use Application\Service\Workflow\Workflow;

/**
 * Tentative de centralisation des "règles métier" concernant la clôture des services réalisés
 * (enseignements + référentiel).
 * 
 * NB: la clôture est représentée en base de données par une validation.
 * 
 * Détermine en fonction du contexte courant les paramètres nécessaires à l'opération.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ClotureRealiseRule extends ValidationEnsRefAbstractRule
{
    /**
     * Non applicable.
     */
    protected function determineStructuresIntervention()
    {
        return $this;
    }

    /**
     * Détermine la structure auteure de la validation à créer ou de la validation recherchée.
     * 
     * @return self
     */
    protected function determineStructureValidation()
    {
        /***************************************************************************
         *                                  RÉALISÉ
         ***************************************************************************/
        if (TypeVolumeHoraire::CODE_REALISE === $this->typeVolumeHoraire->getCode()) {
            
            // Validation par la composante d'affectation de l'intervenant.
            $this->structureValidation = $this->intervenant->getStructure();
        }
        
        $this->addMessage(
                "Le service réalisé ne peut être clôturé que par la structure '{$this->structureValidation}'.", 
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
        
        if ('read' === $privilege) {
            return true;
        }
        
        /*********************************************************
         * Rôles : 
         * - Composante,
         * - Administrateur ayant sélectionné une structure, 
         * - Intervenant
         *********************************************************/
        if (
                $this->role instanceof IntervenantRole || 
                $this->role instanceof ComposanteRole || 
                $this->role instanceof AdministrateurRole && $this->structureRole
        ) {
            /***************************************************************************
             *                                  REALISE
             ***************************************************************************/
            if (TypeVolumeHoraire::CODE_REALISE === $this->typeVolumeHoraire->getCode()) {
                
                // Validation par la composante d'affectation de l'intervenant.
                return $this->structureRole === $this->structureValidation;
            }
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
        return Workflow::CLOTURE_REALISE;
    }
}