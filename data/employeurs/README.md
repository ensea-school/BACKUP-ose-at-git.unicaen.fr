https://www.data.gouv.fr/fr/datasets/base-sirene-des-entreprises-et-de-leurs-etablissements-siren-siret/#_

Process : 
- Attaquer le fichier le StockUniteLegale.csv pour charger les établissements
        - filtre :
            etatAdministratifUniteLegale = A
            caractereEmployeurUniteLegale = O
        - Datas : 
            denominationUniteLegale : Raison sociales
            nicSiegeUniteLegale : NIC du siege sociale
            siren : SIREN de l'unité légal
 
- Attaquer ensuite le fichier StockEtablissement.csv pour aller rechercher l'adresse principal

on choisit d'abord les colonnes nécessaires au traitement pour alléger le fichier : 

cut -d "," -f 1,21,22,24,33 StockUniteLegale_utf8.csv > StockUniteLegale_utf8_cut1.csv


