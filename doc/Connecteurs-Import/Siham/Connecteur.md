# Connecteur SIHAM

Le connecteur SIHAM permet de synchroniser en import :

[PARTIE A/ SIHAM_REF](Partie_A_SIHAM_REF) : des tables de référentiel

* les pays
* les départements
* les voiries
* les structures (composantes)
* les corps et les grades

[PARTIE B/ SIHAM_INTERV](Partie_B_SIHAM_INTERV) : les intervenants existant dans SIHAM

* Les intervenants vacataires et permanents affectés à l'université
* non fourni : les affectations de recherche (à développer si vous en avez l'utilité)

Le code des scripts et des vues qui vous sont fournies ci-dessous ne représente qu'un exemple. Il vous revient de les adapter
afin que vous retrouviez dans OSE les données dont vous avez besoin. Lors de la 1ere installation : installer et mettre au
point la partie [A/ SIHAM_REF](Partie_A_SIHAM_REF) (synchro des tables de référentiel), puis ensuite la
partie [B/ SIHAM_INTERV](Partie_B_SIHAM_INTERV) (synchro des intervenants).

## Particularité du connecteur Siham par rapport à Harpège :

Le connecteur Siham permet :

- d'extraire les données de Siham via un DLKINK OSE-SIHAM => pour alimenter des tables intermédiaires nommées
  OSE.UM_<nom table>
- de créer les connecteurs correspondants dans OSE : Vues OSE.SRC_<nom table> basées sur les tables OSE.UM_<nom table> =>
  utilisées ensuite lors de la détection des différences dans l'appli OSE

Chaque partie du connecteur (cf. A/ et B/ ci-dessus) comprends :

- un tableau suivi_deploiement_MPD_OSE.xlsx : qui précise les étapes de déploiement et de mise en place
- rép. MPD            : pour la création des tables intermédiaires, fonctions et procédures : A PERSONNALISER pour alimenter
  les tables OSE.UM_<nom table>
- rép. Scripts        : scripts (sql et pl-sql) pour lancer l'extraction Siham
- rép. Connecteur_Ose : vues OSE.SRC_<nom table> ou OSE.VM_<nom_table> basées sur tables OSE.UM_<nom table>

Le fait d'avoir des tables intermédiaires permet :

* de synchroniser les dossiers de 3 manières :
    - soit en mode MANUEL        : pour un ou quelques dossiers ciblés par leur matricule
    - soit en mode DIFFERENTIEL : pour les dossiers modifiés dans SIHAM depuis une date (grace à la table hr.zytd12 qui n
      existait pas avec Harpège)
    - soit en mode ACTIFS        : tous les dossiers actifs à une date voulue

* d'ajouter des contrôles intermédiaires :
    - par exemple sur les changements de statut (possibilité de basculer le mode de gestion : Statut unique ou multi-statut,
      en automatique ou manuel (détection bloquante avec validation DRH))
    - de relancer plusieurs fois une synchro partielle en journée sans impacter l'appli (test de maj d'un dossier dans Siham,
      test d'évolution de règles à appliquer dans les scripts par la DSIN)

# Detail du connecteur Pré-requis techniques

## Mise en place du DbLink

- Le lien avec SIHAM se fait au moyen d un DbLink que vous devrez créer. Dans cet exemple, le DbLink s'appellera `SIHAM`
  ou `SIHAM_TEST`.

- Le lien avec le LDAP (base intermédiaire BI chez nous) se fait au moyen d un DbLink, utilisé pour vérifier si les vacataires
  ont validé leur compte informatique professionnel. Si vous n en avez pas l'utilité, la récupération et le test de ces
  informations peuvent être commentés. Dans cet exemple, le DbLink s'appellera `BI`.

## Création de la vue SRC_HARPEGE_STRUCTURE_CODES

Pour SIHAM il n'est pas utile de gérer la vue intermédiaire `SRC_HARPEGE_STRUCTURE_CODES`. Le niveau d'UO pour remonter la
composante/direction est géré dans le code de la procédure de synchro des structures : OSE.UM_SYNCHRO_STRUCTURE

## Déclaration du connecteur dans OSE

OSE doit lister toutes ses sources de données. Il faut donc y ajouter Siham :

```sql
BEGIN
  unicaen_import.add_source('Siham', 'Siham');
  commit;
END;
```

La liste des sources de OSE est accessible ici (URL pointant vers l instance de démonstration de OSE) :
[https://\<votre ose\>/import/sources](https://ose.unicaen.fr/demo/import/sources)

# Detail du connecteur Partie_A_SIHAM_REF

DSIN Install : Télécharger le connecteur SIHAM-OSE au complet puis suivez les étapes ci-dessous :

**!!  le code source est fourni à titre d'exemple et à adapter suivant vos règles internes de codage ou de saisie**

**Prêter une attention particulière aux mentions ##A_PERSONNALISER_CHOIX_SIHAM## et ##A_PERSONNALISER_CHOIX_OSE## : vous devez
adapter avec vos propres règles RH/Siham/Ose**

cf documentation plus détaillée :

## 1ere install : purge des tables pays / départements / voiries / grades / corps

Les pays sont enregistrés dans la table PAYS (table intermédiaire UM_PAYS). Idem DEPARTEMENT/UM_DEPARTEMENT, VOIRIE/UM_VOIRIE,
STRUCTURE/UM_STRUCTURE, CORPS/UM_CORPS, GRADE/UM_GRADE

Si vous venez d'installer OSE, alors l'application est livrée avec un jeu de données par défaut, parmi lesquelles une liste
des pays. Avant d utiliser votre propre liste issue de Siham, vous devez impérativement vider ces tables de référentiel, sans
quoi vous vous retrouveriez avec des erreurs d'import pour cause de doublons.

```sql
DELETE FROM PAYS;
DELETE FROM DEPARTEMENT;
DELETE FROM VOIRIE;
DELETE FROM GRADE;
DELETE FROM CORPS;
```

# Les Structures :

Les structures dans OSE matérialisent des composantes ou des départements. Il n y a qu un seul niveau de structure dans OSE (
au dessous de la structure mère). Les structures portent entres autres l'offre de formation, les intervenants mais aussi les
affectations (droits d accès).

Dans cette table, on importe les structures SIHAM de niveau composante/direction (à paramétrer) et la structure Université (
UNIV) de niveau 1.

# Les corps et des grades

Pour chaque corps nous pouvons avoir plusieurs grades. Pour les titulaires Siham : nous rapatrions les codes corps et grades
de Siham Pour les contractuels ou hébergés : La DRH souhaitant avoir des informations plus précises sur la population, nous
utilisons la zone libellé grade de la fiche intervenant pour renseigner le statut Siham. Nous rapatrions le code STATUT Siham
dans la table CORPS et un code grade en dur STSV/STSP (Sous-statut pour préciser Vacataire ou Permanent).

## Install

Suivre les étapes du
tableau [A_SIHAM_REF_Suivi_deploiement_MPD_OSE.xlsx](Partie_A_SIHAM_REF/A_V15_SIHAM_REF_Suivi_deploiement_MPD_OSE.xlsx)

## 1ere install

Création des tables intermédiaires de référentiel OSE.UM_PAYS / OSE.UM_DEPARTEMENT / OSE.UM_VOIRIE / OSE.UM_STRUCTURE /
OSE.UM_CORPS / OSE.UM_GRADE

Dans le sous-répertoire [Partie_A_SIHAM_REF\A1_SIHAM_REF_MPD](Partie_A_SIHAM_REF/A1_SIHAM_REF_MPD) :
lancer [A_1T_OSE_create_table_v2.0_utf8.sql](Partie_A_SIHAM_REF/A1_SIHAM_REF_MPD/A_1_T_OSE_create_table_v2.0_utf8.sql)

# Install nouvelle version :

vérifier et lancer le ou les alter_tables :

- Si connecteur SIHAM-OSE antérieur à la version Connecteur_SIHAM_OSE_v1.1_2019-12 (cf. Redmine de Caen - Projet OSE-SIHAM -
  DMS - DOCUMENTS MONTPELLIER)
  alors lancer en premier le script alter
  table [Partie_A_SIHAM_REF\A1_SIHAM_REF_MPD\01_T_OSE_alter_tables_v1.4_a_v1.8.sql](Partie_A_SIHAM_REF\A1_SIHAM_REF_MPD\A_1_T_OSE_alter_tables_v1.4_a_v1.8.sql)

- Dans tous les cas, pour OSE V15 : Dans le
  sous-répertoire [Partie_A_SIHAM_REF\A1_SIHAM_REF_MPD](Partie_A_SIHAM_REF\A1_SIHAM_REF_MPD) : lancer
  A_1T_OSE_alter_tables_v2.0_utf8.sql

## Alimentation des tables intermédiaires de référentiel OSE.UM_PAYS / OSE.UM_DEPARTEMENT / OSE.UM_VOIRIE

Dans le sous-répertoire [Partie_A_SIHAM_REF\A1_SIHAM_REF_MPD](Partie_A_SIHAM_REF/A1_SIHAM_REF_MPD)

- adapter à vos codages et règles le code source des fonctions et procédures (A_2_F_OSE_function...
  A_3_P_OSE_procedure_insert_tables_src...)
- lancer la création de ces fonctions et procédures

Dans le sous-répertoire [Partie_A_SIHAM_REF\A2_SIHAM_REF_Scripts](Partie_A_SIHAM_REF/A2_SIHAM_REF_Scripts)

- lancer [A_recompiler_fonction_proc.sql](Partie_A_SIHAM_REF/A2_SIHAM_REF_Scripts/A_recompiler_fonction_proc.sql)
- [lance_synchro_Siham_Ose_referentiel.sql](Partie_A_SIHAM_REF/A2_SIHAM_REF_Scripts/lance_synchro_Siham_Ose_referentiel.sql)

## Detail du connecteur Partie_A_SIHAM_REF vues SRC_<nom_table>

Dans le sous-répertoire [Partie_A_SIHAM_REF\A3_SIHAM_REF_Connecteur_OSE](Partie_A_SIHAM_REF/A3_SIHAM_REF_Connecteur_OSE)

- lancer les scripts .sql de chaque vue source
- cela crée les vues sources
  [SRC_PAYS](Partie_A_SIHAM_REF/A3_SIHAM_REF_Connecteur_OSE/src_pays.sql)
  [SRC_DEPARTEMENT](Partie_A_SIHAM_REF/A3_SIHAM_REF_Connecteur_OSE/src_departement.sql)
  [SRC_VOIRIE](Partie_A_SIHAM_REF/A3_SIHAM_REF_Connecteur_OSE/src_voirie.sql)
  [SRC_STRUCTURE](Partie_A_SIHAM_REF/A3_SIHAM_REF_Connecteur_OSE/src_structure.sql)
  [SRC_CORPS](Partie_A_SIHAM_REF/A3_SIHAM_REF_Connecteur_OSE/src_corps.sql)
  [SRC_GRADE](Partie_A_SIHAM_REF/A3_SIHAM_REF_Connecteur_OSE/src_grade.sql)
  basées sur les tables intermédiaires UM_<nom_table>
- [Activez-les, puis tentez les synchronisations](../activer-synchroniser.md).

# Detail du connecteur Partie_B_SIHAM_INTERV :

DSIN Install : Suivre les étapes du
tableau [B_SIHAM_REF_Suivi_deploiement_MPD_OSE.xlsx](Partie_B_SIHAM_INTERV/B_V15_SIHAM_INTERV_Suivi_deploiement_MPD_OSE.xlsx)

**!!  le code source est fourni à titre d'exemple et à adapter suivant vos règles internes de codage ou de saisie**

**Prêter une attention particulière aux mentions ##A_PERSONNALISER_CHOIX_SIHAM## et ##A_PERSONNALISER_CHOIX_OSE## : vous devez
adapter avec vos propres règles RH/Siham/Ose**

cf documentation plus détaillée :

## 1ere install : Création des tables intermédiaires utiles pour la synchro des intervenants

- OSE.UM_OREC.. (infos complémentaires sur les vacataires - logiciel local OREC - qui ne sont pas saisies dans Siham)
- OSE.UM_INTERVENANT, OSE.UM_ADRESSE_INTERVENANT et des tables de travail pour tracer les synchro

Dans le sous-répertoire [Partie_B_SIHAM_INTERV\B1_SIHAM_INTERV_MPD](Partie_B_SIHAM_INTERV/B1_SIHAM_INTERV_MPD)

- PERSONNALISER, puis lancer B_0b_OREC-createTables_utf8....sql : Ces tables doivent être créées, utiles pour la synchro des
  vacataires vous pouvez les alimenter depuis vos données locales ou chunter toutes les références à ces tables (dans un 2ème
  temps)
- PERSONNALISER, puis lancer B_1T_OSE_create_table_v..._utf8.sql :
  prêter une attention particulière au paramétrage MANUEL de UM_STATUT_INTERVENANT suivant les statuts que vous devez gérer
  dans Ose pour cela
  cf. : [MOP_parametrer_correspondances_Statuts_siham_Ose](Doc_et_MOP/MOP_parametrer_correspondances_SIHAM-OSE.docx)

## Install nouvelle version : vérifier et lancer le alter_tables

Dans le sous-répertoire [Partie_B_SIHAM_INTERV\B1_SIHAM_INTERV_MPD](Partie_B_SIHAM_INTERV/B1_SIHAM_INTERV_MPD) : lancer
B_1T_OSE_alter_tables_v.._utf8.sql

**!! Avec la V15, le découpage des adresses et les multi-statuts ont entrainé de nombreuses modifications dans les tables**

Bien suivre les étapes dans l'ordre du script et vérifier le contenu des nouveaux champs, se fier aux commentaires

## Alimentation des tables intermédiaires pour synchro des intervenants

Dans le sous-répertoire [Partie_B_SIHAM_INTERV\B1_SIHAM_INTERV_MPD](Partie_B_SIHAM_INTERV/B1_SIHAM_INTERV_MPD)

- PERSONNALISER, adapter à vos codages et règles le code source (B_2F_OSE_function... B_3P_OSE_procedures_diverses... )
- lancer la création de ces fonctions et procédures
- procédures importantes :
    - PERSONNALISER + lancer B_4P_OSE_procedure_SELECT_intervenant : sélection des dossiers à synchroniser (
      MANUEL/DIFF/ACTIFS) => insert dans OSE.UM_TRANSFERT_INDIVIDU (flag TODO)
    - PERSONNALISER + lancer B_5P_OSE_procedure_INSERT_intervenant_v2.2.sql : depuis OSE.UM_TRANSFERT_INDIVIDU flag TODO =>
      insert dans OSE.UM_INTERVENANT cf. documentation plus détaillée :

Dans le sous-répertoire [Partie_B_SIHAM_INTERV\B2_SIHAM_INTERV_Scripts](Partie_B_SIHAM_INTERV/B2_SIHAM_INTERV_Scripts)

- lancer [B_recompiler_fonction_proc.sql](Partie_B_SIHAM_INTERV/B2_SIHAM_INTERV_Scripts/B_recompiler_fonction_proc.sql)
- PERSONNALISER l'année et le mode de synchro (MANUEL/DIFF/ACTIFS) + lancer lance_synchro_Siham_Ose_2020.sql

**la 1ere fois lancer en MANUEL sur quelques matricules pour mettre au point votre personnalisation, puis tous les dossiers
ACTIFS, puis DIFF en routine**

cf documentation plus détaillée :

## Detail du connecteur Partie_B_SIHAM_INTERV vue MV_INTERVENANT

Dans le
sous-répertoire [Partie_B_SIHAM_INTERV\B3_SIHAM_INTERV_Connecteur_OSE](Partie_B_SIHAM_INTERV/B3_SIHAM_INTERV_Connecteur_OSE) :

- PERSONNALISER si besoin les tests en dur (paramétrage des STATUTS, UO) + lancer mv_intervenant.sql

- cela crée la vue matérialisée VM [MV_INTERVENANT](Partie_B_SIHAM_INTERV/B3_SIHAM_INTERV_Connecteur_OSE/mv_intervenant.sql)
  Compte tenu de la masse des données et pour des raisons d'optimisation aussi bien que de lisibilité, la vue source va
  s'appuyer sur une vue matérialisée qui va lui "préparer" le travail. Les données en sortie sont préparées pour être
  exploitées ensuite par la vue source [SRC_INTERVENANT](../Générique/SRC_INTERVENANT.sql)

**!! Pour SRC_INTERVENANT préserver le code livré par Caen + voir la documentation pour la mettre en place et paramétrer les
filtres dans OSE :
[SRC_INTERVENANT](../Générique/SRC_INTERVENANT.sql).**

## Cas des intervenants ne se générant dans les tables intermédiaires :

### Cas des intervenants ne se générant pas dans UM_INTERVENANT :

- repérer le ou les matricules, les ajouter dans UM_TRANSFERT_FORCE et lancer en sql le
  script [lance_synchro_Siham_Ose_2020_MANUEL.sql](Partie_B_SIHAM_INTERV/B2_SIHAM_INTERV_Scripts/lance_synchro_Siham_Ose_2020_MANUEL.sql)
  cf. [MOP_parametrer_correspondances_SIHAM-OSE.docx](Doc_et_MOP/MOP_parametrer_correspondances_SIHAM-OSE.docx)

### Cas des intervenants générés dans UM_INTERVENANT et non détecté en ajout/modification dans OSE :

- vérifier si les zones obligatoires pour Ose sont renseignées (structure_id, statut_id ne doivent pas être null,....)
  Voir doc de Caen pour les zones concernées.

### Cas de changement de statut erroné, ne se reportant pas correctement dans UM_INTERVENANT :

- vérifier les tables de trace de la synchro (UM_TRANSFERT_INDIVIDU : peut être est-il considéré comme hors périmètre Ose ? ou
  synchro pas terminée)
  et UM_SYNCHRO_A_VALIDER : peut être en attente de validation manuelle du nouveau statut ou HOSE (Hors statut géré par Ose)
    + les fichiers de log : log_synchro_Siham_Ose.log + OSETEST_trace_synchro_Siham_Ose_sql.lst cf.
      MOP_parametrer_et_suivre_changements_statut.docx
        + [MOP_suivi_et_traces_synchro.docx](Doc_et_MOP/MOP_suivi_et_traces_synchro.docx)

Pour plus de détail voir dans le rép. [Doc_et_MOP](Doc_et_MOP) les différents MOP précisant les recherches d'exploitation.