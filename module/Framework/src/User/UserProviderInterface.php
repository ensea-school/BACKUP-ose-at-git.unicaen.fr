<?php

namespace Framework\User;

interface UserProviderInterface
{

    public function getUser(): ?UserInterface;



    /**
     * @return array|UserProfile[]
     */
    public function getProfiles(): array;



    /**
     * @return array|string[]
     */
    public function getPrivileges(?UserProfileInterface $profile): array;



    public function onProfileChange(?UserProfile $newProfile);
}