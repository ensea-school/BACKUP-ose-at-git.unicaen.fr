CREATE OR REPLACE PACKAGE BODY FORMULE_UNICAEN AS
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



    -- BR15=SOMME(BQ:BQ)
    WHEN c = 'BR15' AND v >= 1 THEN
      RETURN calcFnc('total', 'BQ');



    -- BX15=SOMME(BW:BW)
    WHEN c = 'BX15' AND v >= 1 THEN
      RETURN calcFnc('total', 'BW');



    -- CD15=SOMME(CC:CC)
    WHEN c = 'CD15' AND v >= 1 THEN
      RETURN calcFnc('total', 'CC');



    -- CJ15=SOMME(CI:CI)
    WHEN c = 'CJ15' AND v >= 1 THEN
      RETURN calcFnc('total', 'CI');



    -- CP15=SOMME(CO:CO)
    WHEN c = 'CP15' AND v >= 1 THEN
      RETURN calcFnc('total', 'CO');



    -- CV15=SOMME(CU:CU)
    WHEN c = 'CV15' AND v >= 1 THEN
      RETURN calcFnc('total', 'CU');



    -- DB15=SOMME(DA:DA)
    WHEN c = 'DB15' AND v >= 1 THEN
      RETURN calcFnc('total', 'DA');



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



    -- BR16=MIN(BR15;BL17)
    WHEN c = 'BR16' AND v >= 1 THEN
      RETURN LEAST(cell('BR15'), cell('BL17'));



    -- BX16=MIN(BX15;BR17)
    WHEN c = 'BX16' AND v >= 1 THEN
      RETURN LEAST(cell('BX15'), cell('BR17'));



    -- CD16=MIN(CD15;BX17)
    WHEN c = 'CD16' AND v >= 1 THEN
      RETURN LEAST(cell('CD15'), cell('BX17'));



    -- CJ16=MIN(CJ15;CD17)
    WHEN c = 'CJ16' AND v >= 1 THEN
      RETURN LEAST(cell('CJ15'), cell('CD17'));



    -- CP16=MIN(CP15;CJ17)
    WHEN c = 'CP16' AND v >= 1 THEN
      RETURN LEAST(cell('CP15'), cell('CJ17'));



    -- CV16=MIN(CV15;CP17)
    WHEN c = 'CV16' AND v >= 1 THEN
      RETURN LEAST(cell('CV15'), cell('CP17'));



    -- DB16=MIN(DB15;CV17)
    WHEN c = 'DB16' AND v >= 1 THEN
      RETURN LEAST(cell('DB15'), cell('CV17'));



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



    -- BR17=BL17-BR16
    WHEN c = 'BR17' AND v >= 1 THEN
      RETURN cell('BL17') - cell('BR16');



    -- BX17=BR17-BX16
    WHEN c = 'BX17' AND v >= 1 THEN
      RETURN cell('BR17') - cell('BX16');



    -- CD17=BX17-CD16
    WHEN c = 'CD17' AND v >= 1 THEN
      RETURN cell('BX17') - cell('CD16');



    -- CJ17=CD17-CJ16
    WHEN c = 'CJ17' AND v >= 1 THEN
      RETURN cell('CD17') - cell('CJ16');



    -- CP17=CJ17-CP16
    WHEN c = 'CP17' AND v >= 1 THEN
      RETURN cell('CJ17') - cell('CP16');



    -- CV17=CP17-CV16
    WHEN c = 'CV17' AND v >= 1 THEN
      RETURN cell('CP17') - cell('CV16');



    -- DB17=CV17-DB16
    WHEN c = 'DB17' AND v >= 1 THEN
      RETURN cell('CV17') - cell('DB16');



    -- T=SI($H20="Référentiel";0;$AI20+$AO20+$AU20+$BA20)
    WHEN c = 'T' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        RETURN cell('AI',l) + cell('AO',l) + cell('AU',l) + cell('BA',l);
      END IF;



    -- U=SI($H20="Référentiel";0;$BG20+$BM20+$BS20+$BY20)
    WHEN c = 'U' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        RETURN cell('BG',l) + cell('BM',l) + cell('BS',l) + cell('BY',l);
      END IF;



    -- V=SI($H20="Référentiel";0;$CE20+$CK20)
    WHEN c = 'V' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        RETURN cell('CE',l) + cell('CK',l);
      END IF;



    -- W=SI($H20="Référentiel";$CQ20+$CW20+$DC20;0)
    WHEN c = 'W' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN cell('CQ',l) + cell('CW',l) + cell('DC',l);
      ELSE
        RETURN 0;
      END IF;



    -- X=SI($H20="Référentiel";0;$AK20+$AQ20+$AW20+$BC20)
    WHEN c = 'X' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        RETURN cell('AK',l) + cell('AQ',l) + cell('AW',l) + cell('BC',l);
      END IF;



    -- Y=SI($H20="Référentiel";0;$BI20+$BO20+$BU20+$CA20)
    WHEN c = 'Y' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        RETURN cell('BI',l) + cell('BO',l) + cell('BU',l) + cell('CA',l);
      END IF;



    -- Z=SI($H20="Référentiel";0;SI($L20=1;$CG20+$CM20;0))
    WHEN c = 'Z' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        IF vh.ponderation_service_compl = 1 THEN
          RETURN cell('CG', l) + cell('CM', l);
        ELSE
          RETURN 0;
        END IF;
      END IF;



    -- AA=SI($H20="Référentiel";0;SI($L20=1;0;($CG20+$CM20)*$L20))
    WHEN c = 'AA' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        IF vh.ponderation_service_compl = 1 THEN
          RETURN 0;
        ELSE
          RETURN (cell('CG', l) + cell('CM', l)) * vh.ponderation_service_compl;
        END IF;
      END IF;



    -- AB=SI($H20="Référentiel";$CS20+$CY20+$DE20;0)
    WHEN c = 'AB' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN cell('CS',l) + cell('CY',l) + cell('DE',l);
      ELSE
        RETURN 0;
      END IF;



    -- AD=SI(ESTERREUR(I20);1;I20)
    -- AD=SI(ESTERREUR(I20);1;SI(H20="TP";1;I20))
    WHEN c = 'AD' AND v >= 1 THEN
      IF vh.type_intervention_code = 'TP' THEN
        RETURN 1;
      ELSE
        RETURN vh.taux_service_du;
      END IF;



    -- AE=SI(ESTERREUR(J20);1;J20)
    WHEN c = 'AE' AND v >= 1 THEN
      RETURN vh.taux_service_compl;



    -- AG=SI(ET($D20="Oui";$H20<>"Référentiel";$H20<>"EAD";$A20=i_structure_code);$M20*$E20*$AD20;0)
    WHEN c = 'AG' AND v >= 1 THEN
      IF vh.service_statutaire AND vh.volume_horaire_ref_id IS NULL AND vh.type_intervention_code <> 'EAD' AND vh.structure_is_affectation THEN
        RETURN vh.heures * vh.taux_fi * cell('AD',l);
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



    -- AM=SI(ET($D20="Oui";$H20<>"Référentiel";$H20<>"EAD";$A20<>i_structure_code);$M20*$E20*$AD20;0)
    WHEN c = 'AM' AND v >= 1 THEN
      IF vh.service_statutaire AND vh.volume_horaire_ref_id IS NULL AND vh.type_intervention_code <> 'EAD' AND NOT vh.structure_is_affectation THEN
        RETURN vh.heures * vh.taux_fi * cell('AD',l);
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



    -- AS=SI(ET($D20="Oui";$H20="EAD";$A20=i_structure_code);$M20*$E20*$AD20;0)
    WHEN c = 'AS' AND v >= 1 THEN
      IF vh.service_statutaire AND vh.type_intervention_code = 'EAD' AND vh.structure_is_affectation THEN
        RETURN vh.heures * vh.taux_fi * cell('AD',l);
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



    -- AY=SI(ET($D20="Oui";$H20="EAD";$A20<>i_structure_code);$M20*$E20*$AD20;0)
    WHEN c = 'AY' AND v >= 1 THEN
      IF vh.service_statutaire AND vh.type_intervention_code = 'EAD' AND NOT vh.structure_is_affectation THEN
        RETURN vh.heures * vh.taux_fi * cell('AD',l);
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



    -- BE=SI(ET($D20="Oui";$H20<>"Référentiel";$H20<>"EAD";$A20=i_structure_code);$M20*$F20*$AD20;0)
    WHEN c = 'BE' AND v >= 1 THEN
      IF vh.service_statutaire AND vh.volume_horaire_ref_id IS NULL AND vh.type_intervention_code <> 'EAD' AND vh.structure_is_affectation THEN
        RETURN vh.heures * vh.taux_fa * cell('AD',l);
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



    -- BK=SI(ET($D20="Oui";$H20<>"Référentiel";$H20<>"EAD";$A20<>i_structure_code);$M20*$F20*$AD20;0)
    WHEN c = 'BK' AND v >= 1 THEN
      IF vh.service_statutaire AND vh.volume_horaire_ref_id IS NULL AND vh.type_intervention_code <> 'EAD' AND NOT vh.structure_is_affectation THEN
        RETURN vh.heures * vh.taux_fa * cell('AD',l);
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



    -- BQ=SI(ET($D20="Oui";$H20="EAD";$A20=i_structure_code);$M20*$F20*$AD20;0)
    WHEN c = 'BQ' AND v >= 1 THEN
      IF vh.service_statutaire AND vh.type_intervention_code = 'EAD' AND vh.structure_is_affectation THEN
        RETURN vh.heures * vh.taux_fa * cell('AD',l);
      ELSE
        RETURN 0;
      END IF;



    -- BR=SI(BR$15>0;BQ20/BR$15;0)
    WHEN c = 'BR' AND v >= 1 THEN
      IF cell('BR15') > 0 THEN
        RETURN cell('BQ',l) / cell('BR15');
      ELSE
        RETURN 0;
      END IF;



    -- BS=BR$16*BR20
    WHEN c = 'BS' AND v >= 1 THEN
      RETURN cell('BR16') * cell('BR',l);



    -- BT=SI(BR$17=0;(BQ20-BS20)/$AD20;0)
    WHEN c = 'BT' AND v >= 1 THEN
      IF cell('BR17') = 0 THEN
        RETURN (cell('BQ',l) - cell('BS',l)) / cell('AD', l);
      ELSE
        RETURN 0;
      END IF;



    -- BU=SI(i_depassement_service_du_sans_hc="Non";BT20*$AE20;0)
    WHEN c = 'BU' AND v >= 1 THEN
      IF NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('BT',l) * cell('AE',l);
      ELSE
        RETURN 0;
      END IF;



    -- BW=SI(ET($D20="Oui";$H20="EAD";$A20<>i_structure_code);$M20*$F20*$AD20;0)
    WHEN c = 'BW' AND v >= 1 THEN
      IF vh.service_statutaire AND vh.type_intervention_code = 'EAD' AND NOT vh.structure_is_affectation THEN
        RETURN vh.heures * vh.taux_fa * cell('AD',l);
      ELSE
        RETURN 0;
      END IF;



    -- BX=SI(BX$15>0;BW20/BX$15;0)
    WHEN c = 'BX' AND v >= 1 THEN
      IF cell('BX15') > 0 THEN
        RETURN cell('BW',l) / cell('BX15');
      ELSE
        RETURN 0;
      END IF;



    -- BY=BX$16*BX20
    WHEN c = 'BY' AND v >= 1 THEN
      RETURN cell('BX16') * cell('BX',l);



    -- BZ=SI(BX$17=0;(BW20-BY20)/$AD20;0)
    WHEN c = 'BZ' AND v >= 1 THEN
      IF cell('BX17') = 0 THEN
        RETURN (cell('BW',l) - cell('BY',l)) / cell('AD', l);
      ELSE
        RETURN 0;
      END IF;



    -- CA=SI(i_depassement_service_du_sans_hc="Non";BZ20*$AE20;0)
    WHEN c = 'CA' AND v >= 1 THEN
      IF NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('BZ',l) * cell('AE',l);
      ELSE
        RETURN 0;
      END IF;



    -- CC=SI(ET($D20="Oui";$H20<>"Référentiel";$A20=i_structure_code);$M20*$G20*$AD20;0)
    WHEN c = 'CC' AND v >= 1 THEN
      IF vh.service_statutaire AND vh.volume_horaire_ref_id IS NULL AND vh.structure_is_affectation THEN
        RETURN vh.heures * vh.taux_fc * cell('AD',l);
      ELSE
        RETURN 0;
      END IF;



    -- CD=SI(CD$15>0;CC20/CD$15;0)
    WHEN c = 'CD' AND v >= 1 THEN
      IF cell('CD15') > 0 THEN
        RETURN cell('CC',l) / cell('CD15');
      ELSE
        RETURN 0;
      END IF;



    -- CE=CD$16*CD20
    WHEN c = 'CE' AND v >= 1 THEN
      RETURN cell('CD16') * cell('CD',l);



    -- CF=SI(CD$17=0;(CC20-CE20)/$AD20;0)
    WHEN c = 'CF' AND v >= 1 THEN
      IF cell('CD17') = 0 THEN
        RETURN (cell('CC',l) - cell('CE',l)) / cell('AD', l);
      ELSE
        RETURN 0;
      END IF;



    -- CG=SI(i_depassement_service_du_sans_hc="Non";CF20*$AE20;0)
    WHEN c = 'CG' AND v >= 1 THEN
      IF NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('CF',l) * cell('AE',l);
      ELSE
        RETURN 0;
      END IF;



    -- CI=SI(ET($D20="Oui";$H20<>"Référentiel";$A20<>i_structure_code);$M20*$G20*$AD20;0)
    WHEN c = 'CI' AND v >= 1 THEN
      IF vh.service_statutaire AND vh.volume_horaire_ref_id IS NULL AND NOT vh.structure_is_affectation THEN
        RETURN vh.heures * vh.taux_fc * cell('AD',l);
      ELSE
        RETURN 0;
      END IF;



    -- CJ=SI(CJ$15>0;CI20/CJ$15;0)
    WHEN c = 'CJ' AND v >= 1 THEN
      IF cell('CJ15') > 0 THEN
        RETURN cell('CI',l) / cell('CJ15');
      ELSE
        RETURN 0;
      END IF;



    -- CK=CJ$16*CJ20
    WHEN c = 'CK' AND v >= 1 THEN
      RETURN cell('CJ16') * cell('CJ',l);



    -- CL=SI(CJ$17=0;(CI20-CK20)/$AD20;0)
    WHEN c = 'CL' AND v >= 1 THEN
      IF cell('CJ17') = 0 THEN
        RETURN (cell('CI',l) - cell('CK',l)) / cell('AD', l);
      ELSE
        RETURN 0;
      END IF;



    -- CM=SI(i_depassement_service_du_sans_hc="Non";CL20*$AE20;0)
    WHEN c = 'CM' AND v >= 1 THEN
      IF NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('CL',l) * cell('AE',l);
      ELSE
        RETURN 0;
      END IF;



    -- CO=SI(ET($D20="Oui";$H20="Référentiel";$A20=i_structure_code);$M20*$AD20;0)
    WHEN c = 'CO' AND v >= 1 THEN
      IF vh.service_statutaire AND vh.volume_horaire_ref_id IS NOT NULL AND vh.structure_is_affectation THEN
        RETURN vh.heures * cell('AD',l);
      ELSE
        RETURN 0;
      END IF;



    -- CP=SI(CP$15>0;CO20/CP$15;0)
    WHEN c = 'CP' AND v >= 1 THEN
      IF cell('CP15') > 0 THEN
        RETURN cell('CO',l) / cell('CP15');
      ELSE
        RETURN 0;
      END IF;



    -- CQ=CP$16*CP20
    WHEN c = 'CQ' AND v >= 1 THEN
      RETURN cell('CP16') * cell('CP',l);



    -- CR=SI(CP$17=0;(CO20-CQ20)/$AD20;0)
    WHEN c = 'CR' AND v >= 1 THEN
      IF cell('CP17') = 0 THEN
        RETURN (cell('CO',l) - cell('CQ',l)) / cell('AD', l);
      ELSE
        RETURN 0;
      END IF;



    -- CS=SI(i_depassement_service_du_sans_hc="Non";CR20*$AE20;0)
    WHEN c = 'CS' AND v >= 1 THEN
      IF NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('CR',l) * cell('AE',l);
      ELSE
        RETURN 0;
      END IF;



    -- CU=SI(ET($D20="Oui";$H20="Référentiel";$A20<>i_structure_code;$A20<>$K$10);$M20*$AD20;0)
    WHEN c = 'CU' AND v >= 1 THEN
      IF vh.service_statutaire AND vh.volume_horaire_ref_id IS NOT NULL AND NOT vh.structure_is_affectation AND NOT vh.structure_is_univ THEN
        RETURN vh.heures * cell('AD',l);
      ELSE
        RETURN 0;
      END IF;



    -- CV=SI(CV$15>0;CU20/CV$15;0)
    WHEN c = 'CV' AND v >= 1 THEN
      IF cell('CV15') > 0 THEN
        RETURN cell('CU',l) / cell('CV15');
      ELSE
        RETURN 0;
      END IF;



    -- CW=CV$16*CV20
    WHEN c = 'CW' AND v >= 1 THEN
      RETURN cell('CV16') * cell('CV',l);



    -- CX=SI(CV$17=0;(CU20-CW20)/$AD20;0)
    WHEN c = 'CX' AND v >= 1 THEN
      IF cell('CV17') = 0 THEN
        RETURN (cell('CU',l) - cell('CW',l)) / cell('AD', l);
      ELSE
        RETURN 0;
      END IF;



    -- CY=SI(i_depassement_service_du_sans_hc="Non";CX20*$AE20;0)
    WHEN c = 'CY' AND v >= 1 THEN
      IF NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('CX',l) * cell('AE',l);
      ELSE
        RETURN 0;
      END IF;



    -- DA=SI(ET($D20="Oui";$H20="Référentiel";$A20=$K$10);$M20*$AD20;0)
    WHEN c = 'DA' AND v >= 1 THEN
      IF vh.service_statutaire AND vh.volume_horaire_ref_id IS NOT NULL AND vh.structure_is_univ THEN
        RETURN vh.heures * cell('AD',l);
      ELSE
        RETURN 0;
      END IF;



    -- DB=SI(DB$15>0;DA20/DB$15;0)
    WHEN c = 'DB' AND v >= 1 THEN
      IF cell('DB15') > 0 THEN
        RETURN cell('DA',l) / cell('DB15');
      ELSE
        RETURN 0;
      END IF;



    -- DC=DB$16*DB20
    WHEN c = 'DC' AND v >= 1 THEN
      RETURN cell('DB16') * cell('DB',l);



    -- DD=SI(DB$17=0;(DA20-DC20)/$AD20;0)
    WHEN c = 'DD' AND v >= 1 THEN
      IF cell('DB17') = 0 THEN
        RETURN (cell('DA',l) - cell('DC',l)) / cell('AD', l);
      ELSE
        RETURN 0;
      END IF;



    -- DE=SI(i_depassement_service_du_sans_hc="Non";DD20*$AE20;0)
    WHEN c = 'DE' AND v >= 1 THEN
      IF NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('DD',l) * cell('AE',l);
      ELSE
        RETURN 0;
      END IF;




    ELSE
      return 111;--raise_application_error( -20001, 'La colonne c=' || c || ', l=' || l || ' n''existe pas!');
  END CASE; END;



  PROCEDURE CALCUL_RESULTAT IS
  BEGIN
    -- si l'année est antérieure à 2021/2022 alors on utilise la V2!!
    IF ose_formule.intervenant.annee_id < 2021 THEN
      FORMULE_UNICAEN_2016.CALCUL_RESULTAT;
      RETURN;
    END IF;

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
      ose_formule.volumes_horaires.items(l).heures_compl_fc_majorees := mainCell('Heures compl. FC Maj.', 'AA',l);
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

END FORMULE_UNICAEN;