# Modifier l'adresse principale d'un agent

## Introduction

Cette méthode vous permet de modifier l'adresse principale existante d'un agent :

Différents types d'adresses existent dans Siham, cette méthode traite uniquement l'adresse principal de l'agent (TA01). Pour
information, voici la liste des codes types adresses de SIHAM :

| Type d'adresse                    | Code adresse SIHAM |
|-----------------------------------| ------------------ |
| Adresse résidence principale      | TA01               |
| Adresse résidence secondaire      | TA02               |
| Adresse fiscale                   | TA03               |
| Adresse de repli                  | TA04               |
| Adresse de retraite               | TA05               |

Il ne peut y avoir qu'une seule adresse principale active pour un agent dans SIHAM (date debut < date du jour < date fin).

## Méthode et Paramétres

Nom de la méthode : recupererListeAgents($params)

Paramètre(s) :

$params : Array contenant les clés d'entrées suivantes

| Nom de la clé     | Type    | Desc                                                                                  |
|-------------------|---------|---------------------------------------------------------------------------------------|
| codeEtablissement | String  | Code sihame de l'établissement (nomenclature établissement)     |
| bisTer            | String  | Code siham du complément de voie (nomenclature siham)  |
| natureVoie        | String  | Code siham du type de voie (nomenclature siham)      |
| noVoie            | Integer | Numéro de la voie                |
| compementAdresse  | String  | Complément d'adresse                                                               |
| codePostal        | Integer | Code postal de l'adresse                  |
| ville             | Boolean |  Nom de la commune de l'adresse                       |
| codePays          | String  | Code siham du pays de l'adresse postle (nomenclature siham) |
| dateDebut         | Date    | Date de début au format YYY-MM-DD d'utilisation de l'adresse |
| dateFin           | Date    | Date de fin au format YYY-MM-DD  d'utilisation de l'adresse |
| matricule         | String  | Matricule de l'agent dont il faut modifier l'adresse  |

Retour :

Retourne True|False selon le succes de la modification. Un exception de type SihamException peut être levée en cas d'erreur de
l'API.

