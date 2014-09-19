<?php

namespace Application\Assertion;

use Application\Acl\ComposanteRole;
use Application\Entity\Db\Service;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\Permissions\Acl\Role\RoleInterface;

/**
 * Description of Service
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ServiceAssertion extends AbstractAssertion
{

    /**
     * Returns true if and only if the assertion conditions are met
     *
     * This method is passed the ACL, Role, Resource, and privilege to which the authorization query applies. If the
     * $role, $resource, or $privilege parameters are null, it means that the query applies to all Roles, Resources, or
     * privileges, respectively.
     *
     * @param  Acl               $acl
     * @param  RoleInterface     $role
     * @param  ResourceInterface $resource
     * @param  string            $privilege
     * @return bool
     */
    public function assert(Acl $acl, RoleInterface $role = null, ResourceInterface $resource = null, $privilege = null)
    {
        if ($resource instanceof Service) {
            return $this->assertEntity($acl, $role, $resource, $privilege);
        }
        return true;
    }

    /**
     *
     * @todo gérer les autres types de rôles
     *
     * @param Acl $acl
     * @param RoleInterface $role
     * @param Service $resource
     * @param string $privilege
     * @return boolean
     */
    protected function assertEntity(Acl $acl, RoleInterface $role = null, Service $resource = null, $privilege = null)
    {
        /*********************************************************
         *                      Rôle administrateur
         *********************************************************/
        if ($this->getSelectedIdentityRole() instanceof \Application\Acl\AdministrateurRole){
            return true;
        }

        $intervenant            = $resource->getIntervenant();
        $serviceStructure       = $resource->getStructureEns();
        if (! $serviceStructure && $resource->getElementPedagogique()) $serviceStructure = $resource->getElementPedagogique()->getStructure();
        $intervenantStructure   = $resource->getStructureAff() ?: $resource->getIntervenant()->getStructure();
        
        /*********************************************************
         *                      Rôle intervenant
         *********************************************************/
        if ($this->getSelectedIdentityRole() instanceof \Application\Acl\IntervenantRole){
            if (!$intervenant->getStatut()->getPeutSaisirService()){
                return false;
            }

            if (!$intervenant || $intervenant == $this->getSelectedIdentityRole()->getIntervenant()){
                return true; // Un intervenant ne peut travailler qu'avec ses services ou avec un service non enregistré
            }
        }


        $roleStructure          = $this->getSelectedIdentityRole()->getStructure();

        /*********************************************************
         *                      Rôle Composante
         *********************************************************/
        if ($this->getSelectedIdentityRole() instanceof ComposanteRole){
            if ('read' == $privilege) return true; // les composantes voient tout

            if ($roleStructure == $serviceStructure) return true; // chacun peut gérer ses propres services

            if ($intervenant){
                if ($intervenant instanceof \Application\Entity\Db\IntervenantPermanent){
                    if ($roleStructure == $intervenantStructure){
                        /* la composante d'affectation doit pouvoir saisir et contrôler les heures effectuées par ses permanents dans quelque composante que ce soit. */
                        return true;
                    }
                }
            }
        }

        return false;
    }
}