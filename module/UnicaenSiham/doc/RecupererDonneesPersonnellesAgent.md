# Récupérer les données personnelles d'un agent

## Introduction

Cette méthode vous permet de récupérer les données personnelles d'un agent via son matricule :

* Code établissement
* dateFinObservation
* dateObservation
* listeMatricules

La liste des matricules est obligatoire au minimum.

## Méthode et Paramétres

Nom de la méthode : recupererDonneesPersonnellesAgent($params)

Paramètre(s) :

$params : Array contenant les clés d'entrées suivantes

| Nom de la clé      |  Type    | Desc                                                            |
|--------------------|----------|-----------------------------------------------------------------|
| codeEtablissement  | String   | Code sihame de l'établissement (nomenclature établissement)     |
| dateFinObservation | Date     | Date au format YYYY-MM-DD de fin de validité maximum des données personnelles        |
| dateObservation    | String   | Date de début de validité minimum des données personnelles      |
| listeMatricules    | Array    | Tableau contenant la liste des matricules des agents recherchés |

Retour :

Retourn un [Agent](../src/UnicaenSiham/Entity/Agent.php).

