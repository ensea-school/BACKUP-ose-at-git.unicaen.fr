# Employeurs

## Introduction

L'objectif de cette documentation est d'expliquer comment alimenter la table employeur à partir des fichiers INSEE.
Chaque mois Caen vous fournira une archive qu'il faudra déposer dans /data/employeur/import et lancer ensuite la commande ./bin/ose update-employeur

A noter, que vous pourrez tout à fait alimenter la table employeur via une autre source, à vous dans ce cas de mettre en place votre processus d'alimentation (script, import csv etc...)

## Structuration de la table employeur

La liste des champs suivants sont obligatoires pour un bon fonctionnement : 
* ```raison_sociale``` : La raison social juridique de l'entité
* ```nom_commercial``` : La dénomination commerciale de l'entité
* ```SIREN``` : Le SIREN de l'entité
* ```critere_recherche``` : la concaténation espacée de la raison social, du nom commercial et du SIREN
* ```source_code``` : code unique
* ```source_id``` : id de la source ose

## Générer les données d'import à partir des données INSEE

Il faut se rendre sur le site data.gouv.fr : https://www.data.gouv.fr/fr/datasets/base-sirene-des-entreprises-et-de-leurs-etablissements-siren-siret/

Récupérer les archives suivantes sur le site : 
* Fichier StockEtablissement
* Fichier StockUniteLegale

Il faut déposer ces deux archives directement dans /data/employeur/source/INSEE

Puis il faut lancer le script shell ```prepareImportFile.sh``` dispo dans /data/employeur.

Ce script vous mettra à disposition un archive ```employeur.tar.gz``` dans le répertoire /data/employeur/import prête à être importée via la ligne de commande ```./bin/ose update-employeur```