select * from (
select
  i.id i_id,
  ie.id ie_id, ip.id ip_id,
  i.source_code,
  i.nom_usuel, i.prenom,
  
  ti.code type_code,
  sti.code statut_type_code,
  ti.code type_intervenant,
  src_si.source_code statut_source,
  si.source_code statut,
  dsi.source_code statut_dossier,
  CASE WHEN ie.source_code IS NULL THEN 0 ELSE 1 END exterieur,
  CASE WHEN ip.source_code IS NULL THEN 0 ELSE 1 END "permanent",
  fvht.heures
  
from
  intervenant i
  JOIN src_intervenant src_i on src_i.source_code = i.source_code
  JOIN statut_intervenant src_si on src_si.id = src_i.statut_id
  JOIN statut_intervenant si on si.id = i.statut_id
  JOIN type_intervenant ti on ti.id = i.type_id
  JOIN type_intervenant sti on sti.id = si.type_intervenant_id
  left join INTERVENANT_EXTERIEUR ie on ie.source_code = i.source_code and ie.histo_destruction is null
  left join INTERVENANT_PERMANENT ip on ip.source_code = i.source_code and ip.histo_destruction is null
  left join dossier d on d.id = ie.dossier_id
  left join statut_intervenant dsi on dsi.id = d.statut_id
  left join v_formule_volume_horaire_total fvht on fvht.intervenant_id = i.id
where
  i.histo_destruction is null
) tmp1

WHERE
  0=1
  OR (exterieur = 1 and "permanent" = 1)
  OR (type_code = 'P' AND "permanent" = 0)
  OR (type_code = 'E' AND exterieur = 0)
  OR (type_code = 'E' AND "permanent" = 1)
  OR (type_code = 'P' AND exterieur = 1) 
  OR (statut_dossier IS NULL AND statut_source <> statut)
  OR (ip_id is not null AND ip_id <> i_id)
  OR (ie_id is not null AND ie_id <> i_id)
  OR (type_code <> statut_type_code)
  OR (statut_dossier IS NOT NULL AND statut <> statut_dossier);
  --1=p
  --2=e
  
update intervenant set statut_id=19, type_id=2 where id = 341;