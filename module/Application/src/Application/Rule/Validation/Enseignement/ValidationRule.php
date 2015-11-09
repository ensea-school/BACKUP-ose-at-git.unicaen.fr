<?php

namespace Application\Rule\Validation\Enseignement;

use Application\Rule\Validation\ValidationEnsRefAbstractRule;
use Common\Exception\LogicException;

/**
 * Tentative de centralisation des "règles métier" concernant la validation des enseignements.
 * 
 * Détermine en fonction du contexte courant les paramètres nécessaires à la validation.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ValidationRule extends ValidationEnsRefAbstractRule
{
    /**
     * @var ValidationEnsRefAbstractRule
     */
    protected $delegate;

    /**
     * Configure et retourne le service auquel on délègue le boulot.
     * 
     * @return ValidationEnsRefAbstractRule
     */
    protected function getDelegate()
    {
        if (null === $this->delegate) {
            $this->delegate = $this->delegateDependingOnContext();
        }
        
        $this->delegate
                ->setRole($this->role)
                ->setIntervenant($this->getIntervenant());
        
        return $this->delegate;
    }

    /**
     * Fournit selon le type de volume horaire le service auquel on délègue le boulot.
     *
     * @return array|object
     * @throws \Common\Exception\LogicException
     */
    private function delegateDependingOnContext()
    {
        if ($this->isInContextePrevu()) {
            return $this->getServiceLocator()->get('ValidationEnseignementPrevuRule');
        }
        elseif ($this->isInContexteRealise()) {
            return $this->getServiceLocator()->get('ValidationEnseignementRealiseRule');
        }

        throw new LogicException("Context imprévu.");
    }

    /**
     * @return ValidationEnsRefAbstractRule
     */
    protected function determineStructureRole()
    {
        return $this->getDelegate()->determineStructureRole();
    }

    /**
     * Détermine selon le contexte la ou les composantes d'intervention (éventuelles) à utiliser comme
     * critère de recherche des enseignements déjà validés ou à valider.
     *
     * @return self
     */
    protected function determineStructuresIntervention()
    {
        return $this->getDelegate()->determineStructuresIntervention();
    }

    /**
     * Détermine la structure auteure de la validation à créer ou des validations recherchées.
     *
     * @return self
     */
    protected function determineStructureValidation()
    {
        return $this->getDelegate()->determineStructureValidation();
    }

    /**
     * Indique si le rôle courant possède le privilège spécifié d'après le contexte courant.
     *
     * @param string $privilege Ex: 'create', 'read'
     * @return boolean
     */
    public function isAllowed($privilege)
    {
        return $this->getDelegate()->isAllowed($privilege);
    }

    /**
     * Retourne la clé de l'étape dans le workflow.
     *
     * @return string
     */
    protected function getWorkflowStepKey()
    {
        return $this->getDelegate()->getWorkflowStepKey();
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
        return $this->getDelegate()->getStructuresIntervention();
    }

    /**
     * Retourne la structure auteure de la validation à créer ou des validations recherchées.
     *
     * @return null|Structure
     */
    public function getStructureValidation()
    {
        return $this->getDelegate()->getStructureValidation();
    }
}