CREATE OR REPLACE FORCE VIEW V_CTL_SERVICES_ODF_HISTO AS
with vh as (
  SELECT
    vh.service_id,
    ti.code type_intervention,
    SUM(heures) heures,
    CASE WHEN vh.contrat_id IS NULL THEN 0 ELSE 1 END has_contrat,
    CASE WHEN (SELECT COUNT(*) FROM validation_vol_horaire vvh WHERE vvh.volume_horaire_id = vh.id) = 1 THEN 1 ELSE 0 END has_validation
  FROM
    volume_horaire vh
    JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id
    JOIN type_intervention ti ON ti.id = vh.type_intervention_id
  WHERE
    vh.histo_destruction IS NULL
    AND tvh.code = 'PREVU'
  GROUP BY
    vh.id, ti.code, vh.service_id, vh.contrat_id
)
SELECT
  i.prenom, i.nom_usuel,
  ep.source_code "ELEMENT",
  e.source_code etape,

  vh.type_intervention,
  vh.heures,
  vh.has_contrat,
  vh.has_validation,
  CASE WHEN ep.histo_destruction IS NOT NULL THEN 1 ELSE 0 END element_supprime,
  CASE WHEN e.histo_destruction IS NOT NULL THEN 1 ELSE 0 END etape_supprimee,
  CASE WHEN et.histo_destruction IS NOT NULL THEN 1 ELSE 0 END etablissement_supprime
FROM
  service s
  JOIN intervenant i ON i.id = s.intervenant_id
  LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
  LEFT JOIN etape e ON e.id = ep.etape_id
  LEFT JOIN etablissement et ON et.id = s.etablissement_id
  LEFT JOIN vh ON vh.service_id = s.id
WHERE
  s.histo_destruction IS NULL
  AND (
    (ep.id IS NOT NULL AND ep.histo_destruction IS NOT NULL)
    OR
    (e.id IS NOT NULL AND e.histo_destruction IS NOT NULL)
    OR
    (et.id IS NOT NULL AND et.histo_destruction IS NOT NULL)
  )
order by
  nom_usuel, prenom, etape, "ELEMENT", heures