CREATE OR REPLACE PACKAGE BODY FORMULE_UVSQ AS
      decalageLigne NUMERIC DEFAULT 0;


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


      PROCEDURE dbg( val CLOB ) IS
      BEGIN
        ose_formule.volumes_horaires.items(debugLine).debug_info :=
          ose_formule.volumes_horaires.items(debugLine).debug_info || val;
      END;


      PROCEDURE dbgi( val CLOB ) IS
      BEGIN
        ose_formule.intervenant.debug_info := ose_formule.intervenant.debug_info || val;
      END;

      PROCEDURE dbgDump( val CLOB ) IS
      BEGIN
        dbg('<div class="dbg-dump">' || val || '</div>');
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

        WHEN fncName = 'total' THEN
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


      FUNCTION calcVersion RETURN NUMERIC IS
      BEGIN
        RETURN 1;
      END;



      FUNCTION calcCell( c VARCHAR2, l NUMERIC ) RETURN FLOAT IS
        vh ose_formule.t_volume_horaire;
        i  ose_formule.t_intervenant;
        v NUMERIC;
        val FLOAT;
      BEGIN
        v := calcVersion;

        i := ose_formule.intervenant;
        IF l > 0 THEN
          vh := ose_formule.volumes_horaires.items(l);
        END IF;
        CASE



        -- AI15=SOMME(AH:AH)
        WHEN c = 'AI15' AND v >= 1 THEN
          RETURN calcFnc('total', 'AH');



        -- AO15=SOMME(AN:AN)
        WHEN c = 'AO15' AND v >= 1 THEN
          RETURN calcFnc('total', 'AN');



        -- AU15=SOMME(AT:AT)
        WHEN c = 'AU15' AND v >= 1 THEN
          RETURN calcFnc('total', 'AT');



        -- AI16=MIN(AI15;i_service_du)
        WHEN c = 'AI16' AND v >= 1 THEN
          RETURN LEAST(cell('AI15'), i.service_du);



        -- AO16=MIN(AO15;AI17)
        WHEN c = 'AO16' AND v >= 1 THEN
          RETURN LEAST(cell('AO15'), cell('AI17'));



        -- AU16=MIN(AU15;AO17)
        WHEN c = 'AU16' AND v >= 1 THEN
          RETURN LEAST(cell('AU15'), cell('AO17'));



        -- AI17=i_service_du-AI16
        WHEN c = 'AI17' AND v >= 1 THEN
          RETURN i.service_du - cell('AI16');



        -- AO17=AI17-AO16
        WHEN c = 'AO17' AND v >= 1 THEN
          RETURN cell('AI17') - cell('AO16');



        -- AU17=AO17-AU16
        WHEN c = 'AU17' AND v >= 1 THEN
          RETURN cell('AO17') - cell('AU16');



        -- U=SI($I20="Référentiel";0;($AJ20+$AV20)*F20)
        WHEN c = 'U' AND v >= 1 THEN
          IF vh.volume_horaire_ref_id IS NOT NULL THEN
            RETURN 0;
          ELSE
            RETURN (cell('AJ',l) + cell('AV',l)) * vh.taux_fi;
          END IF;



        -- V=SI($I20="Référentiel";0;($AJ20+$AV20)*G20)
        WHEN c = 'V' AND v >= 1 THEN
          IF vh.volume_horaire_ref_id IS NOT NULL THEN
            RETURN 0;
          ELSE
            RETURN (cell('AJ',l) + cell('AV',l)) * vh.taux_fa;
          END IF;



        -- W=SI($I20="Référentiel";0;($AJ20+$AV20)*H20)
        WHEN c = 'W' AND v >= 1 THEN
          IF vh.volume_horaire_ref_id IS NOT NULL THEN
            RETURN 0;
          ELSE
            RETURN (cell('AJ',l) + cell('AV',l)) * vh.taux_fc;
          END IF;



        -- X=SI($I20="Référentiel";$AP20;0)
        WHEN c = 'X' AND v >= 1 THEN
          IF vh.volume_horaire_ref_id IS NOT NULL THEN
            RETURN cell('AP',l);
          ELSE
            RETURN 0;
          END IF;



        -- Y=SI($I20="Référentiel";0;($AL20+$AX20)*F20)
        WHEN c = 'Y' AND v >= 1 THEN
          IF vh.volume_horaire_ref_id IS NOT NULL THEN
            RETURN 0;
          ELSE
            RETURN (cell('AL',l) + cell('AX',l)) * vh.taux_fi;
          END IF;



        -- Z=SI($I20="Référentiel";0;($AL20+$AX20)*G20)
        WHEN c = 'Z' AND v >= 1 THEN
          IF vh.volume_horaire_ref_id IS NOT NULL THEN
            RETURN 0;
          ELSE
            RETURN (cell('AL',l) + cell('AX',l)) * vh.taux_fa;
          END IF;



        -- AA=SI($I20="Référentiel";0;($AL20+$AX20)*H20)
        WHEN c = 'AA' AND v >= 1 THEN
          IF vh.volume_horaire_ref_id IS NOT NULL THEN
            RETURN 0;
          ELSE
            RETURN (cell('AL',l) + cell('AX',l)) * vh.taux_fc;
          END IF;



        -- AB=0
        WHEN c = 'AB' AND v >= 1 THEN
          RETURN 0;



        -- AC=SI($I20="Référentiel";$AR20;0)
        WHEN c = 'AC' AND v >= 1 THEN
          IF vh.volume_horaire_ref_id IS NOT NULL THEN
            RETURN cell('AR',l);
          ELSE
            RETURN 0;
          END IF;



        -- AE=SI(ESTERREUR(J20);1;J20)
        WHEN c = 'AE' AND v >= 1 THEN
          RETURN vh.taux_service_du;



        -- AF=SI(ESTERREUR(K20);1;K20)
        WHEN c = 'AF' AND v >= 1 THEN
          RETURN vh.taux_service_compl;



        -- AH=SI(ET($E20="Oui";$D20<>"Oui";$I20<>"Référentiel");$N20*$AE20;0)
        WHEN c = 'AH' AND v >= 1 THEN
          IF vh.service_statutaire AND NOT vh.structure_is_exterieur AND vh.volume_horaire_ref_id IS NULL THEN
            RETURN vh.heures * cell('AE',l);
          ELSE
            RETURN 0;
          END IF;



        -- AI=SI(AI$15>0;AH20/AI$15;0)
        WHEN c = 'AI' AND v >= 1 THEN
          IF cell('AI15') > 0 THEN
            RETURN cell('AH',l) / cell('AI15');
          ELSE
            RETURN 0;
          END IF;



        -- AJ=AI$16*AI20
        WHEN c = 'AJ' AND v >= 1 THEN
          RETURN cell('AI16') * cell('AI',l);



        -- AK=SI(AI$17=0;(AH20-AJ20)/$AE20;0)
        WHEN c = 'AK' AND v >= 1 THEN
          IF cell('AI17') = 0 THEN
            RETURN (cell('AH',l) - cell('AJ',l)) / cell('AE',l);
          ELSE
            RETURN 0;
          END IF;



        -- AL=SI(i_depassement_service_du_sans_hc="Non";AK20*$AF20;0)
        WHEN c = 'AL' AND v >= 1 THEN
          IF NOT i.depassement_service_du_sans_hc THEN
            RETURN cell('AK',l) * cell('AF',l);
          ELSE
            RETURN 0;
          END IF;



        -- AN=SI(ET($E20="Oui";$I20="Référentiel");$N20*$AE20;0)
        WHEN c = 'AN' AND v >= 1 THEN
          IF vh.service_statutaire AND vh.volume_horaire_ref_id IS NOT NULL THEN
            RETURN vh.heures * cell('AE',l);
          ELSE
            RETURN 0;
          END IF;



        -- AO=SI(AO$15>0;AN20/AO$15;0)
        WHEN c = 'AO' AND v >= 1 THEN
          IF cell('AO15') > 0 THEN
            RETURN cell('AN',l) / cell('AO15');
          ELSE
            RETURN 0;
          END IF;



        -- AP=AO$16*AO20
        WHEN c = 'AP' AND v >= 1 THEN
          RETURN cell('AO16') * cell('AO',l);



        -- AQ=SI(AO$17=0;(AN20-AP20)/$AE20;0)
        WHEN c = 'AQ' AND v >= 1 THEN
          IF cell('AO17') = 0 THEN
            RETURN (cell('AN',l) - cell('AP',l)) / cell('AE',l);
          ELSE
            RETURN 0;
          END IF;



        -- AR=SI(i_depassement_service_du_sans_hc="Non";AQ20*$AF20;0)
        WHEN c = 'AR' AND v >= 1 THEN
          IF NOT i.depassement_service_du_sans_hc THEN
            RETURN cell('AQ',l) * cell('AF',l);
          ELSE
            RETURN 0;
          END IF;



        -- AT=SI(ET($E20="Oui";$D20="Oui";$I20<>"Référentiel");$N20*$AE20;0)
        WHEN c = 'AT' AND v >= 1 THEN
          IF vh.service_statutaire AND vh.structure_is_exterieur AND vh.volume_horaire_ref_id IS NULL THEN
            RETURN vh.heures * cell('AE',l);
          ELSE
            RETURN 0;
          END IF;



        -- AU=SI(AU$15>0;AT20/AU$15;0)
        WHEN c = 'AU' AND v >= 1 THEN
          IF cell('AU15') > 0 THEN
            RETURN cell('AT',l) / cell('AU15');
          ELSE
            RETURN 0;
          END IF;



        -- AV=AU20*AU$16
        WHEN c = 'AV' AND v >= 1 THEN
          RETURN cell('AU',l) * cell('AU16');



        -- AW=SI(AU$17=0;(AT20-AV20)/$AE20;0)
        WHEN c = 'AW' AND v >= 1 THEN
          IF cell('AU17') = 0 THEN
            RETURN (cell('AT',l) - cell('AV',l)) / cell('AE',l);
          ELSE
            RETURN 0;
          END IF;



        -- AX=SI(i_depassement_service_du_sans_hc="Non";AW20*$AF20;0)
        WHEN c = 'AX' AND v >= 1 THEN
          IF NOT i.depassement_service_du_sans_hc THEN
            RETURN cell('AW',l) * cell('AF',l);
          ELSE
            RETURN 0;
          END IF;






        ELSE
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
          ordre';
      END;

    END FORMULE_UVSQ;