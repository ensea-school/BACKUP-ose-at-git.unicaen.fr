<?php

namespace Application\Rule;

/**
 * Interface commune aux règles métiers de l'application.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
interface RuleInterface
{
    /**
     * Retourne le résultat d'exécution de cette règle.
     * 
     * @return mixed
     */
    public function execute();
    
    /**
     * Retourne <code>true</code> si cette règle est pertinente, <code>false</code> sinon.
     * 
     * @return boolean
     */
    public function isRelevant();
    
    /**
     * Retourne la liste des messages positionnés lors de l'exécution de cette règle.
     * 
     * @return array
     */
    public function getMessages();
}