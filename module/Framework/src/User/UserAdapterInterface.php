<?php

namespace Framework\User;

interface UserAdapterInterface
{

    /**
     * Retourne l'utilisateur courant
     */
    public function getUser(): ?UserInterface;



    /**
     * Détermine si l'usurpation est activée ou non pour l'utilisateur courant
     */
    public function isUsurpationEnabled(): bool;



    /**
     * Détermine s'il y a une ursurpation en cours
     */
    public function isUsurpationEnCours(): bool;



    /**
     * Retourne la liste des profils de l'utilisateur courant
     *
     * @return array|UserProfile[]
     */
    public function getProfiles(): array;



    /**
     * Retourne l'ID de profil par défaut pour l'utilisateur courant
     * Si NULL, le premier profil de la liste sera sélectionné
     *
     * @return int|string|null
     */
    public function getProfileDefaultId(): null|int|string;



    /**
     * Retourne la liste des privilèges pour l'utilisateur courant
     *
     * @return array|string[]
     */
    public function getPrivileges(?UserProfileInterface $profile): array;



    /**
     * Méthode permettant de déclencher des actions après un changement de profil
     */
    public function onBeforeProfileChange(): void;



    /**
     * Méthode permettant de déclencher des actions avant un changement de profil
     */
    public function onAfterProfileChange(?UserProfile $newProfile): void;
}