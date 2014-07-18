<?php

namespace Application\Rule\Intervenant;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Rule\AbstractRule;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Application\Traits\StructureAwareTrait;
use Application\Traits\IntervenantAwareTrait;
use Application\Traits\TypeAgrementAwareTrait;
use Application\Service\TypeAgrementStatut as TypeAgrementStatutService;
use Application\Service\Agrement as AgrementService;
use Application\Service\Structure as StructureService;
use Application\Service\Service as ServiceService;
use Application\Entity\Db\Structure;
use Application\Entity\Db\Agrement;
use Application\Entity\Db\TypeAgrement;

/**
 * Description of AgrementFourniRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AgrementFourniRule extends AbstractRule implements ServiceLocatorAwareInterface, ContextProviderAwareInterface
{
    use ServiceLocatorAwareTrait;
    use ContextProviderAwareTrait;
    use IntervenantAwareTrait;
//    use StructureAwareTrait;
    use TypeAgrementAwareTrait;

    /**
     * 
     * @return boolean
     */
    public function execute()
    {
//        if ($this->getStructure() && $this->getMemePartiellement()) {
//            throw new \Common\Exception\LogicException(
//                    "Si une structure est fournie à cette règle, le flag d'agrément partiel ne peut être à true.");
//        }
//        if ($this->getStructure() && $this->getTypeAgrement()->getCode() === TypeAgrement::CODE_CONSEIL_ACADEMIQUE) {
//            throw new \Common\Exception\LogicException(sprintf(
//                    "Fournir une structure à cette règle n'a pas de sens "
//                    . "car l'agrément de type '%s' se donne toutes structures d'enseignement confondues.", $this->getTypeAgrement()));
//        }
        
//        if (!in_array($this->getTypeAgrement(), $this->getTypesAgrementFournis())) {
//            $this->setMessage(sprintf("L'agrément &laquo; %s &raquo; n'a pas encore été donné%s.", 
//                    $this->getTypeAgrement(),
//                    $this->getStructure() ? 
//                            sprintf(" par la structure &laquo; %s &raquo;", $this->getStructure()) : 
//                            " par la moindre composante"));
//            return false;
//        }

        if ($this->getMemePartiellement() && count($this->getTypesAgrementFournis())) {
            return true;
        }
        
        /**
         * Il y a un Conseil Restreint par structure d'enseignement
         */
        if ($this->getTypeAgrement()->getCode() === TypeAgrement::CODE_CONSEIL_RESTREINT) {
            // teste si agrément fourni pour chaque structure
            foreach ($this->getStructuresEnseignement() as $structure) {
                if (!$this->getAgrementsFournis($structure)) {
                    $this->setMessage(sprintf("L'agrément &laquo; %s &raquo; n'a pas encore été donné par la structure &laquo; %s &raquo;.", 
                            $this->getTypeAgrement(),
                            $structure));
                    return false;
                }
            }
        }
        /**
         * Il y a un seul Conseil Academique pour toutes les structures d'enseignement
         */
        elseif ($this->getTypeAgrement()->getCode() === TypeAgrement::CODE_CONSEIL_ACADEMIQUE) {
            if (!count($this->getTypesAgrementFournis())) {
                $this->setMessage(sprintf("L'agrément &laquo; %s &raquo; n'a pas encore été donné.", $this->getTypeAgrement()));
                return false;
            }
        }
        else {
            throw new \Common\Exception\LogicException("Type d'agrément inattendu!");
        }
        
        $this->setMessage(sprintf("L'agrément &laquo; %s &raquo; a été donné.", $this->getTypeAgrement()));
            
        return true;
    }
    
    public function isRelevant()
    {
        return true;
    }
    
    /**
     * Flag indiquant si l'on se satisfait d'un agrément "partiel".
     * 
     * Autrement dit, avec ce flag à <code>true</code>, les agréments seront considérés comme donnés
     * (i.e. cette règle retournera <code>true</code>) si au moins une structure d'enseignement a donné 
     * son agrément parmi toutes celles où enseigne l'intervenant.
     * 
     * Attention : ce flag n'est pas pris en compte si une structure d'enseignement est fourni à cette règle.
     * 
     * @var boolean
     */
    private $memePartiellement = true;

    /**
     * Retourne la valeur du flag indiquant si l'on se satisfait d'un agrément "partiel".
     * 
     * @return boolean
     */
    public function getMemePartiellement()
    {
        return $this->memePartiellement;
    }

    /**
     * Change la valeur du flag indiquant si l'on se satisfait d'un agrément "partiel".
     * 
     * @param boolean $memePartiellement
     * @return \Application\Rule\Intervenant\AgrementFourniRule
     */
    public function setMemePartiellement($memePartiellement = true)
    {
        $this->memePartiellement = $memePartiellement;
        return $this;
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
            foreach ($this->getTypesAgrementStatut() as $tas) { /* @var $tas TypeAgrementStatut */
                $type = $tas->getType();
                $this->typesAgrementAttendus[$type->getId()] = $type;
            }
        }
        
        return $this->typesAgrementAttendus;
    }
    
    private $typesAgrementFournis;
    
    /**
     * 
     * @return array id => TypeAgrement
     */
    public function getTypesAgrementFournis()
    {
        if (null === $this->typesAgrementFournis) {
            $this->typesAgrementFournis = array();
            foreach ($this->getAgrementsFournis() as $a) { /* @var $a Agrement */
                $type = $a->getType();
                $this->typesAgrementFournis[$type->getId()] = $type;
            }
        }
        
        return $this->typesAgrementFournis;
    }
    
    /**
     * @var array
     */
    private $agrementsFournis;
    
    /**
     * 
     * @param Structure $structure
     * @return array id => Agrement
     */
    public function getAgrementsFournis(Structure $structure = null)
    {
        if (null === $this->agrementsFournis) {
            $qb = $this->getServiceAgrement()->finderByIntervenant($this->getIntervenant());
            $qb = $this->getServiceAgrement()->finderByAnnee($this->getContextProvider()->getGlobalContext()->getAnnee(), $qb);
            $this->agrementsFournis = $this->getServiceAgrement()->getList($qb);
        }
        
        // filtrage par structure éventuel
        if ($structure) {
            $agrements = [];
            foreach ($this->agrementsFournis as $agrement) { /* @var $agrement Agrement */
                if ($structure === $agrement->getStructure()) {
                    $agrements[$agrement->getId()] = $agrement;
                }
            }
            return $agrements;
        }
        
        return $this->agrementsFournis;
    }
    
    /**
     * @var array
     */
    private $structuresEns;
    
    /**
     * 
     * @return array id => Structure
     */
    public function getStructuresEnseignement()
    {
        if (null === $this->structuresEns) {
            // recherches de toutes les structures d'enseignements
            $serviceStructure = $this->getServiceStructure();
            $serviceService   = $this->getServiceService();
            $qb = $serviceStructure->initQuery()[0];
            $serviceStructure->join($serviceService, $qb, 'id', 'structureEns');
            $serviceService->finderByIntervenant($this->getIntervenant(), $qb);
            $this->structuresEns = $serviceStructure->getList($qb);
        }
        
        return $this->structuresEns;
    }
    
    /**
     * @return TypeAgrementStatutService
     */
    private function getServiceTypeAgrementStatut()
    {
        return $this->getServiceLocator()->get('applicationTypeAgrementStatut');
    }
    
    /**
     * @return AgrementService
     */
    private function getServiceAgrement()
    {
        return $this->getServiceLocator()->get('applicationAgrement');
    }
    
    /**
     * @return StructureService
     */
    private function getServiceStructure()
    {
        return $this->getServiceLocator()->get('applicationStructure');
    }
    
    /**
     * @return ServiceService
     */
    private function getServiceService()
    {
        return $this->getServiceLocator()->get('applicationService');
    }
}
