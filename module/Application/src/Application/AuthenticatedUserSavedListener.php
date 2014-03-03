<?php

namespace Application;

use UnicaenAuth\Event\Listener\AuthenticatedUserSavedAbstractListener;
use UnicaenAuth\Event\UserAuthenticatedEvent;
use Application\Entity\Db\Utilisateur;

/**
 * Scrute l'événement déclenché juste avant que l'entité utilisateur ne soit persistée
 * pour renseigner les relations 'intervenant' et 'personnel'.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AuthenticatedUserSavedListener extends AuthenticatedUserSavedAbstractListener
{
    /**
     * Renseigne les relations 'intervenant' et 'personnel' avant que l'objet soit persisté.
     * 
     * @param UserAuthenticatedEvent $e
     */
    public function onUserAuthenticatedPrePersist(UserAuthenticatedEvent $e)
    {
        $user       = $e->getDbUser();   /* @var $user       \ZfcUser\Entity\UserInterface */
        $ldapPeople = $e->getLdapUser(); /* @var $ldapPeople \UnicaenApp\Entity\Ldap\People */
        
        if ($user instanceof Utilisateur) {
            
            $noIndividu = (integer) $ldapPeople->getSupannEmpId(); // pour virer les 0 de tête
            
            $repo = $this->em->getRepository('Application\Entity\Db\Personnel');
            $personnel = $repo->findOneBy(array('sourceCode' => $noIndividu));
            
            $repo = $this->em->getRepository('Application\Entity\Db\Intervenant');
            $intervenant = $repo->findOneBy(array('sourceCode' => $noIndividu));
            
            $user->setPersonnel($personnel)
                    ->setIntervenant($intervenant);
        }
    }
}