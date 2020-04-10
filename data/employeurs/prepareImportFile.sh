#!/bin/bash -x
#https://sites.google.com/site/diezone/awk-1/bou
unzip -o sources/INSEE/StockUniteLegale_utf8.zip -d extract/
unzip -o sources/INSEE/StockEtablissement_utf8.zip -d extract/
awk -vFPAT='([^,]*)|("[^"]+\")' -vOFS=, '{if($1=="siren" || ($33=="O" && $21=="A")) print $1 "," $21 "," $22 "," $23 "," $24 "," $25 "," $26 "," $27 "," $16 "," $33}' extract/StockUniteLegale_utf8.csv | tee -a prepare/StockUniteLegalePrepared.csv
cp prepare/StockUniteLegalePrepared.csv import/employeurs-import.csv



"cp StockUniteLegale.csv ../import/employeurs-import.csv",//On déplace le fichier dans le dossier d'importation
"cd $osedir/data/employeurs/",
        "pwd",
        "./prepareImportcd ../File.sh",
        //"unzip -o StockUniteLegale_utf8.zip -d ../../extract/",//On dezippe
        //"unzip -o StockEtablissement_utf8.zip -d ../../extract/",//On dézippe
        //"cd ../../extract/",
        //"awk -vFPAT='([^,]*)|(\"[^\"]+\")' -vOFS=, '{print $1 \",\" $21 \",\" $22 \",\" $23 \",\" $24 \",\" $25 \",\" $26 \",\" $27 \",\" $33 > \"try.csv\"}' StockUniteLegale_utf8.csv",//On garde uniquement les colonnes nécessaires
        //"cd ../prepare",
        //"sed -i.bak \"/,C,/d\" StockUniteLegale.csv",//On supprime les unités fermées
        //"sed -i.bak \"/,N$/d\" StockUniteLegale.csv",//On supprimer les unités non employeurs
        //"cp StockUniteLegale.csv ../import/employeurs-import.csv",//On déplace le fichier dans le dossier d'importation