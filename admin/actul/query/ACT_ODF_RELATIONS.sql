select
	  etp.annee_id,
      null                                     		as noeud_sup_id,
      null									   		as noeud_sup_exported,
	  null                                     		as structure_sup_id,
      null									   		as comp_sup_id,
      null                                     		as choix_minimum,
      null                                     		as choix_maximum,
      null                                     		as liste_id,
	  null									   		as lse_exported,
	  null									   		as lien_lse_exported ,
      null                                     		as type_choix,
      null                                     		as libelle_liste,
      null                                     		as libelle_court_liste,
      convert(concat(concat(ltrim(rtrim(etp.cod_etp)),'_'),cod_vrs_vet )using utf8)  as noeud_inf_id,
	  etp.tem_exported								as noeud_inf_exported,
      etp.z_structure_id                       		as structure_inf_id,
      etp.cod_cmp							   		as comp_inf_id,
      etp.libelle,
      etp.libelle_court,
      'etape'                                  		as nature,
      null                                     		as cod_pel,
      null                                     		as tem_a_dis_elp,
	  null											as ects
    from (
			select
				anu.cod_anu                              as annee_id,
				etp.libelle_long     					   	   as libelle,
				etp.libelle_court                        as libelle_court,
				null as z_structure_id,
				etp.cod_cmp,
--				concat_ws('_', etp.code, convert(vti.code using utf8)) as source_code,
				etp.code as cod_etp,
				convert(vti.code using utf8) as cod_vrs_vet,
				tpd.cod_nature_diplome as tem_dn,
				vde.statut as statut_actul,
				case etp.exported
					when true then 'O'
					else 'N'
				end tem_exported
				from PREV_PROJET                  			 anu
				join PREV_DIPLOME                    		 dip on dip.prev_projet_id = anu.cod_anu
				join PREV_TYP_DIPLOME                		 tpd on dip.prev_typ_diplome_id = tpd.id
				join PREV_VERSION_DIPLOME            		 vdi on vdi.prev_diplome_id = dip.id
				join PREV_ETAPE                      		 etp on etp.prev_version_diplome_id = vdi.id
				join PREV_VERSION_ETAPE				         vde on vde.prev_etape_id = etp.id
				left join PREV_VET_TYPINS 					 vti on vde.id = vti.prev_vet_id
				left join (select case tis.prev_typins_id
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
				where anu.cod_anu =:v_annee
				and anu.temoin_actif = 1
			order by
				anu.cod_anu,
				etp.libelle_long ,
				etp.libelle_court,
				tpd.typ_dip_apo ,
				etp.code,
				tpd.cod_nature_diplome
	)	etp
    union
	select
	  etp.annee_id,
      convert(etp.source_code using utf8)	   			as noeud_sup_id,
      etp.tem_exported						   			as noeud_sup_exported,
	  etp.z_structure_id                       			as structure_sup_id,
      etp.cod_cmp							   			as comp_sup_id,
      null              					   			as choix_minimum,
      null						    		   			as choix_maximum,
      convert(concat('{}', etp.cod_etp ) using utf8)  	as liste_id,
	  case etp.tem_exported
		when 1 then 'O'
		else 'N'
	  end 												as lse_exported,
	  null									   			as lien_lse_exported ,
	  null                         			   			as type_choix,
      etp.libelle                              			as libelle_liste,
      etp.libelle_court                        			as libelle_court_liste,
      convert(lse.elp_code using utf8)         			as noeud_inf_id,
	  case lse.exported_lse
		when 1 then 'O'
		else 'N'
	  end 	 											as noeud_inf_exported,
      null			                           			as structure_inf_id,
	  lse.cod_cmp							   			as comp_inf_id,
      lse.lib_lse                              			as libelle,
      lse.lic_lse                              			as libelle_court,
      lse.cod_nel                              			as nature,
      lse.periode                              			as cod_pel,
      null                                     			as tem_a_dis_elp,
	  null												as ects
    from (
			select
				anu.cod_anu                              as annee_id,
				etp.libelle_long     					   	   as libelle,
				etp.libelle_court                        as libelle_court,
				tpd.typ_dip_apo  as z_type_formation_id,
				vde.vdi_vet_annee_min as niveau,
				null as z_structure_id,
				etp.cod_cmp,
				concat_ws('_', ltrim(rtrim(etp.code)), convert(vti.code using utf8)) as source_code,
				0 as specifique_echanges ,
				'D999' as domaine_fonctionnel,
				typ_ins.fi as fi,
				typ_ins.fa as fa,
				typ_ins.fc as fc,
				ltrim(rtrim(etp.code)) as cod_etp,
				convert(vti.code using utf8)  as cod_vrs_vet,
				concat_ws('_', anu.cod_anu, ltrim(rtrim(etp.code)), convert(vti.code using utf8)) as id,
				tpd.cod_nature_diplome as tem_dn,
				vde.statut as statut_actul,
				case etp.exported
					when true then 'O'
					else 'N'
				end tem_exported
			from PREV_PROJET                  			 anu
			join PREV_DIPLOME                    		 dip on dip.prev_projet_id = anu.cod_anu
			join PREV_TYP_DIPLOME                		 tpd on dip.prev_typ_diplome_id = tpd.id
			join PREV_VERSION_DIPLOME            		 vdi on vdi.prev_diplome_id = dip.id
			join PREV_ETAPE                      		 etp on etp.prev_version_diplome_id = vdi.id
			join PREV_VERSION_ETAPE                  vde on vde.prev_etape_id = etp.id
			left join PREV_VET_TYPINS 					 vti on vti.prev_vet_id = vde.id
			left join (select case tis.prev_typins_id
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
			where anu.cod_anu =:v_annee
			  and anu.temoin_actif = 1
			order by
				anu.cod_anu,
				etp.libelle_long ,
				etp.libelle_court,
				tpd.typ_dip_apo ,
				etp.code,
				tpd.cod_nature_diplome
	)   etp
    join (
			select ifnull(ltrim(rtrim(elp.code)), ltrim(rtrim(elp_r.code))) as elp_code
				 , case elp.code
					 when true then elp_r.exported
					 else elp.exported
				   end exported_lse
				 , case elp.code
					 when true then elp_r.libelle_court
					 else elp.libelle_court
					 end lic_lse
				 , case elp.code
					 when true then elp_r.libelle_long
					 else elp.libelle_long
				 end lib_lse
				 , case elp.code
					 when true then elp_r.nel
					 else elp.nel
				 end cod_nel
				 , concat_ws('_' ,ltrim(rtrim(etp.code)) ,convert(vti.code using utf8) ) as etape
				 , etp.code cod_lse
				 , etp.cod_cmp
				 , case elp.code
					 when true then elp_r.nb_choix
					 else elp.nb_choix
				 end nb_choix
				 , case tot_choix.prev_elp_parent_id
					 when true then null
					 else
						 case elp.code
						 when true then elp_r.nb_choix
						 else elp.nb_choix
					 end
					 end min_choix
				 , case tot_choix.prev_elp_parent_id
					 when true then null
					 else tot_choix.nb_tot_choix
					 end max_choix
				 , tot_choix.prev_elp_parent_id
				 ,case etp.prem_sem
					when 1 then 'S1'
					when 2 then 'S2'
					when 3 then 'S1'
					when 4 then 'S2'
					when 5 then 'S1'
					when 6 then 'S2'
					else 'S2'
				  end periode
		from
		(
			select elp.id
				, elp.prev_vet_id
				, elp.prev_elp_reference_id
				, elp.exported
			from PREV_ELEMENT_PEDAGOGI elp
			where id  in (select distinct prev_elp_parent_id from PREV_ELEMENT_PEDAGOGI where prev_elp_parent_id is not null)
			and prev_elp_parent_id is null
			union all
			select elp.id
				, elp.prev_vet_id
				, elp.prev_elp_reference_id
				, elp.exported
			from PREV_ELEMENT_PEDAGOGI elp
			where prev_elp_parent_id is null
			and id not in (
			select distinct prev_elp_parent_id
			from PREV_ELEMENT_PEDAGOGI
			where prev_elp_parent_id is not null)
		) kern
		join PREV_ELEMENT_PEDAGOGI elp on elp.id = kern.id
		left join PREV_ELEMENT_PEDAGOGI elp_r  on elp_r.prev_elp_reference_id = kern.id
		left join (
			select count(1) nb_tot_choix, prev_elp_parent_id, prev_vet_id
			from PREV_ELEMENT_PEDAGOGI
			where prev_elp_parent_id is not null
				and prev_elp_parent_id in (select id from PREV_ELEMENT_PEDAGOGI where nb_choix is not null)
			group by prev_elp_parent_id  , prev_vet_id
			order by prev_elp_parent_id
		) tot_choix on tot_choix.prev_elp_parent_id = kern.id  and tot_choix.prev_vet_id = kern.prev_vet_id
		join PREV_VERSION_ETAPE vet on vet.id = kern.prev_vet_id
		join PREV_ETAPE etp on etp.id = vet.prev_etape_id
		join PREV_VET_TYPINS vti on vti.prev_vet_id = vet.id
	)  lse on lse.cod_lse = etp.cod_etp

    union
      select
	  anu.cod_anu annee_id,
      convert(ltrim(rtrim(erl.noeud_sup_id)) using utf8) 	  		  as noeud_sup_id,
	  case erl.noeud_sup_exported
		when 1 then 'O'
		else 'N'
	  end 											  as noeud_sup_exported,
      null 									  		  as structure_sup_id,
      erl.structure_sup_id  				  		  as comp_sup_id,
      erl.choix_minimum,
      erl.choix_maximum,
      convert(concat('{}',ltrim(rtrim(erl.liste_id))) using utf8)  as liste_id,
	  case erl.lse_exported
		when 1 then 'O'
		else 'N'
	  end 											  as lse_exported,
	  case erl.lien_lse_exported
		when 1 then 'O'
		else 'N'
	  end 											  as lien_lse_exported,
      erl.cod_typ_lse 		                 		  as type_choix,
      lse.libelle_long                         		  as libelle_liste,
      lse.libelle_court                        		  as libelle_court_liste,
      convert(ltrim(rtrim(elp.code)) using utf8)	  as noeud_inf_id,
	  case elp.exported
		when 1 then 'O'
		else 'N'
	  end 											  as noeud_inf_exported,
      null			                           		  as structure_inf_id,
      lre.structure_elp                        		  as comp_inf_id,
      elp.libelle_long                         		  as libelle,
      elp.libelle_court                        		  as libelle_court,
      elp.nel                              	   		  as nature,
      lre.periode							   		  as cod_pel,
	  null									   		  as tem_a_dis_elp,
	  elp.ects										  as ects
    from  PREV_PROJET         anu
    cross join (
				select distinct annee_id
					,noeud_sup_id
					,noeud_sup_exported
					,structure_sup_id
					,case nel
						when 'CHOI' then
							case choix_minimum
								when choix_maximum then 'O'
								else 'X'
							end
						else 'O'
					 end 						as cod_typ_lse
					,min_choix choix_minimum
					,max_choix choix_maximum
					,liste_id
					,lse_exported
					,lien_lse_exported
				from (
				select anu.cod_anu annee_id
					,elp.code noeud_sup_id
					,elp.exported as noeud_sup_exported
					,etp.cod_cmp structure_sup_id
					, case elp.nb_choix
						when true then 1
						else 0
						end choix_minimum
					,case ifnull(elp.nb_choix ,0)
							when 0 then 0
							else elp.nb_choix
					end choix_maximum
					, elp.nb_choix
					,elp.nel
					, case tot_choix.prev_elp_parent_id
							when true then null
							else elp.nb_choix
					end min_choix
					, case tot_choix.prev_elp_parent_id
					when true then null
					else tot_choix.nb_tot_choix
					end max_choix
					, tot_choix.prev_elp_parent_id
					,elp.code liste_id
					,elp.lse_exported
					,elp.lien_lse_exported
				from (select * from PREV_ELEMENT_PEDAGOGI where id in (select distinct prev_elp_parent_id from PREV_ELEMENT_PEDAGOGI))  elp
				join PREV_VERSION_ETAPE vet        on vet.id = elp.prev_vet_id
				join PREV_ETAPE etp                on etp.id = vet.prev_etape_id
				join PREV_PROJET                   anu on cod_anu =:v_annee and anu.temoin_actif = 1
				join PREV_DIPLOME                  dip on dip.prev_projet_id = anu.cod_anu
				join PREV_VERSION_DIPLOME          vdi on vdi.prev_diplome_id = dip.id and vdi.id = etp.prev_version_diplome_id
				left join (
							select count(1) nb_tot_choix, prev_elp_parent_id, prev_vet_id
							from PREV_ELEMENT_PEDAGOGI
							where prev_elp_parent_id is not null
								and prev_elp_parent_id in (select id from PREV_ELEMENT_PEDAGOGI where nb_choix is not null)
							group by prev_elp_parent_id  , prev_vet_id
							order by prev_elp_parent_id
							) tot_choix on tot_choix.prev_elp_parent_id = elp.id  and tot_choix.prev_vet_id = elp.prev_vet_id
				where elp.code is not null
				and elp.prev_elp_reference_id is  null
				union
				select
					 anu.cod_anu annee_id
					,elp_comm.code noeud_sup_id
					,elp_comm.exported as noeud_sup_exported
					,etp.cod_cmp	 structure_sup_id
					, case elp_comm.nb_choix
						when true then 1
						else 0
						end choix_minimum
					,case ifnull(elp_comm.nb_choix ,0)
							when 0 then 0
							else elp_comm.nb_choix
					end choix_maximum
					, elp_comm.nb_choix
					, elp_comm.nel
					, case tot_choix.prev_elp_parent_id
							when true then null
							else elp_comm.nb_choix
					end min_choix
					, case tot_choix.prev_elp_parent_id
					when true then null
					else tot_choix.nb_tot_choix
					end max_choix
					, tot_choix.prev_elp_parent_id

					,elp_comm.code liste_id
					,elp_comm.lse_exported
					,elp_comm.lien_lse_exported
				from (select * from PREV_ELEMENT_PEDAGOGI where id in (select distinct prev_elp_parent_id from PREV_ELEMENT_PEDAGOGI))  elp
				join PREV_ELEMENT_PEDAGOGI elp_comm on elp_comm.id = elp.prev_elp_reference_id
				join PREV_VERSION_ETAPE vet        on vet.id = elp.prev_vet_id
				join PREV_ETAPE etp                on etp.id = vet.prev_etape_id
				join PREV_PROJET                   anu on cod_anu = :v_annee and anu.temoin_actif = 1
				join PREV_DIPLOME                  dip on dip.prev_projet_id = anu.cod_anu
				join PREV_VERSION_DIPLOME          vdi on vdi.prev_diplome_id = dip.id and vdi.id = etp.prev_version_diplome_id
				left join (
							select count(1) nb_tot_choix, prev_elp_parent_id, prev_vet_id
							from PREV_ELEMENT_PEDAGOGI
							where prev_elp_parent_id is not null
								and prev_elp_parent_id in (select id from PREV_ELEMENT_PEDAGOGI where nb_choix is not null)
							group by prev_elp_parent_id  , prev_vet_id
							order by prev_elp_parent_id
							) tot_choix on tot_choix.prev_elp_parent_id = elp.id  and tot_choix.prev_vet_id = elp.prev_vet_id
				where elp.code is null
				and elp.prev_elp_reference_id is not null
				) elp_regroupe_lse
			)	erl

    join (
			select *
			from PREV_ELEMENT_PEDAGOGI
			where id in (select distinct prev_elp_parent_id from PREV_ELEMENT_PEDAGOGI) and code is not null
		  )lse on lse.code = erl.liste_id

    join (

			select distinct annee_id
				,noeud_sup_id as cod_elp
				,structure_sup_id as structure_elp
				,liste_id
				,periode
			from (
				select anu.cod_anu annee_id
					,elp.code noeud_sup_id
					,etp.cod_cmp structure_sup_id
					,case etp.prem_sem
						when 1 then 'S1'
						when 2 then 'S2'
						when 3 then 'S1'
						when 4 then 'S2'
						when 5 then 'S1'
						when 6 then 'S2'
						else 'AN'
					end periode
					, case elp.nb_choix
						when true then 1
						else 0
						end choix_minimum
					,case ifnull(elp.nb_choix ,0)
							when 0 then 0
							else elp.nb_choix
					end choix_maximum
					, elp.nb_choix

								, case tot_choix.prev_elp_parent_id
									when true then null
									else
										elp_par.nb_choix
									end min_choix
								, case tot_choix.prev_elp_parent_id
									when true then null
									else tot_choix.nb_tot_choix
									end max_choix
								, tot_choix.prev_elp_parent_id

					,elp_par.code liste_id
					,elp_par.lse_exported
					,elp_par.lien_lse_exported
				from PREV_ELEMENT_PEDAGOGI elp
				join PREV_ELEMENT_PEDAGOGI elp_par on elp_par.id = elp.prev_elp_parent_id
				join PREV_VERSION_ETAPE vet        on vet.id = elp.prev_vet_id
				join PREV_PROJET                   anu on cod_anu = :v_annee and anu.temoin_actif = 1
				join PREV_DIPLOME                  dip on dip.prev_projet_id = anu.cod_anu
				join PREV_VERSION_DIPLOME          vdi on vdi.prev_diplome_id = dip.id
				join PREV_ETAPE etp                on etp.id = vet.prev_etape_id and etp.prev_version_diplome_id = vdi.id
				left join (
							select count(1) nb_tot_choix, prev_elp_parent_id, prev_vet_id
							from PREV_ELEMENT_PEDAGOGI
							where prev_elp_parent_id is not null
								and prev_elp_parent_id in (select id from PREV_ELEMENT_PEDAGOGI where nb_choix is not null)
							group by prev_elp_parent_id  , prev_vet_id
							order by prev_elp_parent_id
							) tot_choix on tot_choix.prev_elp_parent_id = elp_par.id  and tot_choix.prev_vet_id = elp_par.prev_vet_id
				where elp.code is not null
				and elp.prev_elp_parent_id is not null
				and elp.prev_elp_reference_id is  null
				union
				select anu.cod_anu annee_id
					,elp_ref.code noeud_sup_id
					,etp.cod_cmp structure_sup_id
					,case etp.prem_sem
						when 1 then 'S1'
						when 2 then 'S2'
						when 3 then 'S1'
						when 4 then 'S2'
						when 5 then 'S1'
						when 6 then 'S2'
						else 'AN'
					end periode
					, case elp_ref.nb_choix
						when true then 1
						else 0
						end choix_minimum
					, elp_ref.nb_choix
					,case ifnull(elp_ref.nb_choix ,0)
							when  0 then 0
							else  elp_ref.nb_choix
					end choix_maximum
								, case tot_choix.prev_elp_parent_id
									when true then null
									else
										elp_par.nb_choix
									end min_choix
								, case tot_choix.prev_elp_parent_id
									when true then null
									else tot_choix.nb_tot_choix
									end max_choix
								, tot_choix.prev_elp_parent_id
					,elp_par.code liste_id
					,elp_par.lse_exported
					,elp_par.lien_lse_exported
				from PREV_ELEMENT_PEDAGOGI elp
				join PREV_ELEMENT_PEDAGOGI elp_ref on elp_ref.id = elp.prev_elp_reference_id
				join PREV_ELEMENT_PEDAGOGI elp_par on elp_par.id = elp.prev_elp_parent_id
				join PREV_VERSION_ETAPE vet        on vet.id = elp.prev_vet_id
				join PREV_PROJET                   anu on cod_anu =:v_annee and anu.temoin_actif = 1
				join PREV_DIPLOME                    		 dip on dip.prev_projet_id = anu.cod_anu
				join PREV_VERSION_DIPLOME            		 vdi on vdi.prev_diplome_id = dip.id
				join PREV_ETAPE etp                on etp.id = vet.prev_etape_id and etp.prev_version_diplome_id = vdi.id
				left join (
							select count(1) nb_tot_choix, prev_elp_parent_id, prev_vet_id
							from PREV_ELEMENT_PEDAGOGI
							where prev_elp_parent_id is not null
								and prev_elp_parent_id in (select id from PREV_ELEMENT_PEDAGOGI where nb_choix is not null)
							group by prev_elp_parent_id  , prev_vet_id
							order by prev_elp_parent_id
							) tot_choix on tot_choix.prev_elp_parent_id = elp_par.id  and tot_choix.prev_vet_id = elp_par.prev_vet_id
				where elp.code is null
				and elp.prev_elp_parent_id is not null
				and elp.prev_elp_reference_id is not null
			) lse_regroupe_elp

	)lre on lre.liste_id = lse.code and lre.annee_id = anu.cod_anu
    join PREV_ELEMENT_PEDAGOGI    elp on elp.code = lre.cod_elp
	where anu.cod_anu = :v_annee
	  and anu.temoin_actif = 1