CREATE OR REPLACE FORCE VIEW V_INDICATEUR_500 AS
SELECT DISTINCT
  i.id            intervenant_id,i.annee_id,
  i.structure_id  structure_id
FROM
            intervenant                  i
       JOIN statut                      si ON si.id = i.statut_id
       JOIN type_intervenant            ti ON ti.id = si.type_intervenant_id
  LEFT JOIN modification_service_du    msd ON msd.intervenant_id = i.id AND msd.histo_destruction IS NULL
  LEFT JOIN motif_modification_service mms ON mms.id = msd.motif_id
  LEFT JOIN (
    SELECT ts.intervenant_id, SUM(ts.heures) heures
    FROM tbl_service ts
    WHERE ts.type_volume_horaire_code = 'PREVU'
    GROUP BY ts.intervenant_id
    HAVING   SUM(ts.heures) > 0
  ) ens ON ens.intervenant_id = i.id
  LEFT JOIN (
    SELECT tr.intervenant_id, SUM(tr.heures) heures
    FROM tbl_referentiel tr
    WHERE tr.type_volume_horaire_code = 'PREVU'
    GROUP BY tr.intervenant_id
    HAVING   SUM(tr.heures) > 0
  ) ref ON ref.intervenant_id = i.id
WHERE
  i.histo_destruction IS NULL
  AND ti.code = 'P'
  AND ens.intervenant_id IS NULL
  AND ref.intervenant_id IS NULL
GROUP BY
  i.id, i.annee_id, i.structure_id, si.service_statutaire
HAVING
  si.service_statutaire + COALESCE( SUM( msd.heures * mms.multiplicateur ), 0 ) > 0