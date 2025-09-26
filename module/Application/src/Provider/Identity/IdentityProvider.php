<?php

namespace Application\Provider\Identity;

use Application\Entity\Db\Affectation;
use Application\Entity\Db\Role;
use Application\Service\Traits\ContextServiceAwareTrait;
use BjyAuthorize\Provider\Identity\ProviderInterface as IdentityProviderInterface;
use Intervenant\Service\IntervenantServiceAwareTrait;
use UnicaenApp\HostLocalization\HostLocalizationAwareTrait;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenApp\Traits\SessionContainerTrait;
use UnicaenAuthentification\Provider\Identity\ChainableProvider;
use UnicaenAuthentification\Provider\Identity\ChainEvent;

/**
 * Classe chargée de fournir les rôles que possède l'identité authentifiée.
 *
 */
class IdentityProvider implements ChainableProvider, IdentityProviderInterface
{
    use EntityManagerAwareTrait;
    use SessionContainerTrait;
    use IntervenantServiceAwareTrait;
    use ContextServiceAwareTrait;
    use HostLocalizationAwareTrait;

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



    public function clearIdentityRoles()
    {
        $this->identityRoles = null;
    }



    /**
     * {@inheritDoc}
     */
    public function getIdentityRoles()
    {
        if (!$this->identityRoles) {
            $filter = $this->getEntityManager()->getFilters()->enable('historique');
            $filter->init([
                              Role::class,
                              Affectation::class,
                          ]);

            $this->identityRoles = ['guest' => 'guest'];

            $inEtablissement = $this->getHostLocalization()->inEtablissement();

            /**
             * Rôles que possède l'utilisateur dans la base de données.
             */
            if ($utilisateur = $this->getServiceContext()->getUtilisateur()) {
                $this->identityRoles = ['user' => 'user'];
                $affectations        = $this->getEntityManager()->getRepository(Affectation::class)->findBy(['utilisateur' => $utilisateur]);
                foreach ($affectations as $affectation) {
                    /* @var $affectation Affectation */
                    $role = $affectation->getRole();
                    try {
                        if ($role->estNonHistorise() && ($inEtablissement || $role->isAccessibleExterieur())) {
                            $roleId = $role->getCode();
                            if ($structure = $affectation->getStructure()) {
                                $roleId .= '-' . $structure->getSourceCode();
                            }
                            $this->identityRoles[] = $roleId;
                        }
                    } catch (\Exception $e) {
                        // on ignore les affectations dont les rôles ont été supprimés
                    }
                }
            }

            /**
             * Rôle lié au statut de l'intervenant
             */
            if ($intervenant = $this->getServiceContext()->getIntervenant()) {
                $this->identityRoles[$intervenant->getStatut()->getRoleId()] = $intervenant->getStatut()->getRoleId();
            }
        }

        return $this->identityRoles;
    }
}
