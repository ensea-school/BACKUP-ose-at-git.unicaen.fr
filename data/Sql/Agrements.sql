select 
  ta.id ta_id,
  ta.code ta_code,
  si.libelle si_libelle,
  tas.premier_recrutement
from
  type_agrement ta
  LEFT JOIN Type_Agrement_Statut tas ON 
                                        tas.type_agrement_id = ta.id
                                    AND 1 = ose_divers.comprise_entre( tas.histo_creation, tas.histo_destruction )
  LEFT JOIN statut_intervenant si ON si.id = tas.statut_intervenant_id
ORDER BY
  ta_code, si_libelle, premier_recrutement;
  
  
-- CA
SELECT
  tas.type_agrement_id,
  i.id intervenant_id,
  tas.obligatoire
FROM
  type_agrement_statut tas
  JOIN type_agrement ta ON ta.id = tas.type_agrement_id
  JOIN intervenant i ON 1 = ose_divers.comprise_entre(i.histo_creation, i.histo_destruction )
                        AND NVL(i.premier_recrutement,0) = tas.premier_recrutement
WHERE
  ta.code = 'CONSEIL_ACADEMIQUE'
  AND 1 = ose_divers.comprise_entre( tas.histo_creation, tas.histo_destruction );
  
  select * from type_agrement