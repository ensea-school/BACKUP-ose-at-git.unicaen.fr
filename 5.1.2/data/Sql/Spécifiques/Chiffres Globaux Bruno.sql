-- nb de permanents ayant saisi des heures  (enseignements ou référentiel) 
-- nb de vacataires ayant saisi des heures

SELECT
  A.LIBELLE annee,
  ti.libelle type_intervenant,
  COUNT( DISTINCT i.id ) nombre_intervenants
FROM
  intervenant i
  JOIN statut_intervenant         si    ON si.id = i.statut_id
  JOIN type_intervenant           ti    ON ti.id = CASE WHEN si.source_code = 'BIATSS' THEN 2 ELSE si.type_intervenant_id END
  JOIN annee                      a     ON 1=1
  LEFT JOIN service               s     ON s.intervenant_id = i.id    AND s.histo_destruction IS NULL   AND s.annee_id = a.id
  LEFT JOIN service_referentiel   sr    ON sr.intervenant_id = i.id   AND sr.histo_destruction IS NULL  AND sr.annee_id = a.id
  LEFT JOIN fonction_referentiel  fr    ON fr.id = sr.fonction_id     AND fr.histo_destruction IS NULL
WHERE
  i.histo_destruction IS NULL
  AND (
    s.id IS NOT NULL
    OR (sr.id IS NOT NULL AND fr.id IS NOT NULL)
  )
GROUP BY
  ti.libelle, a.libelle;

-- nb d'heures déclarées
SELECT
  a.libelle annee,
  ti.libelle type_intervenant,
  SUM( vh.heures )          service_heures
FROM
  intervenant i
  JOIN statut_intervenant         si    ON si.id = i.statut_id
  JOIN type_intervenant           ti    ON ti.id = CASE WHEN si.source_code = 'BIATSS' THEN 2 ELSE si.type_intervenant_id END
  JOIN service                    s     ON s.intervenant_id = i.id    AND s.histo_destruction IS NULL
  JOIN volume_horaire             vh    ON vh.service_id = s.id       AND vh.histo_destruction IS NULL
  JOIN annee                      a     ON a.id = s.annee_id
WHERE
  i.histo_destruction IS NULL
GROUP BY
  a.libelle, ti.libelle;


-- nb d'heures déclarées (fonctions référentielles)
SELECT
  a.libelle                 annee,
  SUM( sr.heures )          referentiel_heures
FROM
  intervenant i
  JOIN service_referentiel   sr    ON sr.intervenant_id = i.id   AND sr.histo_destruction IS NULL
  JOIN fonction_referentiel  fr    ON fr.id = sr.fonction_id     AND fr.histo_destruction IS NULL
  JOIN annee                 a     ON a.id = sr.annee_id
WHERE
  i.histo_destruction IS NULL
GROUP BY
  a.libelle;


-- nb d'enseignements sur lesquels ont été saisis des heures
SELECT
  a.libelle                 annee,
  COUNT( DISTINCT s.element_pedagogique_id )          elements_pedagogiques
FROM
  intervenant i
  JOIN service               s     ON s.intervenant_id = i.id    AND s.histo_destruction IS NULL
  JOIN annee                 a     ON a.id = s.annee_id
WHERE
  i.histo_destruction IS NULL
GROUP BY
  a.libelle;


-- Total HETD
SELECT
  SUM( hetd.heures ) heures_hetd
FROM
  intervenant i
  JOIN v_formule_heures_hetd hetd  ON hetd.intervenant_id = i.id
WHERE
  i.histo_destruction IS NULL;


-- Total HC
SELECT
  SUM( CASE WHEN comp.heures < 0 THEN 0 ELSE comp.heures END )      heures_complementaires
FROM
  intervenant i
  JOIN v_formule_heures_comp comp  ON comp.intervenant_id = i.id
WHERE
  i.histo_destruction IS NULL;