CREATE OR REPLACE PACKAGE BODY "FORMULE_MONTPELLIER" AS
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
--UM
    WHEN fncName = 'last' THEN
      val := NULL;
      FOR l IN 1 .. ose_formule.volumes_horaires.length LOOP
        cellRes := cell(c,l);
        val := cellRes;
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


    -- J = SI(ESTVIDE(C21);0;RECHERCHEH(SI(ET(C21="TP";TP_vaut_TD="Oui");"TD";C21);types_intervention;2;0))
    WHEN c = 'j' AND v >= 1 THEN
      RETURN vh.taux_service_du * vh.ponderation_service_du;



    -- K = SI(H21="Oui";I21*J21;0)
    WHEN c = 'k' AND v >= 1 THEN
      IF vh.service_statutaire THEN
        RETURN vh.heures * cell('j',l);
      ELSE
        RETURN 0;
      END IF;



    -- l = SI(OU(L20+K21>service_du;L20=service_du);service_du;L20+K21)
  -- UM l =SI(K21 < 0;L20+K21;SI(OU(L20+K21>service_du;L20=service_du);service_du;L20+K21))
/*
    WHEN c = 'l' AND v >= 1 THEN
      IF l < 1 THEN
        RETURN 0;
      END IF;
      IF (cell('l', l-1) + cell('k',l) > i.service_du) OR (cell('l', l-1) = i.service_du) THEN
        RETURN ose_formule.intervenant.service_du;
      ELSE
        RETURN cell('l', l-1) + cell('k',l);
      END IF;
*/

-- UM

    WHEN c = 'l' AND v >= 1 THEN
      IF l < 1 THEN
        RETURN 0;
      END IF;
    IF cell('k',l) < 0 THEN
    RETURN cell('l', l-1) + cell('k',l);
    ELSE
    IF (cell('l', l-1) + cell('k',l) > i.service_du) OR (cell('l', l-1) = i.service_du) THEN
      RETURN ose_formule.intervenant.service_du;
    ELSE
      RETURN cell('l', l-1) + cell('k',l);
    END IF;
    END IF;

    -- m = SI(OU(ESTVIDE(composante_affectation);L20=service_du);SI(H21<>"Oui";0;I21);SI(J21>0;SI(L20+K21<service_du;0;((L20+K21)-service_du)/J21);0))
    -- composante_affectation vide si vacataire
/*    WHEN c = 'm' AND v >= 1 THEN
      -- OU(ESTVIDE(composante_affectation);L20=service_du)
      IF i.type_intervenant_code = 'E' OR cell('l',l-1) = i.service_du THEN
        -- SI(H21<>"Oui";0;I21);
        IF NOT vh.service_statutaire THEN
          RETURN 0;
        ELSE
          RETURN vh.heures;
        END IF;
      ELSE
        -- SI(J21>0;SI(L20+K21<service_du;0;((L20+K21)-service_du)/J21);0)
        IF cell('j',l) > 0 THEN
          IF cell('l',l-1) + cell('k',l) < ose_formule.intervenant.service_du THEN
            RETURN 0;
          ELSE
            RETURN (cell('l',l-1) + cell('k',l) - ose_formule.intervenant.service_du) / cell('j',l);
          END IF;
        ELSE
          RETURN 0;
        END IF;
      END IF;
*/
-- UM
-- m  =SI(OU(ESTVIDE(composante_affectation);OU(L20=service_du;I21<0));SI(OU(H21<>"Oui";L21<service_du);0;I21);SI(J21>0;SI(L20+K21<service_du;0;((L20+K21)-service_du)/J21);0))
    WHEN c = 'm' AND v >= 1 THEN
      --SI(OU(ESTVIDE(composante_affectation);OU(L20=service_du;I21<0))
      IF i.type_intervenant_code = 'E' OR cell('l',l-1) = i.service_du OR ( cell('l',l-1) = i.service_du AND cell('k',l)<0) THEN
        -- SI(OU(H21<>"Oui";L21<service_du);0;I21);
        IF NOT vh.service_statutaire OR (cell('l',l) < i.service_du AND cell('k',l)<0) THEN
          RETURN 0;
        ELSE
          RETURN vh.heures;
        END IF;
      ELSE
        -- SI(J21>0;SI(L20+K21<service_du;0;((L20+K21)-service_du)/J21);0)
        IF cell('j',l) > 0 THEN
          IF cell('l',l-1) + cell('k',l) < ose_formule.intervenant.service_du THEN
            RETURN 0;
          ELSE
            RETURN (cell('l',l-1) + cell('k',l) - ose_formule.intervenant.service_du) / cell('j',l);
          END IF;
        ELSE
          RETURN 0;
        END IF;
      END IF;



    -- n = SI(ESTVIDE(C21);0;RECHERCHEH(C21;types_intervention;3;0))
    WHEN c = 'n' AND v >= 1 THEN
      RETURN vh.taux_service_compl * vh.ponderation_service_compl;



    -- o = SI(OU(service_realise<service_du;HC_autorisees<>"Oui");0;(M21+SI(H21<>"Oui";I21;0))*N21)
    -- service_realise = MAX($L$21:$L$50)
    -- service_du = ose_formule.intervenant.service_du
    -- HC_autorisees = ose_formule.intervenant.depassement_service_du_sans_hc = false
/*    WHEN c = 'o' AND v >= 1 THEN
      IF (calcFnc('max','l') < ose_formule.intervenant.service_du) OR ose_formule.intervenant.depassement_service_du_sans_hc THEN
        RETURN 0;
      ELSE
        IF vh.service_statutaire THEN
          RETURN cell('m',l) * cell('n',l);
        ELSE
          RETURN (cell('m',l) + vh.heures) * cell('n',l);
        END IF;
      END IF;
*/
-- UM
    WHEN c = 'o' AND v >= 1 THEN
      IF (calcFnc('last','l') < ose_formule.intervenant.service_du) OR ose_formule.intervenant.depassement_service_du_sans_hc THEN
        RETURN 0;
      ELSE
        IF vh.service_statutaire THEN
          RETURN cell('m',l) * cell('n',l);
        ELSE
          RETURN (cell('m',l) + vh.heures) * cell('n',l);
        END IF;
      END IF;


    -- q =SI(ESTVIDE(C21);0;SI(H21="Non";0;SI(C21="TP";1;RECHERCHEH(C21;types_intervention;2;0))))
    -- q =SI(H21="Non";0;SI(C21="TP";1;RECHERCHEH(C21;types_intervention;2;0)))
    WHEN c = 'q' AND v >= 1 THEN
      IF NOT vh.service_statutaire THEN
        RETURN 0;
      ELSE
        -- SI(C21="TP";1;RECHERCHEH(C21;types_intervention;2;0))
        IF vh.type_intervention_code = 'TP' THEN
          RETURN 1;
        ELSE
          RETURN vh.taux_service_du;
        END IF;

      END IF;



    -- r =I21*Q21
    WHEN c = 'r' AND v >= 1 THEN
      RETURN vh.heures * cell('q',l);



    -- r136 =SOMME.SI(B$21:B$50;composante_affectation;R$21:R$50)
    WHEN c = 'r136' AND v >= 1 THEN
      val := 0;
      FOR i IN 1 .. ose_formule.volumes_horaires.length LOOP
        IF ose_formule.volumes_horaires.items(i).structure_is_affectation THEN
          val := val + cell('r',i);
        END IF;
      END LOOP;
      RETURN val;



    -- r137 =SOMME.SI(B$21:B$50;"<>"&composante_affectation;R$21:R$50)
    WHEN c = 'r137' AND v >= 1 THEN
      val := 0;
      FOR i IN 1 .. ose_formule.volumes_horaires.length LOOP
        IF NOT ose_formule.volumes_horaires.items(i).structure_is_affectation THEN
          val := val + cell('r',i);
        END IF;
      END LOOP;
      RETURN val;



    -- s =SI(H21="Non";O21;SI(B21=composante_affectation;SI($R$136=0;0;R21*$T$136);SI($R$137=0;0;R21*$T$137)))
    WHEN c = 's' AND v >= 1 THEN
      IF NOT vh.service_statutaire THEN
        RETURN cell('o', l);
      ELSE
        IF vh.structure_is_affectation THEN
          IF cell('r136') = 0 THEN
            RETURN 0;
          ELSE
            RETURN cell('r', l) * cell('t136');
          END IF;
        ELSE
          IF cell('r137') = 0 THEN
            RETURN 0;
          ELSE
            RETURN cell('r', l) * cell('t137');
          END IF;
        END IF;
      END IF;



    -- s136 =SI(OU(HC=0;R136<service_du);0;SI(pour_les_autres_composantes=0;HC     ;SI((HC_Budg-pour_les_autres_composantes)<(HC_Budg*(R136-service_du)/R132);HC_Budg*(R136-service_du)/R132;HC_Budg-pour_les_autres_composantes)))
    -- s136 =SI(OU(HC=0;R136<service_du);0;SI(pour_les_autres_composantes=0;HC_Budg;SI((HC_Budg-pour_les_autres_composantes)<(HC_Budg*(R136-service_du)/R132);HC_Budg*(R136-service_du)/R132;HC_Budg-pour_les_autres_composantes)))
    -- pour_les_autres_composantes = R137
    WHEN c = 's136' AND v >= 1 THEN
      IF calcFnc('total','o') = 0 OR cell('r136') < ose_formule.intervenant.service_du THEN
        RETURN 0;
      ELSE
        -- SI(R137=0;HC;SI((HC_Budg-R137)<(HC_Budg*(R136-service_du)/R132);HC_Budg*(R136-service_du)/R132;HC_Budg-R137))
        IF cell('r137') = 0 THEN
          RETURN cell('hc_budg');
        ELSE
          -- SI((HC_Budg-R137)<(HC_Budg*(R136-service_du)/R132);HC_Budg*(R136-service_du)/R132;HC_Budg-R137)
          IF (cell('hc_budg')-cell('r137'))<(cell('hc_budg')*(cell('r136')-ose_formule.intervenant.service_du)/calcFnc('total','r')) THEN
            -- HC_Budg*(R136-service_du)/R132
            RETURN cell('hc_budg')*(cell('r136')-ose_formule.intervenant.service_du)/calcFnc('total','r');
          ELSE
            -- HC_Budg-R137
            RETURN cell('hc_budg')-cell('r137');
          END IF;
        END IF;
      END IF;



    -- s137 =SI(R137=0;0;SI(HC=0;0;HC_Budg-S136))
    WHEN c = 's137' AND v >= 1 THEN
      IF cell('r137') = 0 THEN
        RETURN 0;
      ELSE
        IF calcFnc('total','o') = 0 THEN
          RETURN 0;
        ELSE
          RETURN cell('hc_budg') - cell('s136');
        END IF;
      END IF;



    -- s138 =SOMME(S136:S137)
    WHEN c = 's138' AND v >= 1 THEN
      RETURN cell('s136') + cell('s138');



    -- t136 =SI(R136=0;0;S136/R136)
    WHEN c = 't136' AND v >= 1 THEN
      IF cell('r136') = 0 THEN
        RETURN 0;
      ELSE
        RETURN cell('s136') / cell('r136');
      END IF;



    -- t137 =SI(R137=0;0;S137/R137)
    WHEN c = 't137' AND v >= 1 THEN
      IF cell('r137') = 0 THEN
        RETURN 0;
      ELSE
        RETURN cell('s137') / cell('r137');
      END IF;



    -- u =                                     SI(OU(ESTVIDE($C21);$C21="Référentiel";ET(HC=0;H21="Non"));0;SI(H21="Non";O21*$D21;SI($M21>0;(($M21*$N21)+($I21-$M21)*J21)*$D21;$K21*$D21)))
    -- u =SI(ESTVIDE(composante_affectation);0;SI(OU(ESTVIDE($C21);$C21="Référentiel";ET(HC=0;H21="Non"));0;SI(H21="Non";O21*$D21;SI($M21>0;(($M21*$N21)+($I21-$M21)*J21)*$D21;$K21*$D21))))
    WHEN c = 'u' AND v >= 1 THEN
      IF i.type_intervenant_code = 'E' THEN
        RETURN 0;
      ELSE
        -- OU(ESTVIDE($C21);$C21="Référentiel";ET(HC=0;H21="Non"))
        IF vh.volume_horaire_ref_id IS NOT NULL OR (calcFnc('total','o')=0 AND NOT vh.service_statutaire) THEN
          RETURN 0;
        ELSE
          -- SI(H21="Non";O21*$D21;SI($M21>0;(($M21*$N21)+($I21-$M21)*J21)*$D21;$K21*$D21))
          IF NOT vh.service_statutaire THEN
            RETURN cell('o',l) * vh.taux_fi;
          ELSE
            -- SI($M21>0;(($M21*$N21)+($I21-$M21)*J21)*$D21;$K21*$D21)
            IF cell('m',l) > 0 THEN
              -- (($M21*$N21)+($I21-$M21)*J21)*$D21
              RETURN ((cell('m',l)*cell('n',l))+(vh.heures-cell('m',l))*cell('j',l))*vh.taux_fi;
            ELSE
              -- $K21*$D21
              RETURN cell('k',l) * vh.taux_fi;
            END IF;
          END IF;
        END IF;
      END IF;



    -- v =SI(ESTVIDE(composante_affectation);0;SI(OU(ESTVIDE($C21);$C21="Référentiel";ET(HC=0;H21="Non"));0;SI(H21="Non";O21*$E21;SI($M21>0;(($M21*$N21)+($I21-$M21)*K21)*$E21;$K21*$E21))))
      --UM  v =SI(ESTVIDE(composante_affectation);0;SI(OU(ESTVIDE($C21);$C21="Référentiel";ET(HC=0;H21="Non"));0;SI(H21="Non";O21*$E21;SI($M21>0;(($M21*$N21)+($I21-$M21)*J21)*$E21;$K21*$E21))))
    -- HC = calcFnc('total','o')
    -- H21="Non" = NOT vh.service_statutaire
    -- P21 = O21!!
    WHEN c = 'v' AND v >= 1 THEN
      IF i.type_intervenant_code = 'E' THEN
        RETURN 0;
      ELSE
        -- OU(ESTVIDE($C21);$C21="Référentiel";ET(HC=0;H21="Non"))
        IF vh.volume_horaire_ref_id IS NOT NULL OR (calcFnc('total','o')=0 AND NOT vh.service_statutaire) THEN
          RETURN 0;
        ELSE
          -- SI(H21="Non";P21;SI($M21>0;(($M21*$N21)+($I21-$M21)*K21)*$E21;$K21*$E21))
          IF NOT vh.service_statutaire THEN
            RETURN cell('o',l) * vh.taux_fa;
          ELSE
            -- SI($M21>0;(($M21*$N21)+($I21-$M21)*K21)*$E21;$K21*$E21)
            IF cell('m',l) > 0 THEN
              -- (($M21*$N21)+($I21-$M21)*J21)*$E21
              RETURN ((cell('m',l)*cell('n',l))+(vh.heures-cell('m',l))*cell('j',l))*vh.taux_fa;
            ELSE
              -- $K21*$E21
              RETURN cell('k',l) * vh.taux_fa;
            END IF;
          END IF;
        END IF;
      END IF;



    -- w =SI(ESTVIDE(composante_affectation);0;SI(OU(ESTVIDE($C21);$C21="Référentiel";ET(HC=0;H21="Non"));0;SI(H21="Non";O21*$F21;SI($M21>0;(($M21*$N21)+($I21-$M21)*L21)*$F21;$K21*$F21))))
  --UM  w =SI(ESTVIDE(composante_affectation);0;SI(OU(ESTVIDE($C21);$C21="Référentiel";ET(HC=0;H21="Non"));0;SI(H21="Non";O21*$F21;SI($M21>0;(($M21*$N21)+($I21-$M21)*J21)*$F21;$K21*$F21))))
    WHEN c = 'w' AND v >= 1 THEN
      IF i.type_intervenant_code = 'E' THEN
        RETURN 0;
      ELSE
        -- OU(ESTVIDE($C21);$C21="Référentiel";ET(HC=0;H21="Non"))
        IF vh.volume_horaire_ref_id IS NOT NULL OR (calcFnc('total','o')=0 AND NOT vh.service_statutaire) THEN
          RETURN 0;
        ELSE
          --SI(H21="Non";Q21;SI($M21>0;(($M21*$N21)+($I21-$M21)*L21)*$F21;$K21*$F21))
          IF NOT vh.service_statutaire THEN
            RETURN cell('o',l) * vh.taux_fc;
          ELSE
            -- SI($M21>0;(($M21*$N21)+($I21-$M21)*L21)*$F21;$K21*$F21)
            IF cell('m',l) > 0 THEN
              RETURN ((cell('m',l)*cell('n',l))+(vh.heures-cell('m',l))*cell('j',l))*vh.taux_fc;
            ELSE
              -- $K21*$F21
              RETURN cell('k',l) * vh.taux_fc;
            END IF;
          END IF;
        END IF;
      END IF;


    -- x =SI(ESTVIDE(composante_affectation);0;SI(OU(ESTVIDE($C21);NON(C21="Référentiel");ET(HC=0;H21="Non"));0;SI(H21="Non";O21;SI($M21>0;(($M21*$N21)+($I21-$M21)*J21);$K21))))
    WHEN c = 'x' AND v >= 1 THEN
      IF i.type_intervenant_code = 'E' THEN
        RETURN 0;
      ELSE
        -- OU(NON(C21="Référentiel");ET(HC=0;H21="Non"))
        IF vh.volume_horaire_ref_id IS NULL OR (calcFnc('total', 'o')=0 AND NOT vh.service_statutaire) THEN
          RETURN 0;
        ELSE
          -- SI(H21="Non";O21;SI($M21>0;(($M21*$N21)+($I21-$M21)*J21);$K21))
          IF NOT vh.service_statutaire THEN
            RETURN cell('o', l);
          ELSE
            -- SI($M21>0;(($M21*$N21)+($I21-$M21)*J21);$K21)
            IF cell('m',l) > 0 THEN
              RETURN (cell('m',l)*cell('n',l))+(vh.heures-cell('m',l))*cell('j',l);
            ELSE
              RETURN cell('k',l);
            END IF;
          END IF;
        END IF;
      END IF;



    -- y =SI($C21="Référentiel";0;SI(ESTVIDE(composante_affectation);O21;$S21))
    WHEN c = 'y' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        IF i.type_intervenant_code = 'E' THEN
          RETURN cell('o',l);
        ELSE
          RETURN cell('s',l);
        END IF;
      END IF;



    -- z =0
    WHEN c = 'z' AND v >= 1 THEN
      RETURN 0;



    -- aa =0
    WHEN c = 'aa' AND v >= 1 THEN
      RETURN 0;



    -- ab =0
    WHEN c = 'ab' AND v >= 1 THEN
      RETURN 0;



    -- ac =SI($C21="Référentiel";SI(ESTVIDE(composante_affectation);O21;$S21);0)
    WHEN c = 'ac' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        IF i.type_intervenant_code = 'E' THEN
          RETURN cell('o',l);
        ELSE
          RETURN cell('s',l);
        END IF;
      ELSE
        RETURN 0;
      END IF;





    -- hc_budg =SI(ESTVIDE(composante_affectation);0;HC-SOMME.SI(H$21:H$131;"Non";O$21:O$131))
    -- (composante_affectation vide si vacataire)
    WHEN c = 'hc_budg_cell' AND v >= 1 THEN
      IF NOT vh.service_statutaire THEN
        RETURN 0;
      ELSE
        RETURN cell('o', l);
      END IF;

    WHEN c = 'hc_budg' AND v >= 1 THEN
      IF i.type_intervenant_code = 'E' THEN
        RETURN 0;
      ELSE
        RETURN calcFnc('total', 'hc_budg_cell');
      END IF;



    ELSE
      raise_application_error( -20001, 'La colonne c=' || c || ', l=' || l || ' n''existe pas!');
  END CASE; END;



  PROCEDURE CALCUL_RESULTAT IS
  BEGIN
    feuille.delete;

    -- transmission des résultats aux volumes horaires et volumes horaires référentiel
    FOR l IN 1 .. ose_formule.volumes_horaires.length LOOP
      ose_formule.volumes_horaires.items(l).service_fi               := mainCell('Service FI', 'u',l);
      ose_formule.volumes_horaires.items(l).service_fa               := mainCell('Service FA', 'v',l);
      ose_formule.volumes_horaires.items(l).service_fc               := mainCell('Service FC', 'w',l);
      ose_formule.volumes_horaires.items(l).service_referentiel      := mainCell('Service référentiel', 'x',l);
      ose_formule.volumes_horaires.items(l).heures_compl_fi          := mainCell('Heures compl. FI', 'y',l);
      ose_formule.volumes_horaires.items(l).heures_compl_fa          := mainCell('Heures compl. FA', 'z',l);
      ose_formule.volumes_horaires.items(l).heures_compl_fc          := mainCell('Heures compl. FC', 'aa',l);
      ose_formule.volumes_horaires.items(l).heures_primes            := mainCell('Heures compl. FC Maj.', 'ab',l);
      ose_formule.volumes_horaires.items(l).heures_compl_referentiel := mainCell('Heures compl. référentiel', 'ac',l);
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

END FORMULE_MONTPELLIER;