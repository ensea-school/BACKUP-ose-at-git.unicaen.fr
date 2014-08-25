<?php

namespace Application\Service;

use Application\Entity\Db\TypeAgrement as TypeAgrementEntity;

/**
 * Description of NavigationPagesProvider
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class NavigationPagesProvider extends AbstractService
{
    use \Application\Traits\WorkflowIntervenantAwareTrait;
    
    public function __invoke(array &$page)
    {
        $pages = [];
        
        foreach ($this->getTypesAgrementAttendus() as $typeAgrement) { /* @var $typeAgrement TypeAgrementEntity */
            $pages[$typeAgrement->getCode()] = [
                'label'  => (string) $typeAgrement,
                'title'  => "Agrément &laquo; $typeAgrement &raquo;",
                'route'  => 'intervenant/agrements/agrement',
                'params' => array(
                    // NB: le paramètre 'intervenant' est injecté par la NavigationFactory du module
                    'typeAgrement' => $typeAgrement->getId(),
                ),
                'withtarget' => true,
                'resource' => 'controller/Application\Controller\Agrement:voir',
                'visible' => 'NavigationPageVisibility',
            ];
        }
        
        return $pages;
    }
    
    private function getIntervenant()
    {
        return $this->getContextProvider()->getGlobalContext()->getIntervenant();
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
            if ($this->getIntervenant()) {
                $qb = $this->getServiceTypeAgrementStatut()->finderByStatutIntervenant($this->getIntervenant()->getStatut());
                $this->typesAgrementStatut = $this->getServiceTypeAgrementStatut()->getList($qb);
            }
            else {
                $this->typesAgrementStatut = [];
            }
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
            foreach ($this->getTypesAgrementStatut() as $tas) { /* @var $tas TypeAgrementStatut */
                $type = $tas->getType();
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