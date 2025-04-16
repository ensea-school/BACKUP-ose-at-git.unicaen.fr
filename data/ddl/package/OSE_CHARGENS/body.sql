CREATE OR REPLACE PACKAGE BODY "OSE_CHARGENS" AS

  PROCEDURE MAJ_CACHE IS
  BEGIN
    UNICAEN_IMPORT.REFRESH_MV('MV_LIEN');
    UNICAEN_TBL.CALCULER('chargens');
  END;


  FUNCTION CALC_COEF( choix_min NUMERIC, choix_max NUMERIC, poids NUMERIC, max_poids NUMERIC, total_poids NUMERIC, nb_choix NUMERIC ) RETURN FLOAT IS
    cmin NUMERIC;
    cmax NUMERIC;
    coef_choix FLOAT;
    coef_poids FLOAT;
    max_coef_poids FLOAT;
    correcteur FLOAT DEFAULT 1;
    res FLOAT;
  BEGIN
    cmin := choix_min;
    cmax := choix_max;

    IF total_poids = 0 THEN RETURN 0; END IF;

    IF cmax IS NULL OR cmax > nb_choix THEN
      cmax := nb_choix;
    END IF;
    IF cmin IS NULL THEN
      cmin := nb_choix;
    ELSIF cmin > cmax THEN
      cmin := cmax;
    END IF;

      coef_choix := (cmin + cmax) / 2 / nb_choix;

      coef_poids := poids / total_poids;

      max_coef_poids := max_poids / total_poids;

      IF (coef_choix * nb_choix * max_coef_poids) <= 1 THEN
        res := coef_choix * nb_choix * coef_poids;
      ELSE
        correcteur := 1;
        res := coef_choix * nb_choix * (coef_poids + (((1/nb_choix)-coef_poids)*correcteur));
      END IF;

      RETURN res;
  END;



  PROCEDURE CALC_EFFECTIF(
    noeud_id       NUMERIC,
    scenario_id    NUMERIC,
    type_heures_id NUMERIC,
    etape_id       NUMERIC
  ) IS
    snid     NUMERIC;
    effectif FLOAT;
  BEGIN
    UPDATE scenario_noeud_effectif SET effectif = 0, SOURCE_ID = ose_divers.GET_OSE_SOURCE_ID()
    WHERE
      scenario_noeud_id = (
        SELECT id FROM scenario_noeud WHERE noeud_id = CALC_EFFECTIF.noeud_id AND scenario_id = CALC_EFFECTIF.scenario_id
      )
      AND type_heures_id = CALC_EFFECTIF.type_heures_id
      AND etape_id = CALC_EFFECTIF.etape_id
    ;

    FOR p IN (
      SELECT * FROM (
        SELECT
          l.noeud_inf_id              noeud_id,
          snsup.scenario_id           scenario_id,
          sninf.id                    scenario_noeud_id,
          sne.type_heures_id          type_heures_id,
          sne.etape_id                etape_id,
          sne.effectif                effectif,
          slsup.choix_minimum         choix_minimum,
          slsup.choix_maximum         choix_maximum,
          COALESCE(slinf.poids,1)     poids,
          MAX(COALESCE(slinf.poids,1)) OVER (PARTITION BY l.noeud_liste_id, snsup.scenario_id, sne.type_heures_id, sne.etape_id) max_poids,
          SUM(COALESCE(slinf.poids,1)) OVER (PARTITION BY l.noeud_liste_id, snsup.scenario_id, sne.type_heures_id, sne.etape_id) total_poids,
          COUNT(*)                     OVER (PARTITION BY l.noeud_liste_id, snsup.scenario_id, sne.type_heures_id, sne.etape_id) nb_choix
        FROM
                    mv_lien                lrem
               JOIN mv_lien                   l ON l.noeud_sup_id = lrem.noeud_sup_id

               JOIN scenario_noeud        snsup ON snsup.noeud_id = l.noeud_sup_id
                                               AND snsup.histo_destruction IS NULL

               JOIN scenario_noeud_effectif sne ON sne.scenario_noeud_id = snsup.id

          LEFT JOIN scenario_lien         slsup ON slsup.histo_destruction IS NULL
                                               AND slsup.lien_id = l.lien_sup_id
                                               AND slsup.scenario_id = snsup.scenario_id

          LEFT JOIN scenario_lien         slinf ON slinf.histo_destruction IS NULL
                                               AND slinf.lien_id = l.lien_inf_id
                                               AND slinf.scenario_id = snsup.scenario_id

          LEFT JOIN scenario_noeud        sninf ON sninf.noeud_id = l.noeud_inf_id
                                               AND sninf.scenario_id = snsup.scenario_id
                                               AND sninf.histo_destruction IS NULL
        WHERE
          lrem.noeud_inf_id = CALC_EFFECTIF.noeud_id
          AND (slsup.actif = 1 OR slsup.actif IS NULL)
          AND (slinf.actif = 1 OR slinf.actif IS NULL)
          AND snsup.scenario_id = CALC_EFFECTIF.scenario_id
          AND sne.type_heures_id = CALC_EFFECTIF.type_heures_id
          AND sne.etape_id = CALC_EFFECTIF.etape_id

      ) WHERE noeud_id = CALC_EFFECTIF.noeud_id
    ) LOOP
      effectif := OSE_CHARGENS.CALC_COEF(
          p.choix_minimum,
          p.choix_maximum,
          p.poids,
          p.max_poids,
          p.total_poids,
          p.nb_choix
        ) * p.effectif;

      snid := p.scenario_noeud_id;
      IF snid IS NULL THEN
        snid := OSE_CHARGENS.CREER_SCENARIO_NOEUD( p.scenario_id, p.noeud_id );
      END IF;
      ADD_SCENARIO_NOEUD_EFFECTIF( snid, p.type_heures_id, p.etape_id, effectif );
    END LOOP;
    CALC_SUB_EFFECTIF( noeud_id, scenario_id, type_heures_id, etape_id );
  END;



  PROCEDURE CALC_SUB_EFFECTIF( noeud_id NUMERIC, scenario_id NUMERIC, type_heures_id NUMERIC, etape_id NUMERIC) IS
  BEGIN
    FOR p IN (
      SELECT
        l.noeud_inf_id
      FROM
        mv_lien l
      WHERE
        l.noeud_sup_id = CALC_SUB_EFFECTIF.noeud_id
    ) LOOP
      CALC_EFFECTIF( p.noeud_inf_id, scenario_id, type_heures_id, etape_id );
    END LOOP;
  END;



  PROCEDURE DUPLIQUER( source_id NUMERIC, destination_id NUMERIC, utilisateur_id NUMERIC, structure_id NUMERIC, noeuds VARCHAR2 DEFAULT '', liens VARCHAR2 DEFAULT '' ) IS
  BEGIN
    UNICAEN_TBL.ACTIV_TRIGGERS := FALSE;

    /* Destruction de tous les liens antérieurs de la destination */
    DELETE FROM
      scenario_lien
    WHERE
      scenario_id = DUPLIQUER.destination_id
      AND histo_destruction IS NULL
      AND (DUPLIQUER.LIENS IS NULL OR DUPLIQUER.LIENS LIKE '%,' || lien_id || ',%' )
      AND (DUPLIQUER.STRUCTURE_ID IS NULL OR lien_id IN (
        SELECT id FROM lien WHERE lien.structure_id = DUPLIQUER.STRUCTURE_ID
      ))
    ;
    commit;

    /* Duplication des liens */
    INSERT INTO scenario_lien (
      id,
      scenario_id, lien_id,
      actif, poids,
      choix_minimum, choix_maximum,
      source_id, source_code,
      histo_creation, histo_createur_id,
      histo_modification, histo_modificateur_id
    ) SELECT
      scenario_lien_id_seq.nextval,
      DUPLIQUER.destination_id, sl.lien_id,
      sl.actif, sl.poids,
      sl.choix_minimum, sl.choix_maximum,
      source.id, 'dupli_' || sl.id || '_' || sl.lien_id || '_' || trunc(dbms_random.value(1,10000000000000)),
      sysdate, DUPLIQUER.utilisateur_id,
      sysdate, DUPLIQUER.utilisateur_id
    FROM
      scenario_lien sl
      JOIN lien l ON l.id = sl.lien_id
      JOIN source ON source.code = 'OSE'
    WHERE
      sl.scenario_id = DUPLIQUER.source_id
      AND sl.histo_destruction IS NULL
      AND (DUPLIQUER.LIENS IS NULL OR DUPLIQUER.LIENS LIKE '%,' || lien_id || ',%' )
      AND (DUPLIQUER.STRUCTURE_ID IS NULL OR l.structure_id = DUPLIQUER.STRUCTURE_ID)
    ;
    commit;


    /* Destruction de tous les noeuds antérieurs de la destination */
    DELETE FROM
      scenario_noeud
    WHERE
      scenario_id = DUPLIQUER.destination_id
      AND histo_destruction IS NULL
      AND (DUPLIQUER.NOEUDS IS NULL OR DUPLIQUER.NOEUDS LIKE '%,' || noeud_id || ',%' )
      AND (DUPLIQUER.STRUCTURE_ID IS NULL OR scenario_noeud.noeud_id IN (
        SELECT id FROM noeud WHERE noeud.structure_id = DUPLIQUER.STRUCTURE_ID
      ))
    ;
    commit;

    /* Duplication des noeuds */
    INSERT INTO scenario_noeud (
      id,
      scenario_id, noeud_id,
      assiduite,
      source_id, source_code,
      histo_creation, histo_createur_id,
      histo_modification, histo_modificateur_id
    ) SELECT
      scenario_noeud_id_seq.nextval,
      DUPLIQUER.destination_id, sn.noeud_id,
      sn.assiduite,
      source.id, 'dupli_' || sn.id || '_' || sn.noeud_id || '_' || trunc(dbms_random.value(1,10000000000000)),
      sysdate, DUPLIQUER.utilisateur_id,
      sysdate, DUPLIQUER.utilisateur_id
    FROM
      scenario_noeud sn
      JOIN noeud n ON n.id = sn.noeud_id
      JOIN source ON source.code = 'OSE'
    WHERE
      sn.scenario_id = DUPLIQUER.source_id
      AND sn.histo_destruction IS NULL
      AND (DUPLIQUER.NOEUDS IS NULL OR DUPLIQUER.NOEUDS LIKE '%,' || noeud_id || ',%' )
      AND (DUPLIQUER.STRUCTURE_ID IS NULL OR n.structure_id = DUPLIQUER.STRUCTURE_ID)
    ;
    commit;

    /* Duplication des effectifs */
    INSERT INTO scenario_noeud_effectif (
      id,
      scenario_noeud_id,
      type_heures_id,
      effectif,
      etape_id,
      source_id,
      histo_createur_id,
      histo_modificateur_id
    ) SELECT
      scenario_noeud_effectif_id_seq.nextval,
      sn_dst.id,
      sne.type_heures_id,
      sne.effectif,
      sne.etape_id,
      ose_divers.GET_OSE_SOURCE_ID(),
      ose_divers.GET_OSE_UTILISATEUR_ID(),
      ose_divers.GET_OSE_UTILISATEUR_ID()
    FROM
      scenario_noeud_effectif sne
      JOIN scenario_noeud sn_src ON sn_src.id = sne.scenario_noeud_id
      JOIN scenario_noeud sn_dst ON sn_dst.scenario_id = DUPLIQUER.destination_id AND sn_dst.noeud_id = sn_src.noeud_id
      JOIN noeud n ON n.id = sn_src.noeud_id
    WHERE
      sn_src.scenario_id = DUPLIQUER.source_id
      AND sn_src.histo_destruction IS NULL
      AND (DUPLIQUER.NOEUDS IS NULL OR DUPLIQUER.NOEUDS LIKE '%,' || sn_src.noeud_id || ',%' )
      AND (DUPLIQUER.STRUCTURE_ID IS NULL OR n.structure_id = DUPLIQUER.STRUCTURE_ID)
    ;
    commit;

    /* Duplication des seuils */
    INSERT INTO scenario_noeud_seuil (
      id,
      scenario_noeud_id,
      type_intervention_id,
      ouverture,
      dedoublement
    ) SELECT
      scenario_noeud_seuil_id_seq.nextval,
      sn_dst.id,
      sns.type_intervention_id,
      sns.ouverture,
      sns.dedoublement
    FROM
      scenario_noeud_seuil sns
      JOIN scenario_noeud sn_src ON sn_src.id = sns.scenario_noeud_id
      JOIN scenario_noeud sn_dst ON sn_dst.scenario_id = DUPLIQUER.destination_id AND sn_dst.noeud_id = sn_src.noeud_id
      JOIN noeud n ON n.id = sn_src.noeud_id
    WHERE
      sn_src.scenario_id = DUPLIQUER.source_id
      AND sn_src.histo_destruction IS NULL
      AND (DUPLIQUER.NOEUDS IS NULL OR DUPLIQUER.NOEUDS LIKE '%,' || sn_src.noeud_id || ',%' )
      AND (DUPLIQUER.STRUCTURE_ID IS NULL OR n.structure_id = DUPLIQUER.STRUCTURE_ID)
    ;
    commit;

    UNICAEN_TBL.ACTIV_TRIGGERS := TRUE;

    UNICAEN_TBL.CALCULER( 'chargens', 'SCENARIO_ID', DUPLIQUER.destination_id );
  END;



  FUNCTION CREER_SCENARIO_NOEUD( scenario_id NUMERIC, noeud_id NUMERIC, assiduite FLOAT DEFAULT 1 ) RETURN NUMERIC IS
    new_id NUMERIC;
  BEGIN

    BEGIN
      SELECT id INTO new_id FROM scenario_noeud WHERE noeud_id = CREER_SCENARIO_NOEUD.noeud_id AND scenario_id = CREER_SCENARIO_NOEUD.scenario_id AND histo_destruction IS NULL;

      RETURN new_id;
    EXCEPTION WHEN NO_DATA_FOUND THEN
      new_id := SCENARIO_NOEUD_ID_SEQ.NEXTVAL;
    END;

    INSERT INTO SCENARIO_NOEUD(
      ID,
      SCENARIO_ID,
      NOEUD_ID,
      ASSIDUITE,
      SOURCE_ID,
      SOURCE_CODE,
      HEURES,
      HISTO_CREATION,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATION,
      HISTO_MODIFICATEUR_ID
    ) VALUES (
      new_id,
      CREER_SCENARIO_NOEUD.scenario_id,
      CREER_SCENARIO_NOEUD.noeud_id,
      CREER_SCENARIO_NOEUD.assiduite,
      OSE_DIVERS.GET_OSE_SOURCE_ID,
      'OSE_NEW_SN_' || new_id,
      null,
      SYSDATE,
      OSE_DIVERS.GET_OSE_UTILISATEUR_ID,
      SYSDATE,
      OSE_DIVERS.GET_OSE_UTILISATEUR_ID
    );
    RETURN new_id;
  END;



  PROCEDURE ADD_SCENARIO_NOEUD_EFFECTIF( scenario_noeud_id NUMERIC, type_heures_id NUMERIC, etape_id NUMERIC, effectif FLOAT ) IS
  BEGIN
    MERGE INTO scenario_noeud_effectif sne USING dual ON (

          sne.scenario_noeud_id = ADD_SCENARIO_NOEUD_EFFECTIF.scenario_noeud_id
      AND sne.type_heures_id = ADD_SCENARIO_NOEUD_EFFECTIF.type_heures_id
      AND sne.etape_id = ADD_SCENARIO_NOEUD_EFFECTIF.etape_id

    ) WHEN MATCHED THEN UPDATE SET

      effectif = effectif + ADD_SCENARIO_NOEUD_EFFECTIF.effectif,
      source_id = ose_divers.GET_OSE_SOURCE_ID()

    WHEN NOT MATCHED THEN INSERT (

      ID,
      SCENARIO_NOEUD_ID,
      TYPE_HEURES_ID,
      ETAPE_ID,
      EFFECTIF,
      SOURCE_ID,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID

    ) VALUES (

      SCENARIO_NOEUD_EFFECTIF_ID_SEQ.NEXTVAL,
      ADD_SCENARIO_NOEUD_EFFECTIF.scenario_noeud_id,
      ADD_SCENARIO_NOEUD_EFFECTIF.type_heures_id,
      ADD_SCENARIO_NOEUD_EFFECTIF.etape_id,
      ADD_SCENARIO_NOEUD_EFFECTIF.effectif,
      ose_divers.GET_OSE_SOURCE_ID(),
      ose_divers.GET_OSE_UTILISATEUR_ID(),
      ose_divers.GET_OSE_UTILISATEUR_ID()

    );
  END;

END OSE_CHARGENS;