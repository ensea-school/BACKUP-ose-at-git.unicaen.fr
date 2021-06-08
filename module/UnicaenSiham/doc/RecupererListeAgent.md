# Récupérer un liste d'agent

## Introduction

Cette méthode vous permet de récupérer une liste d'agent SIHAM à partir de plusieurs critères de recherche :

* Code établissement
* Nom usuel
* Nom patronymique
* Prénom
* Numéro INSEE
* Témoin enseignant chercheur : enseignant chercheur oui ou non
* Témoin ensegnant état : enseignant permanent oui ou non
* Date observation : permet de chercher des agents qu'à partir d'une certaine date

L'ensemble des ces critères de recherche sont cumulables.

## Méthode et Paramétres

Nom de la méthode : recupererListeAgents($params)

Paramètre(s) :

$params : Array contenant les clés d'entrées suivantes

| Nom de la clé          | Type    | Desc  |
|------------------------|---------|----------------------------------------------------------------------------------------|
| codeEtablissement      | String  | Code sihame de l'établissement (nomenclature établissement)                            |
| nomUsuel               | String   | Nom de famille usuel de l'agent (Possibilité d'utiliser un opérateur de type LIKE %)  |
| nomPatronymique        | String  | Nom de naissance de l'agent (Possibilité d'utiliser un opérateur de type LIKE %)       |
| prenom                 | String  | Prénom de l'agent (Possibilité d'utiliser un opérateur de type LIKE %)                 |
| numeroInsee            | String  | Numéro INSEE de l'agent                                                                |
| dateObservation        | Date    | Date à partir de laquelle on recherche un agent                                        |
| temEnseignantChercheur | Boolean | O pour Enseignant/Chercheur et N pour BIATSS                                           |
| temEtat                | Boolean | Critère de recherche par rapport à la date d'observation (voir tableau témoin état)    |  

Valeurs possibles pour le temEtat :

| Témoin état (temEtat)   | Desc                                                                                                  | 
|-------------------------|-------------------------------------------------------------------------------------------------------|
| A                       | Restitution de la liste des agents qui ont situation en vigueur à la date d'observation               |
| P                       | Restitution de la liste des agents qui ont situation en prévision par rapport à la date d'observation |
| I                       | Restitution de la liste des agents qui ont situation cloturée par rapport à la date d'observation     |

Retour :

Retourn un ArrayCollection d'[Agent](../src/UnicaenSiham/Entity/Agent.php).

