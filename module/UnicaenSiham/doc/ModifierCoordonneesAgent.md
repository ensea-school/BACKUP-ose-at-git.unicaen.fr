# Modifier les coordonnes (Téléphones et Emails) d'un agent

## Introduction

Cette méthode vous permet de modifier l'email personnel, l'email professionnelle, téléphone personnel et le téléphone professionnel d'un agent.

Différents types de téléphone et email existent dans SIHAM, cette méthode traite uniquement les typologies SIHAM suivantes :

| Type de coordonnées               | 
|-----------------------------------|
| Téléphone fixe professionnel      |
| Téléphone fixe personnel          |
| Téléphone portable professionnel  |
| Téléphone portable personnel      |
| Email professionnel               |
| Email personnel                   |

Il ne peut y avoir qu'une seule entrée par type de coordonnées dans SIHAM.

## Méthode et Paramétres

Nom de la méthode : **modifierCoordonneesAgent($params, $type)**

Paramètre(s) :

**$params** : Array contenant les clés d'entrées suivantes

| Nom de la clé     | Type    | Desc                                                                                  |
|-------------------|---------|---------------------------------------------------------------------------------------|
| matricule         | String  | Matricule de l'agent dont il faut modifier les coordonnées                            |
| numero            | String  | Email ou numéro de téléphone                                                          |
| dateDebut         | Date    | Date de début au format YYY-MM-DD d'utilisation de l'adresse                          |

**$type** : Code du type de coordonnées à modifier ou ajouter. Les codes sont récupérables en appelant les constants de la class Siham. Voici la liste des coordonnées modifiables :

Type de coordonnées               | Code numero SIHAM  | Constante de class
|-----------------------------------|--------------------|-----------------------------------
| Téléphone fixe professionnel      | TPR                | Siham::SIHAM_CODE_TYPOLOGIE_FIXE_PRO
| Téléphone fixe personnel          | TPE                | Siham::SIHAM_CODE_TYPOLOGIE_FIXE_PERSO
| Téléphone portable professionnel  | PPR                | Siham::SIHAM_CODE_TYPOLOGIE_PORTABLE_PRO
| Téléphone portable personnel      | PPE                | Siham::SIHAM_CODE_TYPOLOGIE_PORTABLE_PERSO
| Email professionnel               | MPR                | Siham::SIHAM_CODE_TYPOLOGIE_EMAIL_PRO
| Email personnel                   | MPE                | Siham::SIHAM_CODE_TYPOLOGIE_EMAIL_PERSO

Retour :

Retourne True|False selon le succes de la modification. Un exception de type SihamException peut être levée en cas d'erreur de l'API.

