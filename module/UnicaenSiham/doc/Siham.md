# UnicaenSiham

## Introduction

Il s'agit d'une bibliothèque UNICAEN, permettant de connecter une application avec le SI RH Siham :

* [Rechercher un agent](./RechercherAgent.md)
* [Récupérer une liste d'agent](./RecupererListeAgent.md)
* [Récupérer les données personnelles d'un agent](./RecupererDonneesPersonnellesAgent.md)
* Modifier les données personnelles d'un agent (Nom, prénom, date de naissance, INSEE etc...)
* Modifier les coordonnées bancaires d'un agent
* [Modifier l'adresse principale d'un agent](./ModifierAdressePrincipaleAgent.md)
* Modifier les téléphone pro / perso d'un agent
* Modifier les emails pro / perso d'un agent
* Prise en charge d'un agent
* Renouvellement d'un agent
* Récupérer les nomenclatures (types de statut, types de contrat, liste des modalités de service etc...)
* Récupérer la liste des unités organisationnelles (UO)

## Configuration

Si vous utilisez le module siham unicaen, vous devez renseigner un certain nombre de paramètres nécessaires au bon
fonctionnement de celui-ci.

Vous pouvez récupérer un modèle de fichier de configuration dans config/unicaen-siham.global.php.dist.

Dans ce fichier de configuration il faudra préciser :

* Les identifiant et mot de passe des APIs Siham
* Les différentes URL des APIs Siham
* La version du client SOAP utilisée

[Activez-les, puis tentez les synchronisations](../activer-synchroniser.md).

