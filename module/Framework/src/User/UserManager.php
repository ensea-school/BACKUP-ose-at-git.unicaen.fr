<?php

namespace Framework\User;

use Framework\Application\Session;
use UnicaenApp\Traits\SessionContainerTrait;

class UserManager
{
    private const SESSION_PROFILE     = 'user-manager/profile';
    private const SESSION_PRIVILEGES  = 'user-manager/privileges';
    private const SESSION_OLD_USER_ID = 'user-manager/old-user-id';

    private UserProviderInterface $userProvider;

    private ?UserInterface $user = null;

    /** @var array|UserProfileInterface[] */
    private array $profiles = [];

    use SessionContainerTrait;

    public function __construct(
        private readonly Session $session,
    )
    {
    }



    public function getUserProvider(): UserProviderInterface
    {
        return $this->userProvider;
    }



    public function setUserProvider(UserProviderInterface $userProvider): UserManager
    {
        $this->userProvider = $userProvider;
        return $this;
    }



    public function getUser(): ?UserInterface
    {
        return $this->user;
    }



    public function setUser(?UserInterface $user): void
    {
        $oldUserId = $this->sessionGet(self::SESSION_OLD_USER_ID);
        $changed   = ($oldUserId !== $this->user?->getId()) || ($this->user?->getId() !== $user?->getId());

        if ($changed) {
            $this->user = $user;
            $this->sessionSet(self::SESSION_OLD_USER_ID, $this->user?->getId());

            $this->loadProfiles();
        }
    }



    /**
     * @return array|UserProfileInterface[]
     */
    public function getProfiles(): array
    {
        return $this->profiles;
    }



    public function getProfile(): ?UserProfile
    {
        $profileId = $this->sessionGet(self::SESSION_PROFILE);
        if ($profileId !== null && array_key_exists($profileId, $this->profiles)) {
            return $this->profiles[$profileId];
        } else {
            return null;
        }
    }



    public function setProfile(null|UserProfileInterface|int|string $profile): void
    {
        $lastProfileId = $this->sessionGet(self::SESSION_PROFILE);
        $profileId     = null;
        if (is_int($profile) || is_string($profile)) {
            $profileId = $profile;

            // contrôle
            $profiles = $this->getProfiles();
            if (!array_key_exists($profileId, $profiles)) {
                throw new \Exception("You aren't authorized to take this profile");
            }
        } elseif ($profile instanceof UserProfileInterface) {
            $profileId = $profile->getId();

            // contrôle
            $profiles = $this->getProfiles();
            if (($profiles[$profileId] ?? null) !== $profile) {
                throw new \Exception("You aren't authorized to take this profile");
            }
        }

        // Assignation
        if ($lastProfileId !== $profileId) {
            $this->sessionSet(self::SESSION_PROFILE, $profileId);
            $this->userProvider->onProfileChange($this->getProfile());
            $this->loadPrivileges();
        }
    }



    /**
     * @return array|string[]
     */
    public function getPrivileges(): array
    {
        return $this->sessionGet(self::SESSION_PRIVILEGES);
    }



    public function isConnected(): bool
    {
        return null !== $this->getUser();
    }



    protected function loadProfiles(): void
    {
        $this->profiles = [];

        $profiles = $this->userProvider->getProfiles();
        foreach ($profiles as $profile) {
            $this->profiles[$profile->getId()] = $profile;
        }
        if (empty($this->profiles)) {
            if ($this->isConnected()) { // au moins un profil authentifié si rien n'est fourni
                $profile                           = new UserProfile(UserProfile::PRIVILEGE_USER, 'Authentifié(e)');
                $this->profiles[$profile->getId()] = $profile;
            } else {
                // $profile = new UserProfile(UserProfile::PRIVILEGE_GUEST, 'Connexion');
                // $this->profiles[$profile->getId()] = $profile;
            }
        }

        $this->loadProfile();

    }



    protected function loadProfile(): void
    {
        $profiles  = $this->getProfiles();
        $profileId = $this->sessionGet(self::SESSION_PROFILE);
        if (empty($profiles)) {
            $this->setProfile(null);
        } else {
            if (!array_key_exists($profileId, $profiles)) {
                $this->setProfile(current($profiles));
            }
        }
    }



    protected function loadPrivileges(): void
    {
        $privileges = $this->userProvider->getPrivileges($this->getProfile());
        if ($this->isConnected()) {
            $privileges[] = UserProfile::PRIVILEGE_USER;
        } else {
            $privileges[] = UserProfile::PRIVILEGE_GUEST;
        }

        $this->sessionSet(self::SESSION_PRIVILEGES, $privileges);
    }



    private function sessionSet(string $key, mixed $value): void
    {
        $this->getSessionContainer()->offsetSet($key, $value);
    }



    private function sessionGet(string $key): mixed
    {
        return $this->getSessionContainer()->offsetGet($key);
    }

}