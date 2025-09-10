CREATE OR REPLACE FORCE VIEW V_TBL_CLOTURE_REALISE AS
WITH t AS (
  SELECT
    i.annee_id              annee_id,
    i.id                    intervenant_id,
    si.cloture              actif,
    CASE WHEN v.id IS NULL THEN 0 ELSE 1 END cloture
  FROM
              intervenant         i
         JOIN statut             si ON si.id = i.statut_id
         JOIN type_validation    tv ON tv.code = 'CLOTURE_REALISE'

    LEFT JOIN validation          v ON v.intervenant_id = i.id
                                   AND v.type_validation_id = tv.id
                                   AND v.histo_destruction IS NULL

  WHERE
    i.histo_destruction IS NULL
    /*@intervenant_id=i.id*/
    /*@annee_id=i.annee_id*/
)
SELECT
  annee_id,
  intervenant_id,
  actif,
  CASE WHEN sum(cloture) = 0 THEN 0 ELSE 1 END cloture
FROM
  t
GROUP BY
  annee_id,
  intervenant_id,
  actif