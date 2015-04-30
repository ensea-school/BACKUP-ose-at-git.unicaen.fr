<?php
namespace Application\Provider\Identity;

use Application\Acl;
use Application\Entity\Db\Affectation;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenAuth\Provider\Identity\ChainableProvider;
use UnicaenAuth\Provider\Identity\ChainEvent;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * Classe chargée de fournir les rôles que possède l'identité authentifiée.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class IdentityProvider implements ServiceLocatorAwareInterface, ChainableProvider, EntityManagerAwareInterface
{
    use ServiceLocatorAwareTrait;
    use EntityManagerAwareTrait;

    /**
     * @var array
     */
    protected $roles;

    /**
     * {@inheritDoc}
     */
    public function injectIdentityRoles(ChainEvent $event)
    {
        $event->addRoles($this->getIdentityRoles());
    }

    /**
     * {@inheritDoc}
     */
    public function getIdentityRoles()
    {
        if (null === $this->roles) {
            $this->getEntityManager()->getFilters()->enable('historique')->init(
                [
                    'Application\Entity\Db\Role',
                    'Application\Entity\Db\Affectation',
                ],
                new \DateTime
            );

            $this->roles = [];

            $serviceAuthUserContext = $this->getServiceLocator()->get('AuthUserContext');
            /* @var $serviceAuthUserContext \UnicaenAuth\Service\UserContext */
            $utilisateur = $serviceAuthUserContext->getDbUser();
            /* @var $utilisateur \Application\Entity\Db\Utilisateur */

            if (! $utilisateur) return $this->roles; // pas connecté

            /**
             * Rôles que possède l'utilisateur dans la base de données.
             */
            if ($utilisateur->getPersonnel()) {
                foreach ($utilisateur->getPersonnel()->getAffectation() as $affectation) {
                    /* @var $affectation Affectation */
                    $roleId = $affectation->getRole()->getCode();
                    if ($structure = $affectation->getStructure()){
                        $roleId .= '-'.$structure->getSourceCode();
                    }
                    $this->roles[] = $roleId;
                }
            }

            /**
             * Rôle correspondant au type d'intervenant auquel appartient l'utilisateur
             */
            $intervenant = $utilisateur->getIntervenant();
            if ($intervenant){
                $this->roles[] = Acl\IntervenantRole::ROLE_ID;
            }
        }
        return $this->roles;
    }
}
