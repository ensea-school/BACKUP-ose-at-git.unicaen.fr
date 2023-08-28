CREATE OR REPLACE FORCE VIEW V_TBL_PRIME AS
SELECT
  c.id                       contrat_id,
  m.id                       mission_id,
  tm.id                      type_mission_id,
  c.intervenant_id           intervenant_id,
  s.id                       structure_id,
  i.annee_id                 annee_id,
  c.declaration_id	         fichier_id,
  f.validation_id            validation_id
FROM
            contrat         c
       JOIN mission m ON m.id = c.mission_id
       JOIN type_mission tm ON tm.id = m.type_mission_id
       JOIN structure s ON s.id = m.structure_id
       JOIN validation      v ON v.id = c.validation_id
                             AND v.histo_destruction IS NULL
       JOIN intervenant i ON i.id = m.intervenant_id
  LEFT JOIN fichier f ON f.id = c.declaration_id
  LEFT JOIN validation v ON f.validation_id = v.id
  LEFT JOIN contrat    c_suiv ON c_suiv.histo_destruction IS NULL
                             AND c_suiv.fin_validite <> c.fin_validite
                             AND c_suiv.intervenant_id = c.intervenant_id
                             AND c.fin_validite BETWEEN c_suiv.debut_validite-1 AND c_suiv.fin_validite
                             AND c.type_contrat_id = (SELECT id FROM type_contrat WHERE code = 'CONTRAT')
  LEFT JOIN validation v_suiv ON v_suiv.id = c_suiv.validation_id
                             AND v_suiv.histo_destruction IS NULL
WHERE
  c.histo_destruction IS NULL
  AND v_suiv.id IS NULL
  AND c.fin_validite < SYSDATE
  AND c.type_contrat_id = (SELECT id FROM type_contrat WHERE code = 'CONTRAT')
  ORDER BY c.fin_validite ASC