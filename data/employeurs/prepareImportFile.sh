#!/bin/bash -x
unzip -o sources/INSEE/StockUniteLegale_utf8.zip -d extract/
unzip -o sources/INSEE/StockEtablissement_utf8.zip -d extract/
awk -vFPAT='([^,]*)|("[^"]+\")' -vOFS=, '{if($1=="siren" || ($33=="O" && $21=="A")) print $1 "," $21 "," $22 "," $23 "," $24 "," $25 "," $26 "," $27 "," $16 "," $33 "," $13}' extract/StockUniteLegale_utf8.csv | tee -a prepare/StockUniteLegalePrepared.csv
cp prepare/StockUniteLegalePrepared.csv import/employeurs-import.csv

