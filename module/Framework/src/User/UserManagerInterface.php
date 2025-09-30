<?php

namespace Framework\User;

interface UserManagerInterface
{
    public function getCurrent(): ?UserInterface;



    public function getPrivileges(): array;



    public function getCurrentProfiles(): array;



    public function isConnected(): bool;
}