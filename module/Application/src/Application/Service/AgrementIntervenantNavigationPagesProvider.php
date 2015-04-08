<?php

namespace Application\Service;

use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Rule\Intervenant\NecessiteAgrementRule;
use Zend\Mvc\MvcEvent;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AgrementIntervenantNavigationPagesProvider extends AgrementNavigationPagesProvider
{
    /**
     * Retourne l'intervenant concerné.
     * 
     * @return IntervenantEntity
     */
    private function getIntervenant()
    {
        $e = $this->getServiceLocator()->get('application')->getMvcEvent(); /* @var $e MvcEvent */
        
        if (($intervenant = $e->getParam('intervenant'))) {
            return $intervenant;
        }
        
        return $this->getServiceContext()->getIntervenant();
    }
    
    /**
     * @return NecessiteAgrementRule
     */
    public function getNecessiteAgrementRule()
    {
        $necessiteAgrementRule = $this->getServiceLocator()->get('NecessiteAgrementRule');
        $necessiteAgrementRule
                ->setIntervenant($this->getIntervenant())
                ->execute();
        
        return $necessiteAgrementRule;
    }
    
    /**
     * Redéfinition pour ne retrouner que les types d'agrément requis par le statut de l'intervenant.
     * 
     * @return array id => TypeAgrement
     */
    public function getTypesAgrements()
    {
        if (!$this->getIntervenant()) {
            $typesAgrementAttendus = [];
        }
        else {
            $typesAgrementAttendus = $this->getNecessiteAgrementRule()->getTypesAgrementAttendus();
        }
        
        return $typesAgrementAttendus;
    }
}