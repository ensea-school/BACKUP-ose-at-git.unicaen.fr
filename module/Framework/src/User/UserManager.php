<?php

namespace Framework\User;

use Framework\Application\Session;
use UnicaenAuthentification\Service\UserContext;

class UserManager
{
    private const SESSION_USER        = 'user-manager/user';
    private const SESSION_PROFILE     = 'user-manager/profile';
    private const SESSION_PROFILES    = 'user-manager/profiles';
    private const SESSION_PRIVILEGES  = 'user-manager/privileges';
    private const SESSION_OLD_USER_ID = 'user-manager/old-user-id';



    public function __construct(
        private readonly Session     $session,
        private readonly UserContext $userContext
    )
    {
    }



    public function login(): void
    {
        $user = $this->userContext->getDbUser();
        $this->session->set(self::SESSION_USER, $user);
        $this->updateProfiles();
    }



    public function logout(): void
    {
        $this->session->set(self::SESSION_USER, null);
        $this->updateProfiles();
    }



    public function updateProfiles(): void
    {
        $roles    = $this->userContext->getSelectableIdentityRoles();
        $profiles = [];
        foreach ($roles as $role) {
            $profile = new UserProfile();
            $profile->setId(count($profiles)+1);
            $profile->setCode($role->getRoleId());
            $profile->setDisplayName($role->getRoleName());

            $profile->setContext('role', $role->getDbRole());
            $profile->setContext('structure', $role->getStructure());
            $profiles[$profile->getId()] = $profile;
        }

        if (empty($profiles) && $this->isConnected()) {
            $profile = new UserProfile();
            $profile->setId(1);
            $profile->setCode(UserProfile::PRIVILEGE_USER);
            $profile->setDisplayName('Authentifié(e)');
            $profiles[$profile->getId()] = $profile;
        }

        $this->session->set(self::SESSION_PROFILES, $profiles);

        $this->updateCurrentProfile();
    }



    public function updateCurrentProfile(): void
    {
        $role = $this->userContext->getSelectedIdentityRole();

        $profiles = $this->getProfiles();
        if (1 === count($profiles)) {
            $this->session->set(self::SESSION_PROFILE, current($profiles));
            return;
        }
        foreach ($profiles as $profile) {
            if ($role->getRoleId() === $profile->getCode()) {
                $this->session->set(self::SESSION_PROFILE, $profile);
                return;
            }
        }
        $this->session->set(self::SESSION_PROFILE, null);

        $this->updatePrivileges();
    }



    public function updatePrivileges(): void
    {
        $privileges = [];

        if ($this->isConnected()) {
            $privileges[] = UserProfile::PRIVILEGE_USER;
            $role         = $this->userContext->getSelectedIdentityRole();
            if ($role && $rp = $role->getPrivileges()) {
                foreach ($rp as $privilege) {
                    $privileges[] = $privilege;
                }
            }

        } else {
            $privileges[] = UserProfile::PRIVILEGE_GUEST;
        }

        $this->session->set(self::SESSION_PRIVILEGES, $privileges);
    }



    public function getCurrent(): ?UserInterface
    {
        return $this->session->get(self::SESSION_USER, null);
    }



    public function detectChanges(): void
    {
        $newId = $this->userContext->getDbUser()?->getId();

        $oldId = $this->session->get(self::SESSION_OLD_USER_ID, null);
        if ($oldId !== $newId) {
            $this->session->set(self::SESSION_OLD_USER_ID, $newId);
            if ($newId !== null) {
                $this->login();
            } else {
                $this->logout();
            }
        }else{
            // Changement du profil courant
            $role = $this->userContext->getSelectedIdentityRole();
            $profile = $this->getCurrentProfile();
            if ($role?->getRoleId() !== $profile?->getCode()) {
                $this->updateCurrentProfile();
            }
        }
    }



    public function getCurrentProfile(): ?UserProfile
    {
        return $this->session->get(self::SESSION_PROFILE, null);
    }



    public function setCurrentProfile(?UserProfileInterface $profile): void
    {
        // contrôle
        $profiles = $this->getProfiles();
        $found    = false;
        foreach ($profiles as $p) {
            if ($profile === $p) {
                $found = true;
            }
        }
        if (!$found) {
            throw new \Exception("You aren't authorized to take this profile");
        }

        // Assignation
        $this->session->set(self::SESSION_PROFILE, $profile);
        $this->updatePrivileges();
    }



    /**
     * @return array|UserProfileInterface[]
     */
    public function getProfiles(): array
    {
        return $this->session->get(self::SESSION_PROFILES, []);
    }



    public function isConnected(): bool
    {
        return null !== $this->getCurrent();
    }



    /**
     * @return array|string[]
     */
    public function getPrivileges(): array
    {
        return $this->session->get(self::SESSION_PRIVILEGES);
    }

}