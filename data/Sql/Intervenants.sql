SELECT * FROM (
SELECT
  i.id i_id,
  i.source_code i_code,
  i.nom_usuel i_nom,
  i.prenom i_prenom,
  s.source_code s_code,
  s.libelle s_libelle,
  ti.code ti_code,
  ti.libelle ti_libelle,
  str.id structure_id,
  str.source_code structure_code,
  str.libelle_court structure_libelle,
  (select sum(sd.heures) from service_du sd WHERE sd.intervenant_id = i.id AND sd.histo_destruction IS NULL) service_du,
  s.service_statutaire
FROM
  intervenant i
  JOIN statut_intervenant s ON s.id = i.statut_id AND s.histo_destruction is null
  JOIN type_intervenant ti ON ti.id = i.type_id AND ti.histo_destruction is null
  JOIN structure str ON str.id = i.structure_id AND str.histo_destruction is null
WHERE
  i.histo_destruction is null
) tmp
WHERE
  nvl(service_du,0) <> service_statutaire