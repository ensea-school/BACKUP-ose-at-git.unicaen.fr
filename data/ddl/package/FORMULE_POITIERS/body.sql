CREATE OR REPLACE PACKAGE BODY FORMULE_POITIERS AS
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



      -- U=IF([.$I20]="Référentiel";0;[.$AJ20]*[.F20])
      WHEN 'U' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN cell('AJ',l) * vh.taux_fi;
        END IF;



      -- V=IF([.$I20]="Référentiel";0;[.$AJ20]*[.G20])
      WHEN 'V' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN cell('AJ',l) * vh.taux_fa;
        END IF;



      -- W=IF([.$I20]="Référentiel";0;[.$AJ20]*[.H20])
      WHEN 'W' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN cell('AJ',l) * vh.taux_fc;
        END IF;



      -- X=IF([.$I20]="Référentiel";[.$AJ20];0)
      WHEN 'X' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN cell('AJ',l);
        ELSE
          RETURN 0;
        END IF;



      -- Y=IF([.$I20]="Référentiel";0;[.$AL20]*[.F20])
      WHEN 'Y' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN cell('AL',l) * vh.taux_fi;
        END IF;



      -- Z=IF([.$I20]="Référentiel";0;[.$AL20]*[.G20])
      WHEN 'Z' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN cell('AL',l) * vh.taux_fa;
        END IF;



      -- AA=IF([.$I20]="Référentiel";0;[.$AL20]*[.H20])
      WHEN 'AA' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN cell('AL',l) * vh.taux_fc;
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



      -- AF=IF(ISERROR([.K20]);1;[.K20]*IF(ISERROR([.M20]);1;[.M20]))
      WHEN 'AF' THEN
        RETURN (vh.taux_service_compl * vh.ponderation_service_compl);



      -- AH=IF([.$E20]="Oui";[.$N20]*[.$AE20];0)
      WHEN 'AH' THEN
        IF vh.service_statutaire THEN
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



      -- AK=IF([.AI$17]=0;IF([.AE20]=0;0;([.AH20]-[.AJ20])/[.$AE20]);0)
      WHEN 'AK' THEN
        IF cell('AI17') = 0 THEN
          IF cell('AE',l) = 0 THEN
            RETURN 0;
          ELSE
            RETURN (cell('AH',l) - cell('AJ',l)) / cell('AE',l);
          END IF;
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
      FORMULE_POITIERS_2021.CALCUL_RESULTAT;
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
      v_formule_intervenant fi
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
      v_formule_volume_horaire fvh
    ORDER BY
      ordre
    ';
  END;

END FORMULE_POITIERS;