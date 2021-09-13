CREATE OR REPLACE PACKAGE BODY OSE_ACTUL AS


  FUNCTION z_to_code ( z_periode_id_semestre VARCHAR2, z_periode_id_ordre NUMERIC ) RETURN VARCHAR2 IS
    pcode VARCHAR2(2);
  BEGIN
    pcode := CASE 
      WHEN z_periode_id_semestre = '1' AND z_periode_id_ordre < 2 THEN 'S1'
      WHEN z_periode_id_semestre = '1' AND z_periode_id_ordre = 2 THEN 'S2'
      WHEN z_periode_id_semestre = '3' AND z_periode_id_ordre < 2 THEN 'S1'
      WHEN z_periode_id_semestre = '3' AND z_periode_id_ordre = 2 THEN 'S2'
      WHEN z_periode_id_semestre = '5' AND z_periode_id_ordre < 2 THEN 'S1'
      WHEN z_periode_id_semestre = '5' AND z_periode_id_ordre = 2 THEN 'S2'
      WHEN z_periode_id_semestre = '2' THEN 'S2'
      WHEN z_periode_id_semestre = '4' THEN 'S2'
      WHEN z_periode_id_semestre = '6' THEN 'S2'
      ELSE NULL
    END;

    RETURN pcode;
  END;



  FUNCTION CALC_SEMESTRE( NOEUD_SOURCE_CODE VARCHAR2, z_periode_id_semestre VARCHAR2, z_periode_id_ordre NUMERIC ) RETURN VARCHAR2 IS
    pcode VARCHAR2(2);
    p_sup VARCHAR2(2);
    is_s1_sup BOOLEAN DEFAULT FALSE;
    is_s2_sup BOOLEAN DEFAULT FALSE;
    is_an_sup BOOLEAN DEFAULT FALSE;
  BEGIN
    IF z_periode_id_semestre IS NOT NULL THEN
      -- si il est fourni, alors on le convertir en code de période et on le retourne
      RETURN z_to_code(z_periode_id_semestre, z_periode_id_ordre);
    END IF;

    -- sinon on recherche un éventuel semestre dans ses noeuds parents
    FOR t IN (
      SELECT
        n.source_code, n.z_periode_id_semestre, n.z_periode_id_ordre
      FROM
        act_lien l
        JOIN act_noeud n ON n.source_code = l.z_noeud_sup_id
      WHERE
        l.z_noeud_inf_id = NOEUD_SOURCE_CODE
    ) LOOP
      p_sup := CALC_SEMESTRE(t.source_code, t.z_periode_id_semestre, t.z_periode_id_ordre);
      IF p_sup = 'S1' THEN
        is_s1_sup := TRUE;
      END IF;
      IF p_sup = 'S2' THEN
        is_s2_sup := TRUE;
      END IF;
      IF p_sup IS NULL THEN
        is_an_sup := TRUE;
      END IF;
    END LOOP;

    IF is_s1_sup AND NOT is_s2_sup AND NOT is_an_sup THEN
      RETURN 'S1';
    END IF;

    IF NOT is_s1_sup AND is_s2_sup AND NOT is_an_sup THEN
      RETURN 'S2';
    END IF;

    RETURN NULL;
  END;



END OSE_ACTUL;