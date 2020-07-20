CREATE OR REPLACE FORCE VIEW SRC_SCENARIO_LIEN AS
SELECT
  s.id                            scenario_id,
  li.id                           lien_id,
  1                               actif,
  1                               poids,
  l.choix_minimum                 choix_minimum,
  l.choix_maximum                 choix_maximum,
  src.id                          source_id,
  l.z_source_code || '_s' || s.id source_code
FROM
            ose_lien@apoprod l
       JOIN source         src ON src.code             = 'Apogee'
       JOIN scenario         s ON s.histo_destruction  IS NULL
       JOIN lien            li ON li.source_code       = l.z_source_code
  LEFT JOIN scenario_lien   sl ON sl.lien_id           = li.id
                              AND sl.scenario_id       = s.id
                              AND sl.histo_destruction IS NULL
                              AND sl.source_id         <> src.id
WHERE
  (l.choix_minimum IS NOT NULL OR l.choix_maximum IS NOT NULL)
  AND sl.id IS NULL