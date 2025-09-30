<?php

namespace Framework\User;

class UserProfile implements UserProfileInterface
{
    private ?int    $id          = null;
    private ?string $code        = null;
    private ?string $displayName = null;
    private array   $context     = [];

    const PRIVILEGE_GUEST = 'guest';
    const PRIVILEGE_USER = 'user';


    public function getId(): ?int
    {
        return $this->id;
    }



    public function setId(?int $id): void
    {
        $this->id = $id;
    }



    public function getCode(): ?string
    {
        return $this->code;
    }



    public function setCode(?string $code): void
    {
        $this->code = $code;
    }



    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }



    public function setDisplayName(?string $displayName): void
    {
        $this->displayName = $displayName;
    }



    public function setContext(string $name, mixed $value): void
    {
        $this->context[$name] = $value;
    }



    public function getContext(?string $name = null): mixed
    {
        if (empty($name)) {
            return $this->context;
        }
        if (!array_key_exists($name, $this->context)) {
            return null;
        }
        return $this->context[$name];
    }

}
