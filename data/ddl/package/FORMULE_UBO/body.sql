CREATE OR REPLACE PACKAGE BODY "FORMULE_UBO" AS
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

  debugActif BOOLEAN DEFAULT TRUE;
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
    IF debugActif THEN
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
    IF debugActif THEN
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



    WHEN c = 'CM' AND v >= 1 THEN
      IF vh.type_intervention_code = 'CM' THEN
        RETURN vh.heures;
      ELSE
        RETURN 0;
      END IF;



    WHEN c = 'TD' AND v >= 1 THEN
      IF vh.type_intervention_code = 'TD' THEN
        RETURN vh.heures;
      ELSE
        RETURN 0;
      END IF;



    WHEN c = 'TP' AND v >= 1 THEN
      IF vh.type_intervention_code = 'TP' THEN
        RETURN vh.heures;
      ELSE
        RETURN 0;
      END IF;



    WHEN c='sCM' AND v >= 1 THEN
      RETURN calcFnc('total', 'CM');



    WHEN c='sTD' AND v >= 1 THEN
      RETURN calcFnc('total', 'TD');



    WHEN c='sTP' AND v >= 1 THEN
      RETURN calcFnc('total', 'TP');



    WHEN c='sHeures' AND v >= 1 THEN
      RETURN cell('sCM') + cell('sTD') + cell('sTP');



    -- =SI(I9=0;2/3;SI(I8="Oui";SI(SOMME(I26:K35)=0;1;(2+(I15/((1,5*SOMME(I26:I35))+SOMME(J26:K35))))/3);SI(SOMME(K26:K35)<=384;1;((384+((SOMME(K26:K35)-384)*(2/3)))/SOMME(K26:K35)))))
    -- I8= TP vaut TD
    -- I9 = i.heures_service_statutaire
    -- I15 = i.service_du
    -- I26:I35 = Somme des CM I=CM, J=TD, K=TP
    -- K26:K35 = Somme des TP
    WHEN c = 'tauxTP' AND v >= 1 THEN
      IF i.heures_service_statutaire = 0 THEN
        RETURN 2/3;
      ELSE
        -- SI(I8="Oui";SI(SOMME(I26:K35)=0;1;(2+(I15/((1,5*SOMME(I26:I35))+SOMME(J26:K35))))/3);SI(SOMME(K26:K35)<=384;1;((384+((SOMME(K26:K35)-384)*(2/3)))/SOMME(K26:K35))))
        IF LOWER(i.param_1)='oui' THEN
          -- SI(SOMME(I26:K35)=0;1;(2+(I15/((1,5*SOMME(I26:I35))+SOMME(J26:K35))))/3);
          IF cell('sHeures') = 0 THEN
            RETURN 1;
          ELSE
            -- (2+(I15/((1,5*SOMME(I26:I35))+SOMME(J26:K35))))/3
            RETURN (2+(i.service_du/((1.5*cell('sCM'))+cell('sTD')+cell('sTP'))))/3;
          END IF;
        ELSE
          -- SI(SOMME(K26:K35)<=384;1;((384+((SOMME(K26:K35)-384)*(2/3)))/SOMME(K26:K35)))
          IF cell('sTP') <= 384 THEN
            RETURN 1;
          ELSE
            --(384+((SOMME(K26:K35)-384)*(2/3)))/SOMME(K26:K35)
            RETURN (384+((cell('sTP')-384)*(2/3)))/cell('sTP');
          END IF;
        END IF;
      END IF;



    WHEN c = 'tauxServiceDu' AND v >= 1 THEN
      IF vh.type_intervention_code = 'TP' THEN
        RETURN cell('tauxTP');
      ELSE
        RETURN vh.taux_service_du;
      END IF;



    WHEN c = 'tauxServiceCompl' AND v >= 1 THEN
      IF vh.type_intervention_code = 'TP' THEN
        RETURN cell('tauxTP');
      ELSE
        RETURN vh.taux_service_compl;
      END IF;


    -- t11=SI(ET($H26=$I$11;NON($F26));I26;0)
    WHEN c = 't11' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NULL AND vh.structure_is_affectation AND NOT vh.taux_fc = 1 THEN
        RETURN vh.heures;
      ELSE
        RETURN 0;
      END IF;



    -- t12=SI(ET($H26<>$I$11;NON($F26));I26;0)
    WHEN c = 't12' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NULL AND NOT vh.structure_is_affectation AND NOT vh.taux_fc = 1 THEN
        RETURN vh.heures;
      ELSE
        RETURN 0;
      END IF;



    -- t13=SI(ET($H26=$I$11;$F26);I26;0)
    WHEN c = 't13' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NULL AND vh.structure_is_affectation AND vh.taux_fc = 1 THEN
        RETURN vh.heures;
      ELSE
        RETURN 0;
      END IF;



    -- t14=SI(ET($H26<>$I$11;$F26);I26;0)
    WHEN c = 't14' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NULL AND NOT vh.structure_is_affectation AND vh.taux_fc = 1 THEN
        RETURN vh.heures;
      ELSE
        RETURN 0;
      END IF;



    -- t15=SI($H38=$I$11;I38;0)
    WHEN c = 't15' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL AND vh.structure_is_affectation THEN
        RETURN vh.heures;
      ELSE
        RETURN 0;
      END IF;



    -- t16=SI(ET($H38<>$I$11;$H38<>$I$2);I38;0)
    WHEN c = 't16' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL AND NOT vh.structure_is_affectation AND NOT vh.structure_is_univ THEN
        RETURN vh.heures;
      ELSE
        RETURN 0;
      END IF;



    -- t17=SI($H38=$I$2;I38;0)
    WHEN c = 't17' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL AND vh.structure_is_univ THEN
        RETURN vh.heures;
      ELSE
        RETURN 0;
      END IF;



    -- t21=I47*I$24
    WHEN c = 't21' AND v >= 1 THEN
      RETURN cell('t11', l) * cell('tauxServiceDu',l);



    -- t22=S47*I$24
    WHEN c = 't22' AND v >= 1 THEN
      RETURN cell('t12', l) * cell('tauxServiceDu',l);



    -- t23=AC47*I$24
    WHEN c = 't23' AND v >= 1 THEN
      RETURN cell('t13', l) * cell('tauxServiceDu',l);



    -- t24=AM47*I$24
    WHEN c = 't24' AND v >= 1 THEN
      RETURN cell('t14', l) * cell('tauxServiceDu',l);



    -- t25=AW47*$R$5
    WHEN c = 't25' AND v >= 1 THEN
      RETURN cell('t15', l);



    -- t26=AY47*$R$5
    WHEN c = 't26' AND v >= 1 THEN
      RETURN cell('t16', l);



    -- t27=BA47*$R$5
    WHEN c = 't27' AND v >= 1 THEN
      RETURN cell('t17', l);



    -- t31=MAX(I15-Q69;0)
    WHEN c = 't31' AND v >= 1 THEN
      RETURN GREATEST(ose_formule.intervenant.service_du - calcFnc('total','t21'), 0);



    -- t32=MAX(Q71-AA69;0)
    WHEN c = 't32' AND v >= 1 THEN
      RETURN GREATEST(cell('t31') - calcFnc('total','t22'), 0);



    -- t33=MAX(AA71-AK69;0)
    WHEN c = 't33' AND v >= 1 THEN
      RETURN GREATEST(cell('t32') - calcFnc('total','t23'), 0);



    -- t34=MAX(AK71-AU69;0)
    WHEN c = 't34' AND v >= 1 THEN
      RETURN GREATEST(cell('t33') - calcFnc('total','t24'), 0);



    -- t35=MAX(AU71-AW64;0)
    WHEN c = 't35' AND v >= 1 THEN
      RETURN GREATEST(cell('t34') - calcFnc('total','t25'), 0);



    -- t36=MAX(AW71-AY64;0)
    WHEN c = 't36' AND v >= 1 THEN
      RETURN GREATEST(cell('t35', l) - calcFnc('total','t26'), 0);



    -- t37=MAX(AY71-BA64;0)
    WHEN c = 't37' AND v >= 1 THEN
      RETURN GREATEST(cell('t36', l) - calcFnc('total','t27'), 0);



    -- t41=SI($Q$69<>0;I59/$Q$69;0)
    WHEN c = 't41' AND v >= 1 THEN
      IF calcFnc('total','t21') <> 0 THEN
        RETURN cell('t21', l) / calcFnc('total','t21');
      ELSE
        RETURN 0;
      END IF;



    -- t42=SI($AA$69<>0;S59/$AA$69;0)
    WHEN c = 't42' AND v >= 1 THEN
      IF calcFnc('total','t22') <> 0 THEN
        RETURN cell('t22', l) / calcFnc('total','t22');
      ELSE
        RETURN 0;
      END IF;



    -- t43=SI($AK$69<>0;AC59/$AK$69;0)
    WHEN c = 't43' AND v >= 1 THEN
      IF calcFnc('total','t23') <> 0 THEN
        RETURN cell('t23', l) / calcFnc('total','t23');
      ELSE
        RETURN 0;
      END IF;



    -- t44=SI($AU$69<>0;AM59/$AU$69;0)
    WHEN c = 't44' AND v >= 1 THEN
      IF calcFnc('total','t24') <> 0 THEN
        RETURN cell('t24', l) / calcFnc('total','t24');
      ELSE
        RETURN 0;
      END IF;



    -- t45=SI($AW$64<>0;AW59/$AW$64;0)
    WHEN c = 't45' AND v >= 1 THEN
      IF calcFnc('total','t25') <> 0 THEN
        RETURN cell('t25', l) / calcFnc('total','t25');
      ELSE
        RETURN 0;
      END IF;



    -- t46=SI($AY$64<>0;AY59/$AY$64;0)
    WHEN c = 't46' AND v >= 1 THEN
      IF calcFnc('total','t26') <> 0 THEN
        RETURN cell('t26', l) / calcFnc('total','t26');
      ELSE
        RETURN 0;
      END IF;



    -- t47=SI($BA$64<>0;BA59/$BA$64;0)
    WHEN c = 't47' AND v >= 1 THEN
      IF calcFnc('total','t27') <> 0 THEN
        RETURN cell('t27', l) / calcFnc('total','t27');
      ELSE
        RETURN 0;
      END IF;



    -- t51=MIN($I$15;$Q$69)*I74
    WHEN c = 't51' AND v >= 1 THEN
      RETURN LEAST(ose_formule.intervenant.service_du, calcFnc('total','t21')) * cell('t41', l);



    -- t52=MIN($Q$71;$AA$69)*S74
    WHEN c = 't52' AND v >= 1 THEN
      RETURN LEAST(cell('t31'), calcFnc('total','t22')) * cell('t42', l);



    -- t53=MIN($AA$71;$AK$69)*AC74
    WHEN c = 't53' AND v >= 1 THEN
      RETURN LEAST(cell('t32'), calcFnc('total','t23')) * cell('t43', l);



    -- t54=MIN($AK$71;$AU$69)*AM74
    WHEN c = 't54' AND v >= 1 THEN
      RETURN LEAST(cell('t33'), calcFnc('total','t24')) * cell('t44', l);



    -- t55=MIN($AU$71;$AW$64)*AW74
    WHEN c = 't55' AND v >= 1 THEN
      RETURN LEAST(cell('t34'), calcFnc('total','t25')) * cell('t45', l);



    -- t56=MIN($AW$71;$AY$64)*AY74
    WHEN c = 't56' AND v >= 1 THEN
      RETURN LEAST(cell('t35'), calcFnc('total','t26')) * cell('t46', l);



    -- t57=MIN($AY$71;$BA$64)*BA74
    WHEN c = 't57' AND v >= 1 THEN
      RETURN LEAST(cell('t36'), calcFnc('total','t27')) * cell('t47', l);



    -- t61=I86*$C26
    WHEN c = 't61' AND v >= 1 THEN
      RETURN cell('t51', l) * vh.taux_fi;



    -- t62=S86*$C26
    WHEN c = 't62' AND v >= 1 THEN
      RETURN cell('t52', l) * vh.taux_fi;



    -- t71=I86*$D26
    WHEN c = 't71' AND v >= 1 THEN
      RETURN cell('t51', l) * vh.taux_fa;



    -- t72=S86*$D26
    WHEN c = 't72' AND v >= 1 THEN
      RETURN cell('t52', l) * vh.taux_fa;



    -- t81=I86*$E26
    WHEN c = 't81' AND v >= 1 THEN
      RETURN cell('t51', l) * vh.taux_fc;



    -- t82=S86*$E26
    WHEN c = 't82' AND v >= 1 THEN
      RETURN cell('t52', l) * vh.taux_fc;



    -- t83=AC86*$E26
    WHEN c = 't83' AND v >= 1 THEN
      RETURN cell('t53', l) * vh.taux_fc;



    -- t84=AM86*$E26
    WHEN c = 't84' AND v >= 1 THEN
      RETURN cell('t54', l) * vh.taux_fc;



    -- t91=SI(I59<>0;I86/I59;0)
    WHEN c = 't91' AND v >= 1 THEN
      IF cell('t21', l) <> 0 THEN
        RETURN cell('t51', l) / cell('t21', l);
      ELSE
        RETURN 0;
      END IF;



    -- t92=SI(S59<>0;S86/S59;0)
    WHEN c = 't92' AND v >= 1 THEN
      IF cell('t22', l) <> 0 THEN
        RETURN cell('t52', l) / cell('t22', l);
      ELSE
        RETURN 0;
      END IF;



    -- t93=SI(AC59<>0;AC86/AC59;0)
    WHEN c = 't93' AND v >= 1 THEN
      IF cell('t23', l) <> 0 THEN
        RETURN cell('t53', l) / cell('t23', l);
      ELSE
        RETURN 0;
      END IF;



    -- t94=SI(AM59<>0;AM86/AM59;0)
    WHEN c = 't94' AND v >= 1 THEN
      IF cell('t24', l) <> 0 THEN
        RETURN cell('t54', l) / cell('t24', l);
      ELSE
        RETURN 0;
      END IF;



    -- t95=SI(AW59<>0;AW86/AW59;0)
    WHEN c = 't95' AND v >= 1 THEN
      IF cell('t25', l) <> 0 THEN
        RETURN cell('t55', l) / cell('t25', l);
      ELSE
        RETURN 0;
      END IF;



    -- t96=SI(AY59<>0;AY86/AY59;0)
    WHEN c = 't96' AND v >= 1 THEN
      IF cell('t26', l) <> 0 THEN
        RETURN cell('t56', l) / cell('t26', l);
      ELSE
        RETURN 0;
      END IF;



    -- t97=SI(BA59<>0;BA86/BA59;0)
    WHEN c = 't97' AND v >= 1 THEN
      IF cell('t27', l) <> 0 THEN
        RETURN cell('t57', l) / cell('t27', l);
      ELSE
        RETURN 0;
      END IF;



    -- t101=SI($BA$71<>0;0;1-I134)
    WHEN c = 't101' AND v >= 1 THEN
      IF cell('t37') <> 0 THEN
        RETURN 0;
      ELSE
        RETURN 1 - cell('t91', l);
      END IF;



    -- t102=SI($BA$71<>0;0;1-S134)
    WHEN c = 't102' AND v >= 1 THEN
      IF cell('t37') <> 0 THEN
        RETURN 0;
      ELSE
        RETURN 1 - cell('t92', l);
      END IF;



    -- t103=SI($BA$71<>0;0;1-AC134)
    WHEN c = 't103' AND v >= 1 THEN
      IF cell('t37') <> 0 THEN
        RETURN 0;
      ELSE
        RETURN 1 - cell('t93', l);
      END IF;



    -- t104=SI($BA$71<>0;0;1-AM134)
    WHEN c = 't104' AND v >= 1 THEN
      IF cell('t37') <> 0 THEN
        RETURN 0;
      ELSE
        RETURN 1 - cell('t94', l);
      END IF;



    -- t105=SI($BA$71<>0;0;1-AW134)
    WHEN c = 't105' AND v >= 1 THEN
      IF cell('t37') <> 0 THEN
        RETURN 0;
      ELSE
        RETURN 1 - cell('t95', l);
      END IF;



    -- t106=SI($BA$71<>0;0;1-AY134)
    WHEN c = 't106' AND v >= 1 THEN
      IF cell('t37') <> 0 THEN
        RETURN 0;
      ELSE
        RETURN 1 - cell('t96', l);
      END IF;



    -- t107=SI($BA$71<>0;0;1-BA134)
    WHEN c = 't107' AND v >= 1 THEN
      IF cell('t37') <> 0 THEN
        RETURN 0;
      ELSE
        RETURN 1 - cell('t97', l);
      END IF;



    -- t111=I47*I$25*I146
    WHEN c = 't111' AND v >= 1 THEN
      RETURN cell('t11', l) * cell('tauxServiceCompl',l) * cell('t101', l);



    -- t112=S47*I$25*S146
    WHEN c = 't112' AND v >= 1 THEN
      RETURN cell('t12', l) * cell('tauxServiceCompl',l) * cell('t102', l);



    -- t113=AC47*I$25*AC146
    WHEN c = 't113' AND v >= 1 THEN
      RETURN cell('t13', l) * cell('tauxServiceCompl',l) * cell('t103', l);



    -- t114=AM47*I$25*AM146
    WHEN c = 't114' AND v >= 1 THEN
      RETURN cell('t14', l) * cell('tauxServiceCompl',l) * cell('t104', l);



    -- t115=AW47*$R$6*AW146
    WHEN c = 't115' AND v >= 1 THEN
      RETURN cell('t15', l) * cell('t105', l);



    -- t116=AY47*$R$6*AY146
    WHEN c = 't116' AND v >= 1 THEN
      RETURN cell('t16', l) * cell('t106', l);



    -- t117=BA47*$R$6*BA146
    WHEN c = 't117' AND v >= 1 THEN
      RETURN cell('t17', l) * cell('t107', l);



    -- t123=AC158*SI($F26;$G26;1)
    WHEN c = 't123' AND v >= 1 THEN
      IF vh.taux_fc = 1 THEN
        RETURN cell('t113', l) * vh.ponderation_service_compl;
      ELSE
        RETURN cell('t113', l);
      END IF;



    -- t124=AM158*SI($F26;$G26;1)
    WHEN c = 't124' AND v >= 1 THEN
      IF vh.taux_fc = 1 THEN
        RETURN cell('t114', l) * vh.ponderation_service_compl;
      ELSE
        RETURN cell('t114', l);
      END IF;



    -- t131=I158*$C26
    WHEN c = 't131' AND v >= 1 THEN
      RETURN cell('t111', l) * vh.taux_fi;



    -- t132=S158*$C26
    WHEN c = 't132' AND v >= 1 THEN
      RETURN cell('t112', l) * vh.taux_fi;



    -- t141=I158*$D26
    WHEN c = 't141' AND v >= 1 THEN
      RETURN cell('t111', l) * vh.taux_fa;



    -- t142=S158*$D26
    WHEN c = 't142' AND v >= 1 THEN
      RETURN cell('t112', l) * vh.taux_fa;



    -- t151=I158*$E26
    WHEN c = 't151' AND v >= 1 THEN
      RETURN cell('t111', l) * vh.taux_fc;



    -- t152=S158*$E26
    WHEN c = 't152' AND v >= 1 THEN
      RETURN cell('t112', l) * vh.taux_fc;



    -- t153=SI(AC170=AC158;AC158;0)*$E26
    WHEN c = 't153' AND v >= 1 THEN
      IF cell('t123', l) = cell('t113', l) THEN
        RETURN cell('t113', l);
      ELSE
        RETURN 0;
      END IF;



    -- t154=SI(AM170=AM158;AM158;0)*$E26
    WHEN c = 't154' AND v >= 1 THEN
      IF cell('t124', l) = cell('t114', l) THEN
        RETURN cell('t114', l);
      ELSE
        RETURN 0;
      END IF;



    -- t163=SI(AC170<>AC158;AC170;0)*$E26
    WHEN c = 't163' AND v >= 1 THEN
      IF cell('t123', l) <> cell('t113', l) THEN
        RETURN cell('t123', l);
      ELSE
        RETURN 0;
      END IF;



    -- t164=SI(AM170<>AM158;AM170;0)*$E26
    WHEN c = 't164' AND v >= 1 THEN
      IF cell('t124', l) <> cell('t114', l) THEN
        RETURN cell('t124', l);
      ELSE
        RETURN 0;
      END IF;



    -- rs=SOMME(I98:AU98)
    WHEN c = 'rs' AND v >= 1 THEN
      RETURN cell('t61',l) + cell('t62',l);



    -- ss=SOMME(I110:AU110)
    WHEN c = 'ss' AND v >= 1 THEN
      RETURN cell('t71',l) + cell('t72',l);



    -- ts=SOMME(I122:AU122)
    WHEN c = 'ts' AND v >= 1 THEN
      RETURN cell('t81',l) + cell('t82',l) + cell('t83',l) + cell('t84',l);



    -- us=SI($I$13="Oui";SOMME(I182:AU182);0)
    WHEN c = 'us' AND v >= 1 THEN
      RETURN cell('t131',l) + cell('t132',l);



    -- vs=SI($I$13="Oui";SOMME(I194:AU194);0)
    WHEN c = 'vs' AND v >= 1 THEN
      IF NOT ose_formule.intervenant.depassement_service_du_sans_hc THEN
        RETURN cell('t141',l) + cell('t142',l);
      ELSE
        RETURN 0;
      END IF;



    -- ws=SI($I$13="Oui";SOMME(I206:AU206);0)
    WHEN c = 'ws' AND v >= 1 THEN
      IF NOT ose_formule.intervenant.depassement_service_du_sans_hc THEN
        RETURN cell('t151',l) + cell('t152',l) + cell('t153',l) + cell('t154',l);
      ELSE
        RETURN 0;
      END IF;



    -- xs=SI($I$13="Oui";SOMME(I218:AU218);0)
    WHEN c = 'xs' AND v >= 1 THEN
      IF NOT ose_formule.intervenant.depassement_service_du_sans_hc THEN
        RETURN cell('t163',l) + cell('t164',l);
      ELSE
        RETURN 0;
      END IF;



    -- rr=SOMME(AW86:BA86)
    WHEN c = 'rr' AND v >= 1 THEN
      RETURN cell('t55',l) + cell('t56',l) + cell('t57',l);



    -- ur=SI($I$13="Oui";SOMME(AW158:BA158);0)
    WHEN c = 'ur' AND v >= 1 THEN
      IF NOT ose_formule.intervenant.depassement_service_du_sans_hc THEN
        RETURN cell('t115',l) + cell('t116',l) + cell('t117',l);
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
      ose_formule.volumes_horaires.items(l).service_fi               := mainCell('Service FI', 'rs',l);
      ose_formule.volumes_horaires.items(l).service_fa               := mainCell('Service FA', 'ss',l);
      ose_formule.volumes_horaires.items(l).service_fc               := mainCell('Service FC', 'ts',l);
      ose_formule.volumes_horaires.items(l).service_referentiel      := mainCell('Service référentiel', 'rr',l);
      ose_formule.volumes_horaires.items(l).heures_compl_fi          := mainCell('Heures compl. FI', 'us',l);
      ose_formule.volumes_horaires.items(l).heures_compl_fa          := mainCell('Heures compl. FA', 'vs',l);
      ose_formule.volumes_horaires.items(l).heures_compl_fc          := mainCell('Heures compl. FC', 'ws',l);
      ose_formule.volumes_horaires.items(l).heures_primes            := mainCell('Heures compl. FC Maj.', 'xs',l);
      ose_formule.volumes_horaires.items(l).heures_compl_referentiel := mainCell('Heures compl. référentiel', 'ur',l);
    END LOOP;
  END;



  FUNCTION INTERVENANT_QUERY RETURN CLOB IS
  BEGIN
    RETURN '
    SELECT
      fi.*,
      CASE WHEN si.code IN (''ENS_CH'',''ASS_MI_TPS'',''ENS_CH_LRU'',''DOCTOR'') THEN ''oui'' ELSE ''non'' END param_1,
     CASE WHEN si.code IN (''LECTEUR'',''ATER'') THEN ''oui'' ELSE ''non'' END param_2,
      NULL param_3,
      NULL param_4,
      NULL param_5
    FROM
      V_FORMULE_INTERVENANT fi
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
      V_FORMULE_VOLUME_HORAIRE fvh
    ORDER BY
      ordre';
  END;

END FORMULE_UBO;