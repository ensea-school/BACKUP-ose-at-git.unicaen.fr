<?php

namespace Application\Assertion;

use Application\Acl\ComposanteRole;
use Application\Acl\EtablissementRole;
use Application\Acl\DrhRole;
use Application\Entity\Db\ServiceReferentiel;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\Permissions\Acl\Role\RoleInterface;

/**
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ServiceReferentielAssertion extends AbstractAssertion
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
        if ($resource instanceof ServiceReferentiel) {
            return $this->assertEntity($acl, $this->getSelectedIdentityRole(), $resource, $privilege);
        }
        return true;
    }

    /**
     *
     * @todo gérer les autres types de rôles
     *
     * @param Acl $acl
     * @param RoleInterface $role
     * @param ServiceReferentiel $resource
     * @param string $privilege
     * @return boolean
     */
    protected function assertEntity(Acl $acl, RoleInterface $role = null, ServiceReferentiel $resource = null, $privilege = null)
    {
        $intervenant            = $resource->getIntervenant();
        $serviceStructure       = $resource->getStructure();
        $intervenantStructure = $intervenant ? $intervenant->getStructure() : null;

        if ($intervenant instanceof \Application\Entity\Db\IntervenantExterieur){
            return false; // pas de référentiel pour les intervenants extérieurs
        }

        /*********************************************************
         *                      Rôle administrateur
         *********************************************************/
        if ($role instanceof \Application\Acl\AdministrateurRole){
            return true;
        }

        /*********************************************************
         *                      Rôle intervenant
         *********************************************************/
        if ($role instanceof \Application\Acl\IntervenantPermanentRole){
            if (!$intervenant || $intervenant == $role->getIntervenant()){
                return true; // Un intervenant ne peut travailler qu'avec ses services ou avec un service non enregistré
            }
        }

        /*********************************************************
         *                      Rôle Composante
         *********************************************************/
        if ($role instanceof ComposanteRole){
            if ('read' == $privilege) return true; // les composantes voient tout

            $roleStructure          = $role->getStructure();
            if ($roleStructure == $serviceStructure) return true; // chacun peut gérer ses propres services

            if ($intervenant){
                if ($intervenant instanceof \Application\Entity\Db\IntervenantPermanent){
                    if ($roleStructure == $intervenantStructure){
                        /* la composante d'affectation doit pouvoir saisir et contrôler les heures effectuées par ses permanents dans quelque composante que ce soit. */
                        return true;
                    }
                }
            }elseif('create' == $privilege){
                return true;
            }
        }

        /*********************************************************
         *                      Rôle Superviseur
         *********************************************************/
        if ($role instanceof EtablissementRole){
            if ('read' == $privilege) return true; // les superviseurs voient tout
        }

        /*********************************************************
         *                      Rôle DRH
         *********************************************************/
        if ($role instanceof DrhRole){
            if ('read' == $privilege) return true; // ils voient tout à la DRH
        }

        return false;
    }
}