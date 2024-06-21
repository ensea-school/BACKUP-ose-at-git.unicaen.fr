CREATE OR REPLACE PACKAGE BODY FORMULE_PARIS AS
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



    -- T=SI($H20="Référentiel";0;$AR20+$AX20)
    WHEN c = 'T' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        RETURN cell('AR',l) + cell('AX',l);
      END IF;



    -- U=SI($H20="Référentiel";0;($BD20+$BJ20)*F20)
    WHEN c = 'U' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        RETURN (cell('BD',l) + cell('BJ',l)) * vh.taux_fa;
      END IF;



    -- V=SI($H20="Référentiel";0;($BD20+$BJ20)*G20)
    WHEN c = 'V' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        RETURN (cell('BD',l) + cell('BJ',l)) * vh.taux_fc;
      END IF;



    -- W=SI($H20="Référentiel";$AL20;0)
    WHEN c = 'W' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN cell('AL',l);
      ELSE
        RETURN 0;
      END IF;



    -- X=SI($H20="Référentiel";0;$AT20+$AZ20)
    WHEN c = 'X' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        RETURN cell('AT',l) + cell('AZ',l);
      END IF;



    -- Y=SI($H20="Référentiel";0;($BF20+$BL20)*F20)
    WHEN c = 'Y' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        RETURN (cell('BF',l) + cell('BL',l)) * vh.taux_fa;
      END IF;



    -- Z=SI($H20="Référentiel";0;($BF20+$BL20)*G20)
    WHEN c = 'Z' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        RETURN (cell('BF',l) + cell('BL',l)) * vh.taux_fc;
      END IF;



    -- AA=0
    WHEN c = 'AA' AND v >= 1 THEN
      RETURN 0;



    -- AB=SI($H20="Référentiel";$AN20;0)
    WHEN c = 'AB' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN cell('AN',l);
      ELSE
        RETURN 0;
      END IF;



    -- AD=SI(ESTERREUR(I20);1;I20)
    WHEN c = 'AD' AND v >= 1 THEN
      RETURN vh.taux_service_du;



    -- AE=SI(ESTERREUR(J20);1;J20)
    WHEN c = 'AE' AND v >= 1 THEN
      RETURN vh.taux_service_compl;



    -- AF=SI($H20="Référentiel";0;SI($E20>0;1;0))
    WHEN c = 'AF' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        IF vh.taux_fi > 0 THEN
          RETURN 1;
        ELSE
          RETURN 0;
        END IF;
      END IF;



    -- AG=SI($H20="Référentiel";0;SI($E20>0;0;F20/($F20+$G20)))
    WHEN c = 'AG' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        IF vh.taux_fi > 0 THEN
          RETURN 0;
        ELSE
          RETURN vh.taux_fa / (vh.taux_fa + vh.taux_fc);
        END IF;
      END IF;



    -- AH=SI($H20="Référentiel";0;SI($E20>0;0;G20/($F20+$G20)))
    WHEN c = 'AH' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        IF vh.taux_fi > 0 THEN
          RETURN 0;
        ELSE
          RETURN vh.taux_fc / (vh.taux_fa + vh.taux_fc);
        END IF;
      END IF;



    -- AJ=SI(ET($D20="Oui";$H20="Référentiel");$M20*$AD20;0)
    WHEN c = 'AJ' AND v >= 1 THEN
      IF vh.service_statutaire AND vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN vh.heures * cell('AD',l);
      ELSE
        RETURN 0;
      END IF;



    -- AK=SI(AK$15>0;AJ20/AK$15;0)
    WHEN c = 'AK' AND v >= 1 THEN
      IF cell('AK15') > 0 THEN
        RETURN cell('AJ',l) / cell('AK15');
      ELSE
        RETURN 0;
      END IF;



    -- AL=AK$16*AK20
    WHEN c = 'AL' AND v >= 1 THEN
      RETURN cell('AK16') * cell('AK',l);



    -- AM=SI(AK$17=0;(AJ20-AL20)/$AD20;0)
    WHEN c = 'AM' AND v >= 1 THEN
      IF cell('AK17') = 0 THEN
        RETURN (cell('AJ',l) - cell('AL',l)) / cell('AD', l);
      ELSE
        RETURN 0;
      END IF;



    -- AN=SI(i_depassement_service_du_sans_hc="Non";AM20*$AE20;0)
    WHEN c = 'AN' AND v >= 1 THEN
      IF NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('AM',l) * cell('AE',l);
      ELSE
        RETURN 0;
      END IF;



    -- AP=SI(ET($D20="Oui";$H20<>"Référentiel";$A20=i_structure_code;$E20>0);$M20*$AD20;0)
    WHEN c = 'AP' AND v >= 1 THEN
      IF vh.service_statutaire AND vh.volume_horaire_ref_id IS NULL AND vh.structure_is_affectation AND vh.taux_fi > 0 THEN
        RETURN vh.heures * cell('AD',l);
      ELSE
        RETURN 0;
      END IF;



    -- AQ=SI(AQ$15>0;AP20/AQ$15;0)
    WHEN c = 'AQ' AND v >= 1 THEN
      IF cell('AQ15') > 0 THEN
        RETURN cell('AP',l) / cell('AQ15');
      ELSE
        RETURN 0;
      END IF;



    -- AR=AQ$16*AQ20
    WHEN c = 'AR' AND v >= 1 THEN
      RETURN cell('AQ16') * cell('AQ',l);



    -- AS=SI(AQ$17=0;(AP20-AR20)/$AD20;0)
    WHEN c = 'AS' AND v >= 1 THEN
      IF cell('AQ17') = 0 THEN
        RETURN (cell('AP',l) - cell('AR',l)) / cell('AD', l);
      ELSE
        RETURN 0;
      END IF;



    -- AT=SI(i_depassement_service_du_sans_hc="Non";AS20*$AE20;0)
    WHEN c = 'AT' AND v >= 1 THEN
      IF NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('AS',l) * cell('AE',l);
      ELSE
        RETURN 0;
      END IF;



    -- AV=SI(ET($D20="Oui";$H20<>"Référentiel";$A20<>i_structure_code;$E20>0);$M20*$AD20;0)
    WHEN c = 'AV' AND v >= 1 THEN
      IF vh.service_statutaire AND vh.volume_horaire_ref_id IS NULL AND NOT vh.structure_is_affectation AND vh.taux_fi > 0 THEN
        RETURN vh.heures * cell('AD',l);
      ELSE
        RETURN 0;
      END IF;



    -- AW=SI(AW$15>0;AV20/AW$15;0)
    WHEN c = 'AW' AND v >= 1 THEN
      IF cell('AW15') > 0 THEN
        RETURN cell('AV',l) / cell('AW15');
      ELSE
        RETURN 0;
      END IF;



    -- AX=AW$16*AW20
    WHEN c = 'AX' AND v >= 1 THEN
      RETURN cell('AW16') * cell('AW',l);



    -- AY=SI(AW$17=0;(AV20-AX20)/$AD20;0)
    WHEN c = 'AY' AND v >= 1 THEN
      IF cell('AW17') = 0 THEN
        RETURN (cell('AV',l) - cell('AX',l)) / cell('AD', l);
      ELSE
        RETURN 0;
      END IF;



    -- AZ=SI(i_depassement_service_du_sans_hc="Non";AY20*$AE20;0)
    WHEN c = 'AZ' AND v >= 1 THEN
      IF NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('AY',l) * cell('AE',l);
      ELSE
        RETURN 0;
      END IF;



    -- BB=SI(ET($D20="Oui";$H20<>"Référentiel";$A20=i_structure_code;$E20=0);$M20*$AD20;0)
    WHEN c = 'BB' AND v >= 1 THEN
      IF vh.service_statutaire AND vh.volume_horaire_ref_id IS NULL AND vh.structure_is_affectation AND vh.taux_fi = 0 THEN
        RETURN vh.heures * cell('AD',l);
      ELSE
        RETURN 0;
      END IF;



    -- BC=SI(BC$15>0;BB20/BC$15;0)
    WHEN c = 'BC' AND v >= 1 THEN
      IF cell('BC15') > 0 THEN
        RETURN cell('BB',l) / cell('BC15');
      ELSE
        RETURN 0;
      END IF;



    -- BD=BC$16*BC20
    WHEN c = 'BD' AND v >= 1 THEN
      RETURN cell('BC16') * cell('BC',l);



    -- BE=SI(BC$17=0;(BB20-BD20)/$AD20;0)
    WHEN c = 'BE' AND v >= 1 THEN
      IF cell('BC17') = 0 THEN
        RETURN (cell('BB',l) - cell('BD',l)) / cell('AD', l);
      ELSE
        RETURN 0;
      END IF;



    -- BF=SI(i_depassement_service_du_sans_hc="Non";BE20*$AE20;0)
    WHEN c = 'BF' AND v >= 1 THEN
      IF NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('BE',l) * cell('AE',l);
      ELSE
        RETURN 0;
      END IF;



    -- BH=SI(ET($D20="Oui";$H20<>"Référentiel";$A20<>i_structure_code;$E20=0);$M20*$AD20;0)
    WHEN c = 'BH' AND v >= 1 THEN
      IF vh.service_statutaire AND vh.volume_horaire_ref_id IS NULL AND NOT vh.structure_is_affectation AND vh.taux_fi = 0 THEN
        RETURN vh.heures * cell('AD',l);
      ELSE
        RETURN 0;
      END IF;



    -- BI=SI(BI$15>0;BH20/BI$15;0)
    WHEN c = 'BI' AND v >= 1 THEN
      IF cell('BI15') > 0 THEN
        RETURN cell('BH',l) / cell('BI15');
      ELSE
        RETURN 0;
      END IF;



    -- BJ=BI$16*BI20
    WHEN c = 'BJ' AND v >= 1 THEN
      RETURN cell('BI16') * cell('BI',l);



    -- BK=SI(BI$17=0;(BH20-BJ20)/$AD20;0)
    WHEN c = 'BK' AND v >= 1 THEN
      IF cell('BI17') = 0 THEN
        RETURN (cell('BH',l) - cell('BJ',l)) / cell('AD',l);
      ELSE
        RETURN 0;
      END IF;



    -- BL=SI(i_depassement_service_du_sans_hc="Non";BK20*$AE20;0)
    WHEN c = 'BL' AND v >= 1 THEN
      IF NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('BK',l) * cell('AE',l);
      ELSE
        RETURN 0;
      END IF;



    -- AK15=SOMME(AJ:AJ)
    WHEN c = 'AK15' AND v >= 1 THEN
      RETURN calcFnc('total', 'AJ');



    -- AK16=MIN(AK15;i_service_du)
    WHEN c = 'AK16' AND v >= 1 THEN
      RETURN LEAST(cell('AK15'), i.service_du);



    -- AK17=i_service_du-AK16
    WHEN c = 'AK17' AND v >= 1 THEN
      RETURN i.service_du - cell('AK16');



    -- AQ15=SOMME(AP:AP)
    WHEN c = 'AQ15' AND v >= 1 THEN
      RETURN calcFnc('total', 'AP');



    -- AQ16=MIN(AQ15;AK17)
    WHEN c = 'AQ16' AND v >= 1 THEN
      RETURN LEAST(cell('AQ15'), cell('AK17'));



    -- AQ17=AK17-AQ16
    WHEN c = 'AQ17' AND v >= 1 THEN
      RETURN cell('AK17') - cell('AQ16');



    -- AW15=SOMME(AV:AV)
    WHEN c = 'AW15' AND v >= 1 THEN
      RETURN calcFnc('total', 'AV');



    -- AW16=MIN(AW15;AQ17)
    WHEN c = 'AW16' AND v >= 1 THEN
      RETURN LEAST(cell('AW15'), cell('AQ17'));



    -- AW17=AQ17-AW16
    WHEN c = 'AW17' AND v >= 1 THEN
      RETURN cell('AQ17') - cell('AW16');



    -- BC15=SOMME(BB:BB)
    WHEN c = 'BC15' AND v >= 1 THEN
      RETURN calcFnc('total', 'BB');



    -- BC16=MIN(BC15;AW17)
    WHEN c = 'BC16' AND v >= 1 THEN
      RETURN LEAST(cell('BC15'), cell('AW17'));



    -- BC17=AW17-BC16
    WHEN c = 'BC17' AND v >= 1 THEN
      RETURN cell('AW17') - cell('BC16');



    -- BI15=SOMME(BH:BH)
    WHEN c = 'BI15' AND v >= 1 THEN
      RETURN calcFnc('total', 'BH');



    -- BI16=MIN(BI15;BC17)
    WHEN c = 'BI16' AND v >= 1 THEN
      RETURN LEAST(cell('BI15'), cell('BC17'));



    -- BI17=BC17-BI16
    WHEN c = 'BI17' AND v >= 1 THEN
      RETURN cell('BC17') - cell('BI16');



    ELSE
      OSE_TEST.echo(c);
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

END FORMULE_PARIS;