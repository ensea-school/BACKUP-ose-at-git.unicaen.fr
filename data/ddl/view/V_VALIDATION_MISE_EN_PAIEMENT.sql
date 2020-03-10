CREATE OR REPLACE FORCE VIEW V_VALIDATION_MISE_EN_PAIEMENT AS
SELECT
  vvh.validation_id,
  mep.id mise_en_paiement_id
FROM
  validation_vol_horaire vvh
  JOIN volume_horaire vh ON vh.id = vvh.volume_horaire_id
  JOIN formule_resultat_service frs ON frs.service_id = vh.service_id
  JOIN mise_en_paiement mep ON mep.formule_res_service_id = frs.id

UNION

SELECT
  vvh.validation_id,
  mep.id mise_en_paiement_id
FROM
  validation_vol_horaire_ref vvh
  JOIN volume_horaire_ref vh ON vh.id = vvh.volume_horaire_ref_id
  JOIN formule_resultat_service_ref frs ON frs.service_referentiel_id = vh.service_referentiel_id
  JOIN mise_en_paiement mep ON mep.formule_res_service_ref_id = frs.id