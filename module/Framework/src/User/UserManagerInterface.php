<?php

namespace Framework\User;

use Laminas\Permissions\Acl\Role\RoleInterface;

interface UserManagerInterface
{


    public function getUser(): ?UserInterface;



    public function getRole(): ?RoleInterface;



    public function setUser(?UserInterface $user): void;



    public function getProfiles(): array;



    public function getProfile(): ?UserProfileInterface;



    public function setProfile(null|UserProfileInterface|int|string $profile): void;



    public function isConnected(): bool;



    public function hasPrivilege(string $privilege): bool;



    public function getPrivileges(): array;
}