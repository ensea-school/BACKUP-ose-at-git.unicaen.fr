CREATE OR REPLACE PACKAGE BODY FORMULE_ULHN_2021 AS
  decalageLigne NUMERIC DEFAULT 21;


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


    -- j=SI(ESTVIDE(C22);0;RECHERCHEH(SI(ET(C22="TP";TP_vaut_TD="Oui");"TD";C22);types_intervention;2;0))
    --  = RECHERCHEH(SI(ET(C22="TP";TP_vaut_TD="Oui");"TD";C22);types_intervention;2;0)
    WHEN c = 'j' AND v >= 1 THEN
      --RETURN GREATEST(vh.taux_service_du * vh.ponderation_service_du,1);
      RETURN vh.taux_service_du * vh.ponderation_service_du ;

    -- k=SI(ESTVIDE(C22);0;RECHERCHEH(C22;types_intervention;3;0))
    --  =RECHERCHEH(C22;types_intervention;3;0)
    WHEN c = 'k' AND v >= 1 THEN
      RETURN vh.taux_service_compl;

    -- l=SI(H22="Oui";I22*J22;0)
    WHEN c = 'l' AND v >= 1 THEN
      IF vh.service_statutaire THEN
        RETURN vh.heures * cell( 'j', l );
      ELSE
        RETURN 0;
      END IF;


    -- l522=SOMME(L22:L81)
    WHEN c = 'l522' AND v >= 1 THEN
      RETURN calcFnc('total','l');

    -- n=SI($L$82>0;L22/$L$82;0)
    WHEN c = 'n' AND v >= 1 THEN
      IF cell('l522') > 0 THEN
        RETURN cell('l',l) / cell('l522');
      ELSE
        RETURN 0;
      END IF;

    -- o=MIN(service_du;$L$82)*N22
    WHEN c = 'o' AND v >= 1 THEN
      RETURN LEAST(ose_formule.intervenant.service_du, cell('l522')) * cell('n',l);

    -- p=SI(L22<>0;O22/L22;0)
    WHEN c = 'p' AND v >= 1 THEN
      IF cell('l',l) <> 0 THEN
        RETURN cell('o',l) / cell('l',l);
      ELSE
        RETURN 0;
      END IF;

    -- q=SI($L$82>service_du;1-P22;0)
    WHEN c = 'q' AND v >= 1 THEN
      IF cell('l522') > ose_formule.intervenant.service_du THEN
        RETURN 1 - cell('p',l);
      ELSE
        RETURN 0;
      END IF;

    -- r=I22*Q22*K22
    -- Changement : ajout de * vh.ponderation_service_du pour prendre en compte le modulateur
    WHEN c = 'r' AND v >= 1 THEN
      RETURN vh.heures * cell('q',l) * cell('k',l) * vh.ponderation_service_du;



    -- t=SI(service_du=0;0;SI(T21+L22>service_du;service_du;T21+L22))
    WHEN c = 't' AND v >= 1 THEN
      IF l = 0 THEN
        RETURN 0;
      END IF;
      IF i.service_du = 0 THEN
        RETURN 0;
      ELSE
        IF (cell('t',l-1) + cell('l',l)) > ose_formule.intervenant.service_du THEN
          RETURN ose_formule.intervenant.service_du;
        ELSE
          RETURN cell('t',l-1) + cell('l',l);
        END IF;
      END IF;



    -- u=SI(service_du=0;0;SI(T21+L22>service_du;service_du-T21;L22))
    WHEN c = 'u' AND v >= 1 THEN
      IF i.service_du = 0 THEN
        RETURN 0;
      ELSE
        --SI(T21+L22>service_du;service_du-T21;L22)
        IF cell('t',l-1) + cell('l',l) > i.service_du THEN
          RETURN i.service_du - cell('t',l-1);
        ELSE
          RETURN cell('l',l);
        END IF;
      END IF;

    -- v=SI(service_du=0;I22;SI(J22>0;SI(T21+L22<service_du;0;((T21+L22)-service_du)/J22);0))
    WHEN c = 'v' AND v >= 1 THEN
      IF i.service_du = 0 THEN
        RETURN vh.heures;
      ELSE
        --SI(J22>0;SI(T21+L22<service_du;0;((T21+L22)-service_du)/J22);0)
        IF cell('j',l) > 0 THEN
          --SI(T21+L22<service_du;0;((T21+L22)-service_du)/J22)
          IF cell('t',l-1) + cell('l',l) < i.service_du THEN
            RETURN 0;
          ELSE
            --((T21+L22)-service_du)/J22
            RETURN ((cell('t',l-1) + cell('l',l)) - i.service_du) / cell('j',l);
          END IF;
        ELSE
          RETURN 0;
        END IF;
      END IF;

    -- w=SI(OU(service_realise<service_du;HC_autorisees<>"Oui");0;(V22+SI(H22<>"Oui";I22;0))*K22)
    -- Changement : ajout de * vh.ponderation_service_du pour prendre en compte le modulateur
    WHEN c = 'w' AND v >= 1 THEN
      IF cell('service_realise') < ose_formule.intervenant.service_du OR ose_formule.intervenant.depassement_service_du_sans_hc THEN
        RETURN 0;
      ELSE
        --(V22+SI(H22<>"Oui";I22;0))*K22
        RETURN (cell('v',l) + CASE WHEN NOT vh.service_statutaire THEN vh.heures ELSE 0 END ) * cell('k',l) * vh.ponderation_service_du;
      END IF;

    -- y=SI(OU(ESTVIDE($C22);$C22="Référentiel");0;SI(contexte_calcul="Réalisé";$U22*D22;$O22*D22))
    WHEN c = 'y' AND v >= 1 THEN
      IF vh.service_referentiel_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        IF ose_formule.intervenant.type_volume_horaire_id = 2 THEN
          RETURN cell('u',l) * vh.taux_fi;
        ELSE
          RETURN cell('o',l) * vh.taux_fi;
        END IF;
      END IF;

    -- z=SI(OU(ESTVIDE($C22);$C22="Référentiel");0;SI(contexte_calcul="Réalisé";$U22*E22;$O22*E22))
    WHEN c = 'z' AND v >= 1 THEN
      IF vh.service_referentiel_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        IF ose_formule.intervenant.type_volume_horaire_id = 2 THEN
          RETURN cell('u',l) * vh.taux_fa;
        ELSE
          RETURN cell('o',l) * vh.taux_fa;
        END IF;
      END IF;

    -- aa=SI(OU(ESTVIDE($C22);$C22="Référentiel");0;SI(contexte_calcul="Réalisé";$U22*F22;$O22*F22))
    WHEN c = 'aa' AND v >= 1 THEN
      IF vh.service_referentiel_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        IF ose_formule.intervenant.type_volume_horaire_id = 2 THEN
          RETURN cell('u',l) * vh.taux_fc;
        ELSE
          RETURN cell('o',l) * vh.taux_fc;
        END IF;
      END IF;

    -- ab=SI($C22="Référentiel";SI(contexte_calcul="Réalisé";$U22;$R22);0)
    WHEN c = 'ab' AND v >= 1 THEN
      IF vh.service_referentiel_id IS NOT NULL THEN
        IF ose_formule.intervenant.type_volume_horaire_id = 2 THEN
          RETURN cell('u',l);
        ELSE
          RETURN cell('r',l);
        END IF;
      ELSE
        RETURN 0;
      END IF;

    -- ac=SI(OU(ESTVIDE($C22);$C22="Référentiel");0;SI(contexte_calcul="Réalisé";$V22*D22;$R22*D22))
    WHEN c = 'ac' AND v >= 1 THEN
      IF vh.service_referentiel_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        IF ose_formule.intervenant.type_volume_horaire_id = 2 THEN
          RETURN cell('w',l) * vh.taux_fi;
        ELSE
          RETURN cell('r',l) * vh.taux_fi;
        END IF;
      END IF;

    -- ad=SI(OU(ESTVIDE($C22);$C22="Référentiel");0;SI(contexte_calcul="Réalisé";$V22*E22;$R22*E22))
    WHEN c = 'ad' AND v >= 1 THEN
      IF vh.service_referentiel_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        IF ose_formule.intervenant.type_volume_horaire_id = 2 THEN
          RETURN cell('w',l) * vh.taux_fa;
        ELSE
          RETURN cell('r',l) * vh.taux_fa;
        END IF;
      END IF;

    -- ae=SI(OU(ESTVIDE($C22);$C22="Référentiel");0;SI(contexte_calcul="Réalisé";$V22*F22;$R22*F22))
    WHEN c = 'ae' AND v >= 1 THEN
      IF vh.service_referentiel_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        IF ose_formule.intervenant.type_volume_horaire_id = 2 THEN
          RETURN cell('w',l) * vh.taux_fc;
        ELSE
          RETURN cell('r',l) * vh.taux_fc;
        END IF;
      END IF;

    -- af=0
    WHEN c = 'af' AND v >= 1 THEN
      RETURN 0;

    -- ag=SI($C22="Référentiel";SI(contexte_calcul="Réalisé";$V22;$R22);0)
    WHEN c = 'ag' AND v >= 1 THEN
      IF vh.service_referentiel_id IS NOT NULL THEN
        IF ose_formule.intervenant.type_volume_horaire_id = 2 THEN
          RETURN cell('w',l);
        ELSE
          RETURN cell('r',l);
        END IF;
      ELSE
        RETURN 0;
      END IF;

    -- d17=SI(contexte_calcul="Réalisé";MAX($T$22:$T$81);SOMME($O$22:$O$81))
    WHEN c='service_realise' AND v >= 1 THEN
      IF ose_formule.intervenant.type_volume_horaire_id = 2 THEN
          RETURN calcFnc('max','t');
        ELSE
          RETURN calcFnc('total','o');
        END IF;

    ELSE
      raise_application_error( -20001, 'La colonne c=' || c || ', l=' || l || ' n''existe pas!');
  END CASE; END;



  PROCEDURE CALCUL_RESULTAT IS
  BEGIN
    feuille.delete;

    -- transmission des résultats aux volumes horaires et volumes horaires référentiel
    FOR l IN 1 .. ose_formule.volumes_horaires.length LOOP
      ose_formule.volumes_horaires.items(l).service_fi               := mainCell('Service FI', 'y',l);
      ose_formule.volumes_horaires.items(l).service_fa               := mainCell('Service FA', 'z',l);
      ose_formule.volumes_horaires.items(l).service_fc               := mainCell('Service FC', 'aa',l);
      ose_formule.volumes_horaires.items(l).service_referentiel      := mainCell('Service référentiel', 'ab',l);
      ose_formule.volumes_horaires.items(l).heures_compl_fi          := mainCell('Heures compl. FI', 'ac',l);
      ose_formule.volumes_horaires.items(l).heures_compl_fa          := mainCell('Heures compl. FA', 'ad',l);
      ose_formule.volumes_horaires.items(l).heures_compl_fc          := mainCell('Heures compl. FC', 'ae',l);
      ose_formule.volumes_horaires.items(l).heures_primes            := mainCell('Heures compl. FC Maj.', 'af',l);
      ose_formule.volumes_horaires.items(l).heures_compl_referentiel := mainCell('Heures compl. référentiel', 'ag',l);
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

END FORMULE_ULHN_2021;