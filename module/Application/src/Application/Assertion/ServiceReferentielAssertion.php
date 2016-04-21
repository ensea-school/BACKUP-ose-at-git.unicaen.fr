<?php

namespace Application\Assertion;

use Application\Acl\AdministrateurRole;
use Application\Acl\ComposanteRole;
use Application\Acl\EtablissementRole;
use Application\Acl\IntervenantPermanentRole;
use Application\Entity\Db\ServiceReferentiel;
use Application\Entity\Db\TypeVolumeHoraire;
use Application\Entity\Db\Structure;
use Application\Rule\Paiement\MiseEnPaiementExisteRule;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\Permissions\Acl\Role\RoleInterface;
use DateTime;

/**
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ServiceReferentielAssertion extends OldAbstractAssertion
{
    use \Application\Service\Traits\ValidationAwareTrait;
    
    /**
     * @var ServiceReferentiel
     */
    protected $resource;
    
    /**
     * @var Intervenant
     */
    protected $intervenant;
    
    /**
     * @var TypeVolumeHoraire
     */
    protected $typeVolumeHoraire;
    
    /**
     * @var boolean
     */
    protected $inCxtPrevu;
    
    /**
     * @var boolean
     */
    protected $inCxtRealise;
    
    /**
     * @var Structure
     */
    protected $serviceStructure;
    
    /**
     * @var Structure
     */
    protected $intervenantStructure;
    
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
        
        if ($this->resource instanceof ServiceReferentiel) {
            $this->intervenant       = $this->resource->getIntervenant();
            $this->typeVolumeHoraire = $this->resource->getTypeVolumeHoraire();
            $this->inCxtPrevu        = $this->typeVolumeHoraire && $this->typeVolumeHoraire->getCode() === TypeVolumeHoraire::CODE_PREVU;
            $this->inCxtRealise      = $this->typeVolumeHoraire && $this->typeVolumeHoraire->getCode() === TypeVolumeHoraire::CODE_REALISE;
            
            $this->serviceStructure     = $this->resource->getStructure();
            $this->intervenantStructure = $this->intervenant ? $this->intervenant->getStructure() : null;

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
        if (! $this->assertClotureRealise()) {
            return false;
        }
        if (! $this->assertMiseEnPaiement()) {
            return false;
        }
        
        if (! $this->intervenant->estPermanent()) {
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
            if ($this->intervenant && $this->intervenant !== $this->role->getIntervenant()) {
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
            
            if (! $this->assertStructureComposanteRole()) {
                return false;
            }
            
            if ('create' === $this->privilege) {
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

        return true;
    }
    
    /**
     * Assertions portant sur les structures mises en jeu,
     * pour le rôle Composante.
     * 
     * @return boolean
     */
    private function assertStructureComposanteRole()
    {
        /**
         * On ne s'intéresse ici qu'au rôle Composante.
         */
        if (! $this->role instanceof ComposanteRole) {
            return true;
        }
        
        $roleStructure = $this->role->getStructure();
        
        /**
         * Le gestionnaire de la structure d'affectation de l'intervenant saisit 
         * le référentiel portant sur sa structure de responsabilité.
         */
        if ($roleStructure === $this->serviceStructure) {
            return true;
        }

        /**
         * - Si la composante d'intervention n'est pas encore connue à ce stade, 
         * on veut sans doute créer un nouveau service, il faut laisser passer...
         * - Ou alors il s'agit d'un saisie de référentiel sans structure de rattachement...
         */
        if ($this->intervenant && ! $this->serviceStructure /*&& 'create' == $this->privilege*/) {
            return true;
        }
        
        /**
         * Référentiel Prévisionnel :
         * La composante d'affectation de l'intervenant doit correspondre à la structure de responsabilité
         * du gestionnaire.
         */
        if ($this->inCxtPrevu) {
            if ($this->intervenantStructure === $roleStructure) {
                return true;
            }
        }
        /**
         * Référentiel Réalisé :
         */
        elseif ($this->inCxtRealise) {
            if ($this->serviceStructure === $roleStructure) {
                // La composante d'intervention correspond à la structure de responsabilité du gestionnaire.
                return true;
            }
            if (
                    $this->intervenant->estPermanent() &&
                    $this->intervenantStructure === $roleStructure
            ) {
                // Un gestionnaire peut agir sur les permanents de sa composante pour n'importe quelle composante d'intervention
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Assertions concernant la clôture du service réalisé.
     * 
     * @return boolean
     */
    private function assertClotureRealise()
    {
        // la clôture de la saisie du réalisé n'a pas de sens pour du réalisé!
        if (! $this->inCxtRealise) {
            return true;
        }
        
        // la clôture de la saisie du réalisé n'a pas de sens pour un vacataire
        if (! $this->intervenant->estPermanent()) {
            return true;
        }
        
        // recherche de la clôture de service réalisé
        $cloture = $this->getServiceValidation()->findValidationClotureServices($this->intervenant, null);
        
        /**
         * Rôle Intervenant :
         * - si le réalisé est clôturé, on bloque.
         */
        if ($this->role->getIntervenant()) {
            switch ($this->privilege) {
                case self::PRIVILEGE_CREATE:
                case self::PRIVILEGE_UPDATE:
                case self::PRIVILEGE_DELETE:
                    // si le réalisé est clôturé, on bloque
                    if ($cloture) {
                        return false;
                    }
                    break;
                default:
                    break;
            }
        }
        
        return true;
    }
    
    /**
     * Assertions concernant les demandes de mise en paiement.
     * 
     * @return boolean
     */
    private function assertMiseEnPaiement()
    {
        // On ne s'intéresse ici qu'au réalisé.
        if (! $this->inCxtRealise) {
            return true;
        }
        // On ne s'intéresse ici qu'aux permanents.
        if (! $this->intervenant->estPermanent()) {
            return true;
        }
        
        // recherche existence d'une demande de mise en paiement
        $demandeMepExiste = $this->getRuleMiseEnPaiementExiste()->execute();
//        var_dump($demandeMepExiste);
        
        /**
         * Aucune demande de mise en paiement ne doit exister.
         */
        switch ($this->privilege) {
            case self::PRIVILEGE_CREATE:
            case self::PRIVILEGE_UPDATE:
            case self::PRIVILEGE_DELETE:
                // si le réalisé est clôturé, on bloque
                if ($demandeMepExiste) {
                    return false;
                }
                break;
            default:
                break;
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
    
    /**
     * @return MiseEnPaiementExisteRule
     */
    private function getRuleMiseEnPaiementExiste()
    {
        $rule = $this->getServiceLocator()->get('MiseEnPaiementExisteRule'); /* @var $rule MiseEnPaiementExisteRule */
        $rule->setIntervenant($this->intervenant)->setIsDemande();
        
        return $rule;
        
    }
}