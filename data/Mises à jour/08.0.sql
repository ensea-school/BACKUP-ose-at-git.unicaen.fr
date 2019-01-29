-- Script de migration de la version 7.x à la 8.0



-- DdlSequence.drop.

DROP SEQUENCE ANNEE_ID_SEQ

/

DROP SEQUENCE ETAT_VOLUME_HORAIRE_ID_SEQ

/

DROP SEQUENCE TYPE_VOLUME_HORAIRE_ID_SEQ

/




-- DdlView.drop.

DROP VIEW V_FORMULE_SERVICE

/

DROP VIEW V_FORMULE_SERVICE_MODIFIE

/

DROP VIEW V_FORMULE_SERVICE_REF

/

DROP VIEW V_FORMULE_VOLUME_HORAIRE_REF

/




-- DdlSequence.create.

CREATE SEQUENCE ETAT_SORTIE_ID_SEQ INCREMENT BY 1 MAXVALUE 9999999999999999999999999999 MINVALUE 1 NOCACHE

/

CREATE SEQUENCE TYPE_INTERVENTION_STATU_ID_SEQ INCREMENT BY 1 MAXVALUE 9999999999999999999999999999 MINVALUE 1 NOCACHE

/




-- DdlTable.create.

CREATE TABLE "ETAT_SORTIE"
(	"ID" NUMBER(*,0) NOT NULL ENABLE,
   "CODE" VARCHAR2(150 CHAR) NOT NULL ENABLE,
   "LIBELLE" VARCHAR2(250 CHAR) NOT NULL ENABLE,
   "FICHIER" BLOB,
   "REQUETE" VARCHAR2(4000 CHAR),
   "CLE" VARCHAR2(30 CHAR),
   "CSV_PARAMS" CLOB,
   "PDF_TRAITEMENT" CLOB,
   "BLOC1_NOM" VARCHAR2(50 CHAR),
   "BLOC1_ZONE" VARCHAR2(80 CHAR),
   "BLOC2_NOM" VARCHAR2(50 CHAR),
   "BLOC1_REQUETE" VARCHAR2(4000 CHAR),
   "BLOC2_ZONE" VARCHAR2(80 CHAR),
   "BLOC2_REQUETE" VARCHAR2(4000 CHAR),
   "BLOC3_NOM" VARCHAR2(50 CHAR),
   "BLOC3_ZONE" VARCHAR2(80 CHAR),
   "BLOC3_REQUETE" VARCHAR2(4000 CHAR),
   "BLOC4_NOM" VARCHAR2(50 CHAR),
   "BLOC4_ZONE" VARCHAR2(80 CHAR),
   "BLOC4_REQUETE" VARCHAR2(4000 CHAR),
   "BLOC5_NOM" VARCHAR2(50 CHAR),
   "BLOC5_ZONE" VARCHAR2(80 CHAR),
   "BLOC5_REQUETE" VARCHAR2(4000 CHAR),
   "BLOC6_NOM" VARCHAR2(50 CHAR),
   "BLOC6_REQUETE" VARCHAR2(4000 CHAR),
   "BLOC6_ZONE" VARCHAR2(80 CHAR),
   "BLOC7_NOM" VARCHAR2(50 CHAR),
   "BLOC7_ZONE" VARCHAR2(80 CHAR),
   "BLOC7_REQUETE" VARCHAR2(4000 CHAR),
   "BLOC8_NOM" VARCHAR2(50 CHAR),
   "BLOC8_ZONE" VARCHAR2(80 CHAR),
   "BLOC8_REQUETE" VARCHAR2(4000 CHAR),
   "BLOC9_NOM" VARCHAR2(50 CHAR),
   "BLOC9_ZONE" VARCHAR2(80 CHAR),
   "BLOC9_REQUETE" VARCHAR2(4000 CHAR),
   "BLOC10_NOM" VARCHAR2(50 CHAR),
   "BLOC10_ZONE" VARCHAR2(80 CHAR),
   "BLOC10_REQUETE" VARCHAR2(4000 CHAR),
   "AUTO_BREAK" NUMBER(1,0) DEFAULT 1 NOT NULL ENABLE
)

/

CREATE TABLE "TYPE_INTERVENTION_STATUT"
(	"ID" NUMBER(*,0) NOT NULL ENABLE,
   "TYPE_INTERVENTION_ID" NUMBER(*,0) NOT NULL ENABLE,
   "STATUT_INTERVENANT_ID" NUMBER(*,0) NOT NULL ENABLE,
   "TAUX_HETD_SERVICE" FLOAT(126),
   "TAUX_HETD_COMPLEMENTAIRE" FLOAT(126)
)

/




-- DdlPrimaryConstraint.create.

ALTER TABLE ETAT_SORTIE ADD CONSTRAINT ETAT_SORTIE_PK PRIMARY KEY (ID) USING INDEX (
      CREATE UNIQUE INDEX ETAT_SORTIE_PK ON ETAT_SORTIE(ID ASC)
) ENABLE

/

ALTER TABLE TYPE_INTERVENTION_STATUT ADD CONSTRAINT TYPE_INTERVENTION_STATUT_PK PRIMARY KEY (ID) USING INDEX (
      CREATE UNIQUE INDEX TYPE_INTERVENTION_STATUT_PK ON TYPE_INTERVENTION_STATUT(ID ASC)
) ENABLE

/




-- DdlRefConstraint.create.

ALTER TABLE TYPE_INTERVENTION_STATUT ADD CONSTRAINT TIS_STATUT_INTERVENANT_FK FOREIGN KEY (STATUT_INTERVENANT_ID)
REFERENCES STATUT_INTERVENANT (ID) ON DELETE CASCADE ENABLE

/

ALTER TABLE TYPE_INTERVENTION_STATUT ADD CONSTRAINT TIS_TYPE_INTERVENTION_FKV1 FOREIGN KEY (TYPE_INTERVENTION_ID)
REFERENCES TYPE_INTERVENTION (ID) ON DELETE CASCADE ENABLE

/




-- DdlUniqueConstraint.create.

ALTER TABLE ETAT_SORTIE ADD CONSTRAINT ETAT_SORTIE_CODE_UN UNIQUE (CODE) USING INDEX (
      CREATE UNIQUE INDEX ETAT_SORTIE_CODE_UN ON ETAT_SORTIE(CODE ASC)
) ENABLE

/

ALTER TABLE TBL_WORKFLOW ADD CONSTRAINT TBL_WORKFLOW__UN UNIQUE (INTERVENANT_ID, ETAPE_ID, STRUCTURE_ID) USING INDEX (
      CREATE UNIQUE INDEX TBL_WORKFLOW__UN ON TBL_WORKFLOW(INTERVENANT_ID ASC, ETAPE_ID ASC, STRUCTURE_ID ASC)
) ENABLE

/

ALTER TABLE TYPE_INTERVENTION_STATUT ADD CONSTRAINT TYPE_INTERVENTION_STATUT__UN UNIQUE (TYPE_INTERVENTION_ID, STATUT_INTERVENANT_ID) USING INDEX (
      CREATE UNIQUE INDEX TYPE_INTERVENTION_STATUT__UN ON TYPE_INTERVENTION_STATUT(TYPE_INTERVENTION_ID ASC, STATUT_INTERVENANT_ID ASC)
) ENABLE

/




-- DdlIndex.create.

CREATE UNIQUE INDEX ETAT_SORTIE_CODE_UN ON ETAT_SORTIE (CODE)

/

CREATE UNIQUE INDEX ETAT_SORTIE_PK ON ETAT_SORTIE (ID)

/

CREATE UNIQUE INDEX TBL_WORKFLOW__UN ON TBL_WORKFLOW (INTERVENANT_ID, ETAPE_ID, STRUCTURE_ID)

/

CREATE UNIQUE INDEX TYPE_INTERVENTION_STATUT_PK ON TYPE_INTERVENTION_STATUT (ID)

/

CREATE UNIQUE INDEX TYPE_INTERVENTION_STATUT__UN ON TYPE_INTERVENTION_STATUT (TYPE_INTERVENTION_ID, STATUT_INTERVENANT_ID)

/




-- DdlTable.alter.noNotNull|noDropColumns

ALTER TABLE "AFFECTATION_RECHERCHE" ADD ("LABO_LIBELLE" VARCHAR2(300 CHAR))

/

ALTER TABLE "FORMULE_RESULTAT" DROP COLUMN "TO_DELETE"

/

ALTER TABLE "FORMULE_RESULTAT_SERVICE" DROP COLUMN "TO_DELETE"

/

ALTER TABLE "FORMULE_RESULTAT_SERVICE_REF" DROP COLUMN "TO_DELETE"

/

ALTER TABLE "FORMULE_RESULTAT_VH" DROP COLUMN "TO_DELETE"

/

ALTER TABLE "FORMULE_RESULTAT_VH_REF" DROP COLUMN "TO_DELETE"

/

ALTER TABLE "TBL" ADD ("FEUILLE_DE_ROUTE" NUMBER(1,0) DEFAULT 0 NOT NULL ENABLE)

/




-- DdlPackage.alter.

CREATE OR REPLACE PACKAGE "OSE_DIVERS" AS

      PROCEDURE CALCULER_TABLEAUX_BORD;

      FUNCTION GET_OSE_UTILISATEUR_ID RETURN NUMERIC;
      FUNCTION GET_OSE_SOURCE_ID RETURN NUMERIC;

      FUNCTION INTERVENANT_HAS_PRIVILEGE( intervenant_id NUMERIC, privilege_name VARCHAR2 ) RETURN NUMERIC;

      FUNCTION implode(i_query VARCHAR2, i_seperator VARCHAR2 DEFAULT ',') RETURN VARCHAR2;

      PROCEDURE intervenant_horodatage_service( INTERVENANT_ID NUMERIC, TYPE_VOLUME_HORAIRE_ID NUMERIC, REFERENTIEL NUMERIC, HISTO_MODIFICATEUR_ID NUMERIC, HISTO_MODIFICATION DATE );

      FUNCTION NIVEAU_FORMATION_ID_CALC( gtf_id NUMERIC, gtf_pertinence_niveau NUMERIC, niveau NUMERIC DEFAULT NULL ) RETURN NUMERIC;

      FUNCTION STR_REDUCE( str CLOB ) RETURN CLOB;

      FUNCTION STR_FIND( haystack CLOB, needle VARCHAR2 ) RETURN NUMERIC;

      FUNCTION LIKED( haystack CLOB, needle CLOB ) RETURN NUMERIC;

      FUNCTION CALCUL_TAUX_FI( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT;

      FUNCTION CALCUL_TAUX_FC( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT;

      FUNCTION CALCUL_TAUX_FA( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT;

      PROCEDURE SYNC_LOG( msg CLOB );

      FUNCTION FORMATTED_RIB (bic VARCHAR2, iban VARCHAR2) RETURN VARCHAR2;

      FUNCTION FORMATTED_ADRESSE(
            no_voie                VARCHAR2,
            nom_voie               VARCHAR2,
            batiment               VARCHAR2,
            mention_complementaire VARCHAR2,
            localite               VARCHAR2,
            code_postal            VARCHAR2,
            ville                  VARCHAR2,
            pays_libelle           VARCHAR2)
            RETURN VARCHAR2;

      PROCEDURE CALCUL_FEUILLE_DE_ROUTE( CONDS CLOB );

      FUNCTION GET_TRIGGER_BODY( TRIGGER_NAME VARCHAR2 ) RETURN VARCHAR2;
END OSE_DIVERS;

/

CREATE OR REPLACE PACKAGE BODY "OSE_DIVERS" AS
      OSE_UTILISATEUR_ID NUMERIC;
      OSE_SOURCE_ID NUMERIC;




      PROCEDURE CALCULER_TABLEAUX_BORD IS
            BEGIN
                  FOR d IN (
                  SELECT tbl_name
                  FROM tbl
                  WHERE tbl_name <> 'formule' -- TROP LONG !!
                  ORDER BY ordre
                  )
                  LOOP
                        UNICAEN_TBL.CALCULER(d.tbl_name);
                        dbms_output.put_line('Calcul du tableau de bord "' || d.tbl_name || '" effectué');
                        COMMIT;
                  END LOOP;
            END;



      FUNCTION GET_OSE_UTILISATEUR_ID RETURN NUMERIC IS
            BEGIN
                  IF OSE_DIVERS.OSE_UTILISATEUR_ID IS NULL THEN
                        SELECT
                               to_number(valeur) INTO OSE_DIVERS.OSE_UTILISATEUR_ID
                        FROM
                             parametre
                        WHERE
                            nom = 'oseuser';
                  END IF;

                  RETURN OSE_DIVERS.OSE_UTILISATEUR_ID;
            END;



      FUNCTION GET_OSE_SOURCE_ID RETURN NUMERIC IS
            BEGIN
                  IF OSE_DIVERS.OSE_SOURCE_ID IS NULL THEN
                        SELECT
                               id INTO OSE_DIVERS.OSE_SOURCE_ID
                        FROM
                             source
                        WHERE
                            code = 'OSE';
                  END IF;

                  RETURN OSE_DIVERS.OSE_SOURCE_ID;
            END;



      FUNCTION INTERVENANT_HAS_PRIVILEGE( intervenant_id NUMERIC, privilege_name VARCHAR2 ) RETURN NUMERIC IS
            statut statut_intervenant%rowtype;
            itype  type_intervenant%rowtype;
            res NUMERIC;
            BEGIN
                  res := 1;
                  SELECT si.* INTO statut FROM statut_intervenant si JOIN intervenant i ON i.statut_id = si.id WHERE i.id = intervenant_id;
                  SELECT ti.* INTO itype  FROM type_intervenant ti WHERE ti.id = statut.type_intervenant_id;

                  /* DEPRECATED */
                  IF 'saisie_service' = privilege_name THEN
                        res := statut.peut_saisir_service;
                        RETURN res;
                  ELSIF 'saisie_service_exterieur' = privilege_name THEN
                        --IF INTERVENANT_HAS_PRIVILEGE( intervenant_id, 'saisie_service' ) = 0 OR itype.code = 'E' THEN -- cascade
                        IF itype.code = 'E' THEN
                              res := 0;
                        END IF;
                        RETURN res;
                  ELSIF 'saisie_service_referentiel' = privilege_name THEN
                        IF itype.code = 'E' THEN
                              res := 0;
                        END IF;
                        RETURN res;
                  ELSIF 'saisie_service_referentiel_autre_structure' = privilege_name THEN
                        res := 1;
                        RETURN res;
                  ELSIF 'saisie_motif_non_paiement' = privilege_name THEN
                        res := statut.peut_saisir_motif_non_paiement;
                        RETURN res;
                  END IF;
                  /* FIN DE DEPRECATED */

                  SELECT
                         count(*)
                      INTO
                            res
                  FROM
                       intervenant i
                             JOIN statut_privilege sp ON sp.statut_id = i.statut_id
                             JOIN privilege p ON p.id = sp.privilege_id
                             JOIN categorie_privilege cp ON cp.id = p.categorie_id
                  WHERE
                      i.id = INTERVENANT_HAS_PRIVILEGE.intervenant_id
                    AND cp.code || '-' || p.code = privilege_name;

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

      PROCEDURE intervenant_horodatage_service( INTERVENANT_ID NUMERIC, TYPE_VOLUME_HORAIRE_ID NUMERIC, REFERENTIEL NUMERIC, HISTO_MODIFICATEUR_ID NUMERIC, HISTO_MODIFICATION DATE ) AS
            BEGIN
                  MERGE INTO histo_intervenant_service his USING dual ON (

                        his.INTERVENANT_ID                = intervenant_horodatage_service.INTERVENANT_ID
                        AND NVL(his.TYPE_VOLUME_HORAIRE_ID,0) = NVL(intervenant_horodatage_service.TYPE_VOLUME_HORAIRE_ID,0)
                        AND his.REFERENTIEL                   = intervenant_horodatage_service.REFERENTIEL

                  ) WHEN MATCHED THEN UPDATE SET

                        HISTO_MODIFICATEUR_ID = intervenant_horodatage_service.HISTO_MODIFICATEUR_ID,
                        HISTO_MODIFICATION = intervenant_horodatage_service.HISTO_MODIFICATION

                  WHEN NOT MATCHED THEN INSERT (

                        ID,
                        INTERVENANT_ID,
                        TYPE_VOLUME_HORAIRE_ID,
                        REFERENTIEL,
                        HISTO_MODIFICATEUR_ID,
                        HISTO_MODIFICATION
                  ) VALUES (
                        HISTO_INTERVENANT_SERVI_ID_SEQ.NEXTVAL,
                        intervenant_horodatage_service.INTERVENANT_ID,
                        intervenant_horodatage_service.TYPE_VOLUME_HORAIRE_ID,
                        intervenant_horodatage_service.REFERENTIEL,
                        intervenant_horodatage_service.HISTO_MODIFICATEUR_ID,
                        intervenant_horodatage_service.HISTO_MODIFICATION

                  );
            END;


      FUNCTION NIVEAU_FORMATION_ID_CALC( gtf_id NUMERIC, gtf_pertinence_niveau NUMERIC, niveau NUMERIC DEFAULT NULL ) RETURN NUMERIC AS
            BEGIN
                  IF 1 <> gtf_pertinence_niveau OR niveau IS NULL OR niveau < 1 OR gtf_id < 1 THEN RETURN NULL; END IF;
                  RETURN gtf_id * 256 + niveau;
            END;

      FUNCTION STR_REDUCE( str CLOB ) RETURN CLOB IS
            BEGIN
                  RETURN utl_raw.cast_to_varchar2((nlssort(str, 'nls_sort=binary_ai')));
            END;

      FUNCTION STR_FIND( haystack CLOB, needle VARCHAR2 ) RETURN NUMERIC IS
            BEGIN
                  IF STR_REDUCE( haystack ) LIKE STR_REDUCE( '%' || needle || '%' ) THEN RETURN 1; END IF;
                  RETURN 0;
            END;

      FUNCTION LIKED( haystack CLOB, needle CLOB ) RETURN NUMERIC IS
            BEGIN
                  RETURN CASE WHEN STR_REDUCE(haystack) LIKE STR_REDUCE(needle) THEN 1 ELSE 0 END;
            END;

      PROCEDURE DO_NOTHING IS
            BEGIN
                  RETURN;
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

      PROCEDURE SYNC_LOG( msg CLOB ) IS
            BEGIN
                  INSERT INTO SYNC_LOG( id, date_sync, message ) VALUES ( sync_log_id_seq.nextval, systimestamp, msg );
            END;

      FUNCTION FORMATTED_RIB (bic VARCHAR2, iban VARCHAR2) RETURN VARCHAR2 IS
            BEGIN
                  if bic is null and iban is null then
                        return null;
                  end if;
                  RETURN regexp_replace(bic, '[[:space:]]+', '') || '-' || regexp_replace(iban, '[[:space:]]+', '');
            END;

      FUNCTION FORMATTED_ADRESSE(
            no_voie                VARCHAR2,
            nom_voie               VARCHAR2,
            batiment               VARCHAR2,
            mention_complementaire VARCHAR2,
            localite               VARCHAR2,
            code_postal            VARCHAR2,
            ville                  VARCHAR2,
            pays_libelle           VARCHAR2)
            RETURN VARCHAR2
      IS
            BEGIN
                  return
                  -- concaténation des éléments non null séparés par ', '
                  trim(trim(',' FROM REPLACE(', ' || NVL(no_voie,'#') || ', ' || NVL(nom_voie,'#') || ', ' || NVL(batiment,'#') || ', ' || NVL(mention_complementaire,'#'), ', #', ''))) ||
                  -- saut de ligne complet
                  chr(13) || chr(10) ||
                  -- concaténation des éléments non null séparés par ', '
                  trim(trim(',' FROM REPLACE(', ' || NVL(localite,'#') || ', ' || NVL(code_postal,'#') || ', ' || NVL(ville,'#') || ', ' || NVL(pays_libelle,'#'), ', #', '')));
            END;



      PROCEDURE CALCUL_FEUILLE_DE_ROUTE( CONDS CLOB ) IS
            BEGIN
                  FOR d IN (
                  SELECT   tbl_name
                  FROM     tbl
                  WHERE    feuille_de_route = 1
                  ORDER BY ordre
                  ) LOOP
                        UNICAEN_TBL.CALCULER(d.tbl_name,CONDS);
                  END LOOP;
            END;



      FUNCTION GET_TRIGGER_BODY( TRIGGER_NAME VARCHAR2 ) RETURN VARCHAR2 IS
            vlong long;
            BEGIN
                  SELECT trigger_body INTO vlong FROM all_triggers WHERE trigger_name = GET_TRIGGER_BODY.TRIGGER_NAME;

                  RETURN substr(vlong, 1, 32767);
            END;

END OSE_DIVERS;

/

CREATE OR REPLACE PACKAGE "OSE_EVENT" AS

      PROCEDURE ON_AFTER_FORMULE_CALC( INTERVENANT_ID NUMERIC );

END OSE_EVENT;

/

CREATE OR REPLACE PACKAGE BODY "OSE_EVENT" AS

      PROCEDURE ON_AFTER_FORMULE_CALC( INTERVENANT_ID NUMERIC ) IS
            p unicaen_tbl.t_params;
            BEGIN
                  p := UNICAEN_TBL.make_params('INTERVENANT_ID', ON_AFTER_FORMULE_CALC.intervenant_id);
                  /*
                      UNICAEN_TBL.CALCULER( 'agrement', p );
                      UNICAEN_TBL.CALCULER( 'paiement', p );
                      UNICAEN_TBL.CALCULER( 'workflow', p );*/
            END;

END OSE_EVENT;

/

CREATE OR REPLACE PACKAGE "OSE_FORMULE" AS

      TYPE t_intervenant IS RECORD (
      id                             NUMERIC,
      annee_id                       NUMERIC,
      structure_id                   NUMERIC,
      type_volume_horaire_id         NUMERIC,
      etat_volume_horaire_id         NUMERIC,

      heures_decharge                FLOAT DEFAULT 0,
      heures_service_statutaire      FLOAT DEFAULT 0,
      heures_service_modifie         FLOAT DEFAULT 0,
      depassement_service_du_sans_hc FLOAT DEFAULT 0,
      type_intervenant_code          VARCHAR(2),

      service_du                     FLOAT
      );

      TYPE t_volume_horaire IS RECORD (
      volume_horaire_id          NUMERIC,
      volume_horaire_ref_id      NUMERIC,
      service_id                 NUMERIC,
      service_referentiel_id     NUMERIC,
      taux_fi                    FLOAT DEFAULT 1,
      taux_fa                    FLOAT DEFAULT 0,
      taux_fc                    FLOAT DEFAULT 0,
      ponderation_service_du     FLOAT DEFAULT 1,
      ponderation_service_compl  FLOAT DEFAULT 1,
      structure_id               NUMERIC,
      structure_is_affectation   BOOLEAN DEFAULT TRUE,
      structure_is_univ          BOOLEAN,
      service_statutaire         BOOLEAN DEFAULT TRUE,
      heures                     FLOAT DEFAULT 0,
      taux_service_du            FLOAT DEFAULT 1,
      taux_service_compl         FLOAT DEFAULT 1,

      service_fi                 FLOAT DEFAULT 0,
      service_fa                 FLOAT DEFAULT 0,
      service_fc                 FLOAT DEFAULT 0,
      service_referentiel        FLOAT DEFAULT 0,
      heures_compl_fi            FLOAT DEFAULT 0,
      heures_compl_fa            FLOAT DEFAULT 0,
      heures_compl_fc            FLOAT DEFAULT 0,
      heures_compl_fc_majorees   FLOAT DEFAULT 0,
      heures_compl_referentiel   FLOAT DEFAULT 0
      );
      TYPE t_lst_volume_horaire IS TABLE OF t_volume_horaire INDEX BY PLS_INTEGER;
      TYPE t_volumes_horaires IS RECORD (
      length NUMERIC DEFAULT 0,
      items t_lst_volume_horaire
      );

      intervenant      t_intervenant;
      volumes_horaires t_volumes_horaires;

      FUNCTION GET_INTERVENANT_ID RETURN NUMERIC;

      FUNCTION GET_TAUX_HORAIRE_HETD( DATE_OBS DATE DEFAULT NULL ) RETURN FLOAT;
      PROCEDURE UPDATE_ANNEE_TAUX_HETD;

      PROCEDURE CALCULER( INTERVENANT_ID NUMERIC );
      PROCEDURE CALCULER_TOUT( ANNEE_ID NUMERIC DEFAULT NULL );        -- mise à jour de TOUTES les données ! ! ! !
      PROCEDURE CALCULER_TBL( PARAMS UNICAEN_TBL.T_PARAMS );

      PROCEDURE DEBUG_INTERVENANT;
      PROCEDURE DEBUG_VOLUMES_HORAIRES(VOLUME_HORAIRE_ID NUMERIC DEFAULT NULL);
END OSE_FORMULE;

/

CREATE OR REPLACE PACKAGE BODY "OSE_FORMULE" AS

      TYPE t_lst_vh_etats IS TABLE OF t_volumes_horaires INDEX BY PLS_INTEGER;
      TYPE t_lst_vh_types IS TABLE OF t_lst_vh_etats INDEX BY PLS_INTEGER;

      TYPE t_resultat IS RECORD (
      id                         NUMERIC,
      formule_resultat_id        NUMERIC,
      type_volume_horaire_id     NUMERIC,
      etat_volume_horaire_id     NUMERIC,
      service_id                 NUMERIC,
      service_referentiel_id     NUMERIC,
      volume_horaire_id          NUMERIC,
      volume_horaire_ref_id      NUMERIC,
      structure_id               NUMERIC,

      service_fi                 FLOAT DEFAULT 0,
      service_fa                 FLOAT DEFAULT 0,
      service_fc                 FLOAT DEFAULT 0,
      service_referentiel        FLOAT DEFAULT 0,
      heures_compl_fi            FLOAT DEFAULT 0,
      heures_compl_fa            FLOAT DEFAULT 0,
      heures_compl_fc            FLOAT DEFAULT 0,
      heures_compl_fc_majorees   FLOAT DEFAULT 0,
      heures_compl_referentiel   FLOAT DEFAULT 0,

      changed                    BOOLEAN DEFAULT FALSE
      );
      TYPE t_resultats IS TABLE OF t_resultat INDEX BY VARCHAR2(15);

      all_volumes_horaires t_lst_vh_types;
      arrondi NUMERIC DEFAULT 2;
      t_res t_resultats;



      FUNCTION GET_INTERVENANT_ID RETURN NUMERIC IS
            BEGIN
                  RETURN intervenant.id;
            END;



      FUNCTION GET_TAUX_HORAIRE_HETD( DATE_OBS DATE DEFAULT NULL ) RETURN FLOAT IS
            taux_hetd FLOAT;
            BEGIN
                  SELECT valeur INTO taux_hetd
                  FROM taux_horaire_hetd t
                  WHERE
                      DATE_OBS BETWEEN t.histo_creation AND COALESCE(t.histo_destruction,GREATEST(SYSDATE,DATE_OBS))
                    AND rownum = 1
                  ORDER BY
                           histo_creation DESC;
                  RETURN taux_hetd;
            END;



      PROCEDURE UPDATE_ANNEE_TAUX_HETD IS
            BEGIN
                  UPDATE annee SET taux_hetd = GET_TAUX_HORAIRE_HETD(date_fin);
            END;



      PROCEDURE LOAD_INTERVENANT_FROM_BDD IS
            BEGIN
                  intervenant.service_du := 0;

                  SELECT
                         intervenant_id,
                         annee_id,
                         structure_id,
                         type_intervenant_code,
                         heures_service_statutaire,
                         depassement_service_du_sans_hc,
                         heures_service_modifie,
                         heures_decharge
                      INTO
                            intervenant.id,
                            intervenant.annee_id,
                            intervenant.structure_id,
                            intervenant.type_intervenant_code,
                            intervenant.heures_service_statutaire,
                            intervenant.depassement_service_du_sans_hc,
                            intervenant.heures_service_modifie,
                            intervenant.heures_decharge
                  FROM
                       v_formule_intervenant fi
                  WHERE
                      fi.intervenant_id = intervenant.id;

                  intervenant.service_du := CASE
                                            WHEN intervenant.depassement_service_du_sans_hc = 1 -- HC traitées comme du service
                                                 OR intervenant.heures_decharge < 0 -- s'il y a une décharge => aucune HC

                                                  THEN 9999
                                            ELSE intervenant.heures_service_statutaire + intervenant.heures_service_modifie
                                            END;

                  EXCEPTION WHEN NO_DATA_FOUND THEN
                  intervenant.id                             := NULL;
                  intervenant.annee_id                       := null;
                  intervenant.structure_id                   := null;
                  intervenant.heures_service_statutaire      := 0;
                  intervenant.depassement_service_du_sans_hc := 0;
                  intervenant.heures_service_modifie         := 0;
                  intervenant.heures_decharge                := 0;
                  intervenant.type_intervenant_code          := 'E';
                  intervenant.service_du                     := 0;
            END;

      PROCEDURE LOAD_VH_FROM_BDD IS
            vh t_volume_horaire;
            etat_volume_horaire_id NUMERIC DEFAULT 1;
            structure_univ NUMERIC;
            length NUMERIC;
            BEGIN
                  all_volumes_horaires.delete;

                  SELECT to_number(valeur) INTO structure_univ FROM parametre WHERE nom = 'structure_univ';

                  FOR d IN (
                  SELECT *
                  FROM   v_formule_volume_horaire fvh
                  WHERE  fvh.intervenant_id = intervenant.id
                  ) LOOP
                        vh.volume_horaire_id         := d.volume_horaire_id;
                        vh.volume_horaire_ref_id     := d.volume_horaire_ref_id;
                        vh.service_id                := d.service_id;
                        vh.service_referentiel_id    := d.service_referentiel_id;
                        vh.taux_fi                   := d.taux_fi;
                        vh.taux_fa                   := d.taux_fa;
                        vh.taux_fc                   := d.taux_fc;
                        vh.ponderation_service_du    := d.ponderation_service_du;
                        vh.ponderation_service_compl := d.ponderation_service_compl;
                        vh.structure_id              := d.structure_id;
                        vh.structure_is_affectation  := NVL(d.structure_id,0) = NVL(intervenant.structure_id,-1);
                        vh.structure_is_univ         := NVL(d.structure_id,0) = NVL(structure_univ,-1);
                        vh.service_statutaire        := d.service_statutaire = 1;
                        vh.heures                    := d.heures;
                        vh.taux_service_du           := d.taux_service_du;
                        vh.taux_service_compl        := d.taux_service_compl;

                        FOR etat_volume_horaire_id IN 1 .. d.etat_volume_horaire_id LOOP
                              BEGIN
                                    length := all_volumes_horaires(d.type_volume_horaire_id)(etat_volume_horaire_id).length;
                                    EXCEPTION WHEN NO_DATA_FOUND THEN
                                    length := 0;
                              END;
                              length := length + 1;
                              all_volumes_horaires(d.type_volume_horaire_id)(etat_volume_horaire_id).length := length;
                              all_volumes_horaires(d.type_volume_horaire_id)(etat_volume_horaire_id).items(length) := vh;
                        END LOOP;
                  END LOOP;
            END;


      PROCEDURE tres_add_heures( code VARCHAR2, vh t_volume_horaire, tvh NUMERIC, evh NUMERIC) IS
            BEGIN
                  IF NOT t_res.exists(code) THEN
                        t_res(code).service_fi               := 0;
                        t_res(code).service_fa               := 0;
                        t_res(code).service_fc               := 0;
                        t_res(code).service_referentiel      := 0;
                        t_res(code).heures_compl_fi          := 0;
                        t_res(code).heures_compl_fa          := 0;
                        t_res(code).heures_compl_fc          := 0;
                        t_res(code).heures_compl_fc_majorees := 0;
                        t_res(code).heures_compl_referentiel := 0;
                  END IF;

                  t_res(code).service_fi               := t_res(code).service_fi               + vh.service_fi;
                  t_res(code).service_fa               := t_res(code).service_fa               + vh.service_fa;
                  t_res(code).service_fc               := t_res(code).service_fc               + vh.service_fc;
                  t_res(code).service_referentiel      := t_res(code).service_referentiel      + vh.service_referentiel;
                  t_res(code).heures_compl_fi          := t_res(code).heures_compl_fi          + vh.heures_compl_fi;
                  t_res(code).heures_compl_fa          := t_res(code).heures_compl_fa          + vh.heures_compl_fa;
                  t_res(code).heures_compl_fc          := t_res(code).heures_compl_fc          + vh.heures_compl_fc;
                  t_res(code).heures_compl_fc_majorees := t_res(code).heures_compl_fc_majorees + vh.heures_compl_fc_majorees;
                  t_res(code).heures_compl_referentiel := t_res(code).heures_compl_referentiel + vh.heures_compl_referentiel;

                  t_res(code).type_volume_horaire_id := tvh;
                  t_res(code).etat_volume_horaire_id := evh;
            END;

      PROCEDURE DEBUG_TRES IS
            code varchar2(15);
            table_name varchar2(30);
            fr formule_resultat%rowtype;
            frs formule_resultat_service%rowtype;
            frsr formule_resultat_service_ref%rowtype;
            frvh formule_resultat_vh%rowtype;
            frvhr formule_resultat_vh_ref%rowtype;
            BEGIN
                  code := t_res.FIRST;
                  LOOP EXIT WHEN code IS NULL;
                        table_name := CASE
                                      WHEN code LIKE '%-s-%' THEN 'FORMULE_RESULTAT_SERVICE'
                                      WHEN code LIKE '%-sr-%' THEN 'FORMULE_RESULTAT_SERVICE_REF'
                                      WHEN code LIKE '%-vh-%' THEN 'FORMULE_RESULTAT_VH'
                                      WHEN code LIKE '%-vhr-%' THEN 'FORMULE_RESULTAT_VH_REF'
                                      ELSE 'FORMULE_RESULTAT'
                                      END;

                        ose_test.echo('T_RES( ' || code || ' - Table ' || table_name || ' ) ');
                        ose_test.echo('  id = ' || t_res(code).id);
                        ose_test.echo('  formule_resultat_id      = ' || t_res(code).formule_resultat_id);
                        ose_test.echo('  type_volume_horaire_id   = ' || t_res(code).type_volume_horaire_id);
                        ose_test.echo('  etat_volume_horaire_id   = ' || t_res(code).etat_volume_horaire_id);
                        ose_test.echo('  volume_horaire_id        = ' || t_res(code).volume_horaire_id);
                        ose_test.echo('  volume_horaire_ref_id    = ' || t_res(code).volume_horaire_ref_id);
                        ose_test.echo('  service_id               = ' || t_res(code).service_id);
                        ose_test.echo('  service_referentiel_id   = ' || t_res(code).service_referentiel_id);
                        ose_test.echo('  structure_id             = ' || t_res(code).structure_id);
                        ose_test.echo('  service_fi               = ' || t_res(code).service_fi);
                        ose_test.echo('  service_fa               = ' || t_res(code).service_fa);
                        ose_test.echo('  service_fc               = ' || t_res(code).service_fc);
                        ose_test.echo('  service_referentiel      = ' || t_res(code).service_referentiel);
                        ose_test.echo('  heures_compl_fi          = ' || t_res(code).heures_compl_fi);
                        ose_test.echo('  heures_compl_fa          = ' || t_res(code).heures_compl_fa);
                        ose_test.echo('  heures_compl_fc          = ' || t_res(code).heures_compl_fc);
                        ose_test.echo('  heures_compl_fc_majorees = ' || t_res(code).heures_compl_fc_majorees);
                        ose_test.echo('  heures_compl_referentiel = ' || t_res(code).heures_compl_referentiel);

                        code := t_res.NEXT(code);
                  END LOOP;
            END;

      PROCEDURE SAVE_TO_BDD IS
            bcode VARCHAR(15);
            code VARCHAR(15);
            type_volume_horaire_id NUMERIC;
            etat_volume_horaire_id NUMERIC;
            vh t_volume_horaire;
            fr formule_resultat%rowtype;
            frs formule_resultat_service%rowtype;
            frsr formule_resultat_service_ref%rowtype;
            frvh formule_resultat_vh%rowtype;
            frvhr formule_resultat_vh_ref%rowtype;
            BEGIN
                  t_res.delete;

                  /* On préinitialise avec ce qui existe déjà */
                  FOR d IN (
                  SELECT
                         fr.type_volume_horaire_id || '-' || fr.etat_volume_horaire_id code,
                         fr.id                       id,
                         fr.id                       formule_resultat_id,
                         fr.type_volume_horaire_id   type_volume_horaire_id,
                         fr.etat_volume_horaire_id   etat_volume_horaire_id,
                         null                        service_id,
                         null                        service_referentiel_id,
                         null                        volume_horaire_id,
                         null                        volume_horaire_ref_id

                  FROM
                       formule_resultat fr
                  WHERE
                      fr.intervenant_id = intervenant.id

                  UNION ALL SELECT
                                   fr.type_volume_horaire_id || '-' || fr.etat_volume_horaire_id || '-s-' || frs.service_id code,
                                   frs.id                      id,
                                   fr.id                       formule_resultat_id,
                                   fr.type_volume_horaire_id   type_volume_horaire_id,
                                   fr.etat_volume_horaire_id   etat_volume_horaire_id,
                                   frs.service_id              service_id,
                                   null                        service_referentiel_id,
                                   null                        volume_horaire_id,
                                   null                        volume_horaire_ref_id
                            FROM
                                 formule_resultat_service frs
                                       JOIN formule_resultat fr ON fr.id = frs.formule_resultat_id
                            WHERE
                                fr.intervenant_id = intervenant.id

                  UNION ALL SELECT
                                   fr.type_volume_horaire_id || '-' || fr.etat_volume_horaire_id || '-sr-' || frsr.service_referentiel_id code,
                                   frsr.id                     id,
                                   fr.id                       formule_resultat_id,
                                   fr.type_volume_horaire_id   type_volume_horaire_id,
                                   fr.etat_volume_horaire_id   etat_volume_horaire_id,
                                   null                        service_id,
                                   frsr.service_referentiel_id service_referentiel_id,
                                   null                        volume_horaire_id,
                                   null                        volume_horaire_ref_id
                            FROM
                                 formule_resultat_service_ref frsr
                                       JOIN formule_resultat fr ON fr.id = frsr.formule_resultat_id
                            WHERE
                                fr.intervenant_id = intervenant.id

                  UNION ALL SELECT
                                   fr.type_volume_horaire_id || '-' || fr.etat_volume_horaire_id || '-vh-' || frvh.volume_horaire_id code,
                                   frvh.id                     id,
                                   fr.id                       formule_resultat_id,
                                   fr.type_volume_horaire_id   type_volume_horaire_id,
                                   fr.etat_volume_horaire_id   etat_volume_horaire_id,
                                   null                        service_id,
                                   null                        service_referentiel_id,
                                   frvh.volume_horaire_id      volume_horaire_id,
                                   null                        volume_horaire_ref_id
                            FROM
                                 formule_resultat_vh frvh
                                       JOIN formule_resultat fr ON fr.id = frvh.formule_resultat_id
                            WHERE
                                fr.intervenant_id = intervenant.id

                  UNION ALL SELECT
                                   fr.type_volume_horaire_id || '-' || fr.etat_volume_horaire_id || '-vhr-' || frvhr.volume_horaire_ref_id code,
                                   frvhr.id                    id,
                                   fr.id                       formule_resultat_id,
                                   fr.type_volume_horaire_id   type_volume_horaire_id,
                                   fr.etat_volume_horaire_id   etat_volume_horaire_id,
                                   null                        service_id,
                                   null                        service_referentiel_id,
                                   null                        volume_horaire_id,
                                   frvhr.volume_horaire_ref_id volume_horaire_ref_id
                            FROM
                                 formule_resultat_vh_ref frvhr
                                       JOIN formule_resultat fr ON fr.id = frvhr.formule_resultat_id
                            WHERE
                                fr.intervenant_id = intervenant.id
                  ) LOOP
                        t_res(d.code).id                     := d.id;
                        t_res(d.code).formule_resultat_id    := d.formule_resultat_id;
                        t_res(d.code).type_volume_horaire_id := d.type_volume_horaire_id;
                        t_res(d.code).etat_volume_horaire_id := d.etat_volume_horaire_id;
                        t_res(d.code).service_id             := d.service_id;
                        t_res(d.code).service_referentiel_id := d.service_referentiel_id;
                        t_res(d.code).volume_horaire_id      := d.volume_horaire_id;
                        t_res(d.code).volume_horaire_ref_id  := d.volume_horaire_ref_id;
                  END LOOP;

                  /* On charge avec les résultats de formule */
                  type_volume_horaire_id := all_volumes_horaires.FIRST;
                  LOOP EXIT WHEN type_volume_horaire_id IS NULL;
                        etat_volume_horaire_id := all_volumes_horaires(type_volume_horaire_id).FIRST;
                        LOOP EXIT WHEN etat_volume_horaire_id IS NULL;
                              FOR i IN 1 .. all_volumes_horaires(type_volume_horaire_id)(etat_volume_horaire_id).length LOOP
                                    vh := all_volumes_horaires(type_volume_horaire_id)(etat_volume_horaire_id).items(i);
                                    bcode := type_volume_horaire_id || '-' || etat_volume_horaire_id;

                                    -- formule_resultat
                                    code := bcode;
                                    tres_add_heures(code,vh, type_volume_horaire_id, etat_volume_horaire_id);

                                    -- formule_resultat_service
                                    IF vh.service_id IS NOT NULL THEN
                                          code := bcode || '-s-' || vh.service_id;
                                          t_res(code).service_id := vh.service_id;
                                          tres_add_heures(code,vh, type_volume_horaire_id, etat_volume_horaire_id);
                                    END IF;

                                    -- formule_resultat_service_ref
                                    IF vh.service_referentiel_id IS NOT NULL THEN
                                          code := bcode || '-sr-' || vh.service_referentiel_id;
                                          t_res(code).service_referentiel_id := vh.service_referentiel_id;
                                          tres_add_heures(code,vh, type_volume_horaire_id, etat_volume_horaire_id);
                                    END IF;

                                    -- formule_resultat_volume_horaire
                                    IF vh.volume_horaire_id IS NOT NULL THEN
                                          code := bcode || '-vh-' || vh.volume_horaire_id;
                                          t_res(code).volume_horaire_id := vh.volume_horaire_id;
                                          tres_add_heures(code,vh, type_volume_horaire_id, etat_volume_horaire_id);
                                    END IF;

                                    -- formule_resultat_volume_horaire_ref
                                    IF vh.volume_horaire_ref_id IS NOT NULL THEN
                                          code := bcode || '-vhr-' || vh.volume_horaire_ref_id;
                                          t_res(code).volume_horaire_ref_id := vh.volume_horaire_ref_id;
                                          tres_add_heures(code,vh, type_volume_horaire_id, etat_volume_horaire_id);
                                    END IF;

                              END LOOP;
                              etat_volume_horaire_id := all_volumes_horaires(type_volume_horaire_id).NEXT(etat_volume_horaire_id);
                        END LOOP;
                        type_volume_horaire_id := all_volumes_horaires.NEXT(type_volume_horaire_id);
                  END LOOP;

                  /* On fait la sauvegarde en BDD */
                  /* D'abord le formule_resultat */
                  code := t_res.FIRST;
                  LOOP EXIT WHEN code IS NULL;
                        IF code = (t_res(code).type_volume_horaire_id || '-' || t_res(code).etat_volume_horaire_id) THEN
                              fr.id                       := t_res(code).id;
                              fr.intervenant_id           := intervenant.id;
                              fr.type_volume_horaire_id   := t_res(code).type_volume_horaire_id;
                              fr.etat_volume_horaire_id   := t_res(code).etat_volume_horaire_id;
                              fr.service_fi               := ROUND(t_res(code).service_fi,2);
                              fr.service_fa               := ROUND(t_res(code).service_fa,2);
                              fr.service_fc               := ROUND(t_res(code).service_fc,2);
                              fr.service_referentiel      := ROUND(t_res(code).service_referentiel,2);
                              fr.heures_compl_fi          := ROUND(t_res(code).heures_compl_fi,2);
                              fr.heures_compl_fa          := ROUND(t_res(code).heures_compl_fa,2);
                              fr.heures_compl_fc          := ROUND(t_res(code).heures_compl_fc,2);
                              fr.heures_compl_fc_majorees := ROUND(t_res(code).heures_compl_fc_majorees,2);
                              fr.heures_compl_referentiel := ROUND(t_res(code).heures_compl_referentiel,2);
                              fr.total := fr.service_fi + fr.service_fa + fr.service_fc + fr.service_referentiel
                                          + fr.heures_compl_fi + fr.heures_compl_fa + fr.heures_compl_fc
                                          + fr.heures_compl_fc_majorees + fr.heures_compl_referentiel;

                              fr.service_du := ROUND(CASE
                                                     WHEN intervenant.depassement_service_du_sans_hc = 1 OR intervenant.heures_decharge < 0
                                                           THEN GREATEST(fr.total, intervenant.heures_service_statutaire + intervenant.heures_service_modifie)
                                                     ELSE intervenant.heures_service_statutaire + intervenant.heures_service_modifie
                                                     END,2);

                              fr.solde                    := fr.total - fr.service_du;
                              IF fr.solde >= 0 THEN
                                    fr.sous_service           := 0;
                                    fr.heures_compl           := fr.solde;
                              ELSE
                                    fr.sous_service           := fr.solde * -1;
                                    fr.heures_compl           := 0;
                              END IF;
                              fr.type_intervenant_code    := intervenant.type_intervenant_code;

                              IF fr.id IS NULL THEN
                                    fr.id := formule_resultat_id_seq.nextval;
                                    t_res(code).id := fr.id;
                                    INSERT INTO formule_resultat VALUES fr;
                              ELSE
                                    UPDATE formule_resultat SET ROW = fr WHERE id = fr.id;
                              END IF;
                        END IF;
                        code := t_res.NEXT(code);
                  END LOOP;

                  --DEBUG_TRES;

                  /* Ensuite toutes les dépendances... */
                  code := t_res.FIRST;
                  LOOP EXIT WHEN code IS NULL;
                        bcode := t_res(code).type_volume_horaire_id || '-' || t_res(code).etat_volume_horaire_id;
                        CASE
                              WHEN code LIKE '%-s-%' THEN -- formule_resultat_service
                              frs.id                         := t_res(code).id;
                              frs.formule_resultat_id        := t_res(bcode).id;
                              frs.service_id                 := t_res(code).service_id;
                              frs.service_fi                 := ROUND(t_res(code).service_fi, 2);
                              frs.service_fa                 := ROUND(t_res(code).service_fa, 2);
                              frs.service_fc                 := ROUND(t_res(code).service_fc, 2);
                              frs.heures_compl_fi            := ROUND(t_res(code).heures_compl_fi, 2);
                              frs.heures_compl_fa            := ROUND(t_res(code).heures_compl_fa, 2);
                              frs.heures_compl_fc            := ROUND(t_res(code).heures_compl_fc, 2);
                              frs.heures_compl_fc_majorees   := ROUND(t_res(code).heures_compl_fc_majorees, 2);
                              frs.total                      := frs.service_fi + frs.service_fa + frs.service_fc
                                                                + frs.heures_compl_fi + frs.heures_compl_fa + frs.heures_compl_fc + frs.heures_compl_fc_majorees;
                              IF frs.id IS NULL THEN
                                    frs.id := formule_resultat_servic_id_seq.nextval;
                                    INSERT INTO formule_resultat_service VALUES frs;
                              ELSE
                                    UPDATE formule_resultat_service SET ROW = frs WHERE id = frs.id;
                              END IF;
                              WHEN code LIKE '%-sr-%' THEN -- formule_resultat_service_ref
                              frsr.id                        := t_res(code).id;
                              frsr.formule_resultat_id       := t_res(bcode).id;
                              frsr.service_referentiel_id    := t_res(code).service_referentiel_id;
                              frsr.service_referentiel       := ROUND(t_res(code).service_referentiel, 2);
                              frsr.heures_compl_referentiel  := ROUND(t_res(code).heures_compl_referentiel, 2);
                              frsr.total                     := frsr.service_referentiel + frsr.heures_compl_referentiel;
                              IF frsr.id IS NULL THEN
                                    frsr.id := formule_resultat_servic_id_seq.nextval;
                                    INSERT INTO formule_resultat_service_ref VALUES frsr;
                              ELSE
                                    UPDATE formule_resultat_service_ref SET ROW = frsr WHERE id = frsr.id;
                              END IF;
                              WHEN code LIKE '%-vh-%' THEN -- formule_resultat_vh
                              frvh.id := t_res(code).id;
                              frvh.formule_resultat_id       := t_res(bcode).id;
                              frvh.volume_horaire_id         := t_res(code).volume_horaire_id;
                              frvh.service_fi                := ROUND(t_res(code).service_fi, 2);
                              frvh.service_fa                := ROUND(t_res(code).service_fa, 2);
                              frvh.service_fc                := ROUND(t_res(code).service_fc, 2);
                              frvh.heures_compl_fi           := ROUND(t_res(code).heures_compl_fi, 2);
                              frvh.heures_compl_fa           := ROUND(t_res(code).heures_compl_fa, 2);
                              frvh.heures_compl_fc           := ROUND(t_res(code).heures_compl_fc, 2);
                              frvh.heures_compl_fc_majorees  := ROUND(t_res(code).heures_compl_fc_majorees, 2);
                              frvh.total                     := frvh.service_fi + frvh.service_fa + frvh.service_fc
                                                                + frvh.heures_compl_fi + frvh.heures_compl_fa + frvh.heures_compl_fc + frvh.heures_compl_fc_majorees;
                              IF frvh.id IS NULL THEN
                                    frvh.id := formule_resultat_vh_id_seq.nextval;
                                    INSERT INTO formule_resultat_vh VALUES frvh;
                              ELSE
                                    UPDATE formule_resultat_vh SET ROW = frvh WHERE id = frvh.id;
                              END IF;
                              WHEN code LIKE '%-vhr-%' THEN -- formule_resultat_vh_ref
                              frvhr.id := t_res(code).id;
                              frvhr.formule_resultat_id      := t_res(bcode).id;
                              frvhr.volume_horaire_ref_id    := t_res(code).volume_horaire_ref_id;
                              frvhr.service_referentiel      := ROUND(t_res(code).service_referentiel, 2);
                              frvhr.heures_compl_referentiel := ROUND(t_res(code).heures_compl_referentiel, 2);
                              frvhr.total                    := frvhr.service_referentiel + frvhr.heures_compl_referentiel;
                              IF frvhr.id IS NULL THEN
                                    frvhr.id := formule_resultat_vh_ref_id_seq.nextval;
                                    INSERT INTO formule_resultat_vh_ref VALUES frvhr;
                              ELSE
                                    UPDATE formule_resultat_vh_ref SET ROW = frvhr WHERE id = frvhr.id;
                              END IF;
                        ELSE code := code;
                        END CASE;
                        code := t_res.NEXT(code);
                  END LOOP;
            END;



      PROCEDURE CALCULER( INTERVENANT_ID NUMERIC ) IS
            type_volume_horaire_id NUMERIC;
            etat_volume_horaire_id NUMERIC;

            function_name VARCHAR2(30);
            package_name VARCHAR2(30);
            BEGIN
                  package_name  := OSE_PARAMETRE.GET_FORMULE_PACKAGE_NAME;
                  function_name := OSE_PARAMETRE.GET_FORMULE_FUNCTION_NAME;

                  intervenant.id := intervenant_id;

                  LOAD_INTERVENANT_FROM_BDD;
                  LOAD_VH_FROM_BDD;

                  type_volume_horaire_id := all_volumes_horaires.FIRST;
                  LOOP EXIT WHEN type_volume_horaire_id IS NULL;
                        intervenant.type_volume_horaire_id := type_volume_horaire_id;
                        etat_volume_horaire_id := all_volumes_horaires(type_volume_horaire_id).FIRST;
                        LOOP EXIT WHEN etat_volume_horaire_id IS NULL;
                              intervenant.etat_volume_horaire_id := etat_volume_horaire_id;
                              volumes_horaires := all_volumes_horaires(type_volume_horaire_id)(etat_volume_horaire_id);
                              EXECUTE IMMEDIATE 'BEGIN ' || package_name || '.' || function_name || '; END;';
                              all_volumes_horaires(type_volume_horaire_id)(etat_volume_horaire_id) := volumes_horaires;
                              etat_volume_horaire_id := all_volumes_horaires(type_volume_horaire_id).NEXT(etat_volume_horaire_id);
                        END LOOP;
                        type_volume_horaire_id := all_volumes_horaires.NEXT(type_volume_horaire_id);
                  END LOOP;

                  SAVE_TO_BDD;

                  OSE_EVENT.ON_AFTER_FORMULE_CALC( CALCULER.INTERVENANT_ID );
            END;

      PROCEDURE CALCULER_TOUT( ANNEE_ID NUMERIC DEFAULT NULL ) IS
            a_id NUMERIC;
            BEGIN
                  a_id := NVL(CALCULER_TOUT.ANNEE_ID, OSE_PARAMETRE.GET_ANNEE);
                  FOR mp IN (
                  SELECT DISTINCT
                                  intervenant_id
                  FROM
                       service s
                             JOIN intervenant i ON i.id = s.intervenant_id
                  WHERE
                      s.histo_destruction IS NULL
                    AND i.annee_id = a_id

                  UNION ALL

                  SELECT DISTINCT
                                  intervenant_id
                  FROM
                       service_referentiel sr
                             JOIN intervenant i ON i.id = sr.intervenant_id
                  WHERE
                      sr.histo_destruction IS NULL
                    AND i.annee_id = a_id

                  )
                  LOOP
                        CALCULER( mp.intervenant_id );
                  END LOOP;
            END;

      PROCEDURE CALCULER_TBL( PARAMS UNICAEN_TBL.T_PARAMS ) IS
            intervenant_id NUMERIC;
            TYPE r_cursor IS REF CURSOR;
            diff_cur r_cursor;
            BEGIN
                  OPEN diff_cur FOR 'WITH interv AS (SELECT id intervenant_id, intervenant.* FROM intervenant)
    SELECT intervenant_id FROM interv WHERE ' || unicaen_tbl.PARAMS_TO_CONDS( params );
                  LOOP
                        FETCH diff_cur INTO intervenant_id; EXIT WHEN diff_cur%NOTFOUND;
                        BEGIN
                              CALCULER( intervenant_id );
                        END;
                  END LOOP;
                  CLOSE diff_cur;
            END;



      PROCEDURE DEBUG_INTERVENANT IS
            BEGIN
                  ose_test.echo('OSE Formule DEBUG Intervenant');
                  ose_test.echo('id                             = ' || intervenant.id);
                  ose_test.echo('annee_id                       = ' || intervenant.annee_id);
                  ose_test.echo('structure_id                   = ' || intervenant.structure_id);
                  ose_test.echo('type_volume_horaire_id         = ' || intervenant.type_volume_horaire_id);
                  ose_test.echo('heures_decharge                = ' || intervenant.heures_decharge);
                  ose_test.echo('heures_service_statutaire      = ' || intervenant.heures_service_statutaire);
                  ose_test.echo('heures_service_modifie         = ' || intervenant.heures_service_modifie);
                  ose_test.echo('depassement_service_du_sans_hc = ' || intervenant.depassement_service_du_sans_hc);
                  ose_test.echo('service_du                     = ' || intervenant.service_du);
            END;

      PROCEDURE DEBUG_VOLUMES_HORAIRES(VOLUME_HORAIRE_ID NUMERIC DEFAULT NULL) IS
            type_volume_horaire_id NUMERIC;
            etat_volume_horaire_id NUMERIC;
            vh t_volume_horaire;
            BEGIN
                  ose_test.echo('OSE Formule DEBUG Intervenant');

                  type_volume_horaire_id := all_volumes_horaires.FIRST;
                  LOOP EXIT WHEN type_volume_horaire_id IS NULL;
                        etat_volume_horaire_id := all_volumes_horaires(type_volume_horaire_id).FIRST;
                        LOOP EXIT WHEN etat_volume_horaire_id IS NULL;
                              ose_test.echo('tvh=' || type_volume_horaire_id || ', evh=' || etat_volume_horaire_id);
                              FOR i IN 1 .. all_volumes_horaires(type_volume_horaire_id)(etat_volume_horaire_id).length LOOP
                                    vh := all_volumes_horaires(type_volume_horaire_id)(etat_volume_horaire_id).items(i);
                                    IF VOLUME_HORAIRE_ID IS NULL OR VOLUME_HORAIRE_ID = vh.volume_horaire_id OR VOLUME_HORAIRE_ID = vh.volume_horaire_ref_id THEN
                                          ose_test.echo('volume_horaire_id         = ' || vh.volume_horaire_id);
                                          ose_test.echo('volume_horaire_ref_id     = ' || vh.volume_horaire_ref_id);
                                          ose_test.echo('service_id                = ' || vh.service_id);
                                          ose_test.echo('service_referentiel_id    = ' || vh.service_referentiel_id);
                                          ose_test.echo('taux_fi                   = ' || vh.taux_fi);
                                          ose_test.echo('taux_fa                   = ' || vh.taux_fa);
                                          ose_test.echo('taux_fc                   = ' || vh.taux_fc);
                                          ose_test.echo('ponderation_service_du    = ' || vh.ponderation_service_du);
                                          ose_test.echo('ponderation_service_compl = ' || vh.ponderation_service_compl);
                                          ose_test.echo('structure_id              = ' || vh.structure_id);
                                          ose_test.echo('structure_is_affectation  = ' || CASE WHEN vh.structure_is_affectation THEN 'OUI' ELSE 'NON' END);
                                          ose_test.echo('structure_is_univ         = ' || CASE WHEN vh.structure_is_univ THEN 'OUI' ELSE 'NON' END);
                                          ose_test.echo('service_statutaire        = ' || CASE WHEN vh.service_statutaire THEN 'OUI' ELSE 'NON' END);
                                          ose_test.echo('heures                    = ' || vh.heures);
                                          ose_test.echo('taux_service_du           = ' || vh.taux_service_du);
                                          ose_test.echo('taux_service_compl        = ' || vh.taux_service_compl);
                                          ose_test.echo('service_fi                = ' || vh.service_fi);
                                          ose_test.echo('service_fa                = ' || vh.service_fa);
                                          ose_test.echo('service_fc                = ' || vh.service_fc);
                                          ose_test.echo('service_referentiel       = ' || vh.service_referentiel);
                                          ose_test.echo('heures_compl_fi           = ' || vh.heures_compl_fi);
                                          ose_test.echo('heures_compl_fa           = ' || vh.heures_compl_fa);
                                          ose_test.echo('heures_compl_fc           = ' || vh.heures_compl_fc);
                                          ose_test.echo('heures_compl_fc_majorees  = ' || vh.heures_compl_fc_majorees);
                                          ose_test.echo('heures_compl_referentiel  = ' || vh.heures_compl_referentiel);
                                          ose_test.echo('');
                                    END IF;
                              END LOOP;
                              etat_volume_horaire_id := all_volumes_horaires(type_volume_horaire_id).NEXT(etat_volume_horaire_id);
                        END LOOP;
                        type_volume_horaire_id := all_volumes_horaires.NEXT(type_volume_horaire_id);
                  END LOOP;
            END;

END OSE_FORMULE;

/

CREATE OR REPLACE PACKAGE "UNICAEN_OSE_FORMULE" AS
      debug_enabled                BOOLEAN DEFAULT FALSE;
      debug_etat_volume_horaire_id NUMERIC DEFAULT 1;
      debug_volume_horaire_id      NUMERIC;
      debug_volume_horaire_ref_id  NUMERIC;

      PROCEDURE CALCUL_RESULTAT_V2;
      PROCEDURE CALCUL_RESULTAT_V3;

      PROCEDURE PURGE_EM_NON_FC;

END UNICAEN_OSE_FORMULE;

/

CREATE OR REPLACE PACKAGE BODY "UNICAEN_OSE_FORMULE" AS

/* Stockage des valeurs intermédiaires */
      TYPE t_valeurs IS TABLE OF FLOAT INDEX BY PLS_INTEGER;
      TYPE t_tableau IS RECORD (
      valeurs t_valeurs,
      total   FLOAT DEFAULT 0
      );
      TYPE t_tableaux       IS TABLE OF t_tableau INDEX BY PLS_INTEGER;
      TYPE t_tableau_config IS RECORD (
      tableau NUMERIC,
      version NUMERIC,
      referentiel BOOLEAN DEFAULT FALSE,
      setTotal BOOLEAN DEFAULT FALSE
      );
      TYPE t_tableaux_configs IS VARRAY(100) OF t_tableau_config;

      t                     t_tableaux;
      vh_index              NUMERIC;



      -- Crée une définition de tableau
      FUNCTION TC( tableau NUMERIC, version NUMERIC, options VARCHAR2 DEFAULT NULL) RETURN t_tableau_config IS
            tcRes t_tableau_config;
            BEGIN
                  tcRes.tableau := tableau;
                  tcRes.version := version;
                  CASE
                        WHEN options like '%t%' THEN tcRes.setTotal := TRUE;
                        WHEN options like '%r%' THEN tcRes.referentiel := TRUE;
                  ELSE RETURN tcRes;
                  END CASE;

                  RETURN tcRes;
            END;

      -- Setter d'une valeur intermédiaire au niveau case
      PROCEDURE SV( tableau NUMERIC, valeur FLOAT ) IS
            BEGIN
                  t(tableau).valeurs(vh_index) := valeur;
                  t(tableau).total             := t(tableau).total + valeur;
            END;

      -- Setter d'une valeur intermédiaire au niveau tableau
      PROCEDURE ST( tableau NUMERIC, valeur FLOAT ) IS
            BEGIN
                  t(tableau).total      := valeur;
            END;

      -- Getter d'une valeur intermédiaire, au niveau case
      FUNCTION GV( tableau NUMERIC ) RETURN FLOAT IS
            BEGIN
                  IF NOT t.exists(tableau) THEN RETURN 0; END IF;
                  IF NOT t(tableau).valeurs.exists( vh_index ) THEN RETURN 0; END IF;
                  RETURN t(tableau).valeurs( vh_index );
            END;

      -- Getter d'une valeur intermédiaire, au niveau tableau
      FUNCTION GT( tableau NUMERIC ) RETURN FLOAT IS
            BEGIN
                  IF NOT t.exists(tableau) THEN RETURN 0; END IF;
                  RETURN t(tableau).total;
            END;




      PROCEDURE DEBUG_VH IS
            tableau NUMERIC;
            vh ose_formule.t_volume_horaire;
            BEGIN
                  IF NOT debug_enabled THEN RETURN; END IF;
                  IF ose_formule.intervenant.etat_volume_horaire_id <> debug_etat_volume_horaire_id THEN RETURN; END IF;

                  FOR i IN 1 .. ose_formule.volumes_horaires.length LOOP
                        vh_index := i;
                        vh := ose_formule.volumes_horaires.items(i);
                        IF vh.volume_horaire_id = debug_volume_horaire_id OR vh.volume_horaire_ref_id = debug_volume_horaire_ref_id THEN
                              ose_formule.DEBUG_INTERVENANT;
                              ose_test.echo('');
                              ose_test.echo('-- DEBUG DE VOLUME HORAIRE --');
                              ose_test.echo('volume_horaire_id         = ' || vh.volume_horaire_id);
                              ose_test.echo('volume_horaire_ref_id     = ' || vh.volume_horaire_ref_id);
                              ose_test.echo('service_id                = ' || vh.service_id);
                              ose_test.echo('service_referentiel_id    = ' || vh.service_referentiel_id);
                              ose_test.echo('taux_fi                   = ' || vh.taux_fi);
                              ose_test.echo('taux_fa                   = ' || vh.taux_fa);
                              ose_test.echo('taux_fc                   = ' || vh.taux_fc);
                              ose_test.echo('ponderation_service_du    = ' || vh.ponderation_service_du);
                              ose_test.echo('ponderation_service_compl = ' || vh.ponderation_service_compl);
                              ose_test.echo('structure_id              = ' || vh.structure_id);
                              ose_test.echo('structure_is_affectation  = ' || CASE WHEN vh.structure_is_affectation THEN 'OUI' ELSE 'NON' END);
                              ose_test.echo('structure_is_univ         = ' || CASE WHEN vh.structure_is_univ THEN 'OUI' ELSE 'NON' END);
                              ose_test.echo('service_statutaire        = ' || CASE WHEN vh.service_statutaire THEN 'OUI' ELSE 'NON' END);
                              ose_test.echo('heures                    = ' || vh.heures);
                              ose_test.echo('taux_service_du           = ' || vh.taux_service_du);
                              ose_test.echo('taux_service_compl        = ' || vh.taux_service_compl);

                              tableau := t.FIRST;
                              LOOP EXIT WHEN tableau IS NULL;
                                    IF gv(tableau) <> 0 OR gt(tableau) <> 0 THEN
                                          ose_test.echo('     t(' || LPAD(tableau,3,' ') || ') v=' || RPAD(round(gv(tableau),3),10,' ') || 't=' || round(gt(tableau),3));
                                    END IF;
                                    tableau := t.NEXT(tableau);
                              END LOOP;

                              ose_test.echo('service_fi                = ' || vh.service_fi);
                              ose_test.echo('service_fa                = ' || vh.service_fa);
                              ose_test.echo('service_fc                = ' || vh.service_fc);
                              ose_test.echo('service_referentiel       = ' || vh.service_referentiel);
                              ose_test.echo('heures_compl_fi           = ' || vh.heures_compl_fi);
                              ose_test.echo('heures_compl_fa           = ' || vh.heures_compl_fa);
                              ose_test.echo('heures_compl_fc           = ' || vh.heures_compl_fc);
                              ose_test.echo('heures_compl_fc_majorees  = ' || vh.heures_compl_fc_majorees);
                              ose_test.echo('heures_compl_referentiel  = ' || vh.heures_compl_referentiel);
                              ose_test.echo('-- FIN DE DEBUG DE VOLUME HORAIRE --');
                              ose_test.echo('');
                        END IF;
                  END LOOP;
            END;



      -- Formule de calcul définie par tableaux
      FUNCTION EXECFORMULE( tableau NUMERIC, version NUMERIC ) RETURN FLOAT IS
            vh ose_formule.t_volume_horaire;
            BEGIN
                  vh := ose_formule.volumes_horaires.items(vh_index);
                  CASE


                        WHEN tableau = 11 AND version = 2 THEN
                        IF vh.structure_is_affectation AND vh.taux_fc < 1 THEN
                              RETURN vh.heures;
                        ELSE
                              RETURN 0;
                        END IF;



                        WHEN tableau = 11 AND version = 3 THEN
                        IF vh.structure_is_affectation THEN
                              RETURN vh.heures * (vh.taux_fi + vh.taux_fa);
                        ELSE
                              RETURN 0;
                        END IF;



                        WHEN tableau = 12 AND version = 2 THEN
                        IF NOT vh.structure_is_affectation AND vh.taux_fc < 1 THEN
                              RETURN vh.heures;
                        ELSE
                              RETURN 0;
                        END IF;



                        WHEN tableau = 12 AND version = 3 THEN
                        IF NOT vh.structure_is_affectation THEN
                              RETURN vh.heures * (vh.taux_fi + vh.taux_fa);
                        ELSE
                              RETURN 0;
                        END IF;



                        WHEN tableau = 13 AND version = 2 THEN
                        IF vh.structure_is_affectation AND vh.taux_fc = 1 THEN
                              RETURN vh.heures;
                        ELSE
                              RETURN 0;
                        END IF;



                        WHEN tableau = 13 AND version = 3 THEN
                        IF vh.structure_is_affectation THEN
                              RETURN vh.heures * vh.taux_fc;
                        ELSE
                              RETURN 0;
                        END IF;



                        WHEN tableau = 14 AND version = 2 THEN
                        IF NOT vh.structure_is_affectation AND vh.taux_fc = 1 THEN
                              RETURN vh.heures;
                        ELSE
                              RETURN 0;
                        END IF;



                        WHEN tableau = 14 AND version = 3 THEN
                        IF NOT vh.structure_is_affectation THEN
                              RETURN vh.heures * vh.taux_fc;
                        ELSE
                              RETURN 0;
                        END IF;



                        WHEN tableau = 15 AND version = 2 THEN
                        IF vh.structure_is_affectation THEN
                              RETURN vh.heures;
                        ELSE
                              RETURN 0;
                        END IF;



                        WHEN tableau = 16 AND version = 2 THEN
                        IF NOT vh.structure_is_affectation AND NOT vh.structure_is_univ THEN
                              RETURN vh.heures;
                        ELSE
                              RETURN 0;
                        END IF;



                        WHEN tableau = 17 AND version = 2 THEN
                        IF vh.structure_is_univ THEN
                              RETURN vh.heures;
                        ELSE
                              RETURN 0;
                        END IF;



                        WHEN tableau = 21 AND version = 2 THEN
                        RETURN gv(11) * vh.taux_service_du;



                        WHEN tableau = 22 AND version = 2 THEN
                        RETURN gv(12) * vh.taux_service_du;



                        WHEN tableau = 23 AND version = 2 THEN
                        RETURN gv(13) * vh.taux_service_du;



                        WHEN tableau = 24 AND version = 2 THEN
                        RETURN gv(14) * vh.taux_service_du;



                        WHEN tableau = 25 AND version = 2 THEN
                        RETURN gv(15);



                        WHEN tableau = 26 AND version = 2 THEN
                        RETURN gv(16);



                        WHEN tableau = 27 AND version = 2 THEN
                        RETURN gv(17);



                        WHEN tableau = 31 AND version = 2 THEN
                        RETURN GREATEST( ose_formule.intervenant.service_du - gt(21), 0 );



                        WHEN tableau = 32 AND version = 2 THEN
                        RETURN GREATEST( gt(31) - gt(22), 0 );



                        WHEN tableau = 33 AND version = 2 THEN
                        RETURN GREATEST( gt(32) - gt(23), 0 );



                        WHEN tableau = 34 AND version = 2 THEN
                        RETURN GREATEST( gt(33) - gt(24), 0 );



                        WHEN tableau = 35 AND version = 2 THEN
                        RETURN GREATEST( gt(34) - gt(25), 0 );



                        WHEN tableau = 36 AND version = 2 THEN
                        RETURN GREATEST( gt(35) - gt(26), 0 );



                        WHEN tableau = 37 AND version = 2 THEN
                        RETURN GREATEST( gt(36) - gt(27), 0 );



                        WHEN tableau = 41 AND version = 2 THEN
                        IF gt(21) <> 0 THEN
                              RETURN gv(21) / gt(21);
                        ELSE
                              RETURN 0;
                        END IF;



                        WHEN tableau = 42 AND version = 2 THEN
                        IF gt(22) <> 0 THEN
                              RETURN gv(22) / gt(22);
                        ELSE
                              RETURN 0;
                        END IF;



                        WHEN tableau = 43 AND version = 2 THEN
                        IF gt(23) <> 0 THEN
                              RETURN gv(23) / gt(23);
                        ELSE
                              RETURN 0;
                        END IF;



                        WHEN tableau = 44 AND version = 2 THEN
                        IF gt(24) <> 0 THEN
                              RETURN gv(24) / gt(24);
                        ELSE
                              RETURN 0;
                        END IF;



                        WHEN tableau = 45 AND version = 2 THEN
                        IF gt(25) <> 0 THEN
                              RETURN gv(25) / gt(25);
                        ELSE
                              RETURN 0;
                        END IF;



                        WHEN tableau = 46 AND version = 2 THEN
                        IF gt(26) <> 0 THEN
                              RETURN gv(26) / gt(26);
                        ELSE
                              RETURN 0;
                        END IF;



                        WHEN tableau = 47 AND version = 2 THEN
                        IF gt(27) <> 0 THEN
                              RETURN gv(27) / gt(27);
                        ELSE
                              RETURN 0;
                        END IF;



                        WHEN tableau = 51 AND version = 2 THEN
                        RETURN LEAST( ose_formule.intervenant.service_du, gt(21) ) * gv(41);



                        WHEN tableau = 52 AND version = 2 THEN
                        RETURN LEAST( gt(31), gt(22) ) * gv(42);



                        WHEN tableau = 53 AND version = 2 THEN
                        RETURN LEAST( gt(32), gt(23) ) * gv(43);



                        WHEN tableau = 54 AND version = 2 THEN
                        RETURN LEAST( gt(33), gt(24) ) * gv(44);



                        WHEN tableau = 55 AND version = 2 THEN
                        RETURN LEAST( gt(34), gt(25) ) * gv(45);



                        WHEN tableau = 56 AND version = 2 THEN
                        RETURN LEAST( gt(35), gt(26) ) * gv(46);



                        WHEN tableau = 57 AND version = 2 THEN
                        RETURN LEAST( gt(36), gt(27) ) * gv(47);



                        WHEN tableau = 61 AND version = 2 THEN
                        RETURN gv(51) * vh.taux_fi;



                        WHEN tableau = 61 AND version = 3 THEN
                        IF vh.taux_fi + vh.taux_fa > 0 THEN
                              RETURN gv(51) / (vh.taux_fi + vh.taux_fa) * vh.taux_fi;
                        ELSE
                              RETURN 0;
                        END IF;



                        WHEN tableau = 62 AND version = 2 THEN
                        RETURN gv(52) * vh.taux_fi;



                        WHEN tableau = 62 AND version = 3 THEN
                        IF vh.taux_fi + vh.taux_fa > 0 THEN
                              RETURN gv(52) / (vh.taux_fi + vh.taux_fa) * vh.taux_fi;
                        ELSE
                              RETURN 0;
                        END IF;



                        WHEN tableau = 71 AND version = 2 THEN
                        RETURN gv(51) * vh.taux_fa;



                        WHEN tableau = 71 AND version = 3 THEN
                        IF vh.taux_fi + vh.taux_fa > 0 THEN
                              RETURN gv(51) / (vh.taux_fi + vh.taux_fa) * vh.taux_fa;
                        ELSE
                              RETURN 0;
                        END IF;



                        WHEN tableau = 72 AND version = 2 THEN
                        RETURN gv(52) * vh.taux_fa;



                        WHEN tableau = 72 AND version = 3 THEN
                        IF vh.taux_fi + vh.taux_fa > 0 THEN
                              RETURN gv(52) / (vh.taux_fi + vh.taux_fa) * vh.taux_fa;
                        ELSE
                              RETURN 0;
                        END IF;



                        WHEN tableau = 81 AND version = 2 THEN
                        RETURN gv(51) * vh.taux_fc;



                        WHEN tableau = 82 AND version = 2 THEN
                        RETURN gv(52) * vh.taux_fc;



                        WHEN tableau = 83 AND version = 2 THEN
                        RETURN gv(53) * vh.taux_fc;



                        WHEN tableau = 83 AND version = 3 THEN
                        RETURN gv(53);



                        WHEN tableau = 84 AND version = 2 THEN
                        RETURN gv(54) * vh.taux_fc;



                        WHEN tableau = 84 AND version = 3 THEN
                        RETURN gv(54);



                        WHEN tableau = 91 AND version = 2 THEN
                        IF gv(21) <> 0 THEN
                              RETURN gv(51) / gv(21);
                        ELSE
                              RETURN 0;
                        END IF;



                        WHEN tableau = 92 AND version = 2 THEN
                        IF gv(22) <> 0 THEN
                              RETURN gv(52) / gv(22);
                        ELSE
                              RETURN 0;
                        END IF;



                        WHEN tableau = 93 AND version = 2 THEN
                        IF gv(23) <> 0 THEN
                              RETURN gv(53) / gv(23);
                        ELSE
                              RETURN 0;
                        END IF;



                        WHEN tableau = 94 AND version = 2 THEN
                        IF gv(24) <> 0 THEN
                              RETURN gv(54) / gv(24);
                        ELSE
                              RETURN 0;
                        END IF;



                        WHEN tableau = 95 AND version = 2 THEN
                        IF gv(25) <> 0 THEN
                              RETURN gv(55) / gv(25);
                        ELSE
                              RETURN 0;
                        END IF;



                        WHEN tableau = 96 AND version = 2 THEN
                        IF gv(26) <> 0 THEN
                              RETURN gv(56) / gv(26);
                        ELSE
                              RETURN 0;
                        END IF;



                        WHEN tableau = 97 AND version = 2 THEN
                        IF gv(27) <> 0 THEN
                              RETURN gv(57) / gv(27);
                        ELSE
                              RETURN 0;
                        END IF;



                        WHEN tableau = 101 AND version = 2 THEN
                        IF gt(37) <> 0 THEN
                              RETURN 0;
                        ELSE
                              RETURN 1 - gv(91);
                        END IF;



                        WHEN tableau = 102 AND version = 2 THEN
                        IF gt(37) <> 0 THEN
                              RETURN 0;
                        ELSE
                              RETURN 1 - gv(92);
                        END IF;



                        WHEN tableau = 103 AND version = 2 THEN
                        IF gt(37) <> 0 THEN
                              RETURN 0;
                        ELSE
                              RETURN 1 - gv(93);
                        END IF;



                        WHEN tableau = 104 AND version = 2 THEN
                        IF gt(37) <> 0 THEN
                              RETURN 0;
                        ELSE
                              RETURN 1 - gv(94);
                        END IF;



                        WHEN tableau = 105 AND version = 2 THEN
                        IF gt(37) <> 0 THEN
                              RETURN 0;
                        ELSE
                              RETURN 1 - gv(95);
                        END IF;



                        WHEN tableau = 106 AND version = 2 THEN
                        IF gt(37) <> 0 THEN
                              RETURN 0;
                        ELSE
                              RETURN 1 - gv(96);
                        END IF;



                        WHEN tableau = 107 AND version = 2 THEN
                        IF gt(37) <> 0 THEN
                              RETURN 0;
                        ELSE
                              RETURN 1 - gv(97);
                        END IF;



                        WHEN tableau = 111 AND version = 2 THEN
                        RETURN gv(11) * vh.taux_service_compl * gv(101);



                        WHEN tableau = 112 AND version = 2 THEN
                        RETURN gv(12) * vh.taux_service_compl * gv(102);



                        WHEN tableau = 113 AND version = 2 THEN
                        RETURN gv(13) * vh.taux_service_compl * gv(103);



                        WHEN tableau = 114 AND version = 2 THEN
                        RETURN gv(14) * vh.taux_service_compl * gv(104);



                        WHEN tableau = 115 AND version = 2 THEN
                        RETURN gv(15) * gv(105);



                        WHEN tableau = 116 AND version = 2 THEN
                        RETURN gv(16) * gv(106);



                        WHEN tableau = 117 AND version = 2 THEN
                        RETURN gv(17) * gv(107);



                        WHEN tableau = 123 AND version = 2 THEN
                        IF vh.taux_fc = 1 THEN
                              RETURN gv(113) * vh.ponderation_service_compl;
                        ELSE
                              RETURN gv(113);
                        END IF;



                        WHEN tableau = 123 AND version = 3 THEN
                        IF vh.taux_fc > 0 THEN
                              RETURN gv(113) * vh.ponderation_service_compl;
                        ELSE
                              RETURN gv(113);
                        END IF;



                        WHEN tableau = 124 AND version = 2 THEN
                        IF vh.taux_fc = 1 THEN
                              RETURN gv(114) * vh.ponderation_service_compl;
                        ELSE
                              RETURN gv(114);
                        END IF;



                        WHEN tableau = 124 AND version = 3 THEN
                        IF vh.taux_fc > 0 THEN
                              RETURN gv(114) * vh.ponderation_service_compl;
                        ELSE
                              RETURN gv(114);
                        END IF;



                        WHEN tableau = 131 AND version = 2 THEN
                        RETURN gv(111) * vh.taux_fi;



                        WHEN tableau = 131 AND version = 3 THEN
                        IF vh.taux_fi + vh.taux_fa > 0 THEN
                              RETURN gv(111) / (vh.taux_fi + vh.taux_fa) * vh.taux_fi;
                        ELSE
                              RETURN 0;
                        END IF;



                        WHEN tableau = 132 AND version = 2 THEN
                        RETURN gv(112) * vh.taux_fi;



                        WHEN tableau = 132 AND version = 3 THEN
                        IF vh.taux_fi + vh.taux_fa > 0 THEN
                              RETURN gv(112) / (vh.taux_fi + vh.taux_fa) * vh.taux_fi;
                        ELSE
                              RETURN 0;
                        END IF;



                        WHEN tableau = 141 AND version = 2 THEN
                        RETURN gv(111) * vh.taux_fa;



                        WHEN tableau = 141 AND version = 3 THEN
                        IF vh.taux_fi + vh.taux_fa > 0 THEN
                              RETURN gv(111) / (vh.taux_fi + vh.taux_fa) * vh.taux_fa;
                        ELSE
                              RETURN 0;
                        END IF;



                        WHEN tableau = 142 AND version = 2 THEN
                        RETURN gv(112) * vh.taux_fa;



                        WHEN tableau = 142 AND version = 3 THEN
                        IF vh.taux_fi + vh.taux_fa > 0 THEN
                              RETURN gv(112) / (vh.taux_fi + vh.taux_fa) * vh.taux_fa;
                        ELSE
                              RETURN 0;
                        END IF;



                        WHEN tableau = 151 AND version = 2 THEN
                        RETURN gv(111) * vh.taux_fc;



                        WHEN tableau = 152 AND version = 2 THEN
                        RETURN gv(112) * vh.taux_fc;



                        WHEN tableau = 153 AND version = 2 THEN
                        IF gv(123) = gv(113) THEN
                              RETURN gv(113) * vh.taux_fc;
                        ELSE
                              RETURN 0;
                        END IF;



                        WHEN tableau = 153 AND version = 3 THEN
                        IF gv(123) = gv(113) THEN
                              RETURN gv(113);
                        ELSE
                              RETURN 0;
                        END IF;



                        WHEN tableau = 154 AND version = 2 THEN
                        IF gv(124) = gv(114) THEN
                              RETURN gv(114) * vh.taux_fc;
                        ELSE
                              RETURN 0;
                        END IF;



                        WHEN tableau = 154 AND version = 3 THEN
                        IF gv(124) = gv(114) THEN
                              RETURN gv(114);
                        ELSE
                              RETURN 0;
                        END IF;



                        WHEN tableau = 163 AND version = 2 THEN
                        IF gv(123) <> gv(113) THEN
                              RETURN gv(123) * vh.taux_fc;
                        ELSE
                              RETURN 0;
                        END IF;



                        WHEN tableau = 163 AND version = 3 THEN
                        IF gv(123) <> gv(113) THEN
                              RETURN gv(123);
                        ELSE
                              RETURN 0;
                        END IF;



                        WHEN tableau = 164 AND version = 2 THEN
                        IF gv(124) <> gv(114) THEN
                              RETURN gv(124) * vh.taux_fc;
                        ELSE
                              RETURN 0;
                        END IF;



                        WHEN tableau = 164 AND version = 3 THEN
                        IF gv(124) <> gv(114) THEN
                              RETURN gv(124);
                        ELSE
                              RETURN 0;
                        END IF;



                  ELSE
                        raise_application_error( -20001, 'Le tableau ' || tableau || ' version ' || version || ' n''existe pas!');
                  END CASE; END;







      PROCEDURE CALCUL_RESULTAT_V2 IS
            tableaux       t_tableaux_configs;
            valeur         FLOAT;
            BEGIN

                  -- Définition des tableaux à utiliser
                  tableaux := t_tableaux_configs(
                      tc( 11,2    ), tc( 12,2    ), tc( 13,2    ), tc( 14,2    ), tc( 15,2,'r' ), tc( 16,2,'r' ), tc( 17,2,'r' ),
                      tc( 21,2    ), tc( 22,2    ), tc( 23,2    ), tc( 24,2    ), tc( 25,2,'r' ), tc( 26,2,'r' ), tc( 27,2,'r' ),
                      tc( 31,2,'t'), tc( 32,2,'t'), tc( 33,2,'t'), tc( 34,2,'t'), tc( 35,2,'tr'), tc( 36,2,'tr'), tc( 37,2,'tr'),
                      tc( 41,2    ), tc( 42,2    ), tc( 43,2    ), tc( 44,2    ), tc( 45,2,'r' ), tc( 46,2,'r' ), tc( 47,2,'r' ),
                      tc( 51,2    ), tc( 52,2    ), tc( 53,2    ), tc( 54,2    ), tc( 55,2,'r' ), tc( 56,2,'r' ), tc( 57,2,'r' ),
                      tc( 61,2    ), tc( 62,2    ),
                      tc( 71,2    ), tc( 72,2    ),
                      tc( 81,2    ), tc( 82,2    ), tc( 83,2    ), tc( 84,2    ),
                      tc( 91,2    ), tc( 92,2    ), tc( 93,2    ), tc( 94,2    ), tc( 95,2,'r' ), tc( 96,2,'r' ), tc( 97,2,'r' ),
                      tc(101,2    ), tc(102,2    ), tc(103,2    ), tc(104,2    ), tc(105,2,'r' ), tc(106,2,'r' ), tc(107,2,'r' ),
                      tc(111,2    ), tc(112,2    ), tc(113,2    ), tc(114,2    ), tc(115,2,'r' ), tc(116,2,'r' ), tc(117,2,'r' ),
                      tc(123,2    ), tc(124,2    ),
                      tc(131,2    ), tc(132,2    ),
                      tc(141,2    ), tc(142,2    ),
                      tc(151,2    ), tc(152,2    ), tc(153,2    ), tc(154,2    ),
                      tc(163,2    ), tc(164,2    )
                  );

                  -- calcul par tableau pour chaque volume horaire
                  t.delete;
                  FOR it IN tableaux.FIRST .. tableaux.LAST LOOP
                        FOR ivh IN 1 .. ose_formule.volumes_horaires.length LOOP
                              vh_index := ivh;
                              IF
                              ose_formule.volumes_horaires.items(ivh).service_id IS NOT NULL AND NOT tableaux(it).referentiel
                              OR ose_formule.volumes_horaires.items(ivh).service_referentiel_id IS NOT NULL AND tableaux(it).referentiel
                              OR tableaux(it).setTotal -- car on en a besoin tout le temps
                              THEN
                                    valeur := EXECFORMULE(tableaux(it).tableau, tableaux(it).version);
                                    IF tableaux(it).setTotal THEN
                                          ST( tableaux(it).tableau, valeur );
                                    ELSE
                                          SV( tableaux(it).tableau, valeur );
                                    END IF;
                              END IF;
                        END LOOP;
                  END LOOP;

                  -- transmisssion des résultats aux volumes horaires et volumes horaires référentiel
                  FOR i IN 1 .. ose_formule.volumes_horaires.length LOOP
                        vh_index := i;
                        IF ose_formule.volumes_horaires.items(i).service_id IS NOT NULL THEN
                              ose_formule.volumes_horaires.items(i).service_fi               := gv( 61) + gv( 62);
                              ose_formule.volumes_horaires.items(i).service_fa               := gv( 71) + gv( 72);
                              ose_formule.volumes_horaires.items(i).service_fc               := gv( 81) + gv( 82) + gv( 83) + gv( 84);
                              ose_formule.volumes_horaires.items(i).heures_compl_fi          := gv(131) + gv(132);
                              ose_formule.volumes_horaires.items(i).heures_compl_fa          := gv(141) + gv(142);
                              ose_formule.volumes_horaires.items(i).heures_compl_fc          := gv(151) + gv(152) + gv(153) + gv(154);
                              ose_formule.volumes_horaires.items(i).heures_compl_fc_majorees :=                     gv(163) + gv(164);
                        ELSIF ose_formule.volumes_horaires.items(i).service_referentiel_id IS NOT NULL THEN
                              ose_formule.volumes_horaires.items(i).service_referentiel      := gv( 55) + gv( 56) + gv( 57);
                              ose_formule.volumes_horaires.items(i).heures_compl_referentiel := gv(115) + gv(116) + gv(117);
                        END IF;
                  END LOOP;

                  DEBUG_VH;
            END;



      PROCEDURE CALCUL_RESULTAT_V3 IS
            tableaux       t_tableaux_configs;
            valeur         FLOAT;
            BEGIN
                  -- si l'année est antérieure à 2016/2017 alors on utilise la V2!!
                  IF ose_formule.intervenant.annee_id < 2016 THEN
                        CALCUL_RESULTAT_V2;
                        RETURN;
                  END IF;


                  -- Définition des tableaux à utiliser
                  tableaux := t_tableaux_configs(
                      tc( 11,3    ), tc( 12,3    ), tc( 13,3    ), tc( 14,3    ), tc( 15,2,'r' ), tc( 16,2,'r' ), tc( 17,2,'r' ),
                      tc( 21,2    ), tc( 22,2    ), tc( 23,2    ), tc( 24,2    ), tc( 25,2,'r' ), tc( 26,2,'r' ), tc( 27,2,'r' ),
                      tc( 31,2,'t'), tc( 32,2,'t'), tc( 33,2,'t'), tc( 34,2,'t'), tc( 35,2,'tr'), tc( 36,2,'tr'), tc( 37,2,'tr'),
                      tc( 41,2    ), tc( 42,2    ), tc( 43,2    ), tc( 44,2    ), tc( 45,2,'r' ), tc( 46,2,'r' ), tc( 47,2,'r' ),
                      tc( 51,2    ), tc( 52,2    ), tc( 53,2    ), tc( 54,2    ), tc( 55,2,'r' ), tc( 56,2,'r' ), tc( 57,2,'r' ),
                      tc( 61,3    ), tc( 62,3    ),
                      tc( 71,3    ), tc( 72,3    ),
                      tc( 83,3    ), tc( 84,3    ),
                      tc( 91,2    ), tc( 92,2    ), tc( 93,2    ), tc( 94,2    ), tc( 95,2,'r' ), tc( 96,2,'r' ), tc( 97,2,'r' ),
                      tc(101,2    ), tc(102,2    ), tc(103,2    ), tc(104,2    ), tc(105,2,'r' ), tc(106,2,'r' ), tc(107,2,'r' ),
                      tc(111,2    ), tc(112,2    ), tc(113,2    ), tc(114,2    ), tc(115,2,'r' ), tc(116,2,'r' ), tc(117,2,'r' ),
                      tc(123,3    ), tc(124,3    ),
                      tc(131,3    ), tc(132,3    ),
                      tc(141,3    ), tc(142,3    ),
                      tc(153,3    ), tc(154,3    ),
                      tc(163,3    ), tc(164,3    )
                  );

                  -- calcul par tableau pour chaque volume horaire
                  t.delete;
                  FOR it IN tableaux.FIRST .. tableaux.LAST LOOP
                        FOR ivh IN 1 .. ose_formule.volumes_horaires.length LOOP
                              vh_index := ivh;
                              IF
                              ose_formule.volumes_horaires.items(ivh).service_id IS NOT NULL AND NOT tableaux(it).referentiel
                              OR ose_formule.volumes_horaires.items(ivh).service_referentiel_id IS NOT NULL AND tableaux(it).referentiel
                              OR tableaux(it).setTotal -- car on en a besoin tout le temps
                              THEN
                                    valeur := EXECFORMULE(tableaux(it).tableau, tableaux(it).version);
                                    IF tableaux(it).setTotal THEN
                                          ST( tableaux(it).tableau, valeur );
                                    ELSE
                                          SV( tableaux(it).tableau, valeur );
                                    END IF;
                              END IF;
                        END LOOP;
                  END LOOP;

                  -- transmission des résultats aux volumes horaires et volumes horaires référentiel
                  FOR i IN 1 .. ose_formule.volumes_horaires.length LOOP
                        vh_index := i;
                        IF ose_formule.volumes_horaires.items(i).service_id IS NOT NULL THEN
                              ose_formule.volumes_horaires.items(i).service_fi               := gv( 61) + gv( 62);
                              ose_formule.volumes_horaires.items(i).service_fa               := gv( 71) + gv( 72);
                              ose_formule.volumes_horaires.items(i).service_fc               := gv( 83) + gv( 84);
                              ose_formule.volumes_horaires.items(i).heures_compl_fi          := gv(131) + gv(132);
                              ose_formule.volumes_horaires.items(i).heures_compl_fa          := gv(141) + gv(142);
                              ose_formule.volumes_horaires.items(i).heures_compl_fc          := gv(153) + gv(154);
                              ose_formule.volumes_horaires.items(i).heures_compl_fc_majorees := gv(163) + gv(164);
                        ELSIF ose_formule.volumes_horaires.items(i).service_referentiel_id IS NOT NULL THEN
                              ose_formule.volumes_horaires.items(i).service_referentiel      := gv( 55) + gv( 56) + gv( 57);
                              ose_formule.volumes_horaires.items(i).heures_compl_referentiel := gv(115) + gv(116) + gv(117);
                        END IF;
                  END LOOP;

                  DEBUG_VH;
            END;



      PROCEDURE PURGE_EM_NON_FC IS
            BEGIN
                  FOR em IN (
                  SELECT
                         em.id
                  FROM
                       ELEMENT_MODULATEUR em
                             JOIN element_pedagogique ep ON ep.id = em.element_id AND ep.histo_destruction IS NULL
                  WHERE
                      em.histo_destruction IS NULL
                    AND ep.taux_fc < 1
                  ) LOOP
                        UPDATE
                            element_modulateur
                        SET
                            histo_destruction = SYSDATE,
                            histo_destructeur_id = ose_parametre.get_ose_user
                        WHERE
                            id = em.id
                  ;
                  END LOOP;
            END;


END UNICAEN_OSE_FORMULE;

/




-- DdlView.alter.

CREATE OR REPLACE FORCE VIEW "V_CONTRAT_SERVICES" ("CONTRAT_ID", "serviceComposante", "serviceCode", "serviceLibelle", "HEURES", "serviceHeures") AS
      SELECT
             c.id                                             contrat_id,
             str.libelle_court                                "serviceComposante",
             ep.code                                          "serviceCode",
             ep.libelle                                       "serviceLibelle",
             sum(vh.heures)                                   heures,
             replace(ltrim(to_char(sum(vh.heures), '999999.00')),'.',',') "serviceHeures"
      FROM
           contrat                  c
                 JOIN intervenant              i ON i.id = c.intervenant_id
                 JOIN type_volume_horaire    tvh ON tvh.code = 'PREVU'
                 JOIN service                  s ON s.intervenant_id = i.id AND s.histo_destruction IS NULL
                 JOIN volume_horaire          vh ON vh.service_id = s.id AND vh.histo_destruction IS NULL AND vh.type_volume_horaire_id = tvh.id
                 LEFT JOIN validation_vol_horaire vvh ON vvh.volume_horaire_id = vh.id
                 LEFT JOIN validation               v ON v.id = vvh.validation_id AND v.histo_destruction IS NULL
                 LEFT JOIN validation              cv ON cv.id = c.validation_id AND cv.histo_destruction IS NULL
                 LEFT JOIN element_pedagogique     ep ON ep.id = s.element_pedagogique_id
                 JOIN structure              str ON str.id = COALESCE(ep.structure_id,i.structure_id)
      WHERE
          c.histo_destruction IS NULL
        AND (cv.id IS NULL OR vh.contrat_id = c.id)
        AND (vh.auto_validation = 1 OR v.id IS NOT NULL)
      GROUP BY
               c.id, str.libelle_court, ep.code, ep.libelle

/

CREATE OR REPLACE FORCE VIEW "V_ETAT_PAIEMENT" ("ANNEE_ID", "TYPE_INTERVENANT_ID", "STRUCTURE_ID", "PERIODE_ID", "INTERVENANT_ID", "CENTRE_COUT_ID", "DOMAINE_FONCTIONNEL_ID", "ANNEE", "ETAT", "COMPOSANTE", "DATE_MISE_EN_PAIEMENT", "PERIODE", "STATUT", "INTERVENANT_CODE", "INTERVENANT_NOM", "INTERVENANT_NUMERO_INSEE", "CENTRE_COUT_CODE", "CENTRE_COUT_LIBELLE", "DOMAINE_FONCTIONNEL_CODE", "DOMAINE_FONCTIONNEL_LIBELLE", "HETD", "HETD_POURC", "HETD_MONTANT", "REM_FC_D714", "EXERCICE_AA", "EXERCICE_AA_MONTANT", "EXERCICE_AC", "EXERCICE_AC_MONTANT") AS
      SELECT
             annee_id,
             type_intervenant_id,
             structure_id,
             periode_id,
             intervenant_id,
             centre_cout_id,
             domaine_fonctionnel_id,

             annee_id || '/' || (annee_id+1) annee,
             etat,
             composante,
             date_mise_en_paiement,
             periode,
             statut,
             intervenant_code,
             intervenant_nom,
             intervenant_numero_insee,
             centre_cout_code,
             centre_cout_libelle,
             domaine_fonctionnel_code,
             domaine_fonctionnel_libelle,
             hetd,
             CASE WHEN pourc_ecart >= 0 THEN
                 CASE WHEN RANK() OVER (PARTITION BY periode_id, intervenant_id, etat, structure_id ORDER BY CASE WHEN (pourc_ecart >= 0 AND pourc_diff >= 0) OR (pourc_ecart < 0 AND pourc_diff < 0) THEN pourc_diff ELSE -1 END DESC) <= (ABS(pourc_ecart) / 0.001) THEN hetd_pourc + (pourc_ecart / ABS(pourc_ecart) * 0.001) ELSE hetd_pourc END
                  ELSE
                 CASE WHEN RANK() OVER (PARTITION BY periode_id, intervenant_id, etat, structure_id ORDER BY CASE WHEN (pourc_ecart >= 0 AND pourc_diff >= 0) OR (pourc_ecart < 0 AND pourc_diff < 0) THEN pourc_diff ELSE -1 END) <= (ABS(pourc_ecart) / 0.001) THEN hetd_pourc + (pourc_ecart / ABS(pourc_ecart) * 0.001) ELSE hetd_pourc END
                 END hetd_pourc,
             hetd_montant,
             rem_fc_d714,
             exercice_aa,
             exercice_aa_montant,
             exercice_ac,
             exercice_ac_montant
      FROM
           (
           SELECT
                  dep3.*,

                  1-CASE WHEN hetd > 0 THEN SUM( hetd_pourc ) OVER ( PARTITION BY periode_id, intervenant_id, etat, structure_id) ELSE 0 END pourc_ecart


           FROM (

                SELECT
                       periode_id,
                       structure_id,
                       type_intervenant_id,
                       intervenant_id,
                       annee_id,
                       centre_cout_id,
                       domaine_fonctionnel_id,
                       etat,
                       composante,
                       date_mise_en_paiement,
                       periode,
                       statut,
                       intervenant_code,
                       intervenant_nom,
                       intervenant_numero_insee,
                       centre_cout_code,
                       centre_cout_libelle,
                       domaine_fonctionnel_code,
                       domaine_fonctionnel_libelle,
                       hetd,
                       ROUND( CASE WHEN hetd > 0 THEN hetd / SUM( hetd ) OVER( PARTITION BY periode_id, intervenant_id, etat, structure_id) ELSE 0 END, 3 ) hetd_pourc,
                       ROUND( hetd * taux_horaire, 2 ) hetd_montant,
                       ROUND( fc_majorees * taux_horaire, 2 ) rem_fc_d714,
                       exercice_aa,
                       ROUND( exercice_aa * taux_horaire, 2 ) exercice_aa_montant,
                       exercice_ac,
                       ROUND( exercice_ac * taux_horaire, 2 ) exercice_ac_montant,


                       (CASE WHEN hetd > 0 THEN hetd / SUM( hetd ) OVER( PARTITION BY periode_id, intervenant_id, etat, structure_id) ELSE 0 END)
                             -
                       ROUND( CASE WHEN hetd > 0 THEN hetd / SUM( hetd ) OVER( PARTITION BY periode_id, intervenant_id, etat, structure_id) ELSE 0 END, 3 ) pourc_diff

                FROM (
                     WITH dep AS ( -- détails par état de paiement
                         SELECT
                                CASE WHEN th.code = 'fc_majorees' THEN 1 ELSE 0 END                 is_fc_majoree,
                                p.id                                                                periode_id,
                                s.id                                                                structure_id,
                                i.id                                                                intervenant_id,
                                i.annee_id                                                          annee_id,
                                cc.id                                                               centre_cout_id,
                                df.id                                                               domaine_fonctionnel_id,
                                ti.id                                                               type_intervenant_id,
                                CASE
                                      WHEN mep.date_mise_en_paiement IS NULL THEN 'a-mettre-en-paiement'
                                      ELSE 'mis-en-paiement'
                                    END                                                                 etat,

                                TRIM(p.libelle_long || ' ' || to_char( add_months( a.date_debut, p.ecart_mois ), 'yyyy' )) periode,
                                mep.date_mise_en_paiement                                           date_mise_en_paiement,
                                s.libelle_court                                                     composante,
                                ti.libelle                                                          statut,
                                i.source_code                                                       intervenant_code,
                                i.nom_usuel || ' ' || i.prenom                                      intervenant_nom,
                                TRIM( NVL(i.numero_insee,'') || NVL(TO_CHAR(i.numero_insee_cle,'00'),'') ) intervenant_numero_insee,
                                cc.source_code                                                      centre_cout_code,
                                cc.libelle                                                          centre_cout_libelle,
                                df.source_code                                                      domaine_fonctionnel_code,
                                df.libelle                                                          domaine_fonctionnel_libelle,
                                CASE WHEN th.code = 'fc_majorees' THEN 0 ELSE mep.heures END        hetd,
                                CASE WHEN th.code = 'fc_majorees' THEN mep.heures ELSE 0 END        fc_majorees,
                                mep.heures * 4 / 10                                                 exercice_aa,
                                mep.heures * 6 / 10                                                 exercice_ac,
                             --CASE WHEN th.code = 'fc_majorees' THEN 0 ELSE mep.heures END * 4 / 10                                                 exercice_aa,
                             --CASE WHEN th.code = 'fc_majorees' THEN 0 ELSE mep.heures END * 6 / 10                                                 exercice_ac,
                                OSE_FORMULE.GET_TAUX_HORAIRE_HETD( NVL(mep.date_mise_en_paiement,SYSDATE) )      taux_horaire
                         FROM
                              v_mep_intervenant_structure  mis
                                    JOIN mise_en_paiement        mep ON mep.id = mis.mise_en_paiement_id AND mep.histo_destruction IS NULL
                                    JOIN type_heures              th ON  th.id = mep.type_heures_id
                                    JOIN centre_cout              cc ON  cc.id = mep.centre_cout_id      -- pas d'historique pour les centres de coût, qui devront tout de même apparaitre mais en erreur
                                    JOIN intervenant               i ON   i.id = mis.intervenant_id      AND i.histo_destruction IS NULL
                                    JOIN annee                     a ON   a.id = i.annee_id
                                    JOIN statut_intervenant       si ON  si.id = i.statut_id
                                    JOIN type_intervenant         ti ON  ti.id = si.type_intervenant_id
                                    JOIN structure                 s ON   s.id = mis.structure_id
                                    LEFT JOIN validation           v ON   v.id = mep.validation_id       AND v.histo_destruction IS NULL
                                    LEFT JOIN domaine_fonctionnel df ON  df.id = mis.domaine_fonctionnel_id
                                    LEFT JOIN periode              p ON   p.id = mep.periode_paiement_id
                     )
                     SELECT
                            periode_id,
                            structure_id,
                            type_intervenant_id,
                            intervenant_id,
                            annee_id,
                            centre_cout_id,
                            domaine_fonctionnel_id,
                            etat,
                            periode,
                            composante,
                            date_mise_en_paiement,
                            statut,
                            intervenant_code,
                            intervenant_nom,
                            intervenant_numero_insee,
                            centre_cout_code,
                            centre_cout_libelle,
                            domaine_fonctionnel_code,
                            domaine_fonctionnel_libelle,
                            SUM( hetd ) hetd,
                            SUM( fc_majorees ) fc_majorees,
                            SUM( exercice_aa ) exercice_aa,
                            SUM( exercice_ac ) exercice_ac,
                            taux_horaire
                     FROM
                          dep
                     GROUP BY
                              periode_id,
                              structure_id,
                              type_intervenant_id,
                              intervenant_id,
                              annee_id,
                              centre_cout_id,
                              domaine_fonctionnel_id,
                              etat,
                              periode,
                              composante,
                              date_mise_en_paiement,
                              statut,
                              intervenant_code,
                              intervenant_nom,
                              intervenant_numero_insee,
                              centre_cout_code,
                              centre_cout_libelle,
                              domaine_fonctionnel_code,
                              domaine_fonctionnel_libelle,
                              taux_horaire,
                              is_fc_majoree
                     )
                         dep2
                )
                    dep3
           )
               dep4
      ORDER BY
               annee_id,
               type_intervenant_id,
               structure_id,
               periode_id,
               intervenant_id

/

CREATE OR REPLACE FORCE VIEW "V_EXPORT_PAIEMENT_WINPAIE" ("ANNEE_ID", "TYPE_INTERVENANT_ID", "STRUCTURE_ID", "PERIODE_ID", "INTERVENANT_ID", "INSEE", "NOM", "CARTE", "CODE_ORIGINE", "RETENUE", "SENS", "MC", "NBU", "MONTANT", "LIBELLE") AS
      SELECT
             annee_id,
             type_intervenant_id,
             structure_id,
             periode_id,
             intervenant_id,

             insee,
             nom,
             '20' carte,
             code_origine,
             '0204' retenue,
             '0' sens,
             'B' mc,
             nbu,
             montant,
             libelle || ' ' || LPAD(TO_CHAR(FLOOR(nbu)),2,'00') || ' H' ||
             CASE to_char(ROUND( nbu-FLOOR(nbu), 2 )*100,'00')
                   WHEN ' 00' THEN '' ELSE ' ' || LPAD(ROUND( nbu-FLOOR(nbu), 2 )*100,2,'00') END libelle
      FROM (
           SELECT
                  i.annee_id                                                                                          annee_id,
                  si.type_intervenant_id                                                                              type_intervenant_id,
                  t2.structure_id                                                                                     structure_id,
                  t2.periode_paiement_id                                                                              periode_id,
                  i.id                                                                                                intervenant_id,

                  '''' || NVL(i.numero_insee,'') || TRIM(NVL(TO_CHAR(i.numero_insee_cle,'00'),''))                    insee,
                  i.nom_usuel || ',' || i.prenom                                                                      nom,
                  t2.code_origine                                                                                     code_origine,
                  CASE WHEN ind <> CEIL(t2.nbu/max_nbu) THEN max_nbu ELSE t2.nbu - max_nbu*(ind-1) END                nbu,
                  t2.nbu                                                                                              tnbu,
                  OSE_FORMULE.GET_TAUX_HORAIRE_HETD( NVL(t2.date_mise_en_paiement,SYSDATE) )                          montant,
                  COALESCE(t2.unite_budgetaire,'') || ' ' || to_char(i.annee_id) || ' ' || to_char(i.annee_id+1)      libelle
           FROM (
                SELECT
                       structure_id,
                       periode_paiement_id,
                       intervenant_id,
                       code_origine,
                       ROUND( SUM(nbu), 2) nbu,
                       unite_budgetaire,
                       date_mise_en_paiement
                FROM (
                     WITH mep AS (
                         SELECT
                             -- pour les filtres
                                mep.id,
                                mis.structure_id,
                                mep.periode_paiement_id,
                                mis.intervenant_id,
                                mep.heures,
                                cc.unite_budgetaire,
                                mep.date_mise_en_paiement
                         FROM
                              v_mep_intervenant_structure  mis
                                    JOIN mise_en_paiement        mep ON mep.id = mis.mise_en_paiement_id AND mep.histo_destruction IS NULL
                                    JOIN centre_cout              cc ON cc.id = mep.centre_cout_id
                                    JOIN type_heures              th ON th.id = mep.type_heures_id
                         WHERE
                             mep.date_mise_en_paiement IS NOT NULL
                           AND mep.periode_paiement_id IS NOT NULL
                           AND th.eligible_extraction_paie = 1
                     )
                     SELECT
                            mep.id,
                            mep.structure_id,
                            mep.periode_paiement_id,
                            mep.intervenant_id,
                            2 code_origine,
                            mep.heures * 4 / 10 nbu,
                            mep.unite_budgetaire,
                            mep.date_mise_en_paiement
                     FROM
                          mep
                     WHERE
                         mep.heures * 4 / 10 > 0

                     UNION

                     SELECT
                            mep.id,
                            mep.structure_id,
                            mep.periode_paiement_id,
                            mep.intervenant_id,
                            1 code_origine,
                            mep.heures * 6 / 10 nbu,
                            mep.unite_budgetaire,
                            mep.date_mise_en_paiement
                     FROM
                          mep
                     WHERE
                         mep.heures * 6 / 10 > 0
                     ) t1
                GROUP BY
                         structure_id,
                         periode_paiement_id,
                         intervenant_id,
                         code_origine,
                         unite_budgetaire,
                         date_mise_en_paiement
                ) t2
                      JOIN (SELECT level ind, 99 max_nbu FROM dual CONNECT BY 1=1 AND LEVEL <= 11) tnbu ON ceil(t2.nbu / max_nbu) >= ind
                      JOIN intervenant i ON i.id = t2.intervenant_id
                      JOIN statut_intervenant si ON si.id = i.statut_id
                      JOIN structure s ON s.id = t2.structure_id
           ) t3
      ORDER BY
               annee_id, type_intervenant_id, structure_id, periode_id, nom, code_origine, nbu DESC

/

CREATE OR REPLACE FORCE VIEW "V_FORMULE_INTERVENANT" ("INTERVENANT_ID", "ANNEE_ID", "STRUCTURE_ID", "TYPE_INTERVENANT_CODE", "HEURES_SERVICE_STATUTAIRE", "DEPASSEMENT_SERVICE_DU_SANS_HC", "HEURES_SERVICE_MODIFIE", "HEURES_DECHARGE") AS
      SELECT
             i.id                                                                 intervenant_id,
             i.annee_id                                                           annee_id,
             CASE WHEN ti.code = 'P' THEN i.structure_id ELSE NULL END           structure_id,
             ti.code                                                              type_intervenant_code,
             si.service_statutaire                                                heures_service_statutaire,
             si.depassement_service_du_sans_hc                                    depassement_service_du_sans_hc,
             COALESCE( SUM( msd.heures * mms.multiplicateur ), 0 )                heures_service_modifie,
             COALESCE( SUM( msd.heures * mms.multiplicateur * mms.decharge ), 0 ) heures_decharge
      FROM
           intervenant                  i
                 LEFT JOIN modification_service_du    msd ON msd.intervenant_id = i.id AND msd.histo_destruction IS NULL
                 LEFT JOIN motif_modification_service mms ON mms.id = msd.motif_id
                 JOIN statut_intervenant          si ON si.id = i.statut_id
                 JOIN type_intervenant            ti ON ti.id = si.type_intervenant_id
      WHERE
          i.histo_destruction IS NULL
        AND i.id = COALESCE( OSE_FORMULE.GET_INTERVENANT_ID, i.id )
      GROUP BY
               i.id, i.annee_id, i.structure_id, ti.code, si.service_statutaire, si.depassement_service_du_sans_hc

/

CREATE OR REPLACE FORCE VIEW "V_FORMULE_VOLUME_HORAIRE" ("ID", "VOLUME_HORAIRE_ID", "VOLUME_HORAIRE_REF_ID", "SERVICE_ID", "SERVICE_REFERENTIEL_ID", "INTERVENANT_ID", "TYPE_INTERVENTION_ID", "TYPE_VOLUME_HORAIRE_ID", "ETAT_VOLUME_HORAIRE_ID", "TAUX_FI", "TAUX_FA", "TAUX_FC", "STRUCTURE_ID", "PONDERATION_SERVICE_DU", "PONDERATION_SERVICE_COMPL", "SERVICE_STATUTAIRE", "HEURES", "HORAIRE_DEBUT", "HORAIRE_FIN", "TAUX_SERVICE_DU", "TAUX_SERVICE_COMPL") AS
      SELECT
             to_number( 1 || vh.id )                                            id,
             vh.id                                                              volume_horaire_id,
             null                                                               volume_horaire_ref_id,
             s.id                                                               service_id,
             null                                                               service_referentiel_id,
             s.intervenant_id                                                   intervenant_id,
             ti.id                                                              type_intervention_id,
             vh.type_volume_horaire_id                                          type_volume_horaire_id,
             vhe.etat_volume_horaire_id                                         etat_volume_horaire_id,

             CASE WHEN ep.id IS NOT NULL THEN ep.taux_fi ELSE 1 END             taux_fi,
             CASE WHEN ep.id IS NOT NULL THEN ep.taux_fa ELSE 0 END             taux_fa,
             CASE WHEN ep.id IS NOT NULL THEN ep.taux_fc ELSE 0 END             taux_fc,
             ep.structure_id                                                    structure_id,
             MAX(COALESCE( m.ponderation_service_du, 1))                        ponderation_service_du,
             MAX(COALESCE( m.ponderation_service_compl, 1))                     ponderation_service_compl,
             COALESCE(tf.service_statutaire,1)                                  service_statutaire,

             vh.heures                                                          heures,
             vh.horaire_debut                                                   horaire_debut,
             vh.horaire_fin                                                     horaire_fin,
             COALESCE(ti.taux_hetd_service,1)                                   taux_service_du,
             COALESCE(ti.taux_hetd_complementaire,1)                            taux_service_compl
      FROM
           volume_horaire            vh
                 JOIN service                    s ON s.id = vh.service_id
                 JOIN intervenant                i ON i.id = s.intervenant_id
                 JOIN type_intervention         ti ON ti.id = vh.type_intervention_id
                 JOIN v_volume_horaire_etat    vhe ON vhe.volume_horaire_id = vh.id

                 LEFT JOIN element_pedagogique       ep ON ep.id = s.element_pedagogique_id
                 LEFT JOIN etape                      e ON e.id = ep.etape_id
                 LEFT JOIN type_formation            tf ON tf.id = e.type_formation_id
                 LEFT JOIN element_modulateur        em ON em.element_id = s.element_pedagogique_id
                                                                 AND em.histo_destruction IS NULL
                 LEFT JOIN modulateur                 m ON m.id = em.modulateur_id
      WHERE
          vh.histo_destruction IS NULL
        AND s.histo_destruction IS NULL
        AND vh.heures <> 0
        AND vh.motif_non_paiement_id IS NULL
        AND s.intervenant_id = COALESCE( OSE_FORMULE.GET_INTERVENANT_ID, s.intervenant_id )
      GROUP BY
               vh.id, s.id, s.intervenant_id, ti.id, vh.type_volume_horaire_id, vhe.etat_volume_horaire_id, ep.id,
               ep.taux_fi, ep.taux_fa, ep.taux_fc, ep.structure_id, tf.service_statutaire, vh.heures,
               vh.horaire_debut, vh.horaire_fin, ti.taux_hetd_service, ti.taux_hetd_complementaire

      UNION ALL

      SELECT
             to_number( 2 || vhr.id )          id,
             null                              volume_horaire_id,
             vhr.id                            volume_horaire_ref_id,
             null                              service_id,
             sr.id                             service_referentiel_id,
             sr.intervenant_id                 intervenant_id,
             null                              type_intervention_id,
             vhr.type_volume_horaire_id        type_volume_horaire_id,
             evh.id                            etat_volume_horaire_id,

             0                                 taux_fi,
             0                                 taux_fa,
             0                                 taux_fc,
             sr.structure_id                   structure_id,
             1                                 ponderation_service_du,
             1                                 ponderation_service_compl,
             COALESCE(fr.service_statutaire,1) service_statutaire,

             vhr.heures                        heures,
             vhr.horaire_debut                 horaire_debut,
             vhr.horaire_fin                   horaire_fin,
             1                                 taux_service_du,
             1                                 taux_service_compl
      FROM
           volume_horaire_ref               vhr
                 JOIN service_referentiel          sr ON sr.id = vhr.service_referentiel_id
                 JOIN v_volume_horaire_ref_etat  vher ON vher.volume_horaire_ref_id = vhr.id
                 JOIN etat_volume_horaire         evh ON evh.id = vher.etat_volume_horaire_id
                 JOIN fonction_referentiel         fr ON fr.id = sr.fonction_id
      WHERE
          vhr.histo_destruction IS NULL
        AND sr.histo_destruction IS NULL
        AND vhr.heures <> 0
        AND sr.intervenant_id = COALESCE( OSE_FORMULE.GET_INTERVENANT_ID, sr.intervenant_id )

      ORDER BY
            horaire_fin, horaire_debut, volume_horaire_id, volume_horaire_ref_id

/

CREATE OR REPLACE FORCE VIEW "V_INDICATEUR_560" ("ID", "ANNEE_ID", "INTERVENANT_ID", "STRUCTURE_ID", "PLAFOND", "HEURES") AS
      SELECT
             rownum                              id,
             i.annee_id                          annee_id,
             i.id                                intervenant_id,
             i.structure_id                      structure_id,
             si.maximum_hetd                     plafond,
             fr.total                            heures
      FROM
           intervenant                     i
                 JOIN etat_volume_horaire      evh ON evh.code = 'saisi'
                 JOIN formule_resultat          fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id
                 JOIN statut_intervenant        si ON si.id = i.statut_id
                 JOIN type_volume_horaire      tvh ON tvh.id = fr.type_volume_horaire_id AND tvh.code= 'PREVU'
      WHERE
          fr.total - fr.heures_compl_fc_majorees > si.maximum_hetd

/

CREATE OR REPLACE FORCE VIEW "V_INDICATEUR_570" ("ID", "ANNEE_ID", "INTERVENANT_ID", "STRUCTURE_ID", "PLAFOND", "HEURES") AS
      SELECT
             rownum                              id,
             i.annee_id                          annee_id,
             i.id                                intervenant_id,
             i.structure_id                      structure_id,
             si.maximum_hetd                     plafond,
             fr.total                            heures
      FROM
           intervenant                     i
                 JOIN etat_volume_horaire      evh ON evh.code = 'saisi'
                 JOIN formule_resultat          fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id
                 JOIN statut_intervenant        si ON si.id = i.statut_id
                 JOIN type_volume_horaire      tvh ON tvh.id = fr.type_volume_horaire_id AND tvh.code= 'REALISE'
      WHERE
          fr.total - fr.heures_compl_fc_majorees > si.maximum_hetd

/




-- DdlTrigger.alter.

CREATE OR REPLACE TRIGGER "INDIC_TRG_MODIF_DOSSIER"
      AFTER INSERT OR UPDATE OF NOM_USUEL, NOM_PATRONYMIQUE, PRENOM, CIVILITE_ID, ADRESSE, RIB, DATE_NAISSANCE ON "DOSSIER"

      FOR EACH ROW
      /**
       * But : mettre à jour la liste des PJ attendues.
       */
      DECLARE
            i integer := 1;
            intervenantId NUMERIC;
            found integer;
            estCreationDossier integer;
            type array_t is table of varchar2(1024);

            attrNames     array_t := array_t();
            attrOldVals   array_t := array_t();
            attrNewVals   array_t := array_t();

            -- valeurs importées (format texte) :
            impSourceName source.libelle%type;
            impNomUsuel   indic_modif_dossier.ATTR_NEW_VALUE%type;
            impNomPatro   indic_modif_dossier.ATTR_NEW_VALUE%type;
            impPrenom     indic_modif_dossier.ATTR_NEW_VALUE%type;
            impCivilite   indic_modif_dossier.ATTR_NEW_VALUE%type;
            impDateNaiss  indic_modif_dossier.ATTR_NEW_VALUE%type;
            impAdresse    indic_modif_dossier.ATTR_NEW_VALUE%type;
            impRib        indic_modif_dossier.ATTR_NEW_VALUE%type;
            -- anciennes valeurs dans le dossier (format texte) :
            oldSourceName source.libelle%type;
            oldNomUsuel   indic_modif_dossier.ATTR_NEW_VALUE%type;
            oldNomPatro   indic_modif_dossier.ATTR_NEW_VALUE%type;
            oldPrenom     indic_modif_dossier.ATTR_NEW_VALUE%type;
            oldCivilite   indic_modif_dossier.ATTR_NEW_VALUE%type;
            oldDateNaiss  indic_modif_dossier.ATTR_NEW_VALUE%type;
            oldAdresse    indic_modif_dossier.ATTR_NEW_VALUE%type;
            oldRib        indic_modif_dossier.ATTR_NEW_VALUE%type;
            -- nouvelles valeurs dans le dossier (format texte) :
            newSourceName source.libelle%type;
            newNomUsuel   indic_modif_dossier.ATTR_NEW_VALUE%type;
            newNomPatro   indic_modif_dossier.ATTR_NEW_VALUE%type;
            newPrenom     indic_modif_dossier.ATTR_NEW_VALUE%type;
            newCivilite   indic_modif_dossier.ATTR_NEW_VALUE%type;
            newDateNaiss  indic_modif_dossier.ATTR_NEW_VALUE%type;
            newAdresse    indic_modif_dossier.ATTR_NEW_VALUE%type;
            newRib        indic_modif_dossier.ATTR_NEW_VALUE%type;
      BEGIN
            --
            -- Témoin indiquant s'il s'agit d'une création de dossier (insert).
            --
            estCreationDossier := case when inserting then 1 else 0 end;

            --
            -- Fetch source OSE.
            --
            select s.libelle into newSourceName from source s where s.code = 'OSE';

            --
            -- Fetch et formattage texte des valeurs importées.
            --
            select
                   i.id,
                   s.libelle,
                   nvl(i.NOM_USUEL, '(Aucun)'),
                   nvl(i.NOM_PATRONYMIQUE, '(Aucun)'),
                   nvl(i.PRENOM, '(Aucun)'),
                   nvl(c.libelle_court, '(Aucune)'),
                   nvl(to_char(i.DATE_NAISSANCE, 'DD/MM/YYYY'), '(Aucune)'),
                   nvl(ose_divers.formatted_rib(i.bic, i.iban), '(Aucun)'),
                   case when a.id is not null
                             then ose_divers.formatted_adresse(a.NO_VOIE, a.NOM_VOIE, a.BATIMENT, a.MENTION_COMPLEMENTAIRE, a.LOCALITE, a.CODE_POSTAL, a.VILLE, a.PAYS_LIBELLE)
                        else '(Aucune)'
                       end
                into
                      intervenantId,
                      oldSourceName,
                      impNomUsuel,
                      impNomPatro,
                      impPrenom,
                      impCivilite,
                      impDateNaiss,
                      impRib,
                      impAdresse
            from intervenant i
                       join source s on s.id = i.source_id
                       left join civilite c on c.id = i.civilite_id
                       left join adresse_intervenant a on a.intervenant_id = i.id AND a.histo_destruction IS NULL
            where i.id = :NEW.intervenant_id;

            --
            -- Anciennes valeurs dans le cas d'une création de dossier : ce sont les valeurs importées.
            --
            if (1 = estCreationDossier) then
                  --dbms_output.put_line('inserting');
                  oldNomUsuel  := impNomUsuel;
                  oldNomPatro  := impNomPatro;
                  oldPrenom    := impPrenom;
                  oldCivilite  := impCivilite;
                  oldDateNaiss := impDateNaiss;
                  oldAdresse   := impAdresse;
                  oldRib       := impRib;
            --
            -- Anciennes valeurs dans le cas d'une mise à jour du dossier.
            --
            else
                  --dbms_output.put_line('updating');
                  oldNomUsuel     := trim(:OLD.NOM_USUEL);
                  oldNomPatro     := trim(:OLD.NOM_PATRONYMIQUE);
                  oldPrenom       := trim(:OLD.PRENOM);
                  oldDateNaiss    := case when :OLD.DATE_NAISSANCE is null then '(Aucune)' else to_char(:OLD.DATE_NAISSANCE, 'DD/MM/YYYY') end;
                  oldAdresse      := trim(:OLD.ADRESSE);
                  oldRib          := trim(:OLD.RIB);
                  if :OLD.CIVILITE_ID is not null then
                        select c.libelle_court into oldCivilite from civilite c where c.id = :OLD.CIVILITE_ID;
                  else
                        oldCivilite := '(Aucune)';
                  end if;
                  select s.libelle into oldSourceName from source s where s.code = 'OSE';
            end if;

            --
            -- Nouvelles valeurs saisies.
            --
            newNomUsuel   := trim(:NEW.NOM_USUEL);
            newNomPatro   := trim(:NEW.NOM_PATRONYMIQUE);
            newPrenom     := trim(:NEW.PRENOM);
            newDateNaiss  := case when :NEW.DATE_NAISSANCE is null then '(Aucune)' else to_char(:NEW.DATE_NAISSANCE, 'DD/MM/YYYY') end;
            newAdresse    := trim(:NEW.ADRESSE);
            newRib        := trim(:NEW.RIB);
            if :NEW.CIVILITE_ID is not null then
                  select c.libelle_court into newCivilite from civilite c where c.id = :NEW.CIVILITE_ID;
            else
                  newCivilite := '(Aucune)';
            end if;

            --
            -- Détection des différences.
            --
            if newNomUsuel <> oldNomUsuel then
                  --dbms_output.put_line('NOM_USUEL ' || sourceLib || ' = ' || oldNomUsuel || ' --> NOM_USUEL OSE = ' || :NEW.NOM_USUEL);
                  attrNames.extend(1);
                  attrOldVals.extend(1);
                  attrNewVals.extend(1);
                  attrNames(i)   := 'Nom usuel';
                  attrOldVals(i) := oldNomUsuel;
                  attrNewVals(i) := newNomUsuel;
                  i := i + 1;
            end if;
            if newNomPatro <> oldNomPatro then
                  --dbms_output.put_line('NOM_PATRONYMIQUE ' || sourceLib || ' = ' || oldNomPatro || ' --> NOM_PATRONYMIQUE OSE = ' || :NEW.NOM_PATRONYMIQUE);
                  attrNames.extend(1);
                  attrOldVals.extend(1);
                  attrNewVals.extend(1);
                  attrNames(i)   := 'Nom de naissance';
                  attrOldVals(i) := oldNomPatro;
                  attrNewVals(i) := newNomPatro;
                  i := i + 1;
            end if;
            if newPrenom <> oldPrenom then
                  --dbms_output.put_line('PRENOM ' || sourceLib || ' = ' || oldPrenom || ' --> PRENOM OSE = ' || :NEW.PRENOM);
                  attrNames.extend(1);
                  attrOldVals.extend(1);
                  attrNewVals.extend(1);
                  attrNames(i)   := 'Prénom';
                  attrOldVals(i) := oldPrenom;
                  attrNewVals(i) := newPrenom;
                  i := i + 1;
            end if;
            if newCivilite <> oldCivilite then
                  --dbms_output.put_line('CIVILITE_ID ' || sourceLib || ' = ' || oldCivilite || ' --> CIVILITE_ID OSE = ' || :NEW.CIVILITE_ID);
                  attrNames.extend(1);
                  attrOldVals.extend(1);
                  attrNewVals.extend(1);
                  attrNames(i)   := 'Civilité';
                  attrOldVals(i) := oldCivilite;
                  attrNewVals(i) := newCivilite;
                  i := i + 1;
            end if;
            if newDateNaiss <> oldDateNaiss then
                  --dbms_output.put_line('DATE_NAISSANCE ' || sourceLib || ' = ' || oldDateNaiss || ' --> DATE_NAISSANCE OSE = ' || :NEW.DATE_NAISSANCE);
                  attrNames.extend(1);
                  attrOldVals.extend(1);
                  attrNewVals.extend(1);
                  attrNames(i)   := 'Date de naissance';
                  attrOldVals(i) := oldDateNaiss;
                  attrNewVals(i) := newDateNaiss;
                  i := i + 1;
            end if;
            if newAdresse <> oldAdresse then
                  --dbms_output.put_line('ADRESSE ' || sourceLib || ' = ' || oldAdresse || ' --> ADRESSE OSE = ' || :NEW.ADRESSE);
                  attrNames.extend(1);
                  attrOldVals.extend(1);
                  attrNewVals.extend(1);
                  attrNames(i)   := 'Adresse postale';
                  attrOldVals(i) := oldAdresse;
                  attrNewVals(i) := newAdresse;
                  i := i + 1;
            end if;
            if oldRib is null or newRib <> oldRib then
                  --dbms_output.put_line('RIB ' || sourceLib || ' = ' || oldRib || ' --> RIB OSE = ' || :NEW.RIB);
                  attrNames.extend(1);
                  attrOldVals.extend(1);
                  attrNewVals.extend(1);
                  attrNames(i)   := 'RIB';
                  attrOldVals(i) := oldRib;
                  attrNewVals(i) := newRib;
                  i := i + 1;
            end if;

            --
            -- Enregistrement des différences.
            --
            for i in 1 .. attrNames.count loop
                  --dbms_output.put_line(attrNames(i) || ' ' || oldSourceName || ' = ' || attrOldVals(i) || ' --> ' || attrNames(i) || ' ' || newSourceName || ' = ' || attrNewVals(i));

                  -- vérification que la même modif n'est pas déjà consignée
                  select count(*) into found from indic_modif_dossier
                  where INTERVENANT_ID = intervenantId
                    and ATTR_NAME = attrNames(i)
                    and ATTR_OLD_VALUE = to_char(attrOldVals(i))
                    and ATTR_NEW_VALUE = to_char(attrNewVals(i));
                  if found > 0 then
                        continue;
                  end if;

                  insert into INDIC_MODIF_DOSSIER(
                      id,
                      INTERVENANT_ID,
                      ATTR_NAME,
                      ATTR_OLD_SOURCE_NAME,
                      ATTR_OLD_VALUE,
                      ATTR_NEW_SOURCE_NAME,
                      ATTR_NEW_VALUE,
                      EST_CREATION_DOSSIER, -- témoin indiquant s'il s'agit d'une création ou d'une modification de dossier
                      HISTO_CREATION,       -- NB: date de modification du dossier
                      HISTO_CREATEUR_ID,    -- NB: auteur de la modification du dossier
                      HISTO_MODIFICATION,
                      HISTO_MODIFICATEUR_ID
                      )
                  values (
                             indic_modif_dossier_id_seq.nextval,
                             intervenantId,
                             attrNames(i),
                             oldSourceName,
                             to_char(attrOldVals(i)),
                             newSourceName,
                             to_char(attrNewVals(i)),
                             estCreationDossier,
                             :NEW.HISTO_MODIFICATION,
                             :NEW.HISTO_MODIFICATEUR_ID,
                             :NEW.HISTO_MODIFICATION,
                             :NEW.HISTO_MODIFICATEUR_ID
                             );
            end loop;

      END;

/















INSERT INTO CATEGORIE_PRIVILEGE (ID, CODE, LIBELLE)
VALUES (CATEGORIE_PRIVILEGE_ID_SEQ.nextval, 'centres-couts', 'Paramétrage des centres de coûts');

INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE)
SELECT privilege_id_seq.nextval                               id,
       (SELECT id FROM CATEGORIE_PRIVILEGE WHERE code = t1.c) CATEGORIE_ID,
       t1.p                                                   CODE,
       t1.l                                                   LIBELLE,
       (SELECT count(*) FROM PRIVILEGE WHERE categorie_id = (SELECT id FROM CATEGORIE_PRIVILEGE WHERE code = t1.c)) +
       rownum                                                 ORDRE
FROM (SELECT 'centres-couts' c, 'administration-visualisation' p, 'Administration (visualisation)' l FROM dual
      UNION ALL SELECT 'centres-couts' c, 'administration-edition' p, 'Administration (édition)' l FROM dual) t1;


INSERT INTO CATEGORIE_PRIVILEGE (ID, CODE, LIBELLE)
VALUES (CATEGORIE_PRIVILEGE_ID_SEQ.nextval, 'etat-sortie', 'États de sortie');

INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE)
SELECT privilege_id_seq.nextval                               id,
       (SELECT id FROM CATEGORIE_PRIVILEGE WHERE code = t1.c) CATEGORIE_ID,
       t1.p                                                   CODE,
       t1.l                                                   LIBELLE,
       (SELECT count(*) FROM PRIVILEGE WHERE categorie_id = (SELECT id FROM CATEGORIE_PRIVILEGE WHERE code = t1.c)) +
       rownum                                                 ORDRE
FROM (SELECT 'etat-sortie' c, 'administration-visualisation' p, 'Administration (visualisation)' l FROM dual
      UNION ALL SELECT 'etat-sortie' c, 'administration-edition' p, 'Administration (édition)' l FROM dual) t1;


INSERT INTO parametre (
    id,
    nom,
    valeur,
    description,
    histo_creation,
    histo_createur_id,
    histo_modification,
    histo_modificateur_id
    ) VALUES (
                 parametre_id_seq.nextval,
                 'structure_univ',
                 null,
                 'Composante représentant l''université (utile éventuellement pour la forpule de calcul)',
                 sysdate,
                 (select id from utilisateur where username='oseappli'),
                 sysdate,
                 (select id from utilisateur where username='oseappli')
                 );


UPDATE tbl SET feuille_de_route = 1 WHERE tbl_name IN (
    'agrement',
    'cloture_realise',
    'contrat',
    'dossier',
    'paiement',
    'piece_jointe',
    'piece_jointe_demande',
    'piece_jointe_fournie',
    'service',
    'service_referentiel',
    'service_saisie',
    'validation_referentiel',
    'workflow',
    'validation_enseignement',
    'formule'
    );

INSERT INTO parametre (
    id,
    nom,
    valeur,
    description,
    histo_creation,
    histo_createur_id,
    histo_modification,
    histo_modificateur_id
    ) VALUES (
                 parametre_id_seq.nextval,
                 'es_winpaie',
                 (select id from etat_sortie where code = 'winpaie'),
                 'État de sortie pour l''extraction Winpaie',
                 sysdate,
                 (select id from utilisateur where username='oseappli'),
                 sysdate,
                 (select id from utilisateur where username='oseappli')
                 );

INSERT INTO parametre (
    id,
    nom,
    valeur,
    description,
    histo_creation,
    histo_createur_id,
    histo_modification,
    histo_modificateur_id
    ) VALUES (
                 parametre_id_seq.nextval,
                 'es_services_pdf',
                 (select id from etat_sortie where code = 'services'),
                 'État de sortie pour l''édition PDF des services',
                 sysdate,
                 (select id from utilisateur where username='oseappli'),
                 sysdate,
                 (select id from utilisateur where username='oseappli')
                 );

INSERT INTO parametre (
    id,
    nom,
    valeur,
    description,
    histo_creation,
    histo_createur_id,
    histo_modification,
    histo_modificateur_id
    ) VALUES (
                 parametre_id_seq.nextval,
                 'es_etat_paiement',
                 (select id from etat_sortie where code = 'etat-paiement'),
                 'État de sortie pour les états de paiement',
                 sysdate,
                 (select id from utilisateur where username='oseappli'),
                 sysdate,
                 (select id from utilisateur where username='oseappli')
                 );


delete from parametre where nom in (
    'winpaie_carte',
    'winpaie_mc',
    'winpaie_retenue',
    'winpaie_sens'
    );
