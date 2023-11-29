CREATE OR REPLACE FORCE VIEW V_SYNTHESE_PRIVILEGE AS
SELECT
  r.libelle role,
  cp.libelle categorie_privilege,
  p.libelle privilege,
  CASE WHEN r.code = 'administrateur' THEN
    CASE
      WHEN cp.code = 'enseignement' AND p.code =  'prevu-autovalidation' THEN 'Non'
      WHEN cp.code = 'enseignement' AND p.code =  'realise-autovalidation' THEN 'Non'
      WHEN cp.code = 'referentiel' AND p.code =  'prevu-autovalidation' THEN 'Non'
      WHEN cp.code = 'referentiel' AND p.code =  'realise-autovalidation' THEN 'Non'
      ELSE 'Oui'
    END
  ELSE
    CASE WHEN rp.privilege_id IS NULL THEN 'Non' ELSE 'Oui' END
  END acces
FROM
  role r
  JOIN privilege p ON 1=1
  JOIN categorie_privilege cp ON cp.id = p.categorie_id
  LEFT JOIN role_privilege rp ON rp.privilege_id = p.id AND rp.role_id = r.id
ORDER BY
  r.libelle, cp.libelle, p.libelle