<?php

namespace Application\Rule\Validation;;

use Application\Acl\IntervenantRole;
use Application\Entity\Db\Structure;
use Application\Entity\Db\TypeVolumeHoraire;
use Application\Rule\AbstractBusinessRule;
use Application\Rule\Paiement\MiseEnPaiementExisteRule;
use Application\Entity\Db\Traits\IntervenantAwareTrait;
use Application\Entity\Db\Traits\TypeVolumeHoraireAwareTrait;
use LogicException;

/**
 * Tentative de centralisation des "règles métier" concernant la validation des enseignements et du référentiel.
 * 
 * Détermine en fonction du contexte courant les paramètres nécessaires à la validation.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class ValidationEnsRefAbstractRule extends AbstractBusinessRule
{
    use IntervenantAwareTrait;
    use TypeVolumeHoraireAwareTrait;

    /**
     * Exécute la règle.
     * 
     * @return self
     */
    public function execute()
    {
        parent::execute();
        
        if (! $this->getIntervenant()) {
            throw new LogicException("Un intervenant doit être spécifié.");
        }
        if (! $this->typeVolumeHoraire) {
            throw new LogicException("Un type de volume horaire doit être spécifié.");
        }

        if (! in_array($this->typeVolumeHoraire->getCode(), TypeVolumeHoraire::$codes)) {
            throw new LogicException("Type de volume horaire spécifié inattendu.");
        }
        
        $this
                ->determineStructuresIntervention()
                ->determineStructureValidation();
        
        return $this;
    }    

    /**
     * Détermine la structure associée au rôle utilisateur courant.
     * 
     * @return self
     * @throws LogicException
     */
    protected function determineStructureRole()
    {
        /**
         * Rôle Intervenant.
         */
        if ($this->role instanceof IntervenantRole) {
            $this->structureRole = $this->getIntervenant()->getStructure();
        }
        else {
            $this->structureRole = $this->role->getStructure();
        }
        
        return $this;
    }

    /**
     * Détermine selon le contexte les composantes d'intervention (éventuelles) à utiliser comme
     * critère de recherche des enseignements déjà validés ou à valider.
     * 
     * @return self
     */
    abstract protected function determineStructuresIntervention();

    /**
     * Détermine la structure auteure de la validation à créer ou des validations existantes.
     * 
     * @return self
     */
    abstract protected function determineStructureValidation();

    /**
     * Composantes d'intervention (éventuelles) à utiliser comme
     * critère de recherche des enseignements déjà validés ou à valider    
     * 
     * @var array Format <code>libellé => Structure</code> 
     * NB: la valeur <code>null</code> est possible (i.e. enseignement hors UCBN).
     */
    protected $structuresIntervention;

    /**
     * Retourne les composantes d'intervention (éventuelles) à utiliser comme
     * critère de recherche des enseignements déjà validés ou à valider    
     * 
     * @return array Format <code>libellé => Structure</code> 
     * NB: la valeur <code>null</code> est possible (i.e. enseignement hors UCBN).
     */
    public function getStructuresIntervention()
    {
        return $this->structuresIntervention;
    }

    /**
     * @var Structure
     */
    protected $structureValidation;

    /**
     * Retourne la structure auteure de la validation à créer ou des validations recherchées.
     * 
     * @return null|Structure
     */
    public function getStructureValidation()
    {
        return $this->structureValidation;
    }
    
    /**
     * Assertions concernant les demandes de mise en paiement.
     *
     * @param string $privilege
     * @return boolean
     */
    protected function isAllowedMiseEnPaiement($privilege)
    {
        // On ne s'intéresse ici qu'au réalisé.
        if (! $this->isInContexteRealise()) {
            return true;
        }
        // On ne s'intéresse ici qu'aux permanents.
        if (! $this->getIntervenant()->estPermanent()) {
            return true;
        }
        
        // recherche existence d'une demande de mise en paiement
        $demandeMepExiste = $this->getRuleMiseEnPaiementExiste()->execute();

        /**
         * Impossible de dévalider si la moindre demande de mise en paiement existe.
         */
        switch ($privilege) {
            case 'delete':
                if ($demandeMepExiste) {
                    return false;
                }
                break;
            default:
                break;
        }
        
        return true;
    }
    
    /**
     * @return MiseEnPaiementExisteRule
     */
    private function getRuleMiseEnPaiementExiste()
    {
        $rule = $this->getServiceLocator()->get('MiseEnPaiementExisteRule'); /* @var $rule MiseEnPaiementExisteRule */
        $rule->setIntervenant($this->getIntervenant())->setIsDemande();
        
        return $rule;
        
    }
    
    /**
     * Indique si le privilège spécifié est accordé, en fonction de la progression 
     * de l'intervenant dans le workflow.
     * 
     * En consultation (privilège 'read'), laisse passer.
     * En création (privilège 'create'), vérifie que l'étape du workflow est atteignable.
     * 
     * @param string $privilege
     * @return boolean
     */
    protected function isAllowedByWorkflow($privilege) 
    {
        if (!$this->getWorkflow()->containsStep($this->getWorkflowStepKey())){ return true; }
        $step = $this->getWorkflow()->getStep($this->getWorkflowStepKey());
        
        /**
         * Consultation : no problemo.
         */
        if ($privilege === 'read') {
            return true;
        }
        
        /**
         * Création : l'étape du workflow doit être atteignable.
         */
        elseif ($privilege === 'create') {
            if (!$this->getWorkflow()->isStepReachable($step)) {
                $this->addMessage("Étape '{$step->getLabel()}' du workflow non atteignable.", 'warning');
                
                return false;
            }
        }
        
        /**
         * Modification, suppression : l'étape suivante du workflow ne doit pas avoir été franchie.
         */
//        elseif (in_array($privilege, ['update', 'delete'])) {
//            $nextStep = $this->getWorkflow()->getNextStep($step);
//            if ($nextStep && $this->getWorkflow()->isStepCrossable($nextStep)) {
//                return false;
//            }
//        }
        
        return true;
    }
    
    /**
     * Retourne la clé de l'étape dans le workflow.
     * 
     * @return string
     */
    abstract protected function getWorkflowStepKey();
    
    /**
     * Initialise et retourne le workflow intervenant.
     * 
     * @return WorkflowIntervenant
     */
    protected function getWorkflow()
    {
        $wf = $this->getWorkflowIntervenant()
                ->setIntervenant($this->getIntervenant())
                ->setRole($this->role);
        
        return $wf;
    }
    
    /**
     * Indique si l'on travaille sur le Prévisionnel.
     * 
     * @return boolean
     */
    protected function isInContextePrevu()
    {
        return TypeVolumeHoraire::CODE_PREVU === $this->typeVolumeHoraire->getCode();
    }
    
    /**
     * Indique si l'on travaille sur le Réalisé.
     * 
     * @return boolean
     */
    protected function isInContexteRealise()
    {
        return TypeVolumeHoraire::CODE_REALISE === $this->typeVolumeHoraire->getCode();
    }
}