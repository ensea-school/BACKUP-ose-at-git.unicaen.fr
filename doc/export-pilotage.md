# Données utilisables par des outils de pilotage (Business Object, etc.)

## Vue Matérialisée MV_EXT_SERVICE

MV_EXT_SERVICE est une vue matérialisée. Elle est basée sur la vue v_export_service qui est également à l'origine du fichier 
CSV d'export CSV des services généré par OSE dans le menu "Services".

Les données d'une vue matérialisées sont stockées dans une table par Oracle. Leur mise à jour sefait manuellement.
Voici la commande à éxécuter pour faire cette mise à jour :

```sql
BEGIN
  DBMS_MVIEW.REFRESH('MV_EXT_SERVICE', 'C');
END;
```

Voici les colonnes qui la composent :

|Colonne                       |Description|
|--                            |--|
|ID                            |Identifiant unique de ligne|
|SERVICE_ID                    |==> SERVICE.ID|
|INTERVENANT_ID                |==> INTERVENANT.ID|
|STATUT_ID                     |==> STATUT.ID|
|TYPE_INTERVENANT_ID           |==> TYPE_INTERVENANT.ID|
|ANNEE_ID                      |==> ANNEE.ID|
|TYPE_VOLUME_HORAIRE_ID        |==> TYPE_VOLUME_HORAIRE.ID|
|ETAT_VOLUME_HORAIRE_ID        |==> ETAT_VOLUME_HORAIRE.ID|
|ETABLISSEMENT_ID              |==> ETABLISSEMENT.ID|
|STRUCTURE_AFF_ID              |==> STRUCTURE.ID Composante d'affectation|
|STRUCTURE_ENS_ID              |==> STRUCTURE.ID Composante d'enseignement|
|GROUPE_TYPE_FORMATION_ID      |==> GROUPE_TYPE_FORMATION.ID Grand type de formation (License, etc.)|
|TYPE_FORMATION_ID             |==> TYPE_FORMATION.ID Type de formation (License pro, etc.)|
|NIVEAU_FORMATION_ID           |Identifiant du niveau de formation (lié à aucune table)|
|ETAPE_ID                      |==> ETAPE.ID|
|ELEMENT_PEDAGOGIQUE_ID        |==> ELEMENT_PEDAGOGIQUE.ID|
|PERIODE_ID                    |==> PERIODE.ID|
|TYPE_INTERVENTION_ID          |==> TYPE_INTERVENTION.ID|
|FONCTION_REFERENTIEL_ID       |==> FONCTION_REFERENTIEL.ID Uniquement s'il s'agit de référentiel|
|INTERVENANT_DISCIPLINE_ID     |==> DISCIPLINE.ID Discipline de l'intervenant|
|ELEMENT_DISCIPLINE_ID         |==> DISCIPLINE.ID Discipline de l'enseignement|
|MOTIF_NON_PAIEMENT_ID         |==> MOTIF_NON_PAIEMENT.ID Si heure non payable|
|TYPE_ETAT                     |Prévu/réalisé, saisi/validé|
|SERVICE_DATE_MODIFICATION     |Dernière date dfe modification du service|
|INTERVENANT_CODE              |Code de l'intervenant|
|INTERVENANT_NOM               |Nom de l'intervenant|
|INTERVENANT_DATE_NAISSANCE    |Date de naissance|
|INTERVENANT_STATUT_LIBELLE    |Libellé de son statut|
|INTERVENANT_TYPE_CODE         |P=permanent, E=extérieur(vacataire)|
|INTERVENANT_TYPE_LIBELLE      |Libellé du type d'intervenant|
|INTERVENANT_GRADE_CODE        |Code du grade (si renseigné)|
|INTERVENANT_GRADE_LIBELLE     |Libellé du grade (si renseigné)|
|INTERVENANT_DISCIPLINE_CODE   |Code de la discipline de l'intervenant|
|INTERVENANT_DISCIPLINE_LIBELLE|Libellé de la discipline de l'intervenant|
|SERVICE_STRUCTURE_AFF_LIBELLE |Libellé de la composante éventuelle de l'intervenant|
|SERVICE_STRUCTURE_ENS_LIBELLE |Libellé de la composante éventuelle d'enseignement|
|ETABLISSEMENT_LIBELLE         |Libellé de l'établissement (utile si enseignement pris dans d'autres établissements|
|GROUPE_TYPE_FORMATION_LIBELLE |Libellé du grand type de formation|
|TYPE_FORMATION_LIBELLE        |Libellé du type de formation|
|ETAPE_NIVEAU                  |Niveau éventuel de la formation (1,2,3, etc.)|
|ETAPE_CODE                    |Code de l'étape (formation)|
|ETAPE_LIBELLE                 |Libellé de l'étape (formation)|
|ELEMENT_CODE                  |Code de l'élément (enseignement)|
|ELEMENT_LIBELLE               |Libellé de l'élément (enseignement)|
|ELEMENT_DISCIPLINE_CODE       |Code de la discipline de l'enseignement|
|ELEMENT_DISCIPLINE_LIBELLE    |Libellé de la discipline de l'enseignement|
|FONCTION_REFERENTIEL_LIBELLE  |Libellé de la fonction référentielle éventuelle|
|ELEMENT_TAUX_FI               |% FI de l'enseignement (entre 0 et 1)|
|ELEMENT_TAUX_FC               |% FC de l'enseignement (entre 0 et 1)|
|ELEMENT_TAUX_FA               |% FA de l'enseignement (entre 0 et 1)|
|SERVICE_REF_FORMATION         |Libellé précisant ce à quoi se rattache la fonction référentielle|
|COMMENTAIRES                  |Commentaires éventuel si référentiel|
|PERIODE_LIBELLE               |Libellé de période si enseignement|
|ELEMENT_PONDERATION_COMPL     |Majoration des heures complémentaires (modulation heures complémentaires)|
|ELEMENT_SOURCE_LIBELLE        |Libellé de la source de données de l'enseignement|
|HEURES                        |Heures|
|HEURES_REF                    |Heures de référentiel|
|HEURES_NON_PAYEES             |Heures non payables|
|MOTIF_NON_PAIEMENT            |Motif de non paiement éventuel|
|SERVICE_STATUTAIRE            |Heures de service statutaire de l'intervenant|
|SERVICE_DU_MODIFIE            |Heures de service dû modifié de l'intervenant|
|SERVICE_FI                    |HETD de service en FI|
|SERVICE_FA                    |HETD de service en FA|
|SERVICE_FC                    |HETD de service en FC|
|SERVICE_REFERENTIEL           |HETD de service en référentiel|
|HEURES_COMPL_FI               |HETD Complémentaires en FI|
|HEURES_COMPL_FA               |HETD Complémentaires en FA|
|HEURES_COMPL_FC               |HETD Complémentaires en FC|
|HEURES_COMPL_FC_MAJOREES      |HETD Complémentaires en FC majorées au titre de la prime FC D714.60 du code de l'éducation|
|HEURES_COMPL_REFERENTIEL      |HETD Complémentaires en référentiel|
|TOTAL                         |HETD total|
|SOLDE                         |Solde en HETD de l'intervenant|
|DATE_CLOTURE_REALISE          |Date éventuelle de clôture des services par l'intervenant|

## Liens entre les services et les paiements

Le liens entre les services et les paiements n'est pertinent que pour les services réalisés validés.

La jointure se fait de la manière suivante (exemple de requête) :
```sql
SELECT 
  * 
FROM 
  mv_ext_service s
  JOIN tbl_paiement p ON COALESCE(p.service_id,0) = COALESCE(s.service_id,0) AND COALESCE(p.service_referentiel_id,0) = COALESCE(s.service_referentiel_id,0)
  LEFT JOIN mise_en_paiement mep ON mep.id = p.mise_en_paiement_id
```
Attention : la vue matérialisée des services est détaillée par volume horaire.
Il se peut donc qu'il y ai plusieurs lignes par service. Or les mises en paiements sont faites sur la base des services.
Donc une même mise en paiement pourra apparaitre plusieurs fois ici.




# Tableaux de bord

Les tableaux de bord peuvent être exploités à des fins de pilotage.
Ce sont des tables mises à jour automatiquement par OSE et rafraichies globalement via la commande
./bin/ose calcul-tableaux-bord.

En voici la description :

## Table TBL_AGREMENT

Gestion des agréments

|Colonne         |Description                                                                        |
|----------------|-----------------------------------------------------------------------------------|
|ID              |                                                                                   |
|ANNEE_ID        |==> ANNEE.ID                                                                       |
|TYPE_AGREMENT_ID|==> TYPE_AGREMENT.ID                                                               |
|INTERVENANT_ID  |==> INTERVENANT.ID                                                                 |
|STRUCTURE_ID    |==> STRUCTURE.ID                                                                   |
|OBLIGATOIRE     |Témoin (1 ou 0) pour définir si l'agrément doit être demandé obligatoirement ou non|
|AGREMENT_ID     |==> AGREMENT.ID (ID de l'agrément si agréé)                                        |


## Table TBL_CHARGENS

Charges d'enseignement

|Colonne                 |Description                               |
|------------------------|------------------------------------------|
|ID                      |                                          |
|ANNEE_ID                |==> ANNEE.ID                              |
|NOEUD_ID                |==> NOEUD.ID                              |
|SCENARIO_ID             |==> SCENARIO.ID                           |
|TYPE_HEURES_ID          |==> TYPE_HEURES.ID                        |
|TYPE_INTERVENTION_ID    |==> TYPE_INTERVENTION.ID                  |
|ELEMENT_PEDAGOGIQUE_ID  |==> ELEMENT_PEDAGOGIQUE.ID                |
|ETAPE_ID                |==> ETAPE.ID                              |
|ETAPE_ENS_ID            |==> ETAPE.ID                              |
|STRUCTURE_ID            |==> STRUCTURE.ID                          |
|GROUPE_TYPE_FORMATION_ID|==> GROUPE_TYPE_FORMATION.ID              |
|OUVERTURE               |Seuil d'ouverture                         |
|DEDOUBLEMENT            |Seuil de dédoublement                     |
|ASSIDUITE               |Taux d'assiduité en % (entre 0 et 1)      |
|EFFECTIF                |Effectifs                                 |
|HEURES_ENS              |Heures d'enseignement (charges par groupe)|
|GROUPES                 |Nombre de groupes calculé                 |
|HEURES                  |Heures réelles calculées                  |
|HETD                    |HETD réelles calculées                    |


## Table TBL_CHARGENS_SEUILS_DEF

Pré-calculs des seuils par défaut

|Colonne                 |Description                 |
|------------------------|----------------------------|
|ID                      |                            |
|ANNEE_ID                |==> ANNEE.ID                |
|SCENARIO_ID             |==> SCENARIO.ID             |
|STRUCTURE_ID            |==> STRUCTURE.ID            |
|GROUPE_TYPE_FORMATION_ID|==> GROUPE_TYPE_FORMATION.ID|
|TYPE_INTERVENTION_ID    |==> TYPE_INTERVENTION.ID    |
|DEDOUBLEMENT            |Seuil de dédoublement       |


## Table TBL_CLOTURE_REALISE

Clôture de saisie du service réalisé par les intervenants

| Colonne        | Description                                           |
|----------------|-------------------------------------------------------|
| ID             |                                                       |
| ANNEE_ID       | ==> ANNEE.ID                                          |
| INTERVENANT_ID | ==> INTERVENANT.ID                                    |
| HAS_CLOTURE    | Témoin (0 ou 1 : si la clôture est nécessaire ou non) |
| EST_CLOTURE    | Témoin (0 ou 1 : 1 si clôturé)                        |


## Table TBL_CONTRAT

Contrats de travail

| Colonne        |Description                                               |
|----------------|----------------------------------------------------------|
| ID             |                                                          |
| ANNEE_ID       |==> ANNEE.ID                                              |
| INTERVENANT_ID |==> INTERVENANT.ID                                        |
| ACTIF          |Témoin (0 ou 1 : 1 si l'intervenant doit avoir un contrat)|
| STRUCTURE_ID   |==> STRUCTURE.ID                                          |
| NBVH           |Nombre de volumes horaires contractualisables             |
| EDITE          |Nombre de volumes horaires contractualisés                |
| SIGNE          |Nombre de volumes horaires signés                         |


## Table TBL_DMEP_LIQUIDATION

Gestion budgétaire (enveloppes)

|Colonne          |Description          |
|-----------------|---------------------|
|ID               |                     |
|ANNEE_ID         |==> ANNEE.ID         |
|TYPE_RESSOURCE_ID|==> TYPE_RESSOURCE.ID|
|STRUCTURE_ID     |==> STRUCTURE.ID     |
|HEURES           |                     |


## Table TBL_DOSSIER

Données personnelles

| Colonne        | Description                              |
|----------------|------------------------------------------|
| ID             |                                          |
| ANNEE_ID       | ==> ANNEE.ID                             |
| INTERVENANT_ID | ==> INTERVENANT.ID                       |
| ACTIF          | 1 Si l'intervenant a un dossier, 0 sinon |
| DOSSIER_ID     | ==> INTERVENANT_DOSSIER.ID               |
| VALIDATION_ID  | ==> VALIDATION.ID                        |


## Table TBL_LIEN

Liens (pour les charges d'enseignement)

|Colonne         |Description                |
|----------------|---------------------------|
|ID              |                           |
|LIEN_ID         |==> LIEN.ID                |
|SCENARIO_ID     |==> SCENARIO.ID            |
|SCENARIO_LIEN_ID|==> SCENARIO_LIEN.ID       |
|NOEUD_SUP_ID    |==> NOEUD.ID               |
|NOEUD_INF_ID    |==> NOEUD.ID               |
|STRUCTURE_ID    |==> STRUCTURE.ID           |
|ACTIF           |Témoin (0 ou 1), 1 si actif|
|POIDS           |Poids (1 par défaut)       |
|CHOIX_MINIMUM   |Choix minimum              |
|CHOIX_MAXIMUM   |Choix maximum              |
|NB_CHOIX        |Nombre de choix            |
|TOTAL_POIDS     |Total de poids des fils    |
|MAX_POIDS       |Poids maximum pour les fils|


## Table TBL_PAIEMENT

Données liées aux paiements et demandes de mises en paiement

|Colonne                   |Description                        |
|--------------------------|-----------------------------------|
|ID                        |                                   |
|ANNEE_ID                  |==> ANNEE.ID                       |
|INTERVENANT_ID            |==> INTERVENANT.ID                 |
|STRUCTURE_ID              |==> STRUCTURE.ID                   |
|MISE_EN_PAIEMENT_ID       |==> MISE_EN_PAIEMENT.ID            |
|PERIODE_PAIEMENT_ID       |==> PERIODE.ID                     |
|HEURES_A_PAYER            |HETD à payer                       |
|HEURES_A_PAYER_POND       |HETD à payer (en %)                |
|HEURES_DEMANDEES          |HETD demandées                     |
|HEURES_PAYEES             |HETD payées                        |
|FORMULE_RES_SERVICE_ID    |==> FORMULE_RESULTAT_SERVICE.ID    |
|FORMULE_RES_SERVICE_REF_ID|==> FORMULE_RESULTAT_SERVICE_REF.ID|
|SERVICE_ID                |==> SERVICE.ID                     |
|SERVICE_REFERENTIEL_ID    |==> SERVICE_REFERENTIEL.ID         |


## Table TBL_PIECE_JOINTE

Pièces justificatives

|Colonne             |Description                         |
|--------------------|------------------------------------|
|ID                  |                                    |
|ANNEE_ID            |==> ANNEE.ID                        |
|TYPE_PIECE_JOINTE_ID|==> TYPE_PIECE_JOINTE.ID            |
|INTERVENANT_ID      |==> INTERVENANT.ID                  |
|DEMANDEE            |Témoin (1 si la PJ est demandée)    |
|FOURNIE             |Témoin (1 si la PJ est fournie)     |
|VALIDEE             |Témoin (1 si la PJ est validée)     |
|HEURES_POUR_SEUIL   |NB d'heures de seuil pour la demande|


## Table TBL_PIECE_JOINTE_DEMANDE

Pièces justificatives (demandes)

|Colonne             |Description             |
|--------------------|------------------------|
|ID                  |                        |
|ANNEE_ID            |==> ANNEE.ID            |
|TYPE_PIECE_JOINTE_ID|==> TYPE_PIECE_JOINTE.ID|
|INTERVENANT_ID      |==> INTERVENANT.ID      |
|HEURES_POUR_SEUIL   |                        |


## Table TBL_PIECE_JOINTE_FOURNIE

Pièces justificatives fournies

|Colonne             |Description             |
|--------------------|------------------------|
|ID                  |                        |
|ANNEE_ID            |==> ANNEE.ID            |
|TYPE_PIECE_JOINTE_ID|==> TYPE_PIECE_JOINTE.ID|
|INTERVENANT_ID      |==> INTERVENANT.ID      |
|VALIDATION_ID       |==> VALIDATION.ID       |
|FICHIER_ID          |==> FICHIER.ID          |
|PIECE_JOINTE_ID     |==> PIECE_JOINTE.ID     |


## Table TBL_SERVICE

Services d'ensiegnement

| Colonne                        | Description                                      |
|--------------------------------|--------------------------------------------------|
| ID                             |                                                  |
| ANNEE_ID                       | ==> ANNEE.ID                                     |
| INTERVENANT_ID                 | ==> INTERVENANT.ID                               |
| SERVICE                        | Témoin (1 si l'intervenant a du service)         |
| TYPE_VOLUME_HORAIRE_ID         | ==> TYPE_VOLUME_HORAIRE.ID                       |
| STRUCTURE_ID                   | ==> STRUCTURE.ID                                 |
| NBVH                           | Nombre de volumes horaires saisis                |
| VALIDE                         | Nombre de volumes horaires validés               |
| ELEMENT_PEDAGOGIQUE_ID         | ==> ELEMENT_PEDAGOGIQUE.ID                       |
| ELEMENT_PEDAGOGIQUE_PERIODE_ID | ==> PERIODE.ID                                   |
| ETAPE_ID                       | ==> ETAPE.ID                                     |
| ELEMENT_PEDAGOGIQUE_HISTO      |                                                  |
| ETAPE_HISTO                    | Témoin (1 si l'étape est supprimée)              |
| HAS_HEURES_MAUVAISE_PERIODE    | Témoin (1 si heures saisies au mauvais semestre) |
| SERVICE_ID                     | ==> SERVICE.ID                                   |
| INTERVENANT_STRUCTURE_ID       | ==> STRUCTURE.ID                                 |
| TYPE_INTERVENANT_ID            | ==> TYPE_INTERVENANT.ID                          |
| TYPE_INTERVENANT_CODE          |                                                  |
| TYPE_VOLUME_HORAIRE_CODE       |                                                  |
| HEURES                         | NB d'heures saisi                                |


## Table TBL_REFERENTIEL

Référentiel

| Colonne                   | Description                        |
|---------------------------|------------------------------------|
| ID                        |                                    |
| ANNEE_ID                  | ==> ANNEE.ID                       |
| INTERVENANT_ID            | ==> INTERVENANT.ID                 |
| ACTIF                     | Témoin (0 ou 1)                    |
| TYPE_VOLUME_HORAIRE_ID    | ==> TYPE_VOLUME_HORAIRE.ID         |
| STRUCTURE_ID              | ==> STRUCTURE.ID                   |
| NBVH                      | Nombre de volumes horaires         |
| VALIDE                    | Nombre de volumes horaires validés |
| INTERVENANT_STRUCTURE_ID  | ==> STRUCTURE.ID                   |
| SERVICE_REFERENTIEL_ID    | ==> SERVICE_REFERENTIEL.ID         |
| FONCTION_REFERENTIEL_ID   | ==> FONCTION_REFERENTIEL.ID        |
| TYPE_INTERVENANT_ID       | ==> TYPE_INTERVENANT.ID            |
| TYPE_INTERVENANT_CODE     | ==> TYPE_INTERVENANT.CODE          |                                   
| TYPE_VOLUME_HORAIRE_CODE  | ==> TYPE_VOLUME_HORAIRE.CODE       |       
| HEURES                    | Nombre d'heures concernées         |



## Table TBL_SERVICE_SAISIE

Service (pour alimenter le Workflow)

| Colonne                 |Description                            |
|-------------------------|---------------------------------------|
| ID                      |                                       |
| ANNEE_ID                |==> ANNEE.ID                           |
| INTERVENANT_ID          |==> INTERVENANT.ID                     |
| SERVICE                 |Témoin (0 ou 1)                        |
| REFERENTIEL             |Témoin (0 ou 1)                        |
| HEURES_SERVICE_PREV     |NB d'heures de service prévisionnel    |
| HEURES_REFERENTIEL_PREV |NB d'heures de référentiel prévisionnel|
| HEURES_SERVICE_REAL     |NB d'heures de service réalisé         |
| HEURES_REFERENTIEL_REAL |NB d'heures de référentiel réalisé     |


## Table TBL_VALIDATION_ENSEIGNEMENT

Suivi des validations de services

|Colonne               |Description                  |
|----------------------|-----------------------------|
|ID                    |                             |
|ANNEE_ID              |==> ANNEE.ID                 |
|INTERVENANT_ID        |==> INTERVENANT.ID           |
|STRUCTURE_ID          |==> STRUCTURE.ID             |
|TYPE_VOLUME_HORAIRE_ID|==> TYPE_VOLUME_HORAIRE.ID   |
|SERVICE_ID            |==> SERVICE.ID               |
|VALIDATION_ID         |==> VALIDATION.ID (Si validé)|
|VOLUME_HORAIRE_ID     |==> VOLUME_HORAIRE.ID        |
|AUTO_VALIDATION       |Témoin (0 ou 1)              |


## Table TBL_VALIDATION_REFERENTIEL

Suivi des validations du référentiel

|Colonne               |Description                  |
|----------------------|-----------------------------|
|ID                    |                             |
|ANNEE_ID              |==> ANNEE.ID                 |
|INTERVENANT_ID        |==> INTERVENANT.ID           |
|STRUCTURE_ID          |==> STRUCTURE.ID             |
|TYPE_VOLUME_HORAIRE_ID|==> TYPE_VOLUME_HORAIRE.ID   |
|SERVICE_REFERENTIEL_ID|==> SERVICE_REFERENTIEL.ID   |
|VALIDATION_ID         |==> VALIDATION.ID (Si validé)|
|VOLUME_HORAIRE_REF_ID |==> VOLUME_HORAIRE_REF.ID    |
|AUTO_VALIDATION       |Témoin (0 ou 1)              |


## Table TBL_WORKFLOW

Workflow (feuilles de routes : avancement par étape par intervenant et le cas échéant par composante)

| Colonne              | Description                                                      |
|----------------------|------------------------------------------------------------------|
| ID                   |                                                                  |
| INTERVENANT_ID       | ==> INTERVENANT.ID (Identifiant de l'intervenant concerné)       |
| ETAPE_ID             | ==> WF_ETAPE.ID (Identifiant de l'étape concernée)               |
| STRUCTURE_ID         | ==> STRUCTURE.ID (Eventuelle structure concernée)                |
| ATTEIGNABLE          | Témoin indiquant si l'étape est atteignable ou non               |
| REALISATION          | Pourcentage de réalisation de l'étape de Workflow (entre 0 et 1) |
| OBJECTIF             | Objectif de réalisation de l'étape de Workflow (entre 0 et 1)    |
| ANNEE_ID             | ==> ANNEE.ID                                                     |
| TYPE_INTERVENANT_ID  | ==> TYPE_INTERVENANT.ID                                          |
| TYPE_INTERVENANT_CODE | Code du type d'intevention (CM / TD / TP, etc)                   |
| ETAPE_CODE           | Code de l'étape du workflow concernée                            |
| STATUT_ID            | ==> STATUT.ID                                                    |