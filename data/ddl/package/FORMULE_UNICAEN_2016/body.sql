CREATE OR REPLACE PACKAGE BODY "FORMULE_UNICAEN_2016" AS

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
    i  ose_formule.t_intervenant;
  BEGIN
    vh := ose_formule.volumes_horaires.items(vh_index);
    i := ose_formule.intervenant;
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



  PROCEDURE CALCUL_RESULTAT IS
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




  FUNCTION INTERVENANT_QUERY RETURN CLOB IS
  BEGIN
    RETURN '
    SELECT
      fi.*,
      NULL param_1,
      NULL param_2,
      NULL param_3,
      NULL param_4,
      NULL param_5
    FROM
      V_FORMULE_INTERVENANT fi
    ';
  END;



  FUNCTION VOLUME_HORAIRE_QUERY RETURN CLOB IS
  BEGIN
    RETURN '
    SELECT
      fvh.*,
      NULL param_1,
      NULL param_2,
      NULL param_3,
      NULL param_4,
      NULL param_5
    FROM
      V_FORMULE_VOLUME_HORAIRE fvh
    ORDER BY
      ordre';
  END;

END FORMULE_UNICAEN_2016;