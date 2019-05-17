-- OSE 8.1
-- Mise à jour depuis les versions 8.0 à 8.0.x vers la version 8.1



--------------------------------------------------
-- Suppression des séquences
--------------------------------------------------

DROP SEQUENCE MESSAGE_ID_SEQ;
/

DROP SEQUENCE PACKAGE_DEPS_ID_SEQ;
/

DROP SEQUENCE PERSONNEL_ID_SEQ;
/

DROP SEQUENCE TBL_CHARGENS_SEUILS_ID_SEQ;
/

DROP SEQUENCE TBL_CONFIG_CLES_ID_SEQ;
/

DROP SEQUENCE TBL_CONFIG_ID_SEQ;
/

DROP SEQUENCE TBL_DEPENDANCES_ID_SEQ;
/

DROP SEQUENCE TMP_CALCUL_ID_SEQ;
/

DROP SEQUENCE TYPE_INTERVENTION_REGLE_ID_SEQ;
/

DROP SEQUENCE TYPE_STRUCTURE_ID_SEQ;
/

--------------------------------------------------
-- Suppression des packages
--------------------------------------------------

DROP PACKAGE UNICAEN_OSE_FORMULE;
/




--------------------------------------------------
-- Création des séquences
--------------------------------------------------

CREATE SEQUENCE FTEST_INTERVENANT_ID_SEQ INCREMENT BY 1 MINVALUE 1 NOCACHE;
/

CREATE SEQUENCE FTEST_STRUCTURE_ID_SEQ INCREMENT BY 1 MINVALUE 1 NOCACHE;
/

CREATE SEQUENCE FTEST_VOLUME_HORAIRE_ID_SEQ INCREMENT BY 1 MINVALUE 1 NOCACHE;
/




--------------------------------------------------
-- Création des tables
--------------------------------------------------

CREATE TABLE "FORMULE"
(	"ID" NUMBER(*,0) NOT NULL ENABLE,
   "LIBELLE" VARCHAR2(100 CHAR) NOT NULL ENABLE,
   "PACKAGE_NAME" VARCHAR2(30 CHAR) NOT NULL ENABLE,
   "PROCEDURE_NAME" VARCHAR2(30 CHAR) NOT NULL ENABLE,
   "I_PARAM_1_LIBELLE" VARCHAR2(100 CHAR),
   "I_PARAM_2_LIBELLE" VARCHAR2(100 CHAR),
   "I_PARAM_3_LIBELLE" VARCHAR2(100 CHAR),
   "I_PARAM_4_LIBELLE" VARCHAR2(100 CHAR),
   "I_PARAM_5_LIBELLE" VARCHAR2(100 CHAR),
   "VH_PARAM_1_LIBELLE" VARCHAR2(100 CHAR),
   "VH_PARAM_2_LIBELLE" VARCHAR2(100 CHAR),
   "VH_PARAM_3_LIBELLE" VARCHAR2(100 CHAR),
   "VH_PARAM_4_LIBELLE" VARCHAR2(100 CHAR),
   "VH_PARAM_5_LIBELLE" VARCHAR2(100 CHAR)
);
/

CREATE TABLE "FORMULE_TEST_INTERVENANT"
(	"ID" NUMBER(*,0) NOT NULL ENABLE,
   "LIBELLE" VARCHAR2(150 CHAR) NOT NULL ENABLE,
   "FORMULE_ID" NUMBER(*,0) NOT NULL ENABLE,
   "ANNEE_ID" NUMBER(*,0) NOT NULL ENABLE,
   "TYPE_INTERVENANT_ID" NUMBER(*,0) DEFAULT 1 NOT NULL ENABLE,
   "STRUCTURE_TEST_ID" NUMBER(*,0) NOT NULL ENABLE,
   "TYPE_VOLUME_HORAIRE_ID" NUMBER(*,0) DEFAULT 1 NOT NULL ENABLE,
   "ETAT_VOLUME_HORAIRE_ID" NUMBER(*,0) DEFAULT 1 NOT NULL ENABLE,
   "HEURES_DECHARGE" FLOAT(126) NOT NULL ENABLE,
   "HEURES_SERVICE_STATUTAIRE" FLOAT(126) DEFAULT 0 NOT NULL ENABLE,
   "HEURES_SERVICE_MODIFIE" FLOAT(126) DEFAULT 0 NOT NULL ENABLE,
   "DEPASSEMENT_SERVICE_DU_SANS_HC" NUMBER(1,0) DEFAULT 0 NOT NULL ENABLE,
   "PARAM_1" VARCHAR2(100 CHAR),
   "PARAM_2" VARCHAR2(100 CHAR),
   "PARAM_3" VARCHAR2(100 CHAR),
   "PARAM_4" VARCHAR2(100 CHAR),
   "PARAM_5" VARCHAR2(100 CHAR),
   "A_SERVICE_DU" FLOAT(126) DEFAULT 0 NOT NULL ENABLE,
   "C_SERVICE_DU" FLOAT(126),
   "DEBUG_INFO" CLOB,
   "TAUX_TP_SERVICE_DU" FLOAT(126) DEFAULT 1 NOT NULL ENABLE,
   "TAUX_AUTRE_SERVICE_DU" FLOAT(126) DEFAULT 1 NOT NULL ENABLE,
   "TAUX_AUTRE_SERVICE_COMPL" FLOAT(126) DEFAULT 1 NOT NULL ENABLE,
   "TAUX_CM_SERVICE_DU" FLOAT(126) DEFAULT 1.5 NOT NULL ENABLE,
   "TAUX_CM_SERVICE_COMPL" FLOAT(126) DEFAULT 1.5 NOT NULL ENABLE,
   "TAUX_TP_SERVICE_COMPL" FLOAT(126) DEFAULT 2/3 NOT NULL ENABLE
);
/

COMMENT ON TABLE "FORMULE_TEST_INTERVENANT" IS 'sequence=FTEST_INTERVENANT_ID_SEQ;';
/

CREATE TABLE "FORMULE_TEST_STRUCTURE"
(	"ID" NUMBER(*,0) NOT NULL ENABLE,
   "LIBELLE" VARCHAR2(80 CHAR) NOT NULL ENABLE,
   "UNIVERSITE" NUMBER(1,0) DEFAULT 0 NOT NULL ENABLE
);
/

COMMENT ON TABLE "FORMULE_TEST_STRUCTURE" IS 'sequence=FTEST_STRUCTURE_ID_SEQ;';
/

CREATE TABLE "FORMULE_TEST_VOLUME_HORAIRE"
(	"ID" NUMBER(*,0) NOT NULL ENABLE,
   "INTERVENANT_TEST_ID" NUMBER(*,0) NOT NULL ENABLE,
   "STRUCTURE_TEST_ID" NUMBER(*,0) NOT NULL ENABLE,
   "REFERENTIEL" NUMBER(1,0) DEFAULT 0 NOT NULL ENABLE,
   "SERVICE_STATUTAIRE" NUMBER(1,0) DEFAULT 1 NOT NULL ENABLE,
   "TAUX_FI" FLOAT(126) DEFAULT 1 NOT NULL ENABLE,
   "TAUX_FA" FLOAT(126) DEFAULT 0 NOT NULL ENABLE,
   "TAUX_FC" FLOAT(126) DEFAULT 0 NOT NULL ENABLE,
   "TYPE_INTERVENTION_CODE" VARCHAR2(15 CHAR),
   "PONDERATION_SERVICE_DU" FLOAT(126) DEFAULT 1 NOT NULL ENABLE,
   "PONDERATION_SERVICE_COMPL" FLOAT(126) DEFAULT 1 NOT NULL ENABLE,
   "PARAM_1" VARCHAR2(100 CHAR),
   "PARAM_2" VARCHAR2(100 CHAR),
   "PARAM_3" VARCHAR2(100 CHAR),
   "PARAM_4" VARCHAR2(100 CHAR),
   "PARAM_5" VARCHAR2(100 CHAR),
   "HEURES" FLOAT(126) DEFAULT 0 NOT NULL ENABLE,
   "A_SERVICE_FI" FLOAT(126),
   "A_SERVICE_FA" FLOAT(126),
   "A_SERVICE_FC" FLOAT(126),
   "A_SERVICE_REFERENTIEL" FLOAT(126),
   "A_HEURES_COMPL_FI" FLOAT(126),
   "A_HEURES_COMPL_FA" FLOAT(126),
   "A_HEURES_COMPL_FC" FLOAT(126),
   "A_HEURES_COMPL_FC_MAJOREES" FLOAT(126),
   "A_HEURES_COMPL_REFERENTIEL" FLOAT(126),
   "C_SERVICE_FI" FLOAT(126),
   "C_SERVICE_FA" FLOAT(126),
   "C_SERVICE_FC" FLOAT(126),
   "C_SERVICE_REFERENTIEL" FLOAT(126),
   "C_HEURES_COMPL_FI" FLOAT(126),
   "C_HEURES_COMPL_FA" FLOAT(126),
   "C_HEURES_COMPL_FC" FLOAT(126),
   "C_HEURES_COMPL_FC_MAJOREES" FLOAT(126),
   "C_HEURES_COMPL_REFERENTIEL" FLOAT(126),
   "DEBUG_INFO" CLOB
);
/

COMMENT ON TABLE "FORMULE_TEST_VOLUME_HORAIRE" IS 'sequence=FTEST_VOLUME_HORAIRE_ID_SEQ;';
/

CREATE TABLE "LISTE_NOIRE"
(	"CODE" VARCHAR2(50 CHAR) NOT NULL ENABLE
);
/




--------------------------------------------------
-- Création des définitions de packages
--------------------------------------------------

CREATE OR REPLACE PACKAGE "FORMULE_MONTPELLIER" AS

  PROCEDURE CALCUL_RESULTAT;

  FUNCTION calcCell( c VARCHAR2, l NUMERIC ) RETURN FLOAT;

END FORMULE_MONTPELLIER;
/

CREATE OR REPLACE PACKAGE "FORMULE_ULHN" AS

  PROCEDURE CALCUL_RESULTAT;

  FUNCTION calcCell( c VARCHAR2, l NUMERIC ) RETURN FLOAT;

END FORMULE_ULHN;
/

CREATE OR REPLACE PACKAGE "FORMULE_NANTERRE" AS

  PROCEDURE CALCUL_RESULTAT;

  FUNCTION calcCell( c VARCHAR2, l NUMERIC ) RETURN FLOAT;

END FORMULE_NANTERRE;
/

CREATE OR REPLACE PACKAGE "FORMULE_UNICAEN" AS
  debug_enabled                BOOLEAN DEFAULT FALSE;
  debug_etat_volume_horaire_id NUMERIC DEFAULT 1;
  debug_volume_horaire_id      NUMERIC;
  debug_volume_horaire_ref_id  NUMERIC;

  PROCEDURE CALCUL_RESULTAT_V2;
  PROCEDURE CALCUL_RESULTAT;

  PROCEDURE PURGE_EM_NON_FC;

END FORMULE_UNICAEN;
/

CREATE OR REPLACE PACKAGE "FORMULE_UBO" AS

  PROCEDURE CALCUL_RESULTAT;

  FUNCTION calcCell( c VARCHAR2, l NUMERIC ) RETURN FLOAT;

END FORMULE_UBO;
/

CREATE OR REPLACE PACKAGE FORMULE_ENSICAEN AS

  PROCEDURE CALCUL_RESULTAT;

  FUNCTION calcCell( c VARCHAR2, l NUMERIC ) RETURN FLOAT;

END FORMULE_ENSICAEN;
/



--------------------------------------------------
-- Création des corps de packages
--------------------------------------------------

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

    feuille(c).cells(l).enCalcul := true;
    val := calcCell( c, l );
    IF debugActif THEN
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



    -- l = SI(L20+K21>service_du;service_du;L20+K21)
    WHEN c = 'l' AND v >= 1 THEN
      IF l < 1 THEN
        RETURN 0;
      END IF;
      IF cell('l', l-1) + cell('k',l) > ose_formule.intervenant.service_du THEN
        RETURN ose_formule.intervenant.service_du;
      ELSE
        RETURN cell('l', l-1) + cell('k',l);
      END IF;



    -- m = SI(J21>0;SI(L20+K21<service_du;0;((L20+K21)-service_du)/J21);0)
    WHEN c = 'm' AND v >= 1 THEN
      IF cell('j',l) > 0 THEN
        IF cell('l',l-1) + cell('k',l) < ose_formule.intervenant.service_du THEN
          RETURN 0;
        ELSE
          RETURN (cell('l',l-1) + cell('k',l) - ose_formule.intervenant.service_du) / cell('j',l);
        END IF;
      ELSE
        RETURN 0;
      END IF;



    -- n = SI(ESTVIDE(C21);0;RECHERCHEH(C21;types_intervention;3;0))
    WHEN c = 'n' AND v >= 1 THEN
      RETURN vh.taux_service_compl * vh.ponderation_service_compl;



    -- o = SI(OU(service_realise<service_du;HC_autorisees<>"Oui");0;(M21+SI(H21<>"Oui";I21;0))*N21)
    -- service_realise = MAX($L$21:$L$50)
    -- service_du = ose_formule.intervenant.service_du
    -- HC_autorisees = ose_formule.intervenant.depassement_service_du_sans_hc = false
    WHEN c = 'o' AND v >= 1 THEN
      IF (calcFnc('max','l') < ose_formule.intervenant.service_du) OR ose_formule.intervenant.depassement_service_du_sans_hc THEN
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



    -- s136 =SI(OU(HC=0;R136<service_du);0;SI(pour_les_autres_composantes=0;HC;SI((HC_Budg-pour_les_autres_composantes)<(HC_Budg*(R136-service_du)/R132);HC_Budg*(R136-service_du)/R132;HC_Budg-pour_les_autres_composantes)))
    -- pour_les_autres_composantes = R137
    WHEN c = 's136' AND v >= 1 THEN
      IF calcFnc('total','o') = 0 OR cell('r136') < ose_formule.intervenant.service_du THEN
        RETURN 0;
      ELSE
        -- SI(R137=0;HC;SI((HC_Budg-R137)<(HC_Budg*(R136-service_du)/R132);HC_Budg*(R136-service_du)/R132;HC_Budg-R137))
        IF cell('r137') = 0 THEN
          RETURN calcFnc('total','o');
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



    -- u =SI(OU(ESTVIDE($C21);$C21="Référentiel";ET(HC=0;H21="Non"));0;SI(H21="Non";O21*$D21;SI($M21>0;(($M21*$N21)+($I21-$M21)*J21)*$D21;$K21*$D21)))
    WHEN c = 'u' AND v >= 1 THEN
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



    -- v =SI(OU(ESTVIDE($C21);$C21="Référentiel";ET(HC=0;H21="Non"));0;SI(H21="Non";O21*$E21;SI($M21>0;(($M21*$N21)+($I21-$M21)*K21)*$E21;$K21*$E21)))
    -- HC = calcFnc('total','o')
    -- H21="Non" = NOT vh.service_statutaire
    -- P21 = O21!!
    WHEN c = 'v' AND v >= 1 THEN
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
            -- (($M21*$N21)+($I21-$M21)*K21)*$E21
            RETURN ((cell('m',l)*cell('n',l))+(vh.heures-cell('m',l))*cell('k',l))*vh.taux_fa;
          ELSE
            -- $K21*$E21
            RETURN cell('k',l) * vh.taux_fa;
          END IF;
        END IF;
      END IF;



    -- w =SI(OU(ESTVIDE($C21);$C21="Référentiel";ET(HC=0;H21="Non"));0;SI(H21="Non";O21*$F21;SI($M21>0;(($M21*$N21)+($I21-$M21)*L21)*$F21;$K21*$F21)))
    WHEN c = 'w' AND v >= 1 THEN
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
            RETURN ((cell('m',l)*cell('n',l))+(vh.heures-cell('m',l))*cell('l',l))*vh.taux_fc;
          ELSE
            -- $K21*$F21
            RETURN cell('k',l) * vh.taux_fc;
          END IF;
        END IF;
      END IF;



    -- x =SI($C21="Référentiel";$K21-$M21;0)
    -- x =SI(OU(ESTVIDE($C21);NON(C21="Référentiel");ET(HC=0;H21="Non"));0;SI(H21="Non";O21;SI($M21>0;(($M21*$N21)+($I21-$M21)*J21);$K21)))
    WHEN c = 'x' AND v >= 1 THEN
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



    -- y =SI($C21="Référentiel";0;$S21)
    WHEN c = 'y' AND v >= 1 THEN
      IF vh.volume_horaire_id IS NOT NULL THEN
        RETURN cell('s',l);
      ELSE
        RETURN 0;
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



    -- ac =SI($C21="Référentiel";$S21;0)
    WHEN c = 'ac' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN cell('s',l);
      ELSE
        RETURN 0;
      END IF;





    -- hc_budg =HC-SOMME.SI(H$21:H$131;"Non";O$21:O$131)
    WHEN c = 'hc_budg_cell' AND v >= 1 THEN
      IF NOT vh.service_statutaire THEN
        RETURN 0;
      ELSE
        RETURN cell('o', l);
      END IF;

    WHEN c = 'hc_budg' AND v >= 1 THEN
      RETURN calcFnc('total', 'hc_budg_cell');



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
      ose_formule.volumes_horaires.items(l).heures_compl_fc_majorees := mainCell('Heures compl. FC Maj.', 'ab',l);
      ose_formule.volumes_horaires.items(l).heures_compl_referentiel := mainCell('Heures compl. référentiel', 'ac',l);
    END LOOP;
  END;

END FORMULE_MONTPELLIER;
/

CREATE OR REPLACE PACKAGE BODY "FORMULE_ULHN" AS
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

    feuille(c).cells(l).enCalcul := true;
    val := calcCell( c, l );
    IF debugActif THEN
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


      -- j=SI(ESTVIDE(C22);0;RECHERCHEH(SI(ET(C22="TP";TP_vaut_TD="Oui");"TD";C22);types_intervention;2;0))
      --  = RECHERCHEH(SI(ET(C22="TP";TP_vaut_TD="Oui");"TD";C22);types_intervention;2;0)
      WHEN c = 'j' AND v >= 1 THEN
        RETURN GREATEST(vh.taux_service_du * vh.ponderation_service_du,1);

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


      -- l82=SOMME(L22:L81)
      WHEN c = 'l82' AND v >= 1 THEN
        RETURN calcFnc('total','l');

      -- n=SI($L$82>0;L22/$L$82;0)
      WHEN c = 'n' AND v >= 1 THEN
        IF cell('l82') > 0 THEN
          RETURN cell('l',l) / cell('l82');
        ELSE
          RETURN 0;
        END IF;

      -- o=MIN(service_du;$L$82)*N22
      WHEN c = 'o' AND v >= 1 THEN
        RETURN LEAST(ose_formule.intervenant.service_du, cell('l82')) * cell('n',l);

      -- p=SI(L22<>0;O22/L22;0)
      WHEN c = 'p' AND v >= 1 THEN
        IF cell('l',l) <> 0 THEN
          RETURN cell('o',l) / cell('l',l);
        ELSE
          RETURN 0;
        END IF;

      -- q=SI($L$82>service_du;1-P22;0)
      WHEN c = 'q' AND v >= 1 THEN
        IF cell('l82') > ose_formule.intervenant.service_du THEN
          RETURN 1 - cell('p',l);
        ELSE
          RETURN 0;
        END IF;

      -- r=I22*Q22*K22
      WHEN c = 'r' AND v >= 1 THEN
        RETURN vh.heures * cell('q',l) * cell('k',l);

      -- t=SI(T21+L22>service_du;service_du;T21+L22)
      WHEN c = 't' AND l = 0 AND v >= 1 THEN
        RETURN 0;

      WHEN c = 't' AND v >= 1 THEN
        IF (cell('t',l-1) + cell('l',l)) > ose_formule.intervenant.service_du THEN
          RETURN ose_formule.intervenant.service_du;
        ELSE
          RETURN cell('t',l-1) + cell('l',l);
        END IF;

      -- u=SI(J22>0;SI(T21+L22<service_du;0;((T21+L22)-service_du)/J22);0)
      WHEN c = 'u' AND v >= 1 THEN
        IF cell('j',l) > 0 THEN
          IF (cell('t',l-1) + cell('l',l)) < ose_formule.intervenant.service_du THEN
            RETURN 0;
          ELSE
            RETURN (cell('t',l-1) + cell('l',l) - ose_formule.intervenant.service_du) / cell('j',l);
          END IF;
        ELSE
          RETURN 0;
        END IF;

      -- v=SI(OU(service_realise<service_du;HC_autorisees<>"Oui");0;(U22+SI(H22<>"Oui";I22;0))*K22)
      WHEN c = 'v' AND v >= 1 THEN
        IF cell('service_realise') < ose_formule.intervenant.service_du OR ose_formule.intervenant.depassement_service_du_sans_hc THEN
          RETURN 0;
        ELSE
          --(U22+SI(H22<>"Oui";I22;0))*K22
          RETURN (cell('u',l) + CASE WHEN NOT vh.service_statutaire THEN cell('i',l) ELSE 0 END ) * cell('k',l);
        END IF;

      -- x=SI(OU(ESTVIDE($C22);$C22="Référentiel");0;SI(contexte_calcul="Réalisé";($L22-$U22)*D22;$O22*D22))
      WHEN c = 'x' AND v >= 1 THEN
        IF vh.service_referentiel_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          IF ose_formule.intervenant.type_volume_horaire_id = 2 THEN
            RETURN (cell('l',l) - cell('u',l)) * vh.taux_fi;
          ELSE
            RETURN cell('o',l) * vh.taux_fi;
          END IF;
        END IF;

      -- y=SI(OU(ESTVIDE($C22);$C22="Référentiel");0;SI(contexte_calcul="Réalisé";($L22-$U22)*E22;$O22*E22))
      WHEN c = 'y' AND v >= 1 THEN
        IF vh.service_referentiel_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          IF ose_formule.intervenant.type_volume_horaire_id = 2 THEN
            RETURN (cell('l',l) - cell('u',l)) * vh.taux_fa;
          ELSE
            RETURN cell('o',l) * vh.taux_fa;
          END IF;
        END IF;

      -- z=SI(OU(ESTVIDE($C22);$C22="Référentiel");0;SI(contexte_calcul="Réalisé";($L22-$U22)*F22;$O22*F22))
      WHEN c = 'z' AND v >= 1 THEN
        IF vh.service_referentiel_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          IF ose_formule.intervenant.type_volume_horaire_id = 2 THEN
            RETURN (cell('l',l) - cell('u',l)) * vh.taux_fc;
          ELSE
            RETURN cell('o',l) * vh.taux_fc;
          END IF;
        END IF;

      -- aa=SI($C22="Référentiel";SI(contexte_calcul="Réalisé";$L22-$U22;$R22);0)
      WHEN c = 'aa' AND v >= 1 THEN
        IF vh.service_referentiel_id IS NOT NULL THEN
          IF ose_formule.intervenant.type_volume_horaire_id = 2 THEN
            RETURN cell('l',l) - cell('u',l);
          ELSE
            RETURN cell('r',l);
          END IF;
        ELSE
          RETURN 0;
        END IF;

      -- ab=SI(OU(ESTVIDE($C22);$C22="Référentiel");0;SI(contexte_calcul="Réalisé";$V22*D22;$R22*D22))
      WHEN c = 'ab' AND v >= 1 THEN
        IF vh.service_referentiel_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          IF ose_formule.intervenant.type_volume_horaire_id = 2 THEN
            RETURN cell('v',l) * vh.taux_fi;
          ELSE
            RETURN cell('r',l) * vh.taux_fi;
          END IF;
        END IF;

      -- ac=SI(OU(ESTVIDE($C22);$C22="Référentiel");0;SI(contexte_calcul="Réalisé";$V22*E22;$R22*E22))
      WHEN c = 'ac' AND v >= 1 THEN
        IF vh.service_referentiel_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          IF ose_formule.intervenant.type_volume_horaire_id = 2 THEN
            RETURN cell('v',l) * vh.taux_fa;
          ELSE
            RETURN cell('r',l) * vh.taux_fa;
          END IF;
        END IF;

      -- ad=SI(OU(ESTVIDE($C22);$C22="Référentiel");0;SI(contexte_calcul="Réalisé";$V22*F22;$R22*F22))
      WHEN c = 'ad' AND v >= 1 THEN
        IF vh.service_referentiel_id IS NOT NULL THEN
          RETURN 0;
        ELSE
          IF ose_formule.intervenant.type_volume_horaire_id = 2 THEN
            RETURN cell('v',l) * vh.taux_fc;
          ELSE
            RETURN cell('r',l) * vh.taux_fc;
          END IF;
        END IF;

      -- ae=0
      WHEN c = 'ae' AND v >= 1 THEN
        RETURN 0;

      -- af=SI($C22="Référentiel";SI(contexte_calcul="Réalisé";$V22;$R22);0)
      WHEN c = 'af' AND v >= 1 THEN
        IF vh.service_referentiel_id IS NOT NULL THEN
          IF ose_formule.intervenant.type_volume_horaire_id = 2 THEN
            RETURN cell('v',l);
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
      ose_formule.volumes_horaires.items(l).service_fi               := mainCell('Service FI', 'x',l);
      ose_formule.volumes_horaires.items(l).service_fa               := mainCell('Service FA', 'y',l);
      ose_formule.volumes_horaires.items(l).service_fc               := mainCell('Service FC', 'z',l);
      ose_formule.volumes_horaires.items(l).service_referentiel      := mainCell('Service référentiel', 'aa',l);
      ose_formule.volumes_horaires.items(l).heures_compl_fi          := mainCell('Heures compl. FI', 'ab',l);
      ose_formule.volumes_horaires.items(l).heures_compl_fa          := mainCell('Heures compl. FA', 'ac',l);
      ose_formule.volumes_horaires.items(l).heures_compl_fc          := mainCell('Heures compl. FC', 'ad',l);
      ose_formule.volumes_horaires.items(l).heures_compl_fc_majorees := mainCell('Heures compl. FC Maj.', 'ae',l);
      ose_formule.volumes_horaires.items(l).heures_compl_referentiel := mainCell('Heures compl. référentiel', 'af',l);
    END LOOP;
  END;

END FORMULE_ULHN;
/

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

    feuille(c).cells(l).enCalcul := true;
    val := calcCell( c, l );
    IF debugActif THEN
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
        ose_test.echo (vh.param_1 ||  '-' || case when
                                                      COALESCE(vh.param_1,' ') NOT IN ('KE8','UP10')
                                                    then '1' else '0' end);

        IF vh.volume_horaire_ref_id IS NULL AND vh.structure_is_affectation AND notInStructs(vh.param_1) THEN
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
        IF vh.volume_horaire_ref_id IS NOT NULL AND vh.service_statutaire AND vh.structure_is_affectation AND notInStructs(vh.param_1) THEN
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
        IF vh.volume_horaire_ref_id IS NULL AND NOT vh.structure_is_affectation AND notInStructs(vh.param_1) THEN
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
        IF vh.volume_horaire_ref_id IS NOT NULL AND vh.service_statutaire AND NOT vh.structure_is_affectation AND notInStructs(vh.param_1) THEN
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
        IF vh.param_1 IN ('KE8','UP10') THEN
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
        RETURN cell('N', l) + cell('X', l) + cell('AH', l);



      -- BK=AM22+AR22
      WHEN c = 'BK' AND v >= 1 THEN
        RETURN cell('AM', l) + cell('AR', l);



      -- BL=AW22+BB22
      WHEN c = 'BL' AND v >= 1 THEN
        RETURN cell('AW', l) + cell('BB', l);



      -- BM=S22+AC22+BG22
      WHEN c = 'BM' AND v >= 1 THEN
        RETURN cell('S', l) + cell('AC', l) + cell('BG', l);



      -- BN=O22+Y22+AI22
      WHEN c = 'BN' AND v >= 1 THEN
        RETURN cell('O', l) + cell('Y', l) + cell('AI', l);



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
        RETURN cell('T', l) + cell('AD', l) + cell('BH', l);



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
      ose_formule.volumes_horaires.items(l).heures_compl_fc_majorees := mainCell('Heures compl. FC Maj.', 'BQ',l);
      ose_formule.volumes_horaires.items(l).heures_compl_referentiel := mainCell('Heures compl. référentiel', 'BR',l);
    END LOOP;
  END;

END FORMULE_NANTERRE;
/

CREATE OR REPLACE PACKAGE BODY "FORMULE_UNICAEN" AS

  /* Stockage des valeurs intermédiaires */
  TYPE t_valeurs IS TABLE OF FLOAT INDEX BY PLS_INTEGER;
  TYPE t_tableau IS RECORD (
    valeurs t_valeurs,
    total   FLOAT DEFAULT 0
    );
  TYPE t_tableaux       IS TABLE OF t_tableau INDEX BY PLS_INTEGER;
  TYPE t_tableau_config IS RECORD (
    tableau NUMERIC,
    version NUMERIC,
    referentiel BOOLEAN DEFAULT FALSE,
    setTotal BOOLEAN DEFAULT FALSE
    );
  TYPE t_tableaux_configs IS VARRAY(100) OF t_tableau_config;

  t                     t_tableaux;
  vh_index              NUMERIC;



  -- Crée une définition de tableau
  FUNCTION TC( tableau NUMERIC, version NUMERIC, options VARCHAR2 DEFAULT NULL) RETURN t_tableau_config IS
    tcRes t_tableau_config;
  BEGIN
    tcRes.tableau := tableau;
    tcRes.version := version;
    CASE
      WHEN options like '%t%' THEN tcRes.setTotal := TRUE;
      WHEN options like '%r%' THEN tcRes.referentiel := TRUE;
      ELSE RETURN tcRes;
      END CASE;

    RETURN tcRes;
  END;

  -- Setter d'une valeur intermédiaire au niveau case
  PROCEDURE SV( tableau NUMERIC, valeur FLOAT ) IS
  BEGIN
    t(tableau).valeurs(vh_index) := valeur;
    t(tableau).total             := t(tableau).total + valeur;
  END;

  -- Setter d'une valeur intermédiaire au niveau tableau
  PROCEDURE ST( tableau NUMERIC, valeur FLOAT ) IS
  BEGIN
    t(tableau).total      := valeur;
  END;

  -- Getter d'une valeur intermédiaire, au niveau case
  FUNCTION GV( tableau NUMERIC ) RETURN FLOAT IS
  BEGIN
    IF NOT t.exists(tableau) THEN RETURN 0; END IF;
    IF NOT t(tableau).valeurs.exists( vh_index ) THEN RETURN 0; END IF;
    RETURN t(tableau).valeurs( vh_index );
  END;

  -- Getter d'une valeur intermédiaire, au niveau tableau
  FUNCTION GT( tableau NUMERIC ) RETURN FLOAT IS
  BEGIN
    IF NOT t.exists(tableau) THEN RETURN 0; END IF;
    RETURN t(tableau).total;
  END;




  PROCEDURE DEBUG_VH IS
    tableau NUMERIC;
    vh ose_formule.t_volume_horaire;
  BEGIN
    IF NOT debug_enabled THEN RETURN; END IF;
    IF ose_formule.intervenant.etat_volume_horaire_id <> debug_etat_volume_horaire_id THEN RETURN; END IF;

    FOR i IN 1 .. ose_formule.volumes_horaires.length LOOP
      vh_index := i;
      vh := ose_formule.volumes_horaires.items(i);
      IF vh.volume_horaire_id = debug_volume_horaire_id OR vh.volume_horaire_ref_id = debug_volume_horaire_ref_id THEN
        ose_formule.DEBUG_INTERVENANT;
        ose_test.echo('');
        ose_test.echo('-- DEBUG DE VOLUME HORAIRE --');
        ose_test.echo('volume_horaire_id         = ' || vh.volume_horaire_id);
        ose_test.echo('volume_horaire_ref_id     = ' || vh.volume_horaire_ref_id);
        ose_test.echo('service_id                = ' || vh.service_id);
        ose_test.echo('service_referentiel_id    = ' || vh.service_referentiel_id);
        ose_test.echo('taux_fi                   = ' || vh.taux_fi);
        ose_test.echo('taux_fa                   = ' || vh.taux_fa);
        ose_test.echo('taux_fc                   = ' || vh.taux_fc);
        ose_test.echo('ponderation_service_du    = ' || vh.ponderation_service_du);
        ose_test.echo('ponderation_service_compl = ' || vh.ponderation_service_compl);
        ose_test.echo('structure_id              = ' || vh.structure_id);
        ose_test.echo('structure_is_affectation  = ' || CASE WHEN vh.structure_is_affectation THEN 'OUI' ELSE 'NON' END);
        ose_test.echo('structure_is_univ         = ' || CASE WHEN vh.structure_is_univ THEN 'OUI' ELSE 'NON' END);
        ose_test.echo('service_statutaire        = ' || CASE WHEN vh.service_statutaire THEN 'OUI' ELSE 'NON' END);
        ose_test.echo('heures                    = ' || vh.heures);
        ose_test.echo('taux_service_du           = ' || vh.taux_service_du);
        ose_test.echo('taux_service_compl        = ' || vh.taux_service_compl);

        tableau := t.FIRST;
        LOOP EXIT WHEN tableau IS NULL;
        IF gv(tableau) <> 0 OR gt(tableau) <> 0 THEN
          ose_test.echo('     t(' || LPAD(tableau,3,' ') || ') v=' || RPAD(round(gv(tableau),3),10,' ') || 't=' || round(gt(tableau),3));
        END IF;
        tableau := t.NEXT(tableau);
        END LOOP;

        ose_test.echo('service_fi                = ' || vh.service_fi);
        ose_test.echo('service_fa                = ' || vh.service_fa);
        ose_test.echo('service_fc                = ' || vh.service_fc);
        ose_test.echo('service_referentiel       = ' || vh.service_referentiel);
        ose_test.echo('heures_compl_fi           = ' || vh.heures_compl_fi);
        ose_test.echo('heures_compl_fa           = ' || vh.heures_compl_fa);
        ose_test.echo('heures_compl_fc           = ' || vh.heures_compl_fc);
        ose_test.echo('heures_compl_fc_majorees  = ' || vh.heures_compl_fc_majorees);
        ose_test.echo('heures_compl_referentiel  = ' || vh.heures_compl_referentiel);
        ose_test.echo('-- FIN DE DEBUG DE VOLUME HORAIRE --');
        ose_test.echo('');
      END IF;
    END LOOP;
  END;



  -- Formule de calcul définie par tableaux
  FUNCTION EXECFORMULE( tableau NUMERIC, version NUMERIC ) RETURN FLOAT IS
    vh ose_formule.t_volume_horaire;
    i  ose_formule.t_intervenant;
  BEGIN
    vh := ose_formule.volumes_horaires.items(vh_index);
    i := ose_formule.intervenant;
    CASE


      WHEN tableau = 11 AND version = 2 THEN
        IF vh.structure_is_affectation AND vh.taux_fc < 1 THEN
          RETURN vh.heures;
        ELSE
          RETURN 0;
        END IF;



      WHEN tableau = 11 AND version = 3 THEN
        IF vh.structure_is_affectation THEN
          RETURN vh.heures * (vh.taux_fi + vh.taux_fa);
        ELSE
          RETURN 0;
        END IF;



      WHEN tableau = 12 AND version = 2 THEN
        IF NOT vh.structure_is_affectation AND vh.taux_fc < 1 THEN
          RETURN vh.heures;
        ELSE
          RETURN 0;
        END IF;



      WHEN tableau = 12 AND version = 3 THEN
        IF NOT vh.structure_is_affectation THEN
          RETURN vh.heures * (vh.taux_fi + vh.taux_fa);
        ELSE
          RETURN 0;
        END IF;



      WHEN tableau = 13 AND version = 2 THEN
        IF vh.structure_is_affectation AND vh.taux_fc = 1 THEN
          RETURN vh.heures;
        ELSE
          RETURN 0;
        END IF;



      WHEN tableau = 13 AND version = 3 THEN
        IF vh.structure_is_affectation THEN
          RETURN vh.heures * vh.taux_fc;
        ELSE
          RETURN 0;
        END IF;



      WHEN tableau = 14 AND version = 2 THEN
        IF NOT vh.structure_is_affectation AND vh.taux_fc = 1 THEN
          RETURN vh.heures;
        ELSE
          RETURN 0;
        END IF;



      WHEN tableau = 14 AND version = 3 THEN
        IF NOT vh.structure_is_affectation THEN
          RETURN vh.heures * vh.taux_fc;
        ELSE
          RETURN 0;
        END IF;



      WHEN tableau = 15 AND version = 2 THEN
        IF vh.structure_is_affectation THEN
          RETURN vh.heures;
        ELSE
          RETURN 0;
        END IF;



      WHEN tableau = 16 AND version = 2 THEN
        IF NOT vh.structure_is_affectation AND NOT vh.structure_is_univ THEN
          RETURN vh.heures;
        ELSE
          RETURN 0;
        END IF;



      WHEN tableau = 17 AND version = 2 THEN
        IF vh.structure_is_univ THEN
          RETURN vh.heures;
        ELSE
          RETURN 0;
        END IF;



      WHEN tableau = 21 AND version = 2 THEN
        RETURN gv(11) * vh.taux_service_du;



      WHEN tableau = 22 AND version = 2 THEN
        RETURN gv(12) * vh.taux_service_du;



      WHEN tableau = 23 AND version = 2 THEN
        RETURN gv(13) * vh.taux_service_du;



      WHEN tableau = 24 AND version = 2 THEN
        RETURN gv(14) * vh.taux_service_du;



      WHEN tableau = 25 AND version = 2 THEN
        RETURN gv(15);



      WHEN tableau = 26 AND version = 2 THEN
        RETURN gv(16);



      WHEN tableau = 27 AND version = 2 THEN
        RETURN gv(17);



      WHEN tableau = 31 AND version = 2 THEN
        RETURN GREATEST( ose_formule.intervenant.service_du - gt(21), 0 );



      WHEN tableau = 32 AND version = 2 THEN
        RETURN GREATEST( gt(31) - gt(22), 0 );



      WHEN tableau = 33 AND version = 2 THEN
        RETURN GREATEST( gt(32) - gt(23), 0 );



      WHEN tableau = 34 AND version = 2 THEN
        RETURN GREATEST( gt(33) - gt(24), 0 );



      WHEN tableau = 35 AND version = 2 THEN
        RETURN GREATEST( gt(34) - gt(25), 0 );



      WHEN tableau = 36 AND version = 2 THEN
        RETURN GREATEST( gt(35) - gt(26), 0 );



      WHEN tableau = 37 AND version = 2 THEN
        RETURN GREATEST( gt(36) - gt(27), 0 );



      WHEN tableau = 41 AND version = 2 THEN
        IF gt(21) <> 0 THEN
          RETURN gv(21) / gt(21);
        ELSE
          RETURN 0;
        END IF;



      WHEN tableau = 42 AND version = 2 THEN
        IF gt(22) <> 0 THEN
          RETURN gv(22) / gt(22);
        ELSE
          RETURN 0;
        END IF;



      WHEN tableau = 43 AND version = 2 THEN
        IF gt(23) <> 0 THEN
          RETURN gv(23) / gt(23);
        ELSE
          RETURN 0;
        END IF;



      WHEN tableau = 44 AND version = 2 THEN
        IF gt(24) <> 0 THEN
          RETURN gv(24) / gt(24);
        ELSE
          RETURN 0;
        END IF;



      WHEN tableau = 45 AND version = 2 THEN
        IF gt(25) <> 0 THEN
          RETURN gv(25) / gt(25);
        ELSE
          RETURN 0;
        END IF;



      WHEN tableau = 46 AND version = 2 THEN
        IF gt(26) <> 0 THEN
          RETURN gv(26) / gt(26);
        ELSE
          RETURN 0;
        END IF;



      WHEN tableau = 47 AND version = 2 THEN
        IF gt(27) <> 0 THEN
          RETURN gv(27) / gt(27);
        ELSE
          RETURN 0;
        END IF;



      WHEN tableau = 51 AND version = 2 THEN
        RETURN LEAST( ose_formule.intervenant.service_du, gt(21) ) * gv(41);



      WHEN tableau = 52 AND version = 2 THEN
        RETURN LEAST( gt(31), gt(22) ) * gv(42);



      WHEN tableau = 53 AND version = 2 THEN
        RETURN LEAST( gt(32), gt(23) ) * gv(43);



      WHEN tableau = 54 AND version = 2 THEN
        RETURN LEAST( gt(33), gt(24) ) * gv(44);



      WHEN tableau = 55 AND version = 2 THEN
        RETURN LEAST( gt(34), gt(25) ) * gv(45);



      WHEN tableau = 56 AND version = 2 THEN
        RETURN LEAST( gt(35), gt(26) ) * gv(46);



      WHEN tableau = 57 AND version = 2 THEN
        RETURN LEAST( gt(36), gt(27) ) * gv(47);



      WHEN tableau = 61 AND version = 2 THEN
        RETURN gv(51) * vh.taux_fi;



      WHEN tableau = 61 AND version = 3 THEN
        IF vh.taux_fi + vh.taux_fa > 0 THEN
          RETURN gv(51) / (vh.taux_fi + vh.taux_fa) * vh.taux_fi;
        ELSE
          RETURN 0;
        END IF;



      WHEN tableau = 62 AND version = 2 THEN
        RETURN gv(52) * vh.taux_fi;



      WHEN tableau = 62 AND version = 3 THEN
        IF vh.taux_fi + vh.taux_fa > 0 THEN
          RETURN gv(52) / (vh.taux_fi + vh.taux_fa) * vh.taux_fi;
        ELSE
          RETURN 0;
        END IF;



      WHEN tableau = 71 AND version = 2 THEN
        RETURN gv(51) * vh.taux_fa;



      WHEN tableau = 71 AND version = 3 THEN
        IF vh.taux_fi + vh.taux_fa > 0 THEN
          RETURN gv(51) / (vh.taux_fi + vh.taux_fa) * vh.taux_fa;
        ELSE
          RETURN 0;
        END IF;



      WHEN tableau = 72 AND version = 2 THEN
        RETURN gv(52) * vh.taux_fa;



      WHEN tableau = 72 AND version = 3 THEN
        IF vh.taux_fi + vh.taux_fa > 0 THEN
          RETURN gv(52) / (vh.taux_fi + vh.taux_fa) * vh.taux_fa;
        ELSE
          RETURN 0;
        END IF;



      WHEN tableau = 81 AND version = 2 THEN
        RETURN gv(51) * vh.taux_fc;



      WHEN tableau = 82 AND version = 2 THEN
        RETURN gv(52) * vh.taux_fc;



      WHEN tableau = 83 AND version = 2 THEN
        RETURN gv(53) * vh.taux_fc;



      WHEN tableau = 83 AND version = 3 THEN
        RETURN gv(53);



      WHEN tableau = 84 AND version = 2 THEN
        RETURN gv(54) * vh.taux_fc;



      WHEN tableau = 84 AND version = 3 THEN
        RETURN gv(54);



      WHEN tableau = 91 AND version = 2 THEN
        IF gv(21) <> 0 THEN
          RETURN gv(51) / gv(21);
        ELSE
          RETURN 0;
        END IF;



      WHEN tableau = 92 AND version = 2 THEN
        IF gv(22) <> 0 THEN
          RETURN gv(52) / gv(22);
        ELSE
          RETURN 0;
        END IF;



      WHEN tableau = 93 AND version = 2 THEN
        IF gv(23) <> 0 THEN
          RETURN gv(53) / gv(23);
        ELSE
          RETURN 0;
        END IF;



      WHEN tableau = 94 AND version = 2 THEN
        IF gv(24) <> 0 THEN
          RETURN gv(54) / gv(24);
        ELSE
          RETURN 0;
        END IF;



      WHEN tableau = 95 AND version = 2 THEN
        IF gv(25) <> 0 THEN
          RETURN gv(55) / gv(25);
        ELSE
          RETURN 0;
        END IF;



      WHEN tableau = 96 AND version = 2 THEN
        IF gv(26) <> 0 THEN
          RETURN gv(56) / gv(26);
        ELSE
          RETURN 0;
        END IF;



      WHEN tableau = 97 AND version = 2 THEN
        IF gv(27) <> 0 THEN
          RETURN gv(57) / gv(27);
        ELSE
          RETURN 0;
        END IF;



      WHEN tableau = 101 AND version = 2 THEN
        IF gt(37) <> 0 THEN
          RETURN 0;
        ELSE
          RETURN 1 - gv(91);
        END IF;



      WHEN tableau = 102 AND version = 2 THEN
        IF gt(37) <> 0 THEN
          RETURN 0;
        ELSE
          RETURN 1 - gv(92);
        END IF;



      WHEN tableau = 103 AND version = 2 THEN
        IF gt(37) <> 0 THEN
          RETURN 0;
        ELSE
          RETURN 1 - gv(93);
        END IF;



      WHEN tableau = 104 AND version = 2 THEN
        IF gt(37) <> 0 THEN
          RETURN 0;
        ELSE
          RETURN 1 - gv(94);
        END IF;



      WHEN tableau = 105 AND version = 2 THEN
        IF gt(37) <> 0 THEN
          RETURN 0;
        ELSE
          RETURN 1 - gv(95);
        END IF;



      WHEN tableau = 106 AND version = 2 THEN
        IF gt(37) <> 0 THEN
          RETURN 0;
        ELSE
          RETURN 1 - gv(96);
        END IF;



      WHEN tableau = 107 AND version = 2 THEN
        IF gt(37) <> 0 THEN
          RETURN 0;
        ELSE
          RETURN 1 - gv(97);
        END IF;



      WHEN tableau = 111 AND version = 2 THEN
        RETURN gv(11) * vh.taux_service_compl * gv(101);



      WHEN tableau = 112 AND version = 2 THEN
        RETURN gv(12) * vh.taux_service_compl * gv(102);



      WHEN tableau = 113 AND version = 2 THEN
        RETURN gv(13) * vh.taux_service_compl * gv(103);



      WHEN tableau = 114 AND version = 2 THEN
        RETURN gv(14) * vh.taux_service_compl * gv(104);



      WHEN tableau = 115 AND version = 2 THEN
        RETURN gv(15) * gv(105);



      WHEN tableau = 116 AND version = 2 THEN
        RETURN gv(16) * gv(106);



      WHEN tableau = 117 AND version = 2 THEN
        RETURN gv(17) * gv(107);



      WHEN tableau = 123 AND version = 2 THEN
        IF vh.taux_fc = 1 THEN
          RETURN gv(113) * vh.ponderation_service_compl;
        ELSE
          RETURN gv(113);
        END IF;



      WHEN tableau = 123 AND version = 3 THEN
        IF vh.taux_fc > 0 THEN
          RETURN gv(113) * vh.ponderation_service_compl;
        ELSE
          RETURN gv(113);
        END IF;



      WHEN tableau = 124 AND version = 2 THEN
        IF vh.taux_fc = 1 THEN
          RETURN gv(114) * vh.ponderation_service_compl;
        ELSE
          RETURN gv(114);
        END IF;



      WHEN tableau = 124 AND version = 3 THEN
        IF vh.taux_fc > 0 THEN
          RETURN gv(114) * vh.ponderation_service_compl;
        ELSE
          RETURN gv(114);
        END IF;



      WHEN tableau = 131 AND version = 2 THEN
        RETURN gv(111) * vh.taux_fi;



      WHEN tableau = 131 AND version = 3 THEN
        IF vh.taux_fi + vh.taux_fa > 0 THEN
          RETURN gv(111) / (vh.taux_fi + vh.taux_fa) * vh.taux_fi;
        ELSE
          RETURN 0;
        END IF;



      WHEN tableau = 132 AND version = 2 THEN
        RETURN gv(112) * vh.taux_fi;



      WHEN tableau = 132 AND version = 3 THEN
        IF vh.taux_fi + vh.taux_fa > 0 THEN
          RETURN gv(112) / (vh.taux_fi + vh.taux_fa) * vh.taux_fi;
        ELSE
          RETURN 0;
        END IF;



      WHEN tableau = 141 AND version = 2 THEN
        RETURN gv(111) * vh.taux_fa;



      WHEN tableau = 141 AND version = 3 THEN
        IF vh.taux_fi + vh.taux_fa > 0 THEN
          RETURN gv(111) / (vh.taux_fi + vh.taux_fa) * vh.taux_fa;
        ELSE
          RETURN 0;
        END IF;



      WHEN tableau = 142 AND version = 2 THEN
        RETURN gv(112) * vh.taux_fa;



      WHEN tableau = 142 AND version = 3 THEN
        IF vh.taux_fi + vh.taux_fa > 0 THEN
          RETURN gv(112) / (vh.taux_fi + vh.taux_fa) * vh.taux_fa;
        ELSE
          RETURN 0;
        END IF;



      WHEN tableau = 151 AND version = 2 THEN
        RETURN gv(111) * vh.taux_fc;



      WHEN tableau = 152 AND version = 2 THEN
        RETURN gv(112) * vh.taux_fc;



      WHEN tableau = 153 AND version = 2 THEN
        IF gv(123) = gv(113) THEN
          RETURN gv(113) * vh.taux_fc;
        ELSE
          RETURN 0;
        END IF;



      WHEN tableau = 153 AND version = 3 THEN
        IF gv(123) = gv(113) THEN
          RETURN gv(113);
        ELSE
          RETURN 0;
        END IF;



      WHEN tableau = 154 AND version = 2 THEN
        IF gv(124) = gv(114) THEN
          RETURN gv(114) * vh.taux_fc;
        ELSE
          RETURN 0;
        END IF;



      WHEN tableau = 154 AND version = 3 THEN
        IF gv(124) = gv(114) THEN
          RETURN gv(114);
        ELSE
          RETURN 0;
        END IF;



      WHEN tableau = 163 AND version = 2 THEN
        IF gv(123) <> gv(113) THEN
          RETURN gv(123) * vh.taux_fc;
        ELSE
          RETURN 0;
        END IF;



      WHEN tableau = 163 AND version = 3 THEN
        IF gv(123) <> gv(113) THEN
          RETURN gv(123);
        ELSE
          RETURN 0;
        END IF;



      WHEN tableau = 164 AND version = 2 THEN
        IF gv(124) <> gv(114) THEN
          RETURN gv(124) * vh.taux_fc;
        ELSE
          RETURN 0;
        END IF;



      WHEN tableau = 164 AND version = 3 THEN
        IF gv(124) <> gv(114) THEN
          RETURN gv(124);
        ELSE
          RETURN 0;
        END IF;



      ELSE
        raise_application_error( -20001, 'Le tableau ' || tableau || ' version ' || version || ' n''existe pas!');
      END CASE; END;







  PROCEDURE CALCUL_RESULTAT_V2 IS
    tableaux       t_tableaux_configs;
    valeur         FLOAT;
  BEGIN

    -- Définition des tableaux à utiliser
    tableaux := t_tableaux_configs(
        tc( 11,2    ), tc( 12,2    ), tc( 13,2    ), tc( 14,2    ), tc( 15,2,'r' ), tc( 16,2,'r' ), tc( 17,2,'r' ),
        tc( 21,2    ), tc( 22,2    ), tc( 23,2    ), tc( 24,2    ), tc( 25,2,'r' ), tc( 26,2,'r' ), tc( 27,2,'r' ),
        tc( 31,2,'t'), tc( 32,2,'t'), tc( 33,2,'t'), tc( 34,2,'t'), tc( 35,2,'tr'), tc( 36,2,'tr'), tc( 37,2,'tr'),
        tc( 41,2    ), tc( 42,2    ), tc( 43,2    ), tc( 44,2    ), tc( 45,2,'r' ), tc( 46,2,'r' ), tc( 47,2,'r' ),
        tc( 51,2    ), tc( 52,2    ), tc( 53,2    ), tc( 54,2    ), tc( 55,2,'r' ), tc( 56,2,'r' ), tc( 57,2,'r' ),
        tc( 61,2    ), tc( 62,2    ),
        tc( 71,2    ), tc( 72,2    ),
        tc( 81,2    ), tc( 82,2    ), tc( 83,2    ), tc( 84,2    ),
        tc( 91,2    ), tc( 92,2    ), tc( 93,2    ), tc( 94,2    ), tc( 95,2,'r' ), tc( 96,2,'r' ), tc( 97,2,'r' ),
        tc(101,2    ), tc(102,2    ), tc(103,2    ), tc(104,2    ), tc(105,2,'r' ), tc(106,2,'r' ), tc(107,2,'r' ),
        tc(111,2    ), tc(112,2    ), tc(113,2    ), tc(114,2    ), tc(115,2,'r' ), tc(116,2,'r' ), tc(117,2,'r' ),
        tc(123,2    ), tc(124,2    ),
        tc(131,2    ), tc(132,2    ),
        tc(141,2    ), tc(142,2    ),
        tc(151,2    ), tc(152,2    ), tc(153,2    ), tc(154,2    ),
        tc(163,2    ), tc(164,2    )
      );

    -- calcul par tableau pour chaque volume horaire
    t.delete;
    FOR it IN tableaux.FIRST .. tableaux.LAST LOOP
      FOR ivh IN 1 .. ose_formule.volumes_horaires.length LOOP
        vh_index := ivh;
        IF
                ose_formule.volumes_horaires.items(ivh).service_id IS NOT NULL AND NOT tableaux(it).referentiel
            OR ose_formule.volumes_horaires.items(ivh).service_referentiel_id IS NOT NULL AND tableaux(it).referentiel
            OR tableaux(it).setTotal -- car on en a besoin tout le temps
        THEN
          valeur := EXECFORMULE(tableaux(it).tableau, tableaux(it).version);
          IF tableaux(it).setTotal THEN
            ST( tableaux(it).tableau, valeur );
          ELSE
            SV( tableaux(it).tableau, valeur );
          END IF;
        END IF;
      END LOOP;
    END LOOP;

    -- transmisssion des résultats aux volumes horaires et volumes horaires référentiel
    FOR i IN 1 .. ose_formule.volumes_horaires.length LOOP
      vh_index := i;
      IF ose_formule.volumes_horaires.items(i).service_id IS NOT NULL THEN
        ose_formule.volumes_horaires.items(i).service_fi               := gv( 61) + gv( 62);
        ose_formule.volumes_horaires.items(i).service_fa               := gv( 71) + gv( 72);
        ose_formule.volumes_horaires.items(i).service_fc               := gv( 81) + gv( 82) + gv( 83) + gv( 84);
        ose_formule.volumes_horaires.items(i).heures_compl_fi          := gv(131) + gv(132);
        ose_formule.volumes_horaires.items(i).heures_compl_fa          := gv(141) + gv(142);
        ose_formule.volumes_horaires.items(i).heures_compl_fc          := gv(151) + gv(152) + gv(153) + gv(154);
        ose_formule.volumes_horaires.items(i).heures_compl_fc_majorees :=                     gv(163) + gv(164);
      ELSIF ose_formule.volumes_horaires.items(i).service_referentiel_id IS NOT NULL THEN
        ose_formule.volumes_horaires.items(i).service_referentiel      := gv( 55) + gv( 56) + gv( 57);
        ose_formule.volumes_horaires.items(i).heures_compl_referentiel := gv(115) + gv(116) + gv(117);
      END IF;
    END LOOP;

    DEBUG_VH;
  END;



  PROCEDURE CALCUL_RESULTAT IS
    tableaux       t_tableaux_configs;
    valeur         FLOAT;
  BEGIN
    -- si l'année est antérieure à 2016/2017 alors on utilise la V2!!
    IF ose_formule.intervenant.annee_id < 2016 THEN
      CALCUL_RESULTAT_V2;
      RETURN;
    END IF;


    -- Définition des tableaux à utiliser
    tableaux := t_tableaux_configs(
        tc( 11,3    ), tc( 12,3    ), tc( 13,3    ), tc( 14,3    ), tc( 15,2,'r' ), tc( 16,2,'r' ), tc( 17,2,'r' ),
        tc( 21,2    ), tc( 22,2    ), tc( 23,2    ), tc( 24,2    ), tc( 25,2,'r' ), tc( 26,2,'r' ), tc( 27,2,'r' ),
        tc( 31,2,'t'), tc( 32,2,'t'), tc( 33,2,'t'), tc( 34,2,'t'), tc( 35,2,'tr'), tc( 36,2,'tr'), tc( 37,2,'tr'),
        tc( 41,2    ), tc( 42,2    ), tc( 43,2    ), tc( 44,2    ), tc( 45,2,'r' ), tc( 46,2,'r' ), tc( 47,2,'r' ),
        tc( 51,2    ), tc( 52,2    ), tc( 53,2    ), tc( 54,2    ), tc( 55,2,'r' ), tc( 56,2,'r' ), tc( 57,2,'r' ),
        tc( 61,3    ), tc( 62,3    ),
        tc( 71,3    ), tc( 72,3    ),
        tc( 83,3    ), tc( 84,3    ),
        tc( 91,2    ), tc( 92,2    ), tc( 93,2    ), tc( 94,2    ), tc( 95,2,'r' ), tc( 96,2,'r' ), tc( 97,2,'r' ),
        tc(101,2    ), tc(102,2    ), tc(103,2    ), tc(104,2    ), tc(105,2,'r' ), tc(106,2,'r' ), tc(107,2,'r' ),
        tc(111,2    ), tc(112,2    ), tc(113,2    ), tc(114,2    ), tc(115,2,'r' ), tc(116,2,'r' ), tc(117,2,'r' ),
        tc(123,3    ), tc(124,3    ),
        tc(131,3    ), tc(132,3    ),
        tc(141,3    ), tc(142,3    ),
        tc(153,3    ), tc(154,3    ),
        tc(163,3    ), tc(164,3    )
      );

    -- calcul par tableau pour chaque volume horaire
    t.delete;
    FOR it IN tableaux.FIRST .. tableaux.LAST LOOP
      FOR ivh IN 1 .. ose_formule.volumes_horaires.length LOOP
        vh_index := ivh;
        IF
                ose_formule.volumes_horaires.items(ivh).service_id IS NOT NULL AND NOT tableaux(it).referentiel
            OR ose_formule.volumes_horaires.items(ivh).service_referentiel_id IS NOT NULL AND tableaux(it).referentiel
            OR tableaux(it).setTotal -- car on en a besoin tout le temps
        THEN
          valeur := EXECFORMULE(tableaux(it).tableau, tableaux(it).version);
          IF tableaux(it).setTotal THEN
            ST( tableaux(it).tableau, valeur );
          ELSE
            SV( tableaux(it).tableau, valeur );
          END IF;
        END IF;
      END LOOP;
    END LOOP;

    -- transmission des résultats aux volumes horaires et volumes horaires référentiel
    FOR i IN 1 .. ose_formule.volumes_horaires.length LOOP
      vh_index := i;
      IF ose_formule.volumes_horaires.items(i).service_id IS NOT NULL THEN
        ose_formule.volumes_horaires.items(i).service_fi               := gv( 61) + gv( 62);
        ose_formule.volumes_horaires.items(i).service_fa               := gv( 71) + gv( 72);
        ose_formule.volumes_horaires.items(i).service_fc               := gv( 83) + gv( 84);
        ose_formule.volumes_horaires.items(i).heures_compl_fi          := gv(131) + gv(132);
        ose_formule.volumes_horaires.items(i).heures_compl_fa          := gv(141) + gv(142);
        ose_formule.volumes_horaires.items(i).heures_compl_fc          := gv(153) + gv(154);
        ose_formule.volumes_horaires.items(i).heures_compl_fc_majorees := gv(163) + gv(164);
      ELSIF ose_formule.volumes_horaires.items(i).service_referentiel_id IS NOT NULL THEN
        ose_formule.volumes_horaires.items(i).service_referentiel      := gv( 55) + gv( 56) + gv( 57);
        ose_formule.volumes_horaires.items(i).heures_compl_referentiel := gv(115) + gv(116) + gv(117);
      END IF;
    END LOOP;

    DEBUG_VH;
  END;



  PROCEDURE PURGE_EM_NON_FC IS
  BEGIN
    FOR em IN (
      SELECT
        em.id
      FROM
        ELEMENT_MODULATEUR em
          JOIN element_pedagogique ep ON ep.id = em.element_id AND ep.histo_destruction IS NULL
      WHERE
          em.histo_destruction IS NULL
        AND ep.taux_fc < 1
      ) LOOP
      UPDATE
        element_modulateur
      SET
        histo_destruction = SYSDATE,
        histo_destructeur_id = ose_parametre.get_ose_user
      WHERE
          id = em.id
      ;
    END LOOP;
  END;


END FORMULE_UNICAEN;
/


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

    feuille(c).cells(l).enCalcul := true;
    val := calcCell( c, l );
    IF debugActif THEN
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
      ose_formule.volumes_horaires.items(l).heures_compl_fc_majorees := mainCell('Heures compl. FC Maj.', 'xs',l);
      ose_formule.volumes_horaires.items(l).heures_compl_referentiel := mainCell('Heures compl. référentiel', 'ur',l);
    END LOOP;
  END;

END FORMULE_UBO;
/

create or replace PACKAGE BODY FORMULE_ENSICAEN AS
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

    feuille(c).cells(l).enCalcul := true;
    val := calcCell( c, l );
    IF debugActif THEN
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



      -- w =SI($C21="Référentiel";$R21;0)
      WHEN c = 'w' AND v >= 1 THEN
        IF vh.volume_horaire_ref_id IS NOT NULL THEN
          RETURN cell('r',l);
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
      ose_formule.volumes_horaires.items(l).heures_compl_fc_majorees := mainCell('Heures compl. FC Maj.', 'aa',l);
      ose_formule.volumes_horaires.items(l).heures_compl_referentiel := mainCell('Heures compl. référentiel', 'ab',l);
    END LOOP;
  END;

END FORMULE_ENSICAEN;
/



--------------------------------------------------
-- Création des indexes
--------------------------------------------------

CREATE UNIQUE INDEX FORMULE_PK ON FORMULE (ID);
/

CREATE UNIQUE INDEX FORMULE_TEST_INTERVENANT_PK ON FORMULE_TEST_INTERVENANT (ID);
/

CREATE UNIQUE INDEX FORMULE_TEST_STRUCTURE_PK ON FORMULE_TEST_STRUCTURE (ID);
/

CREATE UNIQUE INDEX FORMULE_TEST_STRUCTURE__UN ON FORMULE_TEST_STRUCTURE (LIBELLE);
/

CREATE UNIQUE INDEX FORMULE_TEST_VOLUME_HORAIRE_PK ON FORMULE_TEST_VOLUME_HORAIRE (ID);
/

CREATE UNIQUE INDEX FORMULE__UN ON FORMULE (LIBELLE);
/

CREATE UNIQUE INDEX INTERVENANT_LISTE_NOIRE_PK ON LISTE_NOIRE (CODE);
/




--------------------------------------------------
-- Création des clés primaires
--------------------------------------------------

ALTER TABLE FORMULE ADD CONSTRAINT FORMULE_PK PRIMARY KEY (ID) USING INDEX FORMULE_PK ENABLE;
/

ALTER TABLE FORMULE_TEST_INTERVENANT ADD CONSTRAINT FORMULE_TEST_INTERVENANT_PK PRIMARY KEY (ID) USING INDEX FORMULE_TEST_INTERVENANT_PK ENABLE;
/

ALTER TABLE FORMULE_TEST_STRUCTURE ADD CONSTRAINT FORMULE_TEST_STRUCTURE_PK PRIMARY KEY (ID) USING INDEX FORMULE_TEST_STRUCTURE_PK ENABLE;
/

ALTER TABLE FORMULE_TEST_VOLUME_HORAIRE ADD CONSTRAINT FORMULE_TEST_VOLUME_HORAIRE_PK PRIMARY KEY (ID) USING INDEX FORMULE_TEST_VOLUME_HORAIRE_PK ENABLE;
/




--------------------------------------------------
-- Création des clés étrangères
--------------------------------------------------

ALTER TABLE FORMULE_TEST_INTERVENANT ADD CONSTRAINT FTI_ANNEE_FK FOREIGN KEY (ANNEE_ID)
  REFERENCES ANNEE (ID) ON DELETE CASCADE ENABLE;
/

ALTER TABLE FORMULE_TEST_INTERVENANT ADD CONSTRAINT FTI_ETAT_VOLUME_HORAIRE_FK FOREIGN KEY (ETAT_VOLUME_HORAIRE_ID)
  REFERENCES ETAT_VOLUME_HORAIRE (ID) ON DELETE CASCADE ENABLE;
/

ALTER TABLE FORMULE_TEST_INTERVENANT ADD CONSTRAINT FTI_FORMULE_FK FOREIGN KEY (FORMULE_ID)
  REFERENCES FORMULE (ID) ON DELETE CASCADE ENABLE;
/

ALTER TABLE FORMULE_TEST_INTERVENANT ADD CONSTRAINT FTI_FORMULE_TEST_STRUCTURE_FK FOREIGN KEY (STRUCTURE_TEST_ID)
  REFERENCES FORMULE_TEST_STRUCTURE (ID) ON DELETE CASCADE ENABLE;
/

ALTER TABLE FORMULE_TEST_INTERVENANT ADD CONSTRAINT FTI_TYPE_INTERVENANT_FK FOREIGN KEY (TYPE_INTERVENANT_ID)
  REFERENCES TYPE_INTERVENANT (ID) ON DELETE CASCADE ENABLE;
/

ALTER TABLE FORMULE_TEST_INTERVENANT ADD CONSTRAINT FTI_TYPE_VOLUME_HORAIRE_FK FOREIGN KEY (TYPE_VOLUME_HORAIRE_ID)
  REFERENCES TYPE_VOLUME_HORAIRE (ID) ON DELETE CASCADE ENABLE;
/

ALTER TABLE FORMULE_TEST_VOLUME_HORAIRE ADD CONSTRAINT FTVH_FORMULE_TEST_INTERV_FK FOREIGN KEY (INTERVENANT_TEST_ID)
  REFERENCES FORMULE_TEST_INTERVENANT (ID) ON DELETE CASCADE ENABLE;
/

ALTER TABLE FORMULE_TEST_VOLUME_HORAIRE ADD CONSTRAINT FTVH_FORMULE_TEST_STRUCTURE_FK FOREIGN KEY (STRUCTURE_TEST_ID)
  REFERENCES FORMULE_TEST_STRUCTURE (ID) ON DELETE CASCADE ENABLE;
/




--------------------------------------------------
-- Création des contraintes d'unicité
--------------------------------------------------

ALTER TABLE FORMULE_TEST_STRUCTURE ADD CONSTRAINT FORMULE_TEST_STRUCTURE__UN UNIQUE (LIBELLE) USING INDEX FORMULE_TEST_STRUCTURE__UN ENABLE;
/

ALTER TABLE FORMULE ADD CONSTRAINT FORMULE__UN UNIQUE (LIBELLE) USING INDEX FORMULE__UN ENABLE;
/



--------------------------------------------------
-- Modification des tables
--------------------------------------------------

COMMENT ON TABLE "AFFECTATION_RECHERCHE" IS 'Un chercheur peut avoir plusieurs affectations de recherche';
/

ALTER TABLE "FICHIER" MODIFY ("CONTENU" NULL);
/

ALTER TABLE "SYNC_LOG" MODIFY ("DATE_SYNC" DATE);
/



--------------------------------------------------
-- Modification des packages
--------------------------------------------------

CREATE OR REPLACE PACKAGE "OSE_DIVERS" AS

  PROCEDURE CALCULER_TABLEAUX_BORD;

  FUNCTION GET_OSE_UTILISATEUR_ID RETURN NUMERIC;
  FUNCTION GET_OSE_SOURCE_ID RETURN NUMERIC;

  FUNCTION INTERVENANT_HAS_PRIVILEGE( intervenant_id NUMERIC, privilege_name VARCHAR2 ) RETURN NUMERIC;

  FUNCTION implode(i_query VARCHAR2, i_seperator VARCHAR2 DEFAULT ',') RETURN VARCHAR2;

  PROCEDURE intervenant_horodatage_service( INTERVENANT_ID NUMERIC, TYPE_VOLUME_HORAIRE_ID NUMERIC, REFERENTIEL NUMERIC, HISTO_MODIFICATEUR_ID NUMERIC, HISTO_MODIFICATION DATE );

  FUNCTION NIVEAU_FORMATION_ID_CALC( gtf_id NUMERIC, gtf_pertinence_niveau NUMERIC, niveau NUMERIC DEFAULT NULL ) RETURN NUMERIC;

  FUNCTION STR_REDUCE( str VARCHAR2 ) RETURN VARCHAR2;

  FUNCTION STR_FIND( haystack VARCHAR2, needle VARCHAR2 ) RETURN NUMERIC;

  FUNCTION LIKED( haystack VARCHAR2, needle VARCHAR2 ) RETURN NUMERIC;

  FUNCTION CALCUL_TAUX_FI( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT;

  FUNCTION CALCUL_TAUX_FC( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT;

  FUNCTION CALCUL_TAUX_FA( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT;

  PROCEDURE SYNC_LOG( msg VARCHAR2 );

  FUNCTION FORMATTED_RIB (bic VARCHAR2, iban VARCHAR2) RETURN VARCHAR2;

  FUNCTION FORMATTED_ADRESSE(
    no_voie                VARCHAR2,
    nom_voie               VARCHAR2,
    batiment               VARCHAR2,
    mention_complementaire VARCHAR2,
    localite               VARCHAR2,
    code_postal            VARCHAR2,
    ville                  VARCHAR2,
    pays_libelle           VARCHAR2)
  RETURN VARCHAR2;

  PROCEDURE CALCUL_FEUILLE_DE_ROUTE( CONDS VARCHAR2 );

  FUNCTION GET_TRIGGER_BODY( TRIGGER_NAME VARCHAR2 ) RETURN VARCHAR2;
END OSE_DIVERS;
/

create or replace PACKAGE BODY "OSE_DIVERS" AS
  OSE_UTILISATEUR_ID NUMERIC;
  OSE_SOURCE_ID NUMERIC;




  PROCEDURE CALCULER_TABLEAUX_BORD IS
  BEGIN
    FOR d IN (
      SELECT tbl_name
      FROM tbl
      WHERE tbl_name <> 'formule' -- TROP LONG !!
      ORDER BY ordre
    )
    LOOP
      UNICAEN_TBL.CALCULER(d.tbl_name);
      dbms_output.put_line('Calcul du tableau de bord "' || d.tbl_name || '" effectué');
      COMMIT;
    END LOOP;
  END;



  FUNCTION GET_OSE_UTILISATEUR_ID RETURN NUMERIC IS
  BEGIN
    IF OSE_DIVERS.OSE_UTILISATEUR_ID IS NULL THEN
      SELECT
        to_number(valeur) INTO OSE_DIVERS.OSE_UTILISATEUR_ID
      FROM
        parametre
      WHERE
        nom = 'oseuser';
    END IF;

    RETURN OSE_DIVERS.OSE_UTILISATEUR_ID;
  END;



  FUNCTION GET_OSE_SOURCE_ID RETURN NUMERIC IS
  BEGIN
    IF OSE_DIVERS.OSE_SOURCE_ID IS NULL THEN
      SELECT
        id INTO OSE_DIVERS.OSE_SOURCE_ID
      FROM
        source
      WHERE
        code = 'OSE';
    END IF;

    RETURN OSE_DIVERS.OSE_SOURCE_ID;
  END;



  FUNCTION INTERVENANT_HAS_PRIVILEGE( intervenant_id NUMERIC, privilege_name VARCHAR2 ) RETURN NUMERIC IS
    statut statut_intervenant%rowtype;
    itype  type_intervenant%rowtype;
    res NUMERIC;
  BEGIN
    res := 1;
    SELECT si.* INTO statut FROM statut_intervenant si JOIN intervenant i ON i.statut_id = si.id WHERE i.id = intervenant_id;
    SELECT ti.* INTO itype  FROM type_intervenant ti WHERE ti.id = statut.type_intervenant_id;

    /* DEPRECATED */
    IF 'saisie_service' = privilege_name THEN
      res := statut.peut_saisir_service;
      RETURN res;
    ELSIF 'saisie_service_exterieur' = privilege_name THEN
      --IF INTERVENANT_HAS_PRIVILEGE( intervenant_id, 'saisie_service' ) = 0 OR itype.code = 'E' THEN -- cascade
      IF itype.code = 'E' THEN
        res := 0;
      END IF;
      RETURN res;
    ELSIF 'saisie_service_referentiel' = privilege_name THEN
      IF itype.code = 'E' THEN
        res := 0;
      END IF;
      RETURN res;
    ELSIF 'saisie_service_referentiel_autre_structure' = privilege_name THEN
      res := 1;
      RETURN res;
    ELSIF 'saisie_motif_non_paiement' = privilege_name THEN
      res := statut.peut_saisir_motif_non_paiement;
      RETURN res;
    END IF;
    /* FIN DE DEPRECATED */

    SELECT
      count(*)
    INTO
      res
    FROM
      intervenant i
      JOIN statut_privilege sp ON sp.statut_id = i.statut_id
      JOIN privilege p ON p.id = sp.privilege_id
      JOIN categorie_privilege cp ON cp.id = p.categorie_id
    WHERE
      i.id = INTERVENANT_HAS_PRIVILEGE.intervenant_id
      AND cp.code || '-' || p.code = privilege_name;

    RETURN res;
  END;

  FUNCTION implode(i_query VARCHAR2, i_seperator VARCHAR2 DEFAULT ',') RETURN VARCHAR2 AS
    l_return CLOB:='';
    l_temp CLOB;
    TYPE r_cursor is REF CURSOR;
    rc r_cursor;
  BEGIN
    OPEN rc FOR i_query;
    LOOP
      FETCH rc INTO L_TEMP;
      EXIT WHEN RC%NOTFOUND;
      l_return:=l_return||L_TEMP||i_seperator;
    END LOOP;
    RETURN RTRIM(l_return,i_seperator);
  END;

  PROCEDURE intervenant_horodatage_service( INTERVENANT_ID NUMERIC, TYPE_VOLUME_HORAIRE_ID NUMERIC, REFERENTIEL NUMERIC, HISTO_MODIFICATEUR_ID NUMERIC, HISTO_MODIFICATION DATE ) AS
  BEGIN
    MERGE INTO histo_intervenant_service his USING dual ON (

            his.INTERVENANT_ID                = intervenant_horodatage_service.INTERVENANT_ID
        AND NVL(his.TYPE_VOLUME_HORAIRE_ID,0) = NVL(intervenant_horodatage_service.TYPE_VOLUME_HORAIRE_ID,0)
        AND his.REFERENTIEL                   = intervenant_horodatage_service.REFERENTIEL

      ) WHEN MATCHED THEN UPDATE SET

        HISTO_MODIFICATEUR_ID = intervenant_horodatage_service.HISTO_MODIFICATEUR_ID,
        HISTO_MODIFICATION = intervenant_horodatage_service.HISTO_MODIFICATION

      WHEN NOT MATCHED THEN INSERT (

        ID,
        INTERVENANT_ID,
        TYPE_VOLUME_HORAIRE_ID,
        REFERENTIEL,
        HISTO_MODIFICATEUR_ID,
        HISTO_MODIFICATION
      ) VALUES (
        HISTO_INTERVENANT_SERVI_ID_SEQ.NEXTVAL,
        intervenant_horodatage_service.INTERVENANT_ID,
        intervenant_horodatage_service.TYPE_VOLUME_HORAIRE_ID,
        intervenant_horodatage_service.REFERENTIEL,
        intervenant_horodatage_service.HISTO_MODIFICATEUR_ID,
        intervenant_horodatage_service.HISTO_MODIFICATION

      );
  END;


  FUNCTION NIVEAU_FORMATION_ID_CALC( gtf_id NUMERIC, gtf_pertinence_niveau NUMERIC, niveau NUMERIC DEFAULT NULL ) RETURN NUMERIC AS
  BEGIN
    IF 1 <> gtf_pertinence_niveau OR niveau IS NULL OR niveau < 1 OR gtf_id < 1 THEN RETURN NULL; END IF;
    RETURN gtf_id * 256 + niveau;
  END;

  FUNCTION STR_REDUCE( str VARCHAR2 ) RETURN VARCHAR2 IS
  BEGIN
    RETURN RTRIM(utl_raw.cast_to_varchar2((nlssort(str, 'nls_sort=binary_ai'))),CHR(0));
  END;

  FUNCTION STR_FIND( haystack VARCHAR2, needle VARCHAR2 ) RETURN NUMERIC IS
  BEGIN
    IF STR_REDUCE( haystack ) LIKE STR_REDUCE( '%' || needle || '%' ) THEN RETURN 1; END IF;
    RETURN 0;
  END;

  FUNCTION LIKED( haystack VARCHAR2, needle VARCHAR2 ) RETURN NUMERIC IS
  BEGIN
    RETURN CASE WHEN STR_REDUCE(haystack) LIKE STR_REDUCE(needle) THEN 1 ELSE 0 END;
  END;

  PROCEDURE DO_NOTHING IS
  BEGIN
    RETURN;
  END;

  PROCEDURE CALCUL_TAUX( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, r_fi OUT FLOAT, r_fc OUT FLOAT, r_fa OUT FLOAT, arrondi NUMERIC DEFAULT 15 ) IS
    nt FLOAT;
    bi FLOAT;
    bc FLOAT;
    ba FLOAT;
    reste FLOAT;
  BEGIN
    bi := eff_fi * fi;
    bc := eff_fc * fc;
    ba := eff_fa * fa;
    nt := bi + bc + ba;

    IF nt = 0 THEN -- au cas ou, alors on ne prend plus en compte les effectifs!!
      bi := fi;
      bc := fc;
      ba := fa;
      nt := bi + bc + ba;
    END IF;

    IF nt = 0 THEN -- toujours au cas ou...
      bi := 1;
      bc := 0;
      ba := 0;
      nt := bi + bc + ba;
    END IF;

    -- Calcul
    r_fi := bi / nt;
    r_fc := bc / nt;
    r_fa := ba / nt;

    -- Arrondis
    r_fi := ROUND( r_fi, arrondi );
    r_fc := ROUND( r_fc, arrondi );
    r_fa := ROUND( r_fa, arrondi );

    -- détermination du reste
    reste := 1 - r_fi - r_fc - r_fa;

    -- répartition éventuelle du reste
    IF reste <> 0 THEN
      IF r_fi > 0 THEN r_fi := r_fi + reste;
      ELSIF r_fc > 0 THEN r_fc := r_fc + reste;
      ELSE r_fa := r_fa + reste; END IF;
    END IF;

  END;


  FUNCTION CALCUL_TAUX_FI( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT IS
    ri FLOAT;
    rc FLOAT;
    ra FLOAT;
  BEGIN
    CALCUL_TAUX( eff_fi, eff_fc, eff_fa, fi, fc, fa, ri, rc, ra, arrondi );
    RETURN ri;
  END;

  FUNCTION CALCUL_TAUX_FC( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT IS
    ri FLOAT;
    rc FLOAT;
    ra FLOAT;
  BEGIN
    CALCUL_TAUX( eff_fi, eff_fc, eff_fa, fi, fc, fa, ri, rc, ra, arrondi );
    RETURN rc;
  END;

  FUNCTION CALCUL_TAUX_FA( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT IS
    ri FLOAT;
    rc FLOAT;
    ra FLOAT;
  BEGIN
    CALCUL_TAUX( eff_fi, eff_fc, eff_fa, fi, fc, fa, ri, rc, ra, arrondi );
    RETURN ra;
  END;

  PROCEDURE SYNC_LOG( msg VARCHAR2 ) IS
  BEGIN
    INSERT INTO SYNC_LOG( id, date_sync, message ) VALUES ( sync_log_id_seq.nextval, systimestamp, msg );
  END;

  FUNCTION FORMATTED_RIB (bic VARCHAR2, iban VARCHAR2) RETURN VARCHAR2 IS
  BEGIN
    if bic is null and iban is null then
      return null;
    end if;
    RETURN regexp_replace(bic, '[[:space:]]+', '') || '-' || regexp_replace(iban, '[[:space:]]+', '');
  END;

  FUNCTION FORMATTED_ADRESSE(
    no_voie                VARCHAR2,
    nom_voie               VARCHAR2,
    batiment               VARCHAR2,
    mention_complementaire VARCHAR2,
    localite               VARCHAR2,
    code_postal            VARCHAR2,
    ville                  VARCHAR2,
    pays_libelle           VARCHAR2)
    RETURN VARCHAR2
  IS
  BEGIN
    return
      -- concaténation des éléments non null séparés par ', '
      trim(trim(',' FROM REPLACE(', ' || NVL(no_voie,'#') || ', ' || NVL(nom_voie,'#') || ', ' || NVL(batiment,'#') || ', ' || NVL(mention_complementaire,'#'), ', #', ''))) ||
      -- saut de ligne complet
      chr(13) || chr(10) ||
      -- concaténation des éléments non null séparés par ', '
      trim(trim(',' FROM REPLACE(', ' || NVL(localite,'#') || ', ' || NVL(code_postal,'#') || ', ' || NVL(ville,'#') || ', ' || NVL(pays_libelle,'#'), ', #', '')));
  END;



  PROCEDURE CALCUL_FEUILLE_DE_ROUTE( CONDS VARCHAR2 ) IS
  BEGIN
    FOR d IN (
      SELECT   tbl_name
      FROM     tbl
      WHERE    feuille_de_route = 1
      ORDER BY ordre
    ) LOOP
      UNICAEN_TBL.CALCULER(d.tbl_name,CONDS);
    END LOOP;
  END;



  FUNCTION GET_TRIGGER_BODY( TRIGGER_NAME VARCHAR2 ) RETURN VARCHAR2 IS
    vlong long;
  BEGIN
    SELECT trigger_body INTO vlong FROM all_triggers WHERE trigger_name = GET_TRIGGER_BODY.TRIGGER_NAME;

    RETURN substr(vlong, 1, 32767);
  END;

END OSE_DIVERS;
/

CREATE OR REPLACE PACKAGE "OSE_EVENT" AS

  PROCEDURE ON_AFTER_FORMULE_CALC( INTERVENANT_ID NUMERIC );

END OSE_EVENT;
/

CREATE OR REPLACE PACKAGE BODY "OSE_EVENT" AS

  PROCEDURE ON_AFTER_FORMULE_CALC( INTERVENANT_ID NUMERIC ) IS
    p unicaen_tbl.t_params;
  BEGIN
    p := UNICAEN_TBL.make_params('INTERVENANT_ID', ON_AFTER_FORMULE_CALC.intervenant_id);
    /*
        UNICAEN_TBL.CALCULER( 'agrement', p );
        UNICAEN_TBL.CALCULER( 'paiement', p );
        UNICAEN_TBL.CALCULER( 'workflow', p );*/
  END;

END OSE_EVENT;
/

CREATE OR REPLACE PACKAGE "OSE_FORMULE" AS

  TYPE t_intervenant IS RECORD (
    -- identifiants
    id                             NUMERIC,
    annee_id                       NUMERIC,
    structure_id                   NUMERIC,
    type_volume_horaire_id         NUMERIC,
    etat_volume_horaire_id         NUMERIC,

    -- paramètres globaux
    heures_decharge                FLOAT DEFAULT 0,
    heures_service_statutaire      FLOAT DEFAULT 0,
    heures_service_modifie         FLOAT DEFAULT 0,
    depassement_service_du_sans_hc BOOLEAN DEFAULT FALSE,
    type_intervenant_code          VARCHAR(2),

    -- paramètres spacifiques
    param_1                        VARCHAR(100),
    param_2                        VARCHAR(100),
    param_3                        VARCHAR(100),
    param_4                        VARCHAR(100),
    param_5                        VARCHAR(100),

    -- résultats
    service_du                     FLOAT,
    debug_info                     CLOB
  );

  TYPE t_volume_horaire IS RECORD (
    -- identifiants
    volume_horaire_id          NUMERIC,
    volume_horaire_ref_id      NUMERIC,
    service_id                 NUMERIC,
    service_referentiel_id     NUMERIC,
    structure_id               NUMERIC,

    -- paramètres globaux
    structure_is_affectation   BOOLEAN DEFAULT TRUE,
    structure_is_univ          BOOLEAN DEFAULT FALSE,
    service_statutaire         BOOLEAN DEFAULT TRUE,
    taux_fi                    FLOAT DEFAULT 1,
    taux_fa                    FLOAT DEFAULT 0,
    taux_fc                    FLOAT DEFAULT 0,

    -- pondérations et heures
    type_intervention_code     VARCHAR(15),
    taux_service_du            FLOAT DEFAULT 1, -- en fonction des types d'intervention
    taux_service_compl         FLOAT DEFAULT 1, -- en fonction des types d'intervention
    ponderation_service_du     FLOAT DEFAULT 1, -- relatif aux modulateurs
    ponderation_service_compl  FLOAT DEFAULT 1, -- relatif aux modulateurs
    heures                     FLOAT DEFAULT 0, -- heures réelles saisies

    -- paramètres spacifiques
    param_1                    VARCHAR(100),
    param_2                    VARCHAR(100),
    param_3                    VARCHAR(100),
    param_4                    VARCHAR(100),
    param_5                    VARCHAR(100),

    -- résultats
    service_fi                 FLOAT DEFAULT 0,
    service_fa                 FLOAT DEFAULT 0,
    service_fc                 FLOAT DEFAULT 0,
    service_referentiel        FLOAT DEFAULT 0,
    heures_compl_fi            FLOAT DEFAULT 0,
    heures_compl_fa            FLOAT DEFAULT 0,
    heures_compl_fc            FLOAT DEFAULT 0,
    heures_compl_fc_majorees   FLOAT DEFAULT 0,
    heures_compl_referentiel   FLOAT DEFAULT 0,

    debug_info                 CLOB
  );
  TYPE t_lst_volume_horaire IS TABLE OF t_volume_horaire INDEX BY PLS_INTEGER;
  TYPE t_volumes_horaires IS RECORD (
    length NUMERIC DEFAULT 0,
    items t_lst_volume_horaire
  );

  intervenant      t_intervenant;
  volumes_horaires t_volumes_horaires;

  FUNCTION GET_INTERVENANT_ID RETURN NUMERIC;

  FUNCTION GET_TAUX_HORAIRE_HETD( DATE_OBS DATE DEFAULT NULL ) RETURN FLOAT;
  PROCEDURE UPDATE_ANNEE_TAUX_HETD;

  PROCEDURE CALCULER( INTERVENANT_ID NUMERIC );
  PROCEDURE CALCULER_TOUT( ANNEE_ID NUMERIC DEFAULT NULL );        -- mise à jour de TOUTES les données ! ! ! !
  PROCEDURE CALCULER_TBL( PARAMS UNICAEN_TBL.T_PARAMS );

  PROCEDURE TEST( INTERVENANT_TEST_ID NUMERIC );
  PROCEDURE TEST_TOUT;

  PROCEDURE DEBUG_INTERVENANT;
  PROCEDURE DEBUG_VOLUMES_HORAIRES(VOLUME_HORAIRE_ID NUMERIC DEFAULT NULL);
END OSE_FORMULE;
/

CREATE OR REPLACE PACKAGE BODY "OSE_FORMULE" AS

  TYPE t_lst_vh_etats        IS TABLE OF t_volumes_horaires INDEX BY PLS_INTEGER;
  TYPE t_lst_vh_types        IS TABLE OF t_lst_vh_etats INDEX BY PLS_INTEGER;
  TYPE t_lst_vh_intervenants IS TABLE OF t_lst_vh_types INDEX BY PLS_INTEGER;

  TYPE t_resultat IS RECORD (
    id                         NUMERIC,
    formule_resultat_id        NUMERIC,
    type_volume_horaire_id     NUMERIC,
    etat_volume_horaire_id     NUMERIC,
    service_id                 NUMERIC,
    service_referentiel_id     NUMERIC,
    volume_horaire_id          NUMERIC,
    volume_horaire_ref_id      NUMERIC,

    service_fi                 FLOAT DEFAULT 0,
    service_fa                 FLOAT DEFAULT 0,
    service_fc                 FLOAT DEFAULT 0,
    service_referentiel        FLOAT DEFAULT 0,
    heures_compl_fi            FLOAT DEFAULT 0,
    heures_compl_fa            FLOAT DEFAULT 0,
    heures_compl_fc            FLOAT DEFAULT 0,
    heures_compl_fc_majorees   FLOAT DEFAULT 0,
    heures_compl_referentiel   FLOAT DEFAULT 0,

    changed                    BOOLEAN DEFAULT FALSE,
    debug_info                 CLOB
  );

  TYPE t_resultats IS TABLE OF t_resultat INDEX BY VARCHAR2(15);

  all_volumes_horaires t_lst_vh_intervenants;
  arrondi NUMERIC DEFAULT 2;
  t_res t_resultats;
  formule_definition formule%rowtype;
  in_calculer_tout BOOLEAN DEFAULT false;



  FUNCTION GET_INTERVENANT_ID RETURN NUMERIC IS
  BEGIN
    RETURN intervenant.id;
  END;



  FUNCTION GET_TAUX_HORAIRE_HETD( DATE_OBS DATE DEFAULT NULL ) RETURN FLOAT IS
    taux_hetd FLOAT;
  BEGIN
    SELECT valeur INTO taux_hetd
    FROM taux_horaire_hetd t
    WHERE
      DATE_OBS BETWEEN t.histo_creation AND COALESCE(t.histo_destruction,GREATEST(SYSDATE,DATE_OBS))
      AND rownum = 1
    ORDER BY
      histo_creation DESC;
    RETURN taux_hetd;
  END;



  PROCEDURE UPDATE_ANNEE_TAUX_HETD IS
  BEGIN
    UPDATE annee SET taux_hetd = GET_TAUX_HORAIRE_HETD(date_fin);
  END;



  PROCEDURE LOAD_INTERVENANT_FROM_BDD IS
    dsdushc NUMERIC DEFAULT 0;
  BEGIN
    intervenant.service_du := 0;

    SELECT
      fi.intervenant_id,
      fi.annee_id,
      fi.structure_id,
      fi.type_intervenant_code,
      fi.heures_service_statutaire,
      fi.depassement_service_du_sans_hc,
      fi.heures_service_modifie,
      fi.heures_decharge,
      fli.param_1,
      fli.param_2,
      fli.param_3,
      fli.param_4,
      fli.param_5
    INTO
      intervenant.id,
      intervenant.annee_id,
      intervenant.structure_id,
      intervenant.type_intervenant_code,
      intervenant.heures_service_statutaire,
      dsdushc,
      intervenant.heures_service_modifie,
      intervenant.heures_decharge,
      intervenant.param_1,
      intervenant.param_2,
      intervenant.param_3,
      intervenant.param_4,
      intervenant.param_5
    FROM
      v_formule_intervenant fi
      LEFT JOIN v_formule_local_i_params fli ON fli.intervenant_id = fi.intervenant_id
    WHERE
      fi.intervenant_id = intervenant.id;

    intervenant.depassement_service_du_sans_hc := (dsdushc = 1);
    intervenant.service_du := CASE
      WHEN intervenant.depassement_service_du_sans_hc -- HC traitées comme du service
        OR intervenant.heures_decharge < 0 -- s'il y a une décharge => aucune HC

      THEN 9999
      ELSE intervenant.heures_service_statutaire + intervenant.heures_service_modifie
    END;

    EXCEPTION WHEN NO_DATA_FOUND THEN
      intervenant.id                             := NULL;
      intervenant.annee_id                       := null;
      intervenant.structure_id                   := null;
      intervenant.heures_service_statutaire      := 0;
      intervenant.depassement_service_du_sans_hc := FALSE;
      intervenant.heures_service_modifie         := 0;
      intervenant.heures_decharge                := 0;
      intervenant.type_intervenant_code          := 'E';
      intervenant.service_du                     := 0;
      intervenant.param_1                        := NULL;
      intervenant.param_2                        := NULL;
      intervenant.param_3                        := NULL;
      intervenant.param_4                        := NULL;
      intervenant.param_5                        := NULL;
  END;



  PROCEDURE LOAD_INTERVENANT_FROM_TEST IS
    dsdushc NUMERIC DEFAULT 0;
  BEGIN
    SELECT
      fti.id,
      fti.annee_id,
      fti.structure_test_id,
      fti.type_volume_horaire_id,
      fti.etat_volume_horaire_id,
      fti.heures_decharge,
      fti.heures_service_statutaire,
      fti.heures_service_modifie,
      fti.depassement_service_du_sans_hc,
      fti.a_service_du,
      fti.param_1,
      fti.param_2,
      fti.param_3,
      fti.param_4,
      fti.param_5,
      ti.code
    INTO
      intervenant.id,
      intervenant.annee_id,
      intervenant.structure_id,
      intervenant.type_volume_horaire_id,
      intervenant.etat_volume_horaire_id,
      intervenant.heures_decharge,
      intervenant.heures_service_statutaire,
      intervenant.heures_service_modifie,
      dsdushc,
      intervenant.service_du,
      intervenant.param_1,
      intervenant.param_2,
      intervenant.param_3,
      intervenant.param_4,
      intervenant.param_5,
      intervenant.type_intervenant_code
    FROM
      formule_test_intervenant fti
      JOIN type_intervenant ti ON ti.id = fti.type_intervenant_id
    WHERE
      fti.id = intervenant.id;

    intervenant.depassement_service_du_sans_hc := (dsdushc = 1);
    intervenant.service_du := CASE
      WHEN intervenant.depassement_service_du_sans_hc -- HC traitées comme du service
        OR intervenant.heures_decharge < 0 -- s'il y a une décharge => aucune HC

      THEN 9999
      ELSE intervenant.heures_service_statutaire + intervenant.heures_service_modifie
    END;

    EXCEPTION WHEN NO_DATA_FOUND THEN
      intervenant.id                             := NULL;
      intervenant.annee_id                       := null;
      intervenant.structure_id                   := null;
      intervenant.heures_service_statutaire      := 0;
      intervenant.depassement_service_du_sans_hc := FALSE;
      intervenant.heures_service_modifie         := 0;
      intervenant.heures_decharge                := 0;
      intervenant.type_intervenant_code          := 'E';
      intervenant.service_du                     := 0;
      intervenant.param_1                        := null;
      intervenant.param_2                        := null;
      intervenant.param_3                        := null;
      intervenant.param_4                        := null;
      intervenant.param_5                        := null;
  END;



  PROCEDURE LOAD_VH_FROM_BDD IS
    vh t_volume_horaire;
    etat_volume_horaire_id NUMERIC DEFAULT 1;
    structure_univ NUMERIC;
    length NUMERIC;
  BEGIN
    all_volumes_horaires.delete;

    SELECT to_number(valeur) INTO structure_univ FROM parametre WHERE nom = 'structure_univ';

    FOR d IN (
      SELECT
        fvh.*, flvh.param_1, flvh.param_2, flvh.param_3, flvh.param_4, flvh.param_5
      FROM
        v_formule_volume_horaire fvh
        LEFT JOIN v_formule_local_vh_params flvh ON flvh.volume_horaire_id = COALESCE(fvh.volume_horaire_id,0) AND flvh.volume_horaire_ref_id = COALESCE(fvh.volume_horaire_ref_id,0)
      ORDER BY
        ordre
    ) LOOP
      vh.volume_horaire_id         := d.volume_horaire_id;
      vh.volume_horaire_ref_id     := d.volume_horaire_ref_id;
      vh.service_id                := d.service_id;
      vh.service_referentiel_id    := d.service_referentiel_id;
      vh.taux_fi                   := d.taux_fi;
      vh.taux_fa                   := d.taux_fa;
      vh.taux_fc                   := d.taux_fc;
      vh.ponderation_service_du    := d.ponderation_service_du;
      vh.ponderation_service_compl := d.ponderation_service_compl;
      vh.structure_id              := d.structure_id;
      vh.structure_is_affectation  := NVL(d.structure_id,0) = NVL(intervenant.structure_id,-1);
      vh.structure_is_univ         := NVL(d.structure_id,0) = NVL(structure_univ,-1);
      vh.service_statutaire        := d.service_statutaire = 1;
      vh.heures                    := d.heures;
      vh.type_intervention_code    := d.type_intervention_code;
      vh.taux_service_du           := d.taux_service_du;
      vh.taux_service_compl        := d.taux_service_compl;
      vh.param_1                   := d.param_1;
      vh.param_2                   := d.param_2;
      vh.param_3                   := d.param_3;
      vh.param_4                   := d.param_4;
      vh.param_5                   := d.param_5;

      FOR etat_volume_horaire_id IN 1 .. d.etat_volume_horaire_id LOOP
        BEGIN
          length := all_volumes_horaires(d.intervenant_id)(d.type_volume_horaire_id)(etat_volume_horaire_id).length;
        EXCEPTION WHEN NO_DATA_FOUND THEN
          length := 0;
        END;
        length := length + 1;
        all_volumes_horaires(d.intervenant_id)(d.type_volume_horaire_id)(etat_volume_horaire_id).length := length;
        all_volumes_horaires(d.intervenant_id)(d.type_volume_horaire_id)(etat_volume_horaire_id).items(length) := vh;
      END LOOP;
    END LOOP;
  END;



  PROCEDURE LOAD_VH_FROM_TEST IS
    vh t_volume_horaire;
    etat_volume_horaire_id NUMERIC DEFAULT 1;
    structure_univ NUMERIC;
    length NUMERIC;
  BEGIN
    volumes_horaires.items.delete;
    length := 0;

    SELECT id INTO structure_univ FROM formule_test_structure WHERE universite = 1;

    FOR d IN (
      SELECT
        ftvh.*,
        CASE ftvh.type_intervention_code
          WHEN 'CM' THEN COALESCE(fti.taux_cm_service_du,1.5)
          WHEN 'TP' THEN COALESCE(fti.taux_tp_service_du,1)
          WHEN 'AUTRE' THEN COALESCE(fti.taux_autre_service_du,1)
          ELSE 1
        END taux_service_du,
        CASE ftvh.type_intervention_code
          WHEN 'CM' THEN COALESCE(fti.taux_cm_service_compl,1.5)
          WHEN 'TP' THEN COALESCE(fti.taux_tp_service_compl,2/3)
          WHEN 'AUTRE' THEN COALESCE(fti.taux_autre_service_compl,1)
          ELSE 1
        END taux_service_compl
      FROM
        formule_test_volume_horaire ftvh
        JOIN formule_test_intervenant fti ON fti.id = intervenant.id
      WHERE  ftvh.intervenant_test_id = intervenant.id
      ORDER BY ftvh.id
    ) LOOP
      length := length + 1;
      volumes_horaires.length := length;

      IF d.referentiel = 0 THEN
        volumes_horaires.items(length).volume_horaire_id       := d.id;
        volumes_horaires.items(length).service_id              := d.id;
      ELSE
        volumes_horaires.items(length).volume_horaire_ref_id   := d.id;
        volumes_horaires.items(length).service_referentiel_id  := d.id;
      END IF;
      volumes_horaires.items(length).taux_fi                   := d.taux_fi;
      volumes_horaires.items(length).taux_fa                   := d.taux_fa;
      volumes_horaires.items(length).taux_fc                   := d.taux_fc;
      volumes_horaires.items(length).ponderation_service_du    := d.ponderation_service_du;
      volumes_horaires.items(length).ponderation_service_compl := d.ponderation_service_compl;
      volumes_horaires.items(length).structure_id              := d.structure_test_id;
      volumes_horaires.items(length).structure_is_affectation  := NVL(d.structure_test_id,0) = NVL(intervenant.structure_id,-1);
      volumes_horaires.items(length).structure_is_univ         := NVL(d.structure_test_id,0) = NVL(structure_univ,-1);
      volumes_horaires.items(length).service_statutaire        := d.service_statutaire = 1;
      volumes_horaires.items(length).heures                    := d.heures;
      volumes_horaires.items(length).type_intervention_code    := CASE WHEN d.referentiel = 1 THEN NULL ELSE d.type_intervention_code END;
      volumes_horaires.items(length).taux_service_du           := d.taux_service_du;
      volumes_horaires.items(length).taux_service_compl        := d.taux_service_compl;
      volumes_horaires.items(length).param_1                   := d.param_1;
      volumes_horaires.items(length).param_2                   := d.param_2;
      volumes_horaires.items(length).param_3                   := d.param_3;
      volumes_horaires.items(length).param_4                   := d.param_4;
      volumes_horaires.items(length).param_5                   := d.param_5;
    END LOOP;
  END;



  PROCEDURE tres_add_heures( code VARCHAR2, vh t_volume_horaire, tvh NUMERIC, evh NUMERIC) IS
  BEGIN
    IF NOT t_res.exists(code) THEN
      t_res(code).service_fi               := 0;
      t_res(code).service_fa               := 0;
      t_res(code).service_fc               := 0;
      t_res(code).service_referentiel      := 0;
      t_res(code).heures_compl_fi          := 0;
      t_res(code).heures_compl_fa          := 0;
      t_res(code).heures_compl_fc          := 0;
      t_res(code).heures_compl_fc_majorees := 0;
      t_res(code).heures_compl_referentiel := 0;
    END IF;

    t_res(code).service_fi               := t_res(code).service_fi               + vh.service_fi;
    t_res(code).service_fa               := t_res(code).service_fa               + vh.service_fa;
    t_res(code).service_fc               := t_res(code).service_fc               + vh.service_fc;
    t_res(code).service_referentiel      := t_res(code).service_referentiel      + vh.service_referentiel;
    t_res(code).heures_compl_fi          := t_res(code).heures_compl_fi          + vh.heures_compl_fi;
    t_res(code).heures_compl_fa          := t_res(code).heures_compl_fa          + vh.heures_compl_fa;
    t_res(code).heures_compl_fc          := t_res(code).heures_compl_fc          + vh.heures_compl_fc;
    t_res(code).heures_compl_fc_majorees := t_res(code).heures_compl_fc_majorees + vh.heures_compl_fc_majorees;
    t_res(code).heures_compl_referentiel := t_res(code).heures_compl_referentiel + vh.heures_compl_referentiel;

    t_res(code).type_volume_horaire_id := tvh;
    t_res(code).etat_volume_horaire_id := evh;
  END;

  PROCEDURE DEBUG_TRES IS
    code varchar2(15);
    table_name varchar2(30);
    fr formule_resultat%rowtype;
    frs formule_resultat_service%rowtype;
    frsr formule_resultat_service_ref%rowtype;
    frvh formule_resultat_vh%rowtype;
    frvhr formule_resultat_vh_ref%rowtype;
  BEGIN
    code := t_res.FIRST;
    LOOP EXIT WHEN code IS NULL;
      table_name := CASE
        WHEN code LIKE '%-s-%' THEN 'FORMULE_RESULTAT_SERVICE'
        WHEN code LIKE '%-sr-%' THEN 'FORMULE_RESULTAT_SERVICE_REF'
        WHEN code LIKE '%-vh-%' THEN 'FORMULE_RESULTAT_VH'
        WHEN code LIKE '%-vhr-%' THEN 'FORMULE_RESULTAT_VH_REF'
        ELSE 'FORMULE_RESULTAT'
      END;

      ose_test.echo('T_RES( ' || code || ' - Table ' || table_name || ' ) ');
      ose_test.echo('  id = ' || t_res(code).id);
      ose_test.echo('  formule_resultat_id      = ' || t_res(code).formule_resultat_id);
      ose_test.echo('  type_volume_horaire_id   = ' || t_res(code).type_volume_horaire_id);
      ose_test.echo('  etat_volume_horaire_id   = ' || t_res(code).etat_volume_horaire_id);
      ose_test.echo('  volume_horaire_id        = ' || t_res(code).volume_horaire_id);
      ose_test.echo('  volume_horaire_ref_id    = ' || t_res(code).volume_horaire_ref_id);
      ose_test.echo('  service_id               = ' || t_res(code).service_id);
      ose_test.echo('  service_referentiel_id   = ' || t_res(code).service_referentiel_id);
      ose_test.echo('  service_fi               = ' || t_res(code).service_fi);
      ose_test.echo('  service_fa               = ' || t_res(code).service_fa);
      ose_test.echo('  service_fc               = ' || t_res(code).service_fc);
      ose_test.echo('  service_referentiel      = ' || t_res(code).service_referentiel);
      ose_test.echo('  heures_compl_fi          = ' || t_res(code).heures_compl_fi);
      ose_test.echo('  heures_compl_fa          = ' || t_res(code).heures_compl_fa);
      ose_test.echo('  heures_compl_fc          = ' || t_res(code).heures_compl_fc);
      ose_test.echo('  heures_compl_fc_majorees = ' || t_res(code).heures_compl_fc_majorees);
      ose_test.echo('  heures_compl_referentiel = ' || t_res(code).heures_compl_referentiel);

      code := t_res.NEXT(code);
    END LOOP;
  END;

  PROCEDURE SAVE_TO_BDD IS
    bcode VARCHAR(15);
    code VARCHAR(15);
    type_volume_horaire_id NUMERIC;
    etat_volume_horaire_id NUMERIC;
    vh t_volume_horaire;
    fr formule_resultat%rowtype;
    frs formule_resultat_service%rowtype;
    frsr formule_resultat_service_ref%rowtype;
    frvh formule_resultat_vh%rowtype;
    frvhr formule_resultat_vh_ref%rowtype;
  BEGIN
    t_res.delete;

    /* On préinitialise avec ce qui existe déjà */
    FOR d IN (
      SELECT
        fr.type_volume_horaire_id || '-' || fr.etat_volume_horaire_id code,
        fr.id                       id,
        fr.id                       formule_resultat_id,
        fr.type_volume_horaire_id   type_volume_horaire_id,
        fr.etat_volume_horaire_id   etat_volume_horaire_id,
        null                        service_id,
        null                        service_referentiel_id,
        null                        volume_horaire_id,
        null                        volume_horaire_ref_id

      FROM
        formule_resultat fr
      WHERE
        fr.intervenant_id = intervenant.id

      UNION ALL SELECT
        fr.type_volume_horaire_id || '-' || fr.etat_volume_horaire_id || '-s-' || frs.service_id code,
        frs.id                      id,
        fr.id                       formule_resultat_id,
        fr.type_volume_horaire_id   type_volume_horaire_id,
        fr.etat_volume_horaire_id   etat_volume_horaire_id,
        frs.service_id              service_id,
        null                        service_referentiel_id,
        null                        volume_horaire_id,
        null                        volume_horaire_ref_id
      FROM
        formule_resultat_service frs
        JOIN formule_resultat fr ON fr.id = frs.formule_resultat_id
      WHERE
        fr.intervenant_id = intervenant.id

      UNION ALL SELECT
        fr.type_volume_horaire_id || '-' || fr.etat_volume_horaire_id || '-sr-' || frsr.service_referentiel_id code,
        frsr.id                     id,
        fr.id                       formule_resultat_id,
        fr.type_volume_horaire_id   type_volume_horaire_id,
        fr.etat_volume_horaire_id   etat_volume_horaire_id,
        null                        service_id,
        frsr.service_referentiel_id service_referentiel_id,
        null                        volume_horaire_id,
        null                        volume_horaire_ref_id
      FROM
        formule_resultat_service_ref frsr
        JOIN formule_resultat fr ON fr.id = frsr.formule_resultat_id
      WHERE
        fr.intervenant_id = intervenant.id

      UNION ALL SELECT
        fr.type_volume_horaire_id || '-' || fr.etat_volume_horaire_id || '-vh-' || frvh.volume_horaire_id code,
        frvh.id                     id,
        fr.id                       formule_resultat_id,
        fr.type_volume_horaire_id   type_volume_horaire_id,
        fr.etat_volume_horaire_id   etat_volume_horaire_id,
        null                        service_id,
        null                        service_referentiel_id,
        frvh.volume_horaire_id      volume_horaire_id,
        null                        volume_horaire_ref_id
      FROM
        formule_resultat_vh frvh
        JOIN formule_resultat fr ON fr.id = frvh.formule_resultat_id
      WHERE
        fr.intervenant_id = intervenant.id

      UNION ALL SELECT
        fr.type_volume_horaire_id || '-' || fr.etat_volume_horaire_id || '-vhr-' || frvhr.volume_horaire_ref_id code,
        frvhr.id                    id,
        fr.id                       formule_resultat_id,
        fr.type_volume_horaire_id   type_volume_horaire_id,
        fr.etat_volume_horaire_id   etat_volume_horaire_id,
        null                        service_id,
        null                        service_referentiel_id,
        null                        volume_horaire_id,
        frvhr.volume_horaire_ref_id volume_horaire_ref_id
      FROM
        formule_resultat_vh_ref frvhr
        JOIN formule_resultat fr ON fr.id = frvhr.formule_resultat_id
      WHERE
        fr.intervenant_id = intervenant.id
    ) LOOP
      t_res(d.code).id                     := d.id;
      t_res(d.code).formule_resultat_id    := d.formule_resultat_id;
      t_res(d.code).type_volume_horaire_id := d.type_volume_horaire_id;
      t_res(d.code).etat_volume_horaire_id := d.etat_volume_horaire_id;
      t_res(d.code).service_id             := d.service_id;
      t_res(d.code).service_referentiel_id := d.service_referentiel_id;
      t_res(d.code).volume_horaire_id      := d.volume_horaire_id;
      t_res(d.code).volume_horaire_ref_id  := d.volume_horaire_ref_id;
    END LOOP;

    /* On charge avec les résultats de formule */
    IF all_volumes_horaires.exists(intervenant.id) THEN
      type_volume_horaire_id := all_volumes_horaires(intervenant.id).FIRST;
      LOOP EXIT WHEN type_volume_horaire_id IS NULL;
        etat_volume_horaire_id := all_volumes_horaires(intervenant.id)(type_volume_horaire_id).FIRST;
        LOOP EXIT WHEN etat_volume_horaire_id IS NULL;
          FOR i IN 1 .. all_volumes_horaires(intervenant.id)(type_volume_horaire_id)(etat_volume_horaire_id).length LOOP
            vh := all_volumes_horaires(intervenant.id)(type_volume_horaire_id)(etat_volume_horaire_id).items(i);
            bcode := type_volume_horaire_id || '-' || etat_volume_horaire_id;

            -- formule_resultat
            code := bcode;
            tres_add_heures(code,vh, type_volume_horaire_id, etat_volume_horaire_id);

            -- formule_resultat_service
            IF vh.service_id IS NOT NULL THEN
              code := bcode || '-s-' || vh.service_id;
              t_res(code).service_id := vh.service_id;
              tres_add_heures(code,vh, type_volume_horaire_id, etat_volume_horaire_id);
            END IF;

            -- formule_resultat_service_ref
            IF vh.service_referentiel_id IS NOT NULL THEN
              code := bcode || '-sr-' || vh.service_referentiel_id;
              t_res(code).service_referentiel_id := vh.service_referentiel_id;
              tres_add_heures(code,vh, type_volume_horaire_id, etat_volume_horaire_id);
            END IF;

            -- formule_resultat_volume_horaire
            IF vh.volume_horaire_id IS NOT NULL THEN
              code := bcode || '-vh-' || vh.volume_horaire_id;
              t_res(code).volume_horaire_id := vh.volume_horaire_id;
              tres_add_heures(code,vh, type_volume_horaire_id, etat_volume_horaire_id);
            END IF;

            -- formule_resultat_volume_horaire_ref
            IF vh.volume_horaire_ref_id IS NOT NULL THEN
              code := bcode || '-vhr-' || vh.volume_horaire_ref_id;
              t_res(code).volume_horaire_ref_id := vh.volume_horaire_ref_id;
              tres_add_heures(code,vh, type_volume_horaire_id, etat_volume_horaire_id);
            END IF;

          END LOOP;
          etat_volume_horaire_id := all_volumes_horaires(intervenant.id)(type_volume_horaire_id).NEXT(etat_volume_horaire_id);
        END LOOP;
        type_volume_horaire_id := all_volumes_horaires(intervenant.id).NEXT(type_volume_horaire_id);
      END LOOP;
    END IF;

    /* On fait la sauvegarde en BDD */
    /* D'abord le formule_resultat */
    code := t_res.FIRST;
    LOOP EXIT WHEN code IS NULL;
      IF code = (t_res(code).type_volume_horaire_id || '-' || t_res(code).etat_volume_horaire_id) THEN
        fr.id                       := t_res(code).id;
        fr.intervenant_id           := intervenant.id;
        fr.type_volume_horaire_id   := t_res(code).type_volume_horaire_id;
        fr.etat_volume_horaire_id   := t_res(code).etat_volume_horaire_id;
        fr.service_fi               := ROUND(t_res(code).service_fi,2);
        fr.service_fa               := ROUND(t_res(code).service_fa,2);
        fr.service_fc               := ROUND(t_res(code).service_fc,2);
        fr.service_referentiel      := ROUND(t_res(code).service_referentiel,2);
        fr.heures_compl_fi          := ROUND(t_res(code).heures_compl_fi,2);
        fr.heures_compl_fa          := ROUND(t_res(code).heures_compl_fa,2);
        fr.heures_compl_fc          := ROUND(t_res(code).heures_compl_fc,2);
        fr.heures_compl_fc_majorees := ROUND(t_res(code).heures_compl_fc_majorees,2);
        fr.heures_compl_referentiel := ROUND(t_res(code).heures_compl_referentiel,2);
        fr.total := fr.service_fi + fr.service_fa + fr.service_fc + fr.service_referentiel
                  + fr.heures_compl_fi + fr.heures_compl_fa + fr.heures_compl_fc
                  + fr.heures_compl_fc_majorees + fr.heures_compl_referentiel;

        fr.service_du := ROUND(CASE
          WHEN intervenant.depassement_service_du_sans_hc OR intervenant.heures_decharge < 0
          THEN GREATEST(fr.total, intervenant.heures_service_statutaire + intervenant.heures_service_modifie)
          ELSE intervenant.heures_service_statutaire + intervenant.heures_service_modifie
        END,2);

        fr.solde                    := fr.total - fr.service_du;
        IF fr.solde >= 0 THEN
          fr.sous_service           := 0;
          fr.heures_compl           := fr.solde;
        ELSE
          fr.sous_service           := fr.solde * -1;
          fr.heures_compl           := 0;
        END IF;
        fr.type_intervenant_code    := intervenant.type_intervenant_code;

        IF fr.id IS NULL THEN
          fr.id := formule_resultat_id_seq.nextval;
          t_res(code).id := fr.id;
          INSERT INTO formule_resultat VALUES fr;
        ELSE
          UPDATE formule_resultat SET ROW = fr WHERE id = fr.id;
        END IF;
      END IF;
      code := t_res.NEXT(code);
    END LOOP;

    --DEBUG_TRES;

    /* Ensuite toutes les dépendances... */
    code := t_res.FIRST;
    LOOP EXIT WHEN code IS NULL;
      bcode := t_res(code).type_volume_horaire_id || '-' || t_res(code).etat_volume_horaire_id;
      CASE
        WHEN code LIKE '%-s-%' THEN -- formule_resultat_service
          frs.id                         := t_res(code).id;
          frs.formule_resultat_id        := t_res(bcode).id;
          frs.service_id                 := t_res(code).service_id;
          frs.service_fi                 := ROUND(t_res(code).service_fi, 2);
          frs.service_fa                 := ROUND(t_res(code).service_fa, 2);
          frs.service_fc                 := ROUND(t_res(code).service_fc, 2);
          frs.heures_compl_fi            := ROUND(t_res(code).heures_compl_fi, 2);
          frs.heures_compl_fa            := ROUND(t_res(code).heures_compl_fa, 2);
          frs.heures_compl_fc            := ROUND(t_res(code).heures_compl_fc, 2);
          frs.heures_compl_fc_majorees   := ROUND(t_res(code).heures_compl_fc_majorees, 2);
          frs.total                      := frs.service_fi + frs.service_fa + frs.service_fc
                 + frs.heures_compl_fi + frs.heures_compl_fa + frs.heures_compl_fc + frs.heures_compl_fc_majorees;
          IF frs.id IS NULL THEN
            frs.id := formule_resultat_servic_id_seq.nextval;
            INSERT INTO formule_resultat_service VALUES frs;
          ELSE
            UPDATE formule_resultat_service SET ROW = frs WHERE id = frs.id;
          END IF;
        WHEN code LIKE '%-sr-%' THEN -- formule_resultat_service_ref
          frsr.id                        := t_res(code).id;
          frsr.formule_resultat_id       := t_res(bcode).id;
          frsr.service_referentiel_id    := t_res(code).service_referentiel_id;
          frsr.service_referentiel       := ROUND(t_res(code).service_referentiel, 2);
          frsr.heures_compl_referentiel  := ROUND(t_res(code).heures_compl_referentiel, 2);
          frsr.total                     := frsr.service_referentiel + frsr.heures_compl_referentiel;
          IF frsr.id IS NULL THEN
            frsr.id := formule_resultat_servic_id_seq.nextval;
            INSERT INTO formule_resultat_service_ref VALUES frsr;
          ELSE
            UPDATE formule_resultat_service_ref SET ROW = frsr WHERE id = frsr.id;
          END IF;
        WHEN code LIKE '%-vh-%' THEN -- formule_resultat_vh
          frvh.id := t_res(code).id;
          frvh.formule_resultat_id       := t_res(bcode).id;
          frvh.volume_horaire_id         := t_res(code).volume_horaire_id;
          frvh.service_fi                := ROUND(t_res(code).service_fi, 2);
          frvh.service_fa                := ROUND(t_res(code).service_fa, 2);
          frvh.service_fc                := ROUND(t_res(code).service_fc, 2);
          frvh.heures_compl_fi           := ROUND(t_res(code).heures_compl_fi, 2);
          frvh.heures_compl_fa           := ROUND(t_res(code).heures_compl_fa, 2);
          frvh.heures_compl_fc           := ROUND(t_res(code).heures_compl_fc, 2);
          frvh.heures_compl_fc_majorees  := ROUND(t_res(code).heures_compl_fc_majorees, 2);
          frvh.total                     := frvh.service_fi + frvh.service_fa + frvh.service_fc
                  + frvh.heures_compl_fi + frvh.heures_compl_fa + frvh.heures_compl_fc + frvh.heures_compl_fc_majorees;
          IF frvh.id IS NULL THEN
            frvh.id := formule_resultat_vh_id_seq.nextval;
            INSERT INTO formule_resultat_vh VALUES frvh;
          ELSE
            UPDATE formule_resultat_vh SET ROW = frvh WHERE id = frvh.id;
          END IF;
        WHEN code LIKE '%-vhr-%' THEN -- formule_resultat_vh_ref
          frvhr.id := t_res(code).id;
          frvhr.formule_resultat_id      := t_res(bcode).id;
          frvhr.volume_horaire_ref_id    := t_res(code).volume_horaire_ref_id;
          frvhr.service_referentiel      := ROUND(t_res(code).service_referentiel, 2);
          frvhr.heures_compl_referentiel := ROUND(t_res(code).heures_compl_referentiel, 2);
          frvhr.total                    := frvhr.service_referentiel + frvhr.heures_compl_referentiel;
          IF frvhr.id IS NULL THEN
            frvhr.id := formule_resultat_vh_ref_id_seq.nextval;
            INSERT INTO formule_resultat_vh_ref VALUES frvhr;
          ELSE
            UPDATE formule_resultat_vh_ref SET ROW = frvhr WHERE id = frvhr.id;
          END IF;
        ELSE code := code;
      END CASE;
      code := t_res.NEXT(code);
    END LOOP;
  END;



  PROCEDURE SAVE_TO_TEST(passed NUMERIC) IS
    vh t_volume_horaire;
  BEGIN
    UPDATE formule_test_intervenant SET
      c_service_du = CASE WHEN passed = 1 THEN intervenant.service_du ELSE NULL END,
      debug_info = intervenant.debug_info
    WHERE id = intervenant.id;

    FOR i IN 1 .. volumes_horaires.length LOOP
      vh := volumes_horaires.items(i);
      UPDATE formule_test_volume_horaire SET
        c_service_fi               = CASE WHEN passed = 1 THEN vh.service_fi ELSE NULL END,
        c_service_fa               = CASE WHEN passed = 1 THEN vh.service_fa ELSE NULL END,
        c_service_fc               = CASE WHEN passed = 1 THEN vh.service_fc ELSE NULL END,
        c_service_referentiel      = CASE WHEN passed = 1 THEN vh.service_referentiel ELSE NULL END,
        c_heures_compl_fi          = CASE WHEN passed = 1 THEN vh.heures_compl_fi ELSE NULL END,
        c_heures_compl_fa          = CASE WHEN passed = 1 THEN vh.heures_compl_fa ELSE NULL END,
        c_heures_compl_fc          = CASE WHEN passed = 1 THEN vh.heures_compl_fc ELSE NULL END,
        c_heures_compl_fc_majorees = CASE WHEN passed = 1 THEN vh.heures_compl_fc_majorees ELSE NULL END,
        c_heures_compl_referentiel = CASE WHEN passed = 1 THEN vh.heures_compl_referentiel ELSE NULL END,
        debug_info                 = vh.debug_info
      WHERE
        id = COALESCE(vh.volume_horaire_id,vh.volume_horaire_ref_id);
    END LOOP;
  END;



  PROCEDURE CALCULER( INTERVENANT_ID NUMERIC ) IS
    type_volume_horaire_id NUMERIC;
    etat_volume_horaire_id NUMERIC;
  BEGIN
    intervenant.id := intervenant_id;

    IF NOT in_calculer_tout THEN
      formule_definition := ose_parametre.get_formule;
    END IF;

    LOAD_INTERVENANT_FROM_BDD;
    IF intervenant.id IS NULL THEN -- intervenant non trouvé
      RETURN;
    END IF;
    IF NOT in_calculer_tout THEN
      LOAD_VH_FROM_BDD;
    END IF;

    IF all_volumes_horaires.exists(intervenant.id) THEN
      type_volume_horaire_id := all_volumes_horaires(intervenant.id).FIRST;
      LOOP EXIT WHEN type_volume_horaire_id IS NULL;
        intervenant.type_volume_horaire_id := type_volume_horaire_id;
        etat_volume_horaire_id := all_volumes_horaires(intervenant.id)(type_volume_horaire_id).FIRST;
        LOOP EXIT WHEN etat_volume_horaire_id IS NULL;
          intervenant.etat_volume_horaire_id := etat_volume_horaire_id;
          volumes_horaires := all_volumes_horaires(intervenant.id)(type_volume_horaire_id)(etat_volume_horaire_id);
          EXECUTE IMMEDIATE 'BEGIN ' || formule_definition.package_name || '.' || formule_definition.procedure_name || '; END;';
          all_volumes_horaires(intervenant.id)(type_volume_horaire_id)(etat_volume_horaire_id) := volumes_horaires;
          etat_volume_horaire_id := all_volumes_horaires(intervenant.id)(type_volume_horaire_id).NEXT(etat_volume_horaire_id);
        END LOOP;
        type_volume_horaire_id := all_volumes_horaires(intervenant.id).NEXT(type_volume_horaire_id);
      END LOOP;
    END IF;

    SAVE_TO_BDD;

    OSE_EVENT.ON_AFTER_FORMULE_CALC( CALCULER.INTERVENANT_ID );
  END;

  PROCEDURE CALCULER_TOUT( ANNEE_ID NUMERIC DEFAULT NULL ) IS
    i_id NUMERIC;
  BEGIN
    formule_definition := ose_parametre.get_formule;
    intervenant.id := null;
    LOAD_VH_FROM_BDD;

    in_calculer_tout := true;
    i_id := all_volumes_horaires.FIRST;
    LOOP EXIT WHEN i_id IS NULL;
      CALCULER( i_id );
      i_id := all_volumes_horaires.NEXT(i_id);
    END LOOP;
    in_calculer_tout := false;
  END;



  PROCEDURE TEST( INTERVENANT_TEST_ID NUMERIC ) IS
    procedure_name VARCHAR2(30);
    package_name VARCHAR2(30);
  BEGIN
    intervenant.id := INTERVENANT_TEST_ID;

    SELECT
      package_name, procedure_name INTO package_name, procedure_name
    FROM
      formule f JOIN formule_test_intervenant fti ON fti.formule_id = f.id
    WHERE
      fti.id = intervenant.id;

    LOAD_INTERVENANT_FROM_TEST;
    LOAD_VH_FROM_TEST;

    BEGIN
      EXECUTE IMMEDIATE 'BEGIN ' || package_name || '.' || procedure_name || '; END;';
      SAVE_TO_TEST(1);
    EXCEPTION WHEN OTHERS THEN
      SAVE_TO_TEST(0);
      RAISE_APPLICATION_ERROR(-20001, dbms_utility.format_error_backtrace,true);
    END;
  END;



  PROCEDURE TEST_TOUT IS
  BEGIN
    FOR d IN (SELECT id FROM formule_test_intervenant)
    LOOP
      TEST( d.id );
    END LOOP;
  END;



  PROCEDURE CALCULER_TBL( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    intervenant_id NUMERIC;
    TYPE r_cursor IS REF CURSOR;
    diff_cur r_cursor;
  BEGIN
    OPEN diff_cur FOR 'WITH interv AS (SELECT id intervenant_id, intervenant.* FROM intervenant)
    SELECT intervenant_id FROM interv WHERE ' || unicaen_tbl.PARAMS_TO_CONDS( params );
    LOOP
      FETCH diff_cur INTO intervenant_id; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN
        CALCULER( intervenant_id );
      END;
    END LOOP;
    CLOSE diff_cur;
  END;



  PROCEDURE DEBUG_INTERVENANT IS
  BEGIN
    ose_test.echo('OSE Formule DEBUG Intervenant');
    ose_test.echo('id                             = ' || intervenant.id);
    ose_test.echo('annee_id                       = ' || intervenant.annee_id);
    ose_test.echo('structure_id                   = ' || intervenant.structure_id);
    ose_test.echo('type_volume_horaire_id         = ' || intervenant.type_volume_horaire_id);
    ose_test.echo('heures_decharge                = ' || intervenant.heures_decharge);
    ose_test.echo('heures_service_statutaire      = ' || intervenant.heures_service_statutaire);
    ose_test.echo('heures_service_modifie         = ' || intervenant.heures_service_modifie);
    ose_test.echo('depassement_service_du_sans_hc = ' || CASE WHEN intervenant.depassement_service_du_sans_hc THEN 'OUI' ELSE 'NON' END);
    ose_test.echo('service_du                     = ' || intervenant.service_du);
  END;

  PROCEDURE DEBUG_VOLUMES_HORAIRES(VOLUME_HORAIRE_ID NUMERIC DEFAULT NULL) IS
    type_volume_horaire_id NUMERIC;
    etat_volume_horaire_id NUMERIC;
    vh t_volume_horaire;
  BEGIN
    ose_test.echo('OSE Formule DEBUG Intervenant');

    type_volume_horaire_id := all_volumes_horaires(intervenant.id).FIRST;
    LOOP EXIT WHEN type_volume_horaire_id IS NULL;
      etat_volume_horaire_id := all_volumes_horaires(intervenant.id)(type_volume_horaire_id).FIRST;
      LOOP EXIT WHEN etat_volume_horaire_id IS NULL;
        ose_test.echo('tvh=' || type_volume_horaire_id || ', evh=' || etat_volume_horaire_id);
        FOR i IN 1 .. all_volumes_horaires(intervenant.id)(type_volume_horaire_id)(etat_volume_horaire_id).length LOOP
          vh := all_volumes_horaires(intervenant.id)(type_volume_horaire_id)(etat_volume_horaire_id).items(i);
          IF VOLUME_HORAIRE_ID IS NULL OR VOLUME_HORAIRE_ID = vh.volume_horaire_id OR VOLUME_HORAIRE_ID = vh.volume_horaire_ref_id THEN
            ose_test.echo('volume_horaire_id         = ' || vh.volume_horaire_id);
            ose_test.echo('volume_horaire_ref_id     = ' || vh.volume_horaire_ref_id);
            ose_test.echo('service_id                = ' || vh.service_id);
            ose_test.echo('service_referentiel_id    = ' || vh.service_referentiel_id);
            ose_test.echo('taux_fi                   = ' || vh.taux_fi);
            ose_test.echo('taux_fa                   = ' || vh.taux_fa);
            ose_test.echo('taux_fc                   = ' || vh.taux_fc);
            ose_test.echo('ponderation_service_du    = ' || vh.ponderation_service_du);
            ose_test.echo('ponderation_service_compl = ' || vh.ponderation_service_compl);
            ose_test.echo('structure_id              = ' || vh.structure_id);
            ose_test.echo('structure_is_affectation  = ' || CASE WHEN vh.structure_is_affectation THEN 'OUI' ELSE 'NON' END);
            ose_test.echo('structure_is_univ         = ' || CASE WHEN vh.structure_is_univ THEN 'OUI' ELSE 'NON' END);
            ose_test.echo('service_statutaire        = ' || CASE WHEN vh.service_statutaire THEN 'OUI' ELSE 'NON' END);
            ose_test.echo('heures                    = ' || vh.heures);
            ose_test.echo('taux_service_du           = ' || vh.taux_service_du);
            ose_test.echo('taux_service_compl        = ' || vh.taux_service_compl);
            ose_test.echo('service_fi                = ' || vh.service_fi);
            ose_test.echo('service_fa                = ' || vh.service_fa);
            ose_test.echo('service_fc                = ' || vh.service_fc);
            ose_test.echo('service_referentiel       = ' || vh.service_referentiel);
            ose_test.echo('heures_compl_fi           = ' || vh.heures_compl_fi);
            ose_test.echo('heures_compl_fa           = ' || vh.heures_compl_fa);
            ose_test.echo('heures_compl_fc           = ' || vh.heures_compl_fc);
            ose_test.echo('heures_compl_fc_majorees  = ' || vh.heures_compl_fc_majorees);
            ose_test.echo('heures_compl_referentiel  = ' || vh.heures_compl_referentiel);
            ose_test.echo('');
          END IF;
        END LOOP;
        etat_volume_horaire_id := all_volumes_horaires(intervenant.id)(type_volume_horaire_id).NEXT(etat_volume_horaire_id);
      END LOOP;
      type_volume_horaire_id := all_volumes_horaires(intervenant.id).NEXT(type_volume_horaire_id);
    END LOOP;
  END;

END OSE_FORMULE;
/

CREATE OR REPLACE PACKAGE "OSE_PARAMETRE" AS

  function get_etablissement return Numeric;
  function get_annee return Numeric;
  function get_annee_import return Numeric;
  function get_ose_user return Numeric;
  function get_formule RETURN formule%rowtype;

END OSE_PARAMETRE;
/

CREATE OR REPLACE PACKAGE BODY "OSE_PARAMETRE" AS

  cache_ose_user NUMERIC;
  cache_annee_id NUMERIC;

  FUNCTION get_etablissement return Numeric AS
    etab_id numeric;
  BEGIN
    select to_number(valeur) into etab_id from parametre where nom = 'etablissement';
    RETURN etab_id;
  END get_etablissement;

  FUNCTION get_annee return Numeric AS
    annee_id numeric;
  BEGIN
    IF cache_annee_id IS NOT NULL THEN RETURN cache_annee_id; END IF;
    select to_number(valeur) into annee_id from parametre where nom = 'annee';
    cache_annee_id := annee_id;
    RETURN cache_annee_id;
  END get_annee;

  FUNCTION get_annee_import RETURN NUMERIC AS
    annee_id NUMERIC;
  BEGIN
    SELECT to_number(valeur) INTO annee_id FROM parametre WHERE nom = 'annee_import';
    RETURN annee_id;
  END get_annee_import;

  FUNCTION get_ose_user return NUMERIC AS
    ose_user_id numeric;
  BEGIN
    IF cache_ose_user IS NOT NULL THEN RETURN cache_ose_user; END IF;
    select to_number(valeur) into ose_user_id from parametre where nom = 'oseuser';
    cache_ose_user := ose_user_id;
    RETURN cache_ose_user;
  END get_ose_user;

  FUNCTION get_formule RETURN formule%rowtype IS
    fdata formule%rowtype;
  BEGIN
    SELECT
      f.* INTO fdata
    FROM
      formule f
        JOIN parametre p ON f.id = to_number(p.valeur)
    WHERE p.nom = 'formule';
    RETURN fdata;
  END;

END OSE_PARAMETRE;
/




--------------------------------------------------
-- Modification des vues
--------------------------------------------------

CREATE OR REPLACE FORCE VIEW V_CONTRAT_MAIN AS
  WITH hs AS (
    SELECT contrat_id, sum(heures) "serviceTotal" FROM V_CONTRAT_SERVICES GROUP BY contrat_id
    )
    SELECT
      ct.id contrat_id,
      ct."annee",
      ct."nom",
      ct."prenom",
      ct."civilite",
      ct."e",
      ct."dateNaissance",
      ct."adresse",
      ct."numInsee",
      ct."statut",
      ct."totalHETD",
      ct."tauxHoraireValeur",
      ct."tauxHoraireDate",
      ct."dateSignature",
      ct."modifieComplete",
      CASE WHEN ct.est_contrat=1 THEN 1 ELSE null END "contrat1",
      CASE WHEN ct.est_contrat=1 THEN null ELSE 1 END "avenant1",
      CASE WHEN ct.est_contrat=1 THEN '3' ELSE '2' END "n",
      to_char(SYSDATE, 'dd/mm/YYYY - hh24:mi:ss') "horodatage",
      'Exemplaire à conserver' "exemplaire1",
      'Exemplaire à retourner' || ct."exemplaire2" "exemplaire2",
      ct."serviceTotal",

      CASE ct.est_contrat
        WHEN 1 THEN -- contrat
          'Contrat de travail'
        ELSE
            'Avenant au contrat de travail initial modifiant le volume horaire initial'
            || ' de recrutement en qualité'
        END                                         "titre",
      CASE WHEN ct.est_atv = 1 THEN
             'd''agent temporaire vacataire'
           ELSE
               'de chargé' || ct."e" || ' d''enseignement vacataire'
        END                                         "qualite",

      CASE
        WHEN ct.est_projet = 1 AND ct.est_contrat = 1 THEN 'Projet de contrat'
        WHEN ct.est_projet = 0 AND ct.est_contrat = 1 THEN 'Contrat n°' || ct.id
        WHEN ct.est_projet = 1 AND ct.est_contrat = 0 THEN 'Projet d''avenant'
        WHEN ct.est_projet = 0 AND ct.est_contrat = 0 THEN 'Avenant n°' || ct.contrat_id || '.' || ct.numero_avenant
        END                                         "titreCourt"
    FROM
      (
        SELECT
          c.*,
          a.libelle                                                                                     "annee",
          COALESCE(d.nom_usuel,i.nom_usuel)                                                             "nom",
          COALESCE(d.prenom,i.prenom)                                                                   "prenom",
          civ.libelle_court                                                                             "civilite",
          CASE WHEN civ.sexe = 'F' THEN 'e' ELSE '' END                                                 "e",
          to_char(COALESCE(d.date_naissance,i.date_naissance), 'dd/mm/YYYY')                            "dateNaissance",
          COALESCE(d.adresse,ose_divers.formatted_adresse(
              ai.NO_VOIE, ai.NOM_VOIE, ai.BATIMENT, ai.MENTION_COMPLEMENTAIRE, ai.LOCALITE,
              ai.CODE_POSTAL, ai.VILLE, ai.PAYS_LIBELLE))                                               "adresse",
          COALESCE(d.numero_insee,i.numero_insee || ' ' || COALESCE(LPAD(i.numero_insee_cle,2,'0'),'')) "numInsee",
          si.libelle                                                                                    "statut",
          replace(ltrim(to_char(COALESCE(fr.total,0), '999999.00')),'.',',')                            "totalHETD",
          replace(ltrim(to_char(COALESCE(th.valeur,0), '999999.00')),'.',',')                           "tauxHoraireValeur",
          COALESCE(to_char(th.histo_creation, 'dd/mm/YYYY'), 'TAUX INTROUVABLE')                        "tauxHoraireDate",
          to_char(COALESCE(v.histo_creation, c.histo_creation), 'dd/mm/YYYY')                           "dateSignature",
          CASE WHEN c.structure_id <> COALESCE(cp.structure_id,0) THEN 'modifié' ELSE 'complété' END    "modifieComplete",
          CASE WHEN s.aff_adresse_contrat = 1 THEN
                   ' signé à l''adresse suivante :' || CHR(13) || CHR(10) ||
                   s.libelle_court || ' - ' || REPLACE(ose_divers.formatted_adresse(
                                                           astr.NO_VOIE, astr.NOM_VOIE, null, null, astr.LOCALITE,
                                                           astr.CODE_POSTAL, astr.VILLE, null), CHR(13), ' - ')
               ELSE '' END                                                                                   "exemplaire2",
          replace(ltrim(to_char(COALESCE(hs."serviceTotal",0), '999999.00')),'.',',')                   "serviceTotal",
          CASE WHEN c.contrat_id IS NULL THEN 1 ELSE 0 END                                              est_contrat,
          CASE WHEN v.id IS NULL THEN 1 ELSE 0 END                                                      est_projet,
          si.tem_atv                                                                                    est_atv

        FROM
          contrat               c
            JOIN type_contrat         tc ON tc.id = c.type_contrat_id
            JOIN intervenant           i ON i.id = c.intervenant_id
            JOIN annee                 a ON a.id = i.annee_id
            JOIN statut_intervenant   si ON si.id = i.statut_id
            JOIN structure             s ON s.id = c.structure_id
            LEFT JOIN adresse_structure  astr ON astr.structure_id = s.id AND astr.principale = 1 AND astr.histo_destruction IS NULL
            LEFT JOIN dossier               d ON d.intervenant_id = i.id AND d.histo_destruction IS NULL
            JOIN civilite            civ ON civ.id = COALESCE(d.civilite_id,i.civilite_id)
            LEFT JOIN validation            v ON v.id = c.validation_id AND v.histo_destruction IS NULL
            LEFT JOIN adresse_intervenant  ai ON ai.intervenant_id = i.id AND ai.histo_destruction IS NULL

            JOIN type_volume_horaire tvh ON tvh.code = 'PREVU'
            JOIN etat_volume_horaire evh ON evh.code = 'valide'
            LEFT JOIN formule_resultat     fr ON fr.intervenant_id = i.id AND fr.type_volume_horaire_id = tvh.id AND fr.etat_volume_horaire_id = evh.id
            LEFT JOIN taux_horaire_hetd    th ON c.histo_creation BETWEEN th.histo_creation AND COALESCE(th.histo_destruction,SYSDATE)
            LEFT JOIN                      hs ON hs.contrat_id = c.id
            LEFT JOIN contrat              cp ON cp.id = c.contrat_id
        WHERE
            c.histo_destruction IS NULL
      ) ct;
/

CREATE OR REPLACE FORCE VIEW V_CONTRAT_SERVICES AS
SELECT
  c.id                                             contrat_id,
  str.libelle_court                                "serviceComposante",
  ep.code                                          "serviceCode",
  ep.libelle                                       "serviceLibelle",
  sum(vh.heures)                                   heures,
  replace(ltrim(to_char(sum(vh.heures), '999999.00')),'.',',') "serviceHeures"
FROM
  contrat                  c
    JOIN intervenant              i ON i.id = c.intervenant_id
    JOIN type_volume_horaire    tvh ON tvh.code = 'PREVU'
    JOIN service                  s ON s.intervenant_id = i.id AND s.histo_destruction IS NULL
    JOIN volume_horaire          vh ON vh.service_id = s.id AND vh.histo_destruction IS NULL AND vh.type_volume_horaire_id = tvh.id
    LEFT JOIN validation_vol_horaire vvh ON vvh.volume_horaire_id = vh.id
    LEFT JOIN validation               v ON v.id = vvh.validation_id AND v.histo_destruction IS NULL
    LEFT JOIN validation              cv ON cv.id = c.validation_id AND cv.histo_destruction IS NULL
    LEFT JOIN element_pedagogique     ep ON ep.id = s.element_pedagogique_id
    JOIN structure              str ON str.id = COALESCE(ep.structure_id,i.structure_id)
WHERE
    c.histo_destruction IS NULL
  -- On récapitule tous les enseignements validés de la composante et pas seulement le différentiel...
  --AND (cv.id IS NULL OR vh.contrat_id = c.id)
  AND COALESCE(ep.structure_id,i.structure_id) = c.structure_id
  AND (vh.auto_validation = 1 OR v.id IS NOT NULL)
GROUP BY
  c.id, str.libelle_court, ep.code, ep.libelle;
/

CREATE OR REPLACE FORCE VIEW V_ETAT_PAIEMENT AS
SELECT
  annee_id,
  type_intervenant_id,
  structure_id,
  periode_id,
  intervenant_id,
  centre_cout_id,
  domaine_fonctionnel_id,

  annee_id || '/' || (annee_id+1) annee,
  etat,
  composante,
  date_mise_en_paiement,
  periode,
  statut,
  intervenant_code,
  intervenant_nom,
  intervenant_numero_insee,
  centre_cout_code,
  centre_cout_libelle,
  domaine_fonctionnel_code,
  domaine_fonctionnel_libelle,
  hetd,
  CASE WHEN pourc_ecart >= 0 THEN
         CASE WHEN RANK() OVER (PARTITION BY periode_id, intervenant_id, etat, structure_id ORDER BY CASE WHEN (pourc_ecart >= 0 AND pourc_diff >= 0) OR (pourc_ecart < 0 AND pourc_diff < 0) THEN pourc_diff ELSE -1 END DESC) <= (ABS(pourc_ecart) / 0.001) THEN hetd_pourc + (pourc_ecart / ABS(pourc_ecart) * 0.001) ELSE hetd_pourc END
       ELSE
         CASE WHEN RANK() OVER (PARTITION BY periode_id, intervenant_id, etat, structure_id ORDER BY CASE WHEN (pourc_ecart >= 0 AND pourc_diff >= 0) OR (pourc_ecart < 0 AND pourc_diff < 0) THEN pourc_diff ELSE -1 END) <= (ABS(pourc_ecart) / 0.001) THEN hetd_pourc + (pourc_ecart / ABS(pourc_ecart) * 0.001) ELSE hetd_pourc END
    END hetd_pourc,
  hetd_montant,
  rem_fc_d714,
  exercice_aa,
  exercice_aa_montant,
  exercice_ac,
  exercice_ac_montant
FROM
  (
    SELECT
      dep3.*,

      1-CASE WHEN hetd > 0 THEN SUM( hetd_pourc ) OVER ( PARTITION BY periode_id, intervenant_id, etat, structure_id) ELSE 0 END pourc_ecart


    FROM (

           SELECT
             periode_id,
             structure_id,
             type_intervenant_id,
             intervenant_id,
             annee_id,
             centre_cout_id,
             domaine_fonctionnel_id,
             etat,
             composante,
             date_mise_en_paiement,
             periode,
             statut,
             intervenant_code,
             intervenant_nom,
             intervenant_numero_insee,
             centre_cout_code,
             centre_cout_libelle,
             domaine_fonctionnel_code,
             domaine_fonctionnel_libelle,
             hetd,
             ROUND( CASE WHEN hetd > 0 THEN hetd / SUM( hetd ) OVER( PARTITION BY periode_id, intervenant_id, etat, structure_id) ELSE 0 END, 3 ) hetd_pourc,
             ROUND( hetd * taux_horaire, 2 ) hetd_montant,
             ROUND( fc_majorees * taux_horaire, 2 ) rem_fc_d714,
             exercice_aa,
             ROUND( exercice_aa * taux_horaire, 2 ) exercice_aa_montant,
             exercice_ac,
             ROUND( exercice_ac * taux_horaire, 2 ) exercice_ac_montant,


             (CASE WHEN hetd > 0 THEN hetd / SUM( hetd ) OVER( PARTITION BY periode_id, intervenant_id, etat, structure_id) ELSE 0 END)
               -
             ROUND( CASE WHEN hetd > 0 THEN hetd / SUM( hetd ) OVER( PARTITION BY periode_id, intervenant_id, etat, structure_id) ELSE 0 END, 3 ) pourc_diff

           FROM (
                  WITH dep AS ( -- détails par état de paiement
                    SELECT
                      CASE WHEN th.code = 'fc_majorees' THEN 1 ELSE 0 END                 is_fc_majoree,
                      p.id                                                                periode_id,
                      s.id                                                                structure_id,
                      i.id                                                                intervenant_id,
                      i.annee_id                                                          annee_id,
                      cc.id                                                               centre_cout_id,
                      df.id                                                               domaine_fonctionnel_id,
                      ti.id                                                               type_intervenant_id,
                      CASE
                        WHEN mep.date_mise_en_paiement IS NULL THEN 'a-mettre-en-paiement'
                        ELSE 'mis-en-paiement'
                        END                                                                 etat,

                      TRIM(p.libelle_long || ' ' || to_char( add_months( a.date_debut, p.ecart_mois ), 'yyyy' )) periode,
                      mep.date_mise_en_paiement                                           date_mise_en_paiement,
                      s.libelle_court                                                     composante,
                      ti.libelle                                                          statut,
                      i.source_code                                                       intervenant_code,
                      i.nom_usuel || ' ' || i.prenom                                      intervenant_nom,
                      TRIM( NVL(i.numero_insee,'') || NVL(TO_CHAR(i.numero_insee_cle,'00'),'') ) intervenant_numero_insee,
                      cc.source_code                                                      centre_cout_code,
                      cc.libelle                                                          centre_cout_libelle,
                      df.source_code                                                      domaine_fonctionnel_code,
                      df.libelle                                                          domaine_fonctionnel_libelle,
                      CASE WHEN th.code = 'fc_majorees' THEN 0 ELSE mep.heures END        hetd,
                      CASE WHEN th.code = 'fc_majorees' THEN mep.heures ELSE 0 END        fc_majorees,
                      mep.heures * 4 / 10                                                 exercice_aa,
                      mep.heures * 6 / 10                                                 exercice_ac,
                      --CASE WHEN th.code = 'fc_majorees' THEN 0 ELSE mep.heures END * 4 / 10                                                 exercice_aa,
                      --CASE WHEN th.code = 'fc_majorees' THEN 0 ELSE mep.heures END * 6 / 10                                                 exercice_ac,
                      OSE_FORMULE.GET_TAUX_HORAIRE_HETD( NVL(mep.date_mise_en_paiement,SYSDATE) )      taux_horaire
                    FROM
                      v_mep_intervenant_structure  mis
                        JOIN mise_en_paiement        mep ON mep.id = mis.mise_en_paiement_id AND mep.histo_destruction IS NULL
                        JOIN type_heures              th ON  th.id = mep.type_heures_id
                        JOIN centre_cout              cc ON  cc.id = mep.centre_cout_id      -- pas d'historique pour les centres de coût, qui devront tout de même apparaitre mais en erreur
                        JOIN intervenant               i ON   i.id = mis.intervenant_id      AND i.histo_destruction IS NULL
                        JOIN annee                     a ON   a.id = i.annee_id
                        JOIN statut_intervenant       si ON  si.id = i.statut_id
                        JOIN type_intervenant         ti ON  ti.id = si.type_intervenant_id
                        JOIN structure                 s ON   s.id = mis.structure_id
                        LEFT JOIN validation           v ON   v.id = mep.validation_id       AND v.histo_destruction IS NULL
                        LEFT JOIN domaine_fonctionnel df ON  df.id = mis.domaine_fonctionnel_id
                        LEFT JOIN periode              p ON   p.id = mep.periode_paiement_id
                    )
                    SELECT
                      periode_id,
                      structure_id,
                      type_intervenant_id,
                      intervenant_id,
                      annee_id,
                      centre_cout_id,
                      domaine_fonctionnel_id,
                      etat,
                      periode,
                      composante,
                      date_mise_en_paiement,
                      statut,
                      intervenant_code,
                      intervenant_nom,
                      intervenant_numero_insee,
                      centre_cout_code,
                      centre_cout_libelle,
                      domaine_fonctionnel_code,
                      domaine_fonctionnel_libelle,
                      SUM( hetd ) hetd,
                      SUM( fc_majorees ) fc_majorees,
                      SUM( exercice_aa ) exercice_aa,
                      SUM( exercice_ac ) exercice_ac,
                      taux_horaire
                    FROM
                      dep
                    GROUP BY
                      periode_id,
                      structure_id,
                      type_intervenant_id,
                      intervenant_id,
                      annee_id,
                      centre_cout_id,
                      domaine_fonctionnel_id,
                      etat,
                      periode,
                      composante,
                      date_mise_en_paiement,
                      statut,
                      intervenant_code,
                      intervenant_nom,
                      intervenant_numero_insee,
                      centre_cout_code,
                      centre_cout_libelle,
                      domaine_fonctionnel_code,
                      domaine_fonctionnel_libelle,
                      taux_horaire,
                      is_fc_majoree
                )
                  dep2
         )
           dep3
  )
    dep4
ORDER BY
  annee_id,
  type_intervenant_id,
  structure_id,
  periode_id,
  intervenant_nom;
/

CREATE OR REPLACE FORCE VIEW V_EXPORT_PAIEMENT_WINPAIE AS
SELECT
  annee_id,
  type_intervenant_id,
  structure_id,
  periode_id,
  intervenant_id,

  insee,
  nom,
  '20' carte,
  code_origine,
  CASE WHEN type_intervenant_code = 'P' THEN '0204' ELSE '2251' END retenue,
  '0' sens,
  'B' mc,
  nbu,
  montant,
  libelle || ' ' || LPAD(TO_CHAR(FLOOR(nbu)),2,'00') || ' H' ||
  CASE to_char(ROUND( nbu-FLOOR(nbu), 2 )*100,'00')
  WHEN ' 00' THEN '' ELSE ' ' || LPAD(ROUND( nbu-FLOOR(nbu), 2 )*100,2,'00') END libelle
FROM (
  SELECT
    i.annee_id                                                                                          annee_id,
    ti.id                                                                                               type_intervenant_id,
    ti.code                                                                                             type_intervenant_code,
    t2.structure_id                                                                                     structure_id,
    t2.periode_paiement_id                                                                              periode_id,
    i.id                                                                                                intervenant_id,

    '''' || NVL(i.numero_insee,'') || TRIM(NVL(TO_CHAR(i.numero_insee_cle,'00'),''))                    insee,
    i.nom_usuel || ',' || i.prenom                                                                      nom,
    t2.code_origine                                                                                     code_origine,
    CASE WHEN ind <> CEIL(t2.nbu/max_nbu) THEN max_nbu ELSE t2.nbu - max_nbu*(ind-1) END                nbu,
    t2.nbu                                                                                              tnbu,
    OSE_FORMULE.GET_TAUX_HORAIRE_HETD( NVL(t2.date_mise_en_paiement,SYSDATE) )                          montant,
    COALESCE(t2.unite_budgetaire,'') || ' ' || to_char(i.annee_id) || ' ' || to_char(i.annee_id+1)      libelle
  FROM (
    SELECT
      structure_id,
      periode_paiement_id,
      intervenant_id,
      code_origine,
      ROUND( SUM(nbu), 2) nbu,
      unite_budgetaire,
      date_mise_en_paiement
    FROM (
      WITH mep AS (
        SELECT
          -- pour les filtres
          mep.id,
          mis.structure_id,
          mep.periode_paiement_id,
          mis.intervenant_id,
          mep.heures,
          cc.unite_budgetaire,
          mep.date_mise_en_paiement
        FROM
          v_mep_intervenant_structure  mis
          JOIN mise_en_paiement        mep ON mep.id = mis.mise_en_paiement_id AND mep.histo_destruction IS NULL
          JOIN centre_cout              cc ON cc.id = mep.centre_cout_id
          JOIN type_heures              th ON th.id = mep.type_heures_id
        WHERE
          mep.date_mise_en_paiement IS NOT NULL
          AND mep.periode_paiement_id IS NOT NULL
          AND th.eligible_extraction_paie = 1
      )
      SELECT
        mep.id,
        mep.structure_id,
        mep.periode_paiement_id,
        mep.intervenant_id,
        2 code_origine,
        mep.heures * 4 / 10 nbu,
        mep.unite_budgetaire,
        mep.date_mise_en_paiement
      FROM
        mep
      WHERE
        mep.heures * 4 / 10 > 0

      UNION

      SELECT
        mep.id,
        mep.structure_id,
        mep.periode_paiement_id,
        mep.intervenant_id,
        1 code_origine,
        mep.heures * 6 / 10 nbu,
        mep.unite_budgetaire,
        mep.date_mise_en_paiement
      FROM
        mep
      WHERE
        mep.heures * 6 / 10 > 0
    ) t1
    GROUP BY
      structure_id,
      periode_paiement_id,
      intervenant_id,
      code_origine,
      unite_budgetaire,
      date_mise_en_paiement
  ) t2
  JOIN (SELECT level ind, 99 max_nbu FROM dual CONNECT BY 1=1 AND LEVEL <= 11) tnbu ON ceil(t2.nbu / max_nbu) >= ind
  JOIN intervenant         i ON i.id = t2.intervenant_id
  JOIN statut_intervenant si ON si.id = i.statut_id
  JOIN type_intervenant   ti ON ti.id = si.type_intervenant_id
  JOIN structure           s ON s.id = t2.structure_id
) t3
ORDER BY
  annee_id, type_intervenant_id, structure_id, periode_id, nom, code_origine, nbu DESC;
/

CREATE OR REPLACE FORCE VIEW V_EXPORT_SERVICE AS
  WITH t AS ( SELECT
                  'vh_' || vh.id                    id,
                  s.id                              service_id,
                  s.intervenant_id                  intervenant_id,
                  vh.type_volume_horaire_id         type_volume_horaire_id,
                  fr.etat_volume_horaire_id         etat_volume_horaire_id,
                  s.element_pedagogique_id          element_pedagogique_id,
                  s.etablissement_id                etablissement_id,
                  NULL                              structure_aff_id,
                  NULL                              structure_ens_id,
                  vh.periode_id                     periode_id,
                  vh.type_intervention_id           type_intervention_id,
                  NULL                              fonction_referentiel_id,

                  s.description                     service_description,

                  vh.heures                         heures,
                  0                                 heures_ref,
                  0                                 heures_non_payees,
                  frvh.service_fi                   service_fi,
                  frvh.service_fa                   service_fa,
                  frvh.service_fc                   service_fc,
                  0                                 service_referentiel,
                  frvh.heures_compl_fi              heures_compl_fi,
                  frvh.heures_compl_fa              heures_compl_fa,
                  frvh.heures_compl_fc              heures_compl_fc,
                  frvh.heures_compl_fc_majorees     heures_compl_fc_majorees,
                  0                                 heures_compl_referentiel,
                  frvh.total                        total,
                  fr.solde                          solde,
                  NULL                              service_ref_formation,
                  NULL                              commentaires
              FROM
                formule_resultat_vh                frvh
                  JOIN formule_resultat                fr ON fr.id = frvh.formule_resultat_id
                  JOIN volume_horaire                  vh ON vh.id = frvh.volume_horaire_id AND vh.motif_non_paiement_id IS NULL AND vh.histo_destruction IS NULL
                  JOIN service                          s ON s.id = vh.service_id AND s.intervenant_id = fr.intervenant_id AND s.histo_destruction IS NULL

              UNION ALL

              SELECT
                  'vh_' || vh.id                    id,
                  s.id                              service_id,
                  s.intervenant_id                  intervenant_id,
                  vh.type_volume_horaire_id         type_volume_horaire_id,
                  vhe.etat_volume_horaire_id        etat_volume_horaire_id,
                  s.element_pedagogique_id          element_pedagogique_id,
                  s.etablissement_id                etablissement_id,
                  NULL                              structure_aff_id,
                  NULL                              structure_ens_id,
                  vh.periode_id                     periode_id,
                  vh.type_intervention_id           type_intervention_id,
                  NULL                              fonction_referentiel_id,

                  s.description                     service_description,

                  vh.heures                         heures,
                  0                                 heures_ref,
                  1                                 heures_non_payees,
                  0                                 service_fi,
                  0                                 service_fa,
                  0                                 service_fc,
                  0                                 service_referentiel,
                  0                                 heures_compl_fi,
                  0                                 heures_compl_fa,
                  0                                 heures_compl_fc,
                  0                                 heures_compl_fc_majorees,
                  0                                 heures_compl_referentiel,
                  0                                 total,
                  fr.solde                          solde,
                  NULL                              service_ref_formation,
                  NULL                              commentaires
              FROM
                volume_horaire                  vh
                  JOIN service                     s ON s.id = vh.service_id
                  JOIN v_volume_horaire_etat     vhe ON vhe.volume_horaire_id = vh.id
                  JOIN formule_resultat           fr ON fr.intervenant_id = s.intervenant_id AND fr.type_volume_horaire_id = vh.type_volume_horaire_id AND fr.etat_volume_horaire_id = vhe.etat_volume_horaire_id
              WHERE
                  vh.motif_non_paiement_id IS NOT NULL
                AND vh.histo_destruction IS NULL
                AND s.histo_destruction IS NULL

              UNION ALL

              SELECT
                  'vh_ref_' || vhr.id               id,
                  sr.id                             service_id,
                  sr.intervenant_id                 intervenant_id,
                  fr.type_volume_horaire_id         type_volume_horaire_id,
                  fr.etat_volume_horaire_id         etat_volume_horaire_id,
                  NULL                              element_pedagogique_id,
                  OSE_PARAMETRE.GET_ETABLISSEMENT   etablissement_id,
                  NULL                              structure_aff_id,
                  sr.structure_id                   structure_ens_id,
                  NULL                              periode_id,
                  NULL                              type_intervention_id,
                  sr.fonction_id                    fonction_referentiel_id,

                  NULL                              service_description,

                  0                                 heures,
                  vhr.heures                        heures_ref,
                  0                                 heures_non_payees,
                  0                                 service_fi,
                  0                                 service_fa,
                  0                                 service_fc,
                  frvr.service_referentiel          service_referentiel,
                  0                                 heures_compl_fi,
                  0                                 heures_compl_fa,
                  0                                 heures_compl_fc,
                  0                                 heures_compl_fc_majorees,
                  frvr.heures_compl_referentiel     heures_compl_referentiel,
                  frvr.total                        total,
                  fr.solde                          solde,
                  sr.formation                      service_ref_formation,
                  sr.commentaires                   commentaires
              FROM
                formule_resultat_vh_ref       frvr
                  JOIN formule_resultat           fr ON fr.id = frvr.formule_resultat_id
                  JOIN volume_horaire_ref        vhr ON vhr.id =  frvr.volume_horaire_ref_id
                  JOIN service_referentiel        sr ON sr.id = vhr.service_referentiel_id AND sr.intervenant_id = fr.intervenant_id AND sr.histo_destruction IS NULL

              UNION ALL

              SELECT
                  'vh_0_' || i.id                   id,
                  NULL                              service_id,
                  i.id                              intervenant_id,
                  tvh.id                            type_volume_horaire_id,
                  evh.id                            etat_volume_horaire_id,
                  NULL                              element_pedagogique_id,
                  OSE_PARAMETRE.GET_ETABLISSEMENT   etablissement_id,
                  NULL                              structure_aff_id,
                  NULL                              structure_ens_id,
                  NULL                              periode_id,
                  NULL                              type_intervention_id,
                  NULL                              fonction_referentiel_id,

                  NULL                              service_description,

                  0                                 heures,
                  0                                 heures_ref,
                  0                                 heures_non_payees,
                  0                                 service_fi,
                  0                                 service_fa,
                  0                                 service_fc,
                  0                                 service_referentiel,
                  0                                 heures_compl_fi,
                  0                                 heures_compl_fa,
                  0                                 heures_compl_fc,
                  0                                 heures_compl_fc_majorees,
                  NULL                              heures_compl_referentiel,
                  0                                 total,
                  0                                 solde,
                  NULL                              service_ref_formation,
                  NULL                              commentaires
              FROM
                intervenant i
                  JOIN statut_intervenant si ON si.id = i.statut_id
                  JOIN etat_volume_horaire evh ON evh.code IN ('saisi','valide')
                  JOIN type_volume_horaire tvh ON tvh.code IN ('PREVU','REALISE')
                  LEFT JOIN modification_service_du msd ON msd.intervenant_id = i.id AND msd.histo_destruction IS NULL
                  LEFT JOIN motif_modification_service mms ON mms.id = msd.motif_id
              WHERE
                  i.histo_destruction IS NULL
                AND si.service_statutaire > 0
              GROUP BY
                i.id, si.service_statutaire, evh.id, tvh.id
              HAVING
                    si.service_statutaire + SUM(msd.heures * mms.multiplicateur) = 0


    ), ponds AS (
    SELECT
      ep.id                                          element_pedagogique_id,
      MAX(COALESCE( m.ponderation_service_du, 1))    ponderation_service_du,
      MAX(COALESCE( m.ponderation_service_compl, 1)) ponderation_service_compl
    FROM
      element_pedagogique ep
        LEFT JOIN element_modulateur  em ON em.element_id = ep.id
        AND em.histo_destruction IS NULL
        LEFT JOIN modulateur          m ON m.id = em.modulateur_id
    WHERE
        ep.histo_destruction IS NULL
    GROUP BY
      ep.id
    )
    SELECT
      t.id                            id,
      t.service_id                    service_id,
      i.id                            intervenant_id,
      ti.id                           type_intervenant_id,
      i.annee_id                      annee_id,
      his.histo_modification          service_date_modification,
      t.type_volume_horaire_id        type_volume_horaire_id,
      t.etat_volume_horaire_id        etat_volume_horaire_id,
      etab.id                         etablissement_id,
      saff.id                         structure_aff_id,
      sens.id                         structure_ens_id,
      ose_divers.niveau_formation_id_calc( gtf.id, gtf.pertinence_niveau, etp.niveau ) niveau_formation_id,
      etp.id                          etape_id,
      ep.id                           element_pedagogique_id,
      t.periode_id                    periode_id,
      t.type_intervention_id          type_intervention_id,
      t.fonction_referentiel_id       fonction_referentiel_id,

      tvh.libelle || ' ' || evh.libelle type_etat,
      i.source_code                   intervenant_code,
      i.nom_usuel || ' ' || i.prenom  intervenant_nom,
      i.date_naissance                intervenant_date_naissance,
      si.libelle                      intervenant_statut_libelle,
      ti.code                         intervenant_type_code,
      ti.libelle                      intervenant_type_libelle,
      g.source_code                   intervenant_grade_code,
      g.libelle_court                 intervenant_grade_libelle,
      di.source_code                  intervenant_discipline_code,
      di.libelle_court                intervenant_discipline_libelle,
      saff.libelle_court              service_structure_aff_libelle,

      sens.libelle_court              service_structure_ens_libelle,
      etab.libelle                    etablissement_libelle,
      gtf.libelle_court               groupe_type_formation_libelle,
      tf.libelle_court                type_formation_libelle,
      etp.niveau                      etape_niveau,
      etp.source_code                 etape_code,
      etp.libelle                     etape_libelle,
      ep.source_code                  element_code,
      COALESCE(ep.libelle,to_char(t.service_description)) element_libelle,
      de.source_code                  element_discipline_code,
      de.libelle_court                element_discipline_libelle,
      fr.libelle_long                 fonction_referentiel_libelle,
      ep.taux_fi                      element_taux_fi,
      ep.taux_fc                      element_taux_fc,
      ep.taux_fa                      element_taux_fa,
      t.service_ref_formation         service_ref_formation,
      t.commentaires                  commentaires,
      p.libelle_court                 periode_libelle,
      CASE WHEN ponds.ponderation_service_compl = 1 THEN NULL ELSE ponds.ponderation_service_compl END element_ponderation_compl,
      src.libelle                     element_source_libelle,

      t.heures                        heures,
      t.heures_ref                    heures_ref,
      t.heures_non_payees             heures_non_payees,
      si.service_statutaire           service_statutaire,
      fi.heures_service_modifie       service_du_modifie,
      t.service_fi                    service_fi,
      t.service_fa                    service_fa,
      t.service_fc                    service_fc,
      t.service_referentiel           service_referentiel,
      t.heures_compl_fi               heures_compl_fi,
      t.heures_compl_fa               heures_compl_fa,
      t.heures_compl_fc               heures_compl_fc,
      t.heures_compl_fc_majorees      heures_compl_fc_majorees,
      t.heures_compl_referentiel      heures_compl_referentiel,
      t.total                         total,
      t.solde                         solde,
      v.histo_modification            date_cloture_realise

    FROM
      t
        JOIN intervenant                        i ON i.id     = t.intervenant_id AND i.histo_destruction IS NULL
        JOIN statut_intervenant                si ON si.id    = i.statut_id
        JOIN type_intervenant                  ti ON ti.id    = si.type_intervenant_id
        JOIN etablissement                   etab ON etab.id  = t.etablissement_id
        JOIN type_volume_horaire              tvh ON tvh.id   = t.type_volume_horaire_id
        JOIN etat_volume_horaire              evh ON evh.id   = t.etat_volume_horaire_id
        LEFT JOIN histo_intervenant_service   his ON his.intervenant_id = i.id AND his.type_volume_horaire_id = tvh.id AND his.referentiel = 0
        LEFT JOIN grade                         g ON g.id     = i.grade_id
        LEFT JOIN discipline                   di ON di.id    = i.discipline_id
        LEFT JOIN structure                  saff ON saff.id  = i.structure_id AND ti.code = 'P'
        LEFT JOIN element_pedagogique          ep ON ep.id    = t.element_pedagogique_id
        LEFT JOIN discipline                   de ON de.id    = ep.discipline_id
        LEFT JOIN structure                  sens ON sens.id  = NVL(t.structure_ens_id, ep.structure_id)
        LEFT JOIN periode                       p ON p.id     = t.periode_id
        LEFT JOIN source                      src ON src.id   = ep.source_id OR (ep.source_id IS NULL AND src.code = 'OSE')
        LEFT JOIN etape                       etp ON etp.id   = ep.etape_id
        LEFT JOIN type_formation               tf ON tf.id    = etp.type_formation_id AND tf.histo_destruction IS NULL
        LEFT JOIN groupe_type_formation       gtf ON gtf.id   = tf.groupe_id AND gtf.histo_destruction IS NULL
        LEFT JOIN v_formule_intervenant        fi ON fi.intervenant_id = i.id
        LEFT JOIN ponds                     ponds ON ponds.element_pedagogique_id = ep.id
        LEFT JOIN fonction_referentiel         fr ON fr.id    = t.fonction_referentiel_id
        LEFT JOIN type_validation              tv ON tvh.code = 'REALISE' AND tv.code = 'CLOTURE_REALISE'
        LEFT JOIN validation                    v ON v.intervenant_id = i.id AND v.type_validation_id = tv.id AND v.histo_destruction IS NULL;
/

CREATE OR REPLACE FORCE VIEW V_FORMULE_INTERVENANT AS
SELECT
  i.id                                                                 intervenant_id,
  i.annee_id                                                           annee_id,
  CASE WHEN ti.code = 'P' THEN i.structure_id ELSE NULL END           structure_id,
  ti.code                                                              type_intervenant_code,
  si.service_statutaire                                                heures_service_statutaire,
  si.depassement_service_du_sans_hc                                    depassement_service_du_sans_hc,
  COALESCE( SUM( msd.heures * mms.multiplicateur ), 0 )                heures_service_modifie,
  COALESCE( SUM( msd.heures * mms.multiplicateur * mms.decharge ), 0 ) heures_decharge
FROM
  intervenant                  i
    LEFT JOIN modification_service_du    msd ON msd.intervenant_id = i.id AND msd.histo_destruction IS NULL
    LEFT JOIN motif_modification_service mms ON mms.id = msd.motif_id
    JOIN statut_intervenant          si ON si.id = i.statut_id
    JOIN type_intervenant            ti ON ti.id = si.type_intervenant_id
WHERE
    i.histo_destruction IS NULL
  AND i.id = COALESCE( OSE_FORMULE.GET_INTERVENANT_ID, i.id )
GROUP BY
  i.id, i.annee_id, i.structure_id, ti.code, si.service_statutaire, si.depassement_service_du_sans_hc;
/

CREATE OR REPLACE FORCE VIEW V_FORMULE_VOLUME_HORAIRE AS
SELECT rownum ordre, t.* FROM (
SELECT
  to_number( 1 || vh.id )                                              id,
  vh.id                                                                volume_horaire_id,
  null                                                                 volume_horaire_ref_id,
  s.id                                                                 service_id,
  null                                                                 service_referentiel_id,
  s.intervenant_id                                                     intervenant_id,
  ti.id                                                                type_intervention_id,
  vh.type_volume_horaire_id                                            type_volume_horaire_id,
  vhe.etat_volume_horaire_id                                           etat_volume_horaire_id,

  CASE WHEN ep.id IS NOT NULL THEN ep.taux_fi ELSE 1 END               taux_fi,
  CASE WHEN ep.id IS NOT NULL THEN ep.taux_fa ELSE 0 END               taux_fa,
  CASE WHEN ep.id IS NOT NULL THEN ep.taux_fc ELSE 0 END               taux_fc,
  ep.structure_id                                                      structure_id,
  MAX(COALESCE( m.ponderation_service_du, 1))                          ponderation_service_du,
  MAX(COALESCE( m.ponderation_service_compl, 1))                       ponderation_service_compl,
  COALESCE(tf.service_statutaire,1)                                    service_statutaire,

  vh.heures                                                            heures,
  vh.horaire_debut                                                     horaire_debut,
  vh.horaire_fin                                                       horaire_fin,
  ti.code                                                              type_intervention_code,
  COALESCE(tis.taux_hetd_service,ti.taux_hetd_service,1)               taux_service_du,
  COALESCE(tis.taux_hetd_complementaire,ti.taux_hetd_complementaire,1) taux_service_compl
FROM
            volume_horaire            vh
       JOIN service                    s ON s.id = vh.service_id
       JOIN intervenant                i ON i.id = s.intervenant_id
       JOIN type_intervention         ti ON ti.id = vh.type_intervention_id
       JOIN v_volume_horaire_etat    vhe ON vhe.volume_horaire_id = vh.id

  LEFT JOIN element_pedagogique       ep ON ep.id = s.element_pedagogique_id
  LEFT JOIN etape                      e ON e.id = ep.etape_id
  LEFT JOIN type_formation            tf ON tf.id = e.type_formation_id
  LEFT JOIN element_modulateur        em ON em.element_id = s.element_pedagogique_id
                                        AND em.histo_destruction IS NULL
  LEFT JOIN modulateur                 m ON m.id = em.modulateur_id
  LEFT JOIN type_intervention_statut tis ON tis.type_intervention_id = ti.id AND tis.statut_intervenant_id = i.statut_id
WHERE
  vh.histo_destruction IS NULL
  AND s.histo_destruction IS NULL
  AND vh.heures <> 0
  AND vh.motif_non_paiement_id IS NULL
  AND s.intervenant_id = COALESCE( OSE_FORMULE.GET_INTERVENANT_ID, s.intervenant_id )
GROUP BY
  vh.id, s.id, s.intervenant_id, ti.id, vh.type_volume_horaire_id, vhe.etat_volume_horaire_id, ep.id,
  ep.taux_fi, ep.taux_fa, ep.taux_fc, ep.structure_id, tf.service_statutaire, vh.heures,
  vh.horaire_debut, vh.horaire_fin, tis.taux_hetd_service, tis.taux_hetd_complementaire,
  ti.code, ti.taux_hetd_service, ti.taux_hetd_complementaire

UNION ALL

SELECT
  to_number( 2 || vhr.id )          id,
  null                              volume_horaire_id,
  vhr.id                            volume_horaire_ref_id,
  null                              service_id,
  sr.id                             service_referentiel_id,
  sr.intervenant_id                 intervenant_id,
  null                              type_intervention_id,
  vhr.type_volume_horaire_id        type_volume_horaire_id,
  evh.id                            etat_volume_horaire_id,

  0                                 taux_fi,
  0                                 taux_fa,
  0                                 taux_fc,
  sr.structure_id                   structure_id,
  1                                 ponderation_service_du,
  1                                 ponderation_service_compl,
  COALESCE(fr.service_statutaire,1) service_statutaire,

  vhr.heures                        heures,
  vhr.horaire_debut                 horaire_debut,
  vhr.horaire_fin                   horaire_fin,
  null                              type_intervention_code,
  1                                 taux_service_du,
  1                                 taux_service_compl
FROM
  volume_horaire_ref               vhr
  JOIN service_referentiel          sr ON sr.id = vhr.service_referentiel_id
  JOIN v_volume_horaire_ref_etat  vher ON vher.volume_horaire_ref_id = vhr.id
  JOIN etat_volume_horaire         evh ON evh.id = vher.etat_volume_horaire_id
  JOIN fonction_referentiel         fr ON fr.id = sr.fonction_id
WHERE
  vhr.histo_destruction IS NULL
  AND sr.histo_destruction IS NULL
  AND vhr.heures <> 0
  AND sr.intervenant_id = COALESCE( OSE_FORMULE.GET_INTERVENANT_ID, sr.intervenant_id )

ORDER BY
  horaire_fin, horaire_debut, volume_horaire_id, volume_horaire_ref_id
) t;
/

CREATE OR REPLACE FORCE VIEW V_INDICATEUR_560 AS
SELECT
  rownum                              id,
  i.annee_id                          annee_id,
  i.id                                intervenant_id,
  i.structure_id                      structure_id,
  si.maximum_hetd                     plafond,
  fr.total                            heures
FROM
  intervenant                     i
    JOIN etat_volume_horaire      evh ON evh.code = 'saisi'
    JOIN formule_resultat          fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id
    JOIN statut_intervenant        si ON si.id = i.statut_id
    JOIN type_volume_horaire      tvh ON tvh.id = fr.type_volume_horaire_id AND tvh.code= 'PREVU'
WHERE
      fr.total - fr.heures_compl_fc_majorees > si.maximum_hetd;
/

CREATE OR REPLACE FORCE VIEW V_INDICATEUR_570 AS
SELECT
  rownum                              id,
  i.annee_id                          annee_id,
  i.id                                intervenant_id,
  i.structure_id                      structure_id,
  si.maximum_hetd                     plafond,
  fr.total                            heures
FROM
  intervenant                     i
    JOIN etat_volume_horaire      evh ON evh.code = 'saisi'
    JOIN formule_resultat          fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id
    JOIN statut_intervenant        si ON si.id = i.statut_id
    JOIN type_volume_horaire      tvh ON tvh.id = fr.type_volume_horaire_id AND tvh.code= 'REALISE'
WHERE
      fr.total - fr.heures_compl_fc_majorees > si.maximum_hetd;
/

CREATE OR REPLACE FORCE VIEW V_TBL_CONTRAT AS
  WITH t AS (
    SELECT
      i.annee_id                                                                annee_id,
      i.id                                                                      intervenant_id,
      si.peut_avoir_contrat                                                     peut_avoir_contrat,
      NVL(ep.structure_id, i.structure_id)                                      structure_id,
      CASE WHEN evh.code IN ('contrat-edite','contrat-signe') THEN 1 ELSE 0 END edite,
      CASE WHEN evh.code IN ('contrat-signe')                 THEN 1 ELSE 0 END signe
    FROM
      intervenant                 i

        JOIN statut_intervenant         si ON si.id = i.statut_id

        JOIN service                     s ON s.intervenant_id = i.id
        AND s.histo_destruction IS NULL

        JOIN type_volume_horaire       tvh ON tvh.code = 'PREVU'

        JOIN volume_horaire             vh ON vh.service_id = s.id
        AND vh.histo_destruction IS NULL
        AND vh.heures <> 0
        AND vh.type_volume_horaire_id = tvh.id
        AND vh.motif_non_paiement_id IS NULL

        JOIN v_volume_horaire_etat     vhe ON vhe.volume_horaire_id = vh.id

        JOIN etat_volume_horaire       evh ON evh.id = vhe.etat_volume_horaire_id
        AND evh.code IN ('valide', 'contrat-edite', 'contrat-signe')

        JOIN element_pedagogique        ep ON ep.id = s.element_pedagogique_id

    WHERE
        i.histo_destruction IS NULL
      AND NOT (si.peut_avoir_contrat = 0 AND evh.code = 'valide')

    UNION ALL

    SELECT
      i.annee_id                                                                annee_id,
      i.id                                                                      intervenant_id,
      si.peut_avoir_contrat                                                     peut_avoir_contrat,
      s.structure_id                                                            structure_id,
      CASE WHEN evh.code IN ('contrat-edite','contrat-signe') THEN 1 ELSE 0 END edite,
      CASE WHEN evh.code IN ('contrat-signe')                 THEN 1 ELSE 0 END signe
    FROM
      intervenant                 i

        JOIN statut_intervenant         si ON si.id = i.statut_id

        JOIN service_referentiel         s ON s.intervenant_id = i.id
        AND s.histo_destruction IS NULL

        JOIN type_volume_horaire       tvh ON tvh.code = 'PREVU'

        JOIN volume_horaire_ref         vh ON vh.service_referentiel_id = s.id
        AND vh.histo_destruction IS NULL
        AND vh.heures <> 0
        AND vh.type_volume_horaire_id = tvh.id

        JOIN v_volume_horaire_ref_etat vhe ON vhe.volume_horaire_ref_id = vh.id

        JOIN etat_volume_horaire       evh ON evh.id = vhe.etat_volume_horaire_id
        AND evh.code IN ('valide', 'contrat-edite', 'contrat-signe')

    WHERE
        i.histo_destruction IS NULL
      AND NOT (si.peut_avoir_contrat = 0 AND evh.code = 'valide')
    )
    SELECT
      annee_id,
      intervenant_id,
      peut_avoir_contrat,
      structure_id,
      count(*) as nbvh,
      sum(edite) as edite,
      sum(signe) as signe
    FROM
      t
    GROUP BY
      annee_id,
      intervenant_id,
      peut_avoir_contrat,
      structure_id;
/

CREATE OR REPLACE FORCE VIEW V_INDICATEUR_1211 AS
SELECT
  i.id id,
  i.annee_id,
  i.id intervenant_id,
  i.structure_id,
  AVG(t.plafond)  plafond,
  AVG(t.heures)   heures
FROM
  (
    SELECT
      vhr.type_volume_horaire_id        type_volume_horaire_id,
      sr.intervenant_id                 intervenant_id,
      fr.plafond                        plafond,
      fr.id                             fr_id,
      SUM(vhr.heures)                   heures
    FROM
      service_referentiel       sr
        JOIN fonction_referentiel      frf ON frf.id = sr.fonction_id
        JOIN fonction_referentiel      fr ON fr.id = frf.parent_id
        JOIN volume_horaire_ref       vhr ON vhr.service_referentiel_id = sr.id AND vhr.histo_destruction IS NULL
        JOIN type_volume_horaire      tvh ON tvh.id = vhr.type_volume_horaire_id AND tvh.code= 'PREVU'
    WHERE
        sr.histo_destruction IS NULL
    GROUP BY
      vhr.type_volume_horaire_id,
      sr.intervenant_id,
      fr.plafond,
      fr.id
  ) t
    JOIN intervenant i ON i.id = t.intervenant_id
WHERE
    t.heures > t.plafond
  /*i.id*/
GROUP BY
  t.type_volume_horaire_id,
  i.annee_id,
  i.id,
  i.structure_id;
/

CREATE OR REPLACE FORCE VIEW V_INDICATEUR_1221 AS
SELECT
  i.id id,
  i.annee_id,
  i.id intervenant_id,
  i.structure_id,
  AVG(t.plafond)  plafond,
  AVG(t.heures)   heures
FROM
  (
    SELECT
      vhr.type_volume_horaire_id        type_volume_horaire_id,
      sr.intervenant_id                 intervenant_id,
      fr.plafond                        plafond,
      fr.id                             fr_id,
      SUM(vhr.heures)                   heures
    FROM
      service_referentiel       sr
        JOIN fonction_referentiel      frf ON frf.id = sr.fonction_id
        JOIN fonction_referentiel      fr ON fr.id = frf.parent_id
        JOIN volume_horaire_ref       vhr ON vhr.service_referentiel_id = sr.id AND vhr.histo_destruction IS NULL
        JOIN type_volume_horaire      tvh ON tvh.id = vhr.type_volume_horaire_id AND tvh.code= 'REALISE'
    WHERE
        sr.histo_destruction IS NULL
    GROUP BY
      vhr.type_volume_horaire_id,
      sr.intervenant_id,
      fr.plafond,
      fr.id
  ) t
    JOIN intervenant i ON i.id = t.intervenant_id
WHERE
    t.heures > t.plafond
GROUP BY
  t.type_volume_horaire_id,
  i.annee_id,
  i.id,
  i.structure_id;
/

CREATE OR REPLACE FORCE VIEW V_INDICATEUR_1230 AS
SELECT
  t.intervenant_id    id,
  t.annee_id          annee_id,
  t.intervenant_id    intervenant_id,
  t.structure_id      structure_id,
  t.plafond           plafond,
  t.heures            heures
FROM
  (
    SELECT DISTINCT
      i.id                              intervenant_id,
      i.annee_id                        annee_id,
      s.plafond_referentiel             plafond,
      s.id                              structure_id,
      s.libelle_court                   structure_libelle,
      SUM(vhr.heures) OVER (PARTITION BY s.id,vhr.type_volume_horaire_id,i.annee_id) heures
    FROM
             service_referentiel       sr
        JOIN intervenant                i ON i.id = sr.intervenant_id
        JOIN structure                  s ON s.id = sr.structure_id AND s.plafond_referentiel IS NOT NULL
        JOIN volume_horaire_ref       vhr ON vhr.service_referentiel_id = sr.id AND vhr.histo_destruction IS NULL
        JOIN type_volume_horaire      tvh ON tvh.id = vhr.type_volume_horaire_id AND tvh.code = 'PREVU'
    WHERE
        sr.histo_destruction IS NULL
  ) t
WHERE
    t.heures > t.plafond;
/

CREATE OR REPLACE FORCE VIEW V_INDICATEUR_1240 AS
SELECT
  t.intervenant_id    id,
  t.annee_id          annee_id,
  t.intervenant_id    intervenant_id,
  t.structure_id      structure_id,
  t.plafond           plafond,
  t.heures            heures
FROM
  (
    SELECT DISTINCT
      i.id                              intervenant_id,
      i.annee_id                        annee_id,
      s.plafond_referentiel             plafond,
      s.id                              structure_id,
      s.libelle_court                   structure_libelle,
      SUM(vhr.heures) OVER (PARTITION BY s.id,vhr.type_volume_horaire_id,i.annee_id) heures
    FROM
             service_referentiel       sr
        JOIN intervenant                i ON i.id = sr.intervenant_id
        JOIN structure                  s ON s.id = sr.structure_id AND s.plafond_referentiel IS NOT NULL
        JOIN volume_horaire_ref       vhr ON vhr.service_referentiel_id = sr.id AND vhr.histo_destruction IS NULL
        JOIN type_volume_horaire      tvh ON tvh.id = vhr.type_volume_horaire_id AND tvh.code = 'REALISE'
    WHERE
        sr.histo_destruction IS NULL
  ) t
WHERE
    t.heures > t.plafond;
/



--------------------------------------------------
-- Modification des triggers
--------------------------------------------------

CREATE OR REPLACE TRIGGER "F_STATUT_INTERVENANT"
  AFTER UPDATE OF
    service_statutaire,
    depassement,
    type_intervenant_id,
    non_autorise
  ON STATUT_INTERVENANT
  FOR EACH ROW
BEGIN return; /* Désactivation du trigger... */

IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

FOR p IN (

  SELECT DISTINCT
    fr.intervenant_id
  FROM
    intervenant i
      JOIN formule_resultat fr ON fr.intervenant_id = i.id
  WHERE
    (i.statut_id = :NEW.id OR i.statut_id = :OLD.id)
    AND i.histo_destruction IS NULL

  ) LOOP

  UNICAEN_TBL.DEMANDE_CALCUL('formule', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id) );

END LOOP;
END;
/

CREATE OR REPLACE TRIGGER "INDIC_TRG_MODIF_DOSSIER"
  AFTER INSERT OR UPDATE OF NOM_USUEL, NOM_PATRONYMIQUE, PRENOM, CIVILITE_ID, ADRESSE, RIB, DATE_NAISSANCE ON "DOSSIER"

  FOR EACH ROW
  /**
   * But : mettre à jour la liste des PJ attendues.
   */
DECLARE
  i integer := 1;
  intervenantId NUMERIC;
  found integer;
  estCreationDossier integer;
  type array_t is table of varchar2(1024);

  attrNames     array_t := array_t();
  attrOldVals   array_t := array_t();
  attrNewVals   array_t := array_t();

  -- valeurs importées (format texte) :
  impSourceName source.libelle%type;
  impNomUsuel   indic_modif_dossier.ATTR_NEW_VALUE%type;
  impNomPatro   indic_modif_dossier.ATTR_NEW_VALUE%type;
  impPrenom     indic_modif_dossier.ATTR_NEW_VALUE%type;
  impCivilite   indic_modif_dossier.ATTR_NEW_VALUE%type;
  impDateNaiss  indic_modif_dossier.ATTR_NEW_VALUE%type;
  impAdresse    indic_modif_dossier.ATTR_NEW_VALUE%type;
  impRib        indic_modif_dossier.ATTR_NEW_VALUE%type;
  -- anciennes valeurs dans le dossier (format texte) :
  oldSourceName source.libelle%type;
  oldNomUsuel   indic_modif_dossier.ATTR_NEW_VALUE%type;
  oldNomPatro   indic_modif_dossier.ATTR_NEW_VALUE%type;
  oldPrenom     indic_modif_dossier.ATTR_NEW_VALUE%type;
  oldCivilite   indic_modif_dossier.ATTR_NEW_VALUE%type;
  oldDateNaiss  indic_modif_dossier.ATTR_NEW_VALUE%type;
  oldAdresse    indic_modif_dossier.ATTR_NEW_VALUE%type;
  oldRib        indic_modif_dossier.ATTR_NEW_VALUE%type;
  -- nouvelles valeurs dans le dossier (format texte) :
  newSourceName source.libelle%type;
  newNomUsuel   indic_modif_dossier.ATTR_NEW_VALUE%type;
  newNomPatro   indic_modif_dossier.ATTR_NEW_VALUE%type;
  newPrenom     indic_modif_dossier.ATTR_NEW_VALUE%type;
  newCivilite   indic_modif_dossier.ATTR_NEW_VALUE%type;
  newDateNaiss  indic_modif_dossier.ATTR_NEW_VALUE%type;
  newAdresse    indic_modif_dossier.ATTR_NEW_VALUE%type;
  newRib        indic_modif_dossier.ATTR_NEW_VALUE%type;
BEGIN
  --
  -- Témoin indiquant s'il s'agit d'une création de dossier (insert).
  --
  estCreationDossier := case when inserting then 1 else 0 end;

  --
  -- Fetch source OSE.
  --
  select s.libelle into newSourceName from source s where s.code = 'OSE';

  --
  -- Fetch et formattage texte des valeurs importées.
  --
  select
    i.id,
    s.libelle,
    nvl(i.NOM_USUEL, '(Aucun)'),
    nvl(i.NOM_PATRONYMIQUE, '(Aucun)'),
    nvl(i.PRENOM, '(Aucun)'),
    nvl(c.libelle_court, '(Aucune)'),
    nvl(to_char(i.DATE_NAISSANCE, 'DD/MM/YYYY'), '(Aucune)'),
    nvl(ose_divers.formatted_rib(i.bic, i.iban), '(Aucun)'),
    case when a.id is not null
           then ose_divers.formatted_adresse(a.NO_VOIE, a.NOM_VOIE, a.BATIMENT, a.MENTION_COMPLEMENTAIRE, a.LOCALITE, a.CODE_POSTAL, a.VILLE, a.PAYS_LIBELLE)
         else '(Aucune)'
      end
    into
      intervenantId,
      oldSourceName,
      impNomUsuel,
      impNomPatro,
      impPrenom,
      impCivilite,
      impDateNaiss,
      impRib,
      impAdresse
  from intervenant i
         join source s on s.id = i.source_id
         left join civilite c on c.id = i.civilite_id
         left join adresse_intervenant a on a.intervenant_id = i.id AND a.histo_destruction IS NULL
  where i.id = :NEW.intervenant_id;

  --
  -- Anciennes valeurs dans le cas d'une création de dossier : ce sont les valeurs importées.
  --
  if (1 = estCreationDossier) then
    --dbms_output.put_line('inserting');
    oldNomUsuel  := impNomUsuel;
    oldNomPatro  := impNomPatro;
    oldPrenom    := impPrenom;
    oldCivilite  := impCivilite;
    oldDateNaiss := impDateNaiss;
    oldAdresse   := impAdresse;
    oldRib       := impRib;
    --
    -- Anciennes valeurs dans le cas d'une mise à jour du dossier.
    --
  else
    --dbms_output.put_line('updating');
    oldNomUsuel     := trim(:OLD.NOM_USUEL);
    oldNomPatro     := trim(:OLD.NOM_PATRONYMIQUE);
    oldPrenom       := trim(:OLD.PRENOM);
    oldDateNaiss    := case when :OLD.DATE_NAISSANCE is null then '(Aucune)' else to_char(:OLD.DATE_NAISSANCE, 'DD/MM/YYYY') end;
    oldAdresse      := trim(:OLD.ADRESSE);
    oldRib          := trim(:OLD.RIB);
    if :OLD.CIVILITE_ID is not null then
      select c.libelle_court into oldCivilite from civilite c where c.id = :OLD.CIVILITE_ID;
    else
      oldCivilite := '(Aucune)';
    end if;
    select s.libelle into oldSourceName from source s where s.code = 'OSE';
  end if;

  --
  -- Nouvelles valeurs saisies.
  --
  newNomUsuel   := trim(:NEW.NOM_USUEL);
  newNomPatro   := trim(:NEW.NOM_PATRONYMIQUE);
  newPrenom     := trim(:NEW.PRENOM);
  newDateNaiss  := case when :NEW.DATE_NAISSANCE is null then '(Aucune)' else to_char(:NEW.DATE_NAISSANCE, 'DD/MM/YYYY') end;
  newAdresse    := trim(:NEW.ADRESSE);
  newRib        := trim(:NEW.RIB);
  if :NEW.CIVILITE_ID is not null then
    select c.libelle_court into newCivilite from civilite c where c.id = :NEW.CIVILITE_ID;
  else
    newCivilite := '(Aucune)';
  end if;

  --
  -- Détection des différences.
  --
  if newNomUsuel <> oldNomUsuel then
    --dbms_output.put_line('NOM_USUEL ' || sourceLib || ' = ' || oldNomUsuel || ' --> NOM_USUEL OSE = ' || :NEW.NOM_USUEL);
    attrNames.extend(1);
    attrOldVals.extend(1);
    attrNewVals.extend(1);
    attrNames(i)   := 'Nom usuel';
    attrOldVals(i) := oldNomUsuel;
    attrNewVals(i) := newNomUsuel;
    i := i + 1;
  end if;
  if newNomPatro <> oldNomPatro then
    --dbms_output.put_line('NOM_PATRONYMIQUE ' || sourceLib || ' = ' || oldNomPatro || ' --> NOM_PATRONYMIQUE OSE = ' || :NEW.NOM_PATRONYMIQUE);
    attrNames.extend(1);
    attrOldVals.extend(1);
    attrNewVals.extend(1);
    attrNames(i)   := 'Nom de naissance';
    attrOldVals(i) := oldNomPatro;
    attrNewVals(i) := newNomPatro;
    i := i + 1;
  end if;
  if newPrenom <> oldPrenom then
    --dbms_output.put_line('PRENOM ' || sourceLib || ' = ' || oldPrenom || ' --> PRENOM OSE = ' || :NEW.PRENOM);
    attrNames.extend(1);
    attrOldVals.extend(1);
    attrNewVals.extend(1);
    attrNames(i)   := 'Prénom';
    attrOldVals(i) := oldPrenom;
    attrNewVals(i) := newPrenom;
    i := i + 1;
  end if;
  if newCivilite <> oldCivilite then
    --dbms_output.put_line('CIVILITE_ID ' || sourceLib || ' = ' || oldCivilite || ' --> CIVILITE_ID OSE = ' || :NEW.CIVILITE_ID);
    attrNames.extend(1);
    attrOldVals.extend(1);
    attrNewVals.extend(1);
    attrNames(i)   := 'Civilité';
    attrOldVals(i) := oldCivilite;
    attrNewVals(i) := newCivilite;
    i := i + 1;
  end if;
  if newDateNaiss <> oldDateNaiss then
    --dbms_output.put_line('DATE_NAISSANCE ' || sourceLib || ' = ' || oldDateNaiss || ' --> DATE_NAISSANCE OSE = ' || :NEW.DATE_NAISSANCE);
    attrNames.extend(1);
    attrOldVals.extend(1);
    attrNewVals.extend(1);
    attrNames(i)   := 'Date de naissance';
    attrOldVals(i) := oldDateNaiss;
    attrNewVals(i) := newDateNaiss;
    i := i + 1;
  end if;
  if newAdresse <> oldAdresse then
    --dbms_output.put_line('ADRESSE ' || sourceLib || ' = ' || oldAdresse || ' --> ADRESSE OSE = ' || :NEW.ADRESSE);
    attrNames.extend(1);
    attrOldVals.extend(1);
    attrNewVals.extend(1);
    attrNames(i)   := 'Adresse postale';
    attrOldVals(i) := oldAdresse;
    attrNewVals(i) := newAdresse;
    i := i + 1;
  end if;
  if oldRib is null or newRib <> oldRib then
    --dbms_output.put_line('RIB ' || sourceLib || ' = ' || oldRib || ' --> RIB OSE = ' || :NEW.RIB);
    attrNames.extend(1);
    attrOldVals.extend(1);
    attrNewVals.extend(1);
    attrNames(i)   := 'RIB';
    attrOldVals(i) := oldRib;
    attrNewVals(i) := newRib;
    i := i + 1;
  end if;

  --
  -- Enregistrement des différences.
  --
  for i in 1 .. attrNames.count loop
    --dbms_output.put_line(attrNames(i) || ' ' || oldSourceName || ' = ' || attrOldVals(i) || ' --> ' || attrNames(i) || ' ' || newSourceName || ' = ' || attrNewVals(i));

    -- vérification que la même modif n'est pas déjà consignée
    select count(*) into found from indic_modif_dossier
    where INTERVENANT_ID = intervenantId
      and ATTR_NAME = attrNames(i)
      and ATTR_OLD_VALUE = to_char(attrOldVals(i))
      and ATTR_NEW_VALUE = to_char(attrNewVals(i));
    if found > 0 then
      continue;
    end if;

    insert into INDIC_MODIF_DOSSIER(
      id,
      INTERVENANT_ID,
      ATTR_NAME,
      ATTR_OLD_SOURCE_NAME,
      ATTR_OLD_VALUE,
      ATTR_NEW_SOURCE_NAME,
      ATTR_NEW_VALUE,
      EST_CREATION_DOSSIER, -- témoin indiquant s'il s'agit d'une création ou d'une modification de dossier
      HISTO_CREATION,       -- NB: date de modification du dossier
      HISTO_CREATEUR_ID,    -- NB: auteur de la modification du dossier
      HISTO_MODIFICATION,
      HISTO_MODIFICATEUR_ID
    )
    values (
             indic_modif_dossier_id_seq.nextval,
             intervenantId,
             attrNames(i),
             oldSourceName,
             to_char(attrOldVals(i)),
             newSourceName,
             to_char(attrNewVals(i)),
             estCreationDossier,
             :NEW.HISTO_MODIFICATION,
             :NEW.HISTO_MODIFICATEUR_ID,
             :NEW.HISTO_MODIFICATION,
             :NEW.HISTO_MODIFICATEUR_ID
           );
  end loop;

END;
/




-- requêtes non générées

insert into formule_test_structure (id, libelle, universite) values (1, 'Droit', 0);
insert into formule_test_structure (id, libelle, universite) values (2, 'Histoire', 0);
insert into formule_test_structure (id, libelle, universite) values (3, 'IAE', 0);
insert into formule_test_structure (id, libelle, universite) values (4, 'IUT', 0);
insert into formule_test_structure (id, libelle, universite) values (5, 'Lettres', 0);
insert into formule_test_structure (id, libelle, universite) values (6, 'Santé', 0);
insert into formule_test_structure (id, libelle, universite) values (7, 'Sciences', 0);
insert into formule_test_structure (id, libelle, universite) values (8, 'SUAPS', 0);
insert into formule_test_structure (id, libelle, universite) values (9, 'Université', 1);


/* Formules, paramètres et nouveaux privilèges : attention à ne pas les insérer plusieurs fois!! */

INSERT INTO FORMULE(id, libelle, package_name, procedure_name)
values (1, 'Université de Caen', 'FORMULE_UNICAEN', 'CALCUL_RESULTAT');

INSERT INTO FORMULE(id, libelle, package_name, procedure_name)
values (2, 'Université de Montpellier', 'FORMULE_MONTPELLIER', 'CALCUL_RESULTAT');

INSERT INTO FORMULE (ID, LIBELLE, PACKAGE_NAME, PROCEDURE_NAME)
VALUES (3, 'Université Le Havre Normandie', 'FORMULE_ULHN', 'CALCUL_RESULTAT');

INSERT INTO FORMULE (ID, LIBELLE, PACKAGE_NAME, PROCEDURE_NAME, VH_PARAM_1_LIBELLE)
VALUES (4, 'Université de Nanterre', 'FORMULE_NANTERRE', 'CALCUL_RESULTAT', 'Code composante');

INSERT INTO FORMULE (ID, LIBELLE, PACKAGE_NAME, PROCEDURE_NAME, I_PARAM_1_LIBELLE)
VALUES (5, 'Université de Bretagne Occidentale', 'FORMULE_UBO', 'CALCUL_RESULTAT', 'Enseignant Chercheur (Oui ou Non)');

INSERT INTO FORMULE(id, libelle, package_name, procedure_name)
values (6, 'Ensicaen', 'FORMULE_ENSICAEN', 'CALCUL_RESULTAT');

-- Mise à jour des paramètres
ALTER TABLE PARAMETRE ADD CONSTRAINT PARAMETRE_UK UNIQUE (NOM) ENABLE;
/

UPDATE PARAMETRE SET DESCRIPTION = 'Scénario utilisé pour confronter les charges d''enseignement aux services des intervenants' WHERE nom = 'scenario_charges_services';
INSERT INTO parametre (
  id, nom,
  valeur, description,
  histo_creation, histo_createur_id,
  histo_modification, histo_modificateur_id
) VALUES (
  parametre_id_seq.nextval, 'formule',
  (select id from formule where package_name='FORMULE_UNICAEN'), 'Formule de calcul',
  sysdate, (select id from utilisateur where username='oseappli'),
  sysdate, (select id from utilisateur where username='oseappli')
);
DELETE FROM parametre WHERE nom IN ('formule_package_name', 'formule_function_name');



-- Nouveaux privilèges
INSERT INTO CATEGORIE_PRIVILEGE (ID,CODE,LIBELLE) VALUES (
  CATEGORIE_PRIVILEGE_ID_SEQ.nextval,
  'domaines-fonctionnels',
  'Domaines fonctionnels'
);
INSERT INTO CATEGORIE_PRIVILEGE (ID,CODE,LIBELLE) VALUES (
  CATEGORIE_PRIVILEGE_ID_SEQ.nextval,
  'motifs-modification-service-du',
  'Motifs de modification de service dû'
);
INSERT INTO CATEGORIE_PRIVILEGE (ID,CODE,LIBELLE) VALUES (
  CATEGORIE_PRIVILEGE_ID_SEQ.nextval,
  'structures',
  'Structures'
);
INSERT INTO CATEGORIE_PRIVILEGE (ID,CODE,LIBELLE) VALUES (
  CATEGORIE_PRIVILEGE_ID_SEQ.nextval,
  'formule',
  'Formule de calcul'
);

INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE)
SELECT
       privilege_id_seq.nextval id,
       (SELECT id FROM CATEGORIE_PRIVILEGE WHERE code = t1.c ) CATEGORIE_ID,
       t1.p CODE,
       t1.l LIBELLE,
       (SELECT count(*) FROM PRIVILEGE WHERE categorie_id = (SELECT id FROM CATEGORIE_PRIVILEGE WHERE code = t1.c )) + rownum ORDRE
FROM (

   SELECT 'odf' c, 'grands-types-diplome-visualisation' p, 'Grands types de diplômes (visualisation)' l FROM dual
   UNION ALL SELECT 'odf' c, 'grands-types-diplome-edition' p, 'Grands types de diplômes (édition)' l FROM dual

   UNION ALL SELECT 'odf' c, 'types-diplome-visualisation' p, 'Types de diplômes (visualisation)' l FROM dual
   UNION ALL SELECT 'odf' c, 'types-diplome-edition' p, 'Types de diplômes (édition)' l FROM dual

   UNION ALL SELECT 'motifs-modification-service-du' c, 'visualisation' p, 'Administration (visualisation)' l FROM dual
   UNION ALL SELECT 'motifs-modification-service-du' c, 'edition' p, 'Administration (édition)' l FROM dual

   UNION ALL SELECT 'structures' c, 'administration-visualisation' p, 'Administration (visualisation)' l FROM dual
   UNION ALL SELECT 'structures' c, 'administration-edition' p, 'Administration (édition)' l FROM dual

   UNION ALL SELECT 'budget' c, 'types-ressources-visualisation' p, 'Types de ressources - Visualisation' l FROM dual
   UNION ALL SELECT 'budget' c, 'types-ressources-edition' p, 'Types de ressources - Édition' l FROM dual

   UNION ALL SELECT 'domaines-fonctionnels' c, 'administration-visualisation' p, 'Administration (visualisation)' l FROM dual
   UNION ALL SELECT 'domaines-fonctionnels' c, 'administration-edition' p,	'Administration (édition)' l FROM dual

   UNION ALL SELECT 'budget' c, 'cc-activite-visualisation' p, 'CC activité - Visualisation' l FROM dual
   UNION ALL SELECT 'budget' c, 'cc-activite-edition' p, 'CC activité - Édition' l FROM dual

   UNION ALL SELECT 'formule' c, 'tests' p, 'Tests' l FROM dual

   UNION ALL SELECT 'cloture' c, 'edition-services-avec-mep' p, 'Modification des services après clôture et mises en paiement' l FROM dual

) t1;

DELETE FROM privilege WHERE code = 'visualisation' AND categorie_id = (SELECT id from categorie_privilege WHERE code = 'mise-en-paiement');

-- Suppression de l'usage des CLOBS partout où c'est possible
-- si les colonnes concernées szont déjà en VARCHAR, alors il n'est pas nécessaire d'exécuter ces lignes
ALTER TABLE PARAMETRE ADD (NVALEUR VARCHAR2(200) );
ALTER TABLE PARAMETRE ADD (NDESCRIPTION VARCHAR2(500) );

UPDATE parametre SET nvaleur = valeur, ndescription = description;
UPDATE parametre SET valeur = null, description = null;

ALTER TABLE PARAMETRE DROP COLUMN VALEUR;
ALTER TABLE PARAMETRE DROP COLUMN DESCRIPTION;

ALTER TABLE PARAMETRE RENAME COLUMN NVALEUR TO VALEUR;
ALTER TABLE PARAMETRE RENAME COLUMN NDESCRIPTION TO DESCRIPTION;

/
alter trigger F_MODIF_SERVICE_DU disable
/
alter trigger F_MODIF_SERVICE_DU_S disable
/
ALTER TABLE MODIFICATION_SERVICE_DU ADD (NCOMMENTAIRES VARCHAR2(4000) );
UPDATE MODIFICATION_SERVICE_DU SET NCOMMENTAIRES = COMMENTAIRES;
ALTER TABLE MODIFICATION_SERVICE_DU DROP COLUMN COMMENTAIRES;
ALTER TABLE MODIFICATION_SERVICE_DU RENAME COLUMN NCOMMENTAIRES TO COMMENTAIRES;
/
alter trigger F_MODIF_SERVICE_DU enable
/
alter trigger F_MODIF_SERVICE_DU_S enable
/

ALTER TABLE SERVICE ADD (NDESCRIPTION VARCHAR2(4000) );
/
alter trigger SERVICE_CK disable
/
UPDATE SERVICE SET NDESCRIPTION = DESCRIPTION;
ALTER TABLE SERVICE DROP COLUMN DESCRIPTION;
ALTER TABLE SERVICE RENAME COLUMN NDESCRIPTION TO DESCRIPTION;
/
alter trigger SERVICE_CK enable
/

ALTER TABLE SYNC_LOG ADD (NMESSAGE VARCHAR2(4000) );
UPDATE SYNC_LOG SET NMESSAGE = MESSAGE;
ALTER TABLE SYNC_LOG DROP COLUMN MESSAGE;
ALTER TABLE SYNC_LOG RENAME COLUMN NMESSAGE TO MESSAGE;

-- fin de la suppression de l'usage des CLOBS partout où c'est possible

-- Attention à ne pas refaire plusieurs fois ces opérations sur la BDD (si vous avez déjà installé une version 8.1 beta)
ALTER TABLE fonction_referentiel ADD (
  parent_id NUMBER(*, 0)
  );
ALTER TABLE fonction_referentiel
  ADD CONSTRAINT fr_parent_fk FOREIGN KEY ( parent_id )
    REFERENCES fonction_referentiel ( id )
      NOT DEFERRABLE;


INSERT INTO plafond (ID, CODE, LIBELLE) VALUES (
  plafond_id_seq.nextval, 'ref-par-fonction-mere', 'Heures max. de référentiel par intervenant et par type de fonction référentielle'
);
INSERT INTO plafond (ID, CODE, LIBELLE) VALUES (
  plafond_id_seq.nextval, 'ref-par-structure', 'Heures max. de référentiel par structure'
);

INSERT INTO indicateur (
  ID,
  TYPE,
  ORDRE,
  ENABLED,
  NUMERO,
  LIBELLE_SINGULIER,
  LIBELLE_PLURIEL,
  ROUTE,
  TEM_DISTINCT,
  TEM_NOT_STRUCTURE,
  MESSAGE
) VALUES (
  indicateur_id_seq.nextval,
  'Enseignements et référentiel <em>Permanents</em>',
  1211,
  1,
  1211,
  '%s intervenant a des heures de référentiel <i>prévisionnel</i> dépassant le plafond autorisé pour le type de fonction correspondant',
  '%s intervenants ont des heures de référentiel <i>prévisionnel</i> dépassant le plafond autorisé pour le type de fonction correspondant',
  'intervenant/services',
  1,
  0,
  NULL
);

INSERT INTO indicateur (
  ID,
  TYPE,
  ORDRE,
  ENABLED,
  NUMERO,
  LIBELLE_SINGULIER,
  LIBELLE_PLURIEL,
  ROUTE,
  TEM_DISTINCT,
  TEM_NOT_STRUCTURE,
  MESSAGE
) VALUES (
  indicateur_id_seq.nextval,
  'Enseignements et référentiel <em>Permanents</em>',
  1221,
  1,
  1221,
  '%s intervenant a des heures de référentiel <i>réalisé</i> dépassant le plafond autorisé pour le type de fonction correspondant',
  '%s intervenants ont des heures de référentiel <i>réalisé</i> dépassant le plafond autorisé pour le type de fonction correspondant',
  'intervenant/services',
  1,
  0,
  NULL
);

INSERT INTO indicateur (
  ID,
  TYPE,
  ORDRE,
  ENABLED,
  NUMERO,
  LIBELLE_SINGULIER,
  LIBELLE_PLURIEL,
  ROUTE,
  TEM_DISTINCT,
  TEM_NOT_STRUCTURE,
  MESSAGE
) VALUES (
  indicateur_id_seq.nextval,
  'Enseignements et référentiel <em>Permanents</em>',
  1230,
  1,
  1230,
  '%s intervenant a des heures de référentiel <i>prévisionnel</i> dépassant le plafond autorisé pour la composante correspondante',
  '%s intervenants ont des heures de référentiel <i>prévisionnel</i> dépassant le plafond autorisé pour la composante correspondante',
  'intervenant/services',
  1,
  0,
  NULL
);

INSERT INTO indicateur (
  ID,
  TYPE,
  ORDRE,
  ENABLED,
  NUMERO,
  LIBELLE_SINGULIER,
  LIBELLE_PLURIEL,
  ROUTE,
  TEM_DISTINCT,
  TEM_NOT_STRUCTURE,
  MESSAGE
) VALUES (
  indicateur_id_seq.nextval,
  'Enseignements et référentiel <em>Permanents</em>',
  1240,
  1,
  1240,
  '%s intervenant a des heures de référentiel <i>réalisé</i> dépassant le plafond autorisé pour la composante correspondante',
  '%s intervenants ont des heures de référentiel <i>réalisé</i> dépassant le plafond autorisé pour la composante correspondante','intervenant/services',
  1,
  0,
  NULL
);

ALTER TABLE structure ADD (
  plafond_referentiel FLOAT
);



CREATE OR REPLACE FORCE VIEW V_FORMULE_LOCAL_VH_PARAMS AS
SELECT
  null volume_horaire_id,
  null volume_horaire_ref_id,
  null param_1,
  null param_2,
  null param_3,
  null param_4,
  null param_5
FROM
  dual;

CREATE OR REPLACE FORCE VIEW V_FORMULE_LOCAL_I_PARAMS AS
SELECT
  null intervenant_id,
  null param_1,
  null param_2,
  null param_3,
  null param_4,
  null param_5
FROM
  dual;


-- batterie de tests de formules
Insert into FORMULE_TEST_INTERVENANT (ID,LIBELLE,FORMULE_ID,ANNEE_ID,TYPE_INTERVENANT_ID,STRUCTURE_TEST_ID,TYPE_VOLUME_HORAIRE_ID,ETAT_VOLUME_HORAIRE_ID,HEURES_DECHARGE,HEURES_SERVICE_STATUTAIRE,HEURES_SERVICE_MODIFIE,DEPASSEMENT_SERVICE_DU_SANS_HC,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,A_SERVICE_DU,C_SERVICE_DU,TAUX_TP_SERVICE_DU,TAUX_AUTRE_SERVICE_DU,TAUX_AUTRE_SERVICE_COMPL,TAUX_CM_SERVICE_DU,TAUX_CM_SERVICE_COMPL,TAUX_TP_SERVICE_COMPL) values ('110','TEST Montpellier 2',(select id from formule where package_name='FORMULE_MONTPELLIER'),'2018','1','1','1','1','0','192','0','0',null,null,null,null,null,'0','192','0,66666666666667','1','1','1,5','1,5','0,66666666666667');
Insert into FORMULE_TEST_INTERVENANT (ID,LIBELLE,FORMULE_ID,ANNEE_ID,TYPE_INTERVENANT_ID,STRUCTURE_TEST_ID,TYPE_VOLUME_HORAIRE_ID,ETAT_VOLUME_HORAIRE_ID,HEURES_DECHARGE,HEURES_SERVICE_STATUTAIRE,HEURES_SERVICE_MODIFIE,DEPASSEMENT_SERVICE_DU_SANS_HC,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,A_SERVICE_DU,C_SERVICE_DU,TAUX_TP_SERVICE_DU,TAUX_AUTRE_SERVICE_DU,TAUX_AUTRE_SERVICE_COMPL,TAUX_CM_SERVICE_DU,TAUX_CM_SERVICE_COMPL,TAUX_TP_SERVICE_COMPL) values ('111','TEST Montpellier 1',(select id from formule where package_name='FORMULE_MONTPELLIER'),'2018','1','1','1','1','0','192','0','0',null,null,null,null,null,'0','192','0,66666666666667','1','1','1,5','1,5','0,66666666666667');
Insert into FORMULE_TEST_INTERVENANT (ID,LIBELLE,FORMULE_ID,ANNEE_ID,TYPE_INTERVENANT_ID,STRUCTURE_TEST_ID,TYPE_VOLUME_HORAIRE_ID,ETAT_VOLUME_HORAIRE_ID,HEURES_DECHARGE,HEURES_SERVICE_STATUTAIRE,HEURES_SERVICE_MODIFIE,DEPASSEMENT_SERVICE_DU_SANS_HC,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,A_SERVICE_DU,C_SERVICE_DU,TAUX_TP_SERVICE_DU,TAUX_AUTRE_SERVICE_DU,TAUX_AUTRE_SERVICE_COMPL,TAUX_CM_SERVICE_DU,TAUX_CM_SERVICE_COMPL,TAUX_TP_SERVICE_COMPL) values ('112','TEST Le Havre 2 (Zlitni)',(select id from formule where package_name='FORMULE_ULHN'),'2018','1','5','2','1','0','192','0','0',null,null,null,null,null,'0','192','1','1','1','1,5','1,5','0,66666666666667');
Insert into FORMULE_TEST_INTERVENANT (ID,LIBELLE,FORMULE_ID,ANNEE_ID,TYPE_INTERVENANT_ID,STRUCTURE_TEST_ID,TYPE_VOLUME_HORAIRE_ID,ETAT_VOLUME_HORAIRE_ID,HEURES_DECHARGE,HEURES_SERVICE_STATUTAIRE,HEURES_SERVICE_MODIFIE,DEPASSEMENT_SERVICE_DU_SANS_HC,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,A_SERVICE_DU,C_SERVICE_DU,TAUX_TP_SERVICE_DU,TAUX_AUTRE_SERVICE_DU,TAUX_AUTRE_SERVICE_COMPL,TAUX_CM_SERVICE_DU,TAUX_CM_SERVICE_COMPL,TAUX_TP_SERVICE_COMPL) values ('106','TEST Le Havre 1',(select id from formule where package_name='FORMULE_ULHN'),'2018','1','9','2','1','0','384','0','0',null,null,null,null,null,'0','384','0,66666666666667','1','1','1,5','1,5','0,66666666666666666666666666666666666667');
Insert into FORMULE_TEST_INTERVENANT (ID,LIBELLE,FORMULE_ID,ANNEE_ID,TYPE_INTERVENANT_ID,STRUCTURE_TEST_ID,TYPE_VOLUME_HORAIRE_ID,ETAT_VOLUME_HORAIRE_ID,HEURES_DECHARGE,HEURES_SERVICE_STATUTAIRE,HEURES_SERVICE_MODIFIE,DEPASSEMENT_SERVICE_DU_SANS_HC,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,A_SERVICE_DU,C_SERVICE_DU,TAUX_TP_SERVICE_DU,TAUX_AUTRE_SERVICE_DU,TAUX_AUTRE_SERVICE_COMPL,TAUX_CM_SERVICE_DU,TAUX_CM_SERVICE_COMPL,TAUX_TP_SERVICE_COMPL) values ('107','Test Brest',(select id from formule where package_name='FORMULE_UBO'),'2018','1','3','1','1','0','192','0','0','Oui',null,null,null,null,'0','192','1','1','1','1,5','1,5','1');
Insert into FORMULE_TEST_INTERVENANT (ID,LIBELLE,FORMULE_ID,ANNEE_ID,TYPE_INTERVENANT_ID,STRUCTURE_TEST_ID,TYPE_VOLUME_HORAIRE_ID,ETAT_VOLUME_HORAIRE_ID,HEURES_DECHARGE,HEURES_SERVICE_STATUTAIRE,HEURES_SERVICE_MODIFIE,DEPASSEMENT_SERVICE_DU_SANS_HC,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,A_SERVICE_DU,C_SERVICE_DU,TAUX_TP_SERVICE_DU,TAUX_AUTRE_SERVICE_DU,TAUX_AUTRE_SERVICE_COMPL,TAUX_CM_SERVICE_DU,TAUX_CM_SERVICE_COMPL,TAUX_TP_SERVICE_COMPL) values ('113','TEST Nanterre 1',(select id from formule where package_name='FORMULE_NANTERRE'),'2018','1','1','1','1','0','192','0','0',null,null,null,null,null,'0','192','1','1','1','1,5','1,5','1');
Insert into FORMULE_TEST_INTERVENANT (ID,LIBELLE,FORMULE_ID,ANNEE_ID,TYPE_INTERVENANT_ID,STRUCTURE_TEST_ID,TYPE_VOLUME_HORAIRE_ID,ETAT_VOLUME_HORAIRE_ID,HEURES_DECHARGE,HEURES_SERVICE_STATUTAIRE,HEURES_SERVICE_MODIFIE,DEPASSEMENT_SERVICE_DU_SANS_HC,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,A_SERVICE_DU,C_SERVICE_DU,TAUX_TP_SERVICE_DU,TAUX_AUTRE_SERVICE_DU,TAUX_AUTRE_SERVICE_COMPL,TAUX_CM_SERVICE_DU,TAUX_CM_SERVICE_COMPL,TAUX_TP_SERVICE_COMPL) values ('114','TEST Montpellier 4 (BJ Richard)',(select id from formule where package_name='FORMULE_MONTPELLIER'),'2018','1','1','1','1','0','192','-1','0',null,null,null,null,null,'0','191','1','1','1','1,5','1,5','0,66666666666667');
Insert into FORMULE_TEST_INTERVENANT (ID,LIBELLE,FORMULE_ID,ANNEE_ID,TYPE_INTERVENANT_ID,STRUCTURE_TEST_ID,TYPE_VOLUME_HORAIRE_ID,ETAT_VOLUME_HORAIRE_ID,HEURES_DECHARGE,HEURES_SERVICE_STATUTAIRE,HEURES_SERVICE_MODIFIE,DEPASSEMENT_SERVICE_DU_SANS_HC,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,A_SERVICE_DU,C_SERVICE_DU,TAUX_TP_SERVICE_DU,TAUX_AUTRE_SERVICE_DU,TAUX_AUTRE_SERVICE_COMPL,TAUX_CM_SERVICE_DU,TAUX_CM_SERVICE_COMPL,TAUX_TP_SERVICE_COMPL) values ('115','TEST Montpellier 5 (définitif)',(select id from formule where package_name='FORMULE_MONTPELLIER'),'2018','1','1','1','1','0','192','0','0',null,null,null,null,null,'0','192','1','1','1','1,5','1,5','0,66666666666667');
Insert into FORMULE_TEST_INTERVENANT (ID,LIBELLE,FORMULE_ID,ANNEE_ID,TYPE_INTERVENANT_ID,STRUCTURE_TEST_ID,TYPE_VOLUME_HORAIRE_ID,ETAT_VOLUME_HORAIRE_ID,HEURES_DECHARGE,HEURES_SERVICE_STATUTAIRE,HEURES_SERVICE_MODIFIE,DEPASSEMENT_SERVICE_DU_SANS_HC,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,A_SERVICE_DU,C_SERVICE_DU,TAUX_TP_SERVICE_DU,TAUX_AUTRE_SERVICE_DU,TAUX_AUTRE_SERVICE_COMPL,TAUX_CM_SERVICE_DU,TAUX_CM_SERVICE_COMPL,TAUX_TP_SERVICE_COMPL) values ('116','TEST Montpellier 6 (Richard)',(select id from formule where package_name='FORMULE_MONTPELLIER'),'2018','1','1','1','1','0','192','0','0',null,null,null,null,null,'0','192','1','1','1','1,5','1,5','0,66666666666667');
Insert into FORMULE_TEST_INTERVENANT (ID,LIBELLE,FORMULE_ID,ANNEE_ID,TYPE_INTERVENANT_ID,STRUCTURE_TEST_ID,TYPE_VOLUME_HORAIRE_ID,ETAT_VOLUME_HORAIRE_ID,HEURES_DECHARGE,HEURES_SERVICE_STATUTAIRE,HEURES_SERVICE_MODIFIE,DEPASSEMENT_SERVICE_DU_SANS_HC,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,A_SERVICE_DU,C_SERVICE_DU,TAUX_TP_SERVICE_DU,TAUX_AUTRE_SERVICE_DU,TAUX_AUTRE_SERVICE_COMPL,TAUX_CM_SERVICE_DU,TAUX_CM_SERVICE_COMPL,TAUX_TP_SERVICE_COMPL) values ('117','TEST Montpellier 7 (Dudoit ATER)',(select id from formule where package_name='FORMULE_MONTPELLIER'),'2018','1','1','1','1','0','0','0','1',null,null,null,null,null,'0','9999','0,66666666666667','1','1','1,5','1,5','0,66666666666667');
Insert into FORMULE_TEST_INTERVENANT (ID,LIBELLE,FORMULE_ID,ANNEE_ID,TYPE_INTERVENANT_ID,STRUCTURE_TEST_ID,TYPE_VOLUME_HORAIRE_ID,ETAT_VOLUME_HORAIRE_ID,HEURES_DECHARGE,HEURES_SERVICE_STATUTAIRE,HEURES_SERVICE_MODIFIE,DEPASSEMENT_SERVICE_DU_SANS_HC,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,A_SERVICE_DU,C_SERVICE_DU,TAUX_TP_SERVICE_DU,TAUX_AUTRE_SERVICE_DU,TAUX_AUTRE_SERVICE_COMPL,TAUX_CM_SERVICE_DU,TAUX_CM_SERVICE_COMPL,TAUX_TP_SERVICE_COMPL) values ('118','TEST Montpellier 7 (Fontana)',(select id from formule where package_name='FORMULE_MONTPELLIER'),'2018','1','1','1','1','0','192','0','0',null,null,null,null,null,'0','192','1','1','1','1,5','1,5','0,66666666666667');
Insert into FORMULE_TEST_INTERVENANT (ID,LIBELLE,FORMULE_ID,ANNEE_ID,TYPE_INTERVENANT_ID,STRUCTURE_TEST_ID,TYPE_VOLUME_HORAIRE_ID,ETAT_VOLUME_HORAIRE_ID,HEURES_DECHARGE,HEURES_SERVICE_STATUTAIRE,HEURES_SERVICE_MODIFIE,DEPASSEMENT_SERVICE_DU_SANS_HC,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,A_SERVICE_DU,C_SERVICE_DU,TAUX_TP_SERVICE_DU,TAUX_AUTRE_SERVICE_DU,TAUX_AUTRE_SERVICE_COMPL,TAUX_CM_SERVICE_DU,TAUX_CM_SERVICE_COMPL,TAUX_TP_SERVICE_COMPL) values ('109','TEST Montpellier 3',(select id from formule where package_name='FORMULE_MONTPELLIER'),'2018','1','1','1','1','0','192','0','0',null,null,null,null,null,'0','192','0,66666666666667','1','1','1,5','1,5','0,66666666666667');


Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2312','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'6','6','0','0','0','0,19','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2313','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'4','4','0','0','0','0,13','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2314','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0,07','0','0','0','0',null,null,null,null,null,null,null,null,null,'CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2315','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0,07','0','0','0','0',null,null,null,null,null,null,null,null,null,'CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2316','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'4','4','0','0','0','0,13','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2317','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0,07','0','0','0','0',null,null,null,null,null,null,null,null,null,'CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2318','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'3','3','0','0','0','0,09','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2319','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'2','2','0','0','0','0,06','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2320','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'4','1','0','0','0','0,13','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2347','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2348','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'3','3','0','0','0','0','0','0','0','0','3','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2349','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2350','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2351','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'4','4','0','0','0','0','0','0','0','0','4','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2352','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2353','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'2','3','0','0','0','0','0','0','0','0','3','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2354','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2355','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2356','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2321','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'3','0','0','0','0','0,09','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2322','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'2','3','0','0','0','0','0','0','0','0','3','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2323','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2324','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2325','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2326','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2327','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1808','111','1','0','1','1','0','0','1','1',null,null,null,null,null,'10','6,67','0','0','0','1,98','0','0','0','0','6,6666666666667','0','0','0','1,9755351681957284403669724770642201835','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1809','111','1','1','1','0','0','0','1','1',null,null,null,null,null,'10','0','0','0','0','0','0','0','0','1,98','0','0','0','0','0','0','0','0','1,9755351681957284403669724770642201835',null);
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1810','111','3','0','1','1','0','0','1','1',null,null,null,null,null,'10','6,67','0','0','0','10','0','0','0','0','6,6666666666667','0','0','0','10','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1811','111','2','0','0','1','0','0','1','1',null,null,null,null,null,'15','10','0','0','0','15','0','0','0','0','10,00000000000005','0','0','0','15','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1812','111','2','0','0','1','0','0','1','1',null,null,null,null,null,'10','6,67','0','0','0','10','0','0','0','0','6,6666666666667','0','0','0','10','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1813','111','2','0','0','1','0','0','1','1',null,null,null,null,null,'20','13,33','0','0','0','20','0','0','0','0','13,3333333333334','0','0','0','20','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1814','111','1','0','1','0,5','0,5','0','1','1',null,null,null,null,null,'5','1,67','1,67','0','0','0,99','0','0','0','0','1,666666666666675','1,666666666666675','0','0','0,98776758409786422018348623853211009174','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1815','111','3','0','1','1','0','0','1','1',null,null,null,null,null,'10','6,67','0','0','0','10','0','0','0','0','6,6666666666667','0','0','0','10','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1816','111','9','1','0','0','0','0','1','1',null,null,null,null,null,'30','0','0','0','0','0','0','0','0','30','0','0','0','0','0','0','0','0','30',null);
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1817','111','9','1','1','0','0','0','1','1',null,null,null,null,null,'10','0','0','0','0','0','0','0','0','10','0','0','0','0','0','0','0','0','10',null);
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1818','111','1','0','1','1','0','0','1','1',null,null,null,null,null,'25','37,5','0','0','0','7,41','0','0','0','0','37,5','0','0','0','7,4082568807339816513761467889908256881','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1802','111','1','0','1','1','0','0','1','1',null,null,null,null,null,'50','75','0','0','0','14,82','0','0','0','0','75','0','0','0','14,816513761467963302752293577981651376','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1803','111','2','0','1','0,5','0,5','0','1','1',null,null,null,null,null,'30','15','15','0','0','30','0','0','0','0','15','15','0','0','30','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1804','111','1','0','0','0,5','0','0,5','1','1',null,null,null,null,null,'50','37,5','0','37,5','0','14,82','0','0','0','0','37,5','0','37,5','0','14,816513761467963302752293577981651376','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1805','111','1','0','1','1','0','0','1','1',null,null,null,null,null,'12','12','0','0','0','2,37','0','0','0','0','12','0','0','0','2,3706422018348741284403669724770642202','0','0','0','0','TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1806','111','9','1','1','0','0','0','1','1',null,null,null,null,null,'40','0','0','0','40','0','0','0','0','40','0','0','0','40','0','0','0','0','40',null);
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1807','111','1','0','1','1','0','0','1','1',null,null,null,null,null,'48','48','0','0','0','9,48','0','0','0','0','48','0','0','0','9,4825688073394965137614678899082568807','0','0','0','0','TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2328','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2329','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2330','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2331','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2332','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2333','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2334','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2335','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'2','3','0','0','0','0','0','0','0','0','3','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2336','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2337','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0','0','0','0','0','1,5','0','0','0','0','0','0','0','0','TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2338','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'3','3','0','0','0','0','0','0','0','0','3','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2339','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2340','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2341','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'3','3','0','0','0','0','0','0','0','0','3','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2342','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2343','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'3','3','0','0','0','0','0','0','0','0','3','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2344','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2345','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2346','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2357','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2358','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2359','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2360','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2361','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2362','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2363','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2364','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2365','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2366','115','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2367','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'4','4','0','0','0','1,84','0','0','0','0','4','0','0','0','1,8383287920072693188010899182561307902','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2368','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2369','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2370','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'4','4','0','0','0','1,84','0','0','0','0','4','0','0','0','1,8383287920072693188010899182561307902','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2371','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2372','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2373','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2391','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2392','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2393','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2394','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2395','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2396','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2397','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2398','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2399','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2400','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2401','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2402','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2403','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','1,84','0','0','0','0','2,66666666666668','0','0','0','1,8383287920072693188010899182561307902','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2404','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','1,84','0','0','0','0','2,66666666666668','0','0','0','1,8383287920072693188010899182561307902','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2405','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2374','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'4','4','0','0','0','1,84','0','0','0','0','4','0','0','0','1,8383287920072693188010899182561307902','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2375','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'4','4','0','0','0','1,84','0','0','0','0','4','0','0','0','1,8383287920072693188010899182561307902','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2376','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2377','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2378','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2379','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2380','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2381','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2382','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'5','7,5','0','0','0','3,45','0','0','0','0','7,5','0','0','0','3,4468664850136299727520435967302452316','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2383','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2384','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2385','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2386','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2387','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'2','2','0','0','0','0,92','0','0','0','0','2','0','0','0','0,9191643960036346594005449591280653951','0','0','0','0','TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2388','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2389','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2390','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'3,5','5,25','0','0','0','2,41','0','0','0','0','5,25','0','0','0','2,4128065395095409809264305177111716621','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2406','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2407','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2408','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','1,84','0','0','0','0','2,66666666666668','0','0','0','1,8383287920072693188010899182561307902','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2409','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','1,84','0','0','0','0','2,66666666666668','0','0','0','1,8383287920072693188010899182561307902','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2410','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','1,84','0','0','0','0','2,66666666666668','0','0','0','1,8383287920072693188010899182561307902','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2411','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2412','115','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2413','115','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2414','115','3','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','1,84','0','0','0','0','2,66666666666668','0','0','0','1,8383287920072693188010899182561307902','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2415','115','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2416','115','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2417','115','3','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','1,84','0','0','0','0','2,66666666666668','0','0','0','1,8383287920072693188010899182561307902','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2418','115','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2419','115','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2420','115','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2421','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','1,84','0','0','0','0','2,66666666666668','0','0','0','1,8383287920072693188010899182561307902','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2422','115','2','0','1','1','0','0','1','1',null,null,null,null,null,'2','3','0','0','0','1,38','0','0','0','0','3','0','0','0','1,3787465940054519891008174386920980926','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2423','115','3','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','1,84','0','0','0','0','2,66666666666668','0','0','0','1,8383287920072693188010899182561307902','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2424','115','2','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2425','115','2','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2426','115','3','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','1,84','0','0','0','0','2,66666666666668','0','0','0','1,8383287920072693188010899182561307902','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2427','115','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2428','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','1,84','0','0','0','0','2,66666666666668','0','0','0','1,8383287920072693188010899182561307902','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2429','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2430','115','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2431','115','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2432','115','2','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2433','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'2','3','0','0','0','0','0','0','0','0','3','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2434','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2435','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2436','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2437','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1444','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'2','3','0','0','0','0','0','0','0','0','3','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1445','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1446','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1447','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1448','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1449','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1450','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1451','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1452','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1453','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1454','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1455','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1456','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1457','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'2','3','0','0','0','0','0','0','0','0','3','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1458','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1459','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0','0','0','0','0','1,5','0','0','0','0','0','0','0','0','TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1460','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'3','3','0','0','0','0','0','0','0','0','2,00000000000001','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1461','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1462','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1463','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'3','3','0','0','0','0','0','0','0','0','2,00000000000001','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1464','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1465','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'3','3','0','0','0','0','0','0','0','0','2,00000000000001','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1466','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1467','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1468','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1469','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1470','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'3','3','0','0','0','0','0','0','0','0','2,00000000000001','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1471','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1472','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1473','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'4','4','0','0','0','0','0','0','0','0','2,66666666666668','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1474','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1475','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'2','3','0','0','0','0','0','0','0','0','3','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1476','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1477','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1478','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1479','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1480','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1481','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1482','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1483','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1484','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1485','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1486','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1487','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1488','109','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1489','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'4','4','0','0','0','1,84','0','0','0','0','2,66666666666668','0','0','0','1,6058128973660364032697547683923705722','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1490','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1491','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1492','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'4','4','0','0','0','1,84','0','0','0','0','2,66666666666668','0','0','0','1,6058128973660364032697547683923705722','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1493','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1494','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1495','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1496','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'4','4','0','0','0','1,84','0','0','0','0','2,66666666666668','0','0','0','1,6058128973660364032697547683923705722','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1497','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'4','4','0','0','0','1,84','0','0','0','0','2,66666666666668','0','0','0','1,6058128973660364032697547683923705722','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1498','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1499','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1500','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1502','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1503','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1504','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'5','7,5','0','0','0','3,45','0','0','0','0','7,5','0','0','0','3,0108991825613182561307901907356948229','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1506','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1507','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1509','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'2','2','0','0','0','0,92','0','0','0','0','2','0','0','0','0,8029064486830182016348773841961852861','0','0','0','0','TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1510','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1511','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1513','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1514','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1516','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1517','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1519','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1520','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1521','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1523','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1524','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1526','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','1,84','0','0','0','0','2,66666666666668','0','0','0','1,6058128973660364032697547683923705722','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1527','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1529','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1530','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','1,84','0','0','0','0','2,66666666666668','0','0','0','1,6058128973660364032697547683923705722','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1964','112','5','0','1','1','0','0','1','1',null,null,null,null,null,'24','24','0','0','0','0','0','0','0','0','24','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1965','112','5','0','1','1','0','0','1','1',null,null,null,null,null,'16','16','0','0','0','0','0','0','0','0','16','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1966','112','5','0','1','1','0','0','1','1',null,null,null,null,null,'24','24','0','0','0','0','0','0','0','0','24','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1967','112','5','0','1','1','0','0','1','1',null,null,null,null,null,'24','24','0','0','0','0','0','0','0','0','24','0','0','0','0','0','0','0','0','TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1968','112','5','0','1','1','0','0','1','1',null,null,null,null,null,'16','16','0','0','0','0','0','0','0','0','16','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1969','112','5','0','1','1','0','0','1','1',null,null,null,null,null,'4','4','0','0','0','0','0','0','0','0','4','0','0','0','0','0','0','0','0','TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1970','112','5','0','1','1','0','0','1','1',null,null,null,null,null,'16','16','0','0','0','0','0','0','0','0','16','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1971','112','5','0','1','1','0','0','1','1',null,null,null,null,null,'24','24','0','0','0','0','0','0','0','0','24','0','0','0','0','0','0','0','0','TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1972','112','5','0','1','1','0','0','1','1',null,null,null,null,null,'16','16','0','0','0','0','0','0','0','0','16','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1973','112','5','0','1','1','0','0','1','1',null,null,null,null,null,'8','8','0','0','0','0','0','0','0','0','8','0','0','0','0','0','0','0','0','TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1974','112','5','0','1','1','0','0','1','1',null,null,null,null,null,'8','8','0','0','0','0','0','0','0','0','8','0','0','0','0','0','0','0','0','TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1975','112','5','0','1','1','0','0','1','1',null,null,null,null,null,'8','8','0','0','0','0','0','0','0','0','8','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1976','112','5','0','1','1','0','0','1','1',null,null,null,null,null,'6','4','0','0','0','2','0','0','0','0','4','0','0','0','2','0','0','0','0','TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1977','112','5','0','1','1','0','0','1','1',null,null,null,null,null,'12','0','0','0','0','8','0','0','0','0','0','0','0','0','8,00000000000004','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1978','112','5','0','1','1','0','0','1','1',null,null,null,null,null,'6','0','0','0','0','6','0','0','0','0','0','0','0','0','6','0','0','0','0','TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1979','112','5','0','1','1','0','0','1','1',null,null,null,null,null,'2','0','0','0','0','2','0','0','0','0','0','0','0','0','2','0','0','0','0','TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1981','112','5','0','1','1','0','0','1','1',null,null,null,null,null,'8','4','0','0','0','12','0','0','0','0','4','0','0','0','12','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1982','112','5','0','1','1','0','0','1','1',null,null,null,null,null,'1','0','0','0','0','1','0','0','0','0','0','0','0','0','1','0','0','0','0','TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('261','107','3','0','1','1','0','0','1','1',null,null,null,null,null,'52','78','0','0','0','0','0','0','0','0','78','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('262','107','8','0','1','1','0','0','1','1',null,null,null,null,null,'50','73,93','0','0','0','1,07','0','0','0','0','73,928571428571428571428571428571428571','0','0','0','1,0714285714285714285714285714285714286','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('263','107','3','0','1','0','0','1','1','1,12',null,null,null,null,null,'13','0','0','0','0','0','0','0','12,75','0','0','0','0','0','0','0','0','12,751895424836601307189542483660130719','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('264','107','3','0','1','0','0','1','1','1,28',null,null,null,null,null,'20','0','0','0','0','0','0','0','22,42','0','0','0','0','0','0','0','0','22,420915032679738562091503267973856209','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('265','107','8','0','1','0','0','1','1','1',null,null,null,null,null,'50','0','0','0','0','0','0','75','0','0','0','0','0','0','0','0','75','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('266','107','3','0','1','0','1','0','1','1,92',null,null,null,null,null,'7','0','10,5','0','0','0','0','0','0','0','0','10,5','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('267','107','1','0','1','1','0','0','1','1',null,null,null,null,null,'12','11,83','0','0','0','0,17','0','0','0','0','11,828571428571428571428571428571428571','0','0','0','0,17142857142857142857142857142857142857','0','0','0','0','TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1531','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','1,84','0','0','0','0','2,66666666666668','0','0','0','1,6058128973660364032697547683923705722','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1532','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','1,84','0','0','0','0','2,66666666666668','0','0','0','1,6058128973660364032697547683923705722','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1533','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1534','109','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1535','109','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1536','109','3','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','1,84','0','0','0','0','2,66666666666668','0','0','0','1,6058128973660364032697547683923705722','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1537','109','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1538','109','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1539','109','3','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','1,84','0','0','0','0','2,66666666666668','0','0','0','1,6058128973660364032697547683923705722','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1540','109','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1541','109','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1542','109','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1543','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','1,84','0','0','0','0','2,66666666666668','0','0','0','1,6058128973660364032697547683923705722','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1544','109','2','0','1','1','0','0','1','1',null,null,null,null,null,'2','3','0','0','0','1,38','0','0','0','0','3','0','0','0','1,2043596730245273024523160762942779292','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1545','109','3','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','1,84','0','0','0','0','2,66666666666668','0','0','0','1,6058128973660364032697547683923705722','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1546','109','2','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1547','109','2','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1548','109','3','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','1,84','0','0','0','0','2,66666666666668','0','0','0','1,6058128973660364032697547683923705722','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1549','109','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1550','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','1,84','0','0','0','0','2,66666666666668','0','0','0','1,6058128973660364032697547683923705722','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1551','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1552','109','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1553','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1554','109','2','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1501','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1505','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1508','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1512','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'3,5','5,25','0','0','0','2,41','0','0','0','0','5,25','0','0','0','2,107629427792922779291553133514986376','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1515','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1518','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1522','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1525','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','1,84','0','0','0','0','2,66666666666668','0','0','0','1,6058128973660364032697547683923705722','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1528','109','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','0,90326975476839547683923705722070844687','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1984','112','5','0','1','1','0','0','1','1',null,null,null,null,null,'-1','-1','0','0','0','0','0','0','0','0','-1','0','0','0','0','0','0','0','0','TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1986','112','5','0','1','1','0','0','1','1',null,null,null,null,null,'18','0','0','0','0','18','0','0','0','0','0','0','0','0','18','0','0','0','0','TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1988','112','5','0','1','1','0','0','1','1',null,null,null,null,null,'5','0','0','0','0','5','0','0','0','0','0','0','0','0','5','0','0','0','0','TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1989','112','5','0','1','1','0','0','1','1',null,null,null,null,null,'7','0','0','0','0','7','0','0','0','0','0','0','0','0','7','0','0','0','0','TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1991','112','5','0','1','1','0','0','1','1',null,null,null,null,null,'11','0','0','0','0','11','0','0','0','0','0','0','0','0','11','0','0','0','0','TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1993','112','5','0','1','1','0','0','1','1',null,null,null,null,null,'15,5','0','0','0','0','15,5','0','0','0','0','0','0','0','0','15,5','0','0','0','0','TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1994','112','5','0','1','1','0','0','1','1',null,null,null,null,null,'13,5','0','0','0','0','13,5','0','0','0','0','0','0','0','0','13,5','0','0','0','0','TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1980','112','5','0','1','1','0','0','1','1',null,null,null,null,null,'6','3','0','0','0','9','0','0','0','0','3','0','0','0','9','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1983','112','5','0','1','1','0','0','1','1',null,null,null,null,null,'11','0','0','0','0','11','0','0','0','0','0','0','0','0','11','0','0','0','0','TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1985','112','5','0','1','1','0','0','1','1',null,null,null,null,null,'1','1','0','0','0','0','0','0','0','0','1','0','0','0','0','0','0','0','0','TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1987','112','5','0','1','1','0','0','1','1',null,null,null,null,null,'5','0','0','0','0','5','0','0','0','0','0','0','0','0','5','0','0','0','0','TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1990','112','5','0','1','1','0','0','1','1',null,null,null,null,null,'2','1','0','0','0','3','0','0','0','0','1','0','0','0','3','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('1992','112','5','0','1','1','0','0','1','1',null,null,null,null,null,'10','0','0','0','0','10','0','0','0','0','0','0','0','0','10','0','0','0','0','TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2001','106','1','0','1','1','0','0','1','1',null,null,null,null,null,'200','300','0','0','0','0','0','0','0','0',null,null,null,null,null,null,null,null,null,'CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2092','113','1','1','1','0','0','0','1','1',null,null,null,null,null,'15','0','0','0','15','0','0','0','0','0','0','0','0','15','0','0','0','0','0',null);
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2002','106','2','0','1','1','0','0','1','1',null,null,null,null,null,'80','80','0','0','0','0','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2003','106','1','0','1','1','0','0','1','1',null,null,null,null,null,'10','4','0','0','0','4','0','0','0','0',null,null,null,null,null,null,null,null,null,'TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2093','113','8','0','1','1','0','0','1','1','KE8',null,null,null,null,'30','30','0','0','0','0','0','0','0','0','30','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2094','113','1','1','1','0','0','0','1','1',null,null,null,null,null,'40','0','0','0','40','0','0','0','0','0','0','0','0','40','0','0','0','0','0',null);
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2095','113','2','0','1','1','0','0','1','1',null,null,null,null,null,'10','15','0','0','0','0','0','0','0','0','15','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2096','113','2','1','1','0','0','0','1','1',null,null,null,null,null,'10','0','0','0','10','0','0','0','0','0','0','0','0','10','0','0','0','0','0',null);
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2097','113','1','0','1','0','1','0','1','1',null,null,null,null,null,'15','0','15','0','0','0','0','0','0','0','0','15','0','0','0','0','0','0','0','TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2098','113','1','1','1','0','0','0','1','1',null,null,null,null,null,'5','0','0','0','5','0','0','0','0','0','0','0','0','5','0','0','0','0','0',null);
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2099','113','2','0','1','0','0','1','1','1',null,null,null,null,null,'25','0','0','0','0','0','0','25','0','0','0','0','0','0','0','0','25','0','0','TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2248','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'3','3','0','0','0','0,09','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2249','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'3','3','0','0','0','0,09','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2250','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'3','3','0','0','0','0,09','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2251','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'3','3','0','0','0','0,09','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2252','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'2,5','2,5','0','0','0','0,08','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2253','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'2','2','0','0','0','0,06','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2254','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0,05','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2255','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0,07','0','0','0','0',null,null,null,null,null,null,null,null,null,'CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2084','113','1','0','1','1','0','0','1','1',null,null,null,null,null,'20','30','0','0','0','0','0','0','0','0','30','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2085','113','2','0','1','0','1','0','1','1',null,null,null,null,null,'50','0','17','0','0','0','33','0','0','0','0','17','0','0','0','33','0','0','0','TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2086','113','8','0','1','0','0','1','1','1','KE8',null,null,null,null,'30','0','0','0','0','0','0','30','0','0','0','0','0','0','0','0','30','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2087','113','8','1','0','0','0','0','1','1','KE8',null,null,null,null,'20','0','0','0','0','0','0','0','0','20','0','0','0','0','0','0','0','0','20',null);
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2088','113','9','0','1','1','0','0','1','1','UP10',null,null,null,null,'10','15','0','0','0','0','0','0','0','0','15','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2089','113','1','0','1','0','0','1','1','1',null,null,null,null,null,'20','0','0','0','0','0','0','20','0','0','0','0','0','0','0','0','20','0','0','TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2090','113','1','1','0','0','0','0','1','1',null,null,null,null,null,'40','0','0','0','0','0','0','0','0','40','0','0','0','0','0','0','0','0','40',null);
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2091','113','2','1','0','0','0','0','1','1',null,null,null,null,null,'50','0','0','0','0','0','0','0','0','50','0','0','0','0','0','0','0','0','50',null);
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2256','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'2,5','2,5','0','0','0','0,08','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2257','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'6','6','0','0','0','0,19','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2258','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'3','3','0','0','0','0,09','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2259','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'8','8','0','0','0','0,25','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2260','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1','1','0','0','0','0,03','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2261','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0,05','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2262','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'3,25','3,25','0','0','0','0,1','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2263','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'2','2','0','0','0','0,06','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2264','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'3,25','3,25','0','0','0','0,1','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2265','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0,05','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2266','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0,05','0','0','0','0',null,null,null,null,null,null,null,null,null,'TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2267','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0,05','0','0','0','0',null,null,null,null,null,null,null,null,null,'TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2268','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'7','7','0','0','0','0,22','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2269','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0,05','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2270','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'6','6','0','0','0','0,19','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2271','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0,05','0','0','0','0',null,null,null,null,null,null,null,null,null,'TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2272','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0,05','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2273','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0,05','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2274','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0,05','0','0','0','0',null,null,null,null,null,null,null,null,null,'TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2275','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0,05','0','0','0','0',null,null,null,null,null,null,null,null,null,'TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2276','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0,05','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2277','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0,05','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2278','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'2,5','2,5','0','0','0','0,08','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2279','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'6','6','0','0','0','0,19','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2280','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'4','4','0','0','0','0,13','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2281','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'2','2','0','0','0','0,06','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2282','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0,05','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2283','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'3','3','0','0','0','0,09','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2284','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0,05','0','0','0','0',null,null,null,null,null,null,null,null,null,'TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2285','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0,05','0','0','0','0',null,null,null,null,null,null,null,null,null,'TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2286','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0,07','0','0','0','0',null,null,null,null,null,null,null,null,null,'CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2287','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'2','2','0','0','0','0,06','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2288','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0,05','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2289','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0,05','0','0','0','0',null,null,null,null,null,null,null,null,null,'TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2290','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0,05','0','0','0','0',null,null,null,null,null,null,null,null,null,'TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2291','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0,07','0','0','0','0',null,null,null,null,null,null,null,null,null,'CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2292','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0,07','0','0','0','0',null,null,null,null,null,null,null,null,null,'CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2293','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0,07','0','0','0','0',null,null,null,null,null,null,null,null,null,'CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2294','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0,05','0','0','0','0',null,null,null,null,null,null,null,null,null,'TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2295','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0,05','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2296','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0,05','0','0','0','0',null,null,null,null,null,null,null,null,null,'TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2297','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0,05','0','0','0','0',null,null,null,null,null,null,null,null,null,'TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2298','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0,07','0','0','0','0',null,null,null,null,null,null,null,null,null,'CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2299','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0,07','0','0','0','0',null,null,null,null,null,null,null,null,null,'CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2300','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'7','7','0','0','0','0,22','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2301','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0,05','0','0','0','0',null,null,null,null,null,null,null,null,null,'TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2302','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0,05','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2303','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0,05','0','0','0','0',null,null,null,null,null,null,null,null,null,'TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2304','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0,05','0','0','0','0',null,null,null,null,null,null,null,null,null,'TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2305','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'3','4,5','0','0','0','0,14','0','0','0','0',null,null,null,null,null,null,null,null,null,'CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2306','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'2,5','2,5','0','0','0','0,08','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2307','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0,05','0','0','0','0',null,null,null,null,null,null,null,null,null,'TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2308','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'4','4','0','0','0','0,13','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2309','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0,05','0','0','0','0',null,null,null,null,null,null,null,null,null,'TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2310','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0,05','0','0','0','0',null,null,null,null,null,null,null,null,null,'TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2311','114','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0,05','0','0','0','0',null,null,null,null,null,null,null,null,null,'TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('3002','107','1','0','1','0','0','1','1','1,5',null,null,null,null,null,'1','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','2,25','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('3003','107','7','0','1','1','0','0','1','1',null,null,null,null,null,'12','17,74','0','0','0','0,26','0','0','0','0','17,742857142857142857142857142857142857','0','0','0','0,25714285714285714285714285714285714286','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('3004','107','5','0','1','0','0','1','1','1',null,null,null,null,null,'3','0','0','0','0','0','0','3','0','0','0','0','0','0','0','0','3','0','0','TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('3005','107','9','1','1','1','0','0','1','1',null,null,null,null,null,'2','0','0','0','0','0','0','0','0','2','0','0','0','0','0','0','0','0','2',null);
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('3006','107','3','1','1','1','0','0','1','1',null,null,null,null,null,'10','0','0','0','0','0','0','0','0','10','0','0','0','0','0','0','0','0','10',null);
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('3007','107','1','1','1','1','0','0','1','1',null,null,null,null,null,'5','0','0','0','0','0','0','0','0','5','0','0','0','0','0','0','0','0','5',null);
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2438','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2439','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2440','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2441','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2442','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2443','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2444','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2445','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2446','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'2','3','0','0','0','0','0','0','0','0','3','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2447','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2448','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0','0','0','0','0','1,5','0','0','0','0','0','0','0','0','TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2449','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'3','3','0','0','0','0','0','0','0','0','3','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2450','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2451','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2452','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'3','3','0','0','0','0','0','0','0','0','3','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2453','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2454','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'3','3','0','0','0','0','0','0','0','0','3','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2455','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2456','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2457','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2458','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2459','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'3','3','0','0','0','0','0','0','0','0','3','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2460','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2461','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2462','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'4','4','0','0','0','0','0','0','0','0','4','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2463','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2464','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'2','3','0','0','0','0','0','0','0','0','3','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2465','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2466','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2467','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2468','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2469','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2470','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2471','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2472','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2473','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2474','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2475','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2476','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2477','116','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2478','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'4','4','0','0','0','1,84','0','0','0','0','4','0','0','0','1,8383287920072693188010899182561307902','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2479','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2480','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2481','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'4','4','0','0','0','1,84','0','0','0','0','4','0','0','0','1,8383287920072693188010899182561307902','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2482','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2483','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2484','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2485','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'4','4','0','0','0','1,84','0','0','0','0','4','0','0','0','1,8383287920072693188010899182561307902','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2486','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'4','4','0','0','0','1,84','0','0','0','0','4','0','0','0','1,8383287920072693188010899182561307902','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2487','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2488','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2489','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2490','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2492','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2493','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'5','7,5','0','0','0','3,45','0','0','0','0','7,5','0','0','0','3,4468664850136299727520435967302452316','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2495','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2496','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2498','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'2','2','0','0','0','0,92','0','0','0','0','2','0','0','0','0,9191643960036346594005449591280653951','0','0','0','0','TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2499','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2500','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2502','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2503','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2505','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2506','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2508','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2509','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2511','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2512','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2514','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','1,84','0','0','0','0','2,66666666666668','0','0','0','1,8383287920072693188010899182561307902','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2515','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','1,84','0','0','0','0','2,66666666666668','0','0','0','1,8383287920072693188010899182561307902','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2517','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2518','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2520','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','1,84','0','0','0','0','2,66666666666668','0','0','0','1,8383287920072693188010899182561307902','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2521','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','1,84','0','0','0','0','2,66666666666668','0','0','0','1,8383287920072693188010899182561307902','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2523','116','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2524','116','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2526','116','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2527','116','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2529','116','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2530','116','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2532','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','1,84','0','0','0','0','2,66666666666668','0','0','0','1,8383287920072693188010899182561307902','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2533','116','2','0','1','1','0','0','1','1',null,null,null,null,null,'2','3','0','0','0','1,38','0','0','0','0','3','0','0','0','1,3787465940054519891008174386920980926','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2534','116','3','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','1,84','0','0','0','0','2,66666666666668','0','0','0','1,8383287920072693188010899182561307902','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2535','116','2','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2536','116','2','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2537','116','3','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','1,84','0','0','0','0','2,66666666666668','0','0','0','1,8383287920072693188010899182561307902','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2538','116','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2539','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','1,84','0','0','0','0','2,66666666666668','0','0','0','1,8383287920072693188010899182561307902','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2540','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2541','116','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2542','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2543','116','2','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2491','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2494','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2497','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2501','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'3,5','5,25','0','0','0','2,41','0','0','0','0','5,25','0','0','0','2,4128065395095409809264305177111716621','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2504','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2507','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2510','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2513','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2516','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2519','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','1,84','0','0','0','0','2,66666666666668','0','0','0','1,8383287920072693188010899182561307902','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2522','116','4','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2525','116','3','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','1,84','0','0','0','0','2,66666666666668','0','0','0','1,8383287920072693188010899182561307902','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2528','116','3','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','1,84','0','0','0','0','2,66666666666668','0','0','0','1,8383287920072693188010899182561307902','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2531','116','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','1,03','0','0','0','0','2,25','0','0','0','1,0340599455040889918256130790190735695','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2868','117','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2869','117','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2870','117','3','0','1','1','0','0','1','1',null,null,null,null,null,'3','4,5','0','0','0','0','0','0','0','0','4,5','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2871','117','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2872','117','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2873','117','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2874','117','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2875','117','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2876','117','1','0','1','1','0','0','1','1',null,null,null,null,null,'3','2','0','0','0','0','0','0','0','0','2,00000000000001','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2877','117','3','0','1','1','0','0','1','1',null,null,null,null,null,'3','2','0','0','0','0','0','0','0','0','2,00000000000001','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2878','117','3','0','1','1','0','0','1','1',null,null,null,null,null,'3','2','0','0','0','0','0','0','0','0','2,00000000000001','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2879','117','1','0','1','1','0','0','1','1',null,null,null,null,null,'3','2','0','0','0','0','0','0','0','0','2,00000000000001','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2880','117','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2881','117','1','0','1','1','0','0','1','1',null,null,null,null,null,'3','4,5','0','0','0','0','0','0','0','0','4,5','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2882','117','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2883','117','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2884','117','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2885','117','1','0','1','1','0','0','1','1',null,null,null,null,null,'3','4,5','0','0','0','0','0','0','0','0','4,5','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2886','117','1','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','0','0','0','0','0','2,66666666666668','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2887','117','1','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','0','0','0','0','0','2,66666666666668','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2888','117','1','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','0','0','0','0','0','2,66666666666668','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2889','117','3','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','0','0','0','0','0','2,66666666666668','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2890','117','3','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','0','0','0','0','0','2,66666666666668','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2891','117','1','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','0','0','0','0','0','2,66666666666668','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2892','117','1','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','0','0','0','0','0','2,66666666666668','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2893','117','3','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','0','0','0','0','0','2,66666666666668','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2894','117','3','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','0','0','0','0','0','2,66666666666668','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2895','117','4','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','0','0','0','0','0','2,66666666666668','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2896','117','1','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','0','0','0','0','0','2,66666666666668','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2897','117','3','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','0','0','0','0','0','2,66666666666668','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2898','117','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2899','117','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2900','117','1','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','0','0','0','0','0','2,66666666666668','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2901','117','3','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','0','0','0','0','0','2,66666666666668','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2902','117','3','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','0','0','0','0','0','2,66666666666668','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2903','117','3','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','0','0','0','0','0','2,66666666666668','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2904','117','1','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','0','0','0','0','0','2,66666666666668','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2905','117','3','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','0','0','0','0','0','2,66666666666668','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2906','117','3','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','0','0','0','0','0','2,66666666666668','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2907','117','3','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','0','0','0','0','0','2,66666666666668','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2908','117','4','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','0','0','0','0','0','2,66666666666668','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2909','117','4','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','0','0','0','0','0','2,66666666666668','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2910','117','3','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','0','0','0','0','0','2,66666666666668','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2911','117','3','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','0','0','0','0','0','2,66666666666668','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2912','117','3','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','0','0','0','0','0','2,66666666666668','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2913','117','3','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','0','0','0','0','0','2,66666666666668','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2914','117','3','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','0','0','0','0','0','2,66666666666668','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2915','117','1','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','0','0','0','0','0','2,66666666666668','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2916','117','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2917','117','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2918','117','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2919','117','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2920','117','3','0','1','1','0','0','1','1',null,null,null,null,null,'4','6','0','0','0','0','0','0','0','0','6','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2921','117','3','0','1','1','0','0','1','1',null,null,null,null,null,'4','6','0','0','0','0','0','0','0','0','6','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2922','117','3','0','1','1','0','0','1','1',null,null,null,null,null,'4','6','0','0','0','0','0','0','0','0','6','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2923','117','3','0','1','1','0','0','1','1',null,null,null,null,null,'3','4,5','0','0','0','0','0','0','0','0','4,5','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2924','117','3','0','1','1','0','0','1','1',null,null,null,null,null,'3','2','0','0','0','0','0','0','0','0','2,00000000000001','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2925','117','3','0','1','1','0','0','1','1',null,null,null,null,null,'3','2','0','0','0','0','0','0','0','0','2,00000000000001','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2926','117','1','0','1','1','0','0','1','1',null,null,null,null,null,'3','2','0','0','0','0','0','0','0','0','2,00000000000001','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2927','117','1','0','1','1','0','0','1','1',null,null,null,null,null,'3','2','0','0','0','0','0','0','0','0','2,00000000000001','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2928','117','1','0','1','1','0','0','1','1',null,null,null,null,null,'3','2','0','0','0','0','0','0','0','0','2,00000000000001','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2929','117','1','0','1','1','0','0','1','1',null,null,null,null,null,'3','2','0','0','0','0','0','0','0','0','2,00000000000001','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2930','118','1','0','1','1','0','0','1','1',null,null,null,null,null,'3','4,5','0','0','0','0','0','0','0','0','4,5','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2931','118','1','0','1','1','0','0','1','1',null,null,null,null,null,'3','4,5','0','0','0','0','0','0','0','0','4,5','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2932','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0,42','0','0','0','0','2,25','0','0','0','0,41696750902527227436823104693140794224','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2933','118','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2934','118','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2935','118','1','0','1','1','0','0','1','1',null,null,null,null,null,'3','4,5','0','0','0','0','0','0','0','0','4,5','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2936','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'4,25','4,25','0','0','0','0,79','0','0','0','0','4,25','0','0','0','0,78760529482551429602888086642599277978','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2937','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0,42','0','0','0','0','2,25','0','0','0','0,41696750902527227436823104693140794224','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2938','118','1','0','1','1','0','0','1','1',null,null,null,null,null,'3','4,5','0','0','0','0','0','0','0','0','4,5','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2939','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'4,25','4,25','0','0','0','0,79','0','0','0','0','4,25','0','0','0','0,78760529482551429602888086642599277978','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2940','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'4,25','4,25','0','0','0','0,79','0','0','0','0','4,25','0','0','0','0,78760529482551429602888086642599277978','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2941','118','1','0','1','1','0','0','1','1',null,null,null,null,null,'4','4','0','0','0','0','0','0','0','0','4','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2942','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0,42','0','0','0','0','2,25','0','0','0','0,41696750902527227436823104693140794224','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2943','118','1','0','1','1','0','0','1','1',null,null,null,null,null,'3','4,5','0','0','0','0','0','0','0','0','4,5','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2944','118','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2945','118','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2946','118','1','0','1','1','0','0','1','1',null,null,null,null,null,'2','2','0','0','0','0','0','0','0','0','2','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2947','118','1','0','1','1','0','0','1','1',null,null,null,null,null,'4','4','0','0','0','0','0','0','0','0','4','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2948','118','1','0','1','1','0','0','1','1',null,null,null,null,null,'3','4,5','0','0','0','0','0','0','0','0','4,5','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2949','118','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2950','118','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2951','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0,28','0','0','0','0','1,5','0','0','0','0,27797833935018151624548736462093862816','0','0','0','0','TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2952','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0,28','0','0','0','0','1,5','0','0','0','0,27797833935018151624548736462093862816','0','0','0','0','TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2953','118','1','0','1','1','0','0','1','1',null,null,null,null,null,'4','4','0','0','0','0','0','0','0','0','4','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2954','118','1','0','1','1','0','0','1','1',null,null,null,null,null,'4','4','0','0','0','0','0','0','0','0','4','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2955','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'3,5','3,5','0','0','0','0,65','0','0','0','0','3,5','0','0','0','0,6486161251504235379061371841155234657','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2956','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'3,5','3,5','0','0','0','0,65','0','0','0','0','3,5','0','0','0','0,6486161251504235379061371841155234657','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2957','118','4','0','1','1','0','0','1','1',null,null,null,null,null,'3','4,5','0','0','0','0,83','0','0','0','0','4,5','0','0','0','0,83393501805054454873646209386281588448','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2958','118','1','0','1','1','0','0','1','1',null,null,null,null,null,'4','4','0','0','0','0','0','0','0','0','4','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2959','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0,42','0','0','0','0','2,25','0','0','0','0,41696750902527227436823104693140794224','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2960','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0,28','0','0','0','0','1,5','0','0','0','0,27797833935018151624548736462093862816','0','0','0','0','TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2961','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0,28','0','0','0','0','1,5','0','0','0','0,27797833935018151624548736462093862816','0','0','0','0','TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2962','118','1','0','1','1','0','0','1','1',null,null,null,null,null,'4','4','0','0','0','0','0','0','0','0','4','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2963','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0,28','0','0','0','0','1,5','0','0','0','0,27797833935018151624548736462093862816','0','0','0','0','TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2964','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0,28','0','0','0','0','1,5','0','0','0','0,27797833935018151624548736462093862816','0','0','0','0','TD');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2965','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0,42','0','0','0','0','2,25','0','0','0','0,41696750902527227436823104693140794224','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2966','118','1','0','1','1','0','0','1','1',null,null,null,null,null,'4','4','0','0','0','0','0','0','0','0','4','0','0','0','0','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2967','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','1,5','0','0','0','0,28','0','0','0','0','1,5','0','0','0','0,27797833935018151624548736462093862816','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2968','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'3,5','3,5','0','0','0','0,65','0','0','0','0','3,5','0','0','0','0,6486161251504235379061371841155234657','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2969','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'3,5','3,5','0','0','0','0,65','0','0','0','0','3,5','0','0','0','0,6486161251504235379061371841155234657','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2970','118','4','0','1','1','0','0','1','1',null,null,null,null,null,'3','4,5','0','0','0','0,83','0','0','0','0','4,5','0','0','0','0,83393501805054454873646209386281588448','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2971','118','4','0','1','1','0','0','1','1',null,null,null,null,null,'3','4,5','0','0','0','0,83','0','0','0','0','4,5','0','0','0','0,83393501805054454873646209386281588448','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2972','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'4,25','4,25','0','0','0','0,79','0','0','0','0','4,25','0','0','0','0,78760529482551429602888086642599277978','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2973','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'4,25','4,25','0','0','0','0,79','0','0','0','0','4,25','0','0','0','0,78760529482551429602888086642599277978','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2974','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'3,5','3,5','0','0','0','0,65','0','0','0','0','3,5','0','0','0','0,6486161251504235379061371841155234657','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2975','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'3,5','3,5','0','0','0','0,65','0','0','0','0','3,5','0','0','0','0,6486161251504235379061371841155234657','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2976','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'1','1,5','0','0','0','0,28','0','0','0','0','1,5','0','0','0','0,27797833935018151624548736462093862816','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2977','118','1','0','1','1','0','0','1','1',null,null,null,null,null,'3','4,5','0','0','0','0','0','0','0','0','4,5','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2978','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'4,25','4,25','0','0','0','0,79','0','0','0','0','4,25','0','0','0','0,78760529482551429602888086642599277978','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2979','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'4,25','4,25','0','0','0','0,79','0','0','0','0','4,25','0','0','0','0,78760529482551429602888086642599277978','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2980','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'3,5','3,5','0','0','0','0,65','0','0','0','0','3,5','0','0','0','0,6486161251504235379061371841155234657','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2981','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'3,5','3,5','0','0','0','0,65','0','0','0','0','3,5','0','0','0','0,6486161251504235379061371841155234657','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2982','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0,42','0','0','0','0','2,25','0','0','0','0,41696750902527227436823104693140794224','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2983','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0,42','0','0','0','0','2,25','0','0','0','0,41696750902527227436823104693140794224','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2984','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0,42','0','0','0','0','2,25','0','0','0','0,41696750902527227436823104693140794224','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2985','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0,42','0','0','0','0','2,25','0','0','0','0,41696750902527227436823104693140794224','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2986','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'3,5','3,5','0','0','0','0,65','0','0','0','0','3,5','0','0','0','0,6486161251504235379061371841155234657','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2987','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'3,5','3,5','0','0','0','0,65','0','0','0','0','3,5','0','0','0','0,6486161251504235379061371841155234657','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2988','118','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2989','118','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2990','118','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2991','118','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2992','118','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2993','118','1','0','1','1','0','0','1','1',null,null,null,null,null,'1,5','2,25','0','0','0','0','0','0','0','0','2,25','0','0','0','0','0','0','0','0','CM');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2994','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','0,74','0','0','0','0','2,66666666666668','0','0','0','0,74127557160048404332129963898916967509','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2995','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'4,25','2,83','0','0','0','0,79','0','0','0','0','2,8333333333333475','0','0','0','0,78760529482551429602888086642599277978','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2996','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'4','2,67','0','0','0','0,74','0','0','0','0','2,66666666666668','0','0','0','0,74127557160048404332129963898916967509','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2997','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'4,25','2,83','0','0','0','0,79','0','0','0','0','2,8333333333333475','0','0','0','0,78760529482551429602888086642599277978','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2998','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'3,5','2,33','0','0','0','0,65','0','0','0','0','2,333333333333345','0','0','0','0,6486161251504235379061371841155234657','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('2999','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'3,5','2,33','0','0','0','0,65','0','0','0','0','2,333333333333345','0','0','0','0,6486161251504235379061371841155234657','0','0','0','0','TP');
Insert into FORMULE_TEST_VOLUME_HORAIRE (ID,INTERVENANT_TEST_ID,STRUCTURE_TEST_ID,REFERENTIEL,SERVICE_STATUTAIRE,TAUX_FI,TAUX_FA,TAUX_FC,PONDERATION_SERVICE_DU,PONDERATION_SERVICE_COMPL,PARAM_1,PARAM_2,PARAM_3,PARAM_4,PARAM_5,HEURES,A_SERVICE_FI,A_SERVICE_FA,A_SERVICE_FC,A_SERVICE_REFERENTIEL,A_HEURES_COMPL_FI,A_HEURES_COMPL_FA,A_HEURES_COMPL_FC,A_HEURES_COMPL_FC_MAJOREES,A_HEURES_COMPL_REFERENTIEL,C_SERVICE_FI,C_SERVICE_FA,C_SERVICE_FC,C_SERVICE_REFERENTIEL,C_HEURES_COMPL_FI,C_HEURES_COMPL_FA,C_HEURES_COMPL_FC,C_HEURES_COMPL_FC_MAJOREES,C_HEURES_COMPL_REFERENTIEL,TYPE_INTERVENTION_CODE) values ('3000','118','3','0','1','1','0','0','1','1',null,null,null,null,null,'4,5','3','0','0','0','0,83','0','0','0','0','3,000000000000015','0','0','0','0,83393501805054454873646209386281588448','0','0','0','0','TP');


-- remplacement de vos séquences, pour éviter des chevauchements d'ID plus tard...
-- IDEM : requêtes à n'exécuter qu'une seule fois
/
DROP SEQUENCE FTEST_INTERVENANT_ID_SEQ;
/
DROP SEQUENCE FTEST_VOLUME_HORAIRE_ID_SEQ;
/
CREATE SEQUENCE FTEST_INTERVENANT_ID_SEQ INCREMENT BY 1 MAXVALUE 9999999999999999999999999999 MINVALUE 10000 NOCACHE;
/
CREATE SEQUENCE FTEST_VOLUME_HORAIRE_ID_SEQ INCREMENT BY 1 MAXVALUE 9999999999999999999999999999 MINVALUE 10000 NOCACHE;

/

ALTER TABLE STRUCTURE MODIFY (LIBELLE_LONG VARCHAR2(100 CHAR) );
ALTER TABLE CORPS MODIFY (LIBELLE_LONG VARCHAR2(100 CHAR) );
ALTER TABLE GRADE MODIFY (LIBELLE_LONG VARCHAR2(100 CHAR) );
