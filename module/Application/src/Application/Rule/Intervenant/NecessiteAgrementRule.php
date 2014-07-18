<?php

namespace Application\Rule\Intervenant;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Rule\AbstractRule;
use Application\Traits\IntervenantAwareTrait;
use Application\Traits\TypeAgrementAwareTrait;
use Application\Service\TypeAgrementStatut as TypeAgrementStatutService;

/**
 * Description of NecessiteAgrementRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class NecessiteAgrementRule extends AbstractRule implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;
    use IntervenantAwareTrait;
    use TypeAgrementAwareTrait;
    
    public function execute()
    {
        $this->typesAgrementStatut   = null;
        $this->typesAgrementAttendus = null;
        
        $statut = $this->getIntervenant()->getStatut();
        
        if (!in_array($this->getTypeAgrement(), $this->getTypesAgrementAttendus())) {
            $this->setMessage(sprintf(
                    "Le statut de l'intervenant (%s) ne nécessite pas d'agrément &laquo; %s &raquo;.", 
                    $statut, 
                    $this->getTypeAgrement()));
            return false;
        }

        $this->setMessage(sprintf("Le statut de l'intervenant (%s) nécessite l'agrément &laquo; %s &raquo;.", 
                $statut, 
                $this->getTypeAgrement()));
            
        return true;
    }
    
    public function isRelevant()
    {
        return true;
    }
    
    /**
     * @var array
     */
    private $typesAgrementStatut;
    
    /**
     * 
     * @return array id => TypeAgrementStatut
     */
    private function getTypesAgrementStatut()
    {
        if (null === $this->typesAgrementStatut) {
            $qb = $this->getServiceTypeAgrementStatut()->finderByStatutIntervenant($this->getIntervenant()->getStatut());
            $this->typesAgrementStatut = $this->getServiceTypeAgrementStatut()->getList($qb);
        }
        
        return $this->typesAgrementStatut;
    }
    
    /**
     * @var array
     */
    private $typesAgrementAttendus;
    
    /**
     * 
     * @return array id => TypeAgrement
     */
    public function getTypesAgrementAttendus()
    {
        if (null === $this->typesAgrementAttendus) {
            $this->typesAgrementAttendus = array();
            foreach ($this->getTypesAgrementStatut() as $typeAgrementStatut) { /* @var $typeAgrementStatut TypeAgrementStatut */
                $type = $typeAgrementStatut->getType();
                $this->typesAgrementAttendus[$type->getId()] = $type;
            }
        }
        
        return $this->typesAgrementAttendus;
    }
    
    /**
     * @return TypeAgrementStatutService
     */
    private function getServiceTypeAgrementStatut()
    {
        return $this->getServiceLocator()->get('applicationTypeAgrementStatut');
    }
}
