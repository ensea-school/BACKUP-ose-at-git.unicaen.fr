CREATE OR REPLACE PACKAGE BODY FORMULE_RENNES1 AS
  decalageLigne NUMERIC DEFAULT 20;


  /* Stockage des valeurs intermédiaires */
  TYPE t_cell IS RECORD (
    valeur FLOAT,
    enCalcul BOOLEAN DEFAULT FALSE
  );
  TYPE t_cells IS TABLE OF t_cell INDEX BY PLS_INTEGER;
  TYPE t_coll IS RECORD (
    cells t_cells
  );
  TYPE t_colls IS TABLE OF t_coll INDEX BY VARCHAR2(50);
  feuille t_colls;

  debugLine NUMERIC;


  PROCEDURE dbg( val CLOB ) IS
  BEGIN
    ose_formule.volumes_horaires.items(debugLine).debug_info :=
      ose_formule.volumes_horaires.items(debugLine).debug_info || val;
  END;


  PROCEDURE dbgi( val CLOB ) IS
  BEGIN
    ose_formule.intervenant.debug_info := ose_formule.intervenant.debug_info || val;
  END;

  PROCEDURE dbgDump( val CLOB ) IS
  BEGIN
    dbg('<div class="dbg-dump">' || val || '</div>');
  END;

  PROCEDURE dbgCell( c VARCHAR2, l NUMERIC, val FLOAT ) IS
    ligne NUMERIC;
  BEGIN
    ligne := l;
    IF l <> 0 THEN
      ligne := ligne + decalageLigne;
    END IF;

    dbgi( '[cell|' || c || '|' || ligne || '|' || val );
  END;

  PROCEDURE dbgCalc( fncName VARCHAR2, c VARCHAR2, res FLOAT ) IS
  BEGIN
    dbgi( '[calc|' || fncName || '|' || c || '|' || res );
  END;

  FUNCTION cell( c VARCHAR2, l NUMERIC DEFAULT 0 ) RETURN FLOAT IS
    val FLOAT;
  BEGIN
    IF feuille.exists(c) THEN
      IF feuille(c).cells.exists(l) THEN
        IF feuille(c).cells(l).enCalcul THEN
          raise_application_error( -20001, 'Dépendance cyclique : la cellule [' || c || ';' || l || '] est déjà en cours de calcul');
        END IF;
        RETURN feuille(c).cells(l).valeur;
      END IF;
    END IF;

    feuille(c).cells(l).enCalcul := true;
    val := calcCell( c, l );
    IF ose_formule.debug_actif THEN
      dbgCell( c, l, val );
    END IF;
    feuille(c).cells(l).valeur := val;
    feuille(c).cells(l).enCalcul := false;

    RETURN val;
  END;

  FUNCTION mainCell( libelle VARCHAR2, c VARCHAR2, l NUMERIC ) RETURN FLOAT IS
    val FLOAT;
  BEGIN
    debugLine := l;
    val := cell(c,l);

    RETURN val;
  END;

  FUNCTION calcFnc( fncName VARCHAR2, c VARCHAR2 ) RETURN FLOAT IS
    val FLOAT;
    cellRes FLOAT;
  BEGIN
    IF feuille.exists('__' || fncName || '__' || c || '__') THEN
      IF feuille('__' || fncName || '__' || c || '__').cells.exists(1) THEN
        RETURN feuille('__' || fncName || '__' || c || '__').cells(1).valeur;
      END IF;
    END IF;
    CASE
    -- Liste des fonctions supportées

    WHEN fncName = 'total' THEN
      val := 0;
      FOR l IN 1 .. ose_formule.volumes_horaires.length LOOP
        val := val + COALESCE(cell(c, l),0);
      END LOOP;

    WHEN fncName = 'max' THEN
      val := NULL;
      FOR l IN 1 .. ose_formule.volumes_horaires.length LOOP
        cellRes := cell(c,l);
        IF val IS NULL OR val < cellRes THEN
          val := cellRes;
        END IF;
      END LOOP;

    -- fin de la liste des fonctions supportées
    ELSE
      raise_application_error( -20001, 'La formule "' || fncName || '" n''existe pas!');
    END CASE;
    IF ose_formule.debug_actif THEN
      dbgCalc(fncName, c, val );
    END IF;
    feuille('__' || fncName || '__' || c || '__').cells(1).valeur := val;

    RETURN val;
  END;


  FUNCTION calcVersion RETURN NUMERIC IS
  BEGIN
    RETURN 1;
  END;



  FUNCTION notInStructs( v VARCHAR2 DEFAULT NULL ) RETURN BOOLEAN IS
  BEGIN
    RETURN COALESCE(v,' ') NOT IN ('KE8','UP10');
  END;



  FUNCTION calcCell( c VARCHAR2, l NUMERIC ) RETURN FLOAT IS
    vh ose_formule.t_volume_horaire;
    i  ose_formule.t_intervenant;
    v NUMERIC;
    val FLOAT;
  BEGIN
    v := calcVersion;

    i := ose_formule.intervenant;
    IF l > 0 THEN
      vh := ose_formule.volumes_horaires.items(l);
    END IF;
    CASE



    -- AH15=SOMME(AG:AG)
    WHEN c = 'AH15' AND v >= 1 THEN
      RETURN calcFnc('total', 'AG');



    -- AN15=SOMME(AM:AM)
    WHEN c = 'AN15' AND v >= 1 THEN
      RETURN calcFnc('total', 'AM');



    -- AH16=MIN(AH15;i_service_du)
    WHEN c = 'AH16' AND v >= 1 THEN
      RETURN LEAST(cell('AH15'), i.service_du);



    -- AN16=MIN(AN15;AH17)
    WHEN c = 'AN16' AND v >= 1 THEN
      RETURN LEAST(cell('AN15'), cell('AH17'));



    -- AH17=i_service_du-AH16
    WHEN c = 'AH17' AND v >= 1 THEN
      RETURN i.service_du - cell('AH16');



    -- AN17=AH17-AN16
    WHEN c = 'AN17' AND v >= 1 THEN
      RETURN cell('AH17') - cell('AN16');



    -- T=SI($H20="Référentiel";0;$AI20*E20)
    WHEN c = 'T' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        RETURN cell('AI',l) * vh.taux_fi;
      END IF;



    -- U=SI($H20="Référentiel";0;$AI20*F20)
    WHEN c = 'U' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        RETURN cell('AI',l) * vh.taux_fa;
      END IF;



    -- V=SI($H20="Référentiel";0;$AI20*G20)
    WHEN c = 'V' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        RETURN cell('AI',l) * vh.taux_fc;
      END IF;



    -- W=SI($H20="Référentiel";$AO20;0)
    WHEN c = 'W' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN cell('AO',l);
      ELSE
        RETURN 0;
      END IF;



    -- X=SI($H20="Référentiel";0;$AK20*E20)
    WHEN c = 'X' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        RETURN cell('AK',l) * vh.taux_fi;
      END IF;



    -- Y=SI($H20="Référentiel";0;$AK20*F20)
    WHEN c = 'Y' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        RETURN cell('AK',l) * vh.taux_fa;
      END IF;



    -- Z=SI($H20="Référentiel";0;$AK20*G20)
    WHEN c = 'Z' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        RETURN cell('AK',l) * vh.taux_fc;
      END IF;



    -- AA=0
    WHEN c = 'AA' AND v >= 1 THEN
      RETURN 0;



    -- AB=SI($H20="Référentiel";$AQ20;0)
    WHEN c = 'AB' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN cell('AQ',l);
      ELSE
        RETURN 0;
      END IF;



    -- AD=SI(ESTERREUR(I20);1;I20)
    WHEN c = 'AD' AND v >= 1 THEN
      RETURN vh.taux_service_du;



    -- AE=SI(ESTERREUR(J20);1;J20)
    WHEN c = 'AE' AND v >= 1 THEN
      RETURN vh.taux_service_compl;



    -- AG=SI(ET($D20="Oui";$H20<>"Référentiel");$M20*$AD20;0)
    WHEN c = 'AG' AND v >= 1 THEN
      IF vh.service_statutaire AND vh.volume_horaire_ref_id IS NULL THEN
        RETURN vh.heures * cell('AD',l);
      ELSE
        RETURN 0;
      END IF;



    -- AH=SI(AH$15>0;AG20/AH$15;0)
    WHEN c = 'AH' AND v >= 1 THEN
      IF cell('AH15') > 0 THEN
        RETURN cell('AG',l) / cell('AH15');
      ELSE
        RETURN 0;
      END IF;



    -- AI=AH$16*AH20
    WHEN c = 'AI' AND v >= 1 THEN
      RETURN cell('AH16') * cell('AH',l);



    -- AJ=SI(AH$17=0;(AG20-AI20)/$AD20;0)
    WHEN c = 'AJ' AND v >= 1 THEN
      IF cell('AH17') = 0 THEN
        RETURN (cell('AG',l) - cell('AI',l)) / cell('AD', l);
      ELSE
        RETURN 0;
      END IF;



    -- AK=SI(i_depassement_service_du_sans_hc="Non";AJ20*$AE20;0)
    WHEN c = 'AK' AND v >= 1 THEN
      IF NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('AJ',l) * cell('AE',l);
      ELSE
        RETURN 0;
      END IF;



    -- AM=SI(ET($D20="Oui";$H20="Référentiel");$M20*$AD20;0)
    WHEN c = 'AM' AND v >= 1 THEN
      IF vh.service_statutaire AND vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN vh.heures * cell('AD',l);
      ELSE
        RETURN 0;
      END IF;



    -- AN=SI(AN$15>0;AM20/AN$15;0)
    WHEN c = 'AN' AND v >= 1 THEN
      IF cell('AN15') > 0 THEN
        RETURN cell('AM',l) / cell('AN15');
      ELSE
        RETURN 0;
      END IF;



    -- AO=AN$16*AN20
    WHEN c = 'AO' AND v >= 1 THEN
      RETURN cell('AN16') * cell('AN',l);



    -- AP=SI(AN$17=0;(AM20-AO20)/$AD20;0)
    WHEN c = 'AP' AND v >= 1 THEN
      IF cell('AN17') = 0 THEN
        RETURN (cell('AM',l) - cell('AO',l)) / cell('AD', l);
      ELSE
        RETURN 0;
      END IF;



    -- AQ=SI(i_depassement_service_du_sans_hc="Non";AP20*$AE20;0)
    WHEN c = 'AQ' AND v >= 1 THEN
      IF NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('AP',l) * cell('AE',l);
      ELSE
        RETURN 0;
      END IF;



    ELSE
      raise_application_error( -20001, 'La colonne c=' || c || ', l=' || l || ' n''existe pas!');
  END CASE; END;



  PROCEDURE CALCUL_RESULTAT IS
  BEGIN
    feuille.delete;

    -- transmission des résultats aux volumes horaires et volumes horaires référentiel
    FOR l IN 1 .. ose_formule.volumes_horaires.length LOOP
      ose_formule.volumes_horaires.items(l).service_fi               := mainCell('Service FI', 'T',l);
      ose_formule.volumes_horaires.items(l).service_fa               := mainCell('Service FA', 'U',l);
      ose_formule.volumes_horaires.items(l).service_fc               := mainCell('Service FC', 'V',l);
      ose_formule.volumes_horaires.items(l).service_referentiel      := mainCell('Service référentiel', 'W',l);
      ose_formule.volumes_horaires.items(l).heures_compl_fi          := mainCell('Heures compl. FI', 'X',l);
      ose_formule.volumes_horaires.items(l).heures_compl_fa          := mainCell('Heures compl. FA', 'Y',l);
      ose_formule.volumes_horaires.items(l).heures_compl_fc          := mainCell('Heures compl. FC', 'Z',l);
      ose_formule.volumes_horaires.items(l).heures_primes            := mainCell('Heures compl. FC Maj.', 'AA',l);
      ose_formule.volumes_horaires.items(l).heures_compl_referentiel := mainCell('Heures compl. référentiel', 'AB',l);
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

END FORMULE_RENNES1;