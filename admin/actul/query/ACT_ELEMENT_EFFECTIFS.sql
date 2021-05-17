select 	 ltrim(rtrim(arb.code_fils)) z_element_pedagogique_id
		,anu.cod_anu  annee_id
		,IFNULL(typ_ins.fi * elp.calc_eff_prev ,0) effectif_fi
		,IFNULL(typ_ins.fa * elp.calc_eff_prev ,0) effectif_fa
		,IFNULL(typ_ins.fc	* elp.calc_eff_prev ,0) effectif_fc
from PREV_ELEMENT_PEDAGOGI 	elp
	join (
			select elp_root.prev_vet_id
				, elp_root.id
				, elp_root.code
				, elp.prev_vet_id prev_vet_id_fils
				, case elp.libelle_long
						when '-' then elp_com.libelle_long
						else elp.libelle_long
				  end libelle_fils
				, ifnull(elp.code, elp_com.code) code_fils
				, elp.id id_fils
				, case elp.commun
						when true then 'O'
				else 'N'
				end tem_elp_comm
				, case elp.code
						when true then  elp.prev_vet_id
						else elp_com.prev_vet_id -- elp_com.prev_vet_id
				end vet_porteuse
				, case elp_com.commun
						when true then 0
						else 1
				end tem_vet_porteuse
				, elp.code_section_cnu cnu_fils
			from ( select * from PREV_ELEMENT_PEDAGOGI where id  in (select distinct prev_elp_parent_id from PREV_ELEMENT_PEDAGOGI where prev_elp_parent_id is not null) /*and id in (199,206)*/ )elp_root
			join  PREV_ELEMENT_PEDAGOGI elp on elp.prev_elp_parent_id = elp_root.id
			left join PREV_ELEMENT_PEDAGOGI elp_com on elp_com.id = elp.prev_elp_reference_id
			left join PREV_ELEMENT_PEDAGOGI elp_ref on elp_ref.id = elp.prev_elp_reference_id
			union all
			select elp_root.prev_vet_id
				, null
				, null
				, elp.prev_vet_id prev_vet_id_fils
				, case elp.libelle_long
					when '-' then elp_com.libelle_long
					else elp.libelle_long
				  end libelle_fils
				, IFNULL(elp.code, elp_com.code) code_fils
				, elp.id id_fils
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
				, elp.code_section_cnu cnu_fils
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
			left join PREV_ELEMENT_PEDAGOGI elp_ref on elp_ref.id = elp.prev_elp_reference_id) 		arb on arb.id_fils = elp.id
    join PREV_VERSION_ETAPE				       vde on vde.id = arb.prev_vet_id_fils
    join PREV_ETAPE                      	   etp on etp.id = vde.prev_etape_id
	join PREV_PROJET                  		   anu on cod_anu =:v_annee and anu.temoin_actif = 1
	join PREV_DIPLOME                    	   dip on dip.prev_projet_id = anu.cod_anu
    join PREV_VERSION_DIPLOME            	   vdi on vdi.prev_diplome_id = dip.id and vdi.id = etp.prev_version_diplome_id
	left join PREV_VET_TYPINS 				   vti on vde.id = vti.prev_vet_id
    left join (	select case tis.prev_typins_id
							when 0 then 1
						else 0
						end fi
					 , case tis.prev_typins_id
					 	when 1 then 1
					   else 0
					   end fc
					 , case tis.prev_typins_id
					 	when 2 then 1
					   else 0
					   end fa
					 , vet.prev_etape_id
				from PREV_VET_TYPINS tis
					,PREV_VERSION_ETAPE vet
				where tis.prev_vet_id = vet.id
				group by vet.prev_etape_id) typ_ins on typ_ins.prev_etape_id = etp.id
where elp.id not in (select distinct prev_elp_parent_id from PREV_ELEMENT_PEDAGOGI where prev_elp_parent_id is not null )
  and elp.code is not null
  and elp.prev_elp_reference_id is null
order by z_element_pedagogique_id