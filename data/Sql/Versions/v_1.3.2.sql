-- ********************************************************************* --
-- *          à faire AVANT avoir mis à jour le code source            * --
-- ********************************************************************* --

BEGIN DBMS_SCHEDULER.disable(name=>'"OSE"."OSE_SRC_SYNC"', force => TRUE); END;
/


---------------------------
--Nouveau SEQUENCE
--FORMULE_RESULTAT_VH_ID_SEQ
---------------------------
 CREATE SEQUENCE "OSE"."FORMULE_RESULTAT_VH_ID_SEQ" MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 210516 NOCACHE NOORDER NOCYCLE;
---------------------------
--Modifié TABLE
--SERVICE_REFERENTIEL
---------------------------
ALTER TABLE "OSE"."SERVICE_REFERENTIEL" DROP ("TYPE_VOLUME_HORAIRE_ID");

---------------------------
--Modifié TABLE
--NOTIFICATION_INDICATEUR
---------------------------
ALTER TABLE "OSE"."NOTIFICATION_INDICATEUR" MODIFY ("FREQUENCE" NUMBER(*,0));

---------------------------
--Nouveau TABLE
--FORMULE_RESULTAT_VH
---------------------------
  CREATE TABLE "OSE"."FORMULE_RESULTAT_VH"
   (	"ID" NUMBER(*,0) NOT NULL ENABLE,
	"FORMULE_RESULTAT_ID" NUMBER(*,0) NOT NULL ENABLE,
	"VOLUME_HORAIRE_ID" NUMBER(*,0) NOT NULL ENABLE,
	"SERVICE_ASSURE" FLOAT(126) DEFAULT 0 NOT NULL ENABLE,
	"HEURES_SERVICE" FLOAT(126) DEFAULT 0 NOT NULL ENABLE,
	"HEURES_COMPL_FI" FLOAT(126) DEFAULT 0 NOT NULL ENABLE,
	"HEURES_COMPL_FA" FLOAT(126) DEFAULT 0 NOT NULL ENABLE,
	"HEURES_COMPL_FC" FLOAT(126) DEFAULT 0 NOT NULL ENABLE,
	"TO_DELETE" NUMBER(1,0) DEFAULT 0 NOT NULL ENABLE,
	CONSTRAINT "FRVH_FORMULE_RESULTAT_FK" FOREIGN KEY ("FORMULE_RESULTAT_ID")
	 REFERENCES "OSE"."FORMULE_RESULTAT" ("ID") ON DELETE CASCADE ENABLE,
	CONSTRAINT "FRVH_VOLUME_HORAIRE_FK" FOREIGN KEY ("VOLUME_HORAIRE_ID")
	 REFERENCES "OSE"."VOLUME_HORAIRE" ("ID") ON DELETE CASCADE ENABLE
   );
---------------------------
--Modifié VIEW
--V_TMP_WF
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_TMP_WF"
 ( "ID", "SOURCE_CODE", "NB_COMP", "NB_AGREM"
  )  AS
  WITH
  composantes_enseign AS (
      -- composantes d'enseignement par intervenant
      SELECT DISTINCT i.ID, i.source_code, s.structure_ens_id
      FROM service s
      INNER JOIN intervenant i ON i.ID = s.intervenant_id AND (i.histo_destructeur_id IS NULL)
      INNER JOIN STRUCTURE comp ON comp.ID = s.structure_ens_id AND (comp.histo_destructeur_id IS NULL)
      WHERE s.histo_destructeur_id IS NULL
  ),
  agrements_oblig_exist AS (
      -- agréments obligatoires obtenus par intervenant et structure
      SELECT i.ID, i.source_code, A.type_agrement_id, A.ID agrement_id, A.structure_id
      FROM agrement A
      INNER JOIN type_agrement ta ON A.type_agrement_id = ta.ID AND (ta.histo_destructeur_id IS NULL)
      INNER JOIN intervenant i ON A.intervenant_id = i.ID AND (i.histo_destructeur_id IS NULL)
      INNER JOIN type_agrement_statut tas ON i.statut_id = tas.statut_intervenant_id AND ta.ID = tas.type_agrement_id
          AND i.premier_recrutement = tas.premier_recrutement AND tas.obligatoire = 1 AND (tas.histo_destructeur_id IS NULL)
      WHERE A.histo_destructeur_id IS NULL
      AND ta.code = 'CONSEIL_RESTREINT'
  ),
  v_agrement AS (
    -- nombres de composantes d'enseignement et d'agrément obligatoires fournis par intervenant
    SELECT DISTINCT i.ID, i.source_code,
      ( select count(*) from COMPOSANTES_ENSEIGN ce where ce.id = i.id ) nb_comp,
      ( select count(*) from AGREMENTS_OBLIG_EXIST ao where ao.id = i.id ) nb_agrem
    FROM intervenant i
    WHERE i.histo_destructeur_id IS NULL
  )
  SELECT "ID","SOURCE_CODE","NB_COMP","NB_AGREM"
  FROM v_agrement v
  WHERE v.nb_comp <= nb_agrem;
  --AND v.id = p_intervenant_id;


---------------------------
--Nouveau VIEW
--V_RECAP_SERVICE_PREVIS
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_RECAP_SERVICE_PREVIS"
 ( "ID", "INTERVENANT_ID", "NOM_USUEL", "SOURCE_CODE", "ANNEE_ID", "SERVICE_STATUTAIRE", "MODIF_SERVICE", "LIBELLE_STRUCTURE", "CODE_EP", "LIBELLE_EP", "HAS_MODULATEUR", "NON_PAYABLE", "CODE_PERIODE", "ORDRE_PERIODE", "CODE_TI", "ORDRE_TI", "HEURES"
  )  AS
  select
  vh.id,
  i.id intervenant_id,
  i.nom_usuel,
  i.source_code,
  s.annee_id,
  si.service_statutaire,
  nvl(fsm.heures, 0) modif_service,
  str.libelle_court libelle_structure,
  ep.source_code code_ep,
  ep.libelle libelle_ep,
  case when fs.id is null then 0 else 1 end has_modulateur,
  case when vh.motif_non_paiement_id is null then 0 else 1 end non_payable,
  p.code code_periode,
  p.ordre ordre_periode,
  ti.code code_ti,
  ti.ordre ordre_ti,
  sum(vh.heures) heures
from volume_horaire vh
join type_volume_horaire tvh on vh.type_volume_horaire_id = tvh.id and tvh.code = 'PREVU'
join service s on vh.service_id = s.id and s.histo_destructeur_id is null
join element_pedagogique ep on s.element_pedagogique_id = ep.id and ep.histo_destructeur_id is null
join periode p on vh.periode_id = p.id and p.histo_destructeur_id is null
join intervenant i on s.intervenant_id = i.id and i.histo_destructeur_id is null
join statut_intervenant si on i.statut_id = si.id
--join validation_vol_horaire vvh on vvh.volume_horaire_id = vh.id
--join validation v on vvh.validation_id = v.id and v.histo_destructeur_id is null
join structure str on s.structure_ens_id = str.id and str.histo_destructeur_id is null
join type_intervention ti on vh.type_intervention_id = ti.id and ti.histo_destructeur_id is null
left join v_formule_service fs on fs.id = s.id and (fs.ponderation_service_compl <> 1 or fs.ponderation_service_du <> 1) -- NB: fs.id est l'id du service
left join v_formule_service_modifie fsm on fsm.intervenant_id = i.id and fsm.annee_id = s.annee_id
where vh.histo_destructeur_id is null
group by
  vh.id,
  i.id,
  i.nom_usuel,
  i.source_code,
  s.annee_id,
  si.service_statutaire,
  nvl(fsm.heures, 0),
  str.libelle_court,
  ep.source_code,
  ep.libelle,
  case when fs.id is null then 0 else 1 end,
  case when vh.motif_non_paiement_id is null then 0 else 1 end,
  p.code,
  p.ordre,
  ti.code,
  ti.ordre;

/
---------------------------
--Modifié PACKAGE
--OSE_DIVERS
---------------------------
CREATE OR REPLACE PACKAGE "OSE"."OSE_DIVERS" AS

  FUNCTION INTERVENANT_HAS_PRIVILEGE( intervenant_id NUMERIC, privilege_name VARCHAR2 ) RETURN NUMERIC;

  FUNCTION implode(i_query VARCHAR2, i_seperator VARCHAR2 DEFAULT ',') RETURN VARCHAR2;

  FUNCTION intervenant_est_permanent( INTERVENANT_ID NUMERIC ) RETURN NUMERIC;

  FUNCTION intervenant_est_non_autorise( INTERVENANT_ID NUMERIC ) RETURN NUMERIC;

  FUNCTION intervenant_peut_saisir_serv( INTERVENANT_ID NUMERIC ) RETURN NUMERIC;

  FUNCTION NIVEAU_FORMATION_ID_CALC( gtf_id NUMERIC, gtf_pertinence_niveau NUMERIC, niveau NUMERIC DEFAULT NULL ) RETURN NUMERIC;

  FUNCTION STRUCTURE_DANS_STRUCTURE( structure_testee NUMERIC, structure_cible NUMERIC ) RETURN NUMERIC;

  FUNCTION STR_REDUCE( str CLOB ) RETURN CLOB;

  FUNCTION LIKED( haystack CLOB, needle CLOB ) RETURN NUMERIC;

  FUNCTION COMPRISE_ENTRE( date_debut DATE, date_fin DATE DEFAULT NULL, date_obs DATE DEFAULT SYSDATE ) RETURN NUMERIC;

  PROCEDURE DO_NOTHING;

  FUNCTION VOLUME_HORAIRE_VALIDE( volume_horaire_id NUMERIC ) RETURN NUMERIC;

  FUNCTION CALCUL_TAUX_FI( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT;

  FUNCTION CALCUL_TAUX_FC( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT;

  FUNCTION CALCUL_TAUX_FA( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT;

  FUNCTION STRUCTURE_UNIV_GET_ID RETURN NUMERIC;

  PROCEDURE SYNC_LOG( msg CLOB );

END OSE_DIVERS;
/
---------------------------
--Modifié PACKAGE BODY
--OSE_DIVERS
---------------------------
CREATE OR REPLACE PACKAGE BODY "OSE"."OSE_DIVERS" AS

FUNCTION INTERVENANT_HAS_PRIVILEGE( intervenant_id NUMERIC, privilege_name VARCHAR2 ) RETURN NUMERIC IS
  statut statut_intervenant%rowtype;
  itype  type_intervenant%rowtype;
  res NUMERIC;
BEGIN
  res := 1;
  SELECT si.* INTO statut FROM statut_intervenant si JOIN intervenant i ON i.statut_id = si.id WHERE i.id = intervenant_id;
  SELECT ti.* INTO itype  FROM type_intervenant ti WHERE ti.id = statut.type_intervenant_id;
  IF 'saisie_service' = privilege_name THEN
    res := statut.peut_saisir_service;
  ELSIF 'saisie_service_exterieur' = privilege_name THEN
    --IF INTERVENANT_HAS_PRIVILEGE( intervenant_id, 'saisie_service' ) = 0 OR itype.code = 'E' THEN -- cascade
    IF itype.code = 'E' THEN
      res := 0;
    END IF;
  ELSIF 'saisie_service_referentiel' = privilege_name THEN
    IF itype.code = 'E' THEN
      res := 0;
    END IF;
  ELSIF 'saisie_service_referentiel_autre_structure' = privilege_name THEN
    res := 1;
  ELSIF 'saisie_motif_non_paiement' = privilege_name THEN
    res := statut.peut_saisir_motif_non_paiement;
  ELSE
    raise_application_error(-20101, 'Le privilège "' || privilege_name || '" n''existe pas.');
  END IF;
  RETURN res;
END;

FUNCTION implode(i_query VARCHAR2, i_seperator VARCHAR2 DEFAULT ',') RETURN VARCHAR2 AS
  l_return CLOB:='';
  l_temp CLOB;
  TYPE r_cursor is REF CURSOR;
  rc r_cursor;
BEGIN
  OPEN rc FOR i_query;
  LOOP
    FETCH rc INTO L_TEMP;
    EXIT WHEN RC%NOTFOUND;
    l_return:=l_return||L_TEMP||i_seperator;
  END LOOP;
  RETURN RTRIM(l_return,i_seperator);
END;

FUNCTION intervenant_est_permanent( INTERVENANT_ID NUMERIC ) RETURN NUMERIC AS
  resultat NUMERIC;
BEGIN
  SELECT COUNT(*) INTO resultat FROM intervenant_permanent WHERE id = INTERVENANT_ID;
  RETURN resultat;
END;

FUNCTION intervenant_est_non_autorise( INTERVENANT_ID NUMERIC ) RETURN NUMERIC AS
  resultat NUMERIC;
BEGIN
  SELECT COUNT(*) INTO resultat FROM intervenant i JOIN statut_intervenant si ON si.id = i.statut_id AND si.non_autorise = 1 WHERE i.id = INTERVENANT_ID;
  RETURN resultat;
END;

FUNCTION intervenant_peut_saisir_serv( INTERVENANT_ID NUMERIC ) RETURN NUMERIC AS
  resultat NUMERIC;
BEGIN
  SELECT COUNT(*) INTO resultat FROM intervenant i JOIN statut_intervenant si ON si.id = i.statut_id AND si.peut_saisir_service = 1 WHERE i.id = INTERVENANT_ID;
  RETURN resultat;
END;

FUNCTION NIVEAU_FORMATION_ID_CALC( gtf_id NUMERIC, gtf_pertinence_niveau NUMERIC, niveau NUMERIC DEFAULT NULL ) RETURN NUMERIC AS
BEGIN
  IF 1 <> gtf_pertinence_niveau OR niveau IS NULL OR niveau < 1 OR gtf_id < 1 THEN RETURN NULL; END IF;
  RETURN gtf_id * 256 + niveau;
END;

FUNCTION STRUCTURE_DANS_STRUCTURE( structure_testee NUMERIC, structure_cible NUMERIC ) RETURN NUMERIC AS
  RESULTAT NUMERIC;
BEGIN
  IF structure_testee = structure_cible THEN RETURN 1; END IF;

  select count(*) into resultat
  from structure
  WHERE structure.id = structure_testee
  start with parente_id = structure_cible
  connect by parente_id = prior id;

  RETURN RESULTAT;
END;

FUNCTION STR_REDUCE( str CLOB ) RETURN CLOB IS
BEGIN
  RETURN NLS_LOWER(str, 'NLS_SORT = BINARY_AI');
END;

FUNCTION LIKED( haystack CLOB, needle CLOB ) RETURN NUMERIC IS
BEGIN
  RETURN CASE WHEN STR_REDUCE(haystack) LIKE STR_REDUCE(needle) THEN 1 ELSE 0 END;
END;

FUNCTION COMPRISE_ENTRE( date_debut DATE, date_fin DATE DEFAULT NULL, date_obs DATE DEFAULT SYSDATE ) RETURN NUMERIC IS
  res NUMERIC;
BEGIN
--  res := 1;
  res := CASE WHEN to_char(date_obs,'YYYY-MM-DD') >= to_char(date_debut,'YYYY-MM-DD') THEN 1 ELSE 0 END;
  IF 1 = res AND date_fin IS NOT NULL THEN
    res := CASE WHEN to_char(date_obs,'YYYY-MM-DD') < to_char(date_fin,'YYYY-MM-DD') THEN 1 ELSE 0 END;
  END IF;
  RETURN res;
END;

PROCEDURE DO_NOTHING IS
BEGIN
  RETURN;
END;

FUNCTION VOLUME_HORAIRE_VALIDE( volume_horaire_id NUMERIC ) RETURN NUMERIC IS
  res NUMERIC;
BEGIN
  SELECT count(*) INTO res FROM
    validation v
    JOIN validation_vol_horaire vvh ON vvh.validation_id = v.id
  WHERE
    1 = ose_divers.comprise_entre( v.histo_creation, v.histo_destruction );
  RETURN CASE WHEN res > 0 THEN 1 ELSE 0 END;
END;


PROCEDURE CALCUL_TAUX( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, r_fi OUT FLOAT, r_fc OUT FLOAT, r_fa OUT FLOAT, arrondi NUMERIC DEFAULT 15 ) IS
  nt FLOAT;
  bi FLOAT;
  bc FLOAT;
  ba FLOAT;
  reste FLOAT;
BEGIN
  bi := eff_fi * fi;
  bc := eff_fc * fc;
  ba := eff_fa * fa;
  nt := bi + bc + ba;

  IF nt = 0 THEN -- au cas ou, alors on ne prend plus en compte les effectifs!!
    bi := fi;
    bc := fc;
    ba := fa;
    nt := bi + bc + ba;
  END IF;

  IF nt = 0 THEN -- toujours au cas ou...
    bi := 1;
    bc := 0;
    ba := 0;
    nt := bi + bc + ba;
  END IF;

  -- Calcul
  r_fi := bi / nt;
  r_fc := bc / nt;
  r_fa := ba / nt;

  -- Arrondis
  r_fi := ROUND( r_fi, arrondi );
  r_fc := ROUND( r_fc, arrondi );
  r_fa := ROUND( r_fa, arrondi );

  -- détermination du reste
  reste := 1 - r_fi - r_fc - r_fa;

  -- répartition éventuelle du reste
  IF reste <> 0 THEN
    IF r_fi > 0 THEN r_fi := r_fi + reste;
    ELSIF r_fc > 0 THEN r_fc := r_fc + reste;
    ELSE r_fa := r_fa + reste; END IF;
  END IF;

END;


FUNCTION CALCUL_TAUX_FI( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT IS
  ri FLOAT;
  rc FLOAT;
  ra FLOAT;
BEGIN
  CALCUL_TAUX( eff_fi, eff_fc, eff_fa, fi, fc, fa, ri, rc, ra, arrondi );
  RETURN ri;
END;

FUNCTION CALCUL_TAUX_FC( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT IS
  ri FLOAT;
  rc FLOAT;
  ra FLOAT;
BEGIN
  CALCUL_TAUX( eff_fi, eff_fc, eff_fa, fi, fc, fa, ri, rc, ra, arrondi );
  RETURN rc;
END;

FUNCTION CALCUL_TAUX_FA( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT IS
  ri FLOAT;
  rc FLOAT;
  ra FLOAT;
BEGIN
  CALCUL_TAUX( eff_fi, eff_fc, eff_fa, fi, fc, fa, ri, rc, ra, arrondi );
  RETURN ra;
END;

FUNCTION STRUCTURE_UNIV_GET_ID RETURN NUMERIC IS
  res NUMERIC;
BEGIN
  SELECT id INTO res FROM structure WHERE niveau = 1 AND ROWNUM = 1;
  RETURN res;
END;

PROCEDURE SYNC_LOG( msg CLOB ) IS
BEGIN
  INSERT INTO SYNC_LOG( id, date_sync, message ) VALUES ( sync_log_id_seq.nextval, systimestamp, msg );
END;

END OSE_DIVERS;
/

---------------------------
--Modifié VIEW
--V_NIVEAU_FORMATION
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_NIVEAU_FORMATION"
 ( "ID", "CODE", "LIBELLE_LONG", "NIVEAU", "GROUPE_TYPE_FORMATION_ID"
  )  AS
  SELECT DISTINCT
  ose_divers.niveau_formation_id_calc( gtf.id, gtf.pertinence_niveau, e.niveau ) id,
  gtf.libelle_court || e.niveau code,
  gtf.libelle_long,
  e.niveau,
  gtf.id groupe_type_formation_id
FROM
  etape e
  JOIN type_formation tf ON tf.id = e.type_formation_id AND ose_divers.comprise_entre( tf.histo_creation, tf.histo_destruction ) = 1
  JOIN groupe_type_formation gtf ON gtf.id = tf.groupe_id AND ose_divers.comprise_entre( gtf.histo_creation, gtf.histo_destruction ) = 1
WHERE
  ose_divers.comprise_entre( e.histo_creation, e.histo_destruction ) = 1
  AND ose_divers.niveau_formation_id_calc( gtf.id, gtf.pertinence_niveau, e.niveau ) IS NOT NULL
ORDER BY
  gtf.libelle_long, e.niveau;

---------------------------
--Nouveau VIEW
--ADRESSE_INTERVENANT_PRINC
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."ADRESSE_INTERVENANT_PRINC"
 ( "ID", "INTERVENANT_ID", "PRINCIPALE", "TEL_DOMICILE", "MENTION_COMPLEMENTAIRE", "BATIMENT", "NO_VOIE", "NOM_VOIE", "LOCALITE", "CODE_POSTAL", "VILLE", "PAYS_CODE_INSEE", "PAYS_LIBELLE", "SOURCE_ID", "SOURCE_CODE", "VALIDITE_DEBUT", "VALIDITE_FIN", "HISTO_CREATION", "HISTO_CREATEUR_ID", "HISTO_MODIFICATION", "HISTO_MODIFICATEUR_ID", "HISTO_DESTRUCTION", "HISTO_DESTRUCTEUR_ID", "TO_STRING"
  )  AS
  select
    a."ID",a."INTERVENANT_ID",a."PRINCIPALE",a."TEL_DOMICILE",a."MENTION_COMPLEMENTAIRE",a."BATIMENT",a."NO_VOIE",a."NOM_VOIE",a."LOCALITE",a."CODE_POSTAL",a."VILLE",a."PAYS_CODE_INSEE",a."PAYS_LIBELLE",a."SOURCE_ID",a."SOURCE_CODE",a."VALIDITE_DEBUT",a."VALIDITE_FIN",a."HISTO_CREATION",a."HISTO_CREATEUR_ID",a."HISTO_MODIFICATION",a."HISTO_MODIFICATEUR_ID",a."HISTO_DESTRUCTION",a."HISTO_DESTRUCTEUR_ID",
    -- concaténation des éléments non null séparés par ', '
    trim(trim(',' from replace(', ' || nvl(a.no_voie,'#') || ', ' || nvl(a.nom_voie,'#') || ', ' || nvl(a.batiment,'#') || ', ' || nvl(a.mention_complementaire,'#'), ', #', ''))) ||
    -- saut de ligne complet
    chr(13) || chr(10) ||
    -- concaténation des éléments non null séparés par ', '
    trim(trim(',' from replace(', ' || nvl(a.localite,'#') || ', ' || nvl(a.code_postal,'#') || ', ' || nvl(a.ville,'#') || ', ' || nvl(a.pays_libelle,'#'), ', #', ''))) to_string
  from adresse_intervenant a
  where id in (
    -- on ne retient que l'adresse principale si elle existe ou sinon la première adresse trouvée
    select id
    from (
      -- attribution d'un rang par intervenant aux adresses pour avoir la principale (éventuelle) en n°1
      select id, dense_rank() over(partition by intervenant_id order by principale desc) rang from adresse_intervenant
    )
    where rang = 1
  );

---------------------------
--Nouveau VIEW
--V_INDIC_DIFF_DOSSIER
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_INDIC_DIFF_DOSSIER"
 ( "ID", "NOM_USUEL", "ADRESSE_DOSSIER", "ADRESSE_IMPORT", "RIB_DOSSIER", "RIB_IMPORT", "NOM_USUEL_DOSSIER", "NOM_USUEL_IMPORT", "PRENOM_DOSSIER", "PRENOM_IMPORT"
  )  AS
  select
    i.id,
    i.nom_usuel,
    case when d.adresse <> a.to_string                                              then d.adresse                            else null end adresse_dossier,
    case when d.adresse <> a.to_string                                              then a.to_string                          else null end adresse_import,
    case when d.rib <> REPLACE(i.BIC || '-' || i.IBAN, ' ')                         then d.rib                                else null end rib_dossier,
    case when d.rib <> REPLACE(i.BIC || '-' || i.IBAN, ' ')                         then REPLACE(i.BIC || '-' || i.IBAN, ' ') else null end rib_import,
    case when UPPER(REPLACE(d.nom_usuel, ' ')) <> UPPER(REPLACE(i.nom_usuel, ' '))  then REPLACE(d.nom_usuel, ' ')            else null end nom_usuel_dossier,
    case when UPPER(REPLACE(d.nom_usuel, ' ')) <> UPPER(REPLACE(i.nom_usuel, ' '))  then REPLACE(i.nom_usuel, ' ')            else null end nom_usuel_import,
    case when UPPER(REPLACE(d.prenom, ' ')) <> UPPER(REPLACE(i.prenom, ' '))        then REPLACE(d.prenom, ' ')               else null end prenom_dossier,
    case when UPPER(REPLACE(d.prenom, ' ')) <> UPPER(REPLACE(i.prenom, ' '))        then REPLACE(i.prenom, ' ')               else null end prenom_import
  from intervenant i
  join intervenant_exterieur ie on i.id = ie.id
  join dossier d on ie.dossier_id = d.id
  left join adresse_intervenant_princ a on a.intervenant_id = i.id;
---------------------------
--Nouveau VIEW
--V_BERTRAND
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_BERTRAND"
 ( "INTERVENANT_ID", "SOURCE_CODE", "NOM_USUEL", "LIBELLE_STR", "CODE_EP", "LIBELLE_EP", "HAS_MODULATEUR", "CODE_PERIODE", "CODE_TI", "PAYABLE", "HEURES", "GROUPING_EP", "GROUPING_PERIODE", "GROUPING_PAYABLE", "GROUPING_ID"
  )  AS
  with tmp as (
  select
    i.id intervenant_id,
    i.source_code,
    i.nom_usuel,
    str.libelle_court libelle_str,
    ep.source_code code_ep,
    ep.libelle libelle_ep,
    decode(fs.id,null,0,1) has_modulateur,
    p.code code_periode,
    ti.code code_ti,
    decode(vh.motif_non_paiement_id,null,'','NP') payable,
    vh.heures
  from volume_horaire vh
  --join validation_vol_horaire vvh on vvh.volume_horaire_id = vh.id
  --join validation v on vvh.validation_id = v.id and v.histo_destructeur_id is null
  join type_volume_horaire tvh on vh.type_volume_horaire_id = tvh.id and tvh.code = 'PREVU'
  join service s on vh.service_id = s.id and s.histo_destructeur_id is null
  join element_pedagogique ep on s.element_pedagogique_id = ep.id and ep.histo_destructeur_id is null
  join periode p on vh.periode_id = p.id and p.histo_destructeur_id is null
  join structure str on s.structure_ens_id = str.id and str.histo_destructeur_id is null
  join type_intervention ti on vh.type_intervention_id = ti.id and ti.histo_destructeur_id is null
  join intervenant i on s.intervenant_id = i.id and i.histo_destructeur_id is null
  left join v_formule_service fs on fs.id = s.id and (fs.ponderation_service_compl <> 1 or fs.ponderation_service_du <> 1) -- NB: fs.id est l'id du service
  left join v_formule_service_modifie fsm on fsm.intervenant_id = i.id and fsm.annee_id = s.annee_id
  where vh.histo_destructeur_id is null
)
select
  intervenant_id,
  source_code,
  nom_usuel,
  libelle_str,
  code_ep,
  /*decode(grouping(libelle_ep), 1, 'Total tout EP', libelle_ep) as*/ libelle_ep,
  has_modulateur,
  /*decode(grouping(code_periode), 1, 'Total toutes périodes', code_periode) as*/ code_periode,
  code_ti,
  /*decode(grouping(payable), 1, 'Total payable ou non', payable) as*/ payable,
  sum(heures) heures,
  grouping(libelle_ep) as grouping_ep,
  grouping(code_periode) as grouping_periode,
  grouping(payable) as grouping_payable,
  grouping_id(libelle_str, libelle_ep, code_periode, payable) as grouping_id
from tmp
--where source_code = '3948'
group by intervenant_id, source_code, nom_usuel, code_ti, cube(libelle_str, (code_ep, libelle_ep, has_modulateur), code_periode, payable)
having
--  grouping_id(libelle_ep, code_periode, payable) in (0,5,7) equivaut aux 3 lignes suivantes :
  grouping(libelle_str) = 0 and grouping(libelle_ep) = 0 and grouping(code_periode) = 0 and grouping(payable) = 0 or -- totaux détails (grouping_id = 0)
  grouping(libelle_str) = 0 and grouping(libelle_ep) = 1 and grouping(code_periode) = 0 and grouping(payable) = 1 or -- totaux tout EP et payable confondus (grouping_id = 5)
  grouping(libelle_str) = 0 and grouping(libelle_ep) = 1 and grouping(code_periode) = 1 and grouping(payable) = 1 or -- totaux tout EP, période et payable confondus (grouping_id = 7)
  grouping(libelle_str) = 1 and grouping(libelle_ep) = 1 and grouping(code_periode) = 1 and grouping(payable) = 1    -- totaux tout StructureService, EP, période et payable confondus (grouping_id = 15)
order by
  nom_usuel, libelle_str, code_periode, libelle_ep, libelle_ep, code_ti, payable desc;
---------------------------
--Modifié TRIGGER
--WF_TRG_VOLUME_HORAIRE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."WF_TRG_VOLUME_HORAIRE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VOLUME_HORAIRE"
  BEGIN
  ose_workflow.update_intervenants_etapes();
END;
/
---------------------------
--Modifié TRIGGER
--WF_TRG_VOLUME_HORAIRE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."WF_TRG_VOLUME_HORAIRE"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VOLUME_HORAIRE"
  REFERENCING FOR EACH ROW
  DECLARE
  intervenant_id NUMERIC;
  service_id NUMERIC;
BEGIN
  service_id := CASE WHEN deleting THEN :OLD.service_id ELSE :NEW.service_id END;
  SELECT s.intervenant_id into intervenant_id from service s where id = service_id;
  ose_workflow.add_intervenant_to_update (intervenant_id);
END;
/
---------------------------
--Modifié TRIGGER
--WF_TRG_SERVICE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."WF_TRG_SERVICE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."SERVICE"
  BEGIN
  ose_workflow.update_intervenants_etapes();
END;
/
---------------------------
--Modifié TRIGGER
--WF_TRG_SERVICE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."WF_TRG_SERVICE"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."SERVICE"
  REFERENCING FOR EACH ROW
  BEGIN
  ose_workflow.add_intervenant_to_update (CASE WHEN deleting THEN :OLD.intervenant_id ELSE :NEW.intervenant_id END);
END;
/
---------------------------
--Modifié TRIGGER
--VOLUME_HORAIRE_CK
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."VOLUME_HORAIRE_CK"
  BEFORE INSERT OR UPDATE ON "OSE"."VOLUME_HORAIRE"
  REFERENCING FOR EACH ROW
  DECLARE
    has_validation NUMERIC;
    modified       BOOLEAN;
    intervenant_id NUMERIC;
  BEGIN
    IF :OLD.motif_non_paiement_id IS NULL AND :NEW.motif_non_paiement_id IS NOT NULL THEN
      SELECT s.intervenant_id INTO intervenant_id FROM service s WHERE s.id = :NEW.service_id;
      IF 0 = ose_divers.intervenant_has_privilege( intervenant_id, 'saisie_motif_non_paiement') THEN
        raise_application_error(-20101, 'Il est impossible d''associer un motif de non paiement à un intervenant vacataire ou BIATSS.');
      END IF;
    END IF;

    IF :NEW.motif_non_paiement_id IS NOT NULL AND :NEW.contrat_id IS NOT NULL THEN
      raise_application_error(-20101, 'Les heures ayant un motif de non paiement ne peuvent faire l''objet d''une contractualisation');
    END IF;

    modified :=
      NVL(:NEW.id,0) <> NVL(:OLD.id,0)
      OR NVL(:NEW.type_volume_horaire_id,0) <> NVL(:OLD.type_volume_horaire_id,0)
      OR NVL(:NEW.service_id,0) <> NVL(:OLD.service_id,0)
      OR NVL(:NEW.periode_id,0) <> NVL(:OLD.periode_id,0)
      OR NVL(:NEW.type_intervention_id,0) <> NVL(:OLD.type_intervention_id,0)
      OR NVL(:NEW.heures,0) <> NVL(:OLD.heures,0)
      OR NVL(:NEW.motif_non_paiement_id,0) <> NVL(:OLD.motif_non_paiement_id,0)
      OR NVL(:NEW.histo_creation,SYSDATE) <> NVL(:OLD.histo_creation,SYSDATE)
      OR NVL(:NEW.histo_createur_id,0) <> NVL(:OLD.histo_createur_id,0)
      OR NVL(:NEW.histo_destruction,SYSDATE) <> NVL(:OLD.histo_destruction,SYSDATE)
      OR NVL(:NEW.histo_destructeur_id,0) <> NVL(:OLD.histo_destructeur_id,0);

    SELECT
      COUNT(*)
    INTO
      has_validation
    FROM
      VALIDATION_VOL_HORAIRE vvh
      JOIN validation v ON v.id = VVH.VALIDATION_ID
    WHERE
      V.HISTO_DESTRUCTION IS NULL
      AND vvh.VOLUME_HORAIRE_ID  = :NEW.ID;

    IF modified AND 0 <> has_validation THEN
      raise_application_error(-20101, 'Il est impossible de modifier des heures déjà validées.');
    END IF;
  END;
/
---------------------------
--Modifié TRIGGER
--F_VOLUME_HORAIRE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_VOLUME_HORAIRE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VOLUME_HORAIRE"
  BEGIN
  OSE_FORMULE.CALCULER_SUR_DEMANDE;
END;
/
---------------------------
--Modifié PACKAGE
--OSE_FORMULE
---------------------------
CREATE OR REPLACE PACKAGE "OSE"."OSE_FORMULE" AS

  TYPE t_intervenant IS RECORD (
    structure_id              NUMERIC,
    heures_service_statutaire FLOAT   DEFAULT 0,
    heures_service_modifie    FLOAT   DEFAULT 0
  );

  TYPE t_type_etat_vh IS RECORD (
    type_volume_horaire_id    NUMERIC,
    etat_volume_horaire_id    NUMERIC
  );
  TYPE t_lst_type_etat_vh   IS TABLE OF t_type_etat_vh INDEX BY PLS_INTEGER;

  TYPE t_referentiel IS RECORD (
    id                        NUMERIC,
    structure_id              NUMERIC,
    heures                    FLOAT   DEFAULT 0
  );
  TYPE t_lst_referentiel      IS TABLE OF t_referentiel INDEX BY PLS_INTEGER;

  TYPE t_service IS RECORD (
    id                        NUMERIC,
    taux_fi                   FLOAT   DEFAULT 1,
    taux_fa                   FLOAT   DEFAULT 0,
    taux_fc                   FLOAT   DEFAULT 0,
    ponderation_service_du    FLOAT   DEFAULT 1,
    ponderation_service_compl FLOAT   DEFAULT 1,
    structure_aff_id          NUMERIC,
    structure_ens_id          NUMERIC
  );
  TYPE t_lst_service          IS TABLE OF t_service INDEX BY PLS_INTEGER;

  TYPE t_volume_horaire IS RECORD (
    id                        NUMERIC,
    service_id                NUMERIC,
    type_volume_horaire_id    NUMERIC,
    etat_volume_horaire_id    NUMERIC,
    etat_volume_horaire_ordre NUMERIC,
    heures                    FLOAT   DEFAULT 0,
    taux_service_du           FLOAT   DEFAULT 1,
    taux_service_compl        FLOAT   DEFAULT 1
  );
  TYPE t_lst_volume_horaire   IS TABLE OF t_volume_horaire INDEX BY PLS_INTEGER;

  d_intervenant         t_intervenant;
  d_type_etat_vh        t_lst_type_etat_vh;
  d_referentiel         t_lst_referentiel;
  d_service             t_lst_service;
  d_volume_horaire      t_lst_volume_horaire;

  FUNCTION  GET_DATE_OBS RETURN DATE;
  FUNCTION  SET_DATE_OBS( DATE_OBS DATE DEFAULT NULL ) RETURN DATE;

  FUNCTION NOUVEAU_RESULTAT RETURN formule_resultat%rowtype;
  FUNCTION ENREGISTRER_RESULTAT( fr formule_resultat%rowtype ) RETURN NUMERIC;

  FUNCTION NOUVEAU_RESULTAT_SERVICE RETURN formule_resultat_service%rowtype;
  FUNCTION ENREGISTRER_RESULTAT_SERVICE( fs formule_resultat_service%rowtype ) RETURN NUMERIC;

  FUNCTION NOUVEAU_RESULTAT_VH RETURN formule_resultat_vh%rowtype;
  FUNCTION ENREGISTRER_RESULTAT_VH( fvh formule_resultat_vh%rowtype ) RETURN NUMERIC;

  FUNCTION NOUVEAU_RESULTAT_REF RETURN formule_resultat_referentiel%rowtype;
  FUNCTION ENREGISTRER_RESULTAT_REF( fr formule_resultat_referentiel%rowtype ) RETURN NUMERIC;


  PROCEDURE POPULATE( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC );
  PROCEDURE DEMANDE_CALCUL( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC );
  PROCEDURE CALCULER( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC );
  PROCEDURE CALCULER_SUR_DEMANDE; -- mise à jour de tous les items identifiés
  PROCEDURE CALCULER_TOUT;        -- mise à jour de TOUTES les données ! ! ! !

END OSE_FORMULE;

/
---------------------------
--Modifié PACKAGE BODY
--OSE_WORKFLOW
---------------------------
CREATE OR REPLACE PACKAGE BODY "OSE"."OSE_WORKFLOW" AS

  /**
   * Inscription de l'intervenant dont il faudra regénérer la progression dans le workflow
   */
  PROCEDURE Add_Intervenant_To_Update (p_intervenant_id NUMERIC)
  IS
  BEGIN
    MERGE INTO wf_tmp_intervenant t USING dual ON (t.intervenant_id = p_intervenant_id) WHEN NOT MATCHED THEN INSERT (INTERVENANT_ID) VALUES (p_intervenant_id);
  END;

  /**
   * Parcours des intervenants dont il faut regénérer la progression dans le workflow
   */
  PROCEDURE Update_Intervenants_Etapes
  IS
  BEGIN
    FOR ti IN (SELECT distinct * FROM wf_tmp_intervenant) LOOP
      ose_workflow.Update_Intervenant_Etapes(ti.intervenant_id);
    END LOOP;
    DELETE FROM wf_tmp_intervenant;
  END;

  /**
   * Regénère la progression dans le workflow de tous les intervenants dont le statut autorise la saisie de service.
   */
  PROCEDURE Update_All_Intervenants_Etapes
  IS
    CURSOR intervenant_cur IS
      SELECT i.* FROM intervenant i
      JOIN statut_intervenant si ON si.id = i.statut_id AND si.histo_destruction IS NULL AND si.peut_saisir_service = 1
      WHERE i.histo_destruction IS NULL;
  BEGIN
    FOR intervenant_rec IN intervenant_cur
    LOOP
      --DBMS_OUTPUT.put_line (intervenant_rec.nom_usuel || '(' || intervenant_rec.source_code || ')');
      ose_workflow.Update_Intervenant_Etapes(intervenant_rec.id);
    END LOOP;
  END;

  /**
   * Regénère la progression complète dans le workflow d'un intervenant.
   */
  PROCEDURE Update_Intervenant_Etapes (p_intervenant_id NUMERIC)
  IS
    structures_ids T_LIST_STRUCTURE_ID;
    structure_id NUMERIC;
    pertinente NUMERIC;
    franchie NUMERIC;
    atteignable NUMERIC;
    courante NUMERIC;
    courante_trouvee NUMERIC := 0;
    ordre NUMERIC := 1;
  BEGIN
    --
    -- RAZ progression.
    --
    DELETE FROM wf_intervenant_etape ie WHERE ie.intervenant_id = p_intervenant_id;

    --
    -- Parcours des étapes.
    --
    FOR etape_rec IN (
      --select e.* from wf_etape e where e.code = 'DEBUT'
      --UNION
      -- liste ordonnée des étapes sans les étapes DEBUT et FIN
      select ea.* --ea.id, ea.code, ed.id depart_etape_id, ed.code depart_etape_code
      from wf_etape_to_etape ee
      inner join wf_etape ed on ed.id = ee.depart_etape_id
      inner join wf_etape ea on ea.id = ee.arrivee_etape_id
      where ea.code <> 'FIN'
      connect by ee.depart_etape_id = prior ee.arrivee_etape_id
      start with ed.code = 'DEBUT'
      --UNION
      --select e.* from wf_etape e where e.code = 'FIN'
    )
    LOOP
      --
      -- Si l'étape n'est pas pertinente, on passe à la suivante.
      --
      pertinente := 0;
      IF etape_rec.PERTIN_FUNC IS NULL THEN
        pertinente := 1;
      ELSE
        EXECUTE IMMEDIATE 'BEGIN :res := ' || etape_rec.PERTIN_FUNC || '(:1); END;' USING OUT pertinente, p_intervenant_id;
        --DBMS_OUTPUT.put_line (etape_rec.libelle || ' --> ' || etape_rec.PERTIN_FUNC || ' returned ' || pertinente);
      END IF;
      IF pertinente = 0 THEN
        CONTINUE;
      END IF;

      --
      -- La règle (fonction) de franchissement prend 2 arguments : l'id de l'intervenant (null interdit) et l'id de la structure (null accepté).
      -- Cette règle sera exécutée une fois avec un id de structure null (ce qui se traduit par "peu importe la structure"), puis
      -- autant de fois qu'il existe de structures d'enseignement dans le cas où l'étape est déclinable par structure.
      -- L'id null et les ids des structures sont stockés dans une liste qui sera parcourue plus loin.
      --
      structures_ids.DELETE;
      -- id structure null
      structures_ids(structures_ids.COUNT) := NULL;
      -- pour les étapes pouvant être déclinées par structure, collecte des structures d'enseignement
      IF etape_rec.STRUCTURE_DEPENDANT = 1 THEN
        ose_workflow.fetch_structures_ens_ids(p_intervenant_id, structures_ids);
      END IF;

      --
      -- Dans la progression de l'intervenant, une même étape peut figurer plusieurs fois : une fois avec un id de structure null
      -- (ce qui se traduit par "peu importe la structure") + autant de fois qu'il existe de structures d'enseignement dans le cas où
      -- l'étape est déclinable par structure.
      --
      FOR i IN 0 .. structures_ids.COUNT - 1
      LOOP
        structure_id := structures_ids(i);
        --DBMS_OUTPUT.put_line (etape_rec.libelle || ' : structures_ids('||i||') := ' || structure_id);

        --
        -- Interrogation de la règle de franchissement de l'étape.
        --
        IF etape_rec.FRANCH_FUNC IS NULL THEN
          franchie := 1;
        ELSE
          EXECUTE IMMEDIATE 'BEGIN :res := ' || etape_rec.FRANCH_FUNC || '(:1, :2); END;' USING OUT franchie, p_intervenant_id, structure_id;
          --DBMS_OUTPUT.put_line (etape_rec.FRANCH_FUNC || ' returned ' || franchie);
        END IF;

        atteignable := 1;

        --
        -- Si l'étape courante n'a pas encore été trouvée.
        --
        IF courante_trouvee = 0 THEN
          IF franchie = 1 THEN
            courante := 0;
          ELSE
            -- l'étape marquée "courante" est la 1ère étape non franchie
            courante := 1;
            courante_trouvee := etape_rec.id;
          END IF;
        --
        -- Si l'étape courante a été trouvée et que l'on se situe dessus.
        --
        ELSIF courante_trouvee = etape_rec.id THEN
          IF franchie = 1 THEN
            courante := 0;
          ELSE
            courante := 1;
          END IF;
        --
        -- Une étape située après l'étape courante est forcément "non courante".
        --
        ELSE
          courante := 0;
          atteignable := 0;
        END IF;

        --
        -- Ecriture dans la table.
        --
        INSERT INTO wf_intervenant_etape (id, intervenant_id, etape_id, structure_id, courante, franchie, atteignable, ordre)
          SELECT wf_intervenant_etape_id_seq.nextval, p_intervenant_id, etape_rec.id, structure_id, courante, franchie, atteignable, ordre FROM DUAL;

        ordre := ordre + 1;
      END LOOP;

    END LOOP;
  END;

  /**
   *
   */
  PROCEDURE fetch_structures_ens_ids (p_intervenant_id NUMERIC, structures_ids IN OUT T_LIST_STRUCTURE_ID)
  IS
    i PLS_INTEGER;
  BEGIN
    i := structures_ids.COUNT;
    FOR d IN (
      SELECT distinct structure_ens_id FROM service s
      WHERE s.intervenant_id = p_intervenant_id AND S.ANNEE_ID = OSE_PARAMETRE.GET_ANNEE() AND S.HISTO_DESTRUCTION IS NULL
    ) LOOP
      structures_ids(i) := d.structure_ens_id;
      i := i + 1;
    END LOOP;
  END;


  /******************** Règles métiers de pertinence et de franchissement des étapes ********************/

  /**
   *
   */
  FUNCTION peut_saisir_dossier (p_intervenant_id NUMERIC, p_structure_id NUMERIC) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT si.peut_saisir_dossier INTO res FROM statut_intervenant si
    JOIN intervenant i ON i.statut_id = si.id
    WHERE i.id = p_intervenant_id;
    RETURN res;
  END;

  /**
   *
   */
  FUNCTION peut_saisir_service (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT si.peut_saisir_service INTO res FROM statut_intervenant si
    JOIN intervenant i ON i.statut_id = si.id
    WHERE i.id = p_intervenant_id;
    RETURN res;
  END;

  /**
   *
   */
  FUNCTION possede_services (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    IF p_structure_id IS NULL THEN
      SELECT count(*) INTO res FROM service s
      JOIN element_pedagogique ep on ep.id = s.element_pedagogique_id AND ep.histo_destruction IS NULL
      JOIN etape e ON e.id = ep.etape_id AND e.histo_destruction IS NULL
      WHERE s.intervenant_id = p_intervenant_id AND s.annee_id = ose_parametre.get_annee();
    ELSE
      SELECT count(*) INTO res FROM service s
      JOIN element_pedagogique ep on ep.id = s.element_pedagogique_id
      JOIN etape e ON e.id = ep.etape_id
      WHERE s.intervenant_id = p_intervenant_id AND s.annee_id = ose_parametre.get_annee()
      AND s.structure_ens_id = p_structure_id;
    END IF;
    RETURN CASE WHEN res > 0 THEN 1 ELSE 0 END;
  END;

  /**
   *
   */
  FUNCTION peut_saisir_referentiel (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT si.peut_saisir_referentiel INTO res FROM statut_intervenant si JOIN intervenant i ON i.statut_id = si.id
    WHERE i.id = p_intervenant_id;
    RETURN res;
  END;

  /**
   *
   */
  FUNCTION peut_saisir_piece_jointe (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT count(*) INTO res FROM type_piece_jointe_statut tpjs
    JOIN statut_intervenant si on tpjs.statut_intervenant_id = si.id
    JOIN intervenant i ON i.statut_id = si.id
    WHERE i.id = p_intervenant_id;
    RETURN res;
  END;

  /**
   *
   */
  FUNCTION necessite_agrement_cr (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT count(*) INTO res FROM type_agrement_statut tas
    JOIN type_agrement ta ON ta.id = tas.type_agrement_id AND ta.code = 'CONSEIL_RESTREINT'
    JOIN statut_intervenant si on tas.statut_intervenant_id = si.id
    JOIN intervenant i ON i.statut_id = si.id
    WHERE tas.PREMIER_RECRUTEMENT = i.PREMIER_RECRUTEMENT AND tas.OBLIGATOIRE = 1
    AND i.id = p_intervenant_id;
    RETURN res;
  END;

  /**
   *
   */
  FUNCTION necessite_agrement_ca (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT count(*) INTO res FROM type_agrement_statut tas
    JOIN type_agrement ta ON ta.id = tas.type_agrement_id AND ta.code = 'CONSEIL_ACADEMIQUE'
    JOIN statut_intervenant si on tas.statut_intervenant_id = si.id
    JOIN intervenant i ON i.statut_id = si.id
    WHERE tas.PREMIER_RECRUTEMENT = i.PREMIER_RECRUTEMENT AND tas.OBLIGATOIRE = 1
    AND i.id = p_intervenant_id;
    RETURN res;
  END;

  /**
   *
   */
  FUNCTION necessite_contrat (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT si.peut_avoir_contrat INTO res FROM statut_intervenant si JOIN intervenant i ON i.statut_id = si.id
    WHERE i.id = p_intervenant_id;
    RETURN res;
  END;

  /**
   *
   */
  FUNCTION service_valide (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    CURSOR service_cur IS
      SELECT s.* FROM service s
      JOIN volume_horaire vh ON vh.service_id = s.id AND vh.histo_destruction IS NULL
      JOIN v_volume_horaire_etat vhe ON vhe.volume_horaire_id = vh.id
      JOIN etat_volume_horaire evh ON evh.id = vhe.etat_volume_horaire_id AND evh.ordre >= ( SELECT min(ordre) FROM etat_volume_horaire WHERE code = 'valide' )
      JOIN element_pedagogique ep on ep.id = s.element_pedagogique_id AND ep.histo_destruction IS NULL
      JOIN etape e ON e.id = ep.etape_id AND e.histo_destruction IS NULL
      WHERE s.intervenant_id = p_intervenant_id AND s.annee_id = ose_parametre.get_annee();
--    -- autre version : sans utilisation de la vue v_volume_horaire_etat
--    CURSOR service_cur IS
--      SELECT s.* FROM service s
--      JOIN element_pedagogique ep on ep.id = s.element_pedagogique_id AND ep.histo_destruction IS NULL
--      JOIN etape e ON e.id = ep.etape_id AND e.histo_destruction IS NULL
--      JOIN volume_horaire vh ON vh.service_id = s.id AND vh.histo_destruction IS NULL
--      JOIN VALIDATION_VOL_HORAIRE vvh on VVH.VOLUME_HORAIRE_ID = vh.id
--      JOIN validation v on VVH.VALIDATION_ID = v.id AND V.HISTO_DESTRUCTION is null
--      WHERE s.intervenant_id = p_intervenant_id AND s.annee_id = ose_parametre.get_annee();
--    CURSOR service_cur IS
--      SELECT s.* FROM service s
--      JOIN volume_horaire vh ON vh.service_id = s.id AND vh.histo_destruction IS NULL
--      JOIN v_volume_horaire_etat vhe ON vhe.volume_horaire_id = vh.id
--      JOIN etat_volume_horaire evh ON evh.id = vhe.etat_volume_horaire_id AND evh.ordre < ( SELECT min(ordre) FROM etat_volume_horaire WHERE code = 'valide' )
--      JOIN element_pedagogique ep on ep.id = s.element_pedagogique_id AND ep.histo_destruction IS NULL
--      JOIN etape e ON e.id = ep.etape_id AND e.histo_destruction IS NULL
--      WHERE s.intervenant_id = p_intervenant_id AND s.annee_id = ose_parametre.get_annee();
    service_rec service_cur%rowtype;
    res NUMERIC := 0;
    nb numeric;
  BEGIN
    IF p_structure_id IS NULL THEN
      -- si aucune structure n'est spécifiée, on se contente du moindre service trouvé
      OPEN service_cur;
      FETCH service_cur INTO service_rec;
      IF service_cur%FOUND = TRUE THEN
        res := 1;
      END IF;
      CLOSE service_cur;
    ELSE
      -- si une structure précise est spécifiée, on se contente du moindre service trouvé concernant cette structure d'enseignement
      FOR service_rec IN service_cur
      LOOP
        IF service_rec.structure_ens_id = p_structure_id THEN
          res := 1;
          EXIT;
        END IF;
      END LOOP;
    END IF;
    RETURN res;
  END;

  /**
   *
   */
  FUNCTION possede_dossier (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT count(*) INTO res FROM intervenant_exterieur i JOIN dossier d ON d.id = i.dossier_id AND d.histo_destruction IS NULL
    WHERE i.id = p_intervenant_id;
    RETURN res;
  END;

  /**
   *
   */
  FUNCTION dossier_valide (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT count(*) INTO res FROM validation v
    JOIN type_validation tv ON tv.id = v.type_validation_id AND tv.code = 'DONNEES_PERSO_PAR_COMP'
    WHERE v.histo_destruction IS NULL
    AND v.intervenant_id = p_intervenant_id;
    RETURN res;
  END;

  /**
   *
   */
  FUNCTION referentiel_valide (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT count(*) INTO res FROM validation v
    JOIN type_validation tv ON tv.id = v.type_validation_id AND tv.code = 'REFERENTIEL'
    WHERE v.histo_destruction IS NULL
    AND v.intervenant_id = p_intervenant_id;
    RETURN res;
  END;

  /**
   *
   */
  FUNCTION pieces_jointes_fournies (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT count(*) INTO res FROM (
      WITH
      ATTENDU_OBLIGATOIRE AS (
          -- nombres de pj OBLIGATOIRES pour chaque intervenant
          SELECT I.ID INTERVENANT_ID, I.SOURCE_CODE, COALESCE(vheures.TOTAL_HEURES, 0) TOTAL_HEURES, count(tpjs.id) NB /*+ materialize */
          FROM INTERVENANT_EXTERIEUR IE
          INNER JOIN INTERVENANT I ON IE.ID = I.ID AND (I.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN DOSSIER d ON IE.DOSSIER_ID = d.ID AND (d.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN STATUT_INTERVENANT si ON d.STATUT_ID = si.ID AND (si.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN si.VALIDITE_DEBUT AND COALESCE(si.VALIDITE_FIN, SYSDATE))
          INNER JOIN TYPE_PIECE_JOINTE_STATUT tpjs ON si.ID = tpjs.STATUT_INTERVENANT_ID AND (tpjs.PREMIER_RECRUTEMENT = d.PREMIER_RECRUTEMENT) AND (tpjs.HISTO_DESTRUCTEUR_ID IS NULL)
          LEFT JOIN V_PJ_HEURES vheures ON vheures.INTERVENANT_ID = I.ID
          WHERE tpjs.OBLIGATOIRE = 1
          AND (tpjs.SEUIL_HETD IS NULL OR COALESCE(vheures.TOTAL_HEURES, 0) >= tpjs.SEUIL_HETD)
          GROUP BY I.ID, I.SOURCE_CODE, COALESCE(vheures.TOTAL_HEURES, 0)
      ),
      FOURNI_OBLIGATOIRE AS (
          -- nombres de pj OBLIGATOIRES FOURNIES par chaque intervenant
          SELECT I.ID INTERVENANT_ID, I.SOURCE_CODE, count(tpjAttendu.ID) NB /*+ materialize */
          FROM INTERVENANT_EXTERIEUR IE
          INNER JOIN INTERVENANT I ON IE.ID = I.ID AND (I.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN DOSSIER d ON IE.DOSSIER_ID = d.ID AND (d.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN STATUT_INTERVENANT si ON d.STATUT_ID = si.ID AND (si.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN si.VALIDITE_DEBUT AND COALESCE(si.VALIDITE_FIN, SYSDATE))
          INNER JOIN TYPE_PIECE_JOINTE_STATUT tpjs ON si.ID = tpjs.STATUT_INTERVENANT_ID AND (tpjs.PREMIER_RECRUTEMENT = d.PREMIER_RECRUTEMENT) AND (tpjs.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN TYPE_PIECE_JOINTE tpjAttendu ON tpjs.TYPE_PIECE_JOINTE_ID = tpjAttendu.ID AND (tpjAttendu.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN PIECE_JOINTE pj ON d.ID = pj.DOSSIER_ID AND (pj.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN pj.VALIDITE_DEBUT AND COALESCE(pj.VALIDITE_FIN, SYSDATE))
          INNER JOIN TYPE_PIECE_JOINTE tpjFourni ON pj.TYPE_PIECE_JOINTE_ID = tpjFourni.ID AND (tpjFourni.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN tpjFourni.VALIDITE_DEBUT AND COALESCE(tpjFourni.VALIDITE_FIN, SYSDATE))
          LEFT JOIN V_PJ_HEURES vheures ON vheures.INTERVENANT_ID = I.ID
          WHERE tpjs.OBLIGATOIRE = 1
          AND tpjFourni.ID = tpjAttendu.ID
          AND (tpjs.SEUIL_HETD IS NULL OR COALESCE(vheures.TOTAL_HEURES, 0) >= tpjs.SEUIL_HETD)
          -- %s
          AND pj.VALIDATION_ID IS NOT NULL -- %s
          GROUP BY I.ID, I.SOURCE_CODE
      ),
      ATTENDU_FACULTATIF AS (
          -- nombres de pj FACULTATIVES pour chaque intervenant
          SELECT I.ID INTERVENANT_ID, I.SOURCE_CODE, COALESCE(vheures.TOTAL_HEURES, 0) TOTAL_HEURES, count(tpjs.id) NB /*+ materialize */
          FROM INTERVENANT_EXTERIEUR IE
          INNER JOIN INTERVENANT I ON IE.ID = I.ID AND (I.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN DOSSIER d ON IE.DOSSIER_ID = d.ID AND (d.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN STATUT_INTERVENANT si ON d.STATUT_ID = si.ID AND (si.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN si.VALIDITE_DEBUT AND COALESCE(si.VALIDITE_FIN, SYSDATE))
          INNER JOIN TYPE_PIECE_JOINTE_STATUT tpjs ON si.ID = tpjs.STATUT_INTERVENANT_ID AND (tpjs.PREMIER_RECRUTEMENT = d.PREMIER_RECRUTEMENT) AND (tpjs.HISTO_DESTRUCTEUR_ID IS NULL)
          LEFT JOIN V_PJ_HEURES vheures ON vheures.INTERVENANT_ID = I.ID
          WHERE (tpjs.OBLIGATOIRE = 0 OR tpjs.OBLIGATOIRE = 1 AND tpjs.SEUIL_HETD IS NOT NULL AND COALESCE(vheures.TOTAL_HEURES, 0) < tpjs.SEUIL_HETD)
          GROUP BY I.ID, I.SOURCE_CODE, COALESCE(vheures.TOTAL_HEURES, 0)
      ),
      FOURNI_FACULTATIF AS (
          -- nombres de pj FACULTATIVES FOURNIES par chaque intervenant
          SELECT I.ID INTERVENANT_ID, I.SOURCE_CODE, count(tpjAttendu.ID) NB /*+ materialize */
          FROM INTERVENANT_EXTERIEUR IE
          INNER JOIN INTERVENANT I ON IE.ID = I.ID AND (I.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN DOSSIER d ON IE.DOSSIER_ID = d.ID AND (d.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN STATUT_INTERVENANT si ON d.STATUT_ID = si.ID AND (si.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN si.VALIDITE_DEBUT AND COALESCE(si.VALIDITE_FIN, SYSDATE))
          INNER JOIN TYPE_PIECE_JOINTE_STATUT tpjs ON si.ID = tpjs.STATUT_INTERVENANT_ID AND (tpjs.PREMIER_RECRUTEMENT = d.PREMIER_RECRUTEMENT) AND (tpjs.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN TYPE_PIECE_JOINTE tpjAttendu ON tpjs.TYPE_PIECE_JOINTE_ID = tpjAttendu.ID AND (tpjAttendu.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN PIECE_JOINTE pj ON d.ID = pj.DOSSIER_ID AND (pj.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN pj.VALIDITE_DEBUT AND COALESCE(pj.VALIDITE_FIN, SYSDATE))
          INNER JOIN TYPE_PIECE_JOINTE tpjFourni ON pj.TYPE_PIECE_JOINTE_ID = tpjFourni.ID AND (tpjFourni.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN tpjFourni.VALIDITE_DEBUT AND COALESCE(tpjFourni.VALIDITE_FIN, SYSDATE))
          LEFT JOIN V_PJ_HEURES vheures ON vheures.INTERVENANT_ID = I.ID
          WHERE (tpjs.OBLIGATOIRE = 0 OR tpjs.OBLIGATOIRE = 1 AND tpjs.SEUIL_HETD IS NOT NULL AND COALESCE(vheures.TOTAL_HEURES, 0) < tpjs.SEUIL_HETD)
          AND tpjFourni.ID = tpjAttendu.ID
          GROUP BY I.ID, I.SOURCE_CODE
      )
      SELECT
          COALESCE(AO.INTERVENANT_ID, AF.INTERVENANT_ID) ID,
          COALESCE(AO.SOURCE_CODE, AF.SOURCE_CODE)       SOURCE_CODE,
          COALESCE(AO.TOTAL_HEURES, AF.TOTAL_HEURES)     TOTAL_HEURES,
          COALESCE(AO.NB, 0)                             NB_PJ_OBLIG_ATTENDU,
          COALESCE(FO.NB, 0)                             NB_PJ_OBLIG_FOURNI,
          COALESCE(AF.NB, 0)                             NB_PJ_FACUL_ATTENDU,
          COALESCE(FF.NB, 0)                             NB_PJ_FACUL_FOURNI
      FROM            ATTENDU_OBLIGATOIRE AO
      FULL OUTER JOIN ATTENDU_FACULTATIF  AF ON AF.INTERVENANT_ID = AO.INTERVENANT_ID
      LEFT JOIN       FOURNI_OBLIGATOIRE  FO ON FO.INTERVENANT_ID = AO.INTERVENANT_ID
      LEFT JOIN       FOURNI_FACULTATIF   FF ON FF.INTERVENANT_ID = AF.INTERVENANT_ID
      WHERE COALESCE(AO.INTERVENANT_ID, AF.INTERVENANT_ID) = p_intervenant_id
    )
    WHERE NB_PJ_OBLIG_ATTENDU <= NB_PJ_OBLIG_FOURNI;

    RETURN res;
  END;

  /**
   *
   */
  FUNCTION agrement_cr_fourni (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
    code VARCHAR2(64) := 'CONSEIL_RESTREINT';
  BEGIN
    WITH
    composantes_enseign AS (
        -- composantes d'enseignement par intervenant
        SELECT DISTINCT i.ID, i.source_code, s.structure_ens_id
        FROM service s
        INNER JOIN intervenant i ON i.ID = s.intervenant_id AND (i.histo_destructeur_id IS NULL)
        INNER JOIN STRUCTURE comp ON comp.ID = s.structure_ens_id AND (comp.histo_destructeur_id IS NULL)
        WHERE s.histo_destructeur_id IS NULL
        AND (p_structure_id IS NULL OR p_structure_id IS NOT NULL AND s.structure_ens_id = p_structure_id)
    ),
    agrements_oblig_exist AS (
        -- agréments obligatoires obtenus par intervenant et structure
        SELECT i.ID, i.source_code, A.type_agrement_id, A.ID agrement_id, A.structure_id
        FROM agrement A
        INNER JOIN type_agrement ta ON A.type_agrement_id = ta.ID AND (ta.histo_destructeur_id IS NULL)
        INNER JOIN intervenant i ON A.intervenant_id = i.ID AND (i.histo_destructeur_id IS NULL)
        INNER JOIN type_agrement_statut tas ON i.statut_id = tas.statut_intervenant_id AND ta.ID = tas.type_agrement_id
            AND i.premier_recrutement = tas.premier_recrutement AND tas.obligatoire = 1 AND (tas.histo_destructeur_id IS NULL)
        WHERE A.histo_destructeur_id IS NULL
        AND ta.code = code
        AND (p_structure_id IS NULL OR p_structure_id IS NOT NULL AND A.structure_id = p_structure_id)
    ),
    v_agrement AS (
      -- nombres de composantes d'enseignement et d'agrément obligatoires fournis par intervenant
      SELECT DISTINCT i.ID, i.source_code,
        ( select count(*) from COMPOSANTES_ENSEIGN ce where ce.id = i.id ) nb_comp,
        ( select count(*) from AGREMENTS_OBLIG_EXIST ao where ao.id = i.id ) nb_agrem
      FROM intervenant i
      WHERE i.histo_destructeur_id IS NULL
    )
    SELECT COUNT(*) INTO res
    FROM v_agrement v
    WHERE (
      -- si aucune structure précise n'est spécifiée, on ne retient que les intervenants qui ont au moins un d'agrément CR
      p_structure_id IS NULL AND nb_agrem > 0
      OR
      -- si une structure précise est spécifiée, on ne retient que les intervenants qui ont (au moins) autant d'agréments CR que de composantes d'enseignement
      p_structure_id IS NOT NULL AND v.nb_comp <= nb_agrem
    )
    AND v.id = p_intervenant_id ;

    RETURN res;
  END;

  /**
   *
   */
  FUNCTION agrement_ca_fourni (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
    v_code VARCHAR2(64) := 'CONSEIL_ACADEMIQUE';
  BEGIN
    WITH
    agrements_oblig_exist AS (
        -- agréments obligatoires obtenus par intervenant et structure
        SELECT i.ID, i.source_code, A.type_agrement_id, A.ID agrement_id, A.structure_id
        FROM agrement A
        INNER JOIN type_agrement ta ON A.type_agrement_id = ta.ID AND (ta.histo_destructeur_id IS NULL)
        INNER JOIN intervenant i ON A.intervenant_id = i.ID AND (i.histo_destructeur_id IS NULL)
        INNER JOIN type_agrement_statut tas ON i.statut_id = tas.statut_intervenant_id AND ta.ID = tas.type_agrement_id
            AND i.premier_recrutement = tas.premier_recrutement AND tas.obligatoire = 1 AND (tas.histo_destructeur_id IS NULL)
        WHERE A.histo_destructeur_id IS NULL
        AND ta.code = v_code
    ),
    v_agrement AS (
      -- nombres d'agrément obligatoires fournis par intervenant
      SELECT DISTINCT i.ID, i.source_code,
        ( select count(*) from AGREMENTS_OBLIG_EXIST ao where ao.id = i.id ) nb_agrem
      FROM intervenant i
      WHERE i.histo_destructeur_id IS NULL
    )
    SELECT COUNT(*) INTO res
    FROM v_agrement v
    WHERE nb_agrem > 0
    AND v.id = p_intervenant_id ;

    RETURN res;
  END;

  /**
   *
   */
  FUNCTION possede_contrat (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT count(*) INTO res
    FROM contrat c
    JOIN validation v ON c.validation_id = v.id AND v.histo_destruction IS NULL
    WHERE c.HISTO_DESTRUCTION IS NULL
    AND c.intervenant_id = p_intervenant_id
    AND (p_structure_id IS NULL OR p_structure_id IS NOT NULL AND c.STRUCTURE_ID = p_structure_id)
    AND ROWNUM = 1;

    RETURN res;
  END;

END OSE_WORKFLOW;
/
---------------------------
--Modifié PACKAGE BODY
--UNICAEN_OSE_FORMULE
---------------------------
CREATE OR REPLACE PACKAGE BODY "OSE"."UNICAEN_OSE_FORMULE" AS

  TYPE t_valeurs IS TABLE OF FLOAT INDEX BY PLS_INTEGER;
  TYPE t_tableau IS RECORD (
    valeurs t_valeurs,
    total_service t_valeurs,
    total   FLOAT
  );
  TYPE t_tableaux         IS TABLE OF t_tableau                       INDEX BY PLS_INTEGER;

  t                     t_tableaux;
  service_restant_du    t_valeurs;
  resultat              formule_resultat%rowtype;


  PROCEDURE DEBUG_TAB( tab_index PLS_INTEGER ) IS
    id PLS_INTEGER;
    id2 PLS_INTEGER;
    tab t_tableau;
  BEGIN
    tab := t(tab_index);

    ose_test.echo( 'Intervenant id = ' || resultat.intervenant_id );
    ose_test.echo( 'Tableau numéro ' || tab_index );

    id := ose_formule.d_service.FIRST;
    LOOP EXIT WHEN id IS NULL;
      dbms_output.put( 'Service id=' || lpad(id,6,' ') || ' Total=' || lpad(tab.total_service(id),10,' ') || ', data = ' );

      id2 := ose_formule.d_volume_horaire.FIRST;
      LOOP EXIT WHEN id2 IS NULL;
        IF ose_formule.d_volume_horaire(id2).type_volume_horaire_id = resultat.type_volume_horaire_id
        AND ose_formule.d_volume_horaire(id2).etat_volume_horaire_ordre >= resultat.etat_volume_horaire_id AND ose_formule.d_volume_horaire(id2).service_id = id THEN

          dbms_output.put( lpad(tab.valeurs(id2),10,' ') || ' | ' );

        END IF;
      id2 := ose_formule.d_volume_horaire.NEXT(id2);
      END LOOP;
      dbms_output.new_line;
      id := ose_formule.d_service.NEXT(id);
    END LOOP;


    ose_test.echo( 'TOTAL = ' || LPAD(tab.total, 10, ' ') );
  END;

  FUNCTION C_11( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );

    IF NVL(s.structure_ens_id,0) = NVL(s.structure_aff_id,0) AND s.taux_fc < 1 THEN
      RETURN vh.heures;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_12( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );

    IF NVL(s.structure_ens_id,0) <> NVL(s.structure_aff_id,0) AND s.taux_fc < 1 THEN
      RETURN vh.heures;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_13( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );

    IF NVL(s.structure_ens_id,0) = NVL(s.structure_aff_id,0) AND s.taux_fc = 1 THEN
      RETURN vh.heures;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_14( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );

    IF NVL(s.structure_ens_id,0) <> NVL(s.structure_aff_id,0) AND s.taux_fc = 1 THEN
      RETURN vh.heures;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_15( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    IF NVL(ose_formule.d_intervenant.structure_id,0) = NVL(fr.structure_id,0) THEN
      RETURN fr.heures;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_16( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    IF NVL(ose_formule.d_intervenant.structure_id,0) <> NVL(fr.structure_id,0) AND NVL(fr.structure_id,0) <> ose_divers.STRUCTURE_UNIV_GET_ID THEN
      RETURN fr.heures;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_17( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    IF NVL(fr.structure_id,0) = ose_divers.STRUCTURE_UNIV_GET_ID THEN
      RETURN fr.heures;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_21( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN t(11).valeurs(vh.id) * vh.taux_service_du;
  END;

  FUNCTION C_22( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN t(12).valeurs(vh.id) * vh.taux_service_du;
  END;

  FUNCTION C_23( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN t(13).valeurs(vh.id) * vh.taux_service_du;
  END;

  FUNCTION C_24( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN t(14).valeurs(vh.id) * vh.taux_service_du;
  END;

  FUNCTION C_25( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    RETURN t(15).valeurs( fr.id );
  END;

  FUNCTION C_26( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    RETURN t(16).valeurs( fr.id );
  END;

  FUNCTION C_27( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    RETURN t(17).valeurs( fr.id );
  END;

  FUNCTION C_31 RETURN FLOAT IS
  BEGIN
    RETURN GREATEST( resultat.service_du - t(21).total, 0 );
  END;

  FUNCTION C_32 RETURN FLOAT IS
  BEGIN
    RETURN GREATEST( service_restant_du(31) - t(22).total, 0 );
  END;

  FUNCTION C_33 RETURN FLOAT IS
  BEGIN
    RETURN GREATEST( service_restant_du(32) - t(23).total, 0 );
  END;

  FUNCTION C_34 RETURN FLOAT IS
  BEGIN
    RETURN GREATEST( service_restant_du(33) - t(24).total, 0 );
  END;

  FUNCTION C_35 RETURN FLOAT IS
  BEGIN
    RETURN GREATEST( service_restant_du(34) - t(25).total, 0 );
  END;

  FUNCTION C_36 RETURN FLOAT IS
  BEGIN
    RETURN GREATEST( service_restant_du(35) - t(26).total, 0 );
  END;

  FUNCTION C_37 RETURN FLOAT IS
  BEGIN
    RETURN GREATEST( service_restant_du(36) - t(27).total, 0 );
  END;

  FUNCTION C_41( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF t(21).total > 0 THEN
      RETURN t(21).valeurs(vh.id) / t(21).total;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_42( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF t(22).total > 0 THEN
      RETURN t(22).valeurs(vh.id) / t(22).total;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_43( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF t(23).total > 0 THEN
      RETURN t(23).valeurs(vh.id) / t(23).total;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_44( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF t(24).total > 0 THEN
      RETURN t(24).valeurs(vh.id) / t(24).total;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_45( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    IF t(25).total > 0 THEN
      RETURN t(25).valeurs(fr.id) / t(25).total;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_46( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    IF t(26).total > 0 THEN
      RETURN t(26).valeurs(fr.id) / t(26).total;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_47( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    IF t(27).total > 0 THEN
      RETURN t(27).valeurs(fr.id) / t(27).total;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_51( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN LEAST( resultat.service_du, t(21).total ) * t(41).valeurs(vh.id);
  END;

  FUNCTION C_52( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN LEAST( service_restant_du(31), t(22).total ) * t(42).valeurs(vh.id);
  END;

  FUNCTION C_53( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN LEAST( service_restant_du(32), t(23).total ) * t(43).valeurs(vh.id);
  END;

  FUNCTION C_54( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN LEAST( service_restant_du(33), t(24).total ) * t(44).valeurs(vh.id);
  END;

  FUNCTION C_55( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    RETURN LEAST( service_restant_du(34), t(25).total ) * t(45).valeurs(fr.id);
  END;

  FUNCTION C_56( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    RETURN LEAST( service_restant_du(35), t(26).total ) * t(46).valeurs(fr.id);
  END;

  FUNCTION C_57( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    RETURN LEAST( service_restant_du(36), t(27).total ) * t(47).valeurs(fr.id);
  END;

  FUNCTION C_61( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF t(21).valeurs(vh.id) > 0 THEN
      RETURN t(51).valeurs(vh.id) / t(21).valeurs(vh.id);
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_62( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF t(22).valeurs(vh.id) > 0 THEN
      RETURN t(52).valeurs(vh.id) / t(22).valeurs(vh.id);
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_63( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF t(23).valeurs(vh.id) > 0 THEN
      RETURN t(53).valeurs(vh.id) / t(23).valeurs(vh.id);
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_64( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF t(24).valeurs(vh.id) > 0 THEN
      RETURN t(54).valeurs(vh.id) / t(24).valeurs(vh.id);
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_65( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    IF t(25).valeurs(fr.id) > 0 THEN
      RETURN t(55).valeurs(fr.id) / t(25).valeurs(fr.id);
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_66( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    IF t(26).valeurs(fr.id) > 0 THEN
      RETURN t(56).valeurs(fr.id) / t(26).valeurs(fr.id);
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_67( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    IF t(27).valeurs(fr.id) > 0 THEN
      RETURN t(57).valeurs(fr.id) / t(27).valeurs(fr.id);
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_71( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF service_restant_du(37) > 0 THEN
      RETURN 0;
    ELSE
      RETURN 1 - t(61).valeurs(vh.id);
    END IF;
  END;

  FUNCTION C_72( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF service_restant_du(37) > 0 THEN
      RETURN 0;
    ELSE
      RETURN 1 - t(62).valeurs(vh.id);
    END IF;
  END;

  FUNCTION C_73( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF service_restant_du(37) > 0 THEN
      RETURN 0;
    ELSE
      RETURN 1 - t(63).valeurs(vh.id);
    END IF;
  END;

  FUNCTION C_74( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF service_restant_du(37) > 0 THEN
      RETURN 0;
    ELSE
      RETURN 1 - t(64).valeurs(vh.id);
    END IF;
  END;

  FUNCTION C_75( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    IF service_restant_du(37) > 0 THEN
      RETURN 0;
    ELSE
      RETURN 1 - t(65).valeurs(fr.id);
    END IF;
  END;

  FUNCTION C_76( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    IF service_restant_du(37) > 0 THEN
      RETURN 0;
    ELSE
      RETURN 1 - t(66).valeurs(fr.id);
    END IF;
  END;

  FUNCTION C_77( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    IF service_restant_du(37) > 0 THEN
      RETURN 0;
    ELSE
      RETURN 1 - t(67).valeurs(fr.id);
    END IF;
  END;

  FUNCTION C_81( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN t(11).valeurs(vh.id) * vh.taux_service_compl * t(71).valeurs(vh.id);
  END;

  FUNCTION C_82( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN t(12).valeurs(vh.id) * vh.taux_service_compl * t(72).valeurs(vh.id);
  END;

  FUNCTION C_83( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN t(13).valeurs(vh.id) * vh.taux_service_compl * t(73).valeurs(vh.id);
  END;

  FUNCTION C_84( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN t(14).valeurs(vh.id) * vh.taux_service_compl * t(74).valeurs(vh.id);
  END;

  FUNCTION C_85( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    RETURN t(15).valeurs(fr.id) * t(75).valeurs(fr.id);
  END;

  FUNCTION C_86( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    RETURN t(16).valeurs(fr.id) * t(76).valeurs(fr.id);
  END;

  FUNCTION C_87( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    RETURN t(17).valeurs(fr.id) * t(77).valeurs(fr.id);
  END;

  FUNCTION C_93( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );

    IF s.taux_fc = 1 THEN
      RETURN t(83).valeurs(vh.id) * s.ponderation_service_compl;
    ELSE
      RETURN t(83).valeurs(vh.id);
    END IF;
  END;

  FUNCTION C_94( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );

    IF s.taux_fc = 1 THEN
      RETURN t(84).valeurs(vh.id) * s.ponderation_service_compl;
    ELSE
      RETURN t(84).valeurs(vh.id);
    END IF;
  END;

  FUNCTION C_101( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );

    RETURN t(81).valeurs(vh.id) * ( s.taux_fi + s.taux_fa );
  END;

  FUNCTION C_102( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );

    RETURN t(82).valeurs(vh.id) * ( s.taux_fi + s.taux_fa );
  END;

  FUNCTION C_103( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );

    RETURN (t(93).valeurs(vh.id) + t(81).valeurs(vh.id)) * s.taux_fc;
  END;

  FUNCTION C_104( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );

    RETURN (t(94).valeurs(vh.id) + t(82).valeurs(vh.id)) * s.taux_fc;
  END;

  FUNCTION RS_1( s ose_formule.t_service ) RETURN FLOAT IS
  BEGIN
    RETURN t(51).total_service( s.id ) + t(52).total_service( s.id ) + t(53).total_service( s.id ) + t(54).total_service( s.id );
  END;

  FUNCTION RS_2( s ose_formule.t_service ) RETURN FLOAT IS
  BEGIN
    RETURN t(101).total_service( s.id ) + t(102).total_service( s.id );
  END;

  FUNCTION RS_3( s ose_formule.t_service ) RETURN FLOAT IS
  BEGIN
    RETURN t(103).total_service( s.id ) + t(104).total_service( s.id );
  END;

  FUNCTION RS_4( r ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    RETURN t(55).total_service( r.id ) + t(56).total_service( r.id ) + t(57).total_service( r.id );
  END;

  FUNCTION RS_5( r ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    RETURN t(85).total_service( r.id ) + t(86).total_service( r.id ) + t(87).total_service( r.id );
  END;

  FUNCTION RVH_1( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN t(51).valeurs(vh.id) + t(52).valeurs(vh.id) + t(53).valeurs(vh.id) + t(54).valeurs(vh.id);
  END;

  FUNCTION RVH_2( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN t(101).valeurs(vh.id) + t(102).valeurs(vh.id);
  END;

  FUNCTION RVH_3( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN t(103).valeurs(vh.id) + t(104).valeurs(vh.id);
  END;

  PROCEDURE SET_T_VALUE( tab_index PLS_INTEGER, service_id PLS_INTEGER, id PLS_INTEGER, val FLOAT ) IS
  BEGIN
    t(tab_index).valeurs(id) := val;
    t(tab_index).total_service( service_id )   := t(tab_index).total_service( service_id ) + val;
    t(tab_index).total                         := t(tab_index).total + val;
  END;

  PROCEDURE CALCUL_VOLUME_HORAIRE( tab_index PLS_INTEGER, id NUMERIC ) IS
    res FLOAT;
    param ose_formule.t_volume_horaire;
  BEGIN
    param := ose_formule.d_volume_horaire(id);
    res := CASE tab_index
       WHEN  11 THEN  C_11( param ) WHEN  12 THEN  C_12( param ) WHEN  13 THEN  C_13( param ) WHEN  14 THEN  C_14( param )
       WHEN  21 THEN  C_21( param ) WHEN  22 THEN  C_22( param ) WHEN  23 THEN  C_23( param ) WHEN  24 THEN  C_24( param )
       WHEN  41 THEN  C_41( param ) WHEN  42 THEN  C_42( param ) WHEN  43 THEN  C_43( param ) WHEN  44 THEN  C_44( param )
       WHEN  51 THEN  C_51( param ) WHEN  52 THEN  C_52( param ) WHEN  53 THEN  C_53( param ) WHEN  54 THEN  C_54( param )
       WHEN  61 THEN  C_61( param ) WHEN  62 THEN  C_62( param ) WHEN  63 THEN  C_63( param ) WHEN  64 THEN  C_64( param )
       WHEN  71 THEN  C_71( param ) WHEN  72 THEN  C_72( param ) WHEN  73 THEN  C_73( param ) WHEN  74 THEN  C_74( param )
       WHEN  81 THEN  C_81( param ) WHEN  82 THEN  C_82( param ) WHEN  83 THEN  C_83( param ) WHEN  84 THEN  C_84( param )
                                                                 WHEN  93 THEN  C_93( param ) WHEN  94 THEN  C_94( param )
       WHEN 101 THEN C_101( param ) WHEN 102 THEN C_102( param ) WHEN 103 THEN C_103( param ) WHEN 104 THEN C_104( param )
    END;
    SET_T_VALUE( tab_index, param.service_id, id, res );
  END;

  PROCEDURE CALCUL_SERVICE_RESTANT_DU( tab_index PLS_INTEGER ) IS
    res FLOAT;
  BEGIN
    res := CASE tab_index
      WHEN 31 THEN C_31  WHEN 32 THEN C_32  WHEN 33 THEN C_33
      WHEN 34 THEN C_34  WHEN 35 THEN C_35  WHEN 36 THEN C_36
      WHEN 37 THEN C_37
    END;
    service_restant_du(tab_index) := res;
  END;

  PROCEDURE CALCUL_REFERENTIEL( tab_index PLS_INTEGER, id NUMERIC ) IS
    res FLOAT;
    param ose_formule.t_referentiel;
  BEGIN
    param := ose_formule.d_referentiel(id);
    res := CASE tab_index
      WHEN 15 THEN C_15( param )  WHEN 16 THEN C_16( param )  WHEN 17 THEN C_17( param )
      WHEN 25 THEN C_25( param )  WHEN 26 THEN C_26( param )  WHEN 27 THEN C_27( param )
      WHEN 45 THEN C_45( param )  WHEN 46 THEN C_46( param )  WHEN 47 THEN C_47( param )
      WHEN 55 THEN C_55( param )  WHEN 56 THEN C_56( param )  WHEN 57 THEN C_57( param )
      WHEN 65 THEN C_65( param )  WHEN 66 THEN C_66( param )  WHEN 67 THEN C_67( param )
      WHEN 75 THEN C_75( param )  WHEN 76 THEN C_76( param )  WHEN 77 THEN C_77( param )
      WHEN 85 THEN C_85( param )  WHEN 86 THEN C_86( param )  WHEN 87 THEN C_87( param )
    END;
    SET_T_VALUE( tab_index, id, id, res );
  END;

  PROCEDURE P_CALCUL_RESULTAT_V2( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC, TYPE_VOLUME_HORAIRE_ID NUMERIC, ETAT_VOLUME_HORAIRE_ID NUMERIC ) IS
    current_tableau           PLS_INTEGER;
    id                        PLS_INTEGER;
    id2                       PLS_INTEGER;
    val                       FLOAT;
    etat_volume_horaire_ordre NUMERIC;
    TYPE t_liste_tableaux   IS VARRAY (100) OF PLS_INTEGER;
    liste_tableaux            t_liste_tableaux;
    EVH_ORDRE NUMERIC;
  BEGIN
-- Initialisation
    SELECT ordre INTO EVH_ORDRE FROM etat_volume_horaire WHERE ID = P_CALCUL_RESULTAT_V2.ETAT_VOLUME_HORAIRE_ID;
    liste_tableaux := t_liste_tableaux();
    t.delete;
    service_restant_du.delete;

    resultat := ose_formule.nouveau_resultat;
    resultat.intervenant_id           := INTERVENANT_ID;
    resultat.annee_id                 := ANNEE_ID;
    resultat.type_volume_horaire_id   := TYPE_VOLUME_HORAIRE_ID;
    resultat.etat_volume_horaire_id   := ETAT_VOLUME_HORAIRE_ID;
    resultat.service_du               := ose_formule.d_intervenant.heures_service_statutaire + ose_formule.d_intervenant.heures_service_modifie;

    liste_tableaux := t_liste_tableaux(
       11,  12,  13,  14,  15,  16,  17,
       21,  22,  23,  24,  25,  26,  27,
       31,  32,  33,  34,  35,  36,  37,
       41,  42,  43,  44,  45,  46,  47,
       51,  52,  53,  54,  55,  56,  57,
       61,  62,  63,  64,  65,  66,  67,
       71,  72,  73,  74,  75,  76,  77,
       81,  82,  83,  84,  85,  86,  87,
                 93,  94,
      101, 102, 103, 104
    );

    id := ose_formule.d_volume_horaire.FIRST;
    LOOP EXIT WHEN id IS NULL;
      IF
        ose_formule.d_volume_horaire(id).type_volume_horaire_id = P_CALCUL_RESULTAT_V2.TYPE_VOLUME_HORAIRE_ID
        AND ose_formule.d_volume_horaire(id).etat_volume_horaire_ordre >= EVH_ORDRE
      THEN
        resultat.service := resultat.service + ose_formule.d_volume_horaire( id ).heures;
      END IF;
      id := ose_formule.d_volume_horaire.NEXT(id);
    END LOOP;

    FOR i IN liste_tableaux.FIRST .. liste_tableaux.LAST
    LOOP
      current_tableau := liste_tableaux(i);

      IF current_tableau IN ( -- calcul pour les services
         11,  12,  13,  14,
         21,  22,  23,  24,
         41,  42,  43,  44,
         51,  52,  53,  54,
         61,  62,  63,  64,
         71,  72,  73,  74,
         81,  82,  83,  84,
                   93,  94,
        101, 102, 103, 104
      ) THEN
        t(current_tableau).total := 0;
        id2 := ose_formule.d_service.FIRST;
        LOOP EXIT WHEN id2 IS NULL;
          t(current_tableau).total_service(id2) := 0;
          id2 := ose_formule.d_service.NEXT(id2);
        END LOOP;
        id := ose_formule.d_volume_horaire.FIRST;
        LOOP EXIT WHEN id IS NULL;
          IF
            ose_formule.d_volume_horaire(id).type_volume_horaire_id = P_CALCUL_RESULTAT_V2.TYPE_VOLUME_HORAIRE_ID
            AND ose_formule.d_volume_horaire(id).etat_volume_horaire_ordre >= EVH_ORDRE
          THEN
            CALCUL_VOLUME_HORAIRE( current_tableau, id );
          END IF;
          id := ose_formule.d_volume_horaire.NEXT(id);
        END LOOP;

      ELSIF current_tableau IN ( -- calcul des services restants dus
        31, 32, 33, 34, 35, 36, 37
      ) THEN
        CALCUL_SERVICE_RESTANT_DU( current_tableau );

      ELSIF current_tableau IN ( -- tableaux de calcul du référentiel
         15,  16,  17,
         25,  26,  27,
         45,  46,  47,
         55,  56,  57,
         65,  66,  67,
         75,  76,  77,
         85,  86,  87
      ) THEN

        t(current_tableau).total := 0;
        id := ose_formule.d_referentiel.FIRST;
        LOOP EXIT WHEN id IS NULL;
          t(current_tableau).total_service(id) := 0;
          CALCUL_REFERENTIEL( current_tableau, id );
          id := ose_formule.d_referentiel.NEXT(id);
        END LOOP;

      END IF;
    END LOOP;

    resultat.enseignements            := t(51).total + t(52).total + t(53).total + t(54).total + t(81).total + t(82).total + t(93).total + t(94).total;
    resultat.referentiel              := t(55).total + t(56).total + t(57).total + t(85).total + t(86).total + t(87).total;
    resultat.service_assure           := resultat.enseignements + resultat.referentiel;
    resultat.heures_compl_fi          := t(101).total + t(102).total;
    resultat.heures_compl_fc          := t(103).total + t(104).total;
    resultat.heures_compl_referentiel := t(85).total + t(86).total + t(87).total;
    resultat.heures_solde             := resultat.service_assure - resultat.service_du;
    IF resultat.heures_solde >= 0 THEN
      resultat.sous_service           := 0;
      resultat.heures_compl_total     := resultat.heures_solde;
    ELSE
      resultat.sous_service           := resultat.heures_solde * -1;
      resultat.heures_compl_total     := 0;
    END IF;
  END;


  PROCEDURE CALCUL_RESULTAT_V2( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC, TYPE_VOLUME_HORAIRE_ID NUMERIC, ETAT_VOLUME_HORAIRE_ID NUMERIC ) IS
    id                    PLS_INTEGER;
    dev_null              NUMERIC;
    res_service           formule_resultat_service%rowtype;
    res_vh                formule_resultat_vh%rowtype;
    res_ref               formule_resultat_referentiel%rowtype;
    EVH_ORDRE             NUMERIC;
  BEGIN
    P_CALCUL_RESULTAT_V2( INTERVENANT_ID, ANNEE_ID, TYPE_VOLUME_HORAIRE_ID, ETAT_VOLUME_HORAIRE_ID );
    SELECT ordre INTO EVH_ORDRE FROM etat_volume_horaire WHERE ID = CALCUL_RESULTAT_V2.ETAT_VOLUME_HORAIRE_ID;
    resultat.id := OSE_FORMULE.ENREGISTRER_RESULTAT( resultat );

    -- répartition des résultats par service
    id := ose_formule.d_service.FIRST;
    LOOP EXIT WHEN id IS NULL;
      res_service := ose_formule.nouveau_resultat_service;
      res_service.formule_resultat_id := resultat.id;
      res_service.service_id          := id;
      -- calcul des chiffres...
      res_service.heures_service      := RS_1( ose_formule.d_service(id) );
      res_service.heures_compl_fi     := RS_2( ose_formule.d_service(id) );
      res_service.heures_compl_fc     := RS_3( ose_formule.d_service(id) );
      res_service.service_assure      := res_service.heures_service + res_service.heures_compl_fi + res_service.heures_compl_fa + res_service.heures_compl_fc;
      dev_null := ose_formule.ENREGISTRER_RESULTAT_SERVICE( res_service );
      id := ose_formule.d_service.NEXT(id);
    END LOOP;

    -- répartition des résultats par volumes horaires
    id := ose_formule.d_volume_horaire.FIRST;
    LOOP EXIT WHEN id IS NULL;
      IF
        ose_formule.d_volume_horaire(id).type_volume_horaire_id = CALCUL_RESULTAT_V2.TYPE_VOLUME_HORAIRE_ID
        AND ose_formule.d_volume_horaire(id).etat_volume_horaire_ordre >= EVH_ORDRE
      THEN
        res_vh := ose_formule.nouveau_resultat_vh;
        res_vh.formule_resultat_id := resultat.id;
        res_vh.volume_horaire_id   := id;
        -- calcul des chiffres...
        res_vh.heures_service      := RVH_1( ose_formule.d_volume_horaire(id) );
        res_vh.heures_compl_fi     := RVH_2( ose_formule.d_volume_horaire(id) );
        res_vh.heures_compl_fc     := RVH_3( ose_formule.d_volume_horaire(id) );
        res_vh.service_assure      := res_vh.heures_service + res_vh.heures_compl_fi + res_vh.heures_compl_fa + res_vh.heures_compl_fc;
        dev_null := ose_formule.ENREGISTRER_RESULTAT_VH( res_vh );
      END IF;
      id := ose_formule.d_volume_horaire.NEXT(id);
    END LOOP;

    -- répartition des résultats par service référentiel
    id := ose_formule.d_referentiel.FIRST;
    LOOP EXIT WHEN id IS NULL;
      res_ref := ose_formule.nouveau_resultat_ref;
      res_ref.formule_resultat_id      := resultat.id;
      res_ref.service_referentiel_id   := id;
      -- calcul des chiffres...
      res_ref.heures_service           := RS_4( ose_formule.d_referentiel(id) );
      res_ref.heures_compl_referentiel := RS_5( ose_formule.d_referentiel(id) );
      res_ref.service_assure      := res_ref.heures_service + res_ref.heures_compl_referentiel;
      dev_null := ose_formule.ENREGISTRER_RESULTAT_REF( res_ref );
      id := ose_formule.d_referentiel.NEXT(id);
    END LOOP;

  END;

  PROCEDURE DEBUG_RESULTAT_V2( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC, TYPE_VOLUME_HORAIRE_ID NUMERIC, ETAT_VOLUME_HORAIRE_ID NUMERIC, TAB_ID PLS_INTEGER ) IS
  BEGIN
    OSE_FORMULE.POPULATE( INTERVENANT_ID, ANNEE_ID );
    P_CALCUL_RESULTAT_V2( INTERVENANT_ID, ANNEE_ID, TYPE_VOLUME_HORAIRE_ID, ETAT_VOLUME_HORAIRE_ID );
    DEBUG_TAB(TAB_ID);
  END;

END UNICAEN_OSE_FORMULE;
/
---------------------------
--Modifié PACKAGE BODY
--OSE_FORMULE
---------------------------
CREATE OR REPLACE PACKAGE BODY "OSE"."OSE_FORMULE" AS

  v_date_obs DATE;



  FUNCTION GET_DATE_OBS RETURN DATE AS
  BEGIN
    RETURN COALESCE( v_date_obs, SYSDATE );
  END;

  FUNCTION SET_DATE_OBS( DATE_OBS DATE DEFAULT NULL ) RETURN DATE IS
  BEGIN
    v_date_obs := DATE_OBS;
    RETURN v_date_obs;
  END;


  PROCEDURE DEMANDE_CALCUL( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC ) IS
  BEGIN
    MERGE INTO formule_resultat_maj frm USING dual ON (
          frm.INTERVENANT_ID                = DEMANDE_CALCUL.INTERVENANT_ID
      AND frm.ANNEE_ID                      = DEMANDE_CALCUL.ANNEE_ID
    )
    WHEN NOT MATCHED THEN INSERT (
      ID,
      INTERVENANT_ID,
      ANNEE_ID
    ) VALUES (
      FORMULE_RESULTAT_MAJ_ID_SEQ.NEXTVAL,
      DEMANDE_CALCUL.INTERVENANT_ID,
      DEMANDE_CALCUL.ANNEE_ID
    );
  END;

/*
  PROCEDURE MAJ_RESULTAT( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC )IS
  BEGIN
    DELETE FROM -- pour éliminer les anciens résultats avec des états non corrects
      formule_resultat
    WHERE
          intervenant_id = MAJ_RESULTAT.INTERVENANT_ID
      AND annee_id       = MAJ_RESULTAT.ANNEE_ID;

    FOR fr IN ( -- on ne prend que les plus grands états de volumes horaires car les plus petits sont toujours remis à jour!!
      SELECT DISTINCT type_volume_horaire_id, MAX(etat_volume_horaire_id) etat_volume_horaire_id
      FROM formule_volume_horaire
      WHERE intervenant_id = MAJ_RESULTAT.INTERVENANT_ID AND annee_id = MAJ_RESULTAT.ANNEE_ID
      GROUP BY type_volume_horaire_id
    ) LOOP
      MAJ_RESULTAT( INTERVENANT_ID, ANNEE_ID, fr.type_volume_horaire_id, fr.etat_volume_horaire_id );
    END LOOP;
  END;*/



  PROCEDURE CALCULER_TOUT IS
    a_id NUMERIC;
  BEGIN
    a_id := OSE_PARAMETRE.GET_ANNEE;
    FOR mp IN (
      SELECT DISTINCT
        intervenant_id
      FROM
        service s
        JOIN intervenant i ON i.id = s.intervenant_id AND 1 = ose_divers.comprise_entre( i.histo_creation, i.histo_destruction, ose_formule.get_date_obs )
      WHERE
        1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction, ose_formule.get_date_obs )
        AND s.annee_id = a_id

      UNION

      SELECT DISTINCT
        intervenant_id
      FROM
        service_referentiel sr
        JOIN intervenant i ON i.id = sr.intervenant_id AND 1 = ose_divers.comprise_entre( i.histo_creation, i.histo_destruction, ose_formule.get_date_obs )
      WHERE
        1 = ose_divers.comprise_entre( sr.histo_creation, sr.histo_destruction, ose_formule.get_date_obs )
        AND sr.annee_id = a_id

    )
    LOOP
      CALCULER( mp.intervenant_id, a_id );
    END LOOP;
  END;


  PROCEDURE CALCULER_SUR_DEMANDE IS
  BEGIN
    FOR mp IN (SELECT DISTINCT intervenant_id, annee_id FROM formule_resultat_maj)
    LOOP
      CALCULER( mp.intervenant_id, mp.annee_id );
    END LOOP;
    DELETE FROM formule_resultat_maj;
  END;


  FUNCTION NOUVEAU_RESULTAT RETURN formule_resultat%rowtype IS
    resultat formule_resultat%rowtype;
  BEGIN
    resultat.id                       := NULL;
    resultat.intervenant_id           := NULL;
    resultat.annee_id                 := NULL;
    resultat.type_volume_horaire_id   := NULL;
    resultat.etat_volume_horaire_id   := NULL;
    resultat.service_du               := 0;
    resultat.enseignements            := 0;
    resultat.service                  := 0;
    resultat.referentiel              := 0;
    resultat.service_assure           := 0;
    resultat.heures_solde             := 0;
    resultat.heures_compl_fi          := 0;
    resultat.heures_compl_fa          := 0;
    resultat.heures_compl_fc          := 0;
    resultat.heures_compl_referentiel := 0;
    resultat.heures_compl_total       := 0;
    resultat.sous_service             := 0;
    resultat.a_payer                  := 0;
    RETURN resultat;
  END;


  FUNCTION ENREGISTRER_RESULTAT( fr formule_resultat%rowtype ) RETURN NUMERIC IS
    id NUMERIC;
  BEGIN
    MERGE INTO formule_resultat tfr USING dual ON (

          tfr.intervenant_id         = fr.intervenant_id
      AND tfr.annee_id               = fr.annee_id
      AND tfr.type_volume_horaire_id = fr.type_volume_horaire_id
      AND tfr.etat_volume_horaire_id = fr.etat_volume_horaire_id

    ) WHEN MATCHED THEN UPDATE SET

      service_du                     = fr.service_du,
      enseignements                  = fr.enseignements,
      service                        = fr.service,
      referentiel                    = fr.referentiel,
      service_assure                 = fr.service_assure,
      heures_solde                   = fr.heures_solde,
      heures_compl_fi                = fr.heures_compl_fi,
      heures_compl_fa                = fr.heures_compl_fa,
      heures_compl_fc                = fr.heures_compl_fc,
      heures_compl_referentiel       = fr.heures_compl_referentiel,
      heures_compl_total             = fr.heures_compl_total,
      sous_service                   = fr.sous_service,
      a_payer                        = fr.a_payer,
      to_delete                      = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      INTERVENANT_ID,
      ANNEE_ID,
      TYPE_VOLUME_HORAIRE_ID,
      ETAT_VOLUME_HORAIRE_ID,
      SERVICE_DU,
      SERVICE,
      ENSEIGNEMENTS,
      REFERENTIEL,
      SERVICE_ASSURE,
      HEURES_SOLDE,
      HEURES_COMPL_FI,
      HEURES_COMPL_FA,
      HEURES_COMPL_FC,
      HEURES_COMPL_REFERENTIEL,
      HEURES_COMPL_TOTAL,
      SOUS_SERVICE,
      A_PAYER,
      TO_DELETE

    ) VALUES (

      FORMULE_RESULTAT_ID_SEQ.NEXTVAL,
      fr.intervenant_id,
      fr.annee_id,
      fr.type_volume_horaire_id,
      fr.etat_volume_horaire_id,
      fr.service_du,
      fr.service,
      fr.enseignements,
      fr.referentiel,
      fr.service_assure,
      fr.heures_solde,
      fr.heures_compl_fi,
      fr.heures_compl_fa,
      fr.heures_compl_fc,
      fr.heures_compl_referentiel,
      fr.heures_compl_total,
      fr.sous_service,
      fr.a_payer,
      0

    );

    SELECT id INTO id FROM formule_resultat tfr WHERE
          tfr.intervenant_id         = fr.intervenant_id
      AND tfr.annee_id               = fr.annee_id
      AND tfr.type_volume_horaire_id = fr.type_volume_horaire_id
      AND tfr.etat_volume_horaire_id = fr.etat_volume_horaire_id;
    RETURN id;
  END;



  FUNCTION NOUVEAU_RESULTAT_SERVICE RETURN formule_resultat_service%rowtype IS
    fs formule_resultat_service%rowtype;
  BEGIN
    fs.id                  := NULL;
    fs.formule_resultat_id := NULL;
    fs.service_id          := NULL;
    fs.service_assure      := 0;
    fs.heures_service      := 0;
    fs.heures_compl_fi     := 0;
    fs.heures_compl_fa     := 0;
    fs.heures_compl_fc     := 0;
    RETURN fs;
  END;



  FUNCTION ENREGISTRER_RESULTAT_SERVICE( fs formule_resultat_service%rowtype ) RETURN NUMERIC IS
    id NUMERIC;
  BEGIN
    MERGE INTO formule_resultat_service tfs USING dual ON (

          tfs.formule_resultat_id    = fs.formule_resultat_id
      AND tfs.service_id             = fs.service_id

    ) WHEN MATCHED THEN UPDATE SET

      service_assure                 = fs.service_assure,
      heures_service                 = fs.heures_service,
      heures_compl_fi                = fs.heures_compl_fi,
      heures_compl_fa                = fs.heures_compl_fa,
      heures_compl_fc                = fs.heures_compl_fc,
      TO_DELETE                      = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      FORMULE_RESULTAT_ID,
      SERVICE_ID,
      SERVICE_ASSURE,
      HEURES_SERVICE,
      HEURES_COMPL_FI,
      HEURES_COMPL_FA,
      HEURES_COMPL_FC,
      TO_DELETE

    ) VALUES (

      FORMULE_RESULTAT_SERVIC_ID_SEQ.NEXTVAL,
      fs.formule_resultat_id,
      fs.service_id,
      fs.service_assure,
      fs.heures_service,
      fs.heures_compl_fi,
      fs.heures_compl_fa,
      fs.heures_compl_fc,
      0

    );

    SELECT id INTO id FROM formule_resultat_service tfs WHERE
          tfs.formule_resultat_id    = fs.formule_resultat_id
      AND tfs.service_id             = fs.service_id;
    RETURN id;
  END;


  FUNCTION NOUVEAU_RESULTAT_VH RETURN formule_resultat_vh%rowtype IS
    fvh formule_resultat_vh%rowtype;
  BEGIN
    fvh.id                  := NULL;
    fvh.formule_resultat_id := NULL;
    fvh.volume_horaire_id   := NULL;
    fvh.service_assure      := 0;
    fvh.heures_service      := 0;
    fvh.heures_compl_fi     := 0;
    fvh.heures_compl_fa     := 0;
    fvh.heures_compl_fc     := 0;
    RETURN fvh;
  END;



  FUNCTION ENREGISTRER_RESULTAT_VH( fvh formule_resultat_vh%rowtype ) RETURN NUMERIC IS
    id NUMERIC;
  BEGIN
    MERGE INTO formule_resultat_vh tfvh USING dual ON (

          tfvh.formule_resultat_id    = fvh.formule_resultat_id
      AND tfvh.volume_horaire_id      = fvh.volume_horaire_id

    ) WHEN MATCHED THEN UPDATE SET

      service_assure                 = fvh.service_assure,
      heures_service                 = fvh.heures_service,
      heures_compl_fi                = fvh.heures_compl_fi,
      heures_compl_fa                = fvh.heures_compl_fa,
      heures_compl_fc                = fvh.heures_compl_fc,
      TO_DELETE                      = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      FORMULE_RESULTAT_ID,
      VOLUME_HORAIRE_ID,
      SERVICE_ASSURE,
      HEURES_SERVICE,
      HEURES_COMPL_FI,
      HEURES_COMPL_FA,
      HEURES_COMPL_FC,
      TO_DELETE

    ) VALUES (

      FORMULE_RESULTAT_VH_ID_SEQ.NEXTVAL,
      fvh.formule_resultat_id,
      fvh.volume_horaire_id,
      fvh.service_assure,
      fvh.heures_service,
      fvh.heures_compl_fi,
      fvh.heures_compl_fa,
      fvh.heures_compl_fc,
      0

    );

    SELECT id INTO id FROM formule_resultat_vh tfvh WHERE
          tfvh.formule_resultat_id    = fvh.formule_resultat_id
      AND tfvh.volume_horaire_id      = fvh.volume_horaire_id;
    RETURN id;
  END;


  FUNCTION NOUVEAU_RESULTAT_REF RETURN formule_resultat_referentiel%rowtype IS
    fr formule_resultat_referentiel%rowtype;
  BEGIN
    fr.id                       := NULL;
    fr.formule_resultat_id      := NULL;
    fr.service_referentiel_id   := NULL;
    fr.service_assure           := 0;
    fr.heures_service           := 0;
    fr.heures_compl_referentiel := 0;
    RETURN fr;
  END;



  FUNCTION ENREGISTRER_RESULTAT_REF( fr formule_resultat_referentiel%rowtype ) RETURN NUMERIC IS
    id NUMERIC;
  BEGIN
    MERGE INTO formule_resultat_referentiel tfr USING dual ON (

          tfr.formule_resultat_id    = fr.formule_resultat_id
      AND tfr.service_referentiel_id = fr.service_referentiel_id

    ) WHEN MATCHED THEN UPDATE SET

      service_assure                 = fr.service_assure,
      heures_service                 = fr.heures_service,
      heures_compl_referentiel       = fr.heures_compl_referentiel,
      TO_DELETE                      = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      FORMULE_RESULTAT_ID,
      SERVICE_REFERENTIEL_ID,
      SERVICE_ASSURE,
      HEURES_SERVICE,
      HEURES_COMPL_REFERENTIEL,
      TO_DELETE

    ) VALUES (

      FORMULE_RESULTAT_REFERE_ID_SEQ.NEXTVAL,
      fr.formule_resultat_id,
      fr.service_referentiel_id,
      fr.service_assure,
      fr.heures_service,
      fr.heures_compl_referentiel,
      0

    );

    SELECT id INTO id FROM formule_resultat_referentiel tfr WHERE
          tfr.formule_resultat_id    = fr.formule_resultat_id
      AND tfr.service_referentiel_id = fr.service_referentiel_id;

    RETURN id;
  END;


  PROCEDURE POPULATE_INTERVENANT( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC, d_intervenant OUT t_intervenant ) IS
  BEGIN

    SELECT
      structure_id,
      heures_service_statutaire
    INTO
      d_intervenant.structure_id,
      d_intervenant.heures_service_statutaire
    FROM
      v_formule_intervenant fi
    WHERE
      fi.id = POPULATE_INTERVENANT.INTERVENANT_ID;

    SELECT
      NVL( SUM(heures), 0)
    INTO
      d_intervenant.heures_service_modifie
    FROM
      v_formule_service_modifie fsm
    WHERE
      fsm.intervenant_id = POPULATE_INTERVENANT.INTERVENANT_ID
      AND fsm.annee_id   = POPULATE_INTERVENANT.ANNEE_ID;

  EXCEPTION WHEN NO_DATA_FOUND THEN
    d_intervenant.structure_id := null;
    d_intervenant.heures_service_statutaire := null;
  END;


  PROCEDURE POPULATE_REFERENTIEL( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC, d_referentiel OUT t_lst_referentiel ) IS
    i PLS_INTEGER;
  BEGIN
    d_referentiel.delete;

    FOR d IN (
      SELECT
        fr.id,
        fr.structure_id,
        fr.heures
      FROM
        v_formule_referentiel fr
      WHERE
        fr.intervenant_id = POPULATE_REFERENTIEL.INTERVENANT_ID
        AND fr.annee_id   = POPULATE_REFERENTIEL.ANNEE_ID
        AND fr.heures > 0
    ) LOOP
      d_referentiel( d.id ).id           := d.id;
      d_referentiel( d.id ).structure_id := d.structure_id;
      d_referentiel( d.id ).heures       := d.heures;
    END LOOP;

/*
    i := liste_referentiel.FIRST;
    LOOP EXIT WHEN i IS NULL;
--      ose_test.echo('id = ' || i );
      ose_test.echo('structure_id = ' || liste_referentiel( i ).structure_id );
      ose_test.echo('heures = ' || liste_referentiel( i ).heures );

      i := liste_referentiel.NEXT(i);
    END LOOP;*/

  END;


  PROCEDURE POPULATE_SERVICE( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC, d_service OUT t_lst_service ) IS
  BEGIN
    d_service.delete;

    FOR d IN (
      SELECT
        id,
        taux_fi,
        taux_fa,
        taux_fc,
        structure_aff_id,
        structure_ens_id,
        ponderation_service_du,
        ponderation_service_compl
      FROM
        v_formule_service fs
      WHERE
        fs.intervenant_id = POPULATE_SERVICE.INTERVENANT_ID
        AND fs.annee_id   = POPULATE_SERVICE.ANNEE_ID
    ) LOOP
      d_service( d.id ).id                        := d.id;
      d_service( d.id ).taux_fi                   := d.taux_fi;
      d_service( d.id ).taux_fa                   := d.taux_fa;
      d_service( d.id ).taux_fc                   := d.taux_fc;
      d_service( d.id ).ponderation_service_du    := d.ponderation_service_du;
      d_service( d.id ).ponderation_service_compl := d.ponderation_service_compl;
      d_service( d.id ).structure_aff_id          := d.structure_aff_id;
      d_service( d.id ).structure_ens_id          := d.structure_ens_id;
    END LOOP;
  END;


  PROCEDURE POPULATE_VOLUME_HORAIRE( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC, d_volume_horaire OUT t_lst_volume_horaire ) IS
  BEGIN
    d_volume_horaire.delete;

    FOR d IN (
      SELECT
        id,
        service_id,
        heures,
        taux_service_du,
        taux_service_compl,
        fvh.type_volume_horaire_id,
        fvh.etat_volume_horaire_id,
        fvh.etat_volume_horaire_ordre
      FROM
        v_formule_volume_horaire fvh
      WHERE
        fvh.intervenant_id                = POPULATE_VOLUME_HORAIRE.INTERVENANT_ID
        AND fvh.annee_id                  = POPULATE_VOLUME_HORAIRE.ANNEE_ID
    ) LOOP
      d_volume_horaire( d.id ).id                        := d.id;
      d_volume_horaire( d.id ).service_id                := d.service_id;
      d_volume_horaire( d.id ).heures                    := d.heures;
      d_volume_horaire( d.id ).taux_service_du           := d.taux_service_du;
      d_volume_horaire( d.id ).taux_service_compl        := d.taux_service_compl;
      d_volume_horaire( d.id ).type_volume_horaire_id    := d.type_volume_horaire_id;
      d_volume_horaire( d.id ).etat_volume_horaire_id    := d.etat_volume_horaire_id;
      d_volume_horaire( d.id ).etat_volume_horaire_ordre := d.etat_volume_horaire_ordre;
    END LOOP;
  END;


  PROCEDURE POPULATE_TYPE_ETAT_VH( d_volume_horaire t_lst_volume_horaire, d_type_etat_vh OUT t_lst_type_etat_vh ) IS
    TYPE t_ordres IS TABLE OF NUMERIC INDEX BY PLS_INTEGER;

    ordres_found t_ordres;
    ordres_exists t_ordres;
    type_volume_horaire_id PLS_INTEGER;
    etat_volume_horaire_ordre PLS_INTEGER;
    id PLS_INTEGER;
  BEGIN
    d_type_etat_vh.delete;

    -- récupération des ID et ordres de volumes horaires
    FOR evh IN (
      SELECT   id, ordre
      FROM     etat_volume_horaire evh
      WHERE    OSE_DIVERS.COMPRISE_ENTRE( evh.histo_creation, evh.histo_destruction ) = 1
      ORDER BY ordre
    ) LOOP
      ordres_exists( evh.ordre ) := evh.id;
    END LOOP;

    -- récupération des ordres maximum par type d'intervention
    id := d_volume_horaire.FIRST;
    LOOP EXIT WHEN id IS NULL;
      IF NOT ordres_found.EXISTS(d_volume_horaire(id).type_volume_horaire_id) THEN
        ordres_found( d_volume_horaire(id).type_volume_horaire_id ) := d_volume_horaire(id).etat_volume_horaire_ordre;
      ELSIF ordres_found( d_volume_horaire(id).type_volume_horaire_id ) < d_volume_horaire(id).etat_volume_horaire_ordre THEN
        ordres_found( d_volume_horaire(id).type_volume_horaire_id ) := d_volume_horaire(id).etat_volume_horaire_ordre;
      END IF;
      id := d_volume_horaire.NEXT(id);
    END LOOP;

    -- peuplement des t_lst_type_etat_vh
    type_volume_horaire_id := ordres_found.FIRST;
    LOOP EXIT WHEN type_volume_horaire_id IS NULL;
      etat_volume_horaire_ordre := ordres_exists.FIRST;
      LOOP EXIT WHEN etat_volume_horaire_ordre IS NULL;
        IF etat_volume_horaire_ordre <= ordres_found(type_volume_horaire_id) THEN
          d_type_etat_vh( type_volume_horaire_id + 100000 * etat_volume_horaire_ordre ).type_volume_horaire_id := type_volume_horaire_id;
          d_type_etat_vh( type_volume_horaire_id + 100000 * etat_volume_horaire_ordre ).etat_volume_horaire_id := ordres_exists( etat_volume_horaire_ordre );
        END IF;
        etat_volume_horaire_ordre := ordres_exists.NEXT(etat_volume_horaire_ordre);
      END LOOP;

      type_volume_horaire_id := ordres_found.NEXT(type_volume_horaire_id);
    END LOOP;

  END;


  PROCEDURE POPULATE( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC ) IS
  BEGIN
    POPULATE_INTERVENANT    ( INTERVENANT_ID, ANNEE_ID, d_intervenant );
    IF d_intervenant.heures_service_statutaire IS NOT NULL THEN -- sinon rien n'est à faire!!
      POPULATE_REFERENTIEL    ( INTERVENANT_ID, ANNEE_ID, d_referentiel );
      POPULATE_SERVICE        ( INTERVENANT_ID, ANNEE_ID, d_service );
      POPULATE_VOLUME_HORAIRE ( INTERVENANT_ID, ANNEE_ID, d_volume_horaire );
      POPULATE_TYPE_ETAT_VH   ( d_volume_horaire, d_type_etat_vh );
    END IF;
  END;


  PROCEDURE CALCULER( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC ) IS
    id PLS_INTEGER;
    found BOOLEAN;
    function_name VARCHAR2(30);
    package_name VARCHAR2(30);
  BEGIN
    package_name  := OSE_PARAMETRE.GET_FORMULE_PACKAGE_NAME;
    function_name := OSE_PARAMETRE.GET_FORMULE_FUNCTION_NAME;

    -- détection de suppression des lignes de résultat obsolètes
    UPDATE formule_resultat SET TO_DELETE = 1 WHERE intervenant_id = CALCULER.INTERVENANT_ID AND annee_id = CALCULER.ANNEE_ID;
    UPDATE FORMULE_RESULTAT_REFERENTIEL SET TO_DELETE = 1 WHERE formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID AND annee_id = CALCULER.ANNEE_ID);
    UPDATE FORMULE_RESULTAT_SERVICE     SET TO_DELETE = 1 WHERE formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID AND annee_id = CALCULER.ANNEE_ID);
    UPDATE FORMULE_RESULTAT_VH          SET TO_DELETE = 1 WHERE formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID AND annee_id = CALCULER.ANNEE_ID);

    POPULATE( INTERVENANT_ID, ANNEE_ID );
    IF d_intervenant.heures_service_statutaire IS NOT NULL THEN -- sinon rien n'est à faire!!

      -- lancement du calcul sur les nouvelles lignes ou sur les lignes existantes
      id := d_type_etat_vh.FIRST;
      LOOP EXIT WHEN id IS NULL;
        -- délégation du calcul à la formule choisie (à des fins de paramétrage)
        EXECUTE IMMEDIATE
          'BEGIN ' || package_name || '.' || function_name || '( :1, :2, :3, :4 ); END;'
        USING
          INTERVENANT_ID, ANNEE_ID, d_type_etat_vh(id).type_volume_horaire_id, d_type_etat_vh(id).etat_volume_horaire_id;

        id := d_type_etat_vh.NEXT(id);
      END LOOP;
    END IF;

    DELETE FROM FORMULE_RESULTAT_REFERENTIEL WHERE TO_DELETE = 1 AND formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID AND annee_id = CALCULER.ANNEE_ID);
    DELETE FROM FORMULE_RESULTAT_SERVICE WHERE TO_DELETE = 1 AND formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID AND annee_id = CALCULER.ANNEE_ID);
    DELETE FROM FORMULE_RESULTAT_VH WHERE TO_DELETE = 1 AND formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID AND annee_id = CALCULER.ANNEE_ID);
    DELETE FROM formule_resultat WHERE TO_DELETE = 1 AND intervenant_id = CALCULER.INTERVENANT_ID AND annee_id = CALCULER.ANNEE_ID;

  END;

END OSE_FORMULE;
/
BEGIN OSE_FORMULE.CALCULER_TOUT; END;
/


-- sensible

---------------------------
--Nouveau VIEW
--V_TBL_SERVICE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_TBL_SERVICE"
 ( "ID", "SERVICE_ID", "INTERVENANT_ID", "TYPE_INTERVENANT_ID", "ANNEE_ID", "TYPE_VOLUME_HORAIRE_ID", "ETAT_VOLUME_HORAIRE_ID", "ETABLISSEMENT_ID", "STRUCTURE_AFF_ID", "STRUCTURE_ENS_ID", "NIVEAU_FORMATION_ID", "ETAPE_ID", "ELEMENT_PEDAGOGIQUE_ID", "PERIODE_ID", "TYPE_INTERVENTION_ID", "INTERVENANT_CODE", "INTERVENANT_NOM", "INTERVENANT_STATUT_LIBELLE", "INTERVENANT_TYPE_CODE", "INTERVENANT_TYPE_LIBELLE", "SERVICE_STRUCTURE_AFF_LIBELLE", "SERVICE_STRUCTURE_ENS_LIBELLE", "ETABLISSEMENT_LIBELLE", "ETAPE_CODE", "ETAPE_LIBELLE", "ELEMENT_CODE", "ELEMENT_LIBELLE", "COMMENTAIRES", "PERIODE_LIBELLE", "ELEMENT_PONDERATION_COMPL", "ELEMENT_SOURCE_LIBELLE", "HEURES", "HEURES_REF", "HEURES_NON_PAYEES", "HEURES_SERVICE_STATUTAIRE", "HEURES_SERVICE_DU_MODIFIE", "HETD", "HETD_SOLDE"
  )  AS
  WITH t AS ( SELECT
  'vh_' || vh.id                    id,
  s.id                              service_id,
  s.intervenant_id                  intervenant_id,
  s.annee_id                        annee_id,
  vh.type_volume_horaire_id         type_volume_horaire_id,
  fr.etat_volume_horaire_id         etat_volume_horaire_id,
  s.element_pedagogique_id          element_pedagogique_id,
  s.etablissement_id                etablissement_id,
  s.structure_aff_id                structure_aff_id,
  s.structure_ens_id                structure_ens_id,
  vh.periode_id                     periode_id,
  vh.type_intervention_id           type_intervention_id,

  vh.heures                         heures,
  0                                 heures_non_payees,
  0                                 heures_ref,
  frvh.service_assure               hetd,
  fr.heures_solde                   hetd_solde,
  null                              commentaires

FROM
  formule_resultat_vh                frvh
  JOIN formule_resultat                fr ON fr.id = frvh.formule_resultat_id
  JOIN volume_horaire                  vh ON vh.id = frvh.volume_horaire_id AND vh.motif_non_paiement_id IS NULL AND 1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
  JOIN service                          s ON s.id = vh.service_id AND s.annee_id = fr.annee_id AND s.intervenant_id = fr.intervenant_id AND 1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )

UNION

SELECT
  'vh_' || vh.id                    id,
  s.id                              service_id,
  s.intervenant_id                  intervenant_id,
  s.annee_id                        annee_id,
  vh.type_volume_horaire_id         type_volume_horaire_id,
  vhe.etat_volume_horaire_id        etat_volume_horaire_id,
  s.element_pedagogique_id          element_pedagogique_id,
  s.etablissement_id                etablissement_id,
  s.structure_aff_id                structure_aff_id,
  s.structure_ens_id                structure_ens_id,
  vh.periode_id                     periode_id,
  vh.type_intervention_id           type_intervention_id,

  vh.heures                         heures,
  1                                 heures_non_payees,
  0                                 heures_ref,
  0                                 hetd,
  fr.heures_solde                   hetd_solde,
  null                              commentaires

FROM
  volume_horaire                  vh
  JOIN service                     s ON s.id = vh.service_id
  JOIN v_volume_horaire_etat     vhe ON vhe.volume_horaire_id = vh.id
  JOIN formule_resultat           fr ON fr.intervenant_id = s.intervenant_id AND fr.annee_id = s.annee_id AND fr.type_volume_horaire_id = vh.type_volume_horaire_id AND fr.etat_volume_horaire_id = vhe.etat_volume_horaire_id
WHERE
  vh.motif_non_paiement_id IS NOT NULL
  AND 1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
  AND 1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )

UNION

SELECT
  'vh_ref_' || sr.id                id,
  null                              service_id,
  sr.intervenant_id                 intervenant_id,
  sr.annee_id                       annee_id,
  fr.type_volume_horaire_id         type_volume_horaire_id,
  fr.etat_volume_horaire_id         etat_volume_horaire_id,
  NULL                              element_pedagogique_id,
  OSE_PARAMETRE.GET_ETABLISSEMENT   etablissement_id,
  NULL                              structure_aff_id,
  sr.structure_id                   structure_ens_id,
  NULL                              periode_id,
  NULL                              type_intervention_id,

  0                                 heures,
  0                                 heures_non_payees,
  sr.heures                         heures_ref,
  frr.service_assure                hetd,
  fr.heures_solde                   hetd_solde,
  sr.commentaires                   commentaires

FROM
  formule_resultat_referentiel   frr
  JOIN formule_resultat           fr ON fr.id = frr.formule_resultat_id
  JOIN service_referentiel        sr ON sr.id = frr.service_referentiel_id AND sr.intervenant_id = fr.intervenant_id AND sr.annee_id = fr.annee_id AND 1 = ose_divers.comprise_entre( sr.histo_creation, sr.histo_destruction )
)
SELECT
  t.id                            id,
  t.service_id                    service_id,
  i.id                            intervenant_id,
  ti.id                           type_intervenant_id,
  t.annee_id                      annee_id,
  t.type_volume_horaire_id        type_volume_horaire_id,
  t.etat_volume_horaire_id        etat_volume_horaire_id,
  etab.id                         etablissement_id,
  saff.id                         structure_aff_id,
  sens.id                         structure_ens_id,
  ose_divers.niveau_formation_id_calc( gtf.id, gtf.pertinence_niveau, etp.niveau ) niveau_formation_id,
  etp.id                          etape_id,
  ep.id                           element_pedagogique_id,
  t.periode_id                    periode_id,
  t.type_intervention_id          type_intervention_id,

  i.source_code                   intervenant_code,
  i.nom_usuel || ' ' || i.prenom  intervenant_nom,
  si.libelle                      intervenant_statut_libelle,
  ti.code                         intervenant_type_code,
  ti.libelle                      intervenant_type_libelle,
  saff.libelle_court              service_structure_aff_libelle,

  sens.libelle_court              service_structure_ens_libelle,
  etab.libelle                    etablissement_libelle,
  etp.source_code                 etape_code,
  etp.libelle                     etape_libelle,
  ep.source_code                  element_code,
  ep.libelle                      element_libelle,
  null                            commentaires,
  p.libelle_court                 periode_libelle,
  CASE WHEN fs.ponderation_service_compl = 1 THEN NULL ELSE fs.ponderation_service_compl END element_ponderation_compl,
  src.libelle                     element_source_libelle,

  t.heures                        heures,
  t.heures_ref                    heures_ref,
  t.heures_non_payees             heures_non_payees,
  si.service_statutaire           heures_service_statutaire,
  fsm.heures                      heures_service_du_modifie,
  t.hetd                          hetd,
  t.hetd_solde                    hetd_solde

FROM
  t
  JOIN intervenant                        i ON i.id    = t.intervenant_id AND ose_divers.comprise_entre(  i.histo_creation,  i.histo_destruction ) = 1
  JOIN statut_intervenant                si ON si.id   = i.statut_id
  JOIN type_intervenant                  ti ON ti.id   = si.type_intervenant_id
  JOIN etablissement                   etab ON etab.id = t.etablissement_id
  LEFT JOIN structure                  saff ON saff.id = NVL(t.structure_aff_id, i.structure_id) AND ti.code = 'P'
  LEFT JOIN structure                  sens ON sens.id = t.structure_ens_id
  LEFT JOIN element_pedagogique          ep ON ep.id   = t.element_pedagogique_id
  LEFT JOIN periode                       p ON p.id    = t.periode_id
  LEFT JOIN source                      src ON src.id  = ep.source_id
  LEFT JOIN etape                       etp ON etp.id  = ep.etape_id
  LEFT JOIN type_formation               tf ON tf.id   = etp.type_formation_id AND ose_divers.comprise_entre( tf.histo_creation, tf.histo_destruction ) = 1
  LEFT JOIN groupe_type_formation       gtf ON gtf.id  = tf.groupe_id AND ose_divers.comprise_entre( gtf.histo_creation, gtf.histo_destruction ) = 1
  LEFT JOIN v_formule_service_modifie   fsm ON fsm.intervenant_id = i.id AND fsm.annee_id = t.annee_id
  LEFT JOIN v_formule_service            fs ON fs.id   = t.service_id;



-- ********************************************************************* --
-- *          à faire APRÈS avoir mis à jour le code source            * --
-- ********************************************************************* --


BEGIN DBMS_SCHEDULER.enable(name=>'"OSE"."OSE_SRC_SYNC"'); END;
/