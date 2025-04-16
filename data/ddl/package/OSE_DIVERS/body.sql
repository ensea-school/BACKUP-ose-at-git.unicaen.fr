CREATE OR REPLACE PACKAGE BODY "OSE_DIVERS" AS
  OSE_UTILISATEUR_ID NUMERIC;
  OSE_SOURCE_ID NUMERIC;



  FUNCTION DATE_TO_PERIODE_CODE( date DATE, annee_id NUMERIC ) RETURN VARCHAR2 IS
    mois NUMERIC;
    annee NUMERIC;
  BEGIN
    mois := to_number(to_char(date, 'mm'));
    annee := to_number(to_char(date, 'YYYY'));

    RETURN CASE
      WHEN annee = annee_id AND mois = 9 THEN 'P01'
      WHEN annee = annee_id AND mois = 10 THEN 'P02'
      WHEN annee = annee_id AND mois = 11 THEN 'P03'
      WHEN annee = annee_id AND mois = 12 THEN 'P04'
      WHEN annee = annee_id + 1 AND mois = 1 THEN 'P05'
      WHEN annee = annee_id + 1 AND mois = 2 THEN 'P06'
      WHEN annee = annee_id + 1 AND mois = 3 THEN 'P07'
      WHEN annee = annee_id + 1 AND mois = 4 THEN 'P08'
      WHEN annee = annee_id + 1 AND mois = 5 THEN 'P09'
      WHEN annee = annee_id + 1 AND mois = 6 THEN 'P10'
      WHEN annee = annee_id + 1 AND mois = 7 THEN 'P11'
      WHEN annee = annee_id + 1 AND mois = 8 THEN 'P12'
      WHEN annee = annee_id + 1 AND mois = 9 THEN 'P13'
      WHEN annee = annee_id + 1 AND mois = 10 THEN 'P14'
      WHEN annee = annee_id + 1 AND mois = 11 THEN 'P15'
      WHEN annee = annee_id + 1 AND mois = 12 THEN 'P16'
      WHEN annee > annee_id + 1 THEN 'PTD'
      ELSE NULL
    END;
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



  PROCEDURE update_structures IS
  BEGIN
    -- mise à jour des listes d'IDs
    FOR str IN (
      SELECT
        id, COALESCE(ids,'-') oids,
        SYS_CONNECT_BY_PATH(id, '-') || '-' nids,
        COALESCE(libelles_courts, '||') olibelles_courts,
        SYS_CONNECT_BY_PATH(libelle_court, '||') || '||' nlibelles_courts
      FROM
        structure
      CONNECT BY
        structure_id = PRIOR id
      START WITH structure_id IS NULL
    ) LOOP
      IF str.oids <> str.nids THEN
        UPDATE structure SET ids = str.nids WHERE id = str.id;
      END IF;
      IF str.olibelles_courts <> str.nlibelles_courts THEN
        UPDATE structure SET libelles_courts = str.nlibelles_courts WHERE id = str.id;
      END IF;
    END LOOP;

    -- mise à 1 du témoin enseignement si des éléments sont dans la structure
    FOR str IN (
      SELECT DISTINCT
        ep.structure_id id
      FROM
        element_pedagogique ep
        JOIN structure str ON str.id = ep.structure_id
      WHERE
        ep.histo_destruction IS NULL
        AND str.enseignement = 0
    ) LOOP
      UPDATE structure SET enseignement = 1 WHERE id = str.id;
    END LOOP;

    -- On retire les CC par défaut qui ne sont plus valables (historisé) ou si la structure n'est pas raccorchée au centre de coûts
    FOR str IN (
      SELECT
        str.id
      FROM
        structure str
        LEFT JOIN centre_cout_structure ccs ON str.centre_cout_id = ccs.centre_cout_id AND ccs.structure_id = str.id AND ccs.histo_destruction IS NULL
        LEFT JOIN centre_cout cc ON cc.id = ccs.centre_cout_id AND cc.histo_destruction IS NULL
      WHERE
        str.centre_cout_id IS NOT NULL
        AND cc.id IS NULL
    ) LOOP
        UPDATE structure SET centre_cout_id = NULL WHERE id = str.id;
    END LOOP;

    -- mise à jour du centre de coûts par défaut si la structure n'a qu'un seul CC possible
    FOR ccstr IN (
      SELECT
        ccs.structure_id                              structure_id,
        cc.id                                         centre_cout_id,
        COUNT(*) OVER (partition by ccs.structure_id) nbr
      FROM
        centre_cout_structure ccs
        JOIN centre_cout cc ON cc.id = ccs.centre_cout_id AND cc.histo_destruction IS NULL
      WHERE
        ccs.histo_destruction IS NULL
    ) LOOP
      IF ccstr.nbr = 1 THEN
        UPDATE structure SET centre_cout_id = ccstr.centre_cout_id WHERE id = ccstr.structure_id AND centre_cout_id IS NULL;
      END IF;
    END LOOP;

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

  FUNCTION STR_REDUCE( str VARCHAR2 ) RETURN VARCHAR2 IS
  BEGIN
    RETURN RTRIM(utl_raw.cast_to_varchar2((nlssort(str, 'nls_sort=binary_ai'))),CHR(0));
  END;


  FUNCTION FORMAT_FLOAT( n FLOAT ) RETURN VARCHAR2 IS
  BEGIN
    RETURN CASE WHEN n < 1 AND n >= 0 THEN '0' ELSE '' END || REPLACE(ltrim(to_char(n, '999999999999999999.00')), '.', ',');
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



  FUNCTION FORMATTED_ADRESSE(precisions VARCHAR2, lieu_dit VARCHAR2, numero VARCHAR2, numero_compl_id NUMERIC, voirie_id NUMERIC, voie VARCHAR2, code_postal VARCHAR2, commune VARCHAR2, pays_id VARCHAR2 ) RETURN VARCHAR2 IS
    a VARCHAR2(4000) DEFAULT '';
    numeroCompl VARCHAR2(5);
    voirie VARCHAR2(120);
    pays varchar2(120);
    l1 varchar2(1000) DEFAULT '';
    l2 varchar2(1000) DEFAULT '';
  BEGIN
    IF numero_compl_id IS NOT NULL THEN
      SELECT code INTO numeroCompl FROM adresse_numero_compl WHERE id = numero_compl_id;
    END IF;
    IF voirie_id IS NOT NULL THEN
      SELECT libelle INTO voirie FROM voirie WHERE id = voirie_id;
    END IF;
    IF pays_id IS NOT NULL THEN
      SELECT libelle INTO pays FROM pays WHERE id = pays_id;
      IF STR_REDUCE(pays) = 'france' THEN
        pays := null;
      END IF;
    END IF;


    IF precisions IS NOT NULL THEN
      a := a || trim(precisions);
    END IF;

    IF lieu_dit IS NOT NULL THEN
      IF a IS NOT NULL THEN a := a || chr(13) || chr(10); END IF;
      a := a || trim(lieu_dit);
    END IF;

    IF numero IS NOT NULL THEN
      l1 := trim(numero);
    END IF;
    IF numeroCompl IS NOT NULL THEN
      IF l1 IS NOT NULL THEN l1 := l1 || ' '; END IF;
      l1 := l1 || trim(numeroCompl);
    END IF;
    IF voirie IS NOT NULL THEN
      IF l1 IS NOT NULL THEN l1 := l1 || ' '; END IF;
      l1 := l1 || trim(voirie);
    END IF;
    IF voie IS NOT NULL THEN
      IF l1 IS NOT NULL THEN l1 := l1 || ' '; END IF;
      l1 := l1 || trim(voie);
    END IF;
    IF l1 IS NOT NULL THEN
      IF a IS NOT NULL THEN a := a || chr(13) || chr(10); END IF;
      a := a || l1;
    END IF;

    IF code_postal IS NOT NULL THEN
      l2 := trim(code_postal);
    END IF;
    IF commune IS NOT NULL THEN
      IF l2 IS NOT NULL THEN l2 := l2 || ' '; END IF;
      l2 := l2 || trim(commune);
    END IF;
    IF l2 IS NOT NULL THEN
      IF a IS NOT NULL THEN a := a || chr(13) || chr(10); END IF;
      a := a || l2;
    END IF;

    IF pays IS NOT NULL THEN
      IF a IS NOT NULL THEN a := a || chr(13) || chr(10); END IF;
      a := a || trim(pays);
    END IF;

    RETURN a;
  END;

END OSE_DIVERS;