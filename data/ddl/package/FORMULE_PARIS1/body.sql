CREATE OR REPLACE PACKAGE BODY FORMULE_PARIS1 AS
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



      -- U=IF([.$I20]="Référentiel";0;([.$AK20]+[.$AQ20]+[.$AW20]+[.$BO20]+[.$BU20]))
      WHEN 'U' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AK',l) + cell('AQ',l) + cell('AW',l) + cell('BO',l) + cell('BU',l));
        END IF;



      -- V=IF([.$I20]="Référentiel";0;([.$BC20]+[.$BI20]+[.$BU20]+[.$BO20]))
      WHEN 'V' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('BC',l) + cell('BI',l) + cell('BU',l) + cell('BO',l));
        END IF;



      -- W=IF([.$I20]="Référentiel";0;([.$CA20]+[.$CG20]+[.$BO20]+[.$BU20]))
      WHEN 'W' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('CA',l) + cell('CG',l) + cell('BO',l) + cell('BU',l));
        END IF;



      -- X=IF([.$I20]="Référentiel";[.$CM20]+[.$CS20];0)
      WHEN 'X' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN cell('CM',l) + cell('CS',l);
        ELSE
          RETURN 0;
        END IF;



      -- Y=IF([.$I20]="Référentiel";0;([.$AM20]+[.$AS20]+[.$AY20]+[.$BQ20]+[.$BW20]))
      WHEN 'Y' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AM',l) + cell('AS',l) + cell('AY',l) + cell('BQ',l) + cell('BW',l));
        END IF;



      -- Z=IF([.$I20]="Référentiel";0;([.$BE20]+[.$BK20]))
      WHEN 'Z' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('BE',l) + cell('BK',l));
        END IF;



      -- AA=IF([.$I20]="Référentiel";0;([.$CC20]+[.$CI20]))
      WHEN 'AA' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('CC',l) + cell('CI',l));
        END IF;



      -- AB=0
      WHEN 'AB' THEN
        RETURN 0;



      -- AC=IF([.$I20]="Référentiel";[.$CO20]+[.$CU20];0)
      WHEN 'AC' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN cell('CO',l) + cell('CU',l);
        ELSE
          RETURN 0;
        END IF;



      -- AE=IF(AND([.$E20]="Oui";[.$I20]<>"Référentiel";[.$O20]="Oui");[.$N20]*[.$AF20];0)
      WHEN 'AE' THEN
        IF vh.service_statutaire AND vh.volume_horaire_ref_id IS NULL AND vh.param_1 = 'Oui' THEN
          RETURN vh.heures * cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- AF13=SUM([.AE20:.AE500])
      WHEN 'AF13' THEN
        RETURN calcFnc('somme','AE');



      -- AF=IF(ISERROR([.J20]);1;[.J20])
      WHEN 'AF' THEN
        RETURN vh.taux_service_du;



      -- AG=IF(ISERROR([.K20]);1;[.K20])
      WHEN 'AG' THEN
        RETURN vh.taux_service_compl;



      -- AI=IF(AND([.$E20]="Oui";[.$D20]="Oui";[.$I20]<>"Référentiel");[.$N20]*[.$AF20];0)
      WHEN 'AI' THEN
        IF vh.service_statutaire AND vh.structure_is_exterieur AND vh.volume_horaire_ref_id IS NULL THEN
          RETURN vh.heures * cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- AJ15=SUM([.AI$1:.AI$1048576])
      WHEN 'AJ15' THEN
        RETURN calcFnc('somme','AI');



      -- AJ16=MIN([.AJ15];i_service_du)
      WHEN 'AJ16' THEN
        RETURN LEAST(cell('AJ15'), i.service_du);



      -- AJ17=i_service_du-[.AJ16]
      WHEN 'AJ17' THEN
        RETURN i.service_du - cell('AJ16');



      -- AJ=IF([.AJ$15]>0;[.AI20]/[.AJ$15];0)
      WHEN 'AJ' THEN
        IF cell('AJ15') > 0 THEN
          RETURN cell('AI',l) / cell('AJ15');
        ELSE
          RETURN 0;
        END IF;



      -- AK=[.AJ$16]*[.AJ20]
      WHEN 'AK' THEN
        RETURN cell('AJ16') * cell('AJ',l);



      -- AL=IF([.AJ$17]=0;([.AI20]-[.AK20])/[.$AF20];0)
      WHEN 'AL' THEN
        IF cell('AJ17') = 0 THEN
          RETURN (cell('AI',l) - cell('AK',l)) / cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- AM=IF(i_depassement_service_du_sans_hc="Non";[.AL20]*[.$AG20];0)
      WHEN 'AM' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('AL',l) * cell('AG',l);
        ELSE
          RETURN 0;
        END IF;



      -- AO=IF(AND([.$E20]="Oui";[.$B20]="Oui";[.$A20]=i_structure_code;[.$I20]<>"Référentiel";[.$O20]<>"Oui");[.$N20]*[.$F20]*[.$AF20];0)
      WHEN 'AO' THEN
        IF vh.service_statutaire AND vh.structure_is_affectation AND vh.structure_is_affectation AND vh.volume_horaire_ref_id IS NULL AND vh.param_1 <> 'Oui' THEN
          RETURN vh.heures * vh.taux_fi * cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- AP15=SUM([.AO$1:.AO$1048576])
      WHEN 'AP15' THEN
        RETURN calcFnc('somme','AO');



      -- AP16=MIN([.AP15];[.AJ17])
      WHEN 'AP16' THEN
        RETURN LEAST(cell('AP15'), cell('AJ17'));



      -- AP17=[.AJ17]-[.AP16]
      WHEN 'AP17' THEN
        RETURN cell('AJ17') - cell('AP16');



      -- AP=IF([.AP$15]>0;[.AO20]/[.AP$15];0)
      WHEN 'AP' THEN
        IF cell('AP15') > 0 THEN
          RETURN cell('AO',l) / cell('AP15');
        ELSE
          RETURN 0;
        END IF;



      -- AQ=[.AP$16]*[.AP20]
      WHEN 'AQ' THEN
        RETURN cell('AP16') * cell('AP',l);



      -- AR=IF([.AP$17]=0;([.AO20]-[.AQ20])/[.$AF20];0)
      WHEN 'AR' THEN
        IF cell('AP17') = 0 THEN
          RETURN (cell('AO',l) - cell('AQ',l)) / cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- AS=IF(i_depassement_service_du_sans_hc="Non";[.AR20]*[.$AG20];0)
      WHEN 'AS' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('AR',l) * cell('AG',l);
        ELSE
          RETURN 0;
        END IF;



      -- AU=IF(AND([.$E20]="Oui";[.$I20]<>"Référentiel";[.$A20]<>i_structure_code;[.$O20]<>"Oui";[.$D20]="Non");[.$N20]*[.$F20]*[.$AF20];0)
      WHEN 'AU' THEN
        IF vh.service_statutaire AND vh.volume_horaire_ref_id IS NULL AND NOT vh.structure_is_affectation AND vh.param_1 <> 'Oui' AND NOT vh.structure_is_exterieur THEN
          RETURN vh.heures * vh.taux_fi * cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- AV15=SUM([.AU$1:.AU$1048576])
      WHEN 'AV15' THEN
        RETURN calcFnc('somme','AU');



      -- AV16=MIN([.AV15];[.AP17])
      WHEN 'AV16' THEN
        RETURN LEAST(cell('AV15'), cell('AP17'));



      -- AV17=[.AP17]-[.AV16]
      WHEN 'AV17' THEN
        RETURN cell('AP17') - cell('AV16');



      -- AV=IF([.AV$15]>0;[.AU20]/[.AV$15];0)
      WHEN 'AV' THEN
        IF cell('AV15') > 0 THEN
          RETURN cell('AU',l) / cell('AV15');
        ELSE
          RETURN 0;
        END IF;



      -- AW=[.AV$16]*[.AV20]
      WHEN 'AW' THEN
        RETURN cell('AV16') * cell('AV',l);



      -- AX=IF([.AV$17]=0;([.AU20]-[.AW20])/[.$AF20];0)
      WHEN 'AX' THEN
        IF cell('AV17') = 0 THEN
          RETURN (cell('AU',l) - cell('AW',l)) / cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- AY=IF(i_depassement_service_du_sans_hc="Non";[.AX20]*[.$AG20];0)
      WHEN 'AY' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('AX',l) * cell('AG',l);
        ELSE
          RETURN 0;
        END IF;



      -- BA=IF(AND([.$E20]="Oui";[.$B20]="Oui";[.$A20]=i_structure_code;[.$I20]<>"Référentiel";[.$O20]<>"Oui");[.$N20]*[.$G20]*[.$AF20];0)
      WHEN 'BA' THEN
        IF vh.service_statutaire AND vh.structure_is_affectation AND vh.structure_is_affectation AND vh.volume_horaire_ref_id IS NULL AND vh.param_1 <> 'Oui' THEN
          RETURN vh.heures * vh.taux_fa * cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- BB15=SUM([.BA$1:.BA$1048576])
      WHEN 'BB15' THEN
        RETURN calcFnc('somme','BA');



      -- BB16=MIN([.BB15];[.AV17])
      WHEN 'BB16' THEN
        RETURN LEAST(cell('BB15'), cell('AV17'));



      -- BB17=[.AV17]-[.BB16]
      WHEN 'BB17' THEN
        RETURN cell('AV17') - cell('BB16');



      -- BB=IF([.BB$15]>0;[.BA20]/[.BB$15];0)
      WHEN 'BB' THEN
        IF cell('BB15') > 0 THEN
          RETURN cell('BA',l) / cell('BB15');
        ELSE
          RETURN 0;
        END IF;



      -- BC=[.BB$16]*[.BB20]
      WHEN 'BC' THEN
        RETURN cell('BB16') * cell('BB',l);



      -- BD=IF([.BB$17]=0;([.BA20]-[.BC20])/[.$AF20];0)
      WHEN 'BD' THEN
        IF cell('BB17') = 0 THEN
          RETURN (cell('BA',l) - cell('BC',l)) / cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- BE=IF(i_depassement_service_du_sans_hc="Non";[.BD20]*[.$AG20];0)
      WHEN 'BE' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('BD',l) * cell('AG',l);
        ELSE
          RETURN 0;
        END IF;



      -- BG=IF(AND([.$E20]="Oui";[.$D20]="Non";[.$I20]<>"Référentiel";[.$A20]<>i_structure_code;[.$O20]<>"Oui");[.$N20]*[.$G20]*[.$AF20];0)
      WHEN 'BG' THEN
        IF vh.service_statutaire AND NOT vh.structure_is_exterieur AND vh.volume_horaire_ref_id IS NULL AND NOT vh.structure_is_affectation AND vh.param_1 <> 'Oui' THEN
          RETURN vh.heures * vh.taux_fa * cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- BH15=SUM([.BG$1:.BG$1048576])
      WHEN 'BH15' THEN
        RETURN calcFnc('somme','BG');



      -- BH16=MIN([.BH15];[.BB17])
      WHEN 'BH16' THEN
        RETURN LEAST(cell('BH15'), cell('BB17'));



      -- BH17=[.BB17]-[.BH16]
      WHEN 'BH17' THEN
        RETURN cell('BB17') - cell('BH16');



      -- BH=IF([.BH$15]>0;[.BG20]/[.BH$15];0)
      WHEN 'BH' THEN
        IF cell('BH15') > 0 THEN
          RETURN cell('BG',l) / cell('BH15');
        ELSE
          RETURN 0;
        END IF;



      -- BI=[.BH$16]*[.BH20]
      WHEN 'BI' THEN
        RETURN cell('BH16') * cell('BH',l);



      -- BJ=IF([.BH$17]=0;([.BG20]-[.BI20])/[.$AF20];0)
      WHEN 'BJ' THEN
        IF cell('BH17') = 0 THEN
          RETURN (cell('BG',l) - cell('BI',l)) / cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- BK=IF(i_depassement_service_du_sans_hc="Non";[.BJ20]*[.$AG20];0)
      WHEN 'BK' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('BJ',l) * cell('AG',l);
        ELSE
          RETURN 0;
        END IF;



      -- BM=IF(AND([.$E20]="Oui";[.$B20]="Oui";[.$A20]=i_structure_code;[.$I20]<>"Référentiel";[.$O20]="Oui");[.$N20]*[.$AF20];0)
      WHEN 'BM' THEN
        IF vh.service_statutaire AND vh.structure_is_affectation AND vh.structure_is_affectation AND vh.volume_horaire_ref_id IS NULL AND vh.param_1 = 'Oui' THEN
          RETURN vh.heures * cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- BN15=SUM([.BM$1:.BM$1048576])
      WHEN 'BN15' THEN
        RETURN calcFnc('somme','BM');



      -- BN16=IF(MIN([.BN15];[.BH17])<=i_service_du*25%;MIN([.BN15];[.BH17]);i_service_du*25%)
      WHEN 'BN16' THEN
        IF LEAST(cell('BN15'), cell('BH17')) <= i.service_du * 25 / 100  THEN
          RETURN LEAST(cell('BN15'), cell('BH17'));
        ELSE
          RETURN i.service_du * 25 / 100 ;
        END IF;



      -- BN17=[.BH17]-[.BN16]
      WHEN 'BN17' THEN
        RETURN cell('BH17') - cell('BN16');



      -- BN=IF([.BN$15]>0;[.BM20]/[.BN$15];0)
      WHEN 'BN' THEN
        IF cell('BN15') > 0 THEN
          RETURN cell('BM',l) / cell('BN15');
        ELSE
          RETURN 0;
        END IF;



      -- BO=[.BN$16]*[.BN20]
      WHEN 'BO' THEN
        RETURN cell('BN16') * cell('BN',l);



      -- BP=IF([.BN$17]=0;([.BM20]-[.BO20])/[.$AF20];0)
      WHEN 'BP' THEN
        IF cell('BN17') = 0 THEN
          RETURN (cell('BM',l) - cell('BO',l)) / cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- BQ=IF(i_depassement_service_du_sans_hc="Non";([.BM20]-[.BO20])*[.$AG20];0)
      WHEN 'BQ' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN (cell('BM',l) - cell('BO',l)) * cell('AG',l);
        ELSE
          RETURN 0;
        END IF;



      -- BS=IF(AND([.$E20]="Oui";[.$D20]="Non";[.$I20]<>"Référentiel";[.$A20]<>i_structure_code;[.$O20]="Oui");[.$N20]*[.$AF20];0)
      WHEN 'BS' THEN
        IF vh.service_statutaire AND NOT vh.structure_is_exterieur AND vh.volume_horaire_ref_id IS NULL AND NOT vh.structure_is_affectation AND vh.param_1 = 'Oui' THEN
          RETURN vh.heures * cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- BT15=SUM([.BS$1:.BS$1048576])
      WHEN 'BT15' THEN
        RETURN calcFnc('somme','BS');



      -- BT16=IF([.$BN16]+[.$BT15]>=i_service_du*25%;i_service_du*25%-[.$BN16];MIN([.BT15];[.BN17]))
      WHEN 'BT16' THEN
        IF cell('BN16') + cell('BT15') >= i.service_du * 25 / 100  THEN
          RETURN i.service_du * 25 / 100  - cell('BN16');
        ELSE
          RETURN LEAST(cell('BT15'), cell('BN17'));
        END IF;



      -- BT17=[.BN17]-[.BT16]
      WHEN 'BT17' THEN
        RETURN cell('BN17') - cell('BT16');



      -- BT=IF([.BT$15]>0;[.BS20]/[.BT$15];0)
      WHEN 'BT' THEN
        IF cell('BT15') > 0 THEN
          RETURN cell('BS',l) / cell('BT15');
        ELSE
          RETURN 0;
        END IF;



      -- BU=[.BT$16]*[.BT20]
      WHEN 'BU' THEN
        RETURN cell('BT16') * cell('BT',l);



      -- BV=IF([.BT$17]=0;([.BS20]-[.BU20])/[.$AF20];0)
      WHEN 'BV' THEN
        IF cell('BT17') = 0 THEN
          RETURN (cell('BS',l) - cell('BU',l)) / cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- BW=IF(i_depassement_service_du_sans_hc="Non";([.BS20]-[.BU20])*[.$AG20];0)
      WHEN 'BW' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN (cell('BS',l) - cell('BU',l)) * cell('AG',l);
        ELSE
          RETURN 0;
        END IF;



      -- BY=IF(AND([.$E20]="Oui";[.$B20]="Oui";[.$A20]=i_structure_code;[.$I20]<>"Référentiel");[.$N20]*[.$H20]*[.$AF20];0)
      WHEN 'BY' THEN
        IF vh.service_statutaire AND vh.structure_is_affectation AND vh.structure_is_affectation AND vh.volume_horaire_ref_id IS NULL THEN
          RETURN vh.heures * vh.taux_fc * cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- BZ15=SUM([.BY$1:.BY$1048576])
      WHEN 'BZ15' THEN
        RETURN calcFnc('somme','BY');



      -- BZ16=MIN([.BZ15];[.BT17])
      WHEN 'BZ16' THEN
        RETURN LEAST(cell('BZ15'), cell('BT17'));



      -- BZ17=[.BT17]-[.BZ16]
      WHEN 'BZ17' THEN
        RETURN cell('BT17') - cell('BZ16');



      -- BZ=IF([.BZ$15]>0;[.BY20]/[.BZ$15];0)
      WHEN 'BZ' THEN
        IF cell('BZ15') > 0 THEN
          RETURN cell('BY',l) / cell('BZ15');
        ELSE
          RETURN 0;
        END IF;



      -- CA=[.BZ$16]*[.BZ20]
      WHEN 'CA' THEN
        RETURN cell('BZ16') * cell('BZ',l);



      -- CB=IF([.BZ$17]=0;([.BY20]-[.CA20])/[.$AF20];0)
      WHEN 'CB' THEN
        IF cell('BZ17') = 0 THEN
          RETURN (cell('BY',l) - cell('CA',l)) / cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- CC=IF(i_depassement_service_du_sans_hc="Non";[.CB20]*[.$AG20];0)
      WHEN 'CC' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('CB',l) * cell('AG',l);
        ELSE
          RETURN 0;
        END IF;



      -- CE=IF(AND([.$E20]="Oui";[.$D20]="Non";[.$I20]<>"Référentiel";[.$A20]<>i_structure_code);[.$N20]*[.$H20]*[.$AF20];0)
      WHEN 'CE' THEN
        IF vh.service_statutaire AND NOT vh.structure_is_exterieur AND vh.volume_horaire_ref_id IS NULL AND NOT vh.structure_is_affectation THEN
          RETURN vh.heures * vh.taux_fc * cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- CF15=SUM([.CE$1:.CE$1048576])
      WHEN 'CF15' THEN
        RETURN calcFnc('somme','CE');



      -- CF16=MIN([.CF15];[.BZ17])
      WHEN 'CF16' THEN
        RETURN LEAST(cell('CF15'), cell('BZ17'));



      -- CF17=[.BZ17]-[.CF16]
      WHEN 'CF17' THEN
        RETURN cell('BZ17') - cell('CF16');



      -- CF=IF([.CF$15]>0;[.CE20]/[.CF$15];0)
      WHEN 'CF' THEN
        IF cell('CF15') > 0 THEN
          RETURN cell('CE',l) / cell('CF15');
        ELSE
          RETURN 0;
        END IF;



      -- CG=[.CF$16]*[.CF20]
      WHEN 'CG' THEN
        RETURN cell('CF16') * cell('CF',l);



      -- CH=IF([.CF$17]=0;([.CE20]-[.CG20])/[.$AF20];0)
      WHEN 'CH' THEN
        IF cell('CF17') = 0 THEN
          RETURN (cell('CE',l) - cell('CG',l)) / cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- CI=IF(i_depassement_service_du_sans_hc="Non";[.CH20]*[.$AG20];0)
      WHEN 'CI' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('CH',l) * cell('AG',l);
        ELSE
          RETURN 0;
        END IF;



      -- CK=IF(AND([.$E20]="Oui";[.$I20]="Référentiel";OR(LEFT([.$P20];4) = "A2 :";LEFT([.$P20];4)="A3 :");LEFT([.$P20];18)<>"A3 : Apprentissage");[.$N20]*[.$AF20];0)
      WHEN 'CK' THEN
        IF vh.service_statutaire AND vh.volume_horaire_ref_id IS NOT NULL AND (SUBSTR(vh.param_2, 1, 4)  =  'A2 :' OR SUBSTR(vh.param_2, 1, 4) = 'A3 :') AND SUBSTR(vh.param_2, 1, 18) <> 'A3 : Apprentissage' THEN
          RETURN vh.heures * cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- CL15=SUM([.CK$1:.CK$1048576])
      WHEN 'CL15' THEN
        RETURN calcFnc('somme','CK');



      -- CL16=MIN([.CL15];[.CF17])
      WHEN 'CL16' THEN
        RETURN LEAST(cell('CL15'), cell('CF17'));



      -- CL17=[.CF17]-[.CL16]
      WHEN 'CL17' THEN
        RETURN cell('CF17') - cell('CL16');



      -- CL=IF([.CL$15]>0;[.CK20]/[.CL$15];0)
      WHEN 'CL' THEN
        IF cell('CL15') > 0 THEN
          RETURN cell('CK',l) / cell('CL15');
        ELSE
          RETURN 0;
        END IF;



      -- CM=[.CL$16]*[.CL20]
      WHEN 'CM' THEN
        RETURN cell('CL16') * cell('CL',l);



      -- CN=IF([.CL$17]=0;([.CK20]-[.CM20])/[.$AF20];0)
      WHEN 'CN' THEN
        IF cell('CL17') = 0 THEN
          RETURN (cell('CK',l) - cell('CM',l)) / cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- CO=IF(i_depassement_service_du_sans_hc="Non";[.CN20]*[.$AG20];[.$CN20])
      WHEN 'CO' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('CN',l) * cell('AG',l);
        ELSE
          RETURN cell('CN',l);
        END IF;



      -- CQ=IF(AND([.$E20]="Oui";[.$I20]="Référentiel";LEFT([.$P20];18)="A3 : Apprentissage");[.$N20]*[.$AF20];0)
      WHEN 'CQ' THEN
        IF vh.service_statutaire AND vh.volume_horaire_ref_id IS NOT NULL AND SUBSTR(vh.param_2, 1, 18) = 'A3 : Apprentissage' THEN
          RETURN vh.heures * cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- CR15=SUM([.CQ$1:.CQ$1048576])
      WHEN 'CR15' THEN
        RETURN calcFnc('somme','CQ');



      -- CR16=IF([.$D9]<>"Autre";MIN([.CR15];[.CL17]);[.CR15])
      WHEN 'CR16' THEN
        IF i.param_1 <> 'Autre' THEN
          RETURN LEAST(cell('CR15'), cell('CL17'));
        ELSE
          RETURN cell('CR15');
        END IF;



      -- CR17=[.CL17]-[.CR16]
      WHEN 'CR17' THEN
        RETURN cell('CL17') - cell('CR16');



      -- CR=IF([.CR$15]>0;[.CQ20]/[.CR$15];0)
      WHEN 'CR' THEN
        IF cell('CR15') > 0 THEN
          RETURN cell('CQ',l) / cell('CR15');
        ELSE
          RETURN 0;
        END IF;



      -- CS=IF([.$D$9]<>"AUTRE";MIN(100;[.CR$16]*[.CR20]);0)
      WHEN 'CS' THEN
        IF i.param_1 <> 'AUTRE' THEN
          RETURN LEAST(100, cell('CR16') * cell('CR',l));
        ELSE
          RETURN 0;
        END IF;



      -- CT=IF([.$D$9]<>"AUTRE";([.CQ20]-[.CS20])/[.$AF20];[.$CQ20])
      WHEN 'CT' THEN
        IF i.param_1 <> 'AUTRE' THEN
          RETURN (cell('CQ',l) - cell('CS',l)) / cell('AF',l);
        ELSE
          RETURN cell('CQ',l);
        END IF;



      -- CU=IF(i_depassement_service_du_sans_hc="Non";[.$CT20]*[.$AG20];[.$CT20])
      WHEN 'CU' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('CT',l) * cell('AG',l);
        ELSE
          RETURN cell('CT',l);
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
      ose_formule.volumes_horaires.items(l).heures_compl_fc_majorees := mainCell('Heures compl. FC Maj.', 'AB',l);
      ose_formule.volumes_horaires.items(l).heures_compl_referentiel := mainCell('Heures compl. référentiel', 'AC',l);
    END LOOP;
  END;



  FUNCTION INTERVENANT_QUERY RETURN CLOB IS
  BEGIN
    RETURN '
    SELECT
      fi.*,
      si.code param_1,
      NULL param_2,
      NULL param_3,
      NULL param_4,
      NULL param_5
    FROM
      V_FORMULE_INTERVENANT fi
      JOIN intervenant i on i.id = fi.intervenant_id
      JOIN statut si ON si.id = i.statut_id
    ';
  END;



  FUNCTION VOLUME_HORAIRE_QUERY RETURN CLOB IS
  BEGIN
    RETURN '
    SELECT
      fvh.*,
      CASE WHEN COALESCE(gtf.libelle_court,'''') = ''DU'' THEN ''Oui'' ELSE ''Non'' END param_1,
      COALESCE(tfr.code,fr.code) param_2,
      NULL param_3,
      NULL param_4,
      NULL param_5
    FROM
      V_FORMULE_VOLUME_HORAIRE fvh
      LEFT JOIN service s ON s.id = fvh.service_id
      LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
      LEFT JOIN etape e ON e.id = ep.etape_id
      LEFT JOIN type_formation tf ON tf.id = e.type_formation_id
      LEFT JOIN groupe_type_formation gtf ON gtf.id = tf.groupe_id
      LEFT JOIN service_referentiel sr ON sr.id = fvh.service_referentiel_id
      LEFT JOIN fonction_referentiel fr ON fr.id = sr.fonction_id
      LEFT JOIN fonction_referentiel tfr ON tfr.id = tfr.parent_id
    ORDER BY
      fvh.ordre
    ';
  END;

END FORMULE_PARIS1;