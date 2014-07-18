<?php

namespace Application;

use UnicaenAuth\Event\Listener\AuthenticatedUserSavedAbstractListener as AbstractListener;
use UnicaenAuth\Event\UserAuthenticatedEvent;
use Application\Entity\Db\Utilisateur;
use Application\Service\Initializer\IntervenantServiceAwareInterface;
use Application\Service\Initializer\IntervenantServiceAwareTrait;

/**
 * Scrute l'événement déclenché juste avant que l'entité utilisateur ne soit persistée
 * pour renseigner les relations 'intervenant' et 'personnel'.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AuthenticatedUserSavedListener extends AbstractListener implements IntervenantServiceAwareInterface
{
    use IntervenantServiceAwareTrait;
    
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
            
            $repo = $this->getEntityManager()->getRepository('Application\Entity\Db\Personnel');
            $personnel = $repo->findOneBy(array('sourceCode' => $noIndividu));
            
            $repo = $this->getEntityManager()->getRepository('Application\Entity\Db\Intervenant');
            $intervenant = $repo->findOneBy(array('sourceCode' => $noIndividu));
//            
            if (!$intervenant) {
                // import de l'intervenant
                $intervenant = $this->getIntervenantService()->importer($noIndividu);
            }
            
            $user
                    ->setPersonnel($personnel)
                    ->setIntervenant($intervenant);
        }
    }
}