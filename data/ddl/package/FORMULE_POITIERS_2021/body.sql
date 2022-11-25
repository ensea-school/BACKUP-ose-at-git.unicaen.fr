CREATE OR REPLACE PACKAGE BODY FORMULE_POITIERS_2021 AS
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



      -- U=IF([.$I20]="Référentiel";0;([.$AP20]+[.$AV20]+[.$BB20])*[.F20])
      WHEN 'U' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AP',l) + cell('AV',l) + cell('BB',l)) * vh.taux_fi;
        END IF;



      -- V=IF([.$I20]="Référentiel";0;([.$AP20]+[.$AV20]+[.$BB20])*[.G20])
      WHEN 'V' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AP',l) + cell('AV',l) + cell('BB',l)) * vh.taux_fa;
        END IF;



      -- W=IF([.$I20]="Référentiel";0;([.$AP20]+[.$AV20]+[.$BB20])*[.H20])
      WHEN 'W' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AP',l) + cell('AV',l) + cell('BB',l)) * vh.taux_fc;
        END IF;



      -- X=IF([.$I20]="Référentiel";[.$AJ20];0)
      WHEN 'X' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN cell('AJ',l);
        ELSE
          RETURN 0;
        END IF;



      -- Y=IF([.$I20]="Référentiel";0;([.$AR20]+[.$AX20]+[.$BD20])*[.F20])
      WHEN 'Y' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AR',l) + cell('AX',l) + cell('BD',l)) * vh.taux_fi;
        END IF;



      -- Z=IF([.$I20]="Référentiel";0;([.$AR20]+[.$AX20]+[.$BD20])*[.G20])
      WHEN 'Z' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AR',l) + cell('AX',l) + cell('BD',l)) * vh.taux_fa;
        END IF;



      -- AA=IF([.$I20]="Référentiel";0;([.$AR20]+[.$AX20]+[.$BD20])*[.H20])
      WHEN 'AA' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AR',l) + cell('AX',l) + cell('BD',l)) * vh.taux_fc;
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



      -- AE=IF(ISERROR([.J20]);1;[.J20]*IF(ISERROR([.L20]);1;[.L20]))
      WHEN 'AE' THEN
        RETURN vh.taux_service_du;



      -- AF=IF(ISERROR([.K20]);1;[.K20]*IF(ISERROR([.M20]);1;[.M20]))
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



      -- AN=IF(AND([.$E20]="Oui";[.$I20]<>"Référentiel";[.$I20]<>"ETD";NOT(AND([.$G20]=1;OR([.$A20]="I2000";[.$A20]="I2300"))));[.$N20]*[.$AE20];0)
      WHEN 'AN' THEN
        IF vh.service_statutaire AND vh.volume_horaire_ref_id IS NULL AND vh.type_intervention_code <> 'ETD' AND NOT(vh.taux_fa = 1 AND (vh.structure_code = 'I2000' OR vh.structure_code = 'I2300')) THEN
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



      -- AT=IF(AND([.$E20]="Oui";[.$I20]<>"Référentiel";[.$I20]<>"ETD";[.$G20]=1;OR([.$A20]="I2000";[.$A20]="I2300"));[.$N20]*[.$AE20];0)
      WHEN 'AT' THEN
        IF vh.service_statutaire AND vh.volume_horaire_ref_id IS NULL AND vh.type_intervention_code <> 'ETD' AND vh.taux_fa = 1 AND (vh.structure_code = 'I2000' OR vh.structure_code = 'I2300') THEN
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



      -- AZ=IF(AND([.$E20]="Oui";[.$I20]="ETD");[.$N20]*[.$AE20];0)
      WHEN 'AZ' THEN
        IF vh.service_statutaire AND vh.type_intervention_code = 'ETD' THEN
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



      -- BB=[.BA$16]*[.BA20]
      WHEN 'BB' THEN
        RETURN cell('BA16') * cell('BA',l);



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

END FORMULE_POITIERS_2021;