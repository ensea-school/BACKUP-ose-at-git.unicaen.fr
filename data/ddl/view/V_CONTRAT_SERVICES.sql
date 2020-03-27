CREATE OR REPLACE FORCE VIEW V_CONTRAT_SERVICES AS
SELECT
  c.contrat_id                                     contrat_id,
  str.libelle_court                                "serviceComposante",
  ep.code                                          "serviceCode",
  ep.libelle                                       "serviceLibelle",
  sum(vh.heures)                                   heures,
  replace(ltrim(to_char(sum(vh.heures), '999999.00')),'.',',') "serviceHeures"
FROM
  (SELECT
    c1.id contrat_id,
    c1.intervenant_id,
    c1.validation_id,
    c1.structure_id,
    c2.id all_contrat_id
  FROM
    contrat c1,
    contrat c2
    LEFT JOIN validation v ON v.id = c2.validation_id AND v.histo_destruction IS NULL
  WHERE
    c1.histo_destruction IS NULL
    AND c2.histo_destruction IS NULL
    AND c1.structure_id = c2.structure_id
    AND (
      c1.id = c2.id
      OR (v.id IS NOT NULL AND c1.contrat_id = c2.id)
      OR (v.id IS NOT NULL AND c1.contrat_id = c2.contrat_id AND c1.id > c2.id)
    )
  )                                  c
       JOIN intervenant              i ON i.id = c.intervenant_id
       JOIN type_volume_horaire    tvh ON tvh.code = 'PREVU'
       JOIN service                  s ON s.intervenant_id = i.id AND s.histo_destruction IS NULL
       JOIN volume_horaire          vh ON vh.service_id = s.id AND vh.histo_destruction IS NULL AND vh.type_volume_horaire_id = tvh.id AND vh.contrat_id = c.all_contrat_id
  LEFT JOIN validation_vol_horaire vvh ON vvh.volume_horaire_id = vh.id
  LEFT JOIN validation               v ON v.id = vvh.validation_id AND v.histo_destruction IS NULL
  LEFT JOIN validation              cv ON cv.id = c.validation_id AND cv.histo_destruction IS NULL
  LEFT JOIN element_pedagogique     ep ON ep.id = s.element_pedagogique_id
       JOIN structure              str ON str.id = COALESCE(ep.structure_id,i.structure_id)
WHERE
  -- On récapitule tous les enseignements validés de la composante et pas seulement le différentiel...
  --AND (cv.id IS NULL OR vh.contrat_id = c.id)
  COALESCE(ep.structure_id,i.structure_id) = c.structure_id
  AND (vh.auto_validation = 1 OR v.id IS NOT NULL)
GROUP BY
  c.contrat_id, str.libelle_court, ep.code, ep.libelle