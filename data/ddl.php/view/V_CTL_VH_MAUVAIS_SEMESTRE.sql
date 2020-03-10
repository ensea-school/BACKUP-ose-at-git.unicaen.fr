CREATE OR REPLACE FORCE VIEW V_CTL_VH_MAUVAIS_SEMESTRE AS
SELECT
  vh.id,
  i.nom_usuel, i.prenom,
  vh.heures,
  vvh.validation_id
FROM
  volume_horaire vh
  JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id
  JOIN service s ON s.id = vh.service_id
  JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
  JOIN intervenant i ON i.id = s.intervenant_id
  LEFT JOIN VALIDATION_VOL_HORAIRE vvh on VVH.VOLUME_HORAIRE_ID = vh.id
WHERE
  tvh.code = 'PREVU'
  AND ep.periode_id IS NOT NULL
  AND vh.periode_id <> ep.periode_id
ORDER BY
  nom_usuel, prenom, heures