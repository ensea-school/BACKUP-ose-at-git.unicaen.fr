CREATE OR REPLACE PACKAGE BODY FORMULE_POITIERS AS
  decalageLigne NUMERIC DEFAULT 20;


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



  FUNCTION notInStructs( v VARCHAR2 DEFAULT NULL ) RETURN BOOLEAN IS
  BEGIN
    RETURN COALESCE(v,' ') NOT IN ('KE8','UP10');
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



    -- T=SI($H20="Référentiel";0;($AW20+$AY20)*E20)
    WHEN c = 'T' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        RETURN (cell('AW',l) + cell('AY',l)) * vh.TAUX_FI;
      END IF;



    -- U=SI($H20="Référentiel";0;($AW20+$AY20)*F20)
    WHEN c = 'U' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        RETURN (cell('AW',l) + cell('AY',l)) * vh.TAUX_FA;
      END IF;



    -- V=SI($H20="Référentiel";0;($AW20+$AY20)*G20)
    WHEN c = 'V' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        RETURN (cell('AW',l) + cell('AY',l)) * vh.TAUX_FC;
      END IF;



    -- W=SI($H20="Référentiel";$AX20)
    WHEN c = 'W' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN cell('AX',l);
      ELSE
        RETURN 0;
      END IF;



    -- X=SI($H20="Référentiel";0;$BC20*E20)
    -- X=SI($H20="Référentiel";0;SI(i_type_intervenant_code="E";$BB20;$BC20)*E20)
    -- X=SI($H20="Référentiel";0;$BB20*E20)
    WHEN c = 'X' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        RETURN cell('BB',l) * vh.taux_fi;
      END IF;



    -- Y=SI($H20="Référentiel";0;$BC20*F20)
    -- Y=SI($H20="Référentiel";0;SI(i_type_intervenant_code="E";$BB20;$BC20)*F20)
    -- Y=SI($H20="Référentiel";0;$BB20*F20)
    WHEN c = 'Y' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        RETURN cell('BB',l) * vh.taux_fa;
      END IF;



    -- Z=SI($H20="Référentiel";0;$BC20*G20)
    -- Z=SI($H20="Référentiel";0;SI(i_type_intervenant_code="E";$BB20;$BC20)*G20)
    -- Z=SI($H20="Référentiel";0;$BB20*G20)
    WHEN c = 'Z' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        RETURN cell('BB',l) * vh.taux_fc;
      END IF;



    -- AA=0
    WHEN c = 'AA' AND v >= 1 THEN
      RETURN 0;



    -- AB=SI($H20="Référentiel";$BE20;0)
    -- AB=SI($H20="Référentiel";$BC20;0)
    WHEN c = 'AB' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN cell('BC',l);
      ELSE
        RETURN 0;
      END IF;



    -- AD=SI($H20="ETD";0;SI($H20="Référentiel";2;SI(ET($F20=1;OU($A20="I2000";$A20="I2300"));3;1)))
    WHEN c = 'AD' AND v >= 1 THEN
      IF vh.type_intervention_code = 'ETD' THEN
        RETURN 0;
      ELSE
        --SI($H20="Référentiel";2;SI(ET($F20=1;OU($A20="I2000";$A20="I2300"));3;1))
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN 2;
        ELSE
          --SI(ET($F20=1;OU($A20="I2000";$A20="I2300"));3;1)
          IF vh.taux_fa = 1 AND vh.STRUCTURE_CODE IN ('I2000','I2300') THEN
            RETURN 3;
          ELSE
            RETURN 1;
          END IF;
        END IF;
      END IF;



    -- AE=SI(OU(ESTERREUR(I20);ESTERREUR(J20));1;I20*K20)
    WHEN c = 'AE' AND v >= 1 THEN
      RETURN vh.TAUX_SERVICE_DU * vh.PONDERATION_SERVICE_DU;



    -- AF=SI(OU(ESTERREUR(I20);ESTERREUR(J20));1;J20*L20)
    WHEN c = 'AF' AND v >= 1 THEN
      RETURN vh.taux_service_compl * vh.ponderation_service_compl;



    -- AG=SI($D20="Oui";$M20*$AE20;0)
    WHEN c = 'AG' AND v >= 1 THEN
      IF vh.service_statutaire THEN
        RETURN vh.heures * cell('AE',l);
      ELSE
        RETURN 0;
      END IF;



    -- AH=SI(ET($AD20>0;$AD20<3;$D20="Oui");$AG20;0)
    WHEN c = 'AH' AND v >= 1 THEN
      IF cell('AD',l) > 0 AND cell('AD',l) < 3 AND vh.service_statutaire THEN
        RETURN cell('AG',l);
      ELSE
        RETURN 0;
      END IF;



    -- AI=SI(ET($AD20=2;$D20="Oui");$AG20;0)
    WHEN c = 'AI' AND v >= 1 THEN
      IF cell('AD',l) = 2 AND vh.service_statutaire THEN
        RETURN cell('AG',l);
      ELSE
        RETURN 0;
      END IF;



    -- AJ=SI(AI$35<$AJ$9;AI20;SI(AI$35=0;0;AI20/AI$35*$AJ$9))
    -- AJ=SI(AI$35<i_param_1;AI20;AI20/AI$35*i_param_1)
    -- AJ=SI(OU(i_param_1=0;AI$35<i_param_1);AI20;AI20/AI$35*i_param_1)
    WHEN c = 'AJ' AND v >= 1 THEN
      IF i.param_1 = 0 OR cell('AI35') < i.param_1 THEN
        RETURN cell('AI',l);
      ELSE
        RETURN cell('AI',l) / cell('AI35') * i.param_1;
      END IF;



    -- AK=SI(AJ20>0;AJ20;AH20)
    WHEN c = 'AK' AND v >= 1 THEN
      IF cell('AJ',l) > 0 THEN
        RETURN cell('AJ',l);
      ELSE
        RETURN cell('AH',l);
      END IF;



    -- AL=SI(AK$35>0;AK20/AK$35;0)
    WHEN c = 'AL' AND v >= 1 THEN
      IF cell('AK35') > 0 THEN
        RETURN cell('AK',l) / cell('AK35');
      ELSE
        RETURN 0;
      END IF;



    -- AM=SI($AD20=1;AK20;0)
    WHEN c = 'AM' AND v >= 1 THEN
      IF cell('AD',l) = 1 THEN
        RETURN cell('AK',l);
      ELSE
        RETURN 0;
      END IF;



    -- AN=SI(AM$35>0;AM20/AM$35;0)
    WHEN c = 'AN' AND v >= 1 THEN
      IF cell('AM35') > 0 THEN
        RETURN cell('AM',l) / cell('AM35');
      ELSE
        RETURN 0;
      END IF;



    -- AO=SI($AD20=2;AL20;0)
    WHEN c = 'AO' AND v >= 1 THEN
      IF cell('AD',l) = 2 THEN
        RETURN cell('AL',l);
      ELSE
        RETURN 0;
      END IF;



    -- AP=SI((AJ$35+AM$35)<=i_service_du;AM20;AN20*(i_service_du-AJ$35))
    WHEN c = 'AP' AND v >= 1 THEN
      IF (cell('AJ35') + cell('AM35')) <= i.service_du THEN
        RETURN cell('AM',l);
      ELSE
        RETURN cell('AN',l) * (i.SERVICE_DU - cell('AJ35'));
      END IF;



    -- AQ=SI($AD20=2;AK20;0)
    WHEN c = 'AQ' AND v >= 1 THEN
      IF cell('AD',l) = 2 THEN
        RETURN cell('AK',l);
      ELSE
        RETURN 0;
      END IF;



    -- AR=AP20+AQ20
    WHEN c = 'AR' AND v >= 1 THEN
      RETURN cell('AP',l) + cell('AQ',l);



    -- AS=SI($AD20=3;AG20;0)
    WHEN c = 'AS' AND v >= 1 THEN
      IF cell('AD',l) = 3 THEN
        RETURN cell('AG',l);
      ELSE
        RETURN 0;
      END IF;



    -- AT=SI(OU((AM$35+AJ$35)>=i_service_du;AS$35=0);0;AS20/AS$35*MIN((i_service_du-AM$35-AJ$35);AS$35))
    WHEN c = 'AT' AND v >= 1 THEN
      --OU((AM$35+AJ$35)>=i_service_du;AS$35=0)
      IF (cell('AM35') + cell('AJ35')) >= i.service_du OR cell('AS35') = 0 THEN
        RETURN 0;
      ELSE
        --AS20/AS$35*MIN((i_service_du-AM$35-AJ$35);AS$35)
        RETURN cell('AS',l) / cell('AS35') * least(i.SERVICE_DU - cell('AM35') - cell('AJ35'), cell('AS35'));
      END IF;



    -- AU=SI(AM$35>0;AM20/AM$35)
    WHEN c = 'AU' AND v >= 1 THEN
      RETURN cell('AM',l) / cell('AM35');



    -- AW=AP20
    WHEN c = 'AW' AND v >= 1 THEN
      RETURN cell('AP',l);



    -- AX=AJ20
    WHEN c = 'AX' AND v >= 1 THEN
      RETURN cell('AJ',l);



    -- AY=AT20
    WHEN c = 'AY' AND v >= 1 THEN
      RETURN cell('AT',l);



    -- AZ=SI(AG20=0;0;(AR20+AT20)/AG20)
    WHEN c = 'AZ' AND v >= 1 THEN
      IF cell('AG',l) = 0 THEN
        RETURN 0;
      ELSE
        RETURN (cell('AR',l) + cell('AT',l)) / cell('AG',l);
      END IF;



    -- BA=SI(AG$35>i_service_du;1-AZ20;0)
    WHEN c = 'BA' AND v >= 1 THEN
      IF cell('AG35') > i.service_du THEN
        RETURN 1 - cell('AZ',l);
      ELSE
        RETURN 0;
      END IF;



    -- BB=SI(i_depassement_service_du_sans_hc="Non";SI($AD20=2;0;$M20*$AF20*$BA20);0)
    WHEN c = 'BB' AND v >= 1 THEN
      IF NOT i.DEPASSEMENT_SERVICE_DU_SANS_HC THEN
        --SI($AD20=2;0;$M20*$AF20*$BA20)
        IF cell('AD',l) = 2 THEN
          RETURN 0;
        ELSE
          RETURN vh.heures * cell('AF',l) * cell('BA',l);
        END IF;
      ELSE
        RETURN 0;
      END IF;



    -- BC=SI(BB$35>0;BB20/BB$35*MIN(BB$35;$AJ$11);0)
    -- BC=SI(i_depassement_service_du_sans_hc="Non";SI($AD20<>2;0;$M20*$AF20*$BA20);0)
    WHEN c = 'BC' AND v >= 1 THEN
      IF NOT i.DEPASSEMENT_SERVICE_DU_SANS_HC THEN
        --SI($AD20<>2;0;$M20*$AF20*$BA20)
        IF cell('AD',l) <> 2 THEN
          RETURN 0;
        ELSE
          RETURN vh.heures * cell('AF',l) * cell('BA',l);
        END IF;
      ELSE
        RETURN 0;
      END IF;



    -- AG35=SOMME(AG20:AG34)
    WHEN c = 'AG35' AND v >= 1 THEN
      RETURN calcFnc('total', 'AG');



    -- AH35=SOMME(AH20:AH34)
    WHEN c = 'AH35' AND v >= 1 THEN
      RETURN calcFnc('total', 'AH');



    -- AI35=SOMME(AI20:AI34)
    WHEN c = 'AI35' AND v >= 1 THEN
      RETURN calcFnc('total', 'AI');



    -- AJ35=SOMME(AJ20:AJ34)
    WHEN c = 'AJ35' AND v >= 1 THEN
      RETURN calcFnc('total', 'AJ');



    -- AK35=SOMME(AK20:AK34)
    WHEN c = 'AK35' AND v >= 1 THEN
      RETURN calcFnc('total', 'AK');



    -- AM35=SOMME(AM20:AM34)
    WHEN c = 'AM35' AND v >= 1 THEN
      RETURN calcFnc('total', 'AM');



    -- AP35=SOMME(AP20:AP34)
    WHEN c = 'AP35' AND v >= 1 THEN
      RETURN calcFnc('total', 'AP');



    -- AQ35=SOMME(AQ20:AQ34)
    WHEN c = 'AQ35' AND v >= 1 THEN
      RETURN calcFnc('total', 'AQ');



    -- AR35=SOMME(AR20:AR34)
    WHEN c = 'AR35' AND v >= 1 THEN
      RETURN calcFnc('total', 'AR');



    -- AS35=SOMME(AS20:AS34)
    WHEN c = 'AS35' AND v >= 1 THEN
      RETURN calcFnc('total', 'AS');



    -- AT35=SOMME(AT20:AT34)
    WHEN c = 'AT35' AND v >= 1 THEN
      RETURN calcFnc('total', 'AT');



    -- AW35=SOMME(AW20:AW34)
    WHEN c = 'AW35' AND v >= 1 THEN
      RETURN calcFnc('total', 'AW');



    -- AX35=SOMME(AX20:AX34)
    WHEN c = 'AX35' AND v >= 1 THEN
      RETURN calcFnc('total', 'AX');



    -- AY35=SOMME(AY20:AY34)
    WHEN c = 'AY35' AND v >= 1 THEN
      RETURN calcFnc('total', 'AY');



    -- BB35=SOMME(BB20:BB34)
    WHEN c = 'BB35' AND v >= 1 THEN
      RETURN calcFnc('total', 'BB');



    -- BC35=SOMME(BC20:BC34)
    WHEN c = 'BC35' AND v >= 1 THEN
      RETURN calcFnc('total', 'BC');



    -- AW37=AW35
    WHEN c = 'AW37' AND v >= 1 THEN
      RETURN cell('AW35');



    -- AX37=AW37+AX35
    WHEN c = 'AX37' AND v >= 1 THEN
      RETURN cell('AW37') + cell('AX35');



    -- AY37=AX37+AY35
    WHEN c = 'AY37' AND v >= 1 THEN
      RETURN cell('AX37') + cell('AY35');



    -- BC37=BB35+BC35
    WHEN c = 'BC37' AND v >= 1 THEN
      RETURN cell('BB35') + cell('BC35');





    ELSE
      OSE_TEST.echo(c);
      raise_application_error( -20001, 'La colonne c=' || c || ', l=' || l || ' n''existe pas!');
  END CASE; END;



  PROCEDURE CALCUL_RESULTAT IS
  BEGIN
    feuille.delete;

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
      si.plafond_referentiel_service param_1,
      si.plafond_referentiel_hc      param_2,
      si.plafond_hc_hors_remu_fc     param_3,
      NULL param_4,
      NULL param_5
    FROM
      v_formule_intervenant fi
      JOIN intervenant i ON i.id = fi.intervenant_id
      JOIN statut si ON si.id = i.statut_id
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
      ordre';
  END;

END FORMULE_POITIERS;