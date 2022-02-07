SELECT
  i.annee_id                 annee_id,
  vhr.type_volume_horaire_id type_volume_horaire_id,
  i.id                       intervenant_id,
  s.id                       structure_id,
  SUM(vhr.heures)            heures
FROM
  service_referentiel       sr
  JOIN intervenant           i ON i.id = sr.intervenant_id
  JOIN structure             s ON s.id = sr.structure_id AND s.plafond_referentiel IS NOT NULL
  JOIN volume_horaire_ref  vhr ON vhr.service_referentiel_id = sr.id AND vhr.histo_destruction IS NULL
WHERE
  sr.histo_destruction IS NULL
GROUP BY
  i.annee_id, vhr.type_volume_horaire_id, i.id, s.id, s.plafond_referentiel