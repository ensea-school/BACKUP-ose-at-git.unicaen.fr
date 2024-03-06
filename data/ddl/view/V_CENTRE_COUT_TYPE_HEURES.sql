CREATE OR REPLACE FORCE VIEW V_CENTRE_COUT_TYPE_HEURES AS
SELECT
  cc.id centre_cout_id,
  th.id type_heures_id
FROM
  centre_cout cc
  JOIN type_ressource  tr ON tr.id = cc.type_ressource_id
  JOIN cc_activite    cca ON cca.id = cc.activite_id
  JOIN type_heures     th ON th.code = decode(tr.fi + cca.fi, 2, 'fi', NULL)

UNION ALL

SELECT
  cc.id centre_cout_id,
  th.id type_heures_id
FROM
  centre_cout cc
  JOIN type_ressource  tr ON tr.id = cc.type_ressource_id
  JOIN cc_activite    cca ON cca.id = cc.activite_id
  JOIN type_heures     th ON th.code = decode(tr.fc + cca.fc, 2, 'fc', NULL)

UNION ALL

SELECT
  cc.id centre_cout_id,
  th.id type_heures_id
FROM
  centre_cout cc
  JOIN type_ressource  tr ON tr.id = cc.type_ressource_id
  JOIN cc_activite    cca ON cca.id = cc.activite_id
  JOIN type_heures     th ON th.code = decode(tr.fa + cca.fa, 2, 'fa', NULL)

UNION ALL

SELECT
  cc.id centre_cout_id,
  th.id type_heures_id
FROM
  centre_cout cc
  JOIN type_ressource  tr ON tr.id = cc.type_ressource_id
  JOIN cc_activite    cca ON cca.id = cc.activite_id
  JOIN type_heures     th ON th.code = decode(tr.referentiel + cca.referentiel, 2, 'referentiel', NULL)

UNION ALL

SELECT
  cc.id centre_cout_id,
  th.id type_heures_id
FROM
  centre_cout cc
  JOIN type_ressource  tr ON tr.id = cc.type_ressource_id
  JOIN cc_activite    cca ON cca.id = cc.activite_id
  JOIN type_heures     th ON th.code = decode(tr.primes + cca.primes, 2, 'primes', NULL)