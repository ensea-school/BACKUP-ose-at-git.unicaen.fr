<?php

namespace Application\Assertion;

use Application\Acl\ComposanteDbRole;
use Application\Controller\PieceJointeController;
use Application\Entity\Db\PieceJointe;
use Application\Entity\Db\TypePieceJointe;
use Application\Provider\Role\RoleProvider;
use Application\Rule\Intervenant\PieceJointeFourniRule;
use Application\Rule\Intervenant\NecessitePieceJointeRule;
//use Application\Service\Initializer\PieceJointeServiceAwareInterface;
//use Application\Service\Initializer\PieceJointeServiceAwareTrait;
use Application\Service\Workflow\WorkflowIntervenant;
use Application\Service\Workflow\WorkflowIntervenantAwareInterface;
use Application\Service\Workflow\WorkflowIntervenantAwareTrait;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\Permissions\Acl\Role\RoleInterface;

/**
 * Description of PieceJointeAssertion
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PieceJointeAssertion extends AbstractAssertion implements /*PieceJointeServiceAwareInterface,*/ WorkflowIntervenantAwareInterface
{
//    use PieceJointeServiceAwareTrait;
    use WorkflowIntervenantAwareTrait;
    
    const PRIVILEGE_VALIDER     = 'valider';
    const PRIVILEGE_DEVALIDER   = 'devalider';
    const PRIVILEGE_TELECHARGER = 'telecharger';
    
    /**
     * @var PieceJointe
     */
    protected $pj;
    
    /**
     * Returns true if and only if the assertion conditions are met
     *
     * This method is passed the ACL, Role, Resource, and privilege to which the authorization query applies. If the
     * $role, $resource, or $privilege parameters are null, it means that the query applies to all Roles, Resources, or
     * privileges, respectively.
     *
     * @param  Acl                        $acl
     * @param  RoleInterface         $role
     * @param  ResourceInterface $resource
     * @param  string                         $privilege
     * @return bool
     */
    public function assert(Acl $acl, RoleInterface $role = null, ResourceInterface $resource = null, $privilege = null)
    {
        /**
         * Cas N°1 : la ressource spécifiée est une entité ; un privilège est spécifié.
         */
//        if ($resource instanceof PieceJointe) {
//            return $this->assertEntity($acl, $role, $resource, $privilege);
//        }
//        
//        /**
//         * Cas N°2 : la ressource spécifiée est une chaîne de caractères du type 'controller/Application\Controller\PieceJointe:action' ;
//         * un privilège est spécifié (config des pages de navigation) ou pas (config des controller guards BjyAuthorize).
//         */
//        
//        $privilege = $this->normalizedPrivilege($privilege, $resource);
//        
////        var_dump(__CLASS__ . ' >>> ' . $resource . ' : ' . $privilege);
//        
//        $privilegeAjouterLotConseilRestreint  = sprintf("%s/%s", PieceJointeController::ACTION_AJOUTER_LOT, TypePieceJointe::CODE_CONSEIL_RESTREINT);
//        $privilegeAjouterLotConseilAcademique = sprintf("%s/%s", PieceJointeController::ACTION_AJOUTER_LOT, TypePieceJointe::CODE_CONSEIL_ACADEMIQUE);
//
//        // l'ajout par lot d'agréments de type "Conseil Académique" n'est autorisé qu'aux admin
//        if ($privilege === $privilegeAjouterLotConseilAcademique) {
//            if ($this->getSelectedIdentityRole()->getRoleId() !== RoleProvider::ROLE_ID_ADMIN) {
//                return false;
//            }
//        }
//        // l'ajout par lot d'agréments de type "Conseil Restreint" n'est pas autorisé aux admin pour
//        // l'instant car cela nécessiterait la sélection de la composante concernée
//        elseif ($privilege === $privilegeAjouterLotConseilRestreint) {
//            if ($this->getSelectedIdentityRole()->getRoleId() === RoleProvider::ROLE_ID_ADMIN) {
//                return false;
//            }
//        }
        
        return true;
    }
    
    /**
     * 
     * @param string $privilege
     * @param string $resource
     * @return string
     */
    protected function normalizedPrivilege($privilege, $resource)
    {
        if (is_object($privilege)) {
            return $privilege;
        }
        if (!$privilege) {
            $privilege = ($tmp = strrchr($resource, $c = ':')) ? ltrim($tmp, $c) : null;
        }
        if ($privilege && false === strpos($privilege, '/') && $this->getTypePieceJointe()) {
            $privilege .= '/' . $this->getTypePieceJointe()->getCode();
        }
        
        return $privilege;
    }
    
    /**
     * 
     * @param Acl $acl
     * @param RoleInterface $role
     * @param ResourceInterface $resource
     * @param string $privilege
     * @return boolean
     */
    protected function assertEntity(Acl $acl, RoleInterface $role = null, ResourceInterface $resource = null, $privilege = null)
    {
        if (!parent::assertCRUD($acl, $role, $resource, $privilege)) {
            return false;
        }
        
        $this->pj = $resource;
        
        /*********************************************************
         *                      Rôle Composante
         *********************************************************/
        if ($this->getSelectedIdentityRole() instanceof ComposanteDbRole) {
            
            // saisie de l'agrément Conseil Academique interdit aux gestionnaires de composante
            if ($this->pj->getType()->isConseilAcademique()) {
                return false;
            }
            
            // structure de responsabilité de l'utilisateur et structure de l'agrément doivent correspondre
            if ($this->getSelectedIdentityRole()->getStructure() !== $this->pj->getStructure()) {
//            if ($this->pj->getStructure() && $this->getSelectedIdentityRole()->getStructure()->getId() !== $this->pj->getStructure()->getId()) {
                return false;
            }
        }
        
        $agrementStepKey = 'KEY_' . $this->pj->getType()->getCode();
        
        // l'étape PieceJointe du workflow doit être atteignable
        if (!$this->getWorkflow()->isStepReachable($agrementStepKey)) {
            return false;
        }

        /**
         * Modification, suppression
         */
        if (in_array($privilege, ['update', 'delete'])) {
            // l'étape suivante du workflow ne doit pas avoir été franchie
            $nextStep = $this->getWorkflow()->getNextStep($agrementStepKey);
            if ($nextStep && $this->getWorkflow()->isStepCrossable($nextStep)) {
                return false;
            }
        }

        return true;
    }
    
    /**
     * 
     * @return TypePieceJointe
     */
    protected function getTypePieceJointe()
    {
        return $this->getMvcEvent()->getParam('typePieceJointe');
    }
    
    /**
     * @return WorkflowIntervenant
     */
    private function getWorkflow()
    {
        $wf = $this->getWorkflowIntervenant()
                ->setIntervenant($this->pj->getIntervenant())
                ->setRole($this->getSelectedIdentityRole());
        
        return $wf;
    }
    
    /**
     * @return NecessitePieceJointeRule
     */
    private function getRuleNecessitePieceJointe()
    {
        $rule = $this->getPieceJointeService()->getRuleNecessitePieceJointe();
        $rule
                ->setIntervenant($this->pj->getIntervenant())
                ->setTypePieceJointe($this->pj->getType());
        
        return $rule;
    }
    
    /**
     * @return PieceJointeFourniRule
     */
    private function getRulePieceJointeFourni()
    {
        $rule = $this->getPieceJointeService()->getRulePieceJointeFourni();
        $rule
                ->setIntervenant($this->pj->getIntervenant())
                ->setTypePieceJointe($this->pj->getType());
        
        return $rule;
    }
}