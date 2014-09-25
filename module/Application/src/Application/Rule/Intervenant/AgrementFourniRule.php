<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\TypeAgrement;
use Application\Service\ContextProviderAwareInterface;
use Common\Exception\LogicException;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Application\Traits\StructureAwareTrait;

/**
 * Description of AgrementFourniRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AgrementFourniRule extends AgrementAbstractRule implements ServiceLocatorAwareInterface, ContextProviderAwareInterface
{
    use StructureAwareTrait;
    
    /**
     * 
     * @return boolean
     */
    public function execute()
    {
        $role = $this->getContextProvider()->getSelectedIdentityRole();
                
        /**
         * Si agrément partiel toléré : au moins un agrément fourni et c'est ok
         */
        if ($this->getMemePartiellement()) {
            if ($this->getStructure()) {
                throw new LogicException(
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
//            // une structure d'enseignement précise doit être fournie
//            $structures = [ $this->getStructure()->getId() => $this->getStructure() ];
            // aucun critère de structure pour ce type d'agrément
            $structures = [ null ];
        }
        /**
         * Conseil Restreint (un par structure d'enseignement)
         */
        elseif ($this->getTypeAgrement()->getCode() === TypeAgrement::CODE_CONSEIL_RESTREINT) {
            // si une structure d'enseignement précise a été fournie, on ne considèrera qu'elle
            if ($this->getStructure()) {
                $structures = [ $this->getStructure()->getId() => $this->getStructure() ];
            }
            // sinon, pour certains rôles, peu importe la structure
            elseif ($role instanceof \Application\Acl\IntervenantRole || $role instanceof \Application\Acl\AdministrateurRole) {
                // du point de vue intervenant, aucun critère de structure
                $structures = [ null ];
            }
            else {
                $structures = $this->getStructuresEnseignement();
            }
        }
        
        // teste si un agrément existe pour chaque structure d'enseignement
        foreach ($structures as $structure) {
            if (!count($this->getAgrementsFournis($structure))) {
                $this->setMessage(sprintf("L'agrément &laquo; %s &raquo; n'a pas encore été donné%s.", 
                        $this->getTypeAgrement(),
                        $structure ? sprintf(" par la structure &laquo; %s &raquo;", $structure) : null));
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
     * @return AgrementFourniRule
     */
    public function setMemePartiellement($memePartiellement = true)
    {
        $this->memePartiellement = $memePartiellement;
        return $this;
    }
}
