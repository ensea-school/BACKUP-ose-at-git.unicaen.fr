CREATE OR REPLACE PACKAGE BODY FORMULE_ASSAS AS
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



      -- U=IF([.$I20]="Référentiel";0;([.$AJ20]+[.$AV20]))
      WHEN 'U' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AJ',l) + cell('AV',l));
        END IF;



      -- V=IF([.$I20]="Référentiel";0;([.$AP20]+[.$BB20]))
      WHEN 'V' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AP',l) + cell('BB',l));
        END IF;



      -- W=IF([.$I20]="Référentiel";0;([.$BN20]+[.$BT20]))
      WHEN 'W' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('BN',l) + cell('BT',l));
        END IF;



      -- X=IF([.$I20]="Référentiel";[.$BH20];0)
      WHEN 'X' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN cell('BH',l);
        ELSE
          RETURN 0;
        END IF;



      -- Y=IF([.$I20]="Référentiel";0;([.$AL20]+[.$AX20]))
      WHEN 'Y' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AL',l) + cell('AX',l));
        END IF;



      -- Z=IF([.$I20]="Référentiel";0;([.$AR20]+[.$BD20]))
      WHEN 'Z' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AR',l) + cell('BD',l));
        END IF;



      -- AA=IF([.$I20]="Référentiel";0;([.$BP20]+[.$BV20]))
      WHEN 'AA' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('BP',l) + cell('BV',l));
        END IF;



      -- AB=IF([.$I20]="Référentiel";IF([.$X20]=0;[.$BJ20];0);0)
      WHEN 'AB' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          IF vh.service_referentiel = 0 THEN
            RETURN cell('BJ',l);
          ELSE
            RETURN 0;
          END IF;
        ELSE
          RETURN 0;
        END IF;



      -- AC=IF([.$I20]="Référentiel";IF([.$X20]<>0;[.$BJ20];0);0)
      WHEN 'AC' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          IF vh.service_referentiel <> 0 THEN
            RETURN cell('BJ',l);
          ELSE
            RETURN 0;
          END IF;
        ELSE
          RETURN 0;
        END IF;



      -- AE=IF(ISERROR([.J20]);1;[.J20])
      WHEN 'AE' THEN
        RETURN vh.taux_service_du;



      -- AF=IF(ISERROR([.K20]);1;[.K20])
      WHEN 'AF' THEN
        RETURN vh.taux_service_compl;



      -- AH=IF(AND([.$I20]<>"Référentiel";[.$O20]="Oui");[.$N20]*[.$AE20]*[.$F20];0)
      WHEN 'AH' THEN
        IF vh.volume_horaire_ref_id IS NULL AND vh.param_1 = 'Oui' THEN
          RETURN vh.heures * cell('AE',l) * vh.taux_fi;
        ELSE
          RETURN 0;
        END IF;



      -- AI=IF([.AJ$15]>0;[.AH20]/[.AJ$15];0)
      WHEN 'AI' THEN
        IF cell('AJ15') > 0 THEN
          RETURN cell('AH',l) / cell('AJ15');
        ELSE
          RETURN 0;
        END IF;



      -- AJ15=SUM([.AH$1:.AH$1048576])
      WHEN 'AJ15' THEN
        RETURN calcFnc('somme','AH');



      -- AJ16=MIN([.AJ15];i_service_du)
      WHEN 'AJ16' THEN
        RETURN LEAST(cell('AJ15'), i.service_du);



      -- AJ17=i_service_du-[.AJ16]
      WHEN 'AJ17' THEN
        RETURN i.service_du - cell('AJ16');



      -- AJ=[.AJ$16]*[.AI20]
      WHEN 'AJ' THEN
        RETURN cell('AJ16') * cell('AI',l);



      -- AK=IF([.AJ$17]=0;([.AH20]-[.AJ20])/[.$AE20];0)
      WHEN 'AK' THEN
        IF cell('AJ17') = 0 THEN
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



      -- AN=IF(AND([.$I20]<>"Référentiel";[.$O20]="Oui");[.$N20]*[.$AE20]*[.$G20];0)
      WHEN 'AN' THEN
        IF vh.volume_horaire_ref_id IS NULL AND vh.param_1 = 'Oui' THEN
          RETURN vh.heures * cell('AE',l) * vh.taux_fa;
        ELSE
          RETURN 0;
        END IF;



      -- AO=IF([.AP$15]>0;[.AN20]/[.AP$15];0)
      WHEN 'AO' THEN
        IF cell('AP15') > 0 THEN
          RETURN cell('AN',l) / cell('AP15');
        ELSE
          RETURN 0;
        END IF;



      -- AP15=SUM([.AN$1:.AN$1048576])
      WHEN 'AP15' THEN
        RETURN calcFnc('somme','AN');



      -- AP16=MIN([.AP15];[.AJ17])
      WHEN 'AP16' THEN
        RETURN LEAST(cell('AP15'), cell('AJ17'));



      -- AP17=[.AJ17]-[.AP16]
      WHEN 'AP17' THEN
        RETURN cell('AJ17') - cell('AP16');



      -- AP=[.AP$16]*[.AO20]
      WHEN 'AP' THEN
        RETURN cell('AP16') * cell('AO',l);



      -- AQ=IF([.AP$17]=0;([.AN20]-[.AP20])/[.$AE20];0)
      WHEN 'AQ' THEN
        IF cell('AP17') = 0 THEN
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



      -- AT=IF(AND([.$I20]<>"Référentiel";[.$O20]<>"Oui");[.$N20]*[.$AE20]*[.$F20];0)
      WHEN 'AT' THEN
        IF vh.volume_horaire_ref_id IS NULL AND vh.param_1 <> 'Oui' THEN
          RETURN vh.heures * cell('AE',l) * vh.taux_fi;
        ELSE
          RETURN 0;
        END IF;



      -- AU=IF([.AV$15]>0;[.AT20]/[.AV$15];0)
      WHEN 'AU' THEN
        IF cell('AV15') > 0 THEN
          RETURN cell('AT',l) / cell('AV15');
        ELSE
          RETURN 0;
        END IF;



      -- AV15=SUM([.AT$1:.AT$1048576])
      WHEN 'AV15' THEN
        RETURN calcFnc('somme','AT');



      -- AV16=MIN([.AV15];[.AP17])
      WHEN 'AV16' THEN
        RETURN LEAST(cell('AV15'), cell('AP17'));



      -- AV17=[.AP17]-[.AV16]
      WHEN 'AV17' THEN
        RETURN cell('AP17') - cell('AV16');



      -- AV=[.AV$16]*[.AU20]
      WHEN 'AV' THEN
        RETURN cell('AV16') * cell('AU',l);



      -- AW=IF([.AV$17]=0;([.AT20]-[.AV20])/[.$AE20];0)
      WHEN 'AW' THEN
        IF cell('AV17') = 0 THEN
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



      -- AZ=IF(AND([.$I20]<>"Référentiel";[.$O20]<>"Oui");[.$N20]*[.$AE20]*[.$G20];0)
      WHEN 'AZ' THEN
        IF vh.volume_horaire_ref_id IS NULL AND vh.param_1 <> 'Oui' THEN
          RETURN vh.heures * cell('AE',l) * vh.taux_fa;
        ELSE
          RETURN 0;
        END IF;



      -- BA=IF([.BB$15]>0;[.AZ20]/[.BB$15];0)
      WHEN 'BA' THEN
        IF cell('BB15') > 0 THEN
          RETURN cell('AZ',l) / cell('BB15');
        ELSE
          RETURN 0;
        END IF;



      -- BB15=SUM([.AZ$1:.AZ$1048576])
      WHEN 'BB15' THEN
        RETURN calcFnc('somme','AZ');



      -- BB16=MIN([.BB15];[.AV17])
      WHEN 'BB16' THEN
        RETURN LEAST(cell('BB15'), cell('AV17'));



      -- BB17=[.AV17]-[.BB16]
      WHEN 'BB17' THEN
        RETURN cell('AV17') - cell('BB16');



      -- BB=[.BB$16]*[.BA20]
      WHEN 'BB' THEN
        RETURN cell('BB16') * cell('BA',l);



      -- BC=IF([.BB$17]=0;([.AZ20]-[.BB20])/[.$AE20];0)
      WHEN 'BC' THEN
        IF cell('BB17') = 0 THEN
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



      -- BF=IF([.$I20]="Référentiel";[.$N20];0)
      WHEN 'BF' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN vh.heures;
        ELSE
          RETURN 0;
        END IF;



      -- BG=IF([.BH$15]>0;[.BF20]/[.BH$15];0)
      WHEN 'BG' THEN
        IF cell('BH15') > 0 THEN
          RETURN cell('BF',l) / cell('BH15');
        ELSE
          RETURN 0;
        END IF;



      -- BH15=SUM([.BF$1:.BF$1048576])
      WHEN 'BH15' THEN
        RETURN calcFnc('somme','BF');



      -- BH16=MIN([.BH15];[.BB17])
      WHEN 'BH16' THEN
        RETURN LEAST(cell('BH15'), cell('BB17'));



      -- BH17=[.BB17]-[.BH16]
      WHEN 'BH17' THEN
        RETURN cell('BB17') - cell('BH16');



      -- BH=[.BH$16]*[.BG20]
      WHEN 'BH' THEN
        RETURN cell('BH16') * cell('BG',l);



      -- BI=IF([.BH$17]=0;([.BF20]-[.BH20])/[.$AE20];0)
      WHEN 'BI' THEN
        IF cell('BH17') = 0 THEN
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



      -- BL=IF(AND([.$I20]<>"Référentiel";[.$O20]="Oui");[.$N20]*[.$AE20]*[.$H20];0)
      WHEN 'BL' THEN
        IF vh.volume_horaire_ref_id IS NULL AND vh.param_1 = 'Oui' THEN
          RETURN vh.heures * cell('AE',l) * vh.taux_fc;
        ELSE
          RETURN 0;
        END IF;



      -- BM=IF([.BN$15]>0;[.BL20]/[.BN$15];0)
      WHEN 'BM' THEN
        IF cell('BN15') > 0 THEN
          RETURN cell('BL',l) / cell('BN15');
        ELSE
          RETURN 0;
        END IF;



      -- BN15=SUM([.BL$1:.BL$1048576])
      WHEN 'BN15' THEN
        RETURN calcFnc('somme','BL');



      -- BN16=MIN([.BN15];[.BH17])
      WHEN 'BN16' THEN
        RETURN LEAST(cell('BN15'), cell('BH17'));



      -- BN17=[.BH17]-[.BN16]
      WHEN 'BN17' THEN
        RETURN cell('BH17') - cell('BN16');



      -- BN=[.BN$16]*[.BM20]
      WHEN 'BN' THEN
        RETURN cell('BN16') * cell('BM',l);



      -- BO=IF([.BN$17]=0;([.BL20]-[.BN20])/[.$AE20];0)
      WHEN 'BO' THEN
        IF cell('BN17') = 0 THEN
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



      -- BR=IF(AND([.$I20]<>"Référentiel";[.$O20]<>"Oui");[.$N20]*[.$AE20]*[.$H20];0)
      WHEN 'BR' THEN
        IF vh.volume_horaire_ref_id IS NULL AND vh.param_1 <> 'Oui' THEN
          RETURN vh.heures * cell('AE',l) * vh.taux_fc;
        ELSE
          RETURN 0;
        END IF;



      -- BS=IF([.BT$15]>0;[.BR20]/[.BT$15];0)
      WHEN 'BS' THEN
        IF cell('BT15') > 0 THEN
          RETURN cell('BR',l) / cell('BT15');
        ELSE
          RETURN 0;
        END IF;



      -- BT15=SUM([.BR$1:.BR$1048576])
      WHEN 'BT15' THEN
        RETURN calcFnc('somme','BR');



      -- BT16=MIN([.BT15];[.BN17])
      WHEN 'BT16' THEN
        RETURN LEAST(cell('BT15'), cell('BN17'));



      -- BT17=[.BN17]-[.BT16]
      WHEN 'BT17' THEN
        RETURN cell('BN17') - cell('BT16');



      -- BT=[.BT$16]*[.BS20]
      WHEN 'BT' THEN
        RETURN cell('BT16') * cell('BS',l);



      -- BU=IF([.BT$17]=0;([.BR20]-[.BT20])/[.$AE20];0)
      WHEN 'BU' THEN
        IF cell('BT17') = 0 THEN
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
      COALESCE(e.autre_1,''Non'') param_1,
      NULL param_2,
      NULL param_3,
      NULL param_4,
      NULL param_5
    FROM
      V_FORMULE_VOLUME_HORAIRE fvh
      LEFT JOIN service s ON s.id = fvh.service_id
      LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
      LEFT JOIN etape e ON e.id = ep.etape_id
    ORDER BY
      ordre
    ';
  END;

END FORMULE_ASSAS;