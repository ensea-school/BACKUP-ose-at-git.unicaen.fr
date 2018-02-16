SELECT
  tas.id,
  si.libelle statut_intervenant,
  ta.code type_agrement,
  tas.obligatoire,
  tas.premier_recrutement,
  tas.histo_destruction
FROM
  type_agrement_statut tas
  JOIN type_agrement ta ON ta.id = tas.type_agrement_id
  JOIN statut_intervenant si ON si.id = tas.statut_intervenant_id
ORDER BY
  statut_intervenant,
  type_agrement