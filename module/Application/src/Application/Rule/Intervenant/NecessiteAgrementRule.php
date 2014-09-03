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
    
    /**
     * 
     * @return array id => TypeAgrementStatut
     */
    private function getTypesAgrementStatut()
    {
        $qb = $this->getServiceTypeAgrementStatut()->finderByStatutIntervenant($this->getIntervenant()->getStatut());
        $typesAgrementStatut = $this->getServiceTypeAgrementStatut()->getList($qb);
        
        return $typesAgrementStatut;
    }
    
    /**
     * 
     * @return array id => TypeAgrement
     */
    public function getTypesAgrementAttendus()
    {
        $typesAgrementAttendus = array();
        foreach ($this->getTypesAgrementStatut() as $typeAgrementStatut) { /* @var $typeAgrementStatut TypeAgrementStatut */
            $type = $typeAgrementStatut->getType();
            $typesAgrementAttendus[$type->getId()] = $type;
        }
        
        return $typesAgrementAttendus;
    }
    
    /**
     * @return TypeAgrementStatutService
     */
    private function getServiceTypeAgrementStatut()
    {
        return $this->getServiceLocator()->get('applicationTypeAgrementStatut');
    }
}
