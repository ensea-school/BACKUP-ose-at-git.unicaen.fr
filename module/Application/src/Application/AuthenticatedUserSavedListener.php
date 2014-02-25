<?php

namespace Application;

use Zend\EventManager\Event;
use UnicaenAuth\Event\Listener\AuthenticatedUserSaved;

/**
 * Renseigne les relations 'intervenant' et 'personnel' avant que l'objet soit persisté.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AuthenticatedUserSavedListener extends AuthenticatedUserSaved
{
    /**
     * Renseigne les relations 'intervenant' et 'personnel' avant que l'objet soit persisté.
     * 
     * @param Event $e
     */
    public function onUserAuthenticatedPrePersist(Event $e)
    {
        $user       = $e->getParam('entity');     /* @var $user       \ZfcUser\Entity\UserInterface */
        $ldapPeople = $e->getParam('ldapPeople'); /* @var $ldapPeople \UnicaenApp\Entity\Ldap\People */
        
        if ($user instanceof User) {
            $repo = $this->em->getRepository('Application\Entity\Db\Personnel');
            $personnel = $repo->findOneBy(array('sourceCode' => $ldapPeople->getSupannEmpId()));
            
            $repo = $this->em->getRepository('Application\Entity\Db\Intervenant');
            $intervenant = $repo->findOneBy(array('sourceCode' => $ldapPeople->getSupannEmpId()));
            
            $user->setPersonnel($personnel)
                    ->setIntervenant($intervenant);
        }
    }
}