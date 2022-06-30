CREATE OR REPLACE PACKAGE BODY FORMULE_COTE_AZUR AS
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



      -- T=IF([.$H20]="Référentiel";0;[.$AG20]*[.E20])
      WHEN 'T' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN cell('AG',l) * vh.taux_fi;
        END IF;



      -- U=IF([.$H20]="Référentiel";0;[.$AG20]*[.F20])
      WHEN 'U' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN cell('AG',l) * vh.taux_fa;
        END IF;



      -- V=IF([.$H20]="Référentiel";0;[.$AG20]*[.G20])
      WHEN 'V' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN cell('AG',l) * vh.taux_fc;
        END IF;



      -- W=IF([.$H20]="Référentiel";[.$AG20];0)
      WHEN 'W' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN cell('AG',l);
        ELSE
          RETURN 0;
        END IF;



      -- X=IF([.$H20]="Référentiel";0;([.$AK20]+[.$AN20])*[.E20])
      WHEN 'X' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AK',l) + cell('AN',l)) * vh.taux_fi;
        END IF;



      -- Y=IF([.$H20]="Référentiel";0;([.$AK20]+[.$AN20])*[.F20])
      WHEN 'Y' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AK',l) + cell('AN',l)) * vh.taux_fa;
        END IF;



      -- Z=IF([.$H20]="Référentiel";0;([.$AK20]+[.$AN20])*[.G20])
      WHEN 'Z' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AK',l) + cell('AN',l)) * vh.taux_fc;
        END IF;



      -- AA=0
      WHEN 'AA' THEN
        RETURN 0;



      -- AB=IF([.$H20]="Référentiel";[.$AK20]+[.$AN20];0)
      WHEN 'AB' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN cell('AK',l) + cell('AN',l);
        ELSE
          RETURN 0;
        END IF;



      -- AD=IF(ISERROR([.I20]);1;[.I20])
      WHEN 'AD' THEN
        RETURN vh.taux_service_du;



      -- AE=IF([.$D20]="Oui";[.$M20]*[.$AD20];0)
      WHEN 'AE' THEN
        IF vh.service_statutaire THEN
          RETURN vh.heures * cell('AD',l);
        ELSE
          RETURN 0;
        END IF;



      -- AF=IF([.AF19]+[.AE20]>i_service_du;i_service_du;[.AF19]+[.AE20])
      WHEN 'AF' THEN
        IF cell('AF',l-1) + cell('AE',l) > i.service_du THEN
          RETURN i.service_du;
        ELSE
          RETURN cell('AF',l-1) + cell('AE',l);
        END IF;



      -- AG=[.AF20]-[.AF19]
      WHEN 'AG' THEN
        RETURN cell('AF',l) - cell('AF',l-1);



      -- AH19=SUM([.AG$1:.AG$1048576])
      WHEN 'AH19' THEN
        RETURN calcFnc('somme','AG');



      -- AI=IF([.AF19]+[.AE20]<i_service_du;0;(([.AF19]+[.AE20])-i_service_du)/[.AD20])
      WHEN 'AI' THEN
        IF cell('AF',l-1) + cell('AE',l) < i.service_du THEN
          RETURN 0;
        ELSE
          RETURN ((cell('AF',l-1) + cell('AE',l)) - i.service_du) / cell('AD',l);
        END IF;



      -- AJ=IF(ISERROR([.J20]);1;[.J20])
      WHEN 'AJ' THEN
        RETURN vh.taux_service_compl;



      -- AK=IF(OR([.$AH$19]<i_service_du;i_depassement_service_du_sans_hc="Oui");0;[.AI20]*[.AJ20])
      WHEN 'AK' THEN
        IF cell('AH19') < i.service_du OR i.depassement_service_du_sans_hc THEN
          RETURN 0;
        ELSE
          RETURN cell('AI',l) * cell('AJ',l);
        END IF;



      -- AL17=[.AH19]/5
      WHEN 'AL17' THEN
        RETURN cell('AH19') / 5;



      -- AL=IF(OR([.$AH$19]<i_service_du;i_depassement_service_du_sans_hc="Oui";[.$D20]="Oui");0;[.M20]*[.AJ20])
      WHEN 'AL' THEN
        IF cell('AH19') < i.service_du OR i.depassement_service_du_sans_hc OR vh.service_statutaire THEN
          RETURN 0;
        ELSE
          RETURN vh.heures * cell('AJ',l);
        END IF;



      -- AM17=[.AH19]/5
      WHEN 'AM17' THEN
        RETURN cell('AH19') / 5;



      -- AM=IF([.AM19]+[.AL20]>[.$AL$17];[.$AL$17];[.AM19]+[.AL20])
      WHEN 'AM' THEN
        IF cell('AM',l-1) + cell('AL',l) > cell('AL17') THEN
          RETURN cell('AL17');
        ELSE
          RETURN cell('AM',l-1) + cell('AL',l);
        END IF;



      -- AN=[.AM20]-[.AM19]
      WHEN 'AN' THEN
        RETURN cell('AM',l) - cell('AM',l-1);



      -- AF19
      WHEN 'AF19' THEN
        RETURN 0;



      -- AM19
      WHEN 'AM19' THEN
        RETURN 0;




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

END FORMULE_COTE_AZUR;