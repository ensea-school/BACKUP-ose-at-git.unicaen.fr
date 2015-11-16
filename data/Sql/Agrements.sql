select 
  ta.id ta_id,
  ta.code ta_code,
  si.libelle si_libelle,
  tas.premier_recrutement
from
  type_agrement ta
  LEFT JOIN Type_Agrement_Statut tas ON tas.type_agrement_id = ta.id
  LEFT JOIN statut_intervenant si ON si.id = tas.statut_intervenant_id
ORDER BY
  ta_code, si_libelle, premier_recrutement