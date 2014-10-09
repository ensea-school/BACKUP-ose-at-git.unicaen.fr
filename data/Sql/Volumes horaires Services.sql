select
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
  i.source_code = 47955
  --AND vh.histo_destruction IS NULL
  --AND ti.code = 'TP'
order by
  s_id, type_volume_horaire, periode, TI.ORDRE;
  
  
INSERT INTO VALIDATION_VOL_HORAIRE (VALIDATION_ID,VOLUME_HORAIRE_ID) VALUES ( 42, 725 );
DELETE FROM VALIDATION_VOL_HORAIRE WHERE VALIDATION_ID = 42 AND VOLUME_HORAIRE_ID = 725;

SELECT COUNT(*) FROM VALIDATION_VOL_HORAIRE WHERE VOLUME_HORAIRE_ID = 187;
DELETE FROM volume_horaire WHERE id IN (727);
update volume_horaire set heures = 3 where id=728;
update volume_horaire set histo_destruction = null, histo_destructeur_id = null where id = 728;


SELECT COUNT(*)
  FROM 
    VALIDATION_VOL_HORAIRE vvh
    JOIN validation v ON v.id = VVH.VALIDATION_ID
  WHERE
    V.HISTO_DESTRUCTION IS NULL
    AND vvh.VOLUME_HORAIRE_ID = 699;
    
    
INSERT INTO ETAT_VOLUME_HORAIRE (
    CODE,
    LIBELLE,
    ID, HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID
)VALUES(
    'contrat-valide',
    'Contrat validé',
    ETAT_VOLUME_HORAIRE_ID_SEQ.NEXTVAL, 1, 1
);













