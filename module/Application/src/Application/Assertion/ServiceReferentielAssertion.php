<?php

namespace Application\Assertion;

use Application\Acl\AdministrateurRole;
use Application\Acl\ComposanteRole;
use Application\Acl\DrhRole;
use Application\Acl\EtablissementRole;
use Application\Acl\IntervenantPermanentRole;
use Application\Entity\Db\IntervenantExterieur;
use Application\Entity\Db\IntervenantPermanent;
use Application\Entity\Db\ServiceReferentiel;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\Permissions\Acl\Role\RoleInterface;
use DateTime;

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
        parent::assert($acl, $role, $resource, $privilege);
        
        if ($resource instanceof ServiceReferentiel) {
            return $this->assertEntityOld();
        }
        
        return true;
    }

    /**
     *
     * @todo gérer les autres types de rôles
     *
     * @return boolean
     */
    protected function assertEntityOld()
    {
        $intervenant          = $this->resource->getIntervenant();
        $serviceStructure     = $this->resource->getStructure();
        $intervenantStructure = $intervenant ? $intervenant->getStructure() : null;

        if ($intervenant instanceof IntervenantExterieur) {
            return false; // pas de référentiel pour les intervenants extérieurs
        }

        /*********************************************************
         *                      Rôle administrateur
         *********************************************************/
        if ($this->role instanceof AdministrateurRole) {
            return true;
        }

        /*********************************************************
         *                      Rôle intervenant
         *********************************************************/
        if ($this->role instanceof IntervenantPermanentRole) {
            if ($intervenant && $intervenant !== $this->role->getIntervenant()) {
                return false; // Un intervenant ne peut travailler qu'avec ses services ou avec un service non enregistré
            }
            
            if ($this->isDateFinPrivilegeDepassee()) {
                return false;
            }
        }

        /*********************************************************
         *                      Rôle Composante
         *********************************************************/
        if ($this->role instanceof ComposanteRole) {
            if ('read' === $this->privilege) {
                return true; // les composantes voient tout
            }
            
            $roleStructure = $this->role->getStructure();
            if ($roleStructure === $serviceStructure) {
                return true; // chacun peut gérer ses propres services
            }
            
            if ($intervenant) {
                if (!$serviceStructure /*&& 'create' == $this->privilege*/) { 
                    // - Si la composante d'enseignement n'est pas encore connue à ce stade, 
                    // on veut sans doute créer un nouveau service, il faut laisser passer...
                    // - Ou alors il s'agit d'un saisie de référentiel sans structure de rattachement...
                    return true;
                }
                if ($intervenant instanceof IntervenantPermanent) {
                    if ($roleStructure === $intervenantStructure) {
                        /* la composante d'affectation doit pouvoir saisir et contrôler les heures effectuées par ses permanents dans quelque composante que ce soit. */
                        return true;
                    }
                }
            }
            elseif ('create' === $this->privilege) {
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
        if ($this->role instanceof DrhRole){
            if ('read' == $this->privilege) {
                return true; // ils voient tout à la DRH
            }
        }

        return true;
    }

    /**
     * Teste si la date de fin de "privilège" du rôle courant est dépassée ou non.
     *
     * @return boolean
     */
    protected function isDateFinPrivilegeDepassee()
    {
        $dateFin = null;

        /**
         * Rôle Intervenant Permanent
         */
        if ($this->role instanceof IntervenantPermanentRole) {
            // il existe une date de fin de saisie (i.e. ajout, modif, suppression) de service par les intervenants permanents eux-mêmes
            if (in_array($this->privilege, [self::PRIVILEGE_CREATE, self::PRIVILEGE_UPDATE, self::PRIVILEGE_DELETE])) {
                $dateFin = $this->getServiceContext()->getDateFinSaisiePermanents();
            }
        }

        if (null === $dateFin) {
            return false;
        }

        $now = new DateTime();

        $now->setTime(0, 0, 0);
        $dateFin->setTime(0, 0, 0);

        return $now > $dateFin;
    }
}