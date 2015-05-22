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
use Application\Entity\Db\TypeVolumeHoraire;
use DateTime;
use Application\Acl\IntervenantPermanentRole;

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
        /*********************************************************
         *                      Rôle administrateur
         *********************************************************/
        if ($this->role instanceof AdministrateurRole) {
            return true;
        }

        $intervenant      = $this->resource->getIntervenant();
        if ($this->resource->getElementPedagogique()) {
            $serviceStructure = $this->resource->getElementPedagogique()->getStructure();
        }else{
            $serviceStructure = null;
        }
        if ($intervenant) {
            $intervenantStructure = $this->resource->getIntervenant()->getStructure();
        }
        $typeVolumeHoraire = $this->resource->getTypeVolumeHoraire();

        $inCxtPrevu   = $typeVolumeHoraire && $typeVolumeHoraire->getCode() === TypeVolumeHoraire::CODE_PREVU;
        $inCxtRealise = $typeVolumeHoraire && $typeVolumeHoraire->getCode() === TypeVolumeHoraire::CODE_REALISE;

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
                if (!$serviceStructure && 'create' == $this->privilege) {
                    // si la composante d'enseignement n'est pas encore connue à ce stade, 
                    // on veut sans doute créer un nouveau service, il faut laisser passer...
                    return true;
                }
                if ($inCxtPrevu){
                    if ($intervenant instanceof IntervenantPermanent) {
                        if ($roleStructure === $intervenantStructure) {
                            /* la composante d'affectation doit pouvoir saisir et contrôler les heures effectuées par ses permanents dans quelque composante que ce soit. */
                            return true;
                        }
                    }else{
                        if ($roleStructure === $serviceStructure) {
                            // un gestionnaire ne peut saisir des enseignements à un vacataire que sur sa propre composante
                            return true;
                        }
                    }
                }elseif($inCxtRealise){
                    if ($roleStructure === $serviceStructure) {
                        // un gestionnaire peut saisir réalisé des enseignements que sur sa propre composante
                        return true;
                    }
                    if ($intervenant instanceof IntervenantPermanent
                        &&  $roleStructure === $intervenantStructure
                        && $serviceStructure === null
                    ){
                        // un gestionnaire doit pouvoir saisir des services réalisés sur d'autres composantes
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