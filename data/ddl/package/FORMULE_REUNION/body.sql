CREATE OR REPLACE PACKAGE BODY FORMULE_REUNION AS
  decalageLigne NUMERIC DEFAULT 19;

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

  PROCEDURE dbgi( val CLOB ) IS
  BEGIN
    ose_formule.intervenant.debug_info := ose_formule.intervenant.debug_info || val;
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

  FUNCTION cell( c VARCHAR2, l NUMERIC DEFAULT 9999 ) RETURN FLOAT IS
    val FLOAT;
  BEGIN
    IF l = 0 THEN
      RETURN 0;
    END IF;

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

    WHEN fncName = 'somme' THEN
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



  FUNCTION calcCell( c VARCHAR2, l NUMERIC ) RETURN FLOAT IS
    vh ose_formule.t_volume_horaire;
    i  ose_formule.t_intervenant;
    val FLOAT;
  BEGIN
    i := ose_formule.intervenant;
    IF l > 0 AND l <> 9999 THEN
      vh := ose_formule.volumes_horaires.items(l);
    END IF;
    CASE c



      -- T=IF([.$H20]="Référentiel";0;[.AI20]+[.AO20]+[.AU20]+[.BA20])
      WHEN 'T' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN cell('AI',l) + cell('AO',l) + cell('AU',l) + cell('BA',l);
        END IF;



      -- U=IF([.$H20]="Référentiel";0;[.BG20]+[.BM20]+[.BS20]+[.BY20])
      WHEN 'U' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN cell('BG',l) + cell('BM',l) + cell('BS',l) + cell('BY',l);
        END IF;



      -- V=IF([.$H20]="Référentiel";0;[.CE20]+[.CK20]+[.CQ20]+[.CW20])
      WHEN 'V' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN cell('CE',l) + cell('CK',l) + cell('CQ',l) + cell('CW',l);
        END IF;



      -- W=IF([.$H20]="Référentiel";[.DC20]+[.DI20]+[.DO20];0)
      WHEN 'W' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN cell('DC',l) + cell('DI',l) + cell('DO',l);
        ELSE
          RETURN 0;
        END IF;



      -- X=IF([.$H20]="Référentiel";0;[.AK20]+[.AQ20]+[.AW20]+[.BC20])
      WHEN 'X' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN cell('AK',l) + cell('AQ',l) + cell('AW',l) + cell('BC',l);
        END IF;



      -- Y=IF([.$H20]="Référentiel";0;[.BI20]+[.BO20]+[.BU20]+[.CA20])
      WHEN 'Y' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN cell('BI',l) + cell('BO',l) + cell('BU',l) + cell('CA',l);
        END IF;



      -- Z=IF([.$H20]="Référentiel";0;[.CG20]+[.CM20]+[.CS20]+[.CY20])
      WHEN 'Z' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN cell('CG',l) + cell('CM',l) + cell('CS',l) + cell('CY',l);
        END IF;



      -- AA=0
      WHEN 'AA' THEN
        RETURN 0;



      -- AB=IF([.$H20]="Référentiel";[.$DE20]+[.$DK20]+[.$DQ20]+[.$DS20];0)
      WHEN 'AB' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN cell('DE',l) + cell('DK',l) + cell('DQ',l) + cell('DS',l);
        ELSE
          RETURN 0;
        END IF;



      -- AD=IF(ISERROR([.I20]);1;[.I20])
      WHEN 'AD' THEN
        RETURN vh.taux_service_du;



      -- AE=IF(ISERROR([.J20]);1;[.J20])
      WHEN 'AE' THEN
        RETURN vh.taux_service_compl;



      -- AG=IF(AND([.$D20]="Oui";[.$H20]="TP";[.$A20]=i_structure_code);[.$M20]*[.$E20]*[.$AD20];0)
      WHEN 'AG' THEN
        IF vh.service_statutaire AND vh.type_intervention_code = 'TP' AND vh.structure_is_affectation THEN
          RETURN vh.heures * vh.taux_fi * cell('AD',l);
        ELSE
          RETURN 0;
        END IF;



      -- AH15=SUM([.AG$1:.AG$1048576])
      WHEN 'AH15' THEN
        RETURN calcFnc('somme','AG');



      -- AH16=MIN([.AH15];i_service_du)
      WHEN 'AH16' THEN
        RETURN LEAST(cell('AH15'), i.service_du);



      -- AH17=i_service_du-[.AH16]
      WHEN 'AH17' THEN
        RETURN i.service_du - cell('AH16');



      -- AH=IF([.AH$15]>0;[.AG20]/[.AH$15];0)
      WHEN 'AH' THEN
        IF cell('AH15') > 0 THEN
          RETURN cell('AG',l) / cell('AH15');
        ELSE
          RETURN 0;
        END IF;



      -- AI=[.AH$16]*[.AH20]
      WHEN 'AI' THEN
        RETURN cell('AH16') * cell('AH',l);



      -- AJ=IF([.AH$17]=0;([.AG20]-[.AI20])/[.$AD20];0)
      WHEN 'AJ' THEN
        IF cell('AH17') = 0 THEN
          RETURN (cell('AG',l) - cell('AI',l)) / cell('AD',l);
        ELSE
          RETURN 0;
        END IF;



      -- AK=IF(i_depassement_service_du_sans_hc="Non";[.AJ20]*[.$AE20];0)
      WHEN 'AK' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('AJ',l) * cell('AE',l);
        ELSE
          RETURN 0;
        END IF;



      -- AM=IF(AND([.$D20]="Oui";[.$H20]<>"TP";[.$H20]<>"Référentiel";[.$A20]=i_structure_code);[.$M20]*[.$E20]*[.$AD20];0)
      WHEN 'AM' THEN
        IF vh.service_statutaire AND vh.type_intervention_code <> 'TP' AND vh.volume_horaire_ref_id IS NULL AND vh.structure_is_affectation THEN
          RETURN vh.heures * vh.taux_fi * cell('AD',l);
        ELSE
          RETURN 0;
        END IF;



      -- AN15=SUM([.AM$1:.AM$1048576])
      WHEN 'AN15' THEN
        RETURN calcFnc('somme','AM');



      -- AN16=MIN([.AN15];[.AH17])
      WHEN 'AN16' THEN
        RETURN LEAST(cell('AN15'), cell('AH17'));



      -- AN17=[.AH17]-[.AN16]
      WHEN 'AN17' THEN
        RETURN cell('AH17') - cell('AN16');



      -- AN=IF([.AN$15]>0;[.AM20]/[.AN$15];0)
      WHEN 'AN' THEN
        IF cell('AN15') > 0 THEN
          RETURN cell('AM',l) / cell('AN15');
        ELSE
          RETURN 0;
        END IF;



      -- AO=[.AN$16]*[.AN20]
      WHEN 'AO' THEN
        RETURN cell('AN16') * cell('AN',l);



      -- AP=IF([.AN$17]=0;([.AM20]-[.AO20])/[.$AD20];0)
      WHEN 'AP' THEN
        IF cell('AN17') = 0 THEN
          RETURN (cell('AM',l) - cell('AO',l)) / cell('AD',l);
        ELSE
          RETURN 0;
        END IF;



      -- AQ=IF(i_depassement_service_du_sans_hc="Non";[.AP20]*[.$AE20];0)
      WHEN 'AQ' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('AP',l) * cell('AE',l);
        ELSE
          RETURN 0;
        END IF;



      -- AS=IF(AND([.$D20]="Oui";[.$H20]="TP";[.$A20]<>i_structure_code);[.$M20]*[.$E20]*[.$AD20];0)
      WHEN 'AS' THEN
        IF vh.service_statutaire AND vh.type_intervention_code = 'TP' AND NOT vh.structure_is_affectation THEN
          RETURN vh.heures * vh.taux_fi * cell('AD',l);
        ELSE
          RETURN 0;
        END IF;



      -- AT15=SUM([.AS$1:.AS$1048576])
      WHEN 'AT15' THEN
        RETURN calcFnc('somme','AS');



      -- AT16=MIN([.AT15];[.CJ17])
      WHEN 'AT16' THEN
        RETURN LEAST(cell('AT15'), cell('CJ17'));



      -- AT17=[.CJ17]-[.AT16]
      WHEN 'AT17' THEN
        RETURN cell('CJ17') - cell('AT16');



      -- AT=IF([.AT$15]>0;[.AS20]/[.AT$15];0)
      WHEN 'AT' THEN
        IF cell('AT15') > 0 THEN
          RETURN cell('AS',l) / cell('AT15');
        ELSE
          RETURN 0;
        END IF;



      -- AU=[.AT$16]*[.AT20]
      WHEN 'AU' THEN
        RETURN cell('AT16') * cell('AT',l);



      -- AV=IF([.AT$17]=0;([.AS20]-[.AU20])/[.$AD20];0)
      WHEN 'AV' THEN
        IF cell('AT17') = 0 THEN
          RETURN (cell('AS',l) - cell('AU',l)) / cell('AD',l);
        ELSE
          RETURN 0;
        END IF;



      -- AW=IF(i_depassement_service_du_sans_hc="Non";[.AV20]*[.$AE20];0)
      WHEN 'AW' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('AV',l) * cell('AE',l);
        ELSE
          RETURN 0;
        END IF;



      -- AY=IF(AND([.$D20]="Oui";[.$H20]<>"TP";[.$H20]<>"Référentiel";[.$A20]<>i_structure_code);[.$M20]*[.$E20]*[.$AD20];0)
      WHEN 'AY' THEN
        IF vh.service_statutaire AND vh.type_intervention_code <> 'TP' AND vh.volume_horaire_ref_id IS NULL AND NOT vh.structure_is_affectation THEN
          RETURN vh.heures * vh.taux_fi * cell('AD',l);
        ELSE
          RETURN 0;
        END IF;



      -- AZ15=SUM([.AY$1:.AY$1048576])
      WHEN 'AZ15' THEN
        RETURN calcFnc('somme','AY');



      -- AZ16=MIN([.AZ15];[.AT17])
      WHEN 'AZ16' THEN
        RETURN LEAST(cell('AZ15'), cell('AT17'));



      -- AZ17=[.AT17]-[.AZ16]
      WHEN 'AZ17' THEN
        RETURN cell('AT17') - cell('AZ16');



      -- AZ=IF([.AZ$15]>0;[.AY20]/[.AZ$15];0)
      WHEN 'AZ' THEN
        IF cell('AZ15') > 0 THEN
          RETURN cell('AY',l) / cell('AZ15');
        ELSE
          RETURN 0;
        END IF;



      -- BA=[.AZ$16]*[.AZ20]
      WHEN 'BA' THEN
        RETURN cell('AZ16') * cell('AZ',l);



      -- BB=IF([.AZ$17]=0;([.AY20]-[.BA20])/[.$AD20];0)
      WHEN 'BB' THEN
        IF cell('AZ17') = 0 THEN
          RETURN (cell('AY',l) - cell('BA',l)) / cell('AD',l);
        ELSE
          RETURN 0;
        END IF;



      -- BC=IF(i_depassement_service_du_sans_hc="Non";[.BB20]*[.$AE20];0)
      WHEN 'BC' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('BB',l) * cell('AE',l);
        ELSE
          RETURN 0;
        END IF;



      -- BE=IF(AND([.$D20]="Oui";[.$H20]="TP";[.$A20]=i_structure_code);[.$M20]*[.$F20]*[.$AD20];0)
      WHEN 'BE' THEN
        IF vh.service_statutaire AND vh.type_intervention_code = 'TP' AND vh.structure_is_affectation THEN
          RETURN vh.heures * vh.taux_fa * cell('AD',l);
        ELSE
          RETURN 0;
        END IF;



      -- BF15=SUM([.BE$1:.BE$1048576])
      WHEN 'BF15' THEN
        RETURN calcFnc('somme','BE');



      -- BF16=MIN([.BF15];[.AN17])
      WHEN 'BF16' THEN
        RETURN LEAST(cell('BF15'), cell('AN17'));



      -- BF17=[.AN17]-[.BF16]
      WHEN 'BF17' THEN
        RETURN cell('AN17') - cell('BF16');



      -- BF=IF([.BF$15]>0;[.BE20]/[.BF$15];0)
      WHEN 'BF' THEN
        IF cell('BF15') > 0 THEN
          RETURN cell('BE',l) / cell('BF15');
        ELSE
          RETURN 0;
        END IF;



      -- BG=[.BF$16]*[.BF20]
      WHEN 'BG' THEN
        RETURN cell('BF16') * cell('BF',l);



      -- BH=IF([.BF$17]=0;([.BE20]-[.BG20])/[.$AD20];0)
      WHEN 'BH' THEN
        IF cell('BF17') = 0 THEN
          RETURN (cell('BE',l) - cell('BG',l)) / cell('AD',l);
        ELSE
          RETURN 0;
        END IF;



      -- BI=IF(i_depassement_service_du_sans_hc="Non";[.BH20]*[.$AE20];0)
      WHEN 'BI' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('BH',l) * cell('AE',l);
        ELSE
          RETURN 0;
        END IF;



      -- BK=IF(AND([.$D20]="Oui";[.$H20]<>"TP";[.$H20]<>"Référentiel";[.$A20]=i_structure_code);[.$M20]*[.$F20]*[.$AD20];0)
      WHEN 'BK' THEN
        IF vh.service_statutaire AND vh.type_intervention_code <> 'TP' AND vh.volume_horaire_ref_id IS NULL AND vh.structure_is_affectation THEN
          RETURN vh.heures * vh.taux_fa * cell('AD',l);
        ELSE
          RETURN 0;
        END IF;



      -- BL15=SUM([.BK$1:.BK$1048576])
      WHEN 'BL15' THEN
        RETURN calcFnc('somme','BK');



      -- BL16=MIN([.BL15];[.BF17])
      WHEN 'BL16' THEN
        RETURN LEAST(cell('BL15'), cell('BF17'));



      -- BL17=[.BF17]-[.BL16]
      WHEN 'BL17' THEN
        RETURN cell('BF17') - cell('BL16');



      -- BL=IF([.BL$15]>0;[.BK20]/[.BL$15];0)
      WHEN 'BL' THEN
        IF cell('BL15') > 0 THEN
          RETURN cell('BK',l) / cell('BL15');
        ELSE
          RETURN 0;
        END IF;



      -- BM=[.BL$16]*[.BL20]
      WHEN 'BM' THEN
        RETURN cell('BL16') * cell('BL',l);



      -- BN=IF([.BL$17]=0;([.BK20]-[.BM20])/[.$AD20];0)
      WHEN 'BN' THEN
        IF cell('BL17') = 0 THEN
          RETURN (cell('BK',l) - cell('BM',l)) / cell('AD',l);
        ELSE
          RETURN 0;
        END IF;



      -- BO=IF(i_depassement_service_du_sans_hc="Non";[.BN20]*[.$AE20];0)
      WHEN 'BO' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('BN',l) * cell('AE',l);
        ELSE
          RETURN 0;
        END IF;



      -- BQ=IF(AND([.$D20]="Oui";[.$H20]="TP";[.$A20]<>i_structure_code);[.$M20]*[.$F20]*[.$AD20];0)
      WHEN 'BQ' THEN
        IF vh.service_statutaire AND vh.type_intervention_code = 'TP' AND NOT vh.structure_is_affectation THEN
          RETURN vh.heures * vh.taux_fa * cell('AD',l);
        ELSE
          RETURN 0;
        END IF;



      -- BR15=SUM([.BQ$1:.BQ$1048576])
      WHEN 'BR15' THEN
        RETURN calcFnc('somme','BQ');



      -- BR16=MIN([.BR15];[.AZ17])
      WHEN 'BR16' THEN
        RETURN LEAST(cell('BR15'), cell('AZ17'));



      -- BR17=[.AZ17]-[.BR16]
      WHEN 'BR17' THEN
        RETURN cell('AZ17') - cell('BR16');



      -- BR=IF([.BR$15]>0;[.BQ20]/[.BR$15];0)
      WHEN 'BR' THEN
        IF cell('BR15') > 0 THEN
          RETURN cell('BQ',l) / cell('BR15');
        ELSE
          RETURN 0;
        END IF;



      -- BS=[.BR$16]*[.BR20]
      WHEN 'BS' THEN
        RETURN cell('BR16') * cell('BR',l);



      -- BT=IF([.BR$17]=0;([.BQ20]-[.BS20])/[.$AD20];0)
      WHEN 'BT' THEN
        IF cell('BR17') = 0 THEN
          RETURN (cell('BQ',l) - cell('BS',l)) / cell('AD',l);
        ELSE
          RETURN 0;
        END IF;



      -- BU=IF(i_depassement_service_du_sans_hc="Non";[.BT20]*[.$AE20];0)
      WHEN 'BU' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('BT',l) * cell('AE',l);
        ELSE
          RETURN 0;
        END IF;



      -- BW=IF(AND([.$D20]="Oui";[.$H20]<>"TP";[.$H20]<>"Référentiel";[.$A20]<>i_structure_code);[.$M20]*[.$F20]*[.$AD20];0)
      WHEN 'BW' THEN
        IF vh.service_statutaire AND vh.type_intervention_code <> 'TP' AND vh.volume_horaire_ref_id IS NULL AND NOT vh.structure_is_affectation THEN
          RETURN vh.heures * vh.taux_fa * cell('AD',l);
        ELSE
          RETURN 0;
        END IF;



      -- BX15=SUM([.BW$1:.BW$1048576])
      WHEN 'BX15' THEN
        RETURN calcFnc('somme','BW');



      -- BX16=MIN([.BX15];[.BR17])
      WHEN 'BX16' THEN
        RETURN LEAST(cell('BX15'), cell('BR17'));



      -- BX17=[.BR17]-[.BX16]
      WHEN 'BX17' THEN
        RETURN cell('BR17') - cell('BX16');



      -- BX=IF([.BX$15]>0;[.BW20]/[.BX$15];0)
      WHEN 'BX' THEN
        IF cell('BX15') > 0 THEN
          RETURN cell('BW',l) / cell('BX15');
        ELSE
          RETURN 0;
        END IF;



      -- BY=[.BX$16]*[.BX20]
      WHEN 'BY' THEN
        RETURN cell('BX16') * cell('BX',l);



      -- BZ=IF([.BX$17]=0;([.BW20]-[.BY20])/[.$AD20];0)
      WHEN 'BZ' THEN
        IF cell('BX17') = 0 THEN
          RETURN (cell('BW',l) - cell('BY',l)) / cell('AD',l);
        ELSE
          RETURN 0;
        END IF;



      -- CA=IF(i_depassement_service_du_sans_hc="Non";[.BZ20]*[.$AE20];0)
      WHEN 'CA' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('BZ',l) * cell('AE',l);
        ELSE
          RETURN 0;
        END IF;



      -- CC=IF(AND([.$D20]="Oui";[.$H20]="TP";[.$A20]=i_structure_code);[.$M20]*[.$G20]*[.$AD20];0)
      WHEN 'CC' THEN
        IF vh.service_statutaire AND vh.type_intervention_code = 'TP' AND vh.structure_is_affectation THEN
          RETURN vh.heures * vh.taux_fc * cell('AD',l);
        ELSE
          RETURN 0;
        END IF;



      -- CD15=SUM([.CC$1:.CC$1048576])
      WHEN 'CD15' THEN
        RETURN calcFnc('somme','CC');



      -- CD16=MIN([.CD15];[.BL17])
      WHEN 'CD16' THEN
        RETURN LEAST(cell('CD15'), cell('BL17'));



      -- CD17=[.BL17]-[.CD16]
      WHEN 'CD17' THEN
        RETURN cell('BL17') - cell('CD16');



      -- CD=IF([.CD$15]>0;[.CC20]/[.CD$15];0)
      WHEN 'CD' THEN
        IF cell('CD15') > 0 THEN
          RETURN cell('CC',l) / cell('CD15');
        ELSE
          RETURN 0;
        END IF;



      -- CE=[.CD$16]*[.CD20]
      WHEN 'CE' THEN
        RETURN cell('CD16') * cell('CD',l);



      -- CF=IF([.CD$17]=0;([.CC20]-[.CE20])/[.$AD20];0)
      WHEN 'CF' THEN
        IF cell('CD17') = 0 THEN
          RETURN (cell('CC',l) - cell('CE',l)) / cell('AD',l);
        ELSE
          RETURN 0;
        END IF;



      -- CG=IF(i_depassement_service_du_sans_hc="Non";[.CF20]*[.$AE20];0)
      WHEN 'CG' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('CF',l) * cell('AE',l);
        ELSE
          RETURN 0;
        END IF;



      -- CI=IF(AND([.$D20]="Oui";[.$H20]<>"TP";[.$H20]<>"Référentiel";[.$A20]=i_structure_code);[.$M20]*[.$G20]*[.$AD20];0)
      WHEN 'CI' THEN
        IF vh.service_statutaire AND vh.type_intervention_code <> 'TP' AND vh.volume_horaire_ref_id IS NULL AND vh.structure_is_affectation THEN
          RETURN vh.heures * vh.taux_fc * cell('AD',l);
        ELSE
          RETURN 0;
        END IF;



      -- CJ15=SUM([.CI$1:.CI$1048576])
      WHEN 'CJ15' THEN
        RETURN calcFnc('somme','CI');



      -- CJ16=MIN([.CJ15];[.CD17])
      WHEN 'CJ16' THEN
        RETURN LEAST(cell('CJ15'), cell('CD17'));



      -- CJ17=[.CD17]-[.CJ16]
      WHEN 'CJ17' THEN
        RETURN cell('CD17') - cell('CJ16');



      -- CJ=IF([.CJ$15]>0;[.CI20]/[.CJ$15];0)
      WHEN 'CJ' THEN
        IF cell('CJ15') > 0 THEN
          RETURN cell('CI',l) / cell('CJ15');
        ELSE
          RETURN 0;
        END IF;



      -- CK=[.CJ$16]*[.CJ20]
      WHEN 'CK' THEN
        RETURN cell('CJ16') * cell('CJ',l);



      -- CL=IF([.CJ$17]=0;([.CI20]-[.CK20])/[.$AD20];0)
      WHEN 'CL' THEN
        IF cell('CJ17') = 0 THEN
          RETURN (cell('CI',l) - cell('CK',l)) / cell('AD',l);
        ELSE
          RETURN 0;
        END IF;



      -- CM=IF(i_depassement_service_du_sans_hc="Non";[.CL20]*[.$AE20];0)
      WHEN 'CM' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('CL',l) * cell('AE',l);
        ELSE
          RETURN 0;
        END IF;



      -- CO=IF(AND([.$D20]="Oui";[.$H20]="TP";[.$A20]<>i_structure_code);[.$M20]*[.$G20]*[.$AD20];0)
      WHEN 'CO' THEN
        IF vh.service_statutaire AND vh.type_intervention_code = 'TP' AND NOT vh.structure_is_affectation THEN
          RETURN vh.heures * vh.taux_fc * cell('AD',l);
        ELSE
          RETURN 0;
        END IF;



      -- CP15=SUM([.CO$1:.CO$1048576])
      WHEN 'CP15' THEN
        RETURN calcFnc('somme','CO');



      -- CP16=MIN([.CP15];[.BX17])
      WHEN 'CP16' THEN
        RETURN LEAST(cell('CP15'), cell('BX17'));



      -- CP17=[.BX17]-[.CP16]
      WHEN 'CP17' THEN
        RETURN cell('BX17') - cell('CP16');



      -- CP=IF([.CP$15]>0;[.CO20]/[.CP$15];0)
      WHEN 'CP' THEN
        IF cell('CP15') > 0 THEN
          RETURN cell('CO',l) / cell('CP15');
        ELSE
          RETURN 0;
        END IF;



      -- CQ=[.CP$16]*[.CP20]
      WHEN 'CQ' THEN
        RETURN cell('CP16') * cell('CP',l);



      -- CR=IF([.CP$17]=0;([.CO20]-[.CQ20])/[.$AD20];0)
      WHEN 'CR' THEN
        IF cell('CP17') = 0 THEN
          RETURN (cell('CO',l) - cell('CQ',l)) / cell('AD',l);
        ELSE
          RETURN 0;
        END IF;



      -- CS=IF(i_depassement_service_du_sans_hc="Non";[.CR20]*[.$AE20];0)
      WHEN 'CS' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('CR',l) * cell('AE',l);
        ELSE
          RETURN 0;
        END IF;



      -- CU=IF(AND([.$D20]="Oui";[.$H20]<>"TP";[.$H20]<>"Référentiel";[.$A20]<>i_structure_code);[.$M20]*[.$G20]*[.$AD20];0)
      WHEN 'CU' THEN
        IF vh.service_statutaire AND vh.type_intervention_code <> 'TP' AND vh.volume_horaire_ref_id IS NULL AND NOT vh.structure_is_affectation THEN
          RETURN vh.heures * vh.taux_fc * cell('AD',l);
        ELSE
          RETURN 0;
        END IF;



      -- CV15=SUM([.CU$1:.CU$1048576])
      WHEN 'CV15' THEN
        RETURN calcFnc('somme','CU');



      -- CV16=MIN([.CV15];[.CP17])
      WHEN 'CV16' THEN
        RETURN LEAST(cell('CV15'), cell('CP17'));



      -- CV17=[.CP17]-[.CV16]
      WHEN 'CV17' THEN
        RETURN cell('CP17') - cell('CV16');



      -- CV=IF([.CV$15]>0;[.CU20]/[.CV$15];0)
      WHEN 'CV' THEN
        IF cell('CV15') > 0 THEN
          RETURN cell('CU',l) / cell('CV15');
        ELSE
          RETURN 0;
        END IF;



      -- CW=[.CV$16]*[.CV20]
      WHEN 'CW' THEN
        RETURN cell('CV16') * cell('CV',l);



      -- CX=IF([.CV$17]=0;([.CU20]-[.CW20])/[.$AD20];0)
      WHEN 'CX' THEN
        IF cell('CV17') = 0 THEN
          RETURN (cell('CU',l) - cell('CW',l)) / cell('AD',l);
        ELSE
          RETURN 0;
        END IF;



      -- CY=IF(i_depassement_service_du_sans_hc="Non";[.CX20]*[.$AE20];0)
      WHEN 'CY' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('CX',l) * cell('AE',l);
        ELSE
          RETURN 0;
        END IF;



      -- DA=IF(AND([.$D20]="Oui";[.$H20]="Référentiel";[.$A20]=i_structure_code);[.$M20]*[.$AD20];0)
      WHEN 'DA' THEN
        IF vh.service_statutaire AND vh.volume_horaire_ref_id IS NOT NULL AND vh.structure_is_affectation THEN
          RETURN vh.heures * cell('AD',l);
        ELSE
          RETURN 0;
        END IF;



      -- DB15=SUM([.DA$1:.DA$1048576])
      WHEN 'DB15' THEN
        RETURN calcFnc('somme','DA');



      -- DB16=MIN([.DB15];[.CV17])
      WHEN 'DB16' THEN
        RETURN LEAST(cell('DB15'), cell('CV17'));



      -- DB17=[.CV17]-[.DB16]
      WHEN 'DB17' THEN
        RETURN cell('CV17') - cell('DB16');



      -- DB=IF([.DB$15]>0;[.DA20]/[.DB$15];0)
      WHEN 'DB' THEN
        IF cell('DB15') > 0 THEN
          RETURN cell('DA',l) / cell('DB15');
        ELSE
          RETURN 0;
        END IF;



      -- DC=[.DB$16]*[.DB20]
      WHEN 'DC' THEN
        RETURN cell('DB16') * cell('DB',l);



      -- DD=IF([.DB$17]=0;([.DA20]-[.DC20])/[.$AD20];0)
      WHEN 'DD' THEN
        IF cell('DB17') = 0 THEN
          RETURN (cell('DA',l) - cell('DC',l)) / cell('AD',l);
        ELSE
          RETURN 0;
        END IF;



      -- DE=IF(i_depassement_service_du_sans_hc="Non";[.DD20]*[.$AE20];0)
      WHEN 'DE' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('DD',l) * cell('AE',l);
        ELSE
          RETURN 0;
        END IF;



      -- DG=IF(AND([.$D20]="Oui";[.$H20]="Référentiel";[.$A20]<>i_structure_code;[.$A20]<>[.$K$10]);[.$M20]*[.$AD20];0)
      WHEN 'DG' THEN
        IF vh.service_statutaire AND vh.volume_horaire_ref_id IS NOT NULL AND NOT vh.structure_is_affectation AND NOT vh.structure_is_univ THEN
          RETURN vh.heures * cell('AD',l);
        ELSE
          RETURN 0;
        END IF;



      -- DH15=SUM([.DG$1:.DG$1048576])
      WHEN 'DH15' THEN
        RETURN calcFnc('somme','DG');



      -- DH16=MIN([.DH15];[.DB17])
      WHEN 'DH16' THEN
        RETURN LEAST(cell('DH15'), cell('DB17'));



      -- DH17=[.DB17]-[.DH16]
      WHEN 'DH17' THEN
        RETURN cell('DB17') - cell('DH16');



      -- DH=IF([.DH$15]>0;[.DG20]/[.DH$15];0)
      WHEN 'DH' THEN
        IF cell('DH15') > 0 THEN
          RETURN cell('DG',l) / cell('DH15');
        ELSE
          RETURN 0;
        END IF;



      -- DI=[.DH$16]*[.DH20]
      WHEN 'DI' THEN
        RETURN cell('DH16') * cell('DH',l);



      -- DJ=IF([.DH$17]=0;([.DG20]-[.DI20])/[.$AD20];0)
      WHEN 'DJ' THEN
        IF cell('DH17') = 0 THEN
          RETURN (cell('DG',l) - cell('DI',l)) / cell('AD',l);
        ELSE
          RETURN 0;
        END IF;



      -- DK=IF(i_depassement_service_du_sans_hc="Non";[.DJ20]*[.$AE20];0)
      WHEN 'DK' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('DJ',l) * cell('AE',l);
        ELSE
          RETURN 0;
        END IF;



      -- DM=IF(AND([.$D20]="Oui";[.$H20]="Référentiel";[.$A20]=[.$K$10]);[.$M20]*[.$AD20];0)
      WHEN 'DM' THEN
        IF vh.service_statutaire AND vh.volume_horaire_ref_id IS NOT NULL AND vh.structure_is_univ THEN
          RETURN vh.heures * cell('AD',l);
        ELSE
          RETURN 0;
        END IF;



      -- DN15=SUM([.DM$1:.DM$1048576])
      WHEN 'DN15' THEN
        RETURN calcFnc('somme','DM');



      -- DN16=MIN([.DN15];[.DH17])
      WHEN 'DN16' THEN
        RETURN LEAST(cell('DN15'), cell('DH17'));



      -- DN17=[.DH17]-[.DN16]
      WHEN 'DN17' THEN
        RETURN cell('DH17') - cell('DN16');



      -- DN=IF([.DN$15]>0;[.DM20]/[.DN$15];0)
      WHEN 'DN' THEN
        IF cell('DN15') > 0 THEN
          RETURN cell('DM',l) / cell('DN15');
        ELSE
          RETURN 0;
        END IF;



      -- DO=[.DN$16]*[.DN20]
      WHEN 'DO' THEN
        RETURN cell('DN16') * cell('DN',l);



      -- DP=IF([.DN$17]=0;([.DM20]-[.DO20])/[.$AD20];0)
      WHEN 'DP' THEN
        IF cell('DN17') = 0 THEN
          RETURN (cell('DM',l) - cell('DO',l)) / cell('AD',l);
        ELSE
          RETURN 0;
        END IF;



      -- DQ=IF(i_depassement_service_du_sans_hc="Non";[.DP20]*[.$AE20];0)
      WHEN 'DQ' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('DP',l) * cell('AE',l);
        ELSE
          RETURN 0;
        END IF;



      -- DS=IF([.$D20]="Oui";0;IF([.$DN$17]=0;[.$M20];0))
      WHEN 'DS' THEN
        IF vh.service_statutaire THEN
          RETURN 0;
        ELSE
          IF cell('DN17') = 0 THEN
            RETURN vh.heures;
          ELSE
            RETURN 0;
          END IF;
        END IF;




    ELSE
      dbms_output.put_line('La colonne c=' || c || ', l=' || l || ' n''existe pas!');
      raise_application_error( -20001, 'La colonne c=' || c || ', l=' || l || ' n''existe pas!');
  END CASE;
  raise_application_error( -20001, 'La colonne c=' || c || ', l=' || l || ' n''existe pas!');

  END;



  PROCEDURE CALCUL_RESULTAT IS
  BEGIN
    feuille.delete;

    IF ose_formule.intervenant.annee_id < 2023 THEN
      FORMULE_REUNION_2022.CALCUL_RESULTAT;
      RETURN;
    END IF;

    IF ose_formule.intervenant.depassement_service_du_sans_hc THEN -- HC traitées comme du service
      ose_formule.intervenant.service_du := ose_formule.intervenant.heures_service_statutaire + ose_formule.intervenant.heures_service_modifie;
    END IF;

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
      ordre
    ';
  END;

END FORMULE_REUNION;