CREATE OR REPLACE FORCE VIEW V_TBL_SERVICE_REFERENTIEL AS
WITH t AS (

  SELECT
    i.annee_id,
    i.id intervenant_id,
    si.referentiel referentiel,
    vh.type_volume_horaire_id,
    s.structure_id,
    CASE WHEN v.id IS NULL AND vh.auto_validation=0 THEN 0 ELSE 1 END valide
  FROM
              intervenant                     i

         JOIN statut                      si ON si.id = i.statut_id

    LEFT JOIN service_referentiel          s ON s.intervenant_id = i.id
                                            AND s.histo_destruction IS NULL

    LEFT JOIN volume_horaire_ref          vh ON vh.service_referentiel_id = s.id
                                            AND vh.histo_destruction IS NULL

    LEFT JOIN validation_vol_horaire_ref vvh ON vvh.volume_horaire_ref_id = vh.id

    LEFT JOIN validation                   v ON v.id = vvh.validation_id
                                            AND v.histo_destruction IS NULL
  WHERE
    i.histo_destruction IS NULL
    /*@INTERVENANT_ID=i.id*/
    /*@ANNEE_ID=i.annee_id*/
)
SELECT
  annee_id,
  intervenant_id,
  referentiel,
  type_volume_horaire_id,
  structure_id,
  CASE WHEN type_volume_horaire_id IS NULL THEN 0 ELSE count(*) END nbvh,
  sum(valide) valide
FROM
  t
WHERE
  NOT (structure_id IS NOT NULL AND type_volume_horaire_id IS NULL)
GROUP BY
  annee_id,
  intervenant_id,
  referentiel,
  type_volume_horaire_id,
  structure_id