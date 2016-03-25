<?php

namespace Application\Rule\Validation\Enseignement\Realise;

use Application\Entity\Db\Service;
use Application\Rule\Validation\ValidationEnsRefAbstractRule;
use Application\Acl\ComposanteRole;
use Application\Acl\AdministrateurRole;
use Application\Service\Workflow\Workflow;

/**
 * Spécificités de la validation des enseignements RÉALISÉS.
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
         * La structure d'intervention doit correspondre à la 
         * structure du rôle (i.e. structure de responsabilité) ou être null (si enseignement hors UCBN).
         */
        $this->structuresIntervention = [ (string) $this->structureRole => $this->structureRole ];

        /**
         * Le réalisé hors UCBN d'un permanent est validé par sa structure d'affectation
         * (hors UCBN <=> structure d'intervention = null).
         */
        if ($this->getIntervenant()->estPermanent() && $this->structureRole === $this->getIntervenant()->getStructure()) {
            $this->structuresIntervention[Service::HORS_ETABLISSEMENT] = null;
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
         * Validation par chaque composante d'intervention des enseignements la concernant.
         */
        $this->structureValidation = $this->structureRole;
        
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
        
        if (!$this->isAllowedMiseEnPaiement($privilege)) {
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
             * Validation par chaque composante d'intervention des enseignements la concernant.
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
        return Workflow::SERVICE_VALIDATION_REALISE;
    }
}