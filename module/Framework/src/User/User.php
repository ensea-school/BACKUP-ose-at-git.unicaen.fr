<?php

namespace Framework\User;

class User implements UserInterface
{

    private ?int    $id          = null;
    private ?string $displayName = null;



    public function getId(): ?int
    {
        return $this->id;
    }



    public function setId(?int $id): void
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


}