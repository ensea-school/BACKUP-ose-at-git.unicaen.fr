CREATE OR REPLACE FORCE VIEW V_TBL_AGREMENT AS
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
                                    AND tas.histo_destruction IS NULL

  JOIN intervenant                 i ON i.histo_destruction IS NULL
                                    AND (tas.premier_recrutement IS NULL OR NVL(i.premier_recrutement,0) = tas.premier_recrutement)
                                    AND i.statut_id = tas.statut_intervenant_id

  LEFT JOIN agrement               a ON a.type_agrement_id = ta.id
                                    AND a.intervenant_id = i.id
                                    AND a.histo_destruction IS NULL
WHERE
  ta.code = 'CONSEIL_ACADEMIQUE'

UNION ALL

SELECT
  i.annee_id              annee_id,
  tas.type_agrement_id    type_agrement_id,
  i.id                    intervenant_id,
  i_s.structure_id        structure_id,
  tas.obligatoire         obligatoire,
  a.id                    agrement_id
FROM
  type_agrement                   ta
  JOIN type_agrement_statut      tas ON tas.type_agrement_id = ta.id
                                    AND tas.histo_destruction IS NULL

  JOIN intervenant                 i ON i.histo_destruction IS NULL
                                    AND (tas.premier_recrutement IS NULL OR NVL(i.premier_recrutement,0) = tas.premier_recrutement)
                                    AND i.statut_id = tas.statut_intervenant_id

  JOIN                           i_s ON i_s.intervenant_id = i.id

  LEFT JOIN agrement               a ON a.type_agrement_id = ta.id
                                    AND a.intervenant_id = i.id
                                    AND a.structure_id = i_s.structure_id
                                    AND a.histo_destruction IS NULL
WHERE
  ta.code = 'CONSEIL_RESTREINT'