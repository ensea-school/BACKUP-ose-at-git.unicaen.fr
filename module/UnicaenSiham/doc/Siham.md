# UnicaenSiham

## Introduction

Il s'agit d'une bibliothèque UNICAEN, permettant de connecter une application avec le SI RH Siham :

* [Rechercher un agent](./RechercherAgent.md)
* [Récupérer une liste d'agent](./RecupererListeAgent.md)
* [Récupérer les données personnelles d'un agent](./RecupererDonneesPersonnellesAgent.md)
* Modifier les coordonnées bancaires d'un agent
* [Modifier l'adresse principale d'un agent](./ModifierAdressePrincipaleAgent.md)
* [Modifier coordonnées (téléphones et emails) d'un agent](./ModifierCoordonneesAgent.md)
* [Historiser les coordonnées (téléphones et emails) d'un agent](./HistoriserCoordonneesAgent.md)
* Prise en charge d'un agent
* Renouvellement d'un agent
* [Récupérer les nomenclatures (types de statut, types de contrat, liste des modalités de service etc...)](./RecupererNomenclatures.md)
* Récupérer la liste des unités organisationnelles (UO)

## Configuration

Si vous utilisez le module siham unicaen, vous devez renseigner un certain nombre de paramètres nécessaires au bon fonctionnement de celui-ci.

Vous pouvez récupérer un modèle de fichier de configuration dans config/unicaen-siham.global.php.dist.

Dans ce fichier de configuration il faudra préciser :

* Les identifiant et mot de passe des APIs Siham
* Les différentes URL des APIs Siham
* La version du client SOAP utilisée

[Activez-les, puis tentez les synchronisations](../activer-synchroniser.md).

