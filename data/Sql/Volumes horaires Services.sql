select
  i.id i_id,
  i.source_code i_code,
  s.id s_id,
  ep.source_code ep_source_code,
  str.libelle_court structure,
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
  v.histo_destruction v_histo, vh.histo_creation, vh.histo_createur_id
from
  volume_horaire vh
  JOIN service s ON s.id = vh.service_id
  JOIN intervenant i ON i.id = s.intervenant_id
  JOIN type_volume_horaire tvh on tvh.id = vh.TYPE_VOLUME_HORAIRE_ID
  JOIN periode p on p.id = vh.periode_id
  JOIN type_intervention ti on ti.id = vh.type_intervention_id
  LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
  LEFT JOIN structure str ON str.id = ep.structure_id
  LEFT JOIN VALIDATION_VOL_HORAIRE vvh on VVH.VOLUME_HORAIRE_ID = vh.id
  LEFT JOIN validation v ON v.id = VVH.VALIDATION_ID
  LEFT JOIN periode epp on epp.id = ep.periode_id
where
  1=1
  AND i.id=17147
  --AND s.id = 84722
  --AND vh.histo_destruction IS NULL
  AND tvh.code = 'PREVU'
  --AND contrat_id = 341
  --AND tvh.id = 2
  --AND vh.periode_id = 12
  --AND i.nom_usuel = 'Campart'
  --AND ti.code = 'TP'
  --AND validation_id is null
order by
  s_id, type_volume_horaire, periode, TI.ORDRE;
  
  select * from utilisateur where id in (3,234);
  
INSERT INTO VALIDATION_VOL_HORAIRE (VALIDATION_ID,VOLUME_HORAIRE_ID) VALUES ( 101723, 239374 );
DELETE FROM VALIDATION_VOL_HORAIRE WHERE VOLUME_HORAIRE_ID in (239374,244921,244923);

SELECT COUNT(*) FROM VALIDATION_VOL_HORAIRE WHERE VOLUME_HORAIRE_ID = 187;
DELETE FROM volume_horaire WHERE id IN (239374,244921,244923);
update volume_horaire set heures = -6 where id=244923;
update volume_horaire set histo_destruction = sysdate, histo_destructeur_id = 4 where id in (
219902,
233875
);




select
  i.id i_id,
  i.source_code i_code,
  s.id s_id,
  vh.ID vh_id, 
  TVH.LIBELLE type_volume_horaire,
  vh.heures,
  vvh.validation_id,
  vh.histo_destruction vh_histo,
  s.histo_destruction s_histo,
  v.histo_destruction v_histo
from
  volume_horaire_ref vh
  JOIN service_referentiel s ON s.id = vh.SERVICE_REFERENTIEL_ID
  JOIN intervenant i ON i.id = s.intervenant_id
  JOIN type_volume_horaire tvh on tvh.id = vh.TYPE_VOLUME_HORAIRE_ID
  LEFT JOIN VALIDATION_VOL_HORAIRE_ref vvh on VVH.VOLUME_HORAIRE_ref_ID = vh.id
  LEFT JOIN validation v ON v.id = VVH.VALIDATION_ID
where
  1=1
  AND i.source_code = '61983'
--  AND s.id = 3208
  AND vh.histo_destruction IS NULL
  --AND i.nom_usuel = 'Mancq'
  --AND ti.code = 'TP'
  --AND validation_id is not null
order by
  s_id, type_volume_horaire;

/

BEGIN UNICAEN_TBL.CALCULER('formule', UNICAEN_TBL.MAKE_PARAMS('intervenant_id',19711)); END;