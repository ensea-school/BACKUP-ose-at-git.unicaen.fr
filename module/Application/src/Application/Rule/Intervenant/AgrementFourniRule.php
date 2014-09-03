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
    use StructureAwareTrait;
    use TypeAgrementAwareTrait;

    /**
     * 
     * @return boolean
     */
    public function execute()
    {
        /**
         * Si agrément partiel toléré : au moins un agrément fourni et c'est ok
         */
        if ($this->getMemePartiellement()) {
            if ($this->getStructure()) {
                throw new \Common\Exception\LogicException(
                        "Si une structure est fournie à cette règle, le flag d'agrément partiel ne peut être à true.");
            }
            if (count($this->getTypesAgrementFournis())) {
                return true;
            }
        }
        
        /**
         * Conseil Academique (un seul pour toutes les structures d'enseignement)
         */
        if ($this->getTypeAgrement()->getCode() === TypeAgrement::CODE_CONSEIL_ACADEMIQUE) {
            if (!count($this->getTypesAgrementFournis())) {
                $this->setMessage(sprintf("L'agrément &laquo; %s &raquo; n'a pas encore été donné.", $this->getTypeAgrement()));
                return false;
            }
            // une structure d'enseignement précise doit être fournie
            $structures = [ $this->getStructure()->getId() => $this->getStructure() ];
        }
        /**
         * Conseil Restreint (un par structure d'enseignement)
         */
        elseif ($this->getTypeAgrement()->getCode() === TypeAgrement::CODE_CONSEIL_RESTREINT) {
            // si une structure d'enseignement précise a été fournie, on ne considèrera qu'elle
            if ($this->getStructure()) {
                $structures = [ $this->getStructure()->getId() => $this->getStructure() ];
            }
            // sinon le test devra porter sur toutes les structures possibles
            else {
                $structures = $this->getStructuresEnseignement();
            }
        }
        
        // teste si un agrément existe pour chaque structure d'enseignement
        foreach ($structures as $structure) {
            if (!count($this->getAgrementsFournis($structure))) {
                $this->setMessage(sprintf("L'agrément &laquo; %s &raquo; n'a pas encore été donné par la structure &laquo; %s &raquo;.", 
                        $this->getTypeAgrement(),
                        $structure));
                return false;
            }
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
    private $memePartiellement = false;

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
     * 
     * @return array id => TypeAgrementStatut
     */
    private function getTypesAgrementStatut()
    {
        $qb = $this->getServiceTypeAgrementStatut()->finderByStatutIntervenant($this->getIntervenant()->getStatut());
        
        return $this->getServiceTypeAgrementStatut()->getList($qb);
    }
    
    /**
     * 
     * @return array id => TypeAgrement
     */
    public function getTypesAgrementAttendus()
    {
        $typesAgrementAttendus = array();
        foreach ($this->getTypesAgrementStatut() as $tas) { /* @var $tas TypeAgrementStatut */
            $type = $tas->getType();
            $typesAgrementAttendus[$type->getId()] = $type;
        }
        
        return $typesAgrementAttendus;
    }
    
    /**
     * 
     * @return array id => TypeAgrement
     */
    public function getTypesAgrementFournis()
    {
        $typesAgrementFournis = array();
        foreach ($this->getAgrementsFournis() as $a) { /* @var $a Agrement */
            $type = $a->getType();
            $typesAgrementFournis[$type->getId()] = $type;
        }
        
        return $typesAgrementFournis;
    }
    
    /**
     * 
     * @param Structure $structure
     * @return array id => Agrement
     */
    public function getAgrementsFournis(Structure $structure = null)
    {
        $qb = $this->getServiceAgrement()->finderByType($this->getTypeAgrement());
        $qb = $this->getServiceAgrement()->finderByIntervenant($this->getIntervenant(), $qb);
        $qb = $this->getServiceAgrement()->finderByAnnee($this->getContextProvider()->getGlobalContext()->getAnnee(), $qb);
        $agrementsFournis = $this->getServiceAgrement()->getList($qb);
        
        // filtrage par structure éventuel
        if ($structure) {
            $agrements = [];
            foreach ($agrementsFournis as $agrement) { /* @var $agrement Agrement */
                if ($structure === $agrement->getStructure()) {
                    $agrements[$agrement->getId()] = $agrement;
                }
            }
            return $agrements;
        }
        
        return $agrementsFournis;
    }
    
    /**
     * 
     * @return array id => Structure
     */
    public function getStructuresEnseignement()
    {
        // recherche des structures d'enseignements de l'intervenant
        $serviceStructure = $this->getServiceStructure();
        $serviceService   = $this->getServiceService();
        $qb = $serviceStructure->initQuery()[0];
        $serviceStructure->join($serviceService, $qb, 'id', 'structureEns');
        $serviceService->finderByIntervenant($this->getIntervenant(), $qb);
        $structuresEns = $serviceStructure->getList($qb);
        
        return $structuresEns;
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
