select anu.cod_anu annee_id
,dip.libelle_long libelle
,dip.libelle_court libelle_court
,tpd.typ_dip_apo z_type_diplome_id
,dip.cod_mev_apo mention_accredite
,null  as z_structure_id
,dip.cod_cmp_cod cmp_apo
,concat(ltrim(rtrim(dip.code)), '_', vdi.code) source_code
,dip.cod_coll collegium
,dip.cod_sds_apo secteur_sise
,dip.cod_dfd_apo domaine_formation
,case dip.exported
	when true then 'O'
	else 'N'
 end tem_exported
,vdi.libelle_long  as libelle_vdi
,vdi.libelle_court as libelle_court_vdi
,case vdi.exported
	when true then 'O'
	else 'N'
 end tem_exported_vdi
    from PREV_PROJET                  			 anu
    join PREV_DIPLOME                    		 dip on dip.prev_projet_id = anu.cod_anu
    join PREV_TYP_DIPLOME                		 tpd on dip.prev_typ_diplome_id = tpd.id
    join PREV_VERSION_DIPLOME            		 vdi on vdi.prev_diplome_id = dip.id
where anu.cod_anu =:v_annee
	  and anu.temoin_actif = 1
order by annee_id, source_code