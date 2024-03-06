CREATE OR REPLACE PACKAGE BODY FORMULE_AVIGNON AS
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



      -- U=IF([.$I20]="Référentiel";0;([.$AP20]+[.$AV20]+[.$BB20]+[.$BH20]+[.$BN20]+[.$BT20]+[.$BZ20]+[.$CF20])*[.F20])
      WHEN 'U' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AP',l) + cell('AV',l) + cell('BB',l) + cell('BH',l) + cell('BN',l) + cell('BT',l) + cell('BZ',l) + cell('CF',l)) * vh.taux_fi;
        END IF;



      -- V=IF([.$I20]="Référentiel";0;([.$AP20]+[.$AV20]+[.$BB20]+[.$BH20]+[.$BN20]+[.$BT20]+[.$BZ20]+[.$CF20])*[.G20])
      WHEN 'V' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AP',l) + cell('AV',l) + cell('BB',l) + cell('BH',l) + cell('BN',l) + cell('BT',l) + cell('BZ',l) + cell('CF',l)) * vh.taux_fa;
        END IF;



      -- W=IF([.$I20]="Référentiel";0;([.$AP20]+[.$AV20]+[.$BB20]+[.$BH20]+[.$BN20]+[.$BT20]+[.$BZ20]+[.$CF20])*[.H20])
      WHEN 'W' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AP',l) + cell('AV',l) + cell('BB',l) + cell('BH',l) + cell('BN',l) + cell('BT',l) + cell('BZ',l) + cell('CF',l)) * vh.taux_fc;
        END IF;



      -- X=IF([.$I20]="Référentiel";[.$AJ20];0)
      WHEN 'X' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN cell('AJ',l);
        ELSE
          RETURN 0;
        END IF;



      -- Y=IF([.$I20]="Référentiel";0;([.$AR20]+[.$AX20]+[.$BD20]+[.$BJ20]+[.$BP20]+[.$BV20]+[.$CB20]+[.$CH20])*[.F20])
      WHEN 'Y' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AR',l) + cell('AX',l) + cell('BD',l) + cell('BJ',l) + cell('BP',l) + cell('BV',l) + cell('CB',l) + cell('CH',l)) * vh.taux_fi;
        END IF;



      -- Z=IF([.$I20]="Référentiel";0;([.$AR20]+[.$AX20]+[.$BD20]+[.$BJ20]+[.$BP20]+[.$BV20]+[.$CB20]+[.$CH20])*[.G20])
      WHEN 'Z' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AR',l) + cell('AX',l) + cell('BD',l) + cell('BJ',l) + cell('BP',l) + cell('BV',l) + cell('CB',l) + cell('CH',l)) * vh.taux_fa;
        END IF;



      -- AA=IF([.$I20]="Référentiel";0;([.$AR20]+[.$AX20]+[.$BD20]+[.$BJ20]+[.$BP20]+[.$BV20]+[.$CB20]+[.$CH20])*[.H20])
      WHEN 'AA' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AR',l) + cell('AX',l) + cell('BD',l) + cell('BJ',l) + cell('BP',l) + cell('BV',l) + cell('CB',l) + cell('CH',l)) * vh.taux_fc;
        END IF;



      -- AB=0
      WHEN 'AB' THEN
        RETURN 0;



      -- AC=IF([.$I20]="Référentiel";[.$AL20];0)
      WHEN 'AC' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN cell('AL',l);
        ELSE
          RETURN 0;
        END IF;



      -- AE=IF(ISERROR([.J20]);1;[.J20])
      WHEN 'AE' THEN
        RETURN vh.taux_service_du;



      -- AF=IF(ISERROR([.K20]);1;[.K20])
      WHEN 'AF' THEN
        RETURN vh.taux_service_compl;



      -- AH=IF(AND([.$E20]="Oui";[.$I20]="Référentiel");[.$N20]*[.$AE20];0)
      WHEN 'AH' THEN
        IF vh.service_statutaire AND vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN vh.heures * cell('AE',l);
        ELSE
          RETURN 0;
        END IF;



      -- AI15=SUM([.AH$1:.AH$1048576])
      WHEN 'AI15' THEN
        RETURN calcFnc('somme','AH');



      -- AI16=MIN([.AI15];i_service_du)
      WHEN 'AI16' THEN
        RETURN LEAST(cell('AI15'), i.service_du);



      -- AI17=i_service_du-[.AI16]
      WHEN 'AI17' THEN
        RETURN i.service_du - cell('AI16');



      -- AI=IF([.AI$15]>0;[.AH20]/[.AI$15];0)
      WHEN 'AI' THEN
        IF cell('AI15') > 0 THEN
          RETURN cell('AH',l) / cell('AI15');
        ELSE
          RETURN 0;
        END IF;



      -- AJ=[.AI$16]*[.AI20]
      WHEN 'AJ' THEN
        RETURN cell('AI16') * cell('AI',l);



      -- AK=IF([.AI$17]=0;([.AH20]-[.AJ20])/[.$AE20];0)
      WHEN 'AK' THEN
        IF cell('AI17') = 0 THEN
          RETURN (cell('AH',l) - cell('AJ',l)) / cell('AE',l);
        ELSE
          RETURN 0;
        END IF;



      -- AL=IF(i_depassement_service_du_sans_hc="Non";[.AK20]*[.$AF20];0)
      WHEN 'AL' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('AK',l) * cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- AN=IF(AND([.$E20]="Oui";[.$D20]<>"Oui";[.$B20]="Oui";[.$A20]<>"5";[.$I20]<>"Référentiel";[.$P20]<>"S2");[.$N20]*[.$AE20];0)
      WHEN 'AN' THEN
        IF vh.service_statutaire AND NOT vh.structure_is_exterieur AND vh.structure_is_affectation AND vh.structure_code <> '5' AND vh.volume_horaire_ref_id IS NULL AND vh.param_2 <> 'S2' THEN
          RETURN vh.heures * cell('AE',l);
        ELSE
          RETURN 0;
        END IF;



      -- AO15=SUM([.AN$1:.AN$1048576])
      WHEN 'AO15' THEN
        RETURN calcFnc('somme','AN');



      -- AO16=MIN([.AO15];[.AI17])
      WHEN 'AO16' THEN
        RETURN LEAST(cell('AO15'), cell('AI17'));



      -- AO17=[.AI17]-[.AO16]
      WHEN 'AO17' THEN
        RETURN cell('AI17') - cell('AO16');



      -- AO=IF([.AO$15]>0;[.AN20]/[.AO$15];0)
      WHEN 'AO' THEN
        IF cell('AO15') > 0 THEN
          RETURN cell('AN',l) / cell('AO15');
        ELSE
          RETURN 0;
        END IF;



      -- AP=[.AO$16]*[.AO20]
      WHEN 'AP' THEN
        RETURN cell('AO16') * cell('AO',l);



      -- AQ=IF([.AO$17]=0;([.AN20]-[.AP20])/[.$AE20];0)
      WHEN 'AQ' THEN
        IF cell('AO17') = 0 THEN
          RETURN (cell('AN',l) - cell('AP',l)) / cell('AE',l);
        ELSE
          RETURN 0;
        END IF;



      -- AR=IF(i_depassement_service_du_sans_hc="Non";[.AQ20]*[.$AF20];0)
      WHEN 'AR' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('AQ',l) * cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- AT=IF(AND([.$E20]="Oui";[.$D20]<>"Oui";[.$B20]="Oui";[.$A20]<>"5";[.$I20]<>"Référentiel";[.$P20]<>"S1");[.$N20]*[.$AE20];0)
      WHEN 'AT' THEN
        IF vh.service_statutaire AND NOT vh.structure_is_exterieur AND vh.structure_is_affectation AND vh.structure_code <> '5' AND vh.volume_horaire_ref_id IS NULL AND vh.param_2 <> 'S1' THEN
          RETURN vh.heures * cell('AE',l);
        ELSE
          RETURN 0;
        END IF;



      -- AU15=SUM([.AT$1:.AT$1048576])
      WHEN 'AU15' THEN
        RETURN calcFnc('somme','AT');



      -- AU16=MIN([.AU15];[.AO17])
      WHEN 'AU16' THEN
        RETURN LEAST(cell('AU15'), cell('AO17'));



      -- AU17=[.AO17]-[.AU16]
      WHEN 'AU17' THEN
        RETURN cell('AO17') - cell('AU16');



      -- AU=IF([.AU$15]>0;[.AT20]/[.AU$15];0)
      WHEN 'AU' THEN
        IF cell('AU15') > 0 THEN
          RETURN cell('AT',l) / cell('AU15');
        ELSE
          RETURN 0;
        END IF;



      -- AV=[.AU$16]*[.AU20]
      WHEN 'AV' THEN
        RETURN cell('AU16') * cell('AU',l);



      -- AW=IF([.AU$17]=0;([.AT20]-[.AV20])/[.$AE20];0)
      WHEN 'AW' THEN
        IF cell('AU17') = 0 THEN
          RETURN (cell('AT',l) - cell('AV',l)) / cell('AE',l);
        ELSE
          RETURN 0;
        END IF;



      -- AX=IF(i_depassement_service_du_sans_hc="Non";[.AW20]*[.$AF20];0)
      WHEN 'AX' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('AW',l) * cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- AZ=IF(AND([.$A20]="5";[.$E20]="Oui";[.$O20]="Oui";[.$I20]<>"Référentiel";[.$P20]<>"S2");[.$N20]*[.$AE20];0)
      WHEN 'AZ' THEN
        IF vh.structure_code = '5' AND vh.service_statutaire AND vh.param_1 = 'Oui' AND vh.volume_horaire_ref_id IS NULL AND vh.param_2 <> 'S2' THEN
          RETURN vh.heures * cell('AE',l);
        ELSE
          RETURN 0;
        END IF;



      -- BA15=SUM([.AZ$1:.AZ$1048576])
      WHEN 'BA15' THEN
        RETURN calcFnc('somme','AZ');



      -- BA16=MIN([.BA15];[.AU17])
      WHEN 'BA16' THEN
        RETURN LEAST(cell('BA15'), cell('AU17'));



      -- BA17=[.AU17]-[.BA16]
      WHEN 'BA17' THEN
        RETURN cell('AU17') - cell('BA16');



      -- BA=IF([.BA$15]>0;[.AZ20]/[.BA$15];0)
      WHEN 'BA' THEN
        IF cell('BA15') > 0 THEN
          RETURN cell('AZ',l) / cell('BA15');
        ELSE
          RETURN 0;
        END IF;



      -- BB=[.BA20]*[.BA$16]
      WHEN 'BB' THEN
        RETURN cell('BA',l) * cell('BA16');



      -- BC=IF([.BA$17]=0;([.AZ20]-[.BB20])/[.$AE20];0)
      WHEN 'BC' THEN
        IF cell('BA17') = 0 THEN
          RETURN (cell('AZ',l) - cell('BB',l)) / cell('AE',l);
        ELSE
          RETURN 0;
        END IF;



      -- BD=IF(i_depassement_service_du_sans_hc="Non";[.BC20]*[.$AF20];0)
      WHEN 'BD' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('BC',l) * cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- BF=IF(AND([.$A20]="5";[.$E20]="Oui";[.$O20]="Oui";[.$I20]<>"Référentiel";[.$P20]<>"S1");[.$N20]*[.$AE20];0)
      WHEN 'BF' THEN
        IF vh.structure_code = '5' AND vh.service_statutaire AND vh.param_1 = 'Oui' AND vh.volume_horaire_ref_id IS NULL AND vh.param_2 <> 'S1' THEN
          RETURN vh.heures * cell('AE',l);
        ELSE
          RETURN 0;
        END IF;



      -- BG15=SUM([.BF$1:.BF$1048576])
      WHEN 'BG15' THEN
        RETURN calcFnc('somme','BF');



      -- BG16=MIN([.BG15];[.BA17])
      WHEN 'BG16' THEN
        RETURN LEAST(cell('BG15'), cell('BA17'));



      -- BG17=[.BA17]-[.BG16]
      WHEN 'BG17' THEN
        RETURN cell('BA17') - cell('BG16');



      -- BG=IF([.BG$15]>0;[.BF20]/[.BG$15];0)
      WHEN 'BG' THEN
        IF cell('BG15') > 0 THEN
          RETURN cell('BF',l) / cell('BG15');
        ELSE
          RETURN 0;
        END IF;



      -- BH=[.BG20]*[.BG$16]
      WHEN 'BH' THEN
        RETURN cell('BG',l) * cell('BG16');



      -- BI=IF([.BG$17]=0;([.BF20]-[.BH20])/[.$AE20];0)
      WHEN 'BI' THEN
        IF cell('BG17') = 0 THEN
          RETURN (cell('BF',l) - cell('BH',l)) / cell('AE',l);
        ELSE
          RETURN 0;
        END IF;



      -- BJ=IF(i_depassement_service_du_sans_hc="Non";[.BI20]*[.$AF20];0)
      WHEN 'BJ' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('BI',l) * cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- BL=IF(AND([.$A20]="5";[.$O20]="Oui");0;IF(AND([.$E20]="Oui";[.$D20]<>"Oui";[.$B20]<>"Oui";[.$I20]<>"Référentiel";[.$P20]<>"S2");[.$N20]*[.$AE20];0))
      WHEN 'BL' THEN
        IF vh.structure_code = '5' AND vh.param_1 = 'Oui' THEN
          RETURN 0;
        ELSE
          IF vh.service_statutaire AND NOT vh.structure_is_exterieur AND NOT vh.structure_is_affectation AND vh.volume_horaire_ref_id IS NULL AND vh.param_2 <> 'S2' THEN
            RETURN vh.heures * cell('AE',l);
          ELSE
            RETURN 0;
          END IF;
        END IF;



      -- BM15=SUM([.BL$1:.BL$1048576])
      WHEN 'BM15' THEN
        RETURN calcFnc('somme','BL');



      -- BM16=MIN([.BM15];[.BG17])
      WHEN 'BM16' THEN
        RETURN LEAST(cell('BM15'), cell('BG17'));



      -- BM17=[.BG17]-[.BM16]
      WHEN 'BM17' THEN
        RETURN cell('BG17') - cell('BM16');



      -- BM=IF([.BM$15]>0;[.BL20]/[.BM$15];0)
      WHEN 'BM' THEN
        IF cell('BM15') > 0 THEN
          RETURN cell('BL',l) / cell('BM15');
        ELSE
          RETURN 0;
        END IF;



      -- BN=[.BM$16]*[.BM20]
      WHEN 'BN' THEN
        RETURN cell('BM16') * cell('BM',l);



      -- BO=IF([.BM$17]=0;([.BL20]-[.BN20])/[.$AE20];0)
      WHEN 'BO' THEN
        IF cell('BM17') = 0 THEN
          RETURN (cell('BL',l) - cell('BN',l)) / cell('AE',l);
        ELSE
          RETURN 0;
        END IF;



      -- BP=IF(i_depassement_service_du_sans_hc="Non";[.BO20]*[.$AF20];0)
      WHEN 'BP' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('BO',l) * cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- BR=IF(AND([.$A20]="5";[.$O20]="Oui");0;IF(AND([.$E20]="Oui";[.$D20]<>"Oui";[.$B20]<>"Oui";[.$I20]<>"Référentiel";[.$P20]<>"S1");[.$N20]*[.$AE20];0))
      WHEN 'BR' THEN
        IF vh.structure_code = '5' AND vh.param_1 = 'Oui' THEN
          RETURN 0;
        ELSE
          IF vh.service_statutaire AND NOT vh.structure_is_exterieur AND NOT vh.structure_is_affectation AND vh.volume_horaire_ref_id IS NULL AND vh.param_2 <> 'S1' THEN
            RETURN vh.heures * cell('AE',l);
          ELSE
            RETURN 0;
          END IF;
        END IF;



      -- BS15=SUM([.BR$1:.BR$1048576])
      WHEN 'BS15' THEN
        RETURN calcFnc('somme','BR');



      -- BS16=MIN([.BS15];[.BM17])
      WHEN 'BS16' THEN
        RETURN LEAST(cell('BS15'), cell('BM17'));



      -- BS17=[.BM17]-[.BS16]
      WHEN 'BS17' THEN
        RETURN cell('BM17') - cell('BS16');



      -- BS=IF([.BS$15]>0;[.BR20]/[.BS$15];0)
      WHEN 'BS' THEN
        IF cell('BS15') > 0 THEN
          RETURN cell('BR',l) / cell('BS15');
        ELSE
          RETURN 0;
        END IF;



      -- BT=[.BS$16]*[.BS20]
      WHEN 'BT' THEN
        RETURN cell('BS16') * cell('BS',l);



      -- BU=IF([.BS$17]=0;([.BR20]-[.BT20])/[.$AE20];0)
      WHEN 'BU' THEN
        IF cell('BS17') = 0 THEN
          RETURN (cell('BR',l) - cell('BT',l)) / cell('AE',l);
        ELSE
          RETURN 0;
        END IF;



      -- BV=IF(i_depassement_service_du_sans_hc="Non";[.BU20]*[.$AF20];0)
      WHEN 'BV' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('BU',l) * cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- BX=IF(AND([.$A20]="5";[.$O20]="Oui");0;IF(AND([.$E20]="Oui";[.$D20]="Oui";[.$I20]<>"Référentiel";[.$P20]<>"S2");[.$N20]*[.$AE20];0))
      WHEN 'BX' THEN
        IF vh.structure_code = '5' AND vh.param_1 = 'Oui' THEN
          RETURN 0;
        ELSE
          IF vh.service_statutaire AND vh.structure_is_exterieur AND vh.volume_horaire_ref_id IS NULL AND vh.param_2 <> 'S2' THEN
            RETURN vh.heures * cell('AE',l);
          ELSE
            RETURN 0;
          END IF;
        END IF;



      -- BY15=SUM([.BX$1:.BX$1048576])
      WHEN 'BY15' THEN
        RETURN calcFnc('somme','BX');



      -- BY16=MIN([.BY15];[.BS17])
      WHEN 'BY16' THEN
        RETURN LEAST(cell('BY15'), cell('BS17'));



      -- BY17=[.BS17]-[.BY16]
      WHEN 'BY17' THEN
        RETURN cell('BS17') - cell('BY16');



      -- BY=IF([.BY$15]>0;[.BX20]/[.BY$15];0)
      WHEN 'BY' THEN
        IF cell('BY15') > 0 THEN
          RETURN cell('BX',l) / cell('BY15');
        ELSE
          RETURN 0;
        END IF;



      -- BZ=[.BY20]*[.BY$16]
      WHEN 'BZ' THEN
        RETURN cell('BY',l) * cell('BY16');



      -- CA=IF([.BY$17]=0;([.BX20]-[.BZ20])/[.$AE20];0)
      WHEN 'CA' THEN
        IF cell('BY17') = 0 THEN
          RETURN (cell('BX',l) - cell('BZ',l)) / cell('AE',l);
        ELSE
          RETURN 0;
        END IF;



      -- CB=IF(i_depassement_service_du_sans_hc="Non";[.CA20]*[.$AF20];0)
      WHEN 'CB' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('CA',l) * cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- CD=IF(AND([.$A20]="5";[.$O20]="Oui");0;IF(AND([.$E20]="Oui";[.$D20]="Oui";[.$I20]<>"Référentiel";[.$P20]<>"S1");[.$N20]*[.$AE20];0))
      WHEN 'CD' THEN
        IF vh.structure_code = '5' AND vh.param_1 = 'Oui' THEN
          RETURN 0;
        ELSE
          IF vh.service_statutaire AND vh.structure_is_exterieur AND vh.volume_horaire_ref_id IS NULL AND vh.param_2 <> 'S1' THEN
            RETURN vh.heures * cell('AE',l);
          ELSE
            RETURN 0;
          END IF;
        END IF;



      -- CE15=SUM([.CD$1:.CD$1048576])
      WHEN 'CE15' THEN
        RETURN calcFnc('somme','CD');



      -- CE16=MIN([.CE15];[.BY17])
      WHEN 'CE16' THEN
        RETURN LEAST(cell('CE15'), cell('BY17'));



      -- CE17=[.BY17]-[.CE16]
      WHEN 'CE17' THEN
        RETURN cell('BY17') - cell('CE16');



      -- CE=IF([.CE$15]>0;[.CD20]/[.CE$15];0)
      WHEN 'CE' THEN
        IF cell('CE15') > 0 THEN
          RETURN cell('CD',l) / cell('CE15');
        ELSE
          RETURN 0;
        END IF;



      -- CF=[.CE20]*[.CE$16]
      WHEN 'CF' THEN
        RETURN cell('CE',l) * cell('CE16');



      -- CG=IF([.CE$17]=0;([.CD20]-[.CF20])/[.$AE20];0)
      WHEN 'CG' THEN
        IF cell('CE17') = 0 THEN
          RETURN (cell('CD',l) - cell('CF',l)) / cell('AE',l);
        ELSE
          RETURN 0;
        END IF;



      -- CH=IF(i_depassement_service_du_sans_hc="Non";[.CG20]*[.$AF20];0)
      WHEN 'CH' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('CG',l) * cell('AF',l);
        ELSE
          RETURN 0;
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

    IF ose_formule.intervenant.depassement_service_du_sans_hc THEN -- HC traitées comme du service
      ose_formule.intervenant.service_du := ose_formule.intervenant.heures_service_statutaire + ose_formule.intervenant.heures_service_modifie;
    END IF;

    -- transmission des résultats aux volumes horaires et volumes horaires référentiel
    FOR l IN 1 .. ose_formule.volumes_horaires.length LOOP
      ose_formule.volumes_horaires.items(l).service_fi               := mainCell('Service FI', 'U',l);
      ose_formule.volumes_horaires.items(l).service_fa               := mainCell('Service FA', 'V',l);
      ose_formule.volumes_horaires.items(l).service_fc               := mainCell('Service FC', 'W',l);
      ose_formule.volumes_horaires.items(l).service_referentiel      := mainCell('Service référentiel', 'X',l);
      ose_formule.volumes_horaires.items(l).heures_compl_fi          := mainCell('Heures compl. FI', 'Y',l);
      ose_formule.volumes_horaires.items(l).heures_compl_fa          := mainCell('Heures compl. FA', 'Z',l);
      ose_formule.volumes_horaires.items(l).heures_compl_fc          := mainCell('Heures compl. FC', 'AA',l);
      ose_formule.volumes_horaires.items(l).heures_primes            := mainCell('Heures compl. FC Maj.', 'AB',l);
      ose_formule.volumes_horaires.items(l).heures_compl_referentiel := mainCell('Heures compl. référentiel', 'AC',l);
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
      CASE tf.source_code WHEN ''LIC PRO'' THEN ''Oui'' ELSE ''Non'' END param_1,
      p.code param_2,
      NULL param_3,
      NULL param_4,
      NULL param_5
    FROM
      V_FORMULE_VOLUME_HORAIRE fvh
      LEFT JOIN volume_horaire vh ON vh.id = fvh.volume_horaire_id
      LEFT JOIN periode p ON p.id = vh.periode_id
      LEFT JOIN service s ON s.id = fvh.service_id
      LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
      LEFT JOIN etape e ON e.id = ep.etape_id
      LEFT JOIN type_formation tf ON tf.id = e.type_formation_id
    ORDER BY
      fvh.ordre
    ';
  END;

END FORMULE_AVIGNON;