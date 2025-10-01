<?php

namespace Framework\User;

interface UserProfileInterface
{
    public function getId(): null|int|string;



    public function getDisplayName(): ?string;



    public function getContext(): mixed;
}