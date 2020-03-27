CREATE OR REPLACE PACKAGE BODY "OSE_VALIDATION" AS

  FUNCTION can_devalider ( v validation%rowtype ) RETURN varchar2 IS
    tv type_validation%rowtype;
    nb NUMERIC;
    result varchar2(500) default null;
  BEGIN

    SELECT * INTO tv FROM type_validation WHERE id = v.type_validation_id;

    IF tv.code = 'SERVICES_PAR_COMP' THEN

      SELECT
        SUM(CASE WHEN c.id IS NOT NULL THEN 1 ELSE 0 END) INTO nb
      FROM
        validation_vol_horaire vvh
        JOIN volume_horaire vh ON vh.id = vvh.volume_horaire_id
        LEFT JOIN contrat c ON c.id = vh.contrat_id AND c.histo_destruction IS NULL
      WHERE
        vvh.validation_id = v.id;

      -- Si des volumes horaires ont déjà fait l'objet de contrats alors pas de dévalidation possible des heures
      IF nb > 0 THEN
        result := 'La dévalidation est impossible car des contrats ont déjà été édités sur la base de ces heures.';
      END IF;

    END IF;

    IF tv.code = 'CLOTURE_REALISE' THEN

      SELECT
        COUNT(*) INTO nb
      FROM
        tbl_paiement p
      WHERE
        p.periode_paiement_id IS NOT NULL
        AND p.intervenant_id = v.intervenant_id
        AND ROWNUM = 1;

      IF nb > 0 THEN
        result := 'La suppression de la clôture des services réalisés est impossible car des heures ont été payées ou bien le paiement a été demandé.';
      END IF;

    END IF;

    RETURN result;
  END;

END OSE_VALIDATION;