CREATE OR REPLACE PACKAGE BODY FORMULE_LYON2_2023 AS
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



      -- U=IF([.$I20]="Référentiel";0;([.$AK20]+[.$AR20]+[.$AY20]+[.$BF20]+[.$BT20])*[.F20])
      WHEN 'U' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AK',l) + cell('AR',l) + cell('AY',l) + cell('BF',l) + cell('BT',l)) * vh.taux_fi;
        END IF;



      -- V=IF([.$I20]="Référentiel";0;([.$AK20]+[.$AR20]+[.$AY20]+[.$BF20]+[.$BT20])*[.G20])
      WHEN 'V' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AK',l) + cell('AR',l) + cell('AY',l) + cell('BF',l) + cell('BT',l)) * vh.taux_fa;
        END IF;



      -- W=IF([.$I20]="Référentiel";0;([.$AK20]+[.$AR20]+[.$AY20]+[.$BF20]+[.$BT20])*[.H20])
      WHEN 'W' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AK',l) + cell('AR',l) + cell('AY',l) + cell('BF',l) + cell('BT',l)) * vh.taux_fc;
        END IF;



      -- X=IF([.$I20]="Référentiel";[.$BM20];0)
      WHEN 'X' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN cell('BM',l);
        ELSE
          RETURN 0;
        END IF;



      -- Y=IF([.$I20]="Référentiel";0;([.$AM20]+[.$AT20]+[.$BA20]+[.$BH20]+[.$BV20])*[.F20])
      WHEN 'Y' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AM',l) + cell('AT',l) + cell('BA',l) + cell('BH',l) + cell('BV',l)) * vh.taux_fi;
        END IF;



      -- Z=IF([.$I20]="Référentiel";0;([.$AM20]+[.$AT20]+[.$BA20]+[.$BH20]+[.$BV20])*[.G20])
      WHEN 'Z' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AM',l) + cell('AT',l) + cell('BA',l) + cell('BH',l) + cell('BV',l)) * vh.taux_fa;
        END IF;



      -- AA=IF([.$I20]="Référentiel";0;([.$AM20]+[.$AT20]+[.$BA20]+[.$BH20]+[.$BV20])*[.H20])
      WHEN 'AA' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AM',l) + cell('AT',l) + cell('BA',l) + cell('BH',l) + cell('BV',l)) * vh.taux_fc;
        END IF;



      -- AB=0
      WHEN 'AB' THEN
        RETURN 0;



      -- AC=IF([.$I20]="Référentiel";[.$BO20];0)
      WHEN 'AC' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN cell('BO',l);
        ELSE
          RETURN 0;
        END IF;



      -- AE=IF(ISERROR([.J20]);1;[.J20])
      WHEN 'AE' THEN
        RETURN vh.taux_service_du;



      -- AF=IF(ISERROR([.K20]);1;[.K20])
      WHEN 'AF' THEN
        RETURN vh.taux_service_compl;



      -- AH=IF(AND([.$A20]<>"D4DAC10000";[.$B20]="Oui";[.$D20]<>"Oui";[.$H20]<>1;[.$I20]<>"Référentiel");[.$N20];0)
      WHEN 'AH' THEN
        IF vh.structure_code <> 'D4DAC10000' AND vh.structure_is_affectation AND NOT vh.structure_is_exterieur AND vh.taux_fc <> 1 AND vh.volume_horaire_ref_id IS NULL THEN
          RETURN vh.heures;
        ELSE
          RETURN 0;
        END IF;



      -- AI=[.AH20]*[.$AE20]
      WHEN 'AI' THEN
        RETURN cell('AH',l) * cell('AE',l);



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



      -- AL=IF([.AJ$17]=0;IF([.$AE20]>0;([.AI20]-[.AK20])/[.$AE20];[.AH20]);0)
      WHEN 'AL' THEN
        IF cell('AJ17') = 0 THEN
          IF cell('AE',l) > 0 THEN
            RETURN (cell('AI',l) - cell('AK',l)) / cell('AE',l);
          ELSE
            RETURN cell('AH',l);
          END IF;
        ELSE
          RETURN 0;
        END IF;



      -- AM=IF(i_depassement_service_du_sans_hc="Non";[.AL20]*[.$AF20];0)
      WHEN 'AM' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('AL',l) * cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- AO=IF(AND([.$A20]<>"D4DAC10000";[.$B20]<>"Oui";[.$D20]<>"Oui";[.$H20]<>1;[.$I20]<>"Référentiel");[.$N20];0)
      WHEN 'AO' THEN
        IF vh.structure_code <> 'D4DAC10000' AND NOT vh.structure_is_affectation AND NOT vh.structure_is_exterieur AND vh.taux_fc <> 1 AND vh.volume_horaire_ref_id IS NULL THEN
          RETURN vh.heures;
        ELSE
          RETURN 0;
        END IF;



      -- AP=[.AO20]*[.$AE20]
      WHEN 'AP' THEN
        RETURN cell('AO',l) * cell('AE',l);



      -- AQ15=SUM([.AP$1:.AP$1048576])
      WHEN 'AQ15' THEN
        RETURN calcFnc('somme','AP');



      -- AQ16=MIN([.AQ15];[.AJ17])
      WHEN 'AQ16' THEN
        RETURN LEAST(cell('AQ15'), cell('AJ17'));



      -- AQ17=[.AJ17]-[.AQ16]
      WHEN 'AQ17' THEN
        RETURN cell('AJ17') - cell('AQ16');



      -- AQ=IF([.AQ$15]>0;[.AP20]/[.AQ$15];0)
      WHEN 'AQ' THEN
        IF cell('AQ15') > 0 THEN
          RETURN cell('AP',l) / cell('AQ15');
        ELSE
          RETURN 0;
        END IF;



      -- AR=[.AQ$16]*[.AQ20]
      WHEN 'AR' THEN
        RETURN cell('AQ16') * cell('AQ',l);



      -- AS=IF([.AQ$17]=0;IF([.$AE20]>0;([.AP20]-[.AR20])/[.$AE20];[.AO20]);0)
      WHEN 'AS' THEN
        IF cell('AQ17') = 0 THEN
          IF cell('AE',l) > 0 THEN
            RETURN (cell('AP',l) - cell('AR',l)) / cell('AE',l);
          ELSE
            RETURN cell('AO',l);
          END IF;
        ELSE
          RETURN 0;
        END IF;



      -- AT=IF(i_depassement_service_du_sans_hc="Non";[.AS20]*[.$AF20];0)
      WHEN 'AT' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('AS',l) * cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- AV=IF(AND([.$A20]<>"D4DAC10000";[.$D20]<>"Oui";[.$H20]=1;[.$I20]<>"Référentiel");[.$N20];0)
      WHEN 'AV' THEN
        IF vh.structure_code <> 'D4DAC10000' AND NOT vh.structure_is_exterieur AND vh.taux_fc = 1 AND vh.volume_horaire_ref_id IS NULL THEN
          RETURN vh.heures;
        ELSE
          RETURN 0;
        END IF;



      -- AW=[.AV20]*[.$AE20]
      WHEN 'AW' THEN
        RETURN cell('AV',l) * cell('AE',l);



      -- AX15=SUM([.AW$1:.AW$1048576])
      WHEN 'AX15' THEN
        RETURN calcFnc('somme','AW');



      -- AX16=MIN([.AX15];[.AQ17])
      WHEN 'AX16' THEN
        RETURN LEAST(cell('AX15'), cell('AQ17'));



      -- AX17=[.AQ17]-[.AX16]
      WHEN 'AX17' THEN
        RETURN cell('AQ17') - cell('AX16');



      -- AX=IF([.AX$15]>0;[.AW20]/[.AX$15];0)
      WHEN 'AX' THEN
        IF cell('AX15') > 0 THEN
          RETURN cell('AW',l) / cell('AX15');
        ELSE
          RETURN 0;
        END IF;



      -- AY=[.AX$16]*[.AX20]
      WHEN 'AY' THEN
        RETURN cell('AX16') * cell('AX',l);



      -- AZ=IF([.AX$17]=0;IF([.$AE20]>0;([.AW20]-[.AY20])/[.$AE20];[.AV20]);0)
      WHEN 'AZ' THEN
        IF cell('AX17') = 0 THEN
          IF cell('AE',l) > 0 THEN
            RETURN (cell('AW',l) - cell('AY',l)) / cell('AE',l);
          ELSE
            RETURN cell('AV',l);
          END IF;
        ELSE
          RETURN 0;
        END IF;



      -- BA=IF(i_depassement_service_du_sans_hc="Non";[.AZ20]*[.$AF20];0)
      WHEN 'BA' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('AZ',l) * cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- BC=IF(AND([.$A20]="D4DAC10000";[.$I20]<>"Référentiel");[.$N20];0)
      WHEN 'BC' THEN
        IF vh.structure_code = 'D4DAC10000' AND vh.volume_horaire_ref_id IS NULL THEN
          RETURN vh.heures;
        ELSE
          RETURN 0;
        END IF;



      -- BD=[.BC20]*[.$AE20]
      WHEN 'BD' THEN
        RETURN cell('BC',l) * cell('AE',l);



      -- BE15=SUM([.BD$1:.BD$1048576])
      WHEN 'BE15' THEN
        RETURN calcFnc('somme','BD');



      -- BE16=MIN([.BE15];[.AX17])
      WHEN 'BE16' THEN
        RETURN LEAST(cell('BE15'), cell('AX17'));



      -- BE17=[.AX17]-[.BE16]
      WHEN 'BE17' THEN
        RETURN cell('AX17') - cell('BE16');



      -- BE=IF([.BE$15]>0;[.BD20]/[.BE$15];0)
      WHEN 'BE' THEN
        IF cell('BE15') > 0 THEN
          RETURN cell('BD',l) / cell('BE15');
        ELSE
          RETURN 0;
        END IF;



      -- BF=[.BE$16]*[.BE20]
      WHEN 'BF' THEN
        RETURN cell('BE16') * cell('BE',l);



      -- BG=IF([.BE$17]=0;IF([.$AE20]>0;([.BD20]-[.BF20])/[.$AE20];[.BC20]);0)
      WHEN 'BG' THEN
        IF cell('BE17') = 0 THEN
          IF cell('AE',l) > 0 THEN
            RETURN (cell('BD',l) - cell('BF',l)) / cell('AE',l);
          ELSE
            RETURN cell('BC',l);
          END IF;
        ELSE
          RETURN 0;
        END IF;



      -- BH=IF(i_depassement_service_du_sans_hc="Non";[.BG20]*[.$AF20];0)
      WHEN 'BH' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('BG',l) * cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- BJ=IF([.$I20]="Référentiel";[.$N20];0)
      WHEN 'BJ' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN vh.heures;
        ELSE
          RETURN 0;
        END IF;



      -- BK=[.BJ20]*[.$AE20]
      WHEN 'BK' THEN
        RETURN cell('BJ',l) * cell('AE',l);



      -- BL15=SUM([.BK$1:.BK$1048576])
      WHEN 'BL15' THEN
        RETURN calcFnc('somme','BK');



      -- BL16=MIN([.BL15];[.BE17])
      WHEN 'BL16' THEN
        RETURN LEAST(cell('BL15'), cell('BE17'));



      -- BL17=[.BE17]-[.BL16]
      WHEN 'BL17' THEN
        RETURN cell('BE17') - cell('BL16');



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



      -- BN=IF([.BL$17]=0;IF([.$AE20]>0;([.BK20]-[.BM20])/[.$AE20];[.BJ20]);0)
      WHEN 'BN' THEN
        IF cell('BL17') = 0 THEN
          IF cell('AE',l) > 0 THEN
            RETURN (cell('BK',l) - cell('BM',l)) / cell('AE',l);
          ELSE
            RETURN cell('BJ',l);
          END IF;
        ELSE
          RETURN 0;
        END IF;



      -- BO=IF(i_depassement_service_du_sans_hc="Non";[.BN20]*[.$AF20];0)
      WHEN 'BO' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('BN',l) * cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- BQ=IF(AND([.$D20]="Oui";[.$I20]<>"Référentiel");[.$N20];0)
      WHEN 'BQ' THEN
        IF vh.structure_is_exterieur AND vh.volume_horaire_ref_id IS NULL THEN
          RETURN vh.heures;
        ELSE
          RETURN 0;
        END IF;



      -- BR=[.BQ20]*[.$AE20]
      WHEN 'BR' THEN
        RETURN cell('BQ',l) * cell('AE',l);



      -- BS15=SUM([.BR$1:.BR$1048576])
      WHEN 'BS15' THEN
        RETURN calcFnc('somme','BR');



      -- BS16=MIN([.BS15];[.BL17])
      WHEN 'BS16' THEN
        RETURN LEAST(cell('BS15'), cell('BL17'));



      -- BS17=[.BL17]-[.BS16]
      WHEN 'BS17' THEN
        RETURN cell('BL17') - cell('BS16');



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



      -- BU=IF([.BS$17]=0;IF([.$AE20]>0;([.BR20]-[.BT20])/[.$AE20];[.BQ20]);0)
      WHEN 'BU' THEN
        IF cell('BS17') = 0 THEN
          IF cell('AE',l) > 0 THEN
            RETURN (cell('BR',l) - cell('BT',l)) / cell('AE',l);
          ELSE
            RETURN cell('BQ',l);
          END IF;
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

END FORMULE_LYON2_2023;