SELECT
  i.annee_id annee_id,
  type_volume_horaire_id,
  intervenant_id,
  heures,
  plafond
FROM
  (
  SELECT
    intervenant_id,
    type_volume_horaire_id,
    tranche,
    sum(heures) heures,
    least(min(plafond_tranche_mission), min(plafond_tranche)) plafond
  FROM
    (
    SELECT
      m.intervenant_id                                         intervenant_id,
      vhm.type_volume_horaire_id                               type_volume_horaire_id,
      to_char( vhm.horaire_debut, 'YYYY-mm' )                  tranche,
      vhm.heures                                               heures,
      ROUND(CASE to_char( vhm.horaire_debut, 'mm' ) WHEN '07' THEN 150 WHEN '08' THEN 150 ELSE 67 END / 30 * (m.date_fin - m.date_debut),2) plafond_tranche_mission,
      CASE to_char( vhm.horaire_debut, 'mm' ) WHEN '07' THEN 150 WHEN '08' THEN 150 ELSE 67 END plafond_tranche
    FROM
      volume_horaire_mission vhm
      JOIN type_volume_horaire tvh ON tvh.id = vhm.type_volume_horaire_id AND tvh.code = 'REALISE'
      JOIN mission m ON m.id = vhm.mission_id AND m.histo_destruction IS NULL
    WHERE
      vhm.histo_destruction IS NULL
    ) t
  GROUP BY
    intervenant_id,
    type_volume_horaire_id,
    tranche
) t
JOIN intervenant i ON i.id = t.intervenant_id
WHERE
  heures > plafond
  AND rownum = 1