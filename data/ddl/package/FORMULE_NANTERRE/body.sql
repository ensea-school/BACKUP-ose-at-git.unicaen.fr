CREATE OR REPLACE PACKAGE BODY FORMULE_NANTERRE AS
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



    -- service_realise=SOMME(BJ22:BM51)
    WHEN c = 'service_realise' AND v >= 1 THEN
      RETURN calcFnc('total','BJ') + calcFnc('total','BK') + calcFnc('total','BL') + calcFnc('total','BM');



    -- HC=SOMME(BN22:BR51)
    WHEN c = 'HC' AND v >= 1 THEN
      RETURN calcFnc('total','BN') + calcFnc('total','BO') + calcFnc('total','BP') + calcFnc('total','BQ') + calcFnc('total','BR');



    -- I=SI(ESTVIDE(C22);0;RECHERCHEH(SI(ET(C22="TP";TP_vaut_TD="Oui");"TD";C22);types_intervention;2;0))
    WHEN c = 'I' AND v >= 1 THEN
      -- ON ne retourne que le taux en service dû... ? ?
      RETURN vh.taux_service_du;



    -- J=H22*I22
    WHEN c = 'J' AND v >= 1 THEN
      RETURN vh.heures * cell('I', l);



    -- L=SI($H22="";0;SI(ET($C22<>"Référentiel";$B22=composante_affectation;$B22<>"KE8";$B22<>"UP10");$J22*$D22;0))
    WHEN c = 'L' AND v >= 1 THEN
      -- ET($C22<>"Référentiel";$B22=composante_affectation;$B22<>"KE8";$B22<>"UP10")
      IF vh.volume_horaire_ref_id IS NULL AND vh.structure_is_affectation AND notInStructs(vh.structure_code) THEN
        RETURN cell('J',l) * vh.taux_fi;
      ELSE
        RETURN 0;
      END IF;



    -- M=SI(L$52>0;L22/L$52;0)
    WHEN c = 'M' AND v >= 1 THEN
      IF cell('L52') > 0 THEN
        RETURN cell('L', l) / cell('L52');
      ELSE
        RETURN 0;
      END IF;



    -- N=L$53*M22
    WHEN c = 'N' AND v >= 1 THEN
      RETURN cell('L53') * cell('M', l);



    -- O=SI(ET(L$54=0;HC_autorisees="Oui");L22-N22;0)
    WHEN c = 'O' AND v >= 1 THEN
      IF cell('L54') = 0 AND NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('L', l) - cell('N', l);
      ELSE
        RETURN 0;
      END IF;



    -- Q=SI($H22="";0;SI(ET($C22="Référentiel";$G22="Oui";$B22=composante_affectation;$B22<>"KE8";$B22<>"UP10");$J22;0))
    WHEN c = 'Q' AND v >= 1 THEN
      -- ET($C22="Référentiel";$G22="Oui";$B22=composante_affectation;$B22<>"KE8";$B22<>"UP10")
      IF vh.volume_horaire_ref_id IS NOT NULL AND vh.service_statutaire AND vh.structure_is_affectation AND notInStructs(vh.structure_code) THEN
        RETURN cell('J',l);
      ELSE
        RETURN 0;
      END IF;



    -- R=SI(Q$52>0;Q22/Q$52;0)
    WHEN c = 'R' AND v >= 1 THEN
      IF cell('Q52') > 0 THEN
        RETURN cell('Q', l) / cell('Q52');
      ELSE
        RETURN 0;
      END IF;



    -- S=Q$53*R22
    WHEN c = 'S' AND v >= 1 THEN
      RETURN cell('Q53') * cell('R', l);



    -- T=SI(ET(Q$54=0;HC_autorisees="Oui");Q22-S22;0)
    WHEN c = 'T' AND v >= 1 THEN
      IF cell('Q54') = 0 AND NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('Q', l) - cell('S', l);
      ELSE
        RETURN 0;
      END IF;



    -- V=SI($H22="";0;SI(ET($C22<>"Référentiel";$B22<>composante_affectation;$B22<>"KE8";$B22<>"UP10");$J22*$D22;0))
    WHEN c = 'V' AND v >= 1 THEN
      --ET($C22<>"Référentiel";$B22<>composante_affectation;$B22<>"KE8";$B22<>"UP10");$J22*$D22;0)
      IF vh.volume_horaire_ref_id IS NULL AND NOT vh.structure_is_affectation AND notInStructs(vh.structure_code) THEN
        RETURN cell('J',l) * vh.taux_fi;
      ELSE
        RETURN 0;
      END IF;



    -- W=SI(V$52>0;V22/V$52;0)
    WHEN c = 'W' AND v >= 1 THEN
      IF cell('V52') > 0 THEN
        RETURN cell('V', l) / cell('V52');
      ELSE
        RETURN 0;
      END IF;



    -- X=V$53*W22
    WHEN c = 'X' AND v >= 1 THEN
      RETURN cell('V53') * cell('W', l);



    -- Y=SI(ET(V$54=0;HC_autorisees="Oui");V22-X22;0)
    WHEN c = 'Y' AND v >= 1 THEN
      IF cell('V54') = 0 AND NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('V', l) - cell('X', l);
      ELSE
        RETURN 0;
      END IF;



    -- AA=SI($H22="";0;SI(ET($C22="Référentiel";$G22="Oui";$B22<>composante_affectation;$B22<>"KE8";$B22<>"UP10");$J22;0))
    WHEN c = 'AA' AND v >= 1 THEN
      --ET($C22="Référentiel";$G22="Oui";$B22<>composante_affectation;$B22<>"KE8";$B22<>"UP10")
      IF vh.volume_horaire_ref_id IS NOT NULL AND vh.service_statutaire AND NOT vh.structure_is_affectation AND notInStructs(vh.structure_code) THEN
        RETURN cell('J',l);
      ELSE
        RETURN 0;
      END IF;



    -- AB=SI(AA$52>0;AA22/AA$52;0)
    WHEN c = 'AB' AND v >= 1 THEN
      IF cell('AA52') > 0 THEN
        RETURN cell('AA', l) / cell('AA52');
      ELSE
        RETURN 0;
      END IF;



    -- AC=AA$53*AB22
    WHEN c = 'AC' AND v >= 1 THEN
      RETURN cell('AA53') * cell('AB', l);



    -- AD=SI(ET(AA$54=0;HC_autorisees="Oui");AA22-AC22;0)
    WHEN c = 'AD' AND v >= 1 THEN
      IF cell('AA54') = 0 AND NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('AA', l) - cell('AC', l);
      ELSE
        RETURN 0;
      END IF;



    -- AF=SI($H22="";0;SI(OU($B22="KE8";$B22="UP10");SI($C22="Référentiel";SI($G22="Oui";$J22;0);$J22*$D22);0))
    WHEN c = 'AF' AND v >= 1 THEN
      IF NOT notInStructs(vh.structure_code) THEN
        --SI($C22="Référentiel";SI($G22="Oui";$J22;0);$J22*$D22)
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          --SI($G22="Oui";$J22;0)
          IF vh.service_statutaire THEN
            RETURN cell('J', l);
          ELSE
            RETURN 0;
          END IF;
        ELSE
          RETURN cell('J', l) * vh.taux_fi;
        END IF;
      ELSE
        RETURN 0;
      END IF;



    -- AG=SI(AF$52>0;AF22/AF$52;0)
    WHEN c = 'AG' AND v >= 1 THEN
      IF cell('AF52') > 0 THEN
        RETURN cell('AF', l) / cell('AF52');
      ELSE
        RETURN 0;
      END IF;



    -- AH=AF$53*AG22
    WHEN c = 'AH' AND v >= 1 THEN
      RETURN cell('AF53') * cell('AG', l);



    -- AI=SI(ET(AF$54=0;HC_autorisees="Oui");AF22-AH22;0)
    WHEN c = 'AI' AND v >= 1 THEN
      IF cell('AF54') = 0 AND NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('AF', l) - cell('AH', l);
      ELSE
        RETURN 0;
      END IF;



    -- AK=SI($H22="";0;SI(ET($C22<>"Référentiel";$B22=composante_affectation);$J22*$E22;0))
    WHEN c = 'AK' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NULL AND vh.structure_is_affectation THEN
        RETURN cell('J', l) * vh.taux_fa;
      ELSE
        RETURN 0;
      END IF;



    -- AL=SI(AK$52>0;AK22/AK$52;0)
    WHEN c = 'AL' AND v >= 1 THEN
      IF cell('AK52') > 0 THEN
        RETURN cell('AK', l) / cell('AK52');
      ELSE
        RETURN 0;
      END IF;



    -- AM=AK$53*AL22
    WHEN c = 'AM' AND v >= 1 THEN
      RETURN cell('AK53') * cell('AL', l);



    -- AN=SI(ET(AK$54=0;HC_autorisees="Oui");AK22-AM22;0)
    WHEN c = 'AN' AND v >= 1 THEN
      IF cell('AK54') = 0 AND NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('AK', l) - cell('AM', l);
      ELSE
        RETURN 0;
      END IF;



    -- AP=SI($H22="";0;SI(ET($C22<>"Référentiel";$B22<>composante_affectation);$J22*$E22;0))
    WHEN c = 'AP' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NULL AND NOT vh.structure_is_affectation THEN
        RETURN cell('J', l) * vh.taux_fa;
      ELSE
        RETURN 0;
      END IF;



    -- AQ=SI(AP$52>0;AP22/AP$52;0)
    WHEN c = 'AQ' AND v >= 1 THEN
      IF cell('AP52') > 0 THEN
        RETURN cell('AP', l) / cell('AP52');
      ELSE
        RETURN 0;
      END IF;



    -- AR=AP$53*AQ22
    WHEN c = 'AR' AND v >= 1 THEN
      RETURN cell('AP53') * cell('AQ', l);



    -- AS=SI(ET(AP$54=0;HC_autorisees="Oui");AP22-AR22;0)
    WHEN c = 'AS' AND v >= 1 THEN
      IF cell('AP54') = 0 AND NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('AP', l) - cell('AR', l);
      ELSE
        RETURN 0;
      END IF;



    -- AU=SI($H22="";0;SI(ET($C22<>"Référentiel";$B22=composante_affectation);$J22*$F22;0))
    WHEN c = 'AU' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NULL AND vh.structure_is_affectation THEN
        RETURN cell('J', l) * vh.taux_fc;
      ELSE
        RETURN 0;
      END IF;



    -- AV=SI(AU$52>0;AU22/AU$52;0)
    WHEN c = 'AV' AND v >= 1 THEN
      IF cell('AU52') > 0 THEN
        RETURN cell('AU', l) / cell('AU52');
      ELSE
        RETURN 0;
      END IF;



    -- AW=AU$53*AV22
    WHEN c = 'AW' AND v >= 1 THEN
      RETURN cell('AU53') * cell('AV', l);



    -- AX=SI(ET(AU$54=0;HC_autorisees="Oui");AU22-AW22;0)
    WHEN c = 'AX' AND v >= 1 THEN
      IF cell('AU54') = 0 AND NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('AU', l) - cell('AW', l);
      ELSE
        RETURN 0;
      END IF;



    -- AZ=SI($H22="";0;SI(ET($C22<>"Référentiel";$B22<>composante_affectation);$J22*$F22;0))
    WHEN c = 'AZ' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NULL AND NOT vh.structure_is_affectation THEN
        RETURN cell('J', l) * vh.taux_fc;
      ELSE
        RETURN 0;
      END IF;



    -- BA=SI(AZ$52>0;AZ22/AZ$52;0)
    WHEN c = 'BA' AND v >= 1 THEN
      IF cell('AZ52') > 0 THEN
        RETURN cell('AZ', l) / cell('AZ52');
      ELSE
        RETURN 0;
      END IF;



    -- BB=AZ$53*BA22
    WHEN c = 'BB' AND v >= 1 THEN
      RETURN cell('AZ53') * cell('BA', l);



    -- BC=SI(ET(AZ$54=0;HC_autorisees="Oui");AZ22-BB22;0)
    WHEN c = 'BC' AND v >= 1 THEN
      IF cell('AZ54') = 0 AND NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('AZ', l) - cell('BB', l);
      ELSE
        RETURN 0;
      END IF;



    -- BE=J22-SOMME(L22;Q22;V22;AA22;AF22;AK22;AP22;AU22;AZ22)
    WHEN c = 'BE' AND v >= 1 THEN
      RETURN cell('J', l) - (cell('L', l) + cell('Q', l) + cell('V', l) + cell('AA', l) + cell('AF', l) + cell('AK', l) + cell('AP', l) + cell('AU', l) + cell('AZ', l));



    -- BF=SI(BE$52>0;BE22/BE$52;0)
    WHEN c = 'BF' AND v >= 1 THEN
      IF cell('BE52') > 0 THEN
        RETURN cell('BE', l) / cell('BE52');
      ELSE
        RETURN 0;
      END IF;



    -- BG=BE$53*BF22
    WHEN c = 'BG' AND v >= 1 THEN
      RETURN cell('BE53') * cell('BF', l);



    -- BH=SI(ET(BE$54=0;HC_autorisees="Oui");BE22-BG22;0)
    WHEN c = 'BH' AND v >= 1 THEN
      IF cell('BE54') = 0 AND NOT i.depassement_service_du_sans_hc THEN
        RETURN cell('BE', l) - cell('BG', l);
      ELSE
        RETURN 0;
      END IF;



    -- BJ=N22+X22+AH22
    WHEN c = 'BJ' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN cell('N', l) + cell('X', l);
      ELSE
        RETURN cell('N', l) + cell('X', l) + cell('AH', l);
      END IF;



    -- BK=AM22+AR22
    WHEN c = 'BK' AND v >= 1 THEN
      RETURN cell('AM', l) + cell('AR', l);



    -- BL=AW22+BB22
    WHEN c = 'BL' AND v >= 1 THEN
      RETURN cell('AW', l) + cell('BB', l);



    -- BM=S22+AC22+BG22
    WHEN c = 'BM' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN cell('S', l) + cell('AC', l) + cell('BG', l) + cell('AH', l);
      ELSE
        RETURN cell('S', l) + cell('AC', l) + cell('BG', l);
      END IF;



    -- BN=O22+Y22+AI22
    WHEN c = 'BN' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN cell('O', l) + cell('Y', l);
      ELSE
        RETURN cell('O', l) + cell('Y', l) + cell('AI', l);
      END IF;



    -- BO=AN22+AS22
    WHEN c = 'BO' AND v >= 1 THEN
      RETURN cell('AN', l) + cell('AS', l);



    -- BP=AX22+BC22
    WHEN c = 'BP' AND v >= 1 THEN
      RETURN cell('AX', l) + cell('BC', l);



    -- BQ=0
    WHEN c = 'BQ' AND v >= 1 THEN
      RETURN 0;



    -- BR=T22+AD22+BH22
    WHEN c = 'BR' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN cell('T', l) + cell('AD', l) + cell('BH', l) + cell('AI', l);
      ELSE
        RETURN cell('T', l) + cell('AD', l) + cell('BH', l);
      END IF;



    -- L52=SOMME(L22:L51)
    WHEN c = 'L52' AND v >= 1 THEN
      RETURN calcFnc('total','L');



    -- L53=MIN(L52;service_du)
    WHEN c = 'L53' AND v >= 1 THEN
      RETURN LEAST(cell('L52'), i.service_du);



    -- L54=service_du-L53
    WHEN c = 'L54' AND v >= 1 THEN
      RETURN i.service_du - cell('L53');



    -- Q52=SOMME(Q22:Q51)
    WHEN c = 'Q52' AND v >= 1 THEN
      RETURN calcFnc('total','Q');



    -- Q53=MIN(Q52;L54)
    WHEN c = 'Q53' AND v >= 1 THEN
      RETURN LEAST(cell('Q52'), cell('L54'));



    -- Q54=L54-Q53
    WHEN c = 'Q54' AND v >= 1 THEN
      RETURN cell('L54') - cell('Q53');



    -- V52=SOMME(V22:V51)
    WHEN c = 'V52' AND v >= 1 THEN
      RETURN calcFnc('total','V');



    -- V53=MIN(V52;Q54)
    WHEN c = 'V53' AND v >= 1 THEN
      RETURN LEAST(cell('V52'), cell('Q54'));



    -- V54=Q54-V53
    WHEN c = 'V54' AND v >= 1 THEN
      RETURN cell('Q54') - cell('V53');



    -- AA52=SOMME(AA22:AA51)
    WHEN c = 'AA52' AND v >= 1 THEN
      RETURN calcFnc('total','AA');



    -- AA53=MIN(AA52;V54)
    WHEN c = 'AA53' AND v >= 1 THEN
      RETURN LEAST(cell('AA52'), cell('V54'));



    -- AA54=V54-AA53
    WHEN c = 'AA54' AND v >= 1 THEN
      RETURN cell('V54') - cell('AA53');



    -- AF52=SOMME(AF22:AF51)
    WHEN c = 'AF52' AND v >= 1 THEN
      RETURN calcFnc('total','AF');



    -- AF53=MIN(AF52;AA54)
    WHEN c = 'AF53' AND v >= 1 THEN
      RETURN LEAST(cell('AF52'), cell('AA54'));



    -- AF54=AA54-AF53
    WHEN c = 'AF54' AND v >= 1 THEN
      RETURN cell('AA54') - cell('AF53');



    -- AK52=SOMME(AK22:AK51)
    WHEN c = 'AK52' AND v >= 1 THEN
      RETURN calcFnc('total','AK');



    -- AK53=MIN(AK52;AF54)
    WHEN c = 'AK53' AND v >= 1 THEN
      RETURN LEAST(cell('AK52'), cell('AF54'));



    -- AK54=AF54-AK53
    WHEN c = 'AK54' AND v >= 1 THEN
      RETURN cell('AF54') - cell('AK53');



    -- AP52=SOMME(AP22:AP51)
    WHEN c = 'AP52' AND v >= 1 THEN
      RETURN calcFnc('total','AP');



    -- AP53=MIN(AP52;AK54)
    WHEN c = 'AP53' AND v >= 1 THEN
      RETURN LEAST(cell('AP52'), cell('AK54'));



    -- AP54=AK54-AP53
    WHEN c = 'AP54' AND v >= 1 THEN
      RETURN cell('AK54') - cell('AP53');



    -- AU52=SOMME(AU22:AU51)
    WHEN c = 'AU52' AND v >= 1 THEN
      RETURN calcFnc('total','AU');



    -- AU53=MIN(AU52;AP54)
    WHEN c = 'AU53' AND v >= 1 THEN
      RETURN LEAST(cell('AU52'), cell('AP54'));



    -- AU54=AP54-AU53
    WHEN c = 'AU54' AND v >= 1 THEN
      RETURN cell('AP54') - cell('AU53');



    -- AZ52=SOMME(AZ22:AZ51)
    WHEN c = 'AZ52' AND v >= 1 THEN
      RETURN calcFnc('total','AZ');



    -- AZ53=MIN(AZ52;AU54)
    WHEN c = 'AZ53' AND v >= 1 THEN
      RETURN LEAST(cell('AZ52'), cell('AU54'));



    -- AZ54=AU54-AZ53
    WHEN c = 'AZ54' AND v >= 1 THEN
      RETURN cell('AU54') - cell('AZ53');



    -- BE52=SOMME(BE22:BE51)
    WHEN c = 'BE52' AND v >= 1 THEN
      RETURN calcFnc('total','BE');



    -- BE53=MIN(BE52;AZ54)
    WHEN c = 'BE53' AND v >= 1 THEN
      RETURN LEAST(cell('BE52'), cell('AZ54'));



    -- BE54=AZ54-BE53
    WHEN c = 'BE54' AND v >= 1 THEN
      RETURN cell('AZ54') - cell('BE53');



    ELSE
      raise_application_error( -20001, 'La colonne c=' || c || ', l=' || l || ' n''existe pas!');
  END CASE; END;



  PROCEDURE CALCUL_RESULTAT IS
  BEGIN
    feuille.delete;

    -- transmission des résultats aux volumes horaires et volumes horaires référentiel
    FOR l IN 1 .. ose_formule.volumes_horaires.length LOOP
      ose_formule.volumes_horaires.items(l).service_fi               := mainCell('Service FI', 'BJ',l);
      ose_formule.volumes_horaires.items(l).service_fa               := mainCell('Service FA', 'BK',l);
      ose_formule.volumes_horaires.items(l).service_fc               := mainCell('Service FC', 'BL',l);
      ose_formule.volumes_horaires.items(l).service_referentiel      := mainCell('Service référentiel', 'BM',l);
      ose_formule.volumes_horaires.items(l).heures_compl_fi          := mainCell('Heures compl. FI', 'BN',l);
      ose_formule.volumes_horaires.items(l).heures_compl_fa          := mainCell('Heures compl. FA', 'BO',l);
      ose_formule.volumes_horaires.items(l).heures_compl_fc          := mainCell('Heures compl. FC', 'BP',l);
      ose_formule.volumes_horaires.items(l).heures_primes            := mainCell('Heures compl. FC Maj.', 'BQ',l);
      ose_formule.volumes_horaires.items(l).heures_compl_referentiel := mainCell('Heures compl. référentiel', 'BR',l);
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

END FORMULE_NANTERRE;