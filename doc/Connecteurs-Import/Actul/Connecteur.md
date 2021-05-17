# Connecteur Actul+

Le connecteur Actul+ permet de synchtoniser dans OSE une offre de formation en provenance d'Actul+

Le connecteur fonctionne de la manière suivante :
* Des tables intermédiaires sont créées dans la base OSE
* Des vues sources spécifiques se basent sur les tables intermédiaires pour "présenter les données" à OSE. 
Un script PHP se connecte à la base de données Actul+, récupère les données nécessaires et les injecte dans les tables intermédiaires
* La synchro est déclenchée, mettant ainsi à jour OSE.

## 1. Création des tables intermédiaires

Le script SQL [creation-tables-intermediaires.sql](creation-tables-intermediaires.sql) est à exécuter dans votre base de données OSE.

Il va créer les tables et les vues dans lesquelles vont se déverser les données en provenance d'Actul et sur lesquelles s'appuieront les vues sources. 