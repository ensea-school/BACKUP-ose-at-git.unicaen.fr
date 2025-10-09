<?php

namespace Utilisateur\Entity\Db;

use Unicaen\Framework\User\Bcrypt;
use Unicaen\Framework\User\UserInterface;
use ZfcUser\Entity\UserInterface as ZfcUserInterface;
use UnicaenApp\Entity\UserInterface as UnicaenAppUserInterface;
use UnicaenVue\Axios\AxiosExtractorInterface;


class Utilisateur implements UserInterface, UnicaenAppUserInterface, ZfcUserInterface, AxiosExtractorInterface
{
    const APP_UTILISATEUR_ID = 1;

    protected ?int $id = null;

    protected ?string $code = null;

    protected ?string $username = null;

    protected ?string $email = null;

    protected ?string $displayName = null;

    protected ?string $password = null;

    protected int $state = 1;

    protected ?string $passwordResetToken = null;



    public function axiosDefinition(): array
    {
        return ['email', 'displayName'];
    }



    public function getId(): ?int
    {
        return $this->id;
    }



    public function setId($id): Utilisateur
    {
        $this->id = $id;
        return $this;
    }



    public function getCode(): ?string
    {
        return $this->code;
    }



    public function setCode(?string $code): Utilisateur
    {
        $this->code = $code;
        return $this;
    }



    public function getUsername(): ?string
    {
        return $this->username;
    }



    public function setUsername($username): Utilisateur
    {
        $this->username = $username;
        return $this;
    }



    public function getEmail(): ?string
    {
        return $this->email;
    }



    public function setEmail($email): Utilisateur
    {
        $this->email = $email;
        return $this;
    }



    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }



    public function setDisplayName($displayName): Utilisateur
    {
        $this->displayName = $displayName;
        return $this;
    }



    public function getState(): int
    {
        return $this->state;
    }



    public function setState($state): Utilisateur
    {
        $this->state = $state;
        return $this;
    }



    public function getPasswordResetToken(): ?string
    {
        return $this->passwordResetToken;
    }



    public function setPasswordResetToken(?string $passwordResetToken): Utilisateur
    {
        $this->passwordResetToken = $passwordResetToken;
        return $this;
    }



    public function getPassword(): ?string
    {
        return $this->password;
    }



    /**
     * Set password.
     *
     * @param string $password
     *
     * @return void
     */
    public function setPassword($password, $encrypt = false)
    {
        if ($encrypt) {
            $bcrypt   = new Bcrypt();
            $password = $bcrypt->create($password);
        }

        $this->setPasswordResetToken(null);
    }



    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    public function __toString(): string
    {
        return $this->getDisplayName();
    }

}
