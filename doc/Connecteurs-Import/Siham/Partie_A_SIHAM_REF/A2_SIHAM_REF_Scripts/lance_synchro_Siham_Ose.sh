#!/bin/bash

# Pour recuperer les variables d environnement utilisateur HARPINST - version oracle
source /home/harpinst/.profile

#-------------------------------------------------------------------------
# EXTRACTION DONNEES RH POUR ALIMENTER OSE
# APPLI OSE   : gestion des Heures d'enseignement (remplacement de Geisha)
# 
# Entrée / Pré-requis : Requêtes sur SIHAM (sib) via DBLINK  
# Sortie : tables UM_PAYS, UM_DEPARTEMENT... synchronisées en insert/update
#
# - v1.0 17/11/20 MYP : preparation V14 organisee en vue de la V15
# - v2.0 12/03/21 MYP : V15
#--------------------------------------------------------------------------

#Environnement ORACLE
DIRAPPLI=/data/appli/ose/Siham_Ose
DIRSHELL=/data/appli/ose/cron
ORACLE_HOME=$1 ; export ORACLE_HOME
ORACLE_SID=$2  ; export ORACLE_SID
ORACLE_USER=ose ; export ORACLE_USER
ORACLE_PASS=##A_PERSONNALISER## ; export ORACLE_PASS

# variables locales
dirlog=log
fichier=log_synchro_Siham_$2.log
fichierold=log_synchro_Siham_$2.log.old
fichier_trace_sql=trace_sql_synchro_Siham_Ose.lst
TRAITEMENT=SYNCHRO_SIHAM-$2

#adresse mail
mailexploit=##A_PERSONNALISER_MAIL1@univ.fr##,##A_PERSONNALISER_MAIL2@univ.fr##

#
cd $DIRAPPLI
#
#recopie le log pour garder une copie de la précédente exécution
mv $DIRSHELL/$dirlog/$fichier $DIRSHELL/$dirlog/$fichierold
echo "===================== Debut synchro $TRAITEMENT ======================" 
echo "===================== Debut synchro $TRAITEMENT ======================" > $dirlog/$fichier
date >> $DIRSHELL/$dirlog/$fichier

#================= LANCEMENT SCRIPT SQL ==============================================
#Lancement de sqlplus 
$ORACLE_HOME/bin/sqlplus $ORACLE_USER/$ORACLE_PASS << EOF
@$DIRAPPLI/lance_synchro_Siham_Ose_referentiel.sql
EOF
echo "-- Apres synchro Referentiel Ose."
echo "-- Apres synchro Referentiel Ose." >> $DIRSHELL/$dirlog/$fichier
date >> $DIRSHELL/$dirlog/$fichier
cd $DIRAPPLI


#================= VERIF ERREUR ORACLE et/ou MAIL FINAL ==========================
echo "-------- Vérification si les req sql ont généré une erreur ORA --------------"  >> $DIRSHELL/$dirlog/$fichier
	grep 'ORA-' $DIRAPPLI/$fichier_trace_sql  >> $DIRAPPLI/fichier_trace_siham.trc
   	NOMBRE=`wc -l $DIRAPPLI/fichier_trace_siham.trc | awk '{print $1}'`

	echo $TRAITEMENT||" - check ORA erreurs " 
   	if [ $NOMBRE -gt 0 ] ; then
		# Envoi un mail aux responsables de l'exploit avec le contenu fichier de trace
		#
		echo $TRAITEMENT||" - KO ORA Err" 
      	mail -s "crontab oseinst : SYNCHRO_$2 : Attention erreur ORA dans un fichier extrait !" -a $DIRAPPLI/$fichier_trace_sql -b $mailexploit $mailexploit < $DIRSHELL/$dirlog/$fichier
		mail -s "crontab oseinst : SYNCHRO_$2 : Attention erreur ORA dans un fichier extrait !" -a $DIRAPPLI/$fichier_trace_sql -b $1 $1 < $DIRSHELL/$dirlog/$fichier
	else
	    echo  " SYNCHRO_OseProse - OK " 
        mail -s "crontab oseinst : $TRAITEMENT : Execution terminee." -a $DIRAPPLI/$fichier_trace_sql -b $mailexploit $mailexploit < $DIRSHELL/$dirlog/$fichier
		mail -s "crontab oseinst : $TRAITEMENT : Execution terminee." -a $DIRAPPLI/$fichier_trace_sql -b $1 $1 < $DIRSHELL/$dirlog/$fichier
   	fi
	#Suppression des fichiers de trace d'execution
	rm $DIRAPPLI/fichier_trace_siham.trc
echo "-------- Fin : envoi mail effectué -------------"  >> $DIRSHELL/$dirlog/$fichier	
exit
