CREATE OR REPLACE PACKAGE BODY FORMULE_LYON2 AS
  decalageLigne NUMERIC DEFAULT 21;


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



  FUNCTION isOui( v VARCHAR2 DEFAULT NULL ) RETURN BOOLEAN IS
  BEGIN
    RETURN LOWER(v) IN ('1','oui','true');
  END;

  FUNCTION isD4DAC10000( v VARCHAR2 DEFAULT NULL ) RETURN BOOLEAN IS
  BEGIN
    RETURN UPPER(v) IN ('D4DAC10000');
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



    -- J=H22*I22
    WHEN c = 'J' AND v >= 1 THEN
      RETURN vh.heures * vh.taux_service_du;



    -- L=SI($H22="";0;SI(ET($B22=composante_affectation;$G22<>1;$B22<>"D4DAC10000";$D22<>"Référentiel";$C22="Oui");$J22;0))
    WHEN c = 'L' AND v >= 1 THEN
      --SI(ET($B22=composante_affectation;$G22<>1;$B22<>"D4DAC10000";$D22<>"Référentiel";$C22="Oui");$J22;0)
      IF vh.structure_is_affectation AND vh.taux_fc <> 1 AND (NOT isD4DAC10000(vh.structure_code)) AND vh.volume_horaire_ref_id IS NULL AND vh.structure_code IS NOT NULL THEN
        RETURN cell('J', l);
      ELSE
        RETURN 0;
      END IF;



    -- M=SI(L$52>0;L22/L$52;0)
    WHEN c = 'M' AND v >= 1 THEN
      IF cell('L52') > 0 THEN
        RETURN cell('L', l) / cell('L52');
      ELSE
        RETURN 0;
      END IF;



    -- N=L$53*M22
    WHEN c = 'N' AND v >= 1 THEN
      RETURN cell('L53') * cell('M', l);



    -- O=SI(ET(L$54=0;HC_autorisees="Oui");L22-N22;0)
    WHEN c = 'O' AND v >= 1 THEN
      IF cell('L54') = 0 AND NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('L', l) * cell('M', l);
      ELSE
        RETURN 0;
      END IF;



    -- Q=SI($H22="";0;SI(ET($B22<>composante_affectation;$G22<>1;$B22<>"D4DAC10000";$D22<>"Référentiel";$C22="Oui");$J22;0))
    WHEN c = 'Q' AND v >= 1 THEN
      --SI(ET($B22<>composante_affectation;$G22<>1;$B22<>"D4DAC10000";$D22<>"Référentiel";$C22="Oui");$J22;0)
      IF (NOT vh.structure_is_affectation) AND vh.taux_fc <> 1 AND (NOT isD4DAC10000(vh.structure_code)) AND vh.volume_horaire_ref_id IS NULL AND vh.structure_code IS NOT NULL THEN
        RETURN cell('J', l);
      ELSE
        RETURN 0;
      END IF;



    -- R=SI(Q$52>0;Q22/Q$52;0)
    WHEN c = 'R' AND v >= 1 THEN
      IF cell('Q52', l) > 0 THEN
        RETURN cell('Q', l) / cell('Q52');
      ELSE
        RETURN 0;
      END IF;



    -- S=Q$53*R22
    WHEN c = 'S' AND v >= 1 THEN
      RETURN cell('Q53') * cell('R', l);



    -- T=SI(ET(Q$54=0;HC_autorisees="Oui");Q22-S22;0)
    WHEN c = 'T' AND v >= 1 THEN
      IF cell('Q54', l) = 0 AND NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('Q', l) - cell('S', l);
      ELSE
        RETURN 0;
      END IF;



    -- V=SI($H22="";0;SI(ET($G22=1;$B22<>"D4DAC10000";$D22<>"Référentiel";$C22="Oui");$J22;0))
    WHEN c = 'V' AND v >= 1 THEN
      --SI(ET($G22=1;$B22<>"D4DAC10000";$D22<>"Référentiel";$C22="Oui");$J22;0)
      IF vh.taux_fc = 1 AND (NOT isD4DAC10000(vh.structure_code)) AND vh.volume_horaire_ref_id IS NULL AND vh.structure_code IS NOT NULL THEN
        RETURN cell('J', l);
      ELSE
        RETURN 0;
      END IF;



    -- W=SI(V$52>0;V22/V$52;0)
    WHEN c = 'W' AND v >= 1 THEN
      IF cell('V52') > 0 THEN
        RETURN cell('V', l) / cell('V52');
      ELSE
        RETURN 0;
      END IF;



    -- X=V$53*W22
    WHEN c = 'X' AND v >= 1 THEN
      RETURN cell('V53') * cell('W', l);



    -- Y=SI(ET(V$54=0;HC_autorisees="Oui");V22-X22;0)
    WHEN c = 'Y' AND v >= 1 THEN
      IF cell('V54') = 0 AND NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('V', l) - cell('X', l);
      ELSE
        RETURN 0;
      END IF;



    -- AA=SI($H22="";0;SI(ET($B22="D4DAC10000";$D22<>"Référentiel";$C22="Oui");$J22;0))
    WHEN c = 'AA' AND v >= 1 THEN
      --SI(ET($B22="D4DAC10000";$D22<>"Référentiel";$C22="Oui");$J22;0)
      IF isD4DAC10000(vh.structure_code) AND vh.volume_horaire_ref_id IS NULL AND vh.structure_code IS NOT NULL THEN
        RETURN cell('J', l);
      ELSE
        RETURN 0;
      END IF;



    -- AB=SI(AA$52>0;AA22/AA$52;0)
    WHEN c = 'AB' AND v >= 1 THEN
      IF cell('AA52') > 0 THEN
        RETURN cell('AA', l) / cell('AA52');
      ELSE
        RETURN 0;
      END IF;



    -- AC=AA$53*AB22
    WHEN c = 'AC' AND v >= 1 THEN
      RETURN cell('AA53') * cell('AB', l);



    -- AD=SI(ET(AA$54=0;HC_autorisees="Oui");AA22-AC22;0)
    WHEN c = 'AD' AND v >= 1 THEN
      IF cell('AA54') = 0 AND NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('AA', l) - cell('AC', l);
      ELSE
        RETURN 0;
      END IF;



    -- AF=SI($H22="";0;SI(ET($D22="Référentiel");$J22;0))
    WHEN c = 'AF' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN cell('J', l);
      ELSE
        RETURN 0;
      END IF;



    -- AG=SI(AF$52>0;AF22/AF$52;0)
    WHEN c = 'AG' AND v >= 1 THEN
      IF cell('AF52') > 0 THEN
        RETURN cell('AF', l) / cell('AF52');
      ELSE
        RETURN 0;
      END IF;



    -- AH=AF$53*AG22
    WHEN c = 'AH' AND v >= 1 THEN
      RETURN cell('AF53') * cell('AG', l);



    -- AI=SI(ET(AF$54=0;HC_autorisees="Oui");AF22-AH22;0)
    WHEN c = 'AI' AND v >= 1 THEN
      IF cell('AF54') = 0 AND NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('AF', l) - cell('AH', l);
      ELSE
        RETURN 0;
      END IF;



    -- AK=SI($H22="";0;SI(ET($D22<>"Référentiel";$C22<>"Oui");$J22;0))
    WHEN c = 'AK' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NULL AND vh.structure_code IS NULL THEN
        RETURN cell('J', l);
      ELSE
        RETURN 0;
      END IF;



    -- AL=SI(AK$52>0;AK22/AK$52;0)
    WHEN c = 'AL' AND v >= 1 THEN
      IF cell('AK52') > 0 THEN
        RETURN cell('AK', l) / cell('AK52');
      ELSE
        RETURN 0;
      END IF;



    -- AM=AK$53*AL22
    WHEN c = 'AM' AND v >= 1 THEN
      RETURN cell('AK53') * cell('AL', l);



    -- AN=SI(ET(AK$54=0;HC_autorisees="Oui");AK22-AM22;0)
    WHEN c = 'AN' AND v >= 1 THEN
      IF cell('AK54') = 0 AND NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('AK', l) - cell('AM', l);
      ELSE
        RETURN 0;
      END IF;



    -- AP=($N22+$S22+$AC22+$AM22)*SI(E22="";0;E22)
    WHEN c = 'AP' AND v >= 1 THEN
      RETURN (cell('N', l) + cell('S', l) + cell('AC', l) + cell('AM', l)) * vh.taux_fi;



    -- AQ=($N22+$S22+$AC22+$AM22)*SI(F22="";0;F22)
    WHEN c = 'AQ' AND v >= 1 THEN
      RETURN (cell('N', l) + cell('S', l) + cell('AC', l) + cell('AM', l)) * vh.taux_fa;



    -- AR=($N22+$S22+$X22+$AC22+$AM22)*SI(G22="";0;G22)
    WHEN c = 'AR' AND v >= 1 THEN
      RETURN (cell('N', l) + cell('S', l) + cell('X', l) + cell('AC', l) + cell('AM', l)) * vh.taux_fc;



    -- AS=AH22
    WHEN c = 'AS' AND v >= 1 THEN
      RETURN cell('AH', l);



    -- AT=($O22+$T22+$AD22+$AN22)*SI(E22="";0;E22)
    WHEN c = 'AT' AND v >= 1 THEN
      RETURN (cell('O', l) + cell('T', l) + cell('AD', l) + cell('AN', l)) * vh.taux_fi;



    -- AU=($O22+$T22+$AD22+$AN22)*SI(F22="";0;F22)
    WHEN c = 'AU' AND v >= 1 THEN
      RETURN (cell('O', l) + cell('T', l) + cell('AD', l) + cell('AN', l)) * vh.taux_fa;



    -- AV=($O22+$T22+$Y22+$AD22+$AN22)*SI(G22="";0;G22)
    WHEN c = 'AV' AND v >= 1 THEN
      RETURN (cell('O', l) + cell('T', l) + cell('Y', l) + cell('AD', l) + cell('AN', l)) * vh.taux_fc;



    -- AW=0
    WHEN c = 'AW' AND v >= 1 THEN
      RETURN 0;



    -- AX=AI22
    WHEN c = 'AX' AND v >= 1 THEN
      RETURN cell('AI', l);



    -- L52=SOMME(L22:L51)
    WHEN c = 'L52' AND v >= 1 THEN
      RETURN calcFnc('total','L');



    -- L53=MIN(L52;service_du)
    WHEN c = 'L53' AND v >= 1 THEN
      RETURN LEAST(cell('L52'), i.service_du);



    -- L54=service_du-L53
    WHEN c = 'L54' AND v >= 1 THEN
      RETURN i.service_du - cell('L53');



    -- Q52=SOMME(Q22:Q51)
    WHEN c = 'Q52' AND v >= 1 THEN
      RETURN calcFnc('total','Q');



    -- Q53=MIN(Q52;L54)
    WHEN c = 'Q53' AND v >= 1 THEN
      RETURN LEAST(cell('Q52'), cell('L54'));



    -- Q54=L54-Q53
    WHEN c = 'Q54' AND v >= 1 THEN
      RETURN cell('L54') - cell('Q53');



    -- V52=SOMME(V22:V51)
    WHEN c = 'V52' AND v >= 1 THEN
      RETURN calcFnc('total','V');



    -- V53=MIN(V52;Q54)
    WHEN c = 'V53' AND v >= 1 THEN
      RETURN LEAST(cell('V52'), cell('Q54'));



    -- V54=Q54-V53
    WHEN c = 'V54' AND v >= 1 THEN
      RETURN cell('Q54') - cell('V53');



    -- AA52=SOMME(AA22:AA51)
    WHEN c = 'AA52' AND v >= 1 THEN
      RETURN calcFnc('total','AA');



    -- AA53=MIN(AA52;V54)
    WHEN c = 'AA53' AND v >= 1 THEN
      RETURN LEAST(cell('AA52'), cell('V54'));



    -- AA54=V54-AA53
    WHEN c = 'AA54' AND v >= 1 THEN
      RETURN cell('V54') - cell('AA53');



    -- AF52=SOMME(AF22:AF51)
    WHEN c = 'AF52' AND v >= 1 THEN
      RETURN calcFnc('total','AF');



    -- AF53=MIN(AF52;AA54)
    WHEN c = 'AF53' AND v >= 1 THEN
      RETURN LEAST(cell('AF52'), cell('AA54'));



    -- AF54=AA54-AF53
    WHEN c = 'AF54' AND v >= 1 THEN
      RETURN cell('AA54') - cell('AF53');



    -- AK52=SOMME(AK22:AK51)
    WHEN c = 'AK52' AND v >= 1 THEN
      RETURN calcFnc('total','AK');



    -- AK53=MIN(AK52;AF54)
    WHEN c = 'AK53' AND v >= 1 THEN
      RETURN LEAST(cell('AK52'), cell('AF54'));



    -- AK54=AF54-AK53
    WHEN c = 'AK54' AND v >= 1 THEN
      RETURN cell('AF54') - cell('AK53');



    ELSE
      raise_application_error( -20001, 'La colonne c=' || c || ', l=' || l || ' n''existe pas!');
  END CASE; END;



  PROCEDURE CALCUL_RESULTAT IS
  BEGIN
    feuille.delete;

    -- transmission des résultats aux volumes horaires et volumes horaires référentiel
    FOR l IN 1 .. ose_formule.volumes_horaires.length LOOP
      ose_formule.volumes_horaires.items(l).service_fi               := mainCell('Service FI', 'AP',l);
      ose_formule.volumes_horaires.items(l).service_fa               := mainCell('Service FA', 'AQ',l);
      ose_formule.volumes_horaires.items(l).service_fc               := mainCell('Service FC', 'AR',l);
      ose_formule.volumes_horaires.items(l).service_referentiel      := mainCell('Service référentiel', 'AS',l);
      ose_formule.volumes_horaires.items(l).heures_compl_fi          := mainCell('Heures compl. FI', 'AT',l);
      ose_formule.volumes_horaires.items(l).heures_compl_fa          := mainCell('Heures compl. FA', 'AU',l);
      ose_formule.volumes_horaires.items(l).heures_compl_fc          := mainCell('Heures compl. FC', 'AV',l);
      ose_formule.volumes_horaires.items(l).heures_compl_fc_majorees := mainCell('Heures compl. FC Maj.', 'AW',l);
      ose_formule.volumes_horaires.items(l).heures_compl_referentiel := mainCell('Heures compl. référentiel', 'AX',l);
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

END FORMULE_LYON2;