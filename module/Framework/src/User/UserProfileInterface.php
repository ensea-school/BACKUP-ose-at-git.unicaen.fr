<?php

namespace Framework\User;

interface UserProfileInterface
{
    public function getId(): ?int;



    public function getDisplayName(): ?string;



    public function getContext(): mixed;
}