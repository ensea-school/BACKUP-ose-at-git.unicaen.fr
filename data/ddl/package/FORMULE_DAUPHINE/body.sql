CREATE OR REPLACE PACKAGE BODY FORMULE_DAUPHINE AS
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



      -- U=IF([.$I20]="Référentiel";0;([.$AP20]+[.$AV20]+[.$BC20]+[.$BI20])*[.F20])
      WHEN 'U' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AP',l) + cell('AV',l) + cell('BC',l) + cell('BI',l)) * vh.taux_fi;
        END IF;



      -- V=IF([.$I20]="Référentiel";0;([.$AP20]+[.$AV20]+[.$BC20]+[.$BI20])*[.G20])
      WHEN 'V' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AP',l) + cell('AV',l) + cell('BC',l) + cell('BI',l)) * vh.taux_fa;
        END IF;



      -- W=IF([.$I20]="Référentiel";0;([.$AP20]+[.$AV20]+[.$BC20]+[.$BI20])*[.H20])
      WHEN 'W' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AP',l) + cell('AV',l) + cell('BC',l) + cell('BI',l)) * vh.taux_fc;
        END IF;



      -- X=IF([.$I20]="Référentiel";[.$AJ20];0)
      WHEN 'X' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN cell('AJ',l);
        ELSE
          RETURN 0;
        END IF;



      -- Y=IF([.$I20]="Référentiel";0;([.$AR20]+[.$BE20]+[.$BK20])*[.F20])
      WHEN 'Y' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AR',l) + cell('BE',l) + cell('BK',l)) * vh.taux_fi;
        END IF;



      -- Z=IF([.$I20]="Référentiel";0;([.$AR20]+[.$BE20]+[.$BK20])*[.G20])
      WHEN 'Z' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AR',l) + cell('BE',l) + cell('BK',l)) * vh.taux_fa;
        END IF;



      -- AA=IF([.$I20]="Référentiel";0;([.$AR20]+[.$AX20]+[.$BE20]+[.$BK20])*[.H20])
      WHEN 'AA' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AR',l) + cell('AX',l) + cell('BE',l) + cell('BK',l)) * vh.taux_fc;
        END IF;



      -- AB=IF([.$I20]="Référentiel";0;[.$AY20]*[.H20])
      WHEN 'AB' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN cell('AY',l) * vh.taux_fc;
        END IF;



      -- AC=IF([.$I20]="Référentiel";[.$AL20];0)
      WHEN 'AC' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN cell('AL',l);
        ELSE
          RETURN 0;
        END IF;



      -- AE=IF(ISERROR([.J20]);1;[.J20])*[.L20]
      WHEN 'AE' THEN
        RETURN vh.taux_service_du * vh.ponderation_service_du;



      -- AF=IF(ISERROR([.K20]);1;IF(i_depassement_service_du_sans_hc="Oui";0;[.K20]))*[.M20]
      WHEN 'AF' THEN
        IF i.depassement_service_du_sans_hc THEN
          RETURN 0;
        ELSE
          RETURN vh.taux_service_compl * vh.ponderation_service_compl;
        END IF;



      -- AH=IF(AND([.$E20]="Oui";[.$I20]="Référentiel");[.$N20]*[.$AE20];0)
      WHEN 'AH' THEN
        IF vh.service_statutaire AND vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN vh.heures * cell('AE',l);
        ELSE
          RETURN 0;
        END IF;



      -- AI14=SUM([.AH$1:.AH$1048576])
      WHEN 'AI14' THEN
        RETURN calcFnc('somme','AH');



      -- AI15=MIN([.AI14];i_service_du/3)
      WHEN 'AI15' THEN
        RETURN LEAST(cell('AI14'), i.service_du / 3);



      -- AI16=MIN([.AI14];[.AI15])
      WHEN 'AI16' THEN
        RETURN LEAST(cell('AI14'), cell('AI15'));



      -- AI17=i_service_du-[.AI16]
      WHEN 'AI17' THEN
        RETURN i.service_du - cell('AI16');



      -- AI=IF([.AI$14]>0;[.AH20]/[.AI$14];0)
      WHEN 'AI' THEN
        IF cell('AI14') > 0 THEN
          RETURN cell('AH',l) / cell('AI14');
        ELSE
          RETURN 0;
        END IF;



      -- AJ=[.AI$16]*[.AI20]
      WHEN 'AJ' THEN
        RETURN cell('AI16') * cell('AI',l);



      -- AK=IF(OR([.AJ20]<[.AH20];[.AH20]<0);([.AH20]-[.AJ20])/[.$AE20];0)
      WHEN 'AK' THEN
        IF cell('AJ',l) < cell('AH',l) OR cell('AH',l) < 0 THEN
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



      -- AN=IF(OR([.$D20]="Oui";[.$I20]="Référentiel";[.$A20]="DEP");0;IF(AND(MID([.$O20];6;1)="P";(OR(MID([.$O20];2;1)="M";MID([.$O20];2;1)="A")));0;[.$N20]*([.$G20]+[.$F20]+[.$H20])*[.$AE20]))
      WHEN 'AN' THEN
        IF vh.structure_is_exterieur OR vh.volume_horaire_ref_id IS NOT NULL OR vh.structure_code = 'DEP' THEN
          RETURN 0;
        ELSE
          IF COALESCE(SUBSTR(vh.param_1, 6, 1),' ') = 'P' AND (COALESCE(SUBSTR(vh.param_1, 2, 1),' ') = 'M' OR COALESCE(SUBSTR(vh.param_1, 2, 1),' ') = 'A') THEN
            RETURN 0;
          ELSE
            RETURN vh.heures * (vh.taux_fa + vh.taux_fi + vh.taux_fc) * cell('AE',l);
          END IF;
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



      -- AQ=IF(OR([.AP20]<[.AN20];[.AN20]<0);([.AN20]-[.AP20])/[.$AE20];0)
      WHEN 'AQ' THEN
        IF cell('AP',l) < cell('AN',l) OR cell('AN',l) < 0 THEN
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



      -- AT=IF(AND([.$D20]="Non";[.$I20]<>"Référentiel";[.$A20]="DEP");([.$N20]*([.$G20]+[.$F20]+[.$H20])*[.$AE20]);0)
      WHEN 'AT' THEN
        IF NOT vh.structure_is_exterieur AND vh.volume_horaire_ref_id IS NULL AND vh.structure_code = 'DEP' THEN
          RETURN (vh.heures * (vh.taux_fa + vh.taux_fi + vh.taux_fc) * cell('AE',l));
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



      -- AV=[.AU20]*[.AU$16]
      WHEN 'AV' THEN
        RETURN cell('AU',l) * cell('AU16');



      -- AW=IF([.$AU$17]>0;0;((([.$AT20]-[.AV20])/[.$AE20])*[.$AF20]))
      WHEN 'AW' THEN
        IF cell('AU17') > 0 THEN
          RETURN 0;
        ELSE
          RETURN (((cell('AT',l) - cell('AV',l)) / cell('AE',l)) * cell('AF',l));
        END IF;



      -- AX=IF(AND(i_depassement_service_du_sans_hc="Non";i_type_intervenant_code="E");[.$AW20];IF(AND(i_depassement_service_du_sans_hc="Non";i_type_intervenant_code="P");[.$AW20]*(1-([.$AF20]-[.$AE20])/[.$AF20]);0))
      WHEN 'AX' THEN
        IF NOT i.depassement_service_du_sans_hc AND i.type_intervenant_code = 'E' THEN
          RETURN cell('AW',l);
        ELSE
          IF NOT i.depassement_service_du_sans_hc AND i.type_intervenant_code = 'P' THEN
            RETURN cell('AW',l) * (1 - (cell('AF',l) - cell('AE',l)) / cell('AF',l));
          ELSE
            RETURN 0;
          END IF;
        END IF;



      -- AY=IF(AND(i_depassement_service_du_sans_hc="Non";i_type_intervenant_code="E");0;IF(AND(i_depassement_service_du_sans_hc="Non";i_type_intervenant_code="P");[.$AW20]*(([.$AF20]-[.$AE20])/[.$AF20]);0))
      WHEN 'AY' THEN
        IF NOT i.depassement_service_du_sans_hc AND i.type_intervenant_code = 'E' THEN
          RETURN 0;
        ELSE
          IF NOT i.depassement_service_du_sans_hc AND i.type_intervenant_code = 'P' THEN
            RETURN cell('AW',l) * ((cell('AF',l) - cell('AE',l)) / cell('AF',l));
          ELSE
            RETURN 0;
          END IF;
        END IF;



      -- BA=IF(AND([.$D20]="Non";[.$A20]<>"DEP";AND(MID([.$O20];6;1)="P";OR(MID([.$O20];2;1)="A";MID([.$O20];2;1)="M")));[.$N20]*([.$G20]+[.$F20]+[.$H20])*[.$AE20];0)
      WHEN 'BA' THEN
        IF NOT vh.structure_is_exterieur AND vh.structure_code <> 'DEP' AND (COALESCE(SUBSTR(vh.param_1, 6, 1),' ') = 'P' AND (COALESCE(SUBSTR(vh.param_1, 2, 1),' ') = 'A' OR COALESCE(SUBSTR(vh.param_1, 2, 1),' ') = 'M')) THEN
          RETURN vh.heures * (vh.taux_fa + vh.taux_fi + vh.taux_fc) * cell('AE',l);
        ELSE
          RETURN 0;
        END IF;



      -- BB15=SUM([.BA$1:.BA$1048576])
      WHEN 'BB15' THEN
        RETURN calcFnc('somme','BA');



      -- BB16=MIN([.BB15];[.AU17])
      WHEN 'BB16' THEN
        RETURN LEAST(cell('BB15'), cell('AU17'));



      -- BB17=[.AU17]-[.BB16]
      WHEN 'BB17' THEN
        RETURN cell('AU17') - cell('BB16');



      -- BB=IF([.BB$15]>0;[.BA20]/[.BB$15];0)
      WHEN 'BB' THEN
        IF cell('BB15') > 0 THEN
          RETURN cell('BA',l) / cell('BB15');
        ELSE
          RETURN 0;
        END IF;



      -- BC=[.BB20]*[.BB$16]
      WHEN 'BC' THEN
        RETURN cell('BB',l) * cell('BB16');



      -- BD=IF([.BB$17]=0;([.BA20]-[.BC20])/[.$AE20];0)
      WHEN 'BD' THEN
        IF cell('BB17') = 0 THEN
          RETURN (cell('BA',l) - cell('BC',l)) / cell('AE',l);
        ELSE
          RETURN 0;
        END IF;



      -- BE=IF(i_depassement_service_du_sans_hc="Non";[.BD20]*[.$AF20];0)
      WHEN 'BE' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('BD',l) * cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- BG=IF(AND([.$D20]="Oui";[.$I20]<>"Référentiel");[.$N20]*([.$G20]+[.$F20]+[.$H20])*[.$AE20];0)
      WHEN 'BG' THEN
        IF vh.structure_is_exterieur AND vh.volume_horaire_ref_id IS NULL THEN
          RETURN vh.heures * (vh.taux_fa + vh.taux_fi + vh.taux_fc) * cell('AE',l);
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



      -- BJ=IF([.BI20]<[.BG20];([.BG20]-[.BI20])/[.$AE20];0)
      WHEN 'BJ' THEN
        IF cell('BI',l) < cell('BG',l) THEN
          RETURN (cell('BG',l) - cell('BI',l)) / cell('AE',l);
        ELSE
          RETURN 0;
        END IF;



      -- BK=IF(i_depassement_service_du_sans_hc="Non";[.BJ20]*[.$AF20];0)
      WHEN 'BK' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('BJ',l) * cell('AF',l);
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
      ordre
    ';
  END;

END FORMULE_DAUPHINE;