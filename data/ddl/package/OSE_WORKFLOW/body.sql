CREATE OR REPLACE PACKAGE BODY OSE_WORKFLOW AS
  TYPE t_dep_bloquante IS RECORD (
    id NUMERIC,
    to_delete BOOLEAN DEFAULT TRUE
  );
  TYPE t_deps_bloquantes IS TABLE OF t_dep_bloquante INDEX BY PLS_INTEGER;
  TYPE t_workflow_etape IS RECORD ( -- une étape d'un workflow
      id NUMERIC,
      to_delete BOOLEAN,
      to_update BOOLEAN,
      etape_id NUMERIC,
      structure_id NUMERIC,
      old_atteignable NUMERIC(1),
      atteignable NUMERIC(1),
      old_objectif FLOAT,
      objectif FLOAT,
      old_realisation FLOAT,
      realisation FLOAT,
      deps_bloquantes t_deps_bloquantes
  );
  TYPE t_workflow IS TABLE OF t_workflow_etape INDEX BY VARCHAR2(20); -- une feuille de route
  TYPE t_intervenant IS RECORD (
    annee_id NUMERIC,
    intervenant_id NUMERIC,
    statut_id NUMERIC,
    type_intervenant_id NUMERIC,
    type_intervenant_code VARCHAR2(1),
    feuille_de_route t_workflow
  );
  TYPE t_intervenants IS TABLE OF t_intervenant INDEX BY PLS_INTEGER;      -- feuilles de routes de tous les intervenants concernés
  TYPE t_etapes IS TABLE OF wf_etape%rowtype INDEX BY PLS_INTEGER;

  TYPE t_dep IS TABLE OF wf_etape_dep%rowtype INDEX BY PLS_INTEGER;
  TYPE t_deps IS TABLE OF t_dep INDEX BY PLS_INTEGER;


  -- propre au calcul courant ! !
  etapes          t_etapes;
  deps            t_deps;
  intervenants    t_intervenants;
  intervenant     t_intervenant;



  PROCEDURE DEP_CHECK( etape_suiv_id NUMERIC, etape_prec_id NUMERIC ) IS
    eso NUMERIC;
    epo NUMERIC;
  BEGIN
    SELECT ordre INTO eso FROM wf_etape WHERE id = etape_suiv_id;
    SELECT ordre INTO epo FROM wf_etape WHERE id = etape_prec_id;

    IF eso < epo THEN
      raise_application_error(-20101, 'Une étape de Workflow ne peut dépendre d''une étape située en aval');
    END IF;
    IF eso = epo THEN
      raise_application_error(-20101, 'Une étape de Workflow ne peut dépendre d''elle-même');
    END IF;
  END;



  PROCEDURE DUMP_DEBUG IS
    e VARCHAR2(20);
    b NUMERIC;
    dep_blo VARCHAR2(100);
    i NUMERIC;
  BEGIN
    /*
    ose_test.echo('-- TBL_WORKFLOW DUMP ETAPES --');
    i := etapes.FIRST;
    LOOP EXIT WHEN i IS NULL;
      ose_test.echo('id    = ' || etapes(i).id );
      ose_test.echo('code  = ' || etapes(i).code );
      ose_test.echo('ordre = ' || etapes(i).ordre );
      ose_test.echo('');
      i := etapes.NEXT(i);
    END LOOP;
    ose_test.echo('');
    */
    ose_test.echo('annee_id              = ' || intervenant.annee_id );
    ose_test.echo('statut_id = ' || intervenant.statut_id );
    ose_test.echo('type_intervenant_id   = ' || intervenant.type_intervenant_id );
    ose_test.echo('type_intervenant_code = ' || intervenant.type_intervenant_code );
    ose_test.echo('feuille_de_route      = [');

    e := intervenant.feuille_de_route.FIRST;
    LOOP EXIT WHEN e IS NULL;
      ose_test.echo('  index = ' || e );
      ose_test.echo('    id              = ' || intervenant.feuille_de_route(e).id );
      IF intervenant.feuille_de_route(e).etape_id IS NOT NULL THEN
        ose_test.echo('    etape_id        = ' || intervenant.feuille_de_route(e).etape_id || ' (' || etapes(intervenant.feuille_de_route(e).etape_id).code || ')' );
      ELSE
        ose_test.echo('    etape_id        = NULL');
      END IF;
      ose_test.echo('    structure_id    = ' || intervenant.feuille_de_route(e).structure_id );
      ose_test.echo('    atteignable     = ' || intervenant.feuille_de_route(e).atteignable || ' (old=' || intervenant.feuille_de_route(e).old_atteignable || ')' );
      ose_test.echo('    objectif        = ' || intervenant.feuille_de_route(e).objectif || ' (old=' || intervenant.feuille_de_route(e).old_objectif || ')'  );
      ose_test.echo('    realisation     = ' || intervenant.feuille_de_route(e).realisation || ' (old=' || intervenant.feuille_de_route(e).old_realisation || ')'  );
      ose_test.echo('    to_delete       = ' || CASE WHEN intervenant.feuille_de_route(e).to_delete THEN '1' ELSE '0' END );
      ose_test.echo('    to_update       = ' || CASE WHEN intervenant.feuille_de_route(e).to_update THEN '1' ELSE '0' END );

      IF intervenant.feuille_de_route(e).deps_bloquantes.COUNT > 0 THEN
        ose_test.echo('    dépendances bloquantes = [');
        b := intervenant.feuille_de_route(e).deps_bloquantes.FIRST;
        LOOP EXIT WHEN b IS NULL;
          ose_test.echo('        ' || b || ' => ');
          ose_test.echo('            id        = ' || intervenant.feuille_de_route(e).deps_bloquantes(b).id        );
          ose_test.echo('            to_delete = ' || CASE WHEN intervenant.feuille_de_route(e).deps_bloquantes(b).to_delete THEN '1' ELSE '0' END );
          ose_test.echo('');

          b := intervenant.feuille_de_route(e).deps_bloquantes.NEXT(b);
        END LOOP;
        ose_test.echo('    ]');
      END IF;
      ose_test.echo('');

      e := intervenant.feuille_de_route.NEXT(e);
    END LOOP;

    ose_test.echo(']');
  END;



  FUNCTION MAKE_FR_ETAPE_INDEX( etape_id NUMERIC, structure_id NUMERIC DEFAULT NULL) RETURN VARCHAR2 IS
  BEGIN
    RETURN lpad(etapes(etape_id).ordre, 4, '0') || '-' || COALESCE(structure_id,0);
  END;



  PROCEDURE ENREGISTRER( e IN OUT NOCOPY t_workflow_etape ) IS
    w tbl_workflow%rowtype;
    wed wf_dep_bloquante%rowtype;
    b NUMERIC;
  BEGIN
    IF e.to_delete THEN
      DELETE FROM tbl_workflow WHERE id = e.id;
    ELSE
      w.annee_id              := intervenant.annee_id;
      w.intervenant_id        := intervenant.intervenant_id;
      w.statut_id             := intervenant.statut_id;
      w.type_intervenant_id   := intervenant.type_intervenant_id;
      w.type_intervenant_code := intervenant.type_intervenant_code;
      w.etape_id              := e.etape_id;
      w.etape_code            := etapes(e.etape_id).code;
      w.structure_id          := e.structure_id;
      w.atteignable           := e.atteignable;
      w.realisation           := e.realisation;
      w.objectif              := e.objectif;
      IF e.id IS NULL THEN
        w.id := tbl_workflow_id_seq.NEXTVAL;
        INSERT INTO tbl_workflow values w;
      ELSE
        w.id := e.id;
        IF e.old_atteignable <> e.atteignable
          OR e.old_realisation <> e.realisation
          OR e.old_objectif <> e.objectif
        THEN
          e.to_update := TRUE;
        END IF;
        IF e.to_update THEN
          UPDATE tbl_workflow SET row = w WHERE id = w.id;
        END IF;
      END IF;

      b := e.deps_bloquantes.FIRST;
      LOOP EXIT WHEN b IS NULL;
        IF e.deps_bloquantes(b).to_delete THEN
          DELETE FROM wf_dep_bloquante WHERE id = e.deps_bloquantes(b).id;
        ELSIF e.deps_bloquantes(b).id IS NULL THEN
          INSERT INTO wf_dep_bloquante (
            id,
            wf_etape_dep_id,
            tbl_workflow_id
          ) VALUES (
            WF_DEP_BLOQUANTE_ID_SEQ.NEXTVAL,
            b,
            w.id
          );
        END IF;

        b := e.deps_bloquantes.next(b);
      END LOOP;

    END IF;
  END;



  FUNCTION ETAPE_FRANCHIE( etape IN t_workflow_etape, need_done boolean default false ) RETURN FLOAT IS
    res FLOAT DEFAULT 0;
  BEGIN
    IF etape.objectif = 0 THEN
      IF need_done THEN RETURN 0; ELSE RETURN 1; END IF;
    END IF;

    IF etape.atteignable = 0 THEN RETURN 0; END IF;

    IF etape.objectif > 0 THEN
      res := etape.realisation / etape.objectif;
    END IF;

    IF res > 1 THEN
      res := 1;
    END IF;

    RETURN res;
  END;



  PROCEDURE CALCUL_ATTEIGNABLE( wf_etape IN OUT NOCOPY t_workflow_etape, d wf_etape_dep%rowtype ) IS
    workflow t_workflow;
    count_tested PLS_INTEGER DEFAULT 0;
    count_na     PLS_INTEGER DEFAULT 0;
    p VARCHAR2(20); -- index de l'étape précédente
  BEGIN
    IF d.type_intervenant_id IS NOT NULL AND d.type_intervenant_id <> intervenant.type_intervenant_id THEN
      RETURN; -- cette dépendance ne concerne pas notre intervenant
    END IF;

    workflow := intervenant.feuille_de_route;

    p := workflow.FIRST;
    LOOP EXIT WHEN p IS NULL;
      IF workflow(p).etape_id = d.etape_prec_id THEN
        -- on restreint en fonction du périmètre visé :
        --  - si la dépendance n'est pas locale alors on teste
        --  - si les structures aussi bien de l'étape testée que de l'étape dépendante sont nulles alors on teste aussi car elles sont "universelles"
        --  - si les structures sont équivalentes alors on teste, sinon elles ne sont pas dans le périmètre local
        IF
          (d.locale = 0)
          OR wf_etape.structure_id IS NULL
          OR workflow(p).structure_id IS NULL
          OR wf_etape.structure_id = workflow(p).structure_id
        THEN
          count_tested := count_tested + 1;

          -- on teste le type de franchissement désiré et si ce n'est pas bon alors on déclare l'étape courante non atteignable

          --  - idem si on a besoin d'une dépendance partiellement franchie est qu'elle ne l'est pas
          IF d.partielle = 1 THEN
            IF ETAPE_FRANCHIE(workflow(p), d.obligatoire=1) = 0 THEN -- si le franchissement est totalement inexistant
              count_na := count_na + 1;
            END IF;
          --  - si on a besoin d'une dépendance complètement franchie est qu'elle ne l'est pas alors ce n'est pas atteignable
          ELSE
            IF ETAPE_FRANCHIE(workflow(p), d.obligatoire=1) < 1 THEN
              count_na := count_na + 1;
            END IF;
          END IF;
        END IF;

      END IF;
      p := workflow.next(p);
    END LOOP;

    -- on applique le résultat uniquement si des étapes dépendantes ont été trouvées
    IF count_tested > 0 THEN

      -- si les étapes dépendantes ont été intégralement franchies
      IF d.integrale = 1 THEN
        -- si l'intégralité des étapes est atteignable = NON si au moins une ne l'est pas
        IF count_na > 0 THEN
          wf_etape.atteignable := 0;
          wf_etape.deps_bloquantes(d.id).to_delete := FALSE;
        END IF;

      -- sinon...
      ELSE
        -- si au moins une étape est atteignable = NON si toutes ne sont pas atteignables
        IF count_tested = count_na THEN
          wf_etape.atteignable := 0;
          wf_etape.deps_bloquantes(d.id).to_delete := FALSE;
        END IF;
      END IF;
    END IF;
  END;



  -- calcule si les étapes sont atteignables ou non
  PROCEDURE TRAITEMENT IS
    workflow_etape t_workflow_etape;
    e VARCHAR2(20); -- index de l'étape courante
    d PLS_INTEGER; -- ID de l'étape précédante
  BEGIN
    e := intervenant.feuille_de_route.FIRST;
    LOOP EXIT WHEN e IS NULL;
      workflow_etape := intervenant.feuille_de_route(e);

      IF (NOT workflow_etape.to_delete) AND deps.exists(workflow_etape.etape_id) THEN -- s'il n'y a aucune dépendance alors pas de test!!
        d := deps(workflow_etape.etape_id).FIRST;
        LOOP EXIT WHEN d IS NULL;

          CALCUL_ATTEIGNABLE(intervenant.feuille_de_route(e), deps(workflow_etape.etape_id)(d));

          d := deps(workflow_etape.etape_id).NEXT(d);
        END LOOP;
      END IF;
      ENREGISTRER(intervenant.feuille_de_route(e));
      e := intervenant.feuille_de_route.NEXT(e);
    END LOOP;
  END;



  PROCEDURE INITIALISATION IS
  BEGIN
    etapes.delete;
    FOR d IN (
      SELECT * FROM wf_etape
    ) LOOP
      etapes(d.id) := d;
    END LOOP;

    deps.delete;
    FOR d IN (
      SELECT * FROM wf_etape_dep WHERE active = 1
    ) LOOP
      deps(d.etape_suiv_id)(d.etape_prec_id) := d;
    END LOOP;

    intervenants.delete;
  END;



  PROCEDURE CALCULER( INTERVENANT_ID NUMERIC ) IS
  BEGIN
    CALCULER_TBL('INTERVENANT_ID', INTERVENANT_ID);
  END;



  PROCEDURE CALCULER_TOUT( ANNEE_ID NUMERIC DEFAULT NULL ) IS
  BEGIN
    IF ANNEE_ID IS NULL THEN
      CALCULER_TBL();
    ELSE
      CALCULER_TBL('ANNEE_ID', ANNEE_ID);
    END IF;
  END;




  FUNCTION MAKE_V_TBL_WORKFLOW(param VARCHAR2 DEFAULT NULL, value VARCHAR2 DEFAULT NULL) RETURN CLOB IS
    p VARCHAR2(30);
    dems CLOB;
    intervenant CLOB;
    dossier CLOB;
    service_saisie CLOB;
    validation_enseignement CLOB;
    validation_referentiel CLOB;
    pieces_justificatives CLOB;
    agrement CLOB;
    paiement CLOB;
    cloture CLOB;
    contrat CLOB;
  BEGIN
    dems := '
        WHEN e.code = ''DONNEES_PERSO_SAISIE'' OR e.code = ''DONNEES_PERSO_VALIDATION'' THEN
          si.peut_saisir_dossier

        WHEN e.code = ''SERVICE_SAISIE'' THEN
          CASE WHEN si.peut_saisir_service + si.peut_saisir_referentiel = 0 THEN 0 ELSE 1 END

        WHEN e.code = ''PJ_SAISIE'' OR e.code = ''PJ_VALIDATION'' THEN
          CASE WHEN EXISTS(
            SELECT statut_intervenant_id FROM type_piece_jointe_statut tpjs WHERE tpjs.histo_destruction IS NULL AND tpjs.statut_intervenant_id = si.id
            --SELECT intervenant_id FROM tbl_piece_jointe_demande WHERE intervenant_id = i.id
          ) THEN 1 ELSE 0 END

        WHEN e.code = ''SERVICE_VALIDATION'' THEN
          si.peut_saisir_service

        WHEN e.code = ''REFERENTIEL_VALIDATION'' THEN
          si.peut_saisir_referentiel

        WHEN e.code = ''CONSEIL_ACADEMIQUE'' OR e.code = ''CONSEIL_RESTREINT'' THEN
          CASE WHEN EXISTS(
            SELECT statut_intervenant_id
            FROM type_agrement_statut tas JOIN type_agrement ta ON ta.id = tas.type_agrement_id
            WHERE tas.histo_destruction IS NULL
              AND ta.code = e.code
              AND tas.statut_intervenant_id = si.id
          ) THEN 1 ELSE 0 END

        WHEN e.code = ''CONTRAT'' THEN
          si.peut_avoir_contrat

        WHEN e.code = ''SERVICE_SAISIE_REALISE'' OR e.code = ''DEMANDE_MEP'' OR e.code = ''SAISIE_MEP'' THEN
          CASE WHEN si.peut_saisir_service + si.peut_saisir_referentiel = 0 THEN 0 ELSE 1 END

        WHEN e.code = ''CLOTURE_REALISE'' THEN
          si.peut_cloturer_saisie

        WHEN e.code = ''SERVICE_VALIDATION_REALISE'' THEN
          si.peut_saisir_service

        WHEN e.code = ''REFERENTIEL_VALIDATION_REALISE'' THEN
          si.peut_saisir_referentiel
    ';



    intervenant := '
      SELECT
        id                  intervenant_id,
        annee_id            annee_id,
        statut_id           statut_intervenant_id
      FROM
        intervenant
      WHERE
        ' || unicaen_tbl.MAKE_WHERE(CASE param WHEN 'INTERVENANT_ID' THEN 'ID' ELSE param END, value) || '
    ';



    dossier := '
        SELECT
          e.code                                                    etape_code,
          d.intervenant_id                                          intervenant_id,
          null                                                      structure_id,
          1                                                         objectif,
          CASE
            WHEN e.code = ''DONNEES_PERSO_SAISIE'' THEN
              (d.completude_statut + d.completude_identite + d.completude_identite_comp + d.completude_contact + d.completude_adresse + d.completude_insee + d.completude_iban + d.completude_employeur) / 8

            WHEN e.code = ''DONNEES_PERSO_VALIDATION'' THEN
              CASE WHEN d.validation_id IS NULL THEN 0 ELSE 1 END

          END                                                       realisation
        FROM
          tbl_dossier d
          JOIN (
                  SELECT ''DONNEES_PERSO_SAISIE''     code FROM dual
            UNION SELECT ''DONNEES_PERSO_VALIDATION'' code FROM dual
          ) e ON 1=1
        WHERE
          ' || unicaen_tbl.MAKE_WHERE(param, value) || '
          AND d.peut_saisir_dossier = 1
    ';



    service_saisie := '
        SELECT
          e.code                                                    etape_code,
          tss.intervenant_id                                        intervenant_id,
          NULL                                                      structure_id,
          1                                                         objectif,
          CASE
            WHEN e.code = ''SERVICE_SAISIE'' THEN
              CASE WHEN tss.heures_service_prev + tss.heures_referentiel_prev > 0 THEN 1 ELSE 0 END

            WHEN e.code = ''SERVICE_SAISIE_REALISE'' THEN
              CASE WHEN tss.heures_service_real + tss.heures_referentiel_real > 0 THEN 1 ELSE 0 END

          END                                                       realisation
        FROM
          TBL_SERVICE_SAISIE tss
          JOIN (
                  SELECT ''SERVICE_SAISIE''                 code FROM dual
            UNION SELECT ''SERVICE_SAISIE_REALISE''         code FROM dual
          ) e ON 1=1
        WHERE
          ' || unicaen_tbl.MAKE_WHERE(param, value) || '
          AND (tss.peut_saisir_service = 1 OR tss.peut_saisir_referentiel = 1)
    ';



    validation_enseignement := '
        SELECT
          CASE
            WHEN tvh.code = ''PREVU''   THEN ''SERVICE_VALIDATION''
            WHEN tvh.code = ''REALISE'' THEN ''SERVICE_VALIDATION_REALISE''
          END                                                        etape_code,
          tve.intervenant_id                                         intervenant_id,
          tve.structure_id                                           structure_id,
          COUNT(*)                                                   objectif,
          SUM(CASE WHEN tve.validation_id IS NOT NULL THEN 1 ELSE 0 END) realisation
        FROM
          tbl_validation_enseignement tve
          JOIN type_volume_horaire tvh ON tvh.id = tve.type_volume_horaire_id
        WHERE
          ' || unicaen_tbl.MAKE_WHERE(param, value) || '
          AND tve.auto_validation = 0
        GROUP BY
          tve.intervenant_id,
          tve.structure_id,
          tvh.code
    ';



    validation_referentiel := '
        SELECT
          CASE
            WHEN tvh.code = ''PREVU''   THEN ''REFERENTIEL_VALIDATION''
            WHEN tvh.code = ''REALISE'' THEN ''REFERENTIEL_VALIDATION_REALISE''
          END                                                        etape_code,
          tvr.intervenant_id                                         intervenant_id,
          tvr.structure_id                                           structure_id,
          count(*)                                                   objectif,
          SUM(CASE WHEN tvr.validation_id IS NOT NULL THEN 1 ELSE 0 END) realisation
        FROM
          tbl_validation_referentiel tvr
          JOIN type_volume_horaire tvh ON tvh.id = tvr.type_volume_horaire_id
        WHERE
          ' || unicaen_tbl.MAKE_WHERE(param, value) || '
          AND tvr.auto_validation = 0
        GROUP BY
          tvr.intervenant_id,
          tvr.structure_id,
          tvh.code
    ';



    pieces_justificatives := '
        SELECT
          e.code                                                    etape_code,
          pj.intervenant_id                                         intervenant_id,
          null                                                      structure_id,
          CASE
            WHEN e.code = ''PJ_SAISIE'' THEN pj.demandees
            WHEN e.code = ''PJ_VALIDATION'' THEN pj.demandees
          END                                                       objectif,
          CASE
            WHEN e.code = ''PJ_SAISIE'' THEN pj.fournies
            WHEN e.code = ''PJ_VALIDATION'' THEN pj.validees
          END                                                       realisation
        FROM
          (
          SELECT
            intervenant_id,
            SUM(demandee) demandees,
            SUM(fournie)  fournies,
            SUM(validee)  validees
          FROM
            tbl_piece_jointe
          WHERE
            ' || unicaen_tbl.MAKE_WHERE(param, value) || '
            AND demandee > 0
            AND obligatoire = 1
          GROUP BY
            annee_id,
            intervenant_id
        ) pj
          JOIN (
                  SELECT ''PJ_SAISIE''      code FROM dual
            UNION SELECT ''PJ_VALIDATION''  code FROM dual
          ) e ON (
               (e.code = ''PJ_SAISIE''     AND pj.demandees > 0)
            OR (e.code = ''PJ_VALIDATION'' AND pj.fournies  > 0)
          )
    ';



    agrement := '
        SELECT
          ta.code                                                   etape_code,
          a.intervenant_id                                          intervenant_id,
          a.structure_id                                            structure_id,
          1                                                         objectif,
          CASE WHEN a.agrement_id IS NULL THEN 0 ELSE 1 END         realisation
        FROM
          tbl_agrement a
          JOIN type_agrement ta ON ta.id = a.type_agrement_id
        WHERE
          ' || unicaen_tbl.MAKE_WHERE(param, value) || '
    ';



    cloture := '
        SELECT
          ''CLOTURE_REALISE''                                       etape_code,
          c.intervenant_id                                          intervenant_id,
          null                                                      structure_id,
          1                                                         objectif,
          c.cloture                                                 realisation
        FROM
          tbl_cloture_realise c
        WHERE
          ' || unicaen_tbl.MAKE_WHERE(param, value) || '
          AND c.peut_cloturer_saisie = 1
    ';



    paiement := '
        SELECT
          e.code                                                    etape_code,
          mep.intervenant_id                                        intervenant_id,
          mep.structure_id                                          structure_id,
          CASE
            WHEN e.code = ''DEMANDE_MEP'' THEN mep.sap
            WHEN e.code = ''SAISIE_MEP'' THEN mep.dmep
          END                                                       objectif,
          CASE
            WHEN e.code = ''DEMANDE_MEP'' THEN mep.dmep
            WHEN e.code = ''SAISIE_MEP'' THEN mep.mep
          END                                                       realisation
        FROM
          (
            SELECT
              intervenant_id,
              structure_id,
              SUM(heures_a_payer / heures_a_payer_pond) sap,
              SUM(heures_demandees) dmep,
              SUM(heures_payees) mep
            FROM
              tbl_paiement
            WHERE
              ' || unicaen_tbl.MAKE_WHERE(param, value) || '
            GROUP BY
              annee_id,
              intervenant_id,
              structure_id
          ) mep
          JOIN (
                  SELECT ''DEMANDE_MEP''  code FROM dual
            UNION SELECT ''SAISIE_MEP''   code FROM dual
          ) e ON (
               (e.code = ''DEMANDE_MEP'' AND mep.sap > 0)
            OR (e.code = ''SAISIE_MEP''  AND mep.dmep > 0)
          )
    ';



    contrat := '
        SELECT
          ''CONTRAT''                                               etape_code,
          intervenant_id                                            intervenant_id,
          structure_id                                              structure_id,
          nbvh                                                      objectif,
          CASE p.valeur
            WHEN ''date-retour'' THEN signe
            ELSE edite
          END                                                       realisation
        FROM
          tbl_contrat c
          JOIN parametre p on p.nom = ''contrat_regle_franchissement''
        WHERE
          ' || unicaen_tbl.MAKE_WHERE(param, value) || '
          AND peut_avoir_contrat = 1
          AND nbvh > 0
    ';




    RETURN '
    SELECT
      i.annee_id                                           annee_id,
      i.intervenant_id                                     intervenant_id,
      e.id                                                 etape_id,
      w.structure_id                                       structure_id,
      ROUND(COALESCE(w.objectif,0),2)                      objectif,
      CASE WHEN w.intervenant_id IS NULL THEN 0 ELSE 1 END atteignable,
      ROUND(COALESCE(w.realisation,0),2)                   realisation,
      i.statut_intervenant_id                              statut_intervenant_id,
      ti.id                                                type_intervenant_id,
      ti.code                                              type_intervenant_code
    FROM
      ( ' || intervenant || ') i
      JOIN statut_intervenant      si ON si.id = i.statut_intervenant_id
      JOIN type_intervenant        ti ON ti.id = si.type_intervenant_id
      JOIN wf_etape                 e ON 1 = CASE ' || dems || ' END
      LEFT JOIN ( ' || dossier || '
        UNION ALL ' || service_saisie || '
        UNION ALL ' || validation_enseignement || '
        UNION ALL ' || validation_referentiel || '
        UNION ALL ' || pieces_justificatives || '
        UNION ALL ' || agrement || '
        UNION ALL ' || paiement || '
        UNION ALL ' || cloture || '
        UNION ALL ' || contrat || '
      ) w ON w.intervenant_id = i.intervenant_id AND w.etape_code = e.code
    WHERE
      e.obligatoire = 1 OR w.intervenant_id IS NOT NULL
    ';
  END;



  PROCEDURE CALCULER_TBL( param VARCHAR2 DEFAULT NULL, value VARCHAR2 DEFAULT NULL ) IS
    TYPE t_v_tbl_workflow IS RECORD(
      annee_id              NUMERIC,
      intervenant_id        NUMERIC,
      etape_id              NUMERIC,
      structure_id          NUMERIC,
      objectif              FLOAT,
      atteignable           NUMERIC,
      realisation           FLOAT,
      statut_id             NUMERIC,
      type_intervenant_id   NUMERIC,
      type_intervenant_code VARCHAR2(1)
    );
    TYPE t_wdb IS RECORD (
      id              NUMERIC,
      tbl_workflow_id NUMERIC,
      wf_etape_dep_id NUMERIC,
      intervenant_id  NUMERIC,
      etape_id        NUMERIC,
      structure_id    NUMERIC
    );
    wdb t_wdb;
    we_ec VARCHAR(20);

    TYPE r_cursor IS REF CURSOR;
    c r_cursor;

    new_we t_workflow_etape;
    we t_workflow_etape;

    i NUMERIC;

    ci t_intervenant;

    t tbl_workflow%rowtype;
    v t_v_tbl_workflow;
    u BOOLEAN;
  BEGIN
    INITIALISATION;
    OPEN c FOR 'SELECT * FROM tbl_workflow WHERE ' || unicaen_tbl.MAKE_WHERE(param, value);
    LOOP
      FETCH c INTO t; EXIT WHEN c%NOTFOUND;
      IF NOT intervenants.exists(t.intervenant_id) THEN
        intervenants(t.intervenant_id).annee_id              := t.annee_id;
        intervenants(t.intervenant_id).intervenant_id        := t.intervenant_id;
        intervenants(t.intervenant_id).statut_id             := t.statut_id;
        intervenants(t.intervenant_id).type_intervenant_id   := t.type_intervenant_id;
        intervenants(t.intervenant_id).type_intervenant_code := t.type_intervenant_code;
      END IF;
      we_ec := MAKE_FR_ETAPE_INDEX( t.etape_id, t.structure_id );
      we := new_we;
      we.id              := t.id;
      we.to_delete       := TRUE;
      we.to_update       := FALSE;
      we.etape_id        := t.etape_id;
      we.structure_id    := t.structure_id;
      we.old_atteignable := t.atteignable;
      we.atteignable     := t.atteignable;
      we.old_objectif    := t.objectif;
      we.objectif        := t.objectif;
      we.old_realisation := t.realisation;
      we.realisation     := t.realisation;
      intervenants(t.intervenant_id).feuille_de_route(we_ec) := we;
    END LOOP;
    CLOSE c;

    OPEN c FOR MAKE_V_TBL_WORKFLOW(param, value);
    LOOP
      FETCH c INTO v; EXIT WHEN c%NOTFOUND;

      u := FALSE;
      IF intervenants.exists(v.intervenant_id) THEN
        ci := intervenants(v.intervenant_id);
        IF ci.annee_id <> v.annee_id THEN
          intervenants(v.intervenant_id).annee_id := v.annee_id;
          u := TRUE;
        END IF;
        IF ci.statut_id <> v.statut_id THEN
          intervenants(v.intervenant_id).statut_id := v.statut_id;
          u := TRUE;
        END IF;
        IF ci.type_intervenant_id <> v.type_intervenant_id THEN
          intervenants(v.intervenant_id).type_intervenant_id := v.type_intervenant_id;
          u := TRUE;
        END IF;
        IF ci.type_intervenant_code <> v.type_intervenant_code THEN
          intervenants(v.intervenant_id).type_intervenant_code := v.type_intervenant_code;
          u := TRUE;
        END IF;
      ELSE
        intervenants(v.intervenant_id).annee_id              := v.annee_id;
        intervenants(v.intervenant_id).intervenant_id        := v.intervenant_id;
        intervenants(v.intervenant_id).statut_id             := v.statut_id;
        intervenants(v.intervenant_id).type_intervenant_id   := v.type_intervenant_id;
        intervenants(v.intervenant_id).type_intervenant_code := v.type_intervenant_code;
      END IF;
      we_ec := MAKE_FR_ETAPE_INDEX( v.etape_id, v.structure_id );
      IF intervenants(v.intervenant_id).feuille_de_route.exists(we_ec) THEN
        we := intervenants(v.intervenant_id).feuille_de_route(we_ec);
      ELSE
        we                 := new_we;
        we.etape_id        := v.etape_id;
        we.structure_id    := v.structure_id;
      END IF;
      we.to_delete       := FALSE;
      we.to_update       := u;
      we.atteignable     := v.atteignable;
      we.objectif        := v.objectif;
      we.realisation     := v.realisation;
      intervenants(v.intervenant_id).feuille_de_route(we_ec) := we;
    END LOOP;
    CLOSE c;

    OPEN c FOR '
    SELECT
      wdb.id, wdb.tbl_workflow_id, wdb.wf_etape_dep_id, v.intervenant_id, v.etape_id, v.structure_id
    FROM
      wf_dep_bloquante wdb
      JOIN tbl_workflow v ON v.id= wdb.tbl_workflow_id
    WHERE ' || unicaen_tbl.MAKE_WHERE( param, value, 'v' );
    LOOP
      FETCH c INTO wdb; EXIT WHEN c%NOTFOUND;
      we_ec := MAKE_FR_ETAPE_INDEX( wdb.etape_id, wdb.structure_id );
      IF intervenants(wdb.intervenant_id).feuille_de_route.exists(we_ec) THEN
        intervenants(wdb.intervenant_id).feuille_de_route(we_ec).deps_bloquantes(wdb.wf_etape_dep_id).id := wdb.id;
      ELSE
        we := new_we;
        we.to_delete := TRUE;
        we.to_delete := FALSE;
        we.id := wdb.tbl_workflow_id;
        we.etape_id := wdb.etape_id;
        we.structure_id := wdb.structure_id;
        we.deps_bloquantes(wdb.wf_etape_dep_id).id := wdb.id;
        intervenants(wdb.intervenant_id).feuille_de_route(we_ec) := we;
      END IF;
    END LOOP;
    CLOSE c;
    i := intervenants.FIRST;
    LOOP EXIT WHEN i IS NULL;
      intervenant := intervenants(i);
      TRAITEMENT();
      --dump_debug;
      i := intervenants.NEXT(i);
    END LOOP;
  END;

END OSE_WORKFLOW;