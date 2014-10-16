<?php

namespace Application\Assertion;

use Application\Acl\AdministrateurRole;
use Application\Acl\ComposanteRole;
use Application\Acl\DrhRole;
use Application\Acl\EtablissementRole;
use Application\Acl\IntervenantRole;
use Application\Entity\Db\IntervenantPermanent;
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
     * @var Service
     */
    protected $resource;

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
        parent::assert($acl, $role, $resource, $privilege);

        if ($resource instanceof Service) {
            return $this->assertEntity();
        }
        
        return true;
    }

    /**
     *
     * @todo gérer les autres types de rôles
     *
     * @return boolean
     */
    protected function assertEntity()
    {
        /*********************************************************
         *                      Rôle administrateur
         *********************************************************/
        if ($this->role instanceof AdministrateurRole) {
            return true;
        }

        $intervenant      = $this->resource->getIntervenant();
        $serviceStructure = $this->resource->getStructureEns();
        if (!$serviceStructure && $this->resource->getElementPedagogique()) {
            $serviceStructure = $this->resource->getElementPedagogique()->getStructure();
        }
        if ($intervenant) {
            $intervenantStructure = $this->resource->getStructureAff() ? : $this->resource->getIntervenant()->getStructure();
        }

        /*********************************************************
         *                      Rôle intervenant
         *********************************************************/
        if ($this->role instanceof IntervenantRole) {
            if (!$intervenant->getStatut()->getPeutSaisirService()) {
                return false;
            }

            if ($this->isDateFinPrivilegeDepassee()) {
                return false;
            }

            if (!$intervenant || $intervenant == $this->role->getIntervenant()) {
                return true; // Un intervenant ne peut travailler qu'avec ses services ou avec un service non enregistré
            }
        }

        /*********************************************************
         *                      Rôle Composante
         *********************************************************/
        if ($this->role instanceof ComposanteRole) {
            if ('read' == $this->privilege) {
                return true; // les composantes voient tout
            }
            
            $roleStructure = $this->role->getStructure();
            if ($roleStructure == $serviceStructure) {
                return true; // chacun peut gérer ses propres services
            }
            
            if ($intervenant) {
                if ($intervenant instanceof IntervenantPermanent) {
                    if ($roleStructure == $intervenantStructure) {
                        /* la composante d'affectation doit pouvoir saisir et contrôler les heures effectuées par ses permanents dans quelque composante que ce soit. */
                        return true;
                    }
                }
            }
            elseif ('create' == $this->privilege) {
                return true;
            }
        }

        /*********************************************************
         *                      Rôle Superviseur
         *********************************************************/
        if ($this->role instanceof EtablissementRole) {
            if ('read' == $this->privilege) {
                return true; // les superviseurs voient tout
            }
        }

        /*********************************************************
         *                      Rôle DRH
         *********************************************************/
        if ($this->role instanceof DrhRole) {
            if ('read' == $this->privilege) {
                return true; // ils voient tout à la DRH
            }
        }

        return false;
    }
}