#!/bin/bash -x
unzip -o sources/INSEE/StockUniteLegale_utf8.zip -d extract/
unzip -o sources/INSEE/StockEtablissement_utf8.zip -d extract/
#On prend uniquement les colonnes qui nous intéressent
rm -rf prepare/StockUniteLegalePrepared.csv
awk -vFPAT='([^,]*)|("[^"]+\")' -vOFS=, '{if($1=="siren" || ($33=="O" && $21=="A")) print $1 "," $21 "," $22 "," $23 "," $24 "," $25 "," $26 "," $27 "," $16 "," $33 "," $13 "," substr($1,0,2)}' extract/StockUniteLegale_utf8.csv | tee -a prepare/StockUniteLegalePrepared.csv
#On nettoie les anciens fichiers d'import
rm -rf import/*.csv
#On éclate les fichiers en XX fichiers en regroupant par les deux premiers numéros du SIREN
FILENAME=prepare/StockUniteLegalePrepared.csv
PARAM1=`awk -F"," '{print $12}' $FILENAME |sed s/\"//g |uniq` #On récupére la liste des XX numéros de fichier

for i in $PARAM1                            # pour chaque "mot"
do
   HDR=$(head -1 $FILENAME)   #On récupére l'entête du fichier pour l'injecté dans chaque fichier
   #Uniquement si c'est un numérique
   if [[ $i = +([0-9]) ]]
   then
     echo $HDR > import/$i.csv    # On écrit dans le fichier les entêtes de colonnes
     grep ^$i $FILENAME >>import/$i.csv  # recherche toutes les lignes qui commencent par $i
   fi

done
#Création du tar.gz
cd import/
tar zcvf employeur.tar.gz *.csv
#Nettoyage des csv
rm -rf *.csv
#Fin du script