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



      -- U=IF([.$I20]="Référentiel";0;([.$AL20]+[.$AR20]+[.$AX20]+[.$BD20])*[.F20])
      WHEN 'U' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AL',l) + cell('AR',l) + cell('AX',l) + cell('BD',l)) * vh.taux_fi;
        END IF;



      -- V=IF([.$I20]="Référentiel";0;([.$AL20]+[.$AR20]+[.$AX20]+[.$BD20])*[.G20])
      WHEN 'V' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AL',l) + cell('AR',l) + cell('AX',l) + cell('BD',l)) * vh.taux_fa;
        END IF;



      -- W=IF([.$I20]="Référentiel";0;([.$AL20]+[.$AR20]+[.$AX20]+[.$BD20])*[.H20])
      WHEN 'W' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AL',l) + cell('AR',l) + cell('AX',l) + cell('BD',l)) * vh.taux_fc;
        END IF;



      -- X=IF([.$I20]="Référentiel";[.$AL20]+[.$AR20]+[.$AX20]+[.$BD20];0)
      WHEN 'X' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN cell('AL',l) + cell('AR',l) + cell('AX',l) + cell('BD',l);
        ELSE
          RETURN 0;
        END IF;



      -- Y=IF([.$I20]="Référentiel";0;([.$AN20]+[.$AT20]+[.$AZ20]+[.$BF20])*[.F20])
      WHEN 'Y' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AN',l) + cell('AT',l) + cell('AZ',l) + cell('BF',l)) * vh.taux_fi;
        END IF;



      -- Z=IF([.$I20]="Référentiel";0;([.$AN20]+[.$AT20]+[.$AZ20]+[.$BF20])*[.G20])
      WHEN 'Z' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AN',l) + cell('AT',l) + cell('AZ',l) + cell('BF',l)) * vh.taux_fa;
        END IF;



      -- AA=IF([.$I20]="Référentiel";0;([.$AN20]+[.$AT20]+[.$AZ20]+[.$BF20])*[.H20])
      WHEN 'AA' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AN',l) + cell('AT',l) + cell('AZ',l) + cell('BF',l)) * vh.taux_fc;
        END IF;



      -- AB=0
      WHEN 'AB' THEN
        RETURN 0;



      -- AC=IF([.$I20]="Référentiel";([.$AN20]+[.$AT20]+[.$AZ20]+[.$BF20]);0)
      WHEN 'AC' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN (cell('AN',l) + cell('AT',l) + cell('AZ',l) + cell('BF',l));
        ELSE
          RETURN 0;
        END IF;



      -- AE=IF([.$I20]="INTERA";COM.MICROSOFT.IFS([.$P20]<=40;[.$J20]*1;[.$P20]<=80;[.$J20]*2;[.$P20]<=120;[.$J20]*3;[.$P20]<=160;[.$J20]*4;[.$P20]>160;[.$J20]*5);1)
      WHEN 'AE' THEN
        IF vh.type_intervention_code = 'INTERA' THEN
          RETURN CASE
            WHEN vh.param_2 <= 40 THEN vh.taux_service_du
            WHEN vh.param_2 <= 80 THEN vh.taux_service_du * 2
            WHEN vh.param_2 <= 120 THEN vh.taux_service_du * 3
            WHEN vh.param_2 <= 160 THEN vh.taux_service_du * 4
            WHEN vh.param_2 > 160 THEN vh.taux_service_du * 5
          END;
        ELSE
          RETURN 1;
        END IF;



      -- AF=IF(ISERROR([.J20]);1;IF([.$I20]="INTERA";[.L20]*[.$AE20];[.J20]*[.L20]))
      WHEN 'AF' THEN
        IF vh.type_intervention_code = 'INTERA' THEN
          RETURN vh.ponderation_service_du * cell('AE',l);
        ELSE
          RETURN vh.taux_service_du * vh.ponderation_service_du;
        END IF;



      -- AG=IF(ISERROR([.K20]);1;IF([.$I20]="INTERA";[.M20]*[.$AE20];[.K20]*[.M20]))
      WHEN 'AG' THEN
        IF vh.type_intervention_code = 'INTERA' THEN
          RETURN vh.ponderation_service_compl * cell('AE',l);
        ELSE
          RETURN vh.taux_service_compl * vh.ponderation_service_compl;
        END IF;



      -- AH=IF(AND([.$E20]="Oui";[.$I20]<>"Référentiel");[.$N20]*[.$AF20];0)
      WHEN 'AH' THEN
        IF vh.service_statutaire AND vh.volume_horaire_ref_id IS NULL THEN
          RETURN vh.heures * cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- AI15=SUM([.AH20:.AH500])
      WHEN 'AI15' THEN
        RETURN calcFnc('somme','AH');



      -- AI16=IF([.AI15]>=[.AH16];1;0)
      WHEN 'AI16' THEN
        IF cell('AI15') >= cell('AH16') THEN
          RETURN 1;
        ELSE
          RETURN 0;
        END IF;



      -- AJ=IF(AND([.$E20]="Oui";[.$D20]<>"Oui";[.$A20]=i_structure_code;[.$O20]="Oui");IF(OR([.$AI$16]=1;[.$I20]<>"Référentiel");[.$N20]*[.$AF20];0);0)
      WHEN 'AJ' THEN
        IF vh.service_statutaire AND NOT vh.structure_is_exterieur AND vh.structure_is_affectation AND vh.param_1 = 'Oui' THEN
          IF cell('AI16') = 1 OR vh.volume_horaire_ref_id IS NULL THEN
            RETURN vh.heures * cell('AF',l);
          ELSE
            RETURN 0;
          END IF;
        ELSE
          RETURN 0;
        END IF;



      -- AK15=SUM([.AJ$1:.AJ$1048576])
      WHEN 'AK15' THEN
        RETURN calcFnc('somme','AJ');



      -- AK16=MIN([.AK15];i_service_du)
      WHEN 'AK16' THEN
        RETURN LEAST(cell('AK15'), i.service_du);



      -- AK17=i_service_du-[.AK16]
      WHEN 'AK17' THEN
        RETURN i.service_du - cell('AK16');



      -- AK=IF([.AK$15]>0;[.AJ20]/[.AK$15];0)
      WHEN 'AK' THEN
        IF cell('AK15') > 0 THEN
          RETURN cell('AJ',l) / cell('AK15');
        ELSE
          RETURN 0;
        END IF;



      -- AL=[.AK$16]*[.AK20]
      WHEN 'AL' THEN
        RETURN cell('AK16') * cell('AK',l);



      -- AM=IF([.AK$17]=0;([.AJ20]-[.AL20])/[.$AF20];0)
      WHEN 'AM' THEN
        IF cell('AK17') = 0 THEN
          RETURN (cell('AJ',l) - cell('AL',l)) / cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- AN=IF(i_depassement_service_du_sans_hc="Non";[.AM20]*[.$AG20];0)
      WHEN 'AN' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('AM',l) * cell('AG',l);
        ELSE
          RETURN 0;
        END IF;



      -- AP=IF(AND([.$E20]="Oui";[.$D20]<>"Oui";[.$A20]<>i_structure_code;[.$O20]="Oui");IF(OR([.$AI$16]=1;[.$I20]<>"Référentiel");[.$N20]*[.$AF20];0);0)
      WHEN 'AP' THEN
        IF vh.service_statutaire AND NOT vh.structure_is_exterieur AND NOT vh.structure_is_affectation AND vh.param_1 = 'Oui' THEN
          IF cell('AI16') = 1 OR vh.volume_horaire_ref_id IS NULL THEN
            RETURN vh.heures * cell('AF',l);
          ELSE
            RETURN 0;
          END IF;
        ELSE
          RETURN 0;
        END IF;



      -- AQ15=SUM([.AP$1:.AP$1048576])
      WHEN 'AQ15' THEN
        RETURN calcFnc('somme','AP');



      -- AQ16=MIN([.AQ15];[.AK17])
      WHEN 'AQ16' THEN
        RETURN LEAST(cell('AQ15'), cell('AK17'));



      -- AQ17=[.AK17]-[.AQ16]
      WHEN 'AQ17' THEN
        RETURN cell('AK17') - cell('AQ16');



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



      -- AS=IF([.AQ$17]=0;([.AP20]-[.AR20])/[.$AF20];0)
      WHEN 'AS' THEN
        IF cell('AQ17') = 0 THEN
          RETURN (cell('AP',l) - cell('AR',l)) / cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- AT=IF(i_depassement_service_du_sans_hc="Non";[.AS20]*[.$AG20];0)
      WHEN 'AT' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('AS',l) * cell('AG',l);
        ELSE
          RETURN 0;
        END IF;



      -- AV=IF(AND([.$E20]="Oui";[.$D20]<>"Oui";[.$O20]<>"Oui");IF(OR([.$AI$16]=1;[.$I20]<>"Référentiel");[.$N20]*[.$AF20];0);0)
      WHEN 'AV' THEN
        IF vh.service_statutaire AND NOT vh.structure_is_exterieur AND vh.param_1 <> 'Oui' THEN
          IF cell('AI16') = 1 OR vh.volume_horaire_ref_id IS NULL THEN
            RETURN vh.heures * cell('AF',l);
          ELSE
            RETURN 0;
          END IF;
        ELSE
          RETURN 0;
        END IF;



      -- AW15=SUM([.AV$1:.AV$1048576])
      WHEN 'AW15' THEN
        RETURN calcFnc('somme','AV');



      -- AW16=MIN([.AW15];[.AQ17])
      WHEN 'AW16' THEN
        RETURN LEAST(cell('AW15'), cell('AQ17'));



      -- AW17=[.AQ17]-[.AW16]
      WHEN 'AW17' THEN
        RETURN cell('AQ17') - cell('AW16');



      -- AW=IF([.AW$15]>0;[.AV20]/[.AW$15];0)
      WHEN 'AW' THEN
        IF cell('AW15') > 0 THEN
          RETURN cell('AV',l) / cell('AW15');
        ELSE
          RETURN 0;
        END IF;



      -- AX=[.AW$16]*[.AW20]
      WHEN 'AX' THEN
        RETURN cell('AW16') * cell('AW',l);



      -- AY=IF([.AW$17]=0;([.AV20]-[.AX20])/[.$AF20];0)
      WHEN 'AY' THEN
        IF cell('AW17') = 0 THEN
          RETURN (cell('AV',l) - cell('AX',l)) / cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- AZ=IF(i_depassement_service_du_sans_hc="Non";[.AY20]*[.$AG20];0)
      WHEN 'AZ' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('AY',l) * cell('AG',l);
        ELSE
          RETURN 0;
        END IF;



      -- BB=IF(AND([.$E20]="Oui";[.$D20]="Oui");IF(OR([.$AI$16]=1;[.$I20]<>"Référentiel");[.$N20]*[.$AF20];0);0)
      WHEN 'BB' THEN
        IF vh.service_statutaire AND vh.structure_is_exterieur THEN
          IF cell('AI16') = 1 OR vh.volume_horaire_ref_id IS NULL THEN
            RETURN vh.heures * cell('AF',l);
          ELSE
            RETURN 0;
          END IF;
        ELSE
          RETURN 0;
        END IF;



      -- BC15=SUM([.BB$1:.BB$1048576])
      WHEN 'BC15' THEN
        RETURN calcFnc('somme','BB');



      -- BC16=MIN([.BC15];[.AW17])
      WHEN 'BC16' THEN
        RETURN LEAST(cell('BC15'), cell('AW17'));



      -- BC17=[.AW17]-[.BC16]
      WHEN 'BC17' THEN
        RETURN cell('AW17') - cell('BC16');



      -- BC=IF([.BC$15]>0;[.BB20]/[.BC$15];0)
      WHEN 'BC' THEN
        IF cell('BC15') > 0 THEN
          RETURN cell('BB',l) / cell('BC15');
        ELSE
          RETURN 0;
        END IF;



      -- BD=[.BC$16]*[.BC20]
      WHEN 'BD' THEN
        RETURN cell('BC16') * cell('BC',l);



      -- BE=IF([.BC$17]=0;([.BB20]-[.BD20])/[.$AF20];0)
      WHEN 'BE' THEN
        IF cell('BC17') = 0 THEN
          RETURN (cell('BB',l) - cell('BD',l)) / cell('AF',l);
        ELSE
          RETURN 0;
        END IF;



      -- BF=IF(i_depassement_service_du_sans_hc="Non";[.BE20]*[.$AG20];0)
      WHEN 'BF' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('BE',l) * cell('AG',l);
        ELSE
          RETURN 0;
        END IF;



      -- AH16
      WHEN 'AH16' THEN
        RETURN 64;




    ELSE
      dbms_output.put_line('La colonne c=' || c || ', l=' || l || ' n''existe pas!');
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
      CASE WHEN src.id IS NOT NULL THEN ''Oui'' ELSE ''Non'' END param_1,
      e.fi + e.fc + e.fa param_2,
      NULL param_3,
      NULL param_4,
      NULL param_5
    FROM
      V_FORMULE_VOLUME_HORAIRE fvh
      LEFT JOIN service s ON s.id = fvh.service_id
      LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
      LEFT JOIN source src ON src.id = ep.source_id AND LOWER(src.code) = ''apogee''
      LEFT JOIN effectifs e ON e.element_pedagogique_id = ep.id AND e.histo_destruction IS NULL
    ORDER BY
      ordre
    ';
  END;

END FORMULE_RENNES2;