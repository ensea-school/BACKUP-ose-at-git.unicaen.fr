<?php

namespace Application\Provider\Identity;

use Application\Entity\Db\Affectation;
use Application\Entity\Db\Role;
use Application\Service\Traits\ContextAwareTrait;
use Application\Service\Traits\IntervenantAwareTrait;
use Application\Service\Traits\PersonnelAwareTrait;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenAuth\Provider\Identity\ChainableProvider;
use UnicaenAuth\Provider\Identity\ChainEvent;
use BjyAuthorize\Provider\Identity\ProviderInterface as IdentityProviderInterface;
use UnicaenApp\Traits\SessionContainerTrait;

/**
 * Classe chargée de fournir les rôles que possède l'identité authentifiée.
 *
 */
class IdentityProvider implements ChainableProvider, IdentityProviderInterface
{
    use EntityManagerAwareTrait;
    use SessionContainerTrait;
    use IntervenantAwareTrait;
    use PersonnelAwareTrait;
    use ContextAwareTrait;

    /**
     * @var array
     */
    private $identityRoles;



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
        if (!$this->identityRoles){
            $filter = $this->getEntityManager()->getFilters()->enable('historique');
            $filter->init([
                Role::class,
                Affectation::class,
            ]);

            $this->identityRoles = [];

            /**
             * Rôles que possède l'utilisateur dans la base de données.
             */
            if ($personnel = $this->getServiceContext()->getPersonnel()) {
                foreach ($personnel->getAffectation() as $affectation) {
                    /* @var $affectation Affectation */
                    $roleId = $affectation->getRole()->getCode();
                    if ($structure = $affectation->getStructure()) {
                        $roleId .= '-' . $structure->getSourceCode();
                    }
                    $this->identityRoles[] = $roleId;
                }
            }

            /**
             * Rôle lié au statut de l'intervenant
             */
            if ($intervenant = $this->getServiceContext()->getIntervenant()) {
                $this->identityRoles[] = $intervenant->getStatut()->getRoleId();
            }

        }

        return $this->identityRoles;
    }
}
