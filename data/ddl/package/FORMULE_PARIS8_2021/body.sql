CREATE OR REPLACE PACKAGE BODY FORMULE_PARIS8_2021 AS
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

    feuille(c).cells(l).enCalcul := TRUE;
    val := calcCell( c, l );
    IF ose_formule.debug_actif THEN
      dbgCell( c, l, val );
    END IF;
    feuille(c).cells(l).valeur := val;
    feuille(c).cells(l).enCalcul := FALSE;

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



    -- AT15=SOMME(AS:AS)
    WHEN c = 'AT15' AND v >= 1 THEN
      RETURN calcFnc('total', 'AS');



    -- AZ15=SOMME(AY:AY)
    WHEN c = 'AZ15' AND v >= 1 THEN
      RETURN calcFnc('total', 'AY');



    -- BF15=SOMME(BE:BE)
    WHEN c = 'BF15' AND v >= 1 THEN
      RETURN calcFnc('total', 'BE');



    -- BL15=SOMME(BK:BK)
    WHEN c = 'BL15' AND v >= 1 THEN
      RETURN calcFnc('total', 'BK');



    -- AH16=MIN(AH15;i_service_du)
    WHEN c = 'AH16' AND v >= 1 THEN
      RETURN LEAST(cell('AH15'), i.service_du);



    -- AN16=MIN(AN15;AH17)
    WHEN c = 'AN16' AND v >= 1 THEN
      RETURN LEAST(cell('AN15'), cell('AH17'));



    -- AT16=MIN(AT15;AN17)
    WHEN c = 'AT16' AND v >= 1 THEN
      RETURN LEAST(cell('AT15'), cell('AN17'));



    -- AZ16=MIN(AZ15;AT17)
    WHEN c = 'AZ16' AND v >= 1 THEN
      RETURN LEAST(cell('AZ15'), cell('AT17'));



    -- BF16=MIN(BF15;AZ17)
    WHEN c = 'BF16' AND v >= 1 THEN
      RETURN LEAST(cell('BF15'), cell('AZ17'));



    -- BL16=MIN(BL15;BF17)
    WHEN c = 'BL16' AND v >= 1 THEN
      RETURN LEAST(cell('BL15'), cell('BF17'));



    -- AH17=i_service_du-AH16
    WHEN c = 'AH17' AND v >= 1 THEN
      RETURN i.service_du - cell('AH16');



    -- AN17=AH17-AN16
    WHEN c = 'AN17' AND v >= 1 THEN
      RETURN cell('AH17') - cell('AN16');



    -- AT17=AN17-AT16
    WHEN c = 'AT17' AND v >= 1 THEN
      RETURN cell('AN17') - cell('AT16');



    -- AZ17=AT17-AZ16
    WHEN c = 'AZ17' AND v >= 1 THEN
      RETURN cell('AT17') - cell('AZ16');



    -- BF17=AZ17-BF16
    WHEN c = 'BF17' AND v >= 1 THEN
      RETURN cell('AZ17') - cell('BF16');



    -- BL17=BF17-BL16
    WHEN c = 'BL17' AND v >= 1 THEN
      RETURN cell('BF17') - cell('BL16');



    -- T=SI($H20="Référentiel";0;($AI20+$AU20+$BG20)*E20)
    WHEN c = 'T' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        RETURN (cell('AI',l) + cell('AU',l) + cell('BG',l)) * vh.taux_fi;
      END IF;



    -- U=SI($H20="Référentiel";0;($AI20+$AU20+$BG20)*F20)
    WHEN c = 'U' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        RETURN (cell('AI',l) + cell('AU',l) + cell('BG',l)) * vh.taux_fa;
      END IF;



    -- V=SI($H20="Référentiel";0;($AI20+$AU20+$BG20)*G20)
    WHEN c = 'V' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        RETURN (cell('AI',l) + cell('AU',l) + cell('BG',l)) * vh.taux_fc;
      END IF;



    -- W=SI($H20="Référentiel";$AO20+$BA20+$BM20;0)
    WHEN c = 'W' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN cell('AO',l) + cell('BA',l) + cell('BM',l);
      ELSE
        RETURN 0;
      END IF;



    -- X=SI($H20="Référentiel";0;($AK20+$AW20+$BI20)*E20)
    WHEN c = 'X' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        RETURN (cell('AK',l) + cell('AW',l) + cell('BI',l)) * vh.taux_fi;
      END IF;



    -- Y=SI($H20="Référentiel";0;($AK20+$AW20+$BI20)*F20)
    WHEN c = 'Y' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        RETURN (cell('AK',l) + cell('AW',l) + cell('BI',l)) * vh.taux_fa;
      END IF;



    -- Z=SI($H20="Référentiel";0;($AK20+$AW20+$BI20)*G20)
    WHEN c = 'Z' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        RETURN (cell('AK',l) + cell('AW',l) + cell('BI',l)) * vh.taux_fc;
      END IF;



    -- AA=0
    WHEN c = 'AA' AND v >= 1 THEN
      RETURN 0;



    -- AB=SI($H20="Référentiel";$AQ20+$BC20+$BO20;0)
    WHEN c = 'AB' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN cell('AQ',l) + cell('BC',l) + cell('BO',l);
      ELSE
        RETURN 0;
      END IF;



    -- AD=SI(ESTERREUR(I20);1;I20)
    WHEN c = 'AD' AND v >= 1 THEN
      RETURN vh.taux_service_du;



    -- AE=SI(ESTERREUR(J20);1;J20)
    WHEN c = 'AE' AND v >= 1 THEN
      RETURN vh.taux_service_compl;



    -- AG=SI($H20="Référentiel";0;SI(STXT($N20;1;4)="PRIO";$M20*$AD20;0))
    WHEN c = 'AG' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        IF UPPER(SUBSTR(vh.param_1, 1, 4)) = 'PRIO' THEN
          RETURN vh.heures * cell('AD', l);
        ELSE
          RETURN 0;
        END IF;
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



    -- AM=SI(ET($H20="Référentiel";$A20=$K$10);$M20*$AD20;0)
    WHEN c = 'AM' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL AND vh.structure_is_univ THEN
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



    -- AS=SI(ET($H20<>"Référentiel";$A20=i_structure_code;STXT($N20;1;4)<>"PRIO");$M20*$AD20;0)
    WHEN c = 'AS' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NULL AND vh.structure_is_affectation AND UPPER(SUBSTR(vh.param_1, 1, 4)) <> 'PRIO' THEN
        RETURN vh.heures * cell('AD',l);
      ELSE
        RETURN 0;
      END IF;



    -- AT=SI(AT$15>0;AS20/AT$15;0)
    WHEN c = 'AT' AND v >= 1 THEN
      IF cell('AT15') > 0 THEN
        RETURN cell('AS',l) / cell('AT15');
      ELSE
        RETURN 0;
      END IF;



    -- AU=AT$16*AT20
    WHEN c = 'AU' AND v >= 1 THEN
      RETURN cell('AT16') * cell('AT',l);



    -- AV=SI(AT$17=0;(AS20-AU20)/$AD20;0)
    WHEN c = 'AV' AND v >= 1 THEN
      IF cell('AT17') = 0 THEN
        RETURN (cell('AS',l) - cell('AU',l)) / cell('AD', l);
      ELSE
        RETURN 0;
      END IF;



    -- AW=SI(i_depassement_service_du_sans_hc="Non";AV20*$AE20;0)
    WHEN c = 'AW' AND v >= 1 THEN
      IF NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('AV',l) * cell('AE',l);
      ELSE
        RETURN 0;
      END IF;



    -- AY=SI(ET($H20="Référentiel";$A20=i_structure_code);$M20*$AD20;0)
    WHEN c = 'AY' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL AND vh.structure_is_affectation THEN
        RETURN vh.heures * cell('AD',l);
      ELSE
        RETURN 0;
      END IF;



    -- AZ=SI(AZ$15>0;AY20/AZ$15;0)
    WHEN c = 'AZ' AND v >= 1 THEN
      IF cell('AZ15') > 0 THEN
        RETURN cell('AY',l) / cell('AZ15');
      ELSE
        RETURN 0;
      END IF;



    -- BA=AZ$16*AZ20
    WHEN c = 'BA' AND v >= 1 THEN
      RETURN cell('AZ16') * cell('AZ',l);



    -- BB=SI(AZ$17=0;(AY20-BA20)/$AD20;0)
    WHEN c = 'BB' AND v >= 1 THEN
      IF cell('AZ17') = 0 THEN
        RETURN (cell('AY',l) - cell('BA',l)) / cell('AD', l);
      ELSE
        RETURN 0;
      END IF;



    -- BC=SI(i_depassement_service_du_sans_hc="Non";BB20*$AE20;0)
    WHEN c = 'BC' AND v >= 1 THEN
      IF NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('BB',l) * cell('AE',l);
      ELSE
        RETURN 0;
      END IF;



    -- BE=SI(ET($H20<>"Référentiel";$A20<>i_structure_code;STXT($N20;1;4)<>"PRIO");$M20*$AD20;0)
    WHEN c = 'BE' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NULL AND NOT vh.structure_is_affectation AND UPPER(SUBSTR(vh.param_1, 1, 4)) <> 'PRIO' THEN
        RETURN vh.heures * cell('AD',l);
      ELSE
        RETURN 0;
      END IF;



    -- BF=SI(BF$15>0;BE20/BF$15;0)
    WHEN c = 'BF' AND v >= 1 THEN
      IF cell('BF15') > 0 THEN
        RETURN cell('BE',l) / cell('BF15');
      ELSE
        RETURN 0;
      END IF;



    -- BG=BF$16*BF20
    WHEN c = 'BG' AND v >= 1 THEN
      RETURN cell('BF16') * cell('BF',l);



    -- BH=SI(BF$17=0;(BE20-BG20)/$AD20;0)
    WHEN c = 'BH' AND v >= 1 THEN
      IF cell('BF17') = 0 THEN
        RETURN (cell('BE',l) - cell('BG',l)) / cell('AD', l);
      ELSE
        RETURN 0;
      END IF;



    -- BI=SI(i_depassement_service_du_sans_hc="Non";BH20*$AE20;0)
    WHEN c = 'BI' AND v >= 1 THEN
      IF NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('BH',l) * cell('AE',l);
      ELSE
        RETURN 0;
      END IF;



    -- BK=SI(ET($H20="Référentiel";$A20<>i_structure_code;$A20<>$K$10);$M20*$AD20;0)
    WHEN c = 'BK' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL AND NOT vh.structure_is_affectation AND NOT vh.structure_is_univ THEN
        RETURN vh.heures * cell('AD',l);
      ELSE
        RETURN 0;
      END IF;



    -- BL=SI(BL$15>0;BK20/BL$15;0)
    WHEN c = 'BL' AND v >= 1 THEN
      IF cell('BL15') > 0 THEN
        RETURN cell('BK',l) / cell('BL15');
      ELSE
        RETURN 0;
      END IF;



    -- BM=BL$16*BL20
    WHEN c = 'BM' AND v >= 1 THEN
      RETURN cell('BL16') * cell('BL',l);



    -- BN=SI(BL$17=0;(BK20-BM20)/$AD20;0)
    WHEN c = 'BN' AND v >= 1 THEN
      IF cell('BL17') = 0 THEN
        RETURN (cell('BK',l) - cell('BM',l)) / cell('AD', l);
      ELSE
        RETURN 0;
      END IF;



    -- BO=SI(i_depassement_service_du_sans_hc="Non";BN20*$AE20;0)
    WHEN c = 'BO' AND v >= 1 THEN
      IF NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('BN',l) * cell('AE',l);
      ELSE
        RETURN 0;
      END IF;



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
      ep.code param_1,
      NULL param_2,
      NULL param_3,
      NULL param_4,
      NULL param_5
    FROM
      V_FORMULE_VOLUME_HORAIRE fvh
      LEFT JOIN service s ON s.id = fvh.service_id
      LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
    ORDER BY
      ordre';
  END;

END FORMULE_PARIS8_2021;