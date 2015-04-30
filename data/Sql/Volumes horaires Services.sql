select
  i.id i_id,
  i.source_code i_code,
  s.id s_id,
  vh.ID vh_id, 
  epp.libelle_long periode_ep,
  TVH.LIBELLE type_volume_horaire,
  p.libelle_long periode,
  TI.CODE type_intervention,
  vh.heures,
  vh.motif_non_paiement_id,
  vh.contrat_id,
  vvh.validation_id,
  vh.histo_destruction vh_histo,
  s.histo_destruction s_histo,
  ep.histo_destruction ep_histo,
  v.histo_destruction v_histo
from
  volume_horaire vh
  JOIN service s ON s.id = vh.service_id
  JOIN intervenant i ON i.id = s.intervenant_id
  JOIN type_volume_horaire tvh on tvh.id = vh.TYPE_VOLUME_HORAIRE_ID
  JOIN periode p on p.id = vh.periode_id
  JOIN type_intervention ti on ti.id = vh.type_intervention_id
  LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
  LEFT JOIN VALIDATION_VOL_HORAIRE vvh on VVH.VOLUME_HORAIRE_ID = vh.id
  LEFT JOIN validation v ON v.id = VVH.VALIDATION_ID
  LEFT JOIN periode epp on epp.id = ep.periode_id
where
  1=1
  AND i.source_code = '91521'
--  AND s.id = 3208
  AND vh.histo_destruction IS NULL
  AND i.nom_usuel = 'Paulien'
  --AND ti.code = 'TP'
  --AND validation_id is not null
order by
  s_id, type_volume_horaire, periode, TI.ORDRE;
  
  
--INSERT INTO VALIDATION_VOL_HORAIRE (VALIDATION_ID,VOLUME_HORAIRE_ID) VALUES ( 42, 725 );
--DELETE FROM VALIDATION_VOL_HORAIRE WHERE VALIDATION_ID in (158,175) AND VOLUME_HORAIRE_ID in (634);

SELECT COUNT(*) FROM VALIDATION_VOL_HORAIRE WHERE VOLUME_HORAIRE_ID = 187;
--DELETE FROM volume_horaire WHERE id IN (727);
--update volume_horaire set heures = 89 where id=29293;
--update volume_horaire set histo_destruction = null, histo_destructeur_id = null where id = 24883;

