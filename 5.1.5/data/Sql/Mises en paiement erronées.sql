SELECT 
  id--, source_code, nom_usuel, prenom
FROM
(
select
  i.id, i.source_code, i.nom_usuel, i.prenom, case when v.id is null then 1 else 0 end non_valid, case when mep.id is null then 0 else 1 end nb_mep
from
  intervenant i
  JOIN type_intervenant ti ON ti.id = i.type_id
  JOIN service s ON s.intervenant_id = i.id AND 1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )
  JOIN volume_horaire vh ON vh.service_id = s.id AND 1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
  JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id 
  LEFT JOIN validation_vol_horaire vvh ON vvh.volume_horaire_id = vh.id
  LEFT JOIN validation v ON v.id = vvh.validation_id AND 1 = ose_divers.comprise_entre( v.histo_creation, v.histo_destruction )
  LEFT JOIN formule_resultat_service frs ON frs.service_id = s.id
  LEFT JOIN mise_en_paiement mep ON mep.formule_res_service_id = frs.id AND 1 = ose_divers.comprise_entre( mep.histo_creation, mep.histo_destruction )
WHERE
  tvh.code = 'REALISE'
  AND ti.code = 'P'
  --AND i.source_code = 732

UNION

select
  i.id, i.source_code, i.nom_usuel, i.prenom, case when v.id is null then 1 else 0 end non_valid, case when mep.id is null then 0 else 1 end nb_mep
from
  intervenant i
  JOIN type_intervenant ti ON ti.id = i.type_id
  JOIN service_referentiel s ON s.intervenant_id = i.id AND 1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )
  JOIN volume_horaire_ref vh ON vh.service_referentiel_id = s.id AND 1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
  JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id 
  LEFT JOIN validation_vol_horaire_ref vvh ON vvh.volume_horaire_ref_id = vh.id
  LEFT JOIN validation v ON v.id = vvh.validation_id AND 1 = ose_divers.comprise_entre( v.histo_creation, v.histo_destruction )
  LEFT JOIN formule_resultat_service_ref frs ON frs.service_referentiel_id = s.id
  LEFT JOIN mise_en_paiement mep ON mep.formule_res_service_ref_id = frs.id AND 1 = ose_divers.comprise_entre( mep.histo_creation, mep.histo_destruction )
WHERE
  tvh.code = 'REALISE'
  AND ti.code = 'P'
  --AND i.source_code = 732
) t1
GROUP BY
  id, source_code, nom_usuel, prenom
HAVING 
  sum(non_valid) > 0 AND sum(nb_mep) > 0
order by
  nom_usuel
;




select
  'DELETE FROM mise_en_paiement WHERE id = ' || mep.id || ';'
  --, mep.id, fr.intervenant_id
FROM
  mise_en_paiement mep
  LEFT JOIN formule_resultat_service_ref frsr ON frsr.id = mep.FORMULE_RES_SERVICE_REF_ID
  LEFT JOIN formule_resultat_service      frs ON  frs.id = mep.FORMULE_RES_SERVICE_ID
  LEFT JOIN formule_resultat fr ON fr.id = NVL( frs.formule_resultat_id, frsr.formule_resultat_id )
WHERE
  fr.intervenant_id IN (
  2004,1788,308,794,34,2134,2020,1623,1010,27,1328,1620,84,106,702,783,115,1900,309,111,442,1705,4,
  314,338,600,1348,380,758,24,1041,68 );




select  *from intervenant where source_code='36886';







select
  i.source_code, i.nom_usuel, i.prenom, sum(case when v.id is null then 1 else 0 end) non_valid, sum(case when mep.id is null then 0 else 1 end) nb_mep
from
  intervenant i
  JOIN type_intervenant ti ON ti.id = i.type_id
  JOIN service s ON s.intervenant_id = i.id AND 1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )
  JOIN volume_horaire vh ON vh.service_id = s.id AND 1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
  JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id 
  LEFT JOIN validation_vol_horaire vvh ON vvh.volume_horaire_id = vh.id
  LEFT JOIN validation v ON v.id = vvh.validation_id AND 1 = ose_divers.comprise_entre( v.histo_creation, v.histo_destruction )
  LEFT JOIN formule_resultat_service frs ON frs.service_id = s.id
  LEFT JOIN mise_en_paiement mep ON mep.formule_res_service_id = frs.id AND 1 = ose_divers.comprise_entre( mep.histo_creation, mep.histo_destruction )
WHERE
  tvh.code = 'REALISE'
  AND ti.code = 'P'
  AND i.source_code = 732
GROUP BY
  i.source_code, i.nom_usuel, i.prenom;
  
  
select mep.*  
from
  intervenant i
  JOIN type_intervenant ti ON ti.id = i.type_id
  JOIN service s ON s.intervenant_id = i.id AND 1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )
  JOIN volume_horaire vh ON vh.service_id = s.id AND 1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
  JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id 
  LEFT JOIN validation_vol_horaire vvh ON vvh.volume_horaire_id = vh.id
  LEFT JOIN validation v ON v.id = vvh.validation_id AND 1 = ose_divers.comprise_entre( v.histo_creation, v.histo_destruction )
  LEFT JOIN formule_resultat_service frs ON frs.service_id = s.id
  LEFT JOIN mise_en_paiement mep ON mep.formule_res_service_id = frs.id --AND 1 = ose_divers.comprise_entre( mep.histo_creation, mep.histo_destruction )
WHERE
  tvh.code = 'REALISE'
  AND ti.code = 'P'
  AND i.source_code = 732










