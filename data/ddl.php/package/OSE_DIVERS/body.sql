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

  FUNCTION STR_REDUCE( str VARCHAR2 ) RETURN VARCHAR2 IS
  BEGIN
    RETURN RTRIM(utl_raw.cast_to_varchar2((nlssort(str, 'nls_sort=binary_ai'))),CHR(0));
  END;

  FUNCTION STR_FIND( haystack VARCHAR2, needle VARCHAR2 ) RETURN NUMERIC IS
  BEGIN
    IF STR_REDUCE( haystack ) LIKE STR_REDUCE( '%' || needle || '%' ) THEN RETURN 1; END IF;
    RETURN 0;
  END;

  FUNCTION LIKED( haystack VARCHAR2, needle VARCHAR2 ) RETURN NUMERIC IS
  BEGIN
    RETURN CASE WHEN STR_REDUCE(haystack) LIKE STR_REDUCE(needle) THEN 1 ELSE 0 END;
  END;

  PROCEDURE DO_NOTHING IS
  BEGIN
    RETURN;
  END;

  PROCEDURE CALCUL_TAUX( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, r_fi OUT FLOAT, r_fc OUT FLOAT, r_fa OUT FLOAT, arrondi NUMERIC DEFAULT 4 ) IS
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


  FUNCTION CALCUL_TAUX_FI( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 4 ) RETURN FLOAT IS
    ri FLOAT;
    rc FLOAT;
    ra FLOAT;
  BEGIN
    CALCUL_TAUX( eff_fi, eff_fc, eff_fa, fi, fc, fa, ri, rc, ra, arrondi );
    RETURN ri;
  END;

  FUNCTION CALCUL_TAUX_FC( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 4 ) RETURN FLOAT IS
    ri FLOAT;
    rc FLOAT;
    ra FLOAT;
  BEGIN
    CALCUL_TAUX( eff_fi, eff_fc, eff_fa, fi, fc, fa, ri, rc, ra, arrondi );
    RETURN rc;
  END;

  FUNCTION CALCUL_TAUX_FA( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 4 ) RETURN FLOAT IS
    ri FLOAT;
    rc FLOAT;
    ra FLOAT;
  BEGIN
    CALCUL_TAUX( eff_fi, eff_fc, eff_fa, fi, fc, fa, ri, rc, ra, arrondi );
    RETURN ra;
  END;

  PROCEDURE SYNC_LOG( msg VARCHAR2 ) IS
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



  PROCEDURE CALCUL_FEUILLE_DE_ROUTE( CONDS VARCHAR2 ) IS
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