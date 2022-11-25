CREATE OR REPLACE PACKAGE BODY FORMULE_ULHN AS
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

        IF feuille.EXISTS(c) THEN
          IF feuille(c).cells.EXISTS(l) THEN
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
        IF feuille.EXISTS('__' || fncName || '__' || c || '__') THEN
          IF feuille('__' || fncName || '__' || c || '__').cells.EXISTS(1) THEN
            RETURN feuille('__' || fncName || '__' || c || '__').cells(1).valeur;
          END IF;
        END IF;
        CASE
        -- Liste des fonctions supportées

        WHEN fncName = 'somme' THEN
          val := 0;
          FOR l IN 1 .. ose_formule.volumes_horaires.LENGTH LOOP
            val := val + COALESCE(cell(c, l),0);
          END LOOP;

        WHEN fncName = 'max' THEN
          val := NULL;
          FOR l IN 1 .. ose_formule.volumes_horaires.LENGTH LOOP
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



      -- T=IF(OR(ISBLANK([.$H20]);[.$H20]="Référentiel");0;IF(i_type_volume_horaire_code="REALISE";[.$AS20]*[.E20];[.$AM20]*[.E20]))
      WHEN 'T' THEN
        IF vh.type_intervention_code IS NULL OR vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          IF vh.type_volume_horaire_code = 'REALISE' THEN
            RETURN cell('AS',l) * vh.taux_fi;
          ELSE
            RETURN cell('AM',l) * vh.taux_fi;
          END IF;
        END IF;



      -- U=IF(OR(ISBLANK([.$H20]);[.$H20]="Référentiel");0;IF(i_type_volume_horaire_code="REALISE";[.$AS20]*[.F20];[.$AM20]*[.F20]))
      WHEN 'U' THEN
        IF vh.type_intervention_code IS NULL OR vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          IF vh.type_volume_horaire_code = 'REALISE' THEN
            RETURN cell('AS',l) * vh.taux_fa;
          ELSE
            RETURN cell('AM',l) * vh.taux_fa;
          END IF;
        END IF;



      -- V=IF(OR(ISBLANK([.$H20]);[.$H20]="Référentiel");0;IF(i_type_volume_horaire_code="REALISE";[.$AS20]*[.G20];[.$AM20]*[.G20]))
      WHEN 'V' THEN
        IF vh.type_intervention_code IS NULL OR vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          IF vh.type_volume_horaire_code = 'REALISE' THEN
            RETURN cell('AS',l) * vh.taux_fc;
          ELSE
            RETURN cell('AM',l) * vh.taux_fc;
          END IF;
        END IF;



      -- W=IF(OR(ISBLANK([.$H20]);[.$H20]<>"Référentiel");0;IF(i_type_volume_horaire_code="REALISE";[.$AS20];[.$AM20]))
      -- !!!!! modifié manuellement : supression du is_blank inutile !!!!!
      WHEN 'W' THEN
        IF vh.volume_horaire_ref_id IS NULL THEN
          RETURN 0;
        ELSE
          IF vh.type_volume_horaire_code = 'REALISE' THEN
            RETURN cell('AS',l);
          ELSE
            RETURN cell('AM',l);
          END IF;
        END IF;



      -- X=IF(OR(ISBLANK([.$H20]);[.$H20]="Référentiel");0;IF(i_type_volume_horaire_code="REALISE";[.$AV20]*[.E20];[.$AO20]*[.E20]))
      WHEN 'X' THEN
        IF vh.type_intervention_code IS NULL OR vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          IF vh.type_volume_horaire_code = 'REALISE' THEN
            RETURN cell('AV',l) * vh.taux_fi;
          ELSE
            RETURN cell('AO',l) * vh.taux_fi;
          END IF;
        END IF;



      -- Y=IF(OR(ISBLANK([.$H20]);[.$H20]="Référentiel");0;IF(i_type_volume_horaire_code="REALISE";[.$AV20]*[.F20];[.$AO20]*[.F20]))
      WHEN 'Y' THEN
        IF vh.type_intervention_code IS NULL OR vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          IF vh.type_volume_horaire_code = 'REALISE' THEN
            RETURN cell('AV',l) * vh.taux_fa;
          ELSE
            RETURN cell('AO',l) * vh.taux_fa;
          END IF;
        END IF;



      -- Z=IF(OR(ISBLANK([.$H20]);[.$H20]="Référentiel");0;IF(i_type_volume_horaire_code="REALISE";[.$AV20]*[.G20];[.$AO20]*[.G20]))
      WHEN 'Z' THEN
        IF vh.type_intervention_code IS NULL OR vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          IF vh.type_volume_horaire_code = 'REALISE' THEN
            RETURN cell('AV',l) * vh.taux_fc;
          ELSE
            RETURN cell('AO',l) * vh.taux_fc;
          END IF;
        END IF;



      -- AA=0
      WHEN 'AA' THEN
        RETURN 0;



      -- AB=IF(OR(ISBLANK([.$H20]);[.$H20]<>"Référentiel");0;IF(i_type_volume_horaire_code="REALISE";[.$AV20];[.$AO20]))
      -- !!!!! modifié manuellement : supression du is_blank inutile !!!!!
      WHEN 'AB' THEN
        IF vh.volume_horaire_ref_id IS NULL THEN
          RETURN 0;
        ELSE
          IF vh.type_volume_horaire_code = 'REALISE' THEN
            RETURN cell('AV',l);
          ELSE
            RETURN cell('AO',l);
          END IF;
        END IF;



      -- AE=IF([.M20]>0;SUMIF([.AD21:.AD$500];[.AD20];[.M21:.$M500])+[.M20];0)
      WHEN 'AE' THEN
        IF vh.heures > 0 THEN
          val := 0;
          FOR sumIfRow IN l + 1 .. ose_formule.volumes_horaires.LENGTH LOOP
            IF ose_formule.volumes_horaires.items(sumIfRow).param_5 = vh.param_5 THEN
              val := val + ose_formule.volumes_horaires.items(sumIfRow).heures;
            END IF;
          END LOOP;
          RETURN val + vh.heures;
        ELSE
          RETURN 0;
        END IF;



      -- AF=IF([.M20]>0;MIN([.M20];[.AE20]);0)
      WHEN 'AF' THEN
        IF vh.heures > 0 THEN
          RETURN LEAST(vh.heures, cell('AE',l));
        ELSE
          RETURN 0;
        END IF;



      -- AH=IF(ISERROR([.I20]);1;[.I20])*[.K20]
      WHEN 'AH' THEN
        RETURN vh.taux_service_du * vh.ponderation_service_du;



      -- AI=IF(ISERROR([.J20]);1;IF(i_depassement_service_du_sans_hc="Oui";0;[.J20]))*[.L20]
      WHEN 'AI' THEN
        IF i.depassement_service_du_sans_hc THEN
          RETURN 0;
        ELSE
          RETURN vh.taux_service_compl * vh.ponderation_service_compl;
        END IF;



      -- AK=IF([.$D20]="Oui";[.$AF20]*[.$AH20];0)
      WHEN 'AK' THEN
        IF vh.service_statutaire THEN
          RETURN cell('AF',l) * cell('AH',l);
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



      -- AN=IF(AND([.AK20]>0;[.AL$17]=0);([.AK20]-[.AM20])/[.$AH20];0)+IF(AND([.AL$17]=0;[.D20]<>"Oui");[.AF20];0)
      WHEN 'AN' THEN
        IF cell('AK',l) > 0 AND cell('AL17') = 0 THEN
          IF cell('AL17') = 0 AND NOT vh.service_statutaire THEN
            RETURN ((cell('AK',l) - cell('AM',l)) / cell('AH',l)) + cell('AF',l);
          ELSE
            RETURN ((cell('AK',l) - cell('AM',l)) / cell('AH',l));
          END IF;
        ELSE
          IF cell('AL17') = 0 AND NOT vh.service_statutaire THEN
            RETURN cell('AF',l);
          ELSE
            RETURN 0;
          END IF;
        END IF;



      -- AO=IF(i_depassement_service_du_sans_hc="Non";[.AN20]*[.$AI20];0)
      WHEN 'AO' THEN
        IF NOT i.depassement_service_du_sans_hc THEN
          RETURN cell('AN',l) * cell('AI',l);
        ELSE
          RETURN 0;
        END IF;



      -- AQ=IF([.$D20]="Oui";[.$AF20]*[.$AH20];0)
      WHEN 'AQ' THEN
        IF vh.service_statutaire THEN
          RETURN cell('AF',l) * cell('AH',l);
        ELSE
          RETURN 0;
        END IF;



      -- AR=IF([.AQ20]+[.AR19]>i_service_du;i_service_du;[.AQ20]+[.AR19])
      WHEN 'AR' THEN
        IF cell('AQ',l) + cell('AR',l-1) > i.service_du THEN
          RETURN i.service_du;
        ELSE
          RETURN cell('AQ',l) + cell('AR',l-1);
        END IF;



      -- AS=[.AR20]-[.AR19]
      WHEN 'AS' THEN
        RETURN cell('AR',l) - cell('AR',l-1);



      -- AT=IF([.AQ20]>0;([.AQ20]-[.AS20])/[.AH20];0)
      WHEN 'AT' THEN
        IF cell('AQ',l) > 0 THEN
          RETURN (cell('AQ',l) - cell('AS',l)) / cell('AH',l);
        ELSE
          RETURN 0;
        END IF;



      -- AU=IF([.D20]="Oui";0;[.AF20])
      WHEN 'AU' THEN
        IF vh.service_statutaire THEN
          RETURN 0;
        ELSE
          RETURN cell('AF',l);
        END IF;



      -- AV=([.AT20]+[.AU20])*[.AI20]
      WHEN 'AV' THEN
        RETURN (cell('AT',l) + cell('AU',l)) * cell('AI',l);



      -- AR19
      WHEN 'AR19' THEN
        RETURN 0;




        ELSE
          DBMS_OUTPUT.put_line('La colonne c=' || c || ', l=' || l || ' n''existe pas!');
          raise_application_error( -20001, 'La colonne c=' || c || ', l=' || l || ' n''existe pas!');
      END CASE;
      raise_application_error( -20001, 'La colonne c=' || c || ', l=' || l || ' n''existe pas!');

      END;



      PROCEDURE CALCUL_RESULTAT IS
      BEGIN
        feuille.DELETE;

        IF ose_formule.intervenant.depassement_service_du_sans_hc THEN -- HC traitées comme du service
          ose_formule.intervenant.service_du := ose_formule.intervenant.heures_service_statutaire + ose_formule.intervenant.heures_service_modifie;
        END IF;

        -- pré-traitement spécifique au Havre : à conserver lors de modifications ultérieures de la formule
        FOR l IN 1 .. ose_formule.volumes_horaires.LENGTH LOOP
          -- on est en réel : param_1 n'est pas utile puisqu'on a l'ID du service à dispo
          IF ose_formule.volumes_horaires.items(l).param_1 IS NULL THEN
            ose_formule.volumes_horaires.items(l).param_5 := COALESCE(ose_formule.volumes_horaires.items(l).service_id,0)
                                                   || '-' || COALESCE(ose_formule.volumes_horaires.items(l).service_referentiel_id,0)
                                                   || '-' || COALESCE(ose_formule.volumes_horaires.items(l).type_intervention_code,'0');
          ELSE
            -- en mode test, on doit fournir param1 obligatoirement!
            ose_formule.volumes_horaires.items(l).param_5 := ose_formule.volumes_horaires.items(l).param_1 || '-' || COALESCE(ose_formule.volumes_horaires.items(l).type_intervention_code,'0');
          END IF;
        END LOOP;

        -- transmission des résultats aux volumes horaires et volumes horaires référentiel
        FOR l IN 1 .. ose_formule.volumes_horaires.LENGTH LOOP
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

    END FORMULE_ULHN;