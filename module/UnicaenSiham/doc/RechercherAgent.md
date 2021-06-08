# Récupérer un liste d'agent

## Introduction

Cette méthode vous permet de rechercher un agent via les critères suivants :

* codeEtablissement
* Nom usuel
* Nom patronymique
* Prénom
* dateNaissance
* codeNIRSsCle

L'ensemble des ces critères de recherche sont cumulables.

## Méthode et Paramétres

Nom de la méthode : recupererListeAgents($params)

Paramètre(s) :

$params : Array contenant les clés d'entrées suivantes

| Nom de la clé          | Type    | Desc                                                                                  |
|------------------------|---------|---------------------------------------------------------------------------------------|
| nomUsuel               | String  | Nom de famille usuel de l'agent (Possibilité d'utiliser un opérateur de type LIKE %)  |
| nomPatronymique        | String  | Nom de naissance de l'agent (Possibilité d'utiliser un opérateur de type LIKE %)      |
| prenom                 | String  | Prénom de l'agent (Possibilité d'utiliser un opérateur de type LIKE %)                |
| numeroInsee            | String  | Numéro INSEE de l'agent                                                               |
| dateObservation        | Date    | Date au format YYYY-MM-DD à partir de laquelle on recherche un agent                  |
| temEnseignantChercheur | Boolean | Paramétre pour filter sur uniquement les enseignants chercheurs                       |
| temEtat                | Boolean |  Paramètre pour filter uniquement sur les enseignants permanents                      |

Retour :

Retourn un ArrayCollection d'[Agent](../src/UnicaenSiham/Entity/Agent.php).

