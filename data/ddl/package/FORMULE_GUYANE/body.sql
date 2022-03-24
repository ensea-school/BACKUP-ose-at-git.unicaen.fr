CREATE OR REPLACE PACKAGE BODY FORMULE_GUYANE AS
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



  FUNCTION autreEtablissement( v VARCHAR2 DEFAULT NULL ) RETURN BOOLEAN IS
  BEGIN
    RETURN COALESCE(LOWER(v),'non') = 'oui';
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



    -- AE15=SOMME(AD:AD)
    WHEN c = 'AE15' AND v >= 1 THEN
      RETURN calcFnc('total', 'AD');



    -- AI15=SOMME(AH:AH)
    WHEN c = 'AI15' AND v >= 1 THEN
      RETURN calcFnc('total', 'AH');



    -- AO15=SOMME(AN:AN)
    WHEN c = 'AO15' AND v >= 1 THEN
      RETURN calcFnc('total', 'AN');



    -- AU15=SOMME(AT:AT)
    WHEN c = 'AU15' AND v >= 1 THEN
      RETURN calcFnc('total', 'AT');



    -- BA15=SOMME(AZ:AZ)
    WHEN c = 'BA15' AND v >= 1 THEN
      RETURN calcFnc('total', 'AZ');



    -- BG15=SOMME(BF:BF)
    WHEN c = 'BG15' AND v >= 1 THEN
      RETURN calcFnc('total', 'BF');



    -- BM15=SOMME(BL:BL)
    WHEN c = 'BM15' AND v >= 1 THEN
      RETURN calcFnc('total', 'BL');



    -- BS15=SOMME(BR:BR)
    WHEN c = 'BS15' AND v >= 1 THEN
      RETURN calcFnc('total', 'BR');



    -- BY15=SOMME(BX:BX)
    WHEN c = 'BY15' AND v >= 1 THEN
      RETURN calcFnc('total', 'BX');



    -- AI16=MIN(AI15;i_service_du)
    WHEN c = 'AI16' AND v >= 1 THEN
      RETURN LEAST(cell('AI15'), i.service_du);



    -- AO16=MIN(AO15;AI17)
    WHEN c = 'AO16' AND v >= 1 THEN
      RETURN LEAST(cell('AO15'), cell('AI17'));



    -- AU16=MIN(AU15;AO17)
    WHEN c = 'AU16' AND v >= 1 THEN
      RETURN LEAST(cell('AU15'), cell('AO17'));



    -- BA16=MIN(BA15;AU17)
    WHEN c = 'BA16' AND v >= 1 THEN
      RETURN LEAST(cell('BA15'), cell('AU17'));



    -- BG16=MIN(BG15;BA17)
    WHEN c = 'BG16' AND v >= 1 THEN
      RETURN LEAST(cell('BG15'), cell('BA17'));



    -- BM16=MIN(BM15;BG17)
    WHEN c = 'BM16' AND v >= 1 THEN
      RETURN LEAST(cell('BM15'), cell('BG17'));



    -- BS16=MIN(BS15;BM17)
    WHEN c = 'BS16' AND v >= 1 THEN
      RETURN LEAST(cell('BS15'), cell('BM17'));



    -- BY16=MIN(BY15;BS17)
    WHEN c = 'BY16' AND v >= 1 THEN
      RETURN LEAST(cell('BY15'), cell('BS17'));



    -- AI17=i_service_du-AI16
    WHEN c = 'AI17' AND v >= 1 THEN
      RETURN i.service_du - cell('AI16');



    -- AO17=AI17-AO16
    WHEN c = 'AO17' AND v >= 1 THEN
      RETURN cell('AI17') - cell('AO16');



    -- AU17=AO17-AU16
    WHEN c = 'AU17' AND v >= 1 THEN
      RETURN cell('AO17') - cell('AU16');



    -- BA17=AU17-BA16
    WHEN c = 'BA17' AND v >= 1 THEN
      RETURN cell('AU17') - cell('BA16');



    -- BG17=BA17-BG16
    WHEN c = 'BG17' AND v >= 1 THEN
      RETURN cell('BA17') - cell('BG16');



    -- BM17=BG17-BM16
    WHEN c = 'BM17' AND v >= 1 THEN
      RETURN cell('BG17') - cell('BM16');



    -- BS17=BM17-BS16
    WHEN c = 'BS17' AND v >= 1 THEN
      RETURN cell('BM17') - cell('BS16');



    -- BY17=BS17-BY16
    WHEN c = 'BY17' AND v >= 1 THEN
      RETURN cell('BS17') - cell('BY16');



    -- T=SI($H20="Référentiel";0;SI($E20+$F20=0;0;($AJ20+$AP20)/($E20+$F20))*E20+$BZ20)
    WHEN c = 'T' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        --SI($E20+$F20=0;0;($AJ20+$AP20)/($E20+$F20))*E20+$BZ20
        IF vh.taux_fi + vh.taux_fa = 0 THEN
          RETURN cell('BZ', l);
        ELSE
          RETURN ((cell('AJ', l)+cell('AP', l)) / (vh.taux_fi+vh.taux_fa) * vh.taux_fi) + cell('BZ', l);
        END IF;
      END IF;



    -- U=SI($H20="Référentiel";0;SI($E20+$F20=0;0;($AJ20+$AP20)/($E20+$F20))*F20)
    WHEN c = 'U' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        --SI($E20+$F20=0;0;($AJ20+$AP20)/($E20+$F20))*F20)
        IF vh.taux_fi + vh.taux_fa = 0 THEN
          RETURN 0;
        ELSE
          RETURN (cell('AJ', l)+cell('AP', l)) / (vh.taux_fi+vh.taux_fa) * vh.taux_fa;
        END IF;
      END IF;



    -- V=SI($H20="Référentiel";0;$AV20+$BB20)
    WHEN c = 'V' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        RETURN cell('AV',l) + cell('BB',l);
      END IF;



    -- W=SI($H20="Référentiel";$BH20+$BN20+$BT20;0)
    WHEN c = 'W' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN cell('BH',l) + cell('BN',l) + cell('BT',l);
      ELSE
        RETURN 0;
      END IF;



    -- X=SI($H20="Référentiel";0;SI($E20+$F20=0;0;($AL20+$AR20)/($E20+$F20))*E20+$CB20)
    WHEN c = 'X' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        --SI($E20+$F20=0;0;($AL20+$AR20)/($E20+$F20))*E20+$CB20
        IF vh.taux_fi + vh.taux_fa = 0 THEN
          RETURN cell('CB', l);
        ELSE
          RETURN (cell('AL', l)+cell('AR', l)) / (vh.taux_fi+vh.taux_fa) * vh.taux_fi + cell('CB', l);
        END IF;
      END IF;



    -- Y=SI($H20="Référentiel";0;SI($E20+$F20=0;0;($AL20+$AR20)/($E20+$F20))*F20)
    WHEN c = 'Y' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        --SI($E20+$F20=0;0;($AL20+$AR20)/($E20+$F20))*F20
        IF vh.taux_fi + vh.taux_fa = 0 THEN
          RETURN 0;
        ELSE
          RETURN (cell('AL', l)+cell('AR', l)) / (vh.taux_fi+vh.taux_fa) * vh.taux_fa;
        END IF;
      END IF;


    -- Z=SI($H20="Référentiel";0;$AX20+$BD20)
    WHEN c = 'Z' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        RETURN cell('AX',l) + cell('BD',l);
      END IF;



    -- AA=0
    WHEN c = 'AA' AND v >= 1 THEN
      RETURN 0;



    -- AB=SI($H20="Référentiel";$BJ20+$BP20+$BV20;0)
    WHEN c = 'AB' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN cell('BJ',l) + cell('BP',l) + cell('BV',l);
      ELSE
        RETURN 0;
      END IF;



    -- AD=SI(ESTERREUR(I20);1;I20)*SI($A20="ES3";M20;0)
    WHEN c = 'AD' AND v >= 1 THEN
      IF LOWER(vh.structure_code) = 'es3' THEN
        RETURN vh.taux_service_du * vh.heures;
      ELSE
        RETURN 0;
      END IF;




    -- AE=SI(ESTERREUR(I20);1;I20)*SI(ET($A20="ES3";i_structure_code<>"ES3";$AE$15>=12);4/3;1)
    WHEN c = 'AE' AND v >= 1 THEN
      IF LOWER(vh.structure_code) = 'es3' AND LOWER(i.structure_code) <> 'es3' AND cell('AE15') >= 12 THEN
        RETURN vh.taux_service_du * 4 / 3;
      ELSE
        RETURN vh.taux_service_du;
      END IF;




    -- AF=SI(ESTERREUR(J20);1;J20)*SI(ET($A20="ES3";i_structure_code<>"ES3";$AE$15>=12);4/3;1)
    WHEN c = 'AF' AND v >= 1 THEN
      IF LOWER(vh.structure_code) = 'es3' AND LOWER(i.structure_code) <> 'es3' AND cell('AE15') >= 12 THEN
        RETURN vh.taux_service_compl * 4 / 3;
      ELSE
        RETURN vh.taux_service_compl;
      END IF;



    -- AH=SI(ET($D20="Oui";$N20<>"Oui";$H20<>"Référentiel";$A20=i_structure_code);$M20*($E20+$F20)*$AE20;0)
    WHEN c = 'AH' AND v >= 1 THEN
      IF vh.service_statutaire AND NOT autreEtablissement(vh.param_1) AND vh.volume_horaire_ref_id IS NULL AND vh.structure_is_affectation THEN
        RETURN vh.heures * (vh.taux_fi + vh.taux_fa) * cell('AE', l);
      ELSE
        RETURN 0;
      END IF;



    -- AI=SI(AI$15>0;AH20/AI$15;0)
    WHEN c = 'AI' AND v >= 1 THEN
      IF cell('AI15') > 0 THEN
        RETURN cell('AH',l) / cell('AI15');
      ELSE
        RETURN 0;
      END IF;



    -- AJ=AI$16*AI20
    WHEN c = 'AJ' AND v >= 1 THEN
      RETURN cell('AI16') * cell('AI',l);



    -- AK=SI(AI$17=0;(AH20-AJ20)/$AE20;0)
    WHEN c = 'AK' AND v >= 1 THEN
      IF cell('AI17') = 0 THEN
        RETURN (cell('AH',l) - cell('AJ',l)) / cell('AE', l);
      ELSE
        RETURN 0;
      END IF;



    -- AL=SI(i_depassement_service_du_sans_hc="Non";AK20*$AF20;0)
    WHEN c = 'AL' AND v >= 1 THEN
      IF NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('AK',l) * cell('AF',l);
      ELSE
        RETURN 0;
      END IF;



    -- AN=SI(ET($D20="Oui";$N20<>"Oui";$H20<>"Référentiel";$A20<>i_structure_code);$M20*($E20+$F20)*$AE20;0)
    WHEN c = 'AN' AND v >= 1 THEN
      IF vh.service_statutaire AND NOT autreEtablissement(vh.param_1) AND vh.volume_horaire_ref_id IS NULL AND NOT vh.structure_is_affectation THEN
        RETURN vh.heures * (vh.taux_fi + vh.taux_fa) * cell('AE', l);
      ELSE
        RETURN 0;
      END IF;



    -- AO=SI(AO$15>0;AN20/AO$15;0)
    WHEN c = 'AO' AND v >= 1 THEN
      IF cell('AO15') > 0 THEN
        RETURN cell('AN',l) / cell('AO15');
      ELSE
        RETURN 0;
      END IF;



    -- AP=AO$16*AO20
    WHEN c = 'AP' AND v >= 1 THEN
      RETURN cell('AO16') * cell('AO',l);



    -- AQ=SI(AO$17=0;(AN20-AP20)/$AE20;0)
    WHEN c = 'AQ' AND v >= 1 THEN
      IF cell('AO17') = 0 THEN
        RETURN (cell('AN',l) - cell('AP',l)) / cell('AE', l);
      ELSE
        RETURN 0;
      END IF;



    -- AR=SI(i_depassement_service_du_sans_hc="Non";AQ20*$AF20;0)
    WHEN c = 'AR' AND v >= 1 THEN
      IF NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('AQ',l) * cell('AF',l);
      ELSE
        RETURN 0;
      END IF;



    -- AT=SI(ET($D20="Oui";$N20<>"Oui";$H20<>"Référentiel";$A20=i_structure_code);$M20*$G20*$AE20;0)
    WHEN c = 'AT' AND v >= 1 THEN
      IF vh.service_statutaire AND NOT autreEtablissement(vh.param_1) AND vh.volume_horaire_ref_id IS NULL AND vh.structure_is_affectation THEN
        RETURN vh.heures * vh.taux_fc * cell('AE',l);
      ELSE
        RETURN 0;
      END IF;



    -- AU=SI(AU$15>0;AT20/AU$15;0)
    WHEN c = 'AU' AND v >= 1 THEN
      IF cell('AU15') > 0 THEN
        RETURN cell('AT',l) / cell('AU15');
      ELSE
        RETURN 0;
      END IF;



    -- AV=AU$16*AU20
    WHEN c = 'AV' AND v >= 1 THEN
      RETURN cell('AU16') * cell('AU',l);



    -- AW=SI(AU$17=0;(AT20-AV20)/$AE20;0)
    WHEN c = 'AW' AND v >= 1 THEN
      IF cell('AU17') = 0 THEN
        RETURN (cell('AT',l) - cell('AV',l)) / cell('AE', l);
      ELSE
        RETURN 0;
      END IF;



    -- AX=SI(i_depassement_service_du_sans_hc="Non";AW20*$AF20;0)
    WHEN c = 'AX' AND v >= 1 THEN
      IF NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('AW',l) * cell('AF',l);
      ELSE
        RETURN 0;
      END IF;



    -- AZ=SI(ET($D20="Oui";$N20<>"Oui";$H20<>"Référentiel";$A20<>i_structure_code);$M20*$G20*$AE20;0)
    WHEN c = 'AZ' AND v >= 1 THEN
      IF vh.service_statutaire AND NOT autreEtablissement(vh.param_1) AND vh.volume_horaire_ref_id IS NULL AND NOT vh.structure_is_affectation THEN
        RETURN vh.heures * vh.taux_fc * cell('AE',l);
      ELSE
        RETURN 0;
      END IF;



    -- BA=SI(BA$15>0;AZ20/BA$15;0)
    WHEN c = 'BA' AND v >= 1 THEN
      IF cell('BA15') > 0 THEN
        RETURN cell('AZ',l) / cell('BA15');
      ELSE
        RETURN 0;
      END IF;



    -- BB=BA$16*BA20
    WHEN c = 'BB' AND v >= 1 THEN
      RETURN cell('BA16') * cell('BA',l);



    -- BC=SI(BA$17=0;(AZ20-BB20)/$AE20;0)
    WHEN c = 'BC' AND v >= 1 THEN
      IF cell('BA17') = 0 THEN
        RETURN (cell('AZ',l) - cell('BB',l)) / cell('AE', l);
      ELSE
        RETURN 0;
      END IF;



    -- BD=SI(i_depassement_service_du_sans_hc="Non";BC20*$AF20;0)
    WHEN c = 'BD' AND v >= 1 THEN
      IF NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('BC',l) * cell('AF',l);
      ELSE
        RETURN 0;
      END IF;



    -- BF=SI(ET($D20="Oui";$N20<>"Oui";$H20="Référentiel";$A20=i_structure_code);$M20*$AE20;0)
    WHEN c = 'BF' AND v >= 1 THEN
      IF vh.service_statutaire AND NOT autreEtablissement(vh.param_1) AND vh.volume_horaire_ref_id IS NOT NULL AND vh.structure_is_affectation THEN
        RETURN vh.heures * cell('AE',l);
      ELSE
        RETURN 0;
      END IF;



    -- BG=SI(BG$15>0;BF20/BG$15;0)
    WHEN c = 'BG' AND v >= 1 THEN
      IF cell('BG15') > 0 THEN
        RETURN cell('BF',l) / cell('BG15');
      ELSE
        RETURN 0;
      END IF;



    -- BH=BG$16*BG20
    WHEN c = 'BH' AND v >= 1 THEN
      RETURN cell('BG16') * cell('BG',l);



    -- BI=SI(BG$17=0;(BF20-BH20)/$AE20;0)
    WHEN c = 'BI' AND v >= 1 THEN
      IF cell('BG17') = 0 THEN
        RETURN (cell('BF',l) - cell('BH',l)) / cell('AE', l);
      ELSE
        RETURN 0;
      END IF;



    -- BJ=SI(i_depassement_service_du_sans_hc="Non";BI20*$AF20;0)
    WHEN c = 'BJ' AND v >= 1 THEN
      IF NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('BI',l) * cell('AF',l);
      ELSE
        RETURN 0;
      END IF;



    -- BL=SI(ET($D20="Oui";$N20<>"Oui";$H20="Référentiel";$A20<>i_structure_code;$A20<>$K$10);$M20*$AE20;0)
    WHEN c = 'BL' AND v >= 1 THEN
      IF vh.service_statutaire AND NOT autreEtablissement(vh.param_1) AND vh.volume_horaire_ref_id IS NOT NULL AND NOT vh.structure_is_affectation AND NOT vh.structure_is_univ THEN
        RETURN vh.heures * cell('AE',l);
      ELSE
        RETURN 0;
      END IF;



    -- BM=SI(BM$15>0;BL20/BM$15;0)
    WHEN c = 'BM' AND v >= 1 THEN
      IF cell('BM15') > 0 THEN
        RETURN cell('BL',l) / cell('BM15');
      ELSE
        RETURN 0;
      END IF;



    -- BN=BM$16*BM20
    WHEN c = 'BN' AND v >= 1 THEN
      RETURN cell('BM16') * cell('BM',l);



    -- BO=SI(BM$17=0;(BL20-BN20)/$AE20;0)
    WHEN c = 'BO' AND v >= 1 THEN
      IF cell('BM17') = 0 THEN
        RETURN (cell('BL',l) - cell('BN',l)) / cell('AE', l);
      ELSE
        RETURN 0;
      END IF;



    -- BP=SI(i_depassement_service_du_sans_hc="Non";BO20*$AF20;0)
    WHEN c = 'BP' AND v >= 1 THEN
      IF NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('BO',l) * cell('AF',l);
      ELSE
        RETURN 0;
      END IF;



    -- BR=SI(ET($D20="Oui";$N20<>"Oui";$H20="Référentiel";$A20=$K$10);$M20*$AE20;0)
    WHEN c = 'BR' AND v >= 1 THEN
      IF vh.service_statutaire AND NOT autreEtablissement(vh.param_1) AND vh.volume_horaire_ref_id IS NOT NULL AND vh.structure_is_univ THEN
        RETURN vh.heures * cell('AE',l);
      ELSE
        RETURN 0;
      END IF;



    -- BS=SI(BS$15>0;BR20/BS$15;0)
    WHEN c = 'BS' AND v >= 1 THEN
      IF cell('BS15') > 0 THEN
        RETURN cell('BR',l) / cell('BS15');
      ELSE
        RETURN 0;
      END IF;



    -- BT=BS$16*BS20
    WHEN c = 'BT' AND v >= 1 THEN
      RETURN cell('BS16') * cell('BS',l);



    -- BU=SI(BS$17=0;(BR20-BT20)/$AE20;0)
    WHEN c = 'BU' AND v >= 1 THEN
      IF cell('BS17') = 0 THEN
        RETURN (cell('BR',l) - cell('BT',l)) / cell('AE', l);
      ELSE
        RETURN 0;
      END IF;



    -- BV=SI(i_depassement_service_du_sans_hc="Non";BU20*$AF20;0)
    WHEN c = 'BV' AND v >= 1 THEN
      IF NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('BU',l) * cell('AF',l);
      ELSE
        RETURN 0;
      END IF;



    -- BX=SI(ET($D20="Oui";$N20="Oui");$M20*$AE20;0)
    WHEN c = 'BX' AND v >= 1 THEN
      IF vh.service_statutaire AND autreEtablissement(vh.param_1) THEN
        RETURN vh.heures * cell('AE',l);
      ELSE
        RETURN 0;
      END IF;



    -- BY=SI(BY$15>0;BX20/BY$15;0)
    WHEN c = 'BY' AND v >= 1 THEN
      IF cell('BY15') > 0 THEN
        RETURN cell('BX',l) / cell('BY15');
      ELSE
        RETURN 0;
      END IF;



    -- BZ=BY$16*BY20
    WHEN c = 'BZ' AND v >= 1 THEN
      RETURN cell('BY16') * cell('BY',l);



    -- CA=SI(BY$17=0;(BX20-BZ20)/$AE20;0)
    WHEN c = 'CA' AND v >= 1 THEN
      IF cell('BY17') = 0 THEN
        RETURN (cell('BX',l) - cell('BZ',l)) / cell('AE', l);
      ELSE
        RETURN 0;
      END IF;



    -- CB=SI(i_depassement_service_du_sans_hc="Non";CA20*$AF20;0)
    WHEN c = 'CB' AND v >= 1 THEN
      IF NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('CA',l) * cell('AF',l);
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
      CASE WHEN s.element_pedagogique_id IS NULL THEN ''oui'' ELSE ''non'' END param_1,
      NULL param_2,
      NULL param_3,
      NULL param_4,
      NULL param_5
    FROM
      V_FORMULE_VOLUME_HORAIRE fvh
      JOIN service s ON s.id = fvh.service_id
    ORDER BY
      ordre';
  END;

END FORMULE_GUYANE;