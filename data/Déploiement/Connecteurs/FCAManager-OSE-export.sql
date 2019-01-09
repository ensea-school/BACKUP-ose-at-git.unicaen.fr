-- Requêtes de déversmeent des informations de FCA Manager dans OSE

-- Auteur : Jérôme Gallot (jerome.gallot@unicaen.fr)

-- Pour les DBLINK, penser à modifier l'utilisateur qui peut lire les vues (ucbn_ose pour l'UNICAEN)

-- ##################################
-- Vue ETAPE
-- ##################################
-- MAJ 20/11/2017 : Utilisation de l'année universitaire de FCA Manager
-- MAJ 22/11/2017 : Commentaires pour mise à disposition
-- MAJ 24/11/2017 : Déversement de toutes les formations valides des années > 2017
-- MAJ 29/11/2017 : Correction domaine fonctionnel (manque un 'D' dans le code)
--                : Changement type formation -> forcer à null avant refonte tyoe_formation
-- ##################################
-- TODO : utiliser le domaine fonctionnel SIFAC indiqué sur une action au lieu d'un switch dans la requête SQL
--        Améliorer la remontée des niveaux/type formation
-- ##################################
DROP VIEW OSE_ETAPE;

CREATE VIEW OSE_ETAPE AS
(
SELECT
  -- Libellé de l'action
  acn.LIB_ACN AS Libelle,
  -- Le type de formation est par défaut à 82 = formation non diplômante
  '82' AS Z_TYPE_FORMATION_ID,
  -- Code Harpege de la structure
  ucl.COD_STR AS Z_STRUCTURE_ID,
 
  -- Niveau : correspond au type de formation/groupe_type_formation dans OSE
  -- Le type de formation est défini dans le champ 'Validé par' dans FCA
  CASE
    -- WHEN acv.COD_NIF_SOR_ACV=7 THEN 1 -- Niveau 1 = BAC+5
    -- WHEN acv.COD_NIF_SOR_ACV=8 THEN 2 -- Niveau 2 = BAC+3
    -- WHEN acv.COD_NIF_SOR_ACV=9 THEN 3 -- Niveau 3 = Bac+2
    -- Licence Pro
    WHEN 1=1 THEN NULL
    -- NULL permet d'afficher 'Autres'
    ELSE NULL
  END AS NIVEAU,
  -- Domaine fonctionnel
  CASE 
    WHEN acv.COD_NIF_SOR_ACV=7 THEN 'D101' -- Form init et cont licence
    WHEN acv.COD_NIF_SOR_ACV=8 THEN 'D102' -- Form init et cont master
    WHEN acv.COD_NIF_SOR_ACV=9 THEN 'D103' -- Form init et cont doct
    ELSE 'D1132'
  END AS Z_DOMAINE_FONCTIONNEL_ID,
  -- Code source de l'action (ne doit pas changer)
  'FCA-ACN-' ||ACN.ID AS SOURCE_CODE,
  -- Anné Universitaire de l'action
  auv.COD_ANU AS ANNEE_ID,
  -- Référence de l'action dans FCA Manager
  acn.COD_REF_COM_ACN AS CODE
  
  FROM ACTION acn
  LEFT JOIN ACTIVITE acv ON (acv.ID=acn.COD_ACV)
  LEFT JOIN ucbn_composante_ldap@apoprod ucl ON (ucl.COD_CMP=acv.COD_CGE)
  LEFT JOIN ANNEE_UNI auv on (acn.COD_ANU_ACN = auv.COD_ANU)
  WHERE
    -- Les formations ne sont prise en compte qu'à partir de l'année universitaire 2017
    auv.COD_ANU >= 2017
    -- La source doit être FCA MANAGER
    AND acn.COD_LOG_SRC = 4
    -- Le format du nom de la formation doit être correct
    AND acn.COD_REF_COM_ACN like 'FCA-%'||auv.COD_ANU||'%' 
    -- L'action est en service dans FCA Manager;
    AND acn.TEM_EN_SVE_ACN = 1
);
grant select on OSE_ETAPE to ucbn_ose;


-- ##################################
-- Vue Element_pedagogique
-- ##################################
-- MAJ 20/11/2017 : Utilisation de l'année universitaire de FCA Manager
-- MAJ 22/11/2017 : Commentaires pour mise à disposition
-- MAJ 24/11/2017 : Déversement de toutes les formations valides des années > 2017
-- ##################################
DROP VIEW OSE_ELEMENT_PEDAGOGIQUE;

CREATE VIEW OSE_ELEMENT_PEDAGOGIQUE AS
(
  SELECT ens.LIB_ENS AS LIBELLE,
  
  CASE
    -- Si le code de l'élement commence par APO et finie par une année ---> suppression pour retrouver l'étape et la VET d'origine
    WHEN acn.COD_LOG_SRC=2 THEN REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(acn.COD_REF_COM_ACN,'^APO-',''),'-'||acn.COD_ANU_ACN||'$',''),'-','_')
    WHEN acn.COD_LOG_SRC=4 THEN 'FCA-ACN-'||ACN.ID
  END AS Z_ETAPE_ID,
  -- Code Harpege de la structure
  ucl.COD_STR AS Z_STRUCTURE_ID,
  -- Pour l'instant on ne renvoie pas de période (semestre 1, semestre 2)
  NULL AS Z_PERIODE_ID,
  -- Le taux FOAD est défini  par le mode d'action 
  CASE 
    WHEN (acn.COD_MOD_ACN=3 OR acn.COD_MOD_ACN=4) THEN 1
    ELSE 0
  END AS TAUX_FOAD,
  -- Enseignement sans FI par défaut
  0 AS FI,
  -- Enseignement en FC par défaut
  1 AS FC,
  -- Pas d'apprentissage géré par FCA
  0 AS FA,
  -- Le code source contient l'identifiant unique de l'enseignement
  'FCA-ENS-'||ens.COD_ENS AS SOURCE_CODE,
  -- 0% d'apprentissage
  0 AS TAUX_FA,
  -- 100% en formation continue
  1 AS TAUX_FC,
  -- 0% en formation initiale
  0 AS TAUX_FI,
  -- Année universitaire de l'action
  auv.COD_ANU  AS ANNEE_ID,
  -- Pas de correspondance de discipline entre FCA et OSE par défaut
  '' AS Z_DISCPLINE_ID,
  
  ens.COD_REF_COM_ENS AS CODE

FROM ENSEIGNEMENT ens
LEFT JOIN ACTION acn ON acn.ID=ens.COD_ACN_REF
LEFT JOIN ACTIVITE acv ON (acv.ID=acn.COD_ACV)
LEFT JOIN ucbn_composante_ldap@apoprod ucl ON (ucl.COD_CMP=acv.COD_CGE)
LEFT JOIN ANNEE_UNI auv on (acn.COD_ANU_ACN = auv.COD_ANU)
WHERE
    -- Les formations ne sont prise en compte qu'à partir de l'année universitaire 2017
    auv.COD_ANU >= 2017
  -- La source doit être uniquement FCA MANAGER pour les actions
  AND ens.COD_LOG_SRC = 4
  -- on filtre sur la bonne année
  AND acn.COD_ANU_ACN=auv.COD_ANU
  -- on s'applique à conserver que les noms d'enseignements au bon format
  AND ens.COD_REF_COM_ENS like 'FCA-%'||auv.COD_ANU||'%'
  -- On ne conserve que les noms d'enseignement qui sont corrects (zèle ?)
  AND ens.COD_ACN_REF IS NOT NULL
  -- La source doit être FCA MANAGER pour les actions OU Apogee pour certains enseignements en pur qualifiant qui sont adossés à du diplômant
  AND (acn.COD_LOG_SRC = 4 OR acn.COD_LOG_SRC=2)
  -- L'enseignement est en service
  AND ens.TEM_EN_SVE_ENS = 1 
  -- L'action est en service
  AND acn.TEM_EN_SVE_ACN = 1
  -- quelques restrictions UNICAEN sur le nom de l'action
  AND (acn.COD_REF_COM_ACN like 'FCA-%'||auv.COD_ANU or acn.COD_REF_COM_ACN like 'APO-%-'||auv.COD_ANU)
);
GRANT SELECT ON OSE_ELEMENT_PEDAGOGIQUE TO ucbn_ose;

-- ##################################
-- Vue CHEMIN Pédagogique
-- ##################################
-- MAJ 20/11/2017 : Utilisation de l'année universitaire de FCA Manager
-- MAJ 22/11/2017 : Commentaires pour mise à disposition
-- MAJ 24/11/2017 : Déversement de toutes les formations valides des années > 2017
-- ##################################

DROP VIEW OSE_CHEMIN_PEDAGOGIQUE;

CREATE VIEW OSE_CHEMIN_PEDAGOGIQUE AS
(
  SELECT
  
    CASE 
      -- Si le code de l'élement commence par APO et finie par une année ---> suppression pour retrouver l'étape et la VET d'origine
      WHEN acn.COD_LOG_SRC=2 THEN REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(acn.COD_REF_COM_ACN,'^APO-',''),'-'||acn.COD_ANU_ACN||'$',''),'-','_')
      WHEN acn.COD_LOG_SRC=4 THEN 'FCA-ACN-'||ACN.ID
    END AS Z_ETAPE_ID,
    
    'FCA-ENS-'||ens.COD_ENS AS Z_ELEMENT_PEDAGOGIQUE_ID,
    'FCA-ACN-ENS-'||acn_ens.COD_ACN||'-'||acn_ens.COD_ENS AS SOURCE_CODE,
    
    auv.COD_ANU AS ANNEE_ID
  FROM ACTION_ENSEIGNEMENT acn_ens, ACTION acn, ENSEIGNEMENT ens, ANNEE_UNI auv
  WHERE
    acn.COD_ANU_ACN = auv.COD_ANU
    -- Les formations ne sont prise en compte qu'à partir de l'année universitaire 2017
    AND auv.COD_ANU >= 2017
    AND acn_ens.COD_ACN=acn.ID
    AND acn_ens.COD_ENS=ens.COD_ENS
    -- Filtrage sur le nom de l'enseignement (format unicaen)
    AND ens.COD_REF_COM_ENS LIKE 'FCA%'||acn.COD_ANU_ACN||'%'
    -- Filtrage sur le nom de l'action (format unicaen)
    AND (acn.COD_REF_COM_ACN LIKE 'FCA%'||acn.COD_ANU_ACN||'%' OR acn.COD_REF_COM_ACN LIKE 'APO-%'||acn.COD_ANU_ACN ) 
    -- La source doit être FCA MANAGER pour les actions OU Apogee pour certains enseignements en pur qualifiant qui sont adossés à du diplômant
    AND (acn.COD_LOG_SRC = 4 OR acn.COD_LOG_SRC=2)
    -- La source doit être uniquement FCA Manager pour les enseignements
    AND ens.COD_LOG_SRC = 4
    -- L'enseignement est en service
    AND ens.TEM_EN_SVE_ENS = 1
    -- L'action est en service
    AND acn.TEM_EN_SVE_ACN = 1
);
GRANT SELECT ON OSE_CHEMIN_PEDAGOGIQUE TO ucbn_ose;

-- ##################################
-- Volume Horaire Horaire ens(seignement)
-- ##################################
-- MAJ 30/10/2017 : Changement du code source pour différencier TD/CM/TP
-- MAJ 31/10/2017 : Cumul des temps si plusieurs intervenants et enseignements affectés à plusieurs actions
-- MAJ 20/11/2017 : Utilisation de l'année universitaire de FCA Manager
-- MAJ 22/11/2017 : Commentaires pour mise à disposition
-- MAJ 28/11/2017 : Arrondi sur conversion des minutes en heures
-- ##################################

DROP VIEW OSE_VOLUME_HORAIRE_ENS;

CREATE VIEW OSE_VOLUME_HORAIRE_ENS AS (

SELECT Z_TYPE_INTERVENTION_ID, ANNEE_ID, sum(HEURES_TMP) AS HEURES, SOURCE_CODE, Z_ELEMENT_PEDAGOGIQUE_ID FROM (
  SELECT
    -- Définition du taux de rémunération : TD/TP/CM
    CASE 
       -- s'il n'y a pas d'intervenant encore défini, seules des heures de TD pourront être saisie dans OSE
      WHEN tx_rem.lic_trm IS NULL THEN 'TD'
      -- sinon le type d'heures de cours (TP/TD/CM) est issu de FCA
      ELSE tx_rem.lic_trm
    END AS Z_TYPE_INTERVENTION_ID,
    -- Année universitaire 'courante' de FCA Manager
    acn.COD_ANU_ACN AS ANNEE_ID,
    -- Heures d'enseignements
    CASE 
       -- Sans volume horaire défini --> on met 1 heure de saisie autorisée
      WHEN ens_act.NB_MIN_PREVUES IS NULL THEN 1
       -- Sinon transformation des minutes en heures
      ELSE round((ens_act.NB_MIN_PREVUES/60),2)
    END AS HEURES_TMP,
    -- Créations des codes uniques des infos pour OSE
    CASE
      WHEN tx_rem.lic_trm IS NULL THEN 'FCA-ENS-'||acn.COD_ANU_ACN||'-'||ens.COD_ENS||'-TD'
      ELSE 'FCA-ENS-'||acn.COD_ANU_ACN||'-'||ens.COD_ENS||'-'||tx_rem.lic_trm
    END AS SOURCE_CODE,
    
    'FCA-ENS-'||ens.COD_ENS AS Z_ELEMENT_PEDAGOGIQUE_ID
    
    FROM ENSEIGNEMENT ens
    LEFT JOIN ACTION acn ON acn.ID=ens.COD_ACN_REF
    LEFT JOIN ACTIVITE acv ON (acv.ID=acn.COD_ACV)
    LEFT JOIN ucbn_composante_ldap@apoprod ucl ON (ucl.COD_CMP=acv.COD_CGE)
    LEFT JOIN ENSEIGNEMENT_ACTEUR_INTER ens_act ON (ens_act.COD_ENS=ens.COD_ENS)
    LEFT JOIN TAUX_REMUNERATION tx_rem ON (tx_rem.id=ens_act.COD_TRM)
    JOIN ANNEE_UNI auv on (acn.COD_ANU_ACN = auv.COD_ANU)
  WHERE
      -- Les formations ne sont prise en compte qu'à partir de l'année universitaire 2017
    auv.COD_ANU >= 2017
     -- La source doit être FCA MANAGER pour les enseignements
    AND ens.COD_LOG_SRC = 4
    -- Filtrage sur l'année universitaire en cours dans FCA Manager
    AND acn.COD_ANU_ACN=auv.COD_ANU
    -- Filtrage sur le nom de l'enseignement
    AND ens.COD_REF_COM_ENS like 'FCA-%'||acn.COD_ANU_ACN||'%'
    -- L'enseignement est en service
    AND ens.TEM_EN_SVE_ENS = 1
    -- L'action est en service
    AND acn.TEM_EN_SVE_ACN = 1)
    GROUP BY Z_TYPE_INTERVENTION_ID,ANNEE_ID,SOURCE_CODE,Z_ELEMENT_PEDAGOGIQUE_ID
    -- S'il n'y a pas d'intervenant, on ne remonte pas le volume horaire (ancien choix avant de forcer un nombre d'heures)
    -- AND lic_trm IS NOT NULL
);
GRANT SELECT ON OSE_VOLUME_HORAIRE_ENS TO ucbn_ose;

