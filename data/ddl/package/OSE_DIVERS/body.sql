CREATE OR REPLACE PACKAGE BODY "OSE_DIVERS" AS
  OSE_UTILISATEUR_ID NUMERIC;
  OSE_SOURCE_ID NUMERIC;

  CPA_S1_ID NUMERIC;


  FUNCTION CALC_POURC_AA( periode_id NUMERIC, horaire_debut DATE, horaire_fin DATE, annee_id NUMERIC ) RETURN FLOAT IS
    regle_paiement_annee_civ VARCHAR2(50);
    nbjaa NUMERIC;
    nbjac NUMERIC;
  BEGIN
    regle_paiement_annee_civ := ose_parametre.get_regle_paiement_annee_civ;

    IF regle_paiement_annee_civ = '4-6sur10' THEN
      RETURN 4/10;
    END IF;

    -- Sinon on calcule en fonction du nombre du semestre
    IF horaire_debut IS NULL AND horaire_fin IS NULL AND periode_id IS NOT NULL THEN
      IF CPA_S1_ID IS NULL THEN
        SELECT id INTO CPA_S1_ID FROM periode WHERE code = 'S1';
      END IF;

      IF periode_id = CPA_S1_ID THEN
        RETURN ose_parametre.get_pourc_s1_annee_civ;
      ELSE
        RETURN 0;
      END IF;
    END IF;

    -- S'il y a des dates, alors on s'appuie dessus
    IF horaire_debut IS NOT NULL AND horaire_fin IS NULL THEN
      IF to_number(to_char(horaire_debut,'YYYY')) = annee_id THEN
        RETURN 1;
      ELSE
        RETURN 0;
      END IF;
    END IF;

    IF horaire_fin IS NOT NULL AND horaire_debut IS NULL THEN
      IF to_number(to_char(horaire_fin,'YYYY')) = annee_id THEN
        RETURN 1;
      ELSE
        RETURN 0;
      END IF;
    END IF;

    IF horaire_fin IS NOT NULL AND horaire_debut IS NOT NULL THEN
      IF to_number(to_char(horaire_debut,'YYYY')) = to_number(to_char(horaire_fin,'YYYY')) THEN -- si c'est la même année
        IF to_number(to_char(horaire_debut,'YYYY')) = annee_id THEN
          RETURN 1;
        ELSE
          RETURN 0;
        END IF;
      ELSE
        nbjaa := to_date('01/01/' || (annee_id+1), 'dd/mm/YYYY') - horaire_debut;
        IF nbjaa < 1 THEN
          RETURN 0;
        END IF;

        nbjac := horaire_fin - to_date('31/12/' || annee_id, 'dd/mm/YYYY');
        IF nbjac < 1 THEN
          RETURN 1;
        END IF;

        RETURN nbjaa / (nbjaa + nbjac);
      END IF;
    END IF;

    IF periode_id IS NULL THEN
      -- on se trouve dans du référentiel ou dans un enseignement annuel, on utilise le ratio configuré
      RETURN ose_parametre.get_pourc_s1_annee_civ;
    ELSE
      -- Sinon on retourne comme avant, CAD 4/10
      RETURN 4/10;
    END IF;
  END;



  FUNCTION CALC_HEURES_AA(heures FLOAT, pourc_exercice_aa FLOAT, total_heures FLOAT, cumul_heures FLOAT) RETURN FLOAT IS
  BEGIN
    IF cumul_heures <= total_heures * pourc_exercice_aa THEN
      RETURN heures;
    END IF;

    IF total_heures * pourc_exercice_aa - cumul_heures + heures > 0 THEN
      RETURN total_heures * pourc_exercice_aa - cumul_heures + heures;
    END IF;

    RETURN 0;
  END;



  PROCEDURE CALCULER_TABLEAUX_BORD IS
  BEGIN
    FOR d IN (
      SELECT tbl_name
      FROM tbl
      WHERE tbl_name <> 'formule' -- TROP LONG !!
      ORDER BY ordre
    )LOOP
      UNICAEN_TBL.CALCULER(d.tbl_name);
      dbms_output.put_line('Calcul du tableau de bord "' || d.tbl_name || '" effectué');
      COMMIT;
    END LOOP;
  END;



  PROCEDURE CALCUL_FEUILLE_DE_ROUTE( INTERVENANT_ID NUMERIC ) IS
  BEGIN
    FOR d IN (
      SELECT   tbl_name
      FROM     tbl
      WHERE    feuille_de_route = 1
      ORDER BY ordre
    ) LOOP
      UNICAEN_TBL.CALCULER(d.tbl_name,'INTERVENANT_ID',intervenant_id);
    END LOOP;
  END;



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



  FUNCTION INTERVENANT_HAS_PRIVILEGE( intervenant_id NUMERIC, privilege_name VARCHAR2 ) RETURN NUMERIC IS
    statut statut%rowtype;
    itype  type_intervenant%rowtype;
    res NUMERIC;
  BEGIN
    res := 1;
    SELECT si.* INTO statut FROM statut si JOIN intervenant i ON i.statut_id = si.id WHERE i.id = intervenant_id;
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

  FUNCTION STR_FIND( haystack VARCHAR2, needle VARCHAR2 ) RETURN NUMERIC IS
  BEGIN
    IF STR_REDUCE( haystack ) LIKE STR_REDUCE( '%' || needle || '%' ) THEN RETURN 1; END IF;
    RETURN 0;
  END;

  FUNCTION GET_VIEW_QUERY( view_name VARCHAR2 ) RETURN CLOB IS
    vlong long;
  BEGIN
    SELECT text into vlong
    FROM   ALL_VIEWS
    WHERE  view_name = GET_VIEW_QUERY.view_name;

    RETURN to_clob(vlong);
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