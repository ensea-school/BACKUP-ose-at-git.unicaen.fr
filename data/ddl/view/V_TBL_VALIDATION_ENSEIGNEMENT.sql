CREATE OR REPLACE FORCE VIEW V_TBL_VALIDATION_ENSEIGNEMENT AS
SELECT
  i.annee_id                annee_id,
  i.id                      intervenant_id,
  CASE WHEN rsv.priorite = 'affectation' THEN
    COALESCE( i.structure_id, ep.structure_id )
  ELSE
    COALESCE( ep.structure_id, i.structure_id )
  END                       structure_id,
  vh.type_volume_horaire_id type_volume_horaire_id,
  evh.id                    etat_volume_horaire_id,
  evh.ordre                 etat_volume_horaire_ordre,
  s.id                      service_id,
  vh.id                     volume_horaire_id,
  vh.auto_validation        auto_validation,
  t.validation_id           validation_id,
  CASE WHEN vh.auto_validation = 1 OR t.validation_id IS NOT NULL THEN 1 ELSE 0 END valide
FROM
  volume_horaire  vh
       JOIN service          s ON s.id = vh.service_id
       JOIN intervenant      i ON i.id = s.intervenant_id
       JOIN statut          si ON si.id = i.statut_id
  LEFT JOIN regle_structure_validation rsv ON rsv.type_intervenant_id = si.type_intervenant_id AND rsv.type_volume_horaire_id = vh.type_volume_horaire_id
  LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
  LEFT JOIN (
    SELECT
      vvh.volume_horaire_id,
      vvh.validation_id
    FROM
      type_validation tv
      JOIN validation v ON v.type_validation_id = tv.id AND v.histo_destruction IS NULL
      JOIN validation_vol_horaire vvh ON vvh.validation_id = v.id
    WHERE
      tv.code = 'SERVICES_PAR_COMP'
  ) t ON t.volume_horaire_id = vh.id
  LEFT JOIN contrat          c ON c.id = vh.contrat_id AND c.histo_destruction IS NULL
  LEFT JOIN validation      cv ON cv.id = c.validation_id AND cv.histo_destruction IS NULL
       JOIN etat_volume_horaire evh ON evh.code = CASE
         WHEN c.date_retour_signe IS NOT NULL            THEN 'contrat-signe'
         WHEN cv.id IS NOT NULL                          THEN 'contrat-edite'
         WHEN vh.auto_validation = 1 OR t.volume_horaire_id IS NOT NULL THEN 'valide'
                                                         ELSE 'saisi'
       END
WHERE
  (vh.histo_destruction IS NULL OR t.validation_id IS NOT NULL)
  /*@intervenant_id=i.id*/
  /*@annee_id=i.annee_id*/
  /*@statut_id=si.id*/