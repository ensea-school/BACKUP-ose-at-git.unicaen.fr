CREATE OR REPLACE FORCE VIEW V_PRIVILEGES_ROLES AS
WITH statuts_roles AS (
SELECT
  rp.privilege_id,
  r.code role
FROM
  role_privilege rp
  JOIN role r ON r.id = rp.role_id AND r.histo_destruction IS NULL

UNION ALL

SELECT
  sp.privilege_id,
  'statut/' || s.code role
FROM
  statut_privilege sp
  JOIN statut s ON s.id = sp.statut_id AND s.histo_destruction IS NULL
)
SELECT
  cp.code || '-' || p.code privilege,
  sr.role
FROM
  privilege p
  JOIN categorie_privilege cp ON cp.id = p.categorie_id
  LEFT JOIN statuts_roles sr ON sr.privilege_id = p.id