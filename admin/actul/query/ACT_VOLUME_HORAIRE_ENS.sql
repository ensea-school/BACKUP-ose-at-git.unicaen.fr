select distinct anu.cod_anu annee_id
     ,ltrim(rtrim(arb.code_fils)) code_elp
     ,chg.typ_heu type_intervention
     ,chg.nb_heures heures
     ,concat_ws('_',ltrim(rtrim(arb.code_fils)),chg.typ_heu) source_code
     ,concat_ws('_',anu.cod_anu,ltrim(rtrim(arb.code_fils)),chg.typ_heu) id
     ,elg.nb_gpes groupes
--     ,ifnull(elg.nb_gpes,1) grp
from PREV_ELEMENT_PEDAGOGI 						 elp
	join (
				select elp_root.prev_vet_id
					, elp_root.id
					, elp_root.code
					, elp.prev_vet_id prev_vet_id_fils
					, elp.id id_fils
					, ifnull(elp.code, elp_com.code) code_fils
					, case elp.commun
							when true then 'O'
					else 'N'
					end tem_elp_comm
					, case elp.code
							when true then  elp.prev_vet_id
							else elp_com.prev_vet_id
					end vet_porteuse
					, case elp_com.commun
						when true then 0
						else 1
					end tem_vet_porteuse
				from (
						select *
						from PREV_ELEMENT_PEDAGOGI
						where id  in (select distinct prev_elp_parent_id from PREV_ELEMENT_PEDAGOGI where prev_elp_parent_id is not null)
					)elp_root
				join  PREV_ELEMENT_PEDAGOGI elp on elp.prev_elp_parent_id = elp_root.id
				left join PREV_ELEMENT_PEDAGOGI elp_com on elp_com.id = elp.prev_elp_reference_id
				left join PREV_ELEMENT_PEDAGOGI elp_ref on elp_ref.id = elp.prev_elp_reference_id
				union all
				select elp_root.prev_vet_id
					, null
					, null
					, elp.prev_vet_id prev_vet_id_fils
					, elp.id id_fils
					, ifnull(elp.code, elp_com.code) code_fils
					, case elp.commun
							when true then 'O'
					else 'N'
					end tem_elp_comm
					, case elp.code
							when true then  elp.prev_vet_id
							else elp_com.prev_vet_id
					end vet_porteuse
					, case elp_com.commun
								when true then 0
					else 1
					end tem_vet_porteuse
				from ( select * from PREV_ELEMENT_PEDAGOGI where id  in
						(
							select distinct id
							from PREV_ELEMENT_PEDAGOGI
							where prev_elp_parent_id is null
							and id not in (
							select distinct prev_elp_parent_id
							from PREV_ELEMENT_PEDAGOGI
							where prev_elp_parent_id is not null
						)
					)
				)elp_root
				join  PREV_ELEMENT_PEDAGOGI elp on elp.id = elp_root.id
				left join PREV_ELEMENT_PEDAGOGI elp_com on elp_com.id = elp.prev_elp_reference_id
				left join PREV_ELEMENT_PEDAGOGI elp_ref on elp_ref.id = elp.prev_elp_reference_id) 	arb on arb.id_fils = elp.id
    join PREV_VERSION_ETAPE				         vde on vde.id = arb.prev_vet_id_fils
	join PREV_ETAPE                      		 etp on etp.id = vde.prev_etape_id
	join PREV_PROJET							 anu on anu.cod_anu = :v_annee and anu.temoin_actif = 1
	join PREV_DIPLOME							 dip on  dip.prev_projet_id = anu.cod_anu
	join PREV_VERSION_DIPLOME       			 vdi on vdi.prev_diplome_id = dip.id and vdi.id = etp.prev_version_diplome_id
    left join PREV_VET_TYPINS 					 vti on vde.id = vti.prev_vet_id
    join PREV_HEUS             chg  on chg.prev_elp_id = arb.id_fils
    left join PREV_ELP_CALC_NB_GPES elg  on elg.prev_elp_id = chg.prev_elp_id and elg.typ_heu = chg.typ_heu
where elp.id not in (select distinct prev_elp_parent_id from PREV_ELEMENT_PEDAGOGI where prev_elp_parent_id is not null )
  and elp.code is not null
  and elp.prev_elp_reference_id is null
order by source_code