CREATE OR REPLACE PACKAGE BODY FORMULE_PARIS8 AS
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



      -- T=IF([.$H20]="Référentiel";0;([.$AI20]+[.$AU20]+[.$BG20])*[.E20])
      WHEN 'T' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AI',l) + cell('AU',l) + cell('BG',l)) * vh.taux_fi;
        END IF;



      -- U=IF([.$H20]="Référentiel";0;([.$AI20]+[.$AU20]+[.$BG20])*[.F20])
      WHEN 'U' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AI',l) + cell('AU',l) + cell('BG',l)) * vh.taux_fa;
        END IF;



      -- V=IF([.$H20]="Référentiel";0;([.$AI20]+[.$AU20]+[.$BG20])*[.G20])
      WHEN 'V' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AI',l) + cell('AU',l) + cell('BG',l)) * vh.taux_fc;
        END IF;



      -- W=IF([.$H20]="Référentiel";[.$AO20]+[.$BA20]+[.$BM20];0)
      WHEN 'W' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN cell('AO',l) + cell('BA',l) + cell('BM',l);
        ELSE
          RETURN 0;
        END IF;



      -- X=IF([.$H20]="Référentiel";0;([.$AK20]+[.$AW20]+[.$BI20]+[.$BS20])*[.E20])
      WHEN 'X' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AK',l) + cell('AW',l) + cell('BI',l) + cell('BS',l)) * vh.taux_fi;
        END IF;



      -- Y=IF([.$H20]="Référentiel";0;([.$AK20]+[.$AW20]+[.$BI20]+[.$BS20])*[.F20])
      WHEN 'Y' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AK',l) + cell('AW',l) + cell('BI',l) + cell('BS',l)) * vh.taux_fa;
        END IF;



      -- Z=IF([.$H20]="Référentiel";0;([.$AK20]+[.$AW20]+[.$BI20]+[.$BS20])*[.G20])
      WHEN 'Z' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AK',l) + cell('AW',l) + cell('BI',l) + cell('BS',l)) * vh.taux_fc;
        END IF;



      -- AA=0
      WHEN 'AA' THEN
        RETURN 0;



      -- AB=IF([.$H20]="Référentiel";[.$AQ20]+[.$BC20]+[.$BO20]+[.$BS20];0)
      WHEN 'AB' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN cell('AQ',l) + cell('BC',l) + cell('BO',l) + cell('BS',l);
        ELSE
          RETURN 0;
        END IF;



      -- AD=IF(ISERROR([.I20]);1;[.I20])
      WHEN 'AD' THEN
        RETURN vh.taux_service_du;



      -- AE=IF(ISERROR([.J20]);1;[.J20])
      WHEN 'AE' THEN
        RETURN vh.taux_service_compl;



      -- AG=IF([.$H20]="Référentiel";0;IF(AND(MID([.$N20];1;4)="PRIO";MID([.$A20];1;5)<>"20595";MID([.$A20];1;5)<>"40");[.$M20]*[.$AD20];0))
      WHEN 'AG' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          IF COALESCE(SUBSTR(vh.param_1, 1, 4),' ') = 'PRIO' AND COALESCE(SUBSTR(vh.structure_code, 1, 5),' ') <> '20595' AND COALESCE(SUBSTR(vh.structure_code, 1, 5),' ') <> '40' THEN
            RETURN vh.heures * cell('AD',l);
          ELSE
            RETURN 0;
          END IF;
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



      -- AM=IF(AND([.$H20]="Référentiel";[.$A20]=[.$K$10];MID([.$A20];1;5)<>"20595";MID([.$A20];1;5)<>"40");[.$M20]*[.$AD20];0)
      WHEN 'AM' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL AND vh.structure_is_univ AND COALESCE(SUBSTR(vh.structure_code, 1, 5),' ') <> '20595' AND COALESCE(SUBSTR(vh.structure_code, 1, 5),' ') <> '40' THEN
          RETURN vh.heures * cell('AD',l);
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



      -- AS=IF(AND([.$H20]<>"Référentiel";[.$A20]=i_structure_code;MID([.$N20];1;4)<>"PRIO";MID([.$A20];1;5)<>"20595";MID([.$A20];1;5)<>"40");[.$M20]*[.$AD20];0)
      WHEN 'AS' THEN
        IF vh.volume_horaire_ref_id IS NULL AND vh.structure_is_affectation AND COALESCE(SUBSTR(vh.param_1, 1, 4),' ') <> 'PRIO' AND COALESCE(SUBSTR(vh.structure_code, 1, 5),' ') <> '20595' AND COALESCE(SUBSTR(vh.structure_code, 1, 5),' ') <> '40' THEN
          RETURN vh.heures * cell('AD',l);
        ELSE
          RETURN 0;
        END IF;



      -- AT15=SUM([.AS$1:.AS$1048576])
      WHEN 'AT15' THEN
        RETURN calcFnc('somme','AS');



      -- AT16=MIN([.AT15];[.AN17])
      WHEN 'AT16' THEN
        RETURN LEAST(cell('AT15'), cell('AN17'));



      -- AT17=[.AN17]-[.AT16]
      WHEN 'AT17' THEN
        RETURN cell('AN17') - cell('AT16');



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



      -- AY=IF(AND(AND([.$H20]="Référentiel";[.$A20]=i_structure_code);( MID([.$A20];1;5))<>"20595";( MID([.$A20];1;5))<>"40");[.$M20]*[.$AD20];0)
      WHEN 'AY' THEN
        IF (vh.volume_horaire_ref_id IS NOT NULL AND vh.structure_is_affectation) AND (COALESCE(SUBSTR(vh.structure_code, 1, 5),' ')) <> '20595' AND (COALESCE(SUBSTR(vh.structure_code, 1, 5),' ')) <> '40' THEN
          RETURN vh.heures * cell('AD',l);
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



      -- BE=IF(AND([.$H20]<>"Référentiel";[.$A20]<>i_structure_code;MID([.$N20];1;4)<>"PRIO";MID([.$A20];1;5)<>"20595";MID([.$A20];1;5)<>"40");[.$M20]*[.$AD20];0)
      WHEN 'BE' THEN
        IF vh.volume_horaire_ref_id IS NULL AND NOT vh.structure_is_affectation AND COALESCE(SUBSTR(vh.param_1, 1, 4),' ') <> 'PRIO' AND COALESCE(SUBSTR(vh.structure_code, 1, 5),' ') <> '20595' AND COALESCE(SUBSTR(vh.structure_code, 1, 5),' ') <> '40' THEN
          RETURN vh.heures * cell('AD',l);
        ELSE
          RETURN 0;
        END IF;



      -- BF15=SUM([.BE$1:.BE$1048576])
      WHEN 'BF15' THEN
        RETURN calcFnc('somme','BE');



      -- BF16=MIN([.BF15];[.AZ17])
      WHEN 'BF16' THEN
        RETURN LEAST(cell('BF15'), cell('AZ17'));



      -- BF17=[.AZ17]-[.BF16]
      WHEN 'BF17' THEN
        RETURN cell('AZ17') - cell('BF16');



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



      -- BK=IF(AND([.$H20]="Référentiel";[.$A20]<>i_structure_code;[.$A20]<>[.$K$10];MID([.$A20];1;5)<>"20595";MID([.$A20];1;5)<>"40");[.$M20]*[.$AD20];0)
      WHEN 'BK' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL AND NOT vh.structure_is_affectation AND NOT vh.structure_is_univ AND COALESCE(SUBSTR(vh.structure_code, 1, 5),' ') <> '20595' AND COALESCE(SUBSTR(vh.structure_code, 1, 5),' ') <> '40' THEN
          RETURN vh.heures * cell('AD',l);
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



      -- BQ=IF(AND([.$D20]="Oui";[.$H20]<>"Référentiel");[.$M20]*[.$AE20];0)
      WHEN 'BQ' THEN
        IF vh.service_statutaire AND vh.volume_horaire_ref_id IS NULL THEN
          RETURN vh.heures * cell('AE',l);
        ELSE
          RETURN 0;
        END IF;



      -- BR=IF(AND(MID([.$A20];1;25)<>"20595";MID([.$A20];1;25)<>"40");[.$BQ20];0)
      WHEN 'BR' THEN
        IF COALESCE(SUBSTR(vh.structure_code, 1, 25),' ') <> '20595' AND COALESCE(SUBSTR(vh.structure_code, 1, 25),' ') <> '40' THEN
          RETURN cell('BQ',l);
        ELSE
          RETURN 0;
        END IF;



      -- BS15=SUM([.BR20:.BR500])
      WHEN 'BS15' THEN
        RETURN calcFnc('somme','BR');



      -- BS=IF([.$BS$15]<i_service_du;0;IF(OR(MID([.$A20];1;25)="20595";MID([.$A20];1;25)="40");[.$M20]*[.$AE20];0))
      WHEN 'BS' THEN
        IF cell('BS15') < i.service_du THEN
          RETURN 0;
        ELSE
          IF COALESCE(SUBSTR(vh.structure_code, 1, 25),' ') = '20595' OR COALESCE(SUBSTR(vh.structure_code, 1, 25),' ') = '40' THEN
            RETURN vh.heures * cell('AE',l);
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

    IF ose_formule.intervenant.annee_id < 2022 THEN
      FORMULE_PARIS8_2021.CALCUL_RESULTAT;
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
      ose_formule.volumes_horaires.items(l).heures_primes            := mainCell('Heures compl. FC Maj.', 'AA',l);
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

END FORMULE_PARIS8;