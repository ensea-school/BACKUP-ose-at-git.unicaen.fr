<?php
namespace Application\Provider\Identity;

use UnicaenAuth\Provider\Identity\ChainableProvider;

/**
 * Classe de fournisseur d'identité issue de l'annuaire Ldap.
 * 
 * Retourne les rôles correspondant aux groupes LDAP auxquels appartient l'entité LDAP authentifiée.
 * NB : 
 * - Les ACL sont fournies par le service d'authorisation du module BjyAuthorize
 * - L'identité authentifiée est fournie par le service d'authentification.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class IdentityProvider implements ChainableProvider
{
    /**
     * {@inheritDoc}
     */
    public function injectIdentityRoles(\UnicaenAuth\Provider\Identity\ChainEvent $event)
    {
        $event->addRoles($this->getIdentityRoles());
    }
    
    /**
     * {@inheritDoc}
     */
    public function getIdentityRoles()
    {
        return array('intervenant');
    }
}