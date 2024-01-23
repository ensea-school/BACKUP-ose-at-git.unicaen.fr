CREATE OR REPLACE PACKAGE BODY FORMULE_ROUEN AS
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



      -- U=IF([.$I20]="Référentiel";0;([.$AM20]+[.$AS20])*[.F20])
      WHEN 'U' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AM',l) + cell('AS',l)) * vh.taux_fi;
        END IF;



      -- V=IF([.$I20]="Référentiel";0;([.$AM20]+[.$AS20])*[.G20])
      WHEN 'V' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AM',l) + cell('AS',l)) * vh.taux_fa;
        END IF;



      -- W=IF([.$I20]="Référentiel";0;([.$AM20]+[.$AS20])*[.H20])
      WHEN 'W' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AM',l) + cell('AS',l)) * vh.taux_fc;
        END IF;



      -- X=IF([.$I20]="Référentiel";[.$AY20]+[.$BE20]+[.$BK20]+[.$BQ20];0)
      WHEN 'X' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN cell('AY',l) + cell('BE',l) + cell('BK',l) + cell('BQ',l);
        ELSE
          RETURN 0;
        END IF;



      -- Y=IF([.$I20]="Référentiel";0;([.$AO20]+[.$AU20])*[.F20])
      WHEN 'Y' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AO',l) + cell('AU',l)) * vh.taux_fi;
        END IF;



      -- Z=IF([.$I20]="Référentiel";0;([.$AO20]+[.$AU20])*[.G20])
      WHEN 'Z' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AO',l) + cell('AU',l)) * vh.taux_fa;
        END IF;



      -- AA=IF([.$I20]="Référentiel";0;([.$AO20]+[.$AU20])*[.H20])
      WHEN 'AA' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN (cell('AO',l) + cell('AU',l)) * vh.taux_fc;
        END IF;



      -- AB=0
      WHEN 'AB' THEN
        RETURN 0;



      -- AC=IF([.$I20]="Référentiel";[.$BA20]+[.$BG20]+[.$BM20]+[.$BS20];0)
      WHEN 'AC' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN cell('BA',l) + cell('BG',l) + cell('BM',l) + cell('BS',l);
        ELSE
          RETURN 0;
        END IF;



      -- AE=IF([.$I20]="Référentiel";0;[.$N20])*IF(ISERROR([.$J20]);1;[.$J20])
      WHEN 'AE' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          RETURN vh.heures * vh.taux_service_du;
        END IF;



      -- AF=IF([.$I20]="TP";[.$N20];0)*IF(ISERROR([.$J20]);1;[.$J20])
      WHEN 'AF' THEN
        IF vh.type_intervention_code = 'TP' THEN
          RETURN vh.heures * vh.taux_service_du;
        ELSE
          RETURN 0;
        END IF;



      -- AG15=SUM([.AE$1:.AE$1048576])
      WHEN 'AG15' THEN
        RETURN calcFnc('somme','AE');



      -- AG16=SUM([.AF$1:.AF$1048576])
      WHEN 'AG16' THEN
        RETURN calcFnc('somme','AF');



      -- AG17=IF([.AG15]=0;0;[.AG16]/[.AG15])
      WHEN 'AG17' THEN
        IF cell('AG15') = 0 THEN
          RETURN 0;
        ELSE
          RETURN cell('AG16') / cell('AG15');
        END IF;



      -- AH=IF(ISERROR([.J20]);1;IF([.I20]="TP";IF(AND([.$AG$15]<i_heures_decharge;[.$AG16]<>0);[.J20];([.$AG$16]-(([.$AG$15]-i_heures_decharge)*[.$AG$17])+(([.$AG$15]-i_heures_decharge)*[.$AG$17]*[.K20]))/[.$AG$16]);[.J20]))
      WHEN 'AH' THEN
        IF vh.type_intervention_code = 'TP' THEN
          IF cell('AG15') < i.heures_service_statutaire AND cell('AG16') <> 0 THEN
            RETURN vh.taux_service_du;
          ELSE
            RETURN (cell('AG16') - ((cell('AG15') - i.heures_service_statutaire) * cell('AG17')) + ((cell('AG15') - i.heures_service_statutaire) * cell('AG17') * vh.taux_service_compl)) / cell('AG16');
          END IF;
        ELSE
          RETURN vh.taux_service_du;
        END IF;



      -- AI=IF(ISERROR([.K20]);1;IF([.I20]="TP";IF(AND([.$AG$15]<i_heures_decharge;[.$AG16]<>0);[.J20];([.$AG$16]-(([.$AG$15]-i_heures_decharge)*[.$AG$17])+(([.$AG$15]-i_heures_decharge)*[.$AG$17]*[.K20]))/[.$AG$16]);[.K20]))
      WHEN 'AI' THEN
        IF vh.type_intervention_code = 'TP' THEN
          IF cell('AG15') < i.heures_service_statutaire AND cell('AG16') <> 0 THEN
            RETURN vh.taux_service_du;
          ELSE
            RETURN (cell('AG16') - ((cell('AG15') - i.heures_service_statutaire) * cell('AG17')) + ((cell('AG15') - i.heures_service_statutaire) * cell('AG17') * vh.taux_service_compl)) / cell('AG16');
          END IF;
        ELSE
          RETURN vh.taux_service_compl;
        END IF;



      -- AK=IF(AND([.$E20]="Oui";[.$D20]<>"Oui";[.$B20]="Oui";[.$I20]<>"Référentiel");[.$N20]*[.$AH20];0)
      WHEN 'AK' THEN
        IF vh.service_statutaire AND NOT vh.structure_is_exterieur AND vh.structure_is_affectation AND vh.volume_horaire_ref_id IS NULL THEN
          RETURN vh.heures * cell('AH',l);
        ELSE
          RETURN 0;
        END IF;



      -- AL15=SUM([.AK$1:.AK$1048576])
      WHEN 'AL15' THEN
        RETURN calcFnc('somme','AK');



      -- AL16=MIN([.AL15];i_service_du)
      WHEN 'AL16' THEN
        RETURN LEAST(cell('AL15'), i.service_du);



      -- AL17=i_service_du-[.AL16]
      WHEN 'AL17' THEN
        RETURN i.service_du - cell('AL16');



      -- AL=IF([.AL$15]>0;[.AK20]/[.AL$15];0)
      WHEN 'AL' THEN
        IF cell('AL15') > 0 THEN
          RETURN cell('AK',l) / cell('AL15');
        ELSE
          RETURN 0;
        END IF;



      -- AM=[.AL$16]*[.AL20]
      WHEN 'AM' THEN
        RETURN cell('AL16') * cell('AL',l);



      -- AN=IF([.AL$17]=0;([.AK20]-[.AM20])/[.$AH20];0)
      WHEN 'AN' THEN
        IF cell('AL17') = 0 THEN
          RETURN (cell('AK',l) - cell('AM',l)) / cell('AH',l);
        ELSE
          RETURN 0;
        END IF;



      -- AO=IF(i_depassement_service_du_sans_hc="Non";[.AN20]*[.$AI20];0)
      WHEN 'AO' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('AN',l) * cell('AI',l);
        ELSE
          RETURN 0;
        END IF;



      -- AQ=IF(AND([.$E20]="Oui";[.$B20]<>"Oui";[.$I20]<>"Référentiel");[.$N20]*[.$AH20];0)
      WHEN 'AQ' THEN
        IF vh.service_statutaire AND NOT vh.structure_is_affectation AND vh.volume_horaire_ref_id IS NULL THEN
          RETURN vh.heures * cell('AH',l);
        ELSE
          RETURN 0;
        END IF;



      -- AR15=SUM([.AQ$1:.AQ$1048576])
      WHEN 'AR15' THEN
        RETURN calcFnc('somme','AQ');



      -- AR16=MIN([.AR15];[.AL17])
      WHEN 'AR16' THEN
        RETURN LEAST(cell('AR15'), cell('AL17'));



      -- AR17=[.AL17]-[.AR16]
      WHEN 'AR17' THEN
        RETURN cell('AL17') - cell('AR16');



      -- AR=IF([.AR$15]>0;[.AQ20]/[.AR$15];0)
      WHEN 'AR' THEN
        IF cell('AR15') > 0 THEN
          RETURN cell('AQ',l) / cell('AR15');
        ELSE
          RETURN 0;
        END IF;



      -- AS=[.AR$16]*[.AR20]
      WHEN 'AS' THEN
        RETURN cell('AR16') * cell('AR',l);



      -- AT=IF([.AR$17]=0;([.AQ20]-[.AS20])/[.$AH20];0)
      WHEN 'AT' THEN
        IF cell('AR17') = 0 THEN
          RETURN (cell('AQ',l) - cell('AS',l)) / cell('AH',l);
        ELSE
          RETURN 0;
        END IF;



      -- AU=IF(i_depassement_service_du_sans_hc="Non";[.AT20]*[.$AI20];0)
      WHEN 'AU' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('AT',l) * cell('AI',l);
        ELSE
          RETURN 0;
        END IF;



      -- AW=IF(AND([.$E20]="Oui";[.$B20]="Oui";[.$I20]="Référentiel";[.$O20]="G3");[.$N20]*[.$AH20];0)
      WHEN 'AW' THEN
        IF vh.service_statutaire AND vh.structure_is_affectation AND vh.volume_horaire_ref_id IS NOT NULL AND vh.param_1 = 'G3' THEN
          RETURN vh.heures * cell('AH',l);
        ELSE
          RETURN 0;
        END IF;



      -- AX15=SUM([.AW$1:.AW$1048576])
      WHEN 'AX15' THEN
        RETURN calcFnc('somme','AW');



      -- AX16=MIN([.AX15];[.AR17])
      WHEN 'AX16' THEN
        RETURN LEAST(cell('AX15'), cell('AR17'));



      -- AX17=[.AR17]-[.AX16]
      WHEN 'AX17' THEN
        RETURN cell('AR17') - cell('AX16');



      -- AX=IF([.AX$15]>0;[.AW20]/[.AX$15];0)
      WHEN 'AX' THEN
        IF cell('AX15') > 0 THEN
          RETURN cell('AW',l) / cell('AX15');
        ELSE
          RETURN 0;
        END IF;



      -- AY=[.AX20]*[.AX$16]
      WHEN 'AY' THEN
        RETURN cell('AX',l) * cell('AX16');



      -- AZ=IF([.AX$17]=0;([.AW20]-[.AY20])/[.$AH20];0)
      WHEN 'AZ' THEN
        IF cell('AX17') = 0 THEN
          RETURN (cell('AW',l) - cell('AY',l)) / cell('AH',l);
        ELSE
          RETURN 0;
        END IF;



      -- BA=IF(i_depassement_service_du_sans_hc="Non";[.AZ20]*[.$AI20];0)
      WHEN 'BA' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('AZ',l) * cell('AI',l);
        ELSE
          RETURN 0;
        END IF;



      -- BC=IF(AND([.$E20]="Oui";[.$B20]<>"Oui";[.$I20]="Référentiel";[.$O20]="G3");[.$N20]*[.$AH20];0)
      WHEN 'BC' THEN
        IF vh.service_statutaire AND NOT vh.structure_is_affectation AND vh.volume_horaire_ref_id IS NOT NULL AND vh.param_1 = 'G3' THEN
          RETURN vh.heures * cell('AH',l);
        ELSE
          RETURN 0;
        END IF;



      -- BD15=SUM([.BC$1:.BC$1048576])
      WHEN 'BD15' THEN
        RETURN calcFnc('somme','BC');



      -- BD16=MIN([.BD15];[.AX17])
      WHEN 'BD16' THEN
        RETURN LEAST(cell('BD15'), cell('AX17'));



      -- BD17=[.AX17]-[.BD16]
      WHEN 'BD17' THEN
        RETURN cell('AX17') - cell('BD16');



      -- BD=IF([.BD$15]>0;[.BC20]/[.BD$15];0)
      WHEN 'BD' THEN
        IF cell('BD15') > 0 THEN
          RETURN cell('BC',l) / cell('BD15');
        ELSE
          RETURN 0;
        END IF;



      -- BE=[.BD20]*[.BD$16]
      WHEN 'BE' THEN
        RETURN cell('BD',l) * cell('BD16');



      -- BF=IF([.BD$17]=0;([.BC20]-[.BE20])/[.$AH20];0)
      WHEN 'BF' THEN
        IF cell('BD17') = 0 THEN
          RETURN (cell('BC',l) - cell('BE',l)) / cell('AH',l);
        ELSE
          RETURN 0;
        END IF;



      -- BG=IF(i_depassement_service_du_sans_hc="Non";[.BF20]*[.$AI20];0)
      WHEN 'BG' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('BF',l) * cell('AI',l);
        ELSE
          RETURN 0;
        END IF;



      -- BI=IF(AND([.$I20]="Référentiel";[.$O20]<>"G3";[.$O20]<>"G2");[.$N20]*[.$AH20];0)
      WHEN 'BI' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL AND vh.param_1 <> 'G3' AND vh.param_1 <> 'G2' THEN
          RETURN vh.heures * cell('AH',l);
        ELSE
          RETURN 0;
        END IF;



      -- BJ15=SUM([.BI$1:.BI$1048576])
      WHEN 'BJ15' THEN
        RETURN calcFnc('somme','BI');



      -- BJ16=MIN([.BJ15];[.BD17])
      WHEN 'BJ16' THEN
        RETURN LEAST(cell('BJ15'), cell('BD17'));



      -- BJ17=[.BD17]-[.BJ16]
      WHEN 'BJ17' THEN
        RETURN cell('BD17') - cell('BJ16');



      -- BJ=IF([.BJ$15]>0;[.BI20]/[.BJ$15];0)
      WHEN 'BJ' THEN
        IF cell('BJ15') > 0 THEN
          RETURN cell('BI',l) / cell('BJ15');
        ELSE
          RETURN 0;
        END IF;



      -- BK=[.BJ20]*[.BJ$16]
      WHEN 'BK' THEN
        RETURN cell('BJ',l) * cell('BJ16');



      -- BL=IF([.BJ$17]=0;([.BI20]-[.BK20])/[.$AH20];0)
      WHEN 'BL' THEN
        IF cell('BJ17') = 0 THEN
          RETURN (cell('BI',l) - cell('BK',l)) / cell('AH',l);
        ELSE
          RETURN 0;
        END IF;



      -- BM=IF(i_depassement_service_du_sans_hc="Non";[.BL20]*[.$AI20];0)
      WHEN 'BM' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('BL',l) * cell('AI',l);
        ELSE
          RETURN 0;
        END IF;



      -- BO=IF(AND([.$I20]="Référentiel";[.$O20]="G2");[.$N20]*[.$AH20];0)
      WHEN 'BO' THEN
        IF vh.volume_horaire_ref_id IS NOT NULL AND vh.param_1 = 'G2' THEN
          RETURN vh.heures * cell('AH',l);
        ELSE
          RETURN 0;
        END IF;



      -- BP15=SUM([.BO$1:.BO$1048576])
      WHEN 'BP15' THEN
        RETURN calcFnc('somme','BO');



      -- BP16=MIN([.BP15];[.BJ17])
      WHEN 'BP16' THEN
        RETURN LEAST(cell('BP15'), cell('BJ17'));



      -- BP17=[.BJ17]-[.BP16]
      WHEN 'BP17' THEN
        RETURN cell('BJ17') - cell('BP16');



      -- BP=IF([.BP$15]>0;[.BO20]/[.BP$15];0)
      WHEN 'BP' THEN
        IF cell('BP15') > 0 THEN
          RETURN cell('BO',l) / cell('BP15');
        ELSE
          RETURN 0;
        END IF;



      -- BQ=[.BP20]*[.BP$16]
      WHEN 'BQ' THEN
        RETURN cell('BP',l) * cell('BP16');



      -- BR=IF([.BP$17]=0;([.BO20]-[.BQ20])/[.$AH20];0)
      WHEN 'BR' THEN
        IF cell('BP17') = 0 THEN
          RETURN (cell('BO',l) - cell('BQ',l)) / cell('AH',l);
        ELSE
          RETURN 0;
        END IF;



      -- BS=IF(i_depassement_service_du_sans_hc="Non";[.BR20]*[.$AI20];0)
      WHEN 'BS' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('BR',l) * cell('AI',l);
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
      COALESCE(tfr.code,fr.code) param_1,
      NULL param_2,
      NULL param_3,
      NULL param_4,
      NULL param_5
    FROM
      V_FORMULE_VOLUME_HORAIRE fvh
      LEFT JOIN service_referentiel sr ON sr.id = fvh.service_referentiel_id
      LEFT JOIN fonction_referentiel fr ON fr.id = sr.fonction_id
      LEFT JOIN fonction_referentiel tfr ON tfr.id = tfr.parent_id
    ORDER BY
      ordre
    ';
  END;

END FORMULE_ROUEN;