<?php

namespace Application\Rule\Intervenant;

use Zend\ServiceManager\ServiceLocatorAwareInterface;

/**
 * Règle métier déterminant si un intervenant est concerné par un type d'agrément donné.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class NecessiteAgrementRule extends AgrementAbstractRule implements ServiceLocatorAwareInterface
{
    /**
     * 
     * @return boolean
     */
    public function execute()
    {
        $statut = $this->getIntervenant()->getStatut();
        
        // si aucun critère type d'agrément n'a été spécifié
        if (!$this->getTypeAgrement()) {
            if (!$this->getTypesAgrementAttendus()) {
                $this->setMessage(sprintf(
                        "Le statut de l'intervenant (%s) ne nécessite aucun d'agrément particulier.", 
                        $statut));
                return false;
            }
            else {
                $this->setMessage(sprintf(
                        "Le statut de l'intervenant (%s) nécessite un agrément au moins.", 
                        $statut));
                return true;
            }
        }
        
        // si type d'agrément spécifié ne fait pas partie des attendus
        if (!in_array($this->getTypeAgrement(), $this->getTypesAgrementAttendus())) {
            $this->setMessage(sprintf(
                    "Le statut de l'intervenant (%s) ne nécessite pas d'agrément &laquo; %s &raquo;.", 
                    $statut, 
                    $this->getTypeAgrement()));
            return false;
        }
        else {
            $this->setMessage(sprintf("Le statut de l'intervenant (%s) nécessite l'agrément &laquo; %s &raquo;.", 
                    $statut, 
                    $this->getTypeAgrement()));
        }
        
        return true;
    }
    
    public function isRelevant()
    {
        return true;
    }
}
