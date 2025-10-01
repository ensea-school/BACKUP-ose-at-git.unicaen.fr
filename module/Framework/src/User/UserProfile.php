<?php

namespace Framework\User;

class UserProfile implements UserProfileInterface
{
    private null|int|string $id          = null;
    private ?string         $displayName = null;
    private array           $context     = [];

    const PRIVILEGE_GUEST = 'guest';
    const PRIVILEGE_USER  = 'user';



    public function __construct(int|string|null $id = null, ?string $displayName = null)
    {
        if (null !== $id){
            $this->id = $id;
        }
        if (null !== $displayName){
            $this->displayName = $displayName;
        }
    }



    public function getId(): null|int|string
    {
        return $this->id;
    }



    public function setId(null|int|string $id): void
    {
        $this->id = $id;
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
