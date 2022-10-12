CREATE OR REPLACE PACKAGE BODY FORMULE_RENNES2 AS
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
    IF l > 0 THEN
      vh := ose_formule.volumes_horaires.items(l);
    END IF;
    CASE c



      -- T=IF([.$H20]="Référentiel";0;([.$AK20]+[.$AQ20]+[.$AW20]+[.$BC20])*[.E20])
      WHEN 'T' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AK',l) + cell('AQ',l) + cell('AW',l) + cell('BC',l)) * vh.taux_fi;
        END IF;



      -- U=IF([.$H20]="Référentiel";0;([.$AK20]+[.$AQ20]+[.$AW20]+[.$BC20])*[.F20])
      WHEN 'U' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AK',l) + cell('AQ',l) + cell('AW',l) + cell('BC',l)) * vh.taux_fa;
        END IF;



      -- V=IF([.$H20]="Référentiel";0;([.$AK20]+[.$AQ20]+[.$AW20]+[.$BC20])*[.G20])
      WHEN 'V' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AK',l) + cell('AQ',l) + cell('AW',l) + cell('BC',l)) * vh.taux_fc;
        END IF;



      -- W=IF([.$H20]="Référentiel";[.$AK20]+[.$AQ20]+[.$AW20]+[.$BC20];0)
      WHEN 'W' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN cell('AK',l) + cell('AQ',l) + cell('AW',l) + cell('BC',l);
        ELSE
          RETURN 0;
        END IF;



      -- X=IF([.$H20]="Référentiel";0;([.$AM20]+[.$AS20]+[.$AY20]+[.$BE20])*[.E20])
      WHEN 'X' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AM',l) + cell('AS',l) + cell('AY',l) + cell('BE',l)) * vh.taux_fi;
        END IF;



      -- Y=IF([.$H20]="Référentiel";0;([.$AM20]+[.$AS20]+[.$AY20]+[.$BE20])*[.F20])
      WHEN 'Y' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AM',l) + cell('AS',l) + cell('AY',l) + cell('BE',l)) * vh.taux_fa;
        END IF;



      -- Z=IF([.$H20]="Référentiel";0;([.$AM20]+[.$AS20]+[.$AY20]+[.$BE20])*[.G20])
      WHEN 'Z' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AM',l) + cell('AS',l) + cell('AY',l) + cell('BE',l)) * vh.taux_fc;
        END IF;



      -- AA=0
      WHEN 'AA' THEN
        RETURN 0;



      -- AB=IF([.$H20]="Référentiel";[.$AM20]+[.$AS20]+[.$AY20]+[.$BE20];0)
      WHEN 'AB' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN cell('AM',l) + cell('AS',l) + cell('AY',l) + cell('BE',l);
        ELSE
          RETURN 0;
        END IF;



      -- AD=IF(ISERROR([.I20]);1;[.I20])
      WHEN 'AD' THEN
        RETURN vh.taux_service_du;



      -- AE=IF(ISERROR([.J20]);1;[.J20])
      WHEN 'AE' THEN
        RETURN vh.taux_service_compl;



      -- AF=IF(AND([.$D20]="Oui";[.$H20]<>"Référentiel");[.$M20]*[.$AD20];0)
      WHEN 'AF' THEN
        IF vh.service_statutaire AND vh.volume_horaire_ref_id IS NULL THEN
          RETURN vh.heures * cell('AD',l);
        ELSE
          RETURN 0;
        END IF;



      -- AG15=SUM([.AF20:.AF500])
      WHEN 'AG15' THEN
        RETURN calcFnc('somme','AF');



      -- AG16=[.AG15]>=[.AF16]
      WHEN 'AG16' THEN
        IF cell('AG15') >= cell('AF16') THEN
          RETURN 1;
        ELSE
          RETURN 0;
        END IF;



      -- AI=IF(AND([.$D20]="Oui";[.$N20]<>"Oui";[.$A20]=i_structure_code;[.$O20]="Oui");IF(OR([.$AG$16];[.$H20]<>"Référentiel");[.$M20]*[.$AD20];0);0)
      WHEN 'AI' THEN
        IF vh.service_statutaire AND vh.param_1 <> 'Oui' AND vh.structure_is_affectation AND vh.param_2 = 'Oui' THEN
          IF cell('AG16') = 1 OR vh.volume_horaire_ref_id IS NULL THEN
            RETURN vh.heures * cell('AD',l);
          ELSE
            RETURN 0;
          END IF;
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



      -- AL=IF([.AJ$17]=0;([.AI20]-[.AK20])/[.$AD20];0)
      WHEN 'AL' THEN
        IF cell('AJ17') = 0 THEN
          RETURN (cell('AI',l) - cell('AK',l)) / cell('AD',l);
        ELSE
          RETURN 0;
        END IF;



      -- AM=IF(i_depassement_service_du_sans_hc="Non";[.AL20]*[.$AE20];0)
      WHEN 'AM' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('AL',l) * cell('AE',l);
        ELSE
          RETURN 0;
        END IF;



      -- AO=IF(AND([.$D20]="Oui";[.$N20]<>"Oui";[.$A20]<>i_structure_code;[.$O20]="Oui");IF(OR([.$AG$16];[.$H20]<>"Référentiel");[.$M20]*[.$AD20];0);0)
      WHEN 'AO' THEN
        IF vh.service_statutaire AND vh.param_1 <> 'Oui' AND NOT vh.structure_is_affectation AND vh.param_2 = 'Oui' THEN
          IF cell('AG16') = 1 OR vh.volume_horaire_ref_id IS NULL THEN
            RETURN vh.heures * cell('AD',l);
          ELSE
            RETURN 0;
          END IF;
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



      -- AR=IF([.AP$17]=0;([.AO20]-[.AQ20])/[.$AD20];0)
      WHEN 'AR' THEN
        IF cell('AP17') = 0 THEN
          RETURN (cell('AO',l) - cell('AQ',l)) / cell('AD',l);
        ELSE
          RETURN 0;
        END IF;



      -- AS=IF(i_depassement_service_du_sans_hc="Non";[.AR20]*[.$AE20];0)
      WHEN 'AS' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('AR',l) * cell('AE',l);
        ELSE
          RETURN 0;
        END IF;



      -- AU=IF(AND([.$D20]="Oui";[.$N20]<>"Oui";[.$O20]<>"Oui");IF(OR([.$AG$16];[.$H20]<>"Référentiel");[.$M20]*[.$AD20];0);0)
      WHEN 'AU' THEN
        IF vh.service_statutaire AND vh.param_1 <> 'Oui' AND vh.param_2 <> 'Oui' THEN
          IF cell('AG16') = 1 OR vh.volume_horaire_ref_id IS NULL THEN
            RETURN vh.heures * cell('AD',l);
          ELSE
            RETURN 0;
          END IF;
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



      -- AX=IF([.AV$17]=0;([.AU20]-[.AW20])/[.$AD20];0)
      WHEN 'AX' THEN
        IF cell('AV17') = 0 THEN
          RETURN (cell('AU',l) - cell('AW',l)) / cell('AD',l);
        ELSE
          RETURN 0;
        END IF;



      -- AY=IF(i_depassement_service_du_sans_hc="Non";[.AX20]*[.$AE20];0)
      WHEN 'AY' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('AX',l) * cell('AE',l);
        ELSE
          RETURN 0;
        END IF;

      -- BA=IF(AND([.$D20]="Oui";[.$N20]="Oui");IF(OR([.$AG$16];[.$H20]<>"Référentiel");[.$M20]*[.$AD20];0);0)
      WHEN 'BA' THEN
        IF vh.service_statutaire AND vh.param_1 = 'Oui' THEN
          IF cell('AG16') = 1 OR vh.volume_horaire_ref_id IS NULL THEN
            RETURN vh.heures * cell('AD',l);
          ELSE
            RETURN 0;
          END IF;
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



      -- BD=IF([.BB$17]=0;([.BA20]-[.BC20])/[.$AD20];0)
      WHEN 'BD' THEN
        IF cell('BB17') = 0 THEN
          RETURN (cell('BA',l) - cell('BC',l)) / cell('AD',l);
        ELSE
          RETURN 0;
        END IF;



      -- BE=IF(i_depassement_service_du_sans_hc="Non";[.BD20]*[.$AE20];0)
      WHEN 'BE' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('BD',l) * cell('AE',l);
        ELSE
          RETURN 0;
        END IF;



      -- AF16
      WHEN 'AF16' THEN
        RETURN 64;




    ELSE
      raise_application_error( -20001, 'La colonne c=' || c || ', l=' || l || ' n''existe pas!');
  END CASE;
  raise_application_error( -20001, 'La colonne c=' || c || ', l=' || l || ' n''existe pas!');

  END;



  PROCEDURE CALCUL_RESULTAT IS
  BEGIN
    feuille.delete;

    -- si l'année est antérieure à 2022/2023 alors on utilise la formule de l'Université de Caen
    IF ose_formule.intervenant.annee_id < 2022 THEN
      FORMULE_UNICAEN.CALCUL_RESULTAT;
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
      CASE WHEN fvh.structure_is_exterieur = 1 THEN ''Oui'' ELSE ''Non'' END param_1,
      CASE WHEN src.id IS NOT NULL THEN ''Oui'' ELSE ''Non'' END param_2,
      NULL param_3,
      NULL param_4,
      NULL param_5
    FROM
      V_FORMULE_VOLUME_HORAIRE fvh
      LEFT JOIN service s ON s.id = fvh.service_id
      LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
      LEFT JOIN source src ON src.id = ep.source_id AND LOWER(src.code) = ''apogee''
    ORDER BY
      ordre
    ';
  END;

END FORMULE_RENNES2;