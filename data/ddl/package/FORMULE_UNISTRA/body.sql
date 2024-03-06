CREATE OR REPLACE PACKAGE BODY FORMULE_UNISTRA AS
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



    -- service_realise =SOMME($O$21:$O$40)
    WHEN c = 'service_realise' AND v >= 1 THEN
      RETURN calcFnc('total', 'o');



    -- hc =SOMME($R$21:$R$40)
    WHEN c = 'hc' AND v >= 1 THEN
      RETURN calcFnc('total', 'r');



    -- j =SI(ESTVIDE(C21);0;RECHERCHEH(SI(ET(C21="TP";TP_vaut_TD="Oui");"TD";C21);types_intervention;2;0))
    -- j =RECHERCHEH(SI(ET(C21="TP";TP_vaut_TD="Oui");"TD";C21);types_intervention;2;0)
    WHEN c = 'j' AND v >= 1 THEN
      RETURN vh.taux_service_du;



    -- k =SI(ESTVIDE(C21);0;RECHERCHEH(C21;types_intervention;3;0))
    WHEN c = 'k' AND v >= 1 THEN
      RETURN vh.taux_service_compl;



    -- l =SI(H21="Oui";I21*J21;0)
    WHEN c = 'l' AND v >= 1 THEN
      IF vh.service_statutaire THEN
        RETURN vh.heures * cell('j',l);
      ELSE
        RETURN 0;
      END IF;



    -- n =SI($L$41>0;L21/$L$41;0)
    WHEN c = 'n' AND v >= 1 THEN
      IF calcFnc('total', 'l') > 0 THEN
        RETURN cell('l',l) / calcFnc('total', 'l');
      ELSE
        RETURN 0;
      END IF;



    -- o =MIN(service_du;$L$41)*N21
    WHEN c = 'o' AND v >= 1 THEN
      RETURN LEAST(i.service_du, calcFnc('total', 'l')) * cell('n',l);



    -- p =SI(L21<>0;O21/L21;0)
    WHEN c = 'p' AND v >= 1 THEN
      IF cell('l',l) <> 0 THEN
        RETURN cell('o',l) / cell('l',l);
      ELSE
        RETURN 0;
      END IF;



    -- q =SI($L$41>service_du;1-P21;0)
    WHEN c = 'q' AND v >= 1 THEN
      IF calcFnc('total', 'l') > i.service_du THEN
        RETURN 1 - cell('p',l);
      ELSE
        RETURN 0;
      END IF;



    -- r =SI(HC_autorisees="Oui";I21*Q21*K21;0)
    WHEN c = 'r' AND v >= 1 THEN
      IF NOT i.depassement_service_du_sans_hc THEN
        RETURN vh.heures * cell('q',l) * cell('k',l);
      ELSE
        RETURN 0;
      END IF;



    -- t =SI(OU(ESTVIDE($C21);$C21="Référentiel");0;$O21*D21)
    WHEN c = 't' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        RETURN cell('o',l) * vh.taux_fi;
      END IF;



    -- u =SI(OU(ESTVIDE($C21);$C21="Référentiel");0;$O21*E21)
    WHEN c = 'u' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        RETURN cell('o',l) * vh.taux_fa;
      END IF;



    -- v =SI(OU(ESTVIDE($C21);$C21="Référentiel");0;$O21*F21)
    WHEN c = 'v' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        RETURN cell('o',l) * vh.taux_fc;
      END IF;



    -- w =SI($C21="Référentiel";$O21;0)
    WHEN c = 'w' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN cell('o',l);
      ELSE
        RETURN 0;
      END IF;



    -- x =SI(OU(ESTVIDE($C21);$C21="Référentiel");0;$R21*D21)
    WHEN c = 'x' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        RETURN cell('r',l) * vh.taux_fi;
      END IF;



    -- y =SI(OU(ESTVIDE($C21);$C21="Référentiel");0;$R21*E21)
    WHEN c = 'y' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        RETURN cell('r',l) * vh.taux_fa;
      END IF;



    -- z =SI(OU(ESTVIDE($C21);$C21="Référentiel");0;$R21*F21)
    WHEN c = 'z' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        RETURN cell('r',l) * vh.taux_fc;
      END IF;



    -- aa =0
    WHEN c = 'aa' AND v >= 1 THEN
      RETURN 0;



    -- ab =SI($C21="Référentiel";$R21;0)
    WHEN c = 'ab' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN cell('r',l);
      ELSE
        RETURN 0;
      END IF;



    ELSE
      raise_application_error( -20001, 'La colonne c=' || c || ', l=' || l || ' n''existe pas!');
  END CASE; END;



  PROCEDURE CALCUL_RESULTAT IS
  BEGIN
    feuille.delete;

    -- transmission des résultats aux volumes horaires et volumes horaires référentiel
    FOR l IN 1 .. ose_formule.volumes_horaires.length LOOP
      ose_formule.volumes_horaires.items(l).service_fi               := mainCell('Service FI', 't',l);
      ose_formule.volumes_horaires.items(l).service_fa               := mainCell('Service FA', 'u',l);
      ose_formule.volumes_horaires.items(l).service_fc               := mainCell('Service FC', 'v',l);
      ose_formule.volumes_horaires.items(l).service_referentiel      := mainCell('Service référentiel', 'w',l);
      ose_formule.volumes_horaires.items(l).heures_compl_fi          := mainCell('Heures compl. FI', 'x',l);
      ose_formule.volumes_horaires.items(l).heures_compl_fa          := mainCell('Heures compl. FA', 'y',l);
      ose_formule.volumes_horaires.items(l).heures_compl_fc          := mainCell('Heures compl. FC', 'z',l);
      ose_formule.volumes_horaires.items(l).heures_primes            := mainCell('Heures compl. FC Maj.', 'aa',l);
      ose_formule.volumes_horaires.items(l).heures_compl_referentiel := mainCell('Heures compl. référentiel', 'ab',l);
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

END FORMULE_UNISTRA;