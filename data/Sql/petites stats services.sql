SELECT DISTINCT
  tvh.code,
  COUNT( DISTINCT i.id) OVER (PARTITION BY tvh.code) nb_intervenants,
  COUNT( DISTINCT s.id) OVER (PARTITION BY tvh.code) nb_services,
  SUM(vh.heures) OVER (PARTITION BY tvh.code) total_heures
from
  service s
  JOIN volume_horaire vh ON vh.service_id = s.id AND 1 = ose_divers.comprise_entre( vh.histo_creation,vh.histo_destruction )
  JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id
  JOIN v_volume_horaire_etat vhe ON vhe.volume_horaire_id = vh.id
  JOIN etat_volume_horaire evh ON evh.id = vhe.etat_volume_horaire_id
  JOIN intervenant i ON i.id = s.intervenant_id AND 1 = ose_divers.comprise_entre( i.histo_creation,i.histo_destruction )
  JOIN type_intervenant ti ON ti.id = i.type_id
  JOIN utilisateur u ON u.intervenant_id = i.id
WHERE
  1 = ose_divers.comprise_entre( s.histo_creation,s.histo_destruction )
  --AND evh.code IN ('valide', 'saisi')
  AND ti.code = 'P' -- permanents
  AND u.id = vh.histo_createur_id
  --AND ti.code = 'E' -- vacataires
;


SELECT 
  count(*)
from
  service s
  JOIN volume_horaire vh ON vh.service_id = s.id AND 1 = ose_divers.comprise_entre( vh.histo_creation,vh.histo_destruction )
  JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id
  JOIN v_volume_horaire_etat vhe ON vhe.volume_horaire_id = vh.id
  JOIN etat_volume_horaire evh ON evh.id = vhe.etat_volume_horaire_id
  JOIN intervenant i ON i.id = s.intervenant_id AND 1 = ose_divers.comprise_entre( i.histo_creation,i.histo_destruction )
  JOIN type_intervenant ti ON ti.id = i.type_id
  JOIN utilisateur u ON u.intervenant_id = i.id
WHERE
  1 = ose_divers.comprise_entre( s.histo_creation,s.histo_destruction )
  --AND evh.code IN ('valide', 'saisi')
  AND tvh.code = 'PREVU'
  AND ti.code = 'P' -- permanents
  AND u.id = vh.histo_createur_id
  --AND ti.code = 'E' -- vacataires
;


select
  count( distinct i.id )
from
  mise_en_paiement mep
  JOIN V_MEP_INTERVENANT_STRUCTURE mis ON mis.MISE_EN_PAIEMENT_ID = mep.id
  --JOIN periode p on p.id = mep.periode_paiement_id
  JOIn intervenant i ON i.id = mis.intervenant_id
  JOIN type_intervenant ti ON ti.id = i.type_id
where
  1 = ose_divers.comprise_entre( mep.histo_creation, mep.histo_destruction )
  --AND p.code = 'M07'
  AND ti.code = 'P'
