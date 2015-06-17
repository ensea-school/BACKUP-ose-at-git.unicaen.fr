<?php

namespace Application\Rule\Validation\Referentiel;

use Application\Rule\Validation\ValidationEnsRefAbstractRule;
use Common\Exception\LogicException;

/**
 * Tentative de centralisation des "règles métier" concernant la validation des enseignements.
 * 
 * Détermine en fonction du contexte courant les paramètres nécessaires à la validation
 * des enseignements.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ValidationRule extends ValidationEnsRefAbstractRule
{
    /**
     * @var ValidationEnsRefAbstractRule
     */
    protected $proxy;

    /**
     * 
     * @return ValidationEnsRefAbstractRule
     * @throws LogicException
     */
    protected function getProxy()
    {
        if (null === $this->proxy) {
            if ($this->isInContextePrevu()) {
                $this->proxy = $this->getServiceLocator()->get('ValidationReferentielPrevuRule');
            }
            elseif ($this->isInContexteRealise()) {
                $this->proxy = $this->getServiceLocator()->get('ValidationReferentielRealiseRule');
            }
            else {
                throw new LogicException("Cas imprévu.");
            }
        }
        
        $this->proxy
                ->setRole($this->role)
                ->setIntervenant($this->intervenant);

        return $this->proxy;
    }
    
    /**
     * Exécute la règle.
     * 
     * @return self
     */
    public function execute()
    {
        return $this->getProxy()->execute();
    }

    /**
     * Détermine selon le contexte la ou les composantes d'intervention (éventuelles) à utiliser comme
     * critère de recherche des enseignements déjà validés ou à valider.
     * 
     * @return self
     */
    protected function determineStructuresIntervention()
    {
        return $this->getProxy()->determineStructuresIntervention();
    }

    /**
     * Détermine la structure auteure de la validation à créer ou des validations recherchées.
     * 
     * @return self
     */
    protected function determineStructureValidation()
    {
        return $this->getProxy()->determineStructureValidation();
    }

    /**
     * Indique si le rôle courant possède le privilège spécifié d'après le contexte courant.
     * 
     * @param string $privilege Ex: 'create', 'read'
     * @return boolean
     */
    public function isAllowed($privilege)
    {
        return $this->getProxy()->isAllowed($privilege);
    }

    /**
     * Retourne la clé de l'étape dans le workflow.
     * 
     * @return string
     */
    protected function getWorkflowStepKey()
    {
        return $this->getProxy()->getWorkflowStepKey();
    }
    
    /**
     * Retourne les composantes d'intervention (éventuelles) à utiliser comme
     * critère de recherche des enseignements déjà validés ou à valider    
     * 
     * @return array Format <code>libellé => Structure</code> 
     * NB: la valeur <code>null</code> est possible (i.e. enseignement hors UCBN).
     */
    public function getStructuresIntervention()
    {
        return $this->getProxy()->getStructuresIntervention();
    }

    /**
     * Retourne la structure auteure de la validation à créer ou des validations recherchées.
     * 
     * @return null|Structure
     */
    public function getStructureValidation()
    {
        return $this->getProxy()->getStructureValidation();
    }
}