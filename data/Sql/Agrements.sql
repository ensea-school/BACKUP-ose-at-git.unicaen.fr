SELECT
  i.id i_id,
  a.annee_id,
  ta.code type_agrement,
  i.nom_usuel || ' ' || i.prenom intervenant,
  s.libelle_court structure,
  a.obligatoire,
  a.agrement_id
FROM
  tbl_agrement a
  JOIN type_agrement ta ON ta.id = a.type_agrement_id
  JOIN intervenant i ON i.id = a.intervenant_id
  LEFT JOIN structure s ON s.id = a.structure_id
WHERE
  1=1
  AND i.id = 548
;




WITH i_s AS (
  SELECT DISTINCT
    fr.intervenant_id,
    ep.structure_id
  FROM
    formule_resultat fr
    JOIN type_volume_horaire  tvh ON tvh.code = 'PREVU' AND tvh.id = fr.type_volume_horaire_id
    JOIN etat_volume_horaire  evh ON evh.code = 'valide' AND evh.id = fr.etat_volume_horaire_id

    JOIN formule_resultat_service frs ON frs.formule_resultat_id = fr.id
    JOIN service s ON s.id = frs.service_id
    JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
  WHERE
    frs.total > 0
)
SELECT
  i.annee_id              annee_id,
  tas.type_agrement_id    type_agrement_id,
  i.id                    intervenant_id,
  null                    structure_id,
  tas.obligatoire         obligatoire,
  a.id                    agrement_id
FROM
  type_agrement                  ta
  JOIN type_agrement_statut      tas ON tas.type_agrement_id = ta.id
                                    AND 1 = ose_divers.comprise_entre( tas.histo_creation, tas.histo_destruction )
                               
  JOIN intervenant                 i ON 1 = ose_divers.comprise_entre(i.histo_creation, i.histo_destruction )
                                    AND (tas.premier_recrutement IS NULL OR NVL(i.premier_recrutement,0) = tas.premier_recrutement)
                                    AND i.statut_id = tas.statut_intervenant_id
                            
  LEFT JOIN agrement               a ON a.type_agrement_id = ta.id 
                                    AND a.intervenant_id = i.id
                                    AND 1 = ose_divers.comprise_entre( a.histo_creation, a.histo_destruction )
WHERE
  ta.code = 'CONSEIL_ACADEMIQUE'
  and i.id = 548