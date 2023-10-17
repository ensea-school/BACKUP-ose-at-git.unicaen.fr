CREATE OR REPLACE FORCE VIEW V_TBL_VALIDATION_REFERENTIEL AS
SELECT DISTINCT
  i.annee_id,
  i.id intervenant_id,
  CASE WHEN rsv.priorite = 'affectation' THEN
    COALESCE( i.structure_id, s.structure_id )
  ELSE
    COALESCE( s.structure_id, i.structure_id )
  END structure_id,
  vh.type_volume_horaire_id,
  s.id service_referentiel_id,
  vh.id volume_horaire_ref_id,
  vh.auto_validation,
  v.id validation_id
FROM
  service_referentiel s
  JOIN volume_horaire_ref vh ON vh.service_referentiel_id = s.id AND vh.histo_destruction IS NULL
  JOIN intervenant i ON i.id = s.intervenant_id AND i.histo_destruction IS NULL
  JOIN statut si ON si.id = i.statut_id
  LEFT JOIN regle_structure_validation rsv ON rsv.type_intervenant_id = si.type_intervenant_id AND rsv.type_volume_horaire_id = vh.type_volume_horaire_id
  LEFT JOIN validation_vol_horaire_ref vvh ON vvh.volume_horaire_ref_id = vh.id
  LEFT JOIN validation v ON v.id = vvh.validation_id AND v.histo_destruction IS NULL
WHERE
  s.histo_destruction IS NULL
  /*@INTERVENANT_ID=i.id*/
  /*@ANNEE_ID=i.annee_id*/