CREATE OR REPLACE PROCEDURE OSE.UM_SELECT_INTERVENANT (p_annee_id number, p_d_deb_annee_univ date, p_d_fin_annee_univ date, p_type_transfert varchar2, p_date_profondeur date, p_date_systeme date) IS
/* ====================================================================================================
  # Detail du connecteur PARTIE B/ SIHAM_INTERV : SYNCHRO DES INTERVENANTS - Avec user OSE
   
  PROCEDURE OSE.UM_SELECT_INTERVENANT
  
  Cette procédure permet de sélectionner le ou les dossiers à synchroniser avec OSE et de les insérer dans OSE.UM_TRANSFERT_INDIVIDU pour suivi des traces de synchro
  
  Appelée par : lance_synchro_Siham_Ose.sql
  Paramètres : cf. explications script appelant ci-dessus
  
  Sortie : 	Remplissage TABLE OSE.UM_TRANSFERT_INDIVIDU avec la population ciblée, flag témoins traitements d'update ou insert à "TODO" 
			+ rapatrie également les infos complementaires qui ne sont pas dans siham pour les vacataires (infos OREC appli locale Montpellier)

  -- v2.2  03/04/20 MYP : v_orec rectif nb_h_service_rectorat enlever replace . par , car fait planter le to_number, ca marche avec un .
  -- v2.3  27/04/20 MYP : si contractuel date prolongation après age limite saisie dans carriere complement retraire
  -- v2.3b 05/05/20 MYP : pb date prolongation complement retraire : date en zone texte saisie libre
  -- v2.4  22/09/20 MYP : pb pour recup lib_cat orec car croise avec aff future
  -- v2.4b 19/11/20 MYP : adaptation prép V15 : v_annee_id remplacé par param p_annee_id
  -- v2.5  23/02/20 MYP : test intitule_categorie orec 
  -- v3.0  04/12/20 MYP : adaptations pour OSE V15
  -- v3.1  11/10/21 MYP : ajout EMP_SOURCE_CODE provenant d'orec : pour rafp
  -- v3.1b 04/02/22 MYP : dblink .world
  -- v3.2  25/03/22 MYP : ajout col sexe dans UM_TRANSFERT_INDIVIDU
  -- v3.3  18/07/22 MYP : correction test format date
=====================================================================================================*/

			   
-- VARIABLES DE TRAITEMENT ----------------------------
v_nb_dossier_traites		number(12)		:= 0;
v_service_rectorat			varchar2(255)	:= '';		-- v2.2
v_nb_h_service_rectorat		number(8,2)		:= 0;		-- v2.2
v_prefixe_matricule			varchar(20)		:= '';		-- v3.0 04/12/2020
v_uo_a_exclure				varchar2(100)	:= ''; 


/*=============================================================================================================*/
/*================== 			curseur MANUEL (forçage dossiers spéciaux)     =============================== */
/*=============================================================================================================*/
cursor cur_dossier_manu is
		-- v2   - 22/03/2019 - MYP - Le ou les matricules forcés seulement
		select distinct nudoss, matcle as matricule 
		from OSE.UM_TRANSFERT_FORCE
		where D_VERIF_MANUELLE is null
;
/*=============================================================================================================*/
/*================== 			curseur DIFFERENTIEL (journalier)		   		===============================*/
/*=============================================================================================================*/
cursor cur_dossier_diff is
	-- SELECTION MATRICULES maj dernierement dans SIHAM : dans ZYTD12 depuis la date de profondeur
	select distinct V_doss.nudoss, V_doss.matcle as matricule
	from (
		select distinct td12.nudoss, i.matcle
			--to_char(td12.timjif,'YYYY-MM-DD') as date_maj, td12.cdinfo, count(distinct td12.nudoss)
			from hr.zytd12@SIHAM.WORLD    td12
				, hr.zy00@SIHAM.WORLD i
			--  Maj pour les types de modifs non techniques comme pour les TM tables Miroirs de Siham + 'ES' Entrées sorties
			-- '0F' Adresses, '0H' tel mail, '0I' bq, '18' sit fam, '3B' '3C' affectations, 'CO' contrat, 'GR' 'GS' carriere, 'PO' positions, 'V1' fonctions
			-- ##A_PERSONNALISER_CHOIX_SIHAM##
			where td12.CDINFO IN ('00','0H','0F','FF','FX','U5','10','05','07','06','18','CB','0I','FA','GS','PO','FL','GR','VS','CO','TL','3C','3B','DI','TK','ES','V1')
				and trunc(td12.TIMJIF) >= trunc(p_date_profondeur)
				and trunc(td12.TIMJIF) <= greatest(p_date_systeme, trunc(sysdate))      --v1.3b p_date_systeme --v2.1 
				and td12.nudoss = i.nudoss
				and i.matcle like v_prefixe_matricule||'%'  -- v3.0 04/12/2020
		union
			-- v0.3 - 28/05/2018 - MYP - MATRICULE vacataires ok dans OREC
			select distinct i.nudoss, i.matcle as matricule
			from
			(select distinct matricule
					from OSE.UM_OREC_INFO
					where annee_id = p_annee_id  -- v2.1
					--and service_rectorat  like '%.%'
			) v_new_orec
			,hr.zy00@SIHAM.WORLD i
			where v_new_orec.matricule = i.matcle
		union
			-- v0.4 - 07/06/2018 - MYP - Matricules forcés manuellement
			select distinct nudoss, matcle as matricule 
			from OSE.UM_TRANSFERT_FORCE
			where D_VERIF_MANUELLE is null
	) V_doss
	------------!!!!!!!!!!!!!!!!!!! EN DUR le 31/01/2020 car pb zone service_rectorat  -- reactive le 02/06/2020
	--where V_doss.matcle not in ('UDM000123760','UDM000015867')
	order by V_doss.matcle
;
	
/*=============================================================================================================*/
/*================== 			curseur ACTIFS (à la date du jour)			    ===============================*/
-- v2.1 20/09/2019 : croiser avec aff HIE pour divider par deux le nb de dossiers
/*=============================================================================================================*/
cursor cur_dossier_actif is
	select DISTINCT 
	i.nudoss, i.matcle as matricule, substr(trim(i_adm.lognid),1,12) 	as uid_ldap 
   ,i.qualit, i.nomuse, i.prenom, i.nompat
   ,v_naiss.datnai ,v_naiss.sexe													-- v3.2																
   ,floor(floor(months_between(p_date_systeme, v_naiss.datnai))/12) 	as nb_an	-- v3.2
   ,mod(floor(months_between(p_date_systeme, v_naiss.datnai)),12) 		as nb_mois	-- v3.2
   ,nvl( situ_strat.dtef1s-1,  v_pos.date_maintien_activ_pos) 			as date_maintien_activ
   from hr.zy00@SIHAM.WORLD i            	-- dossier agent
		,hr.zy4i@SIHAM.WORLD i_adm     	-- HRA id user
		,(	---- v_naiss -----------------------------------------------------------------
        select naiss.nudoss
            , naiss.datnai
            , substr(lib_reg.libabr,1,1) as sexe
            from hr.zy10@SIHAM.WORLD naiss 
            ,hr.zd00@SIHAM.WORLD reg          -- reglementation
            , hr.zd01@SIHAM.WORLD lib_reg
        where     -- statut actif
            --naiss.nudoss = p_nudoss
            -- reglementation UGP
            naiss.sexemp = reg.cdcode
            and reg.cdstco='UGP'
            and reg.nudoss = lib_reg.nudoss
            and lib_reg.cdlang = 'F' 
		) v_naiss  -- vue infos naissance  -- v3.2
		,hr.zy1S@SIHAM.WORLD situ_strat 	-- carriere situation strategique
		-- ##A_PERSONNALISER_CHOIX_SIHAM##
		,(  -- v1.4b position admin
			select nudoss, datxxx-1 as date_maintien_activ_pos
			from hr.zyPO@SIHAM.WORLD pos 		
			where p_date_systeme between pos.dateff and pos.datxxx-1
			and pos.posits = 'AC'    -- en activite
			and pos.RSPRO like 'PA%' -- Prolongation activite
		) v_pos
		,(
			select aff_hie.nudoss, aff_hie.idou00 as uo, trunc(aff_hie.dtef00) as date_deb, trunc(aff_hie.dten00) as date_fin, trim(aff_hie.idjb00) as code_emploi
				,case   when trunc(aff_hie.dtef00) > p_d_fin_annee_univ then 'INACTIF'   -- pas encore là car saisie à l'avance
						when trunc(aff_hie.dten00) < p_d_deb_annee_univ then 'INACTIF'   -- période passée
				  else 'ACTIF'
				end as ETAT
				,trim(aff_hie.idps00) as code_poste                                    
			from hr.zy3b@SIHAM.WORLD aff_hie         -- affectation HIE
			where 
			-- ##A_PERSONNALISER_CHOIX_SIHAM##
			trim(aff_hie.idou00) not in (v_uo_a_exclure) 	-- v3.0 04/12/2020 voir remplissage variable
			and ( -- affectations sur annee univ
				  ( trunc(aff_hie.dtef00) <= p_d_fin_annee_univ
					and trunc(aff_hie.dtef00) <= p_date_systeme
					and trunc(aff_hie.dtef00) <= add_months(p_d_fin_annee_univ,12)
					and trunc(aff_hie.dten00) >= p_d_deb_annee_univ
				  ) 
				  -- ##A_PERSONNALISER_CHOIX_SIHAM## : ou affectation future si aucune sur annee univ (demandé par notre DRH pour planning PROSE)
				  or ( --aff_hie.dtef00 >=  p_d_fin_annee_univ and
						trunc(aff_hie.dtef00) > p_date_systeme
						and not exists (
								select 1
								from hr.zy3b@SIHAM.WORLD -- affectation HIE
								where
								-- ##A_PERSONNALISER_CHOIX_SIHAM##
								trim(idou00) not in (v_uo_a_exclure) -- v3.0 04/12/2020 voir remplissage variable
								and ( trunc(dtef00) <= p_d_fin_annee_univ
										and trunc(dtef00) <= p_date_systeme
										and trunc(dten00) >= p_d_deb_annee_univ
									   )
												
						)
					)
				)
		) aff_UM  -- v2.1
	-- ##A_PERSONNALISER_CHOIX_SIHAM##
	where i.matcle like v_prefixe_matricule||'%'
	and i.nudoss = i_adm.nudoss(+)
	and i.nudoss = v_naiss.nudoss(+)   -- v3.2
	and i.nudoss = situ_strat.nudoss(+)
	-- ##A_PERSONNALISER_CHOIX_SIHAM##
	and situ_strat.cgstat(+) = 'MC100'   -- Maintien activite
	and i.nudoss = v_pos.nudoss(+) 
	and i.nudoss = aff_UM.nudoss
	--and i.matcle = 'UDM000209170'     -- test dup val on index
;

/*=============================================================================================================*/
/*================== 			curseur INFOS DOSSIER 					   		===============================*/
/*=============================================================================================================*/
cursor cur_info (p_nudoss NUMBER, p_siham_matricule VARCHAR2) is 
--, p_d_deb_annee_univ date, p_d_fin_annee_univ date) is
select 
	distinct	
	case when v_orec.matricule is not null then 'POP_OREC' else
		-- ##A_PERSONNALISER_CHOIX_SIHAM## -- v3.0 simplificiation : seulement orec/statut_pip de siham
		'ACTIF_'||v_statut.statut_pip
	end as type_transfert	-- flag type de pop juste pour suivi traces dans OSE.UM_TRANSFERT_INDIVIDU
	,i.nudoss								as nudoss
	,trim(i.matcle)							as matcle  				-- SIHAM_MATRICULE
	,i.uid_ldap								as uid_ldap				
	,i.qualit					 			as qualit
	,trim(i.nomuse)							as nomuse
	,trim(i.prenom)							as prenom
    ,trim(i.nompat)							as nompat
	,v_orec.recrutement						as recrutement			-- Gracieux/Rémunéré
	,v_orec.type_emp						as type_emp				-- PUBLIC/PRIVE
	,v_orec.tem_fonc						as tem_fonctionnaire	-- OUI/NON
	,v_orec.employeur						as employeur			-- NOM EMPLOYEUR 
	,v_orec.ville_service_rectorat			as ville_service_rectorat	-- Ville si employeur rectorat
	,v_orec.nb_h_service_rectorat			as nb_h_service_rectorat	-- nombre d heure si employeur rectorat
	,v_orec.code_uo							as orec_code_uo				-- UO composante depuis OREC (car dans SIHAM les vac sont tous sur UO VACATIONS)
	-- ##A_PERSONNALISER_CHOIX_SIHAM##
	,case when v_aff.code_emploi = 'UDOCXXX_01' and v_statut.statut_pip in ( 'C0102','C0322') then 'DOC_UM'	-- Doctorants Université
		  when v_aff.code_emploi = 'UDOCXXX_01' and v_statut.statut_pip like 'HB%' then 'DOC_EXT'			-- Doctorants Externes
			else substr(v_orec.orec_lib_categorie,1,32)														-- sinon categorie de population OREC
	end as orec_lib_categorie
	,nvl(v_depart_def.date_deb,i.date_limite_activite)	as date_depart_def		-- date de depart definitif
	,case when v_depart_def.date_deb is not null then v_depart_def.lib_depart	-- cause motif depart definitif
		  when v_depart_def.date_deb is null and i.date_limite_activite is not null then 'LIMITE ACTIVITE' 
		  else null
	end as cause_depart_def
	,v_groupe_hie.groupe_hierarchique							-- correspond a la population Harpege IA/AA/SA...
	,v_orec.type_vac 						as orec_type_vac 	-- v1.5  ATV/CEV
	,v_orec.emp_source_code 									-- v3.1  11/10/2021
	,i.sexe														-- v3.2 30/05/22
from
    ( 	--- dossier agent
		select
		v_ind.nudoss
		,v_ind.matcle
		,v_ind.uid_ldap
		,v_ind.qualit
		,v_ind.nomuse
		,v_ind.prenom
		,v_ind.nompat
		,v_ind.datnai
		,v_ind.nb_an
		,v_ind.nb_mois
		,v_ind.sexe		-- v3.2
		-- ##A_PERSONNALISER_CHOIX_SIHAM## si maintien activite renseigné alors cette date sinon calcul suivant regle DRH -- ex UDM%100940
        ,nvl(v_ind.date_maintien_activ,
             case  when to_char(v_ind.datnai,'YYYYMM') < '195107' and v_ind.nb_an>=65 then trunc(add_months(v_ind.datnai,65*12)) 
                    when to_char(v_ind.datnai,'YYYY') = '1951' and (v_ind.nb_an>65 or (v_ind.nb_an=65 and v_ind.nb_mois >=4)) then trunc(add_months(v_ind.datnai,65*12+4))   
                    when to_char(v_ind.datnai,'YYYY') = '1952' and (v_ind.nb_an>65 or (v_ind.nb_an=65 and v_ind.nb_mois >=9)) then trunc(add_months(v_ind.datnai,65*12+9))
                    when to_char(v_ind.datnai,'YYYY') = '1953' and (v_ind.nb_an>66 or (v_ind.nb_an=66 and v_ind.nb_mois >=2)) then trunc(add_months(v_ind.datnai,66*12+2))
                    when to_char(v_ind.datnai,'YYYY') = '1954' and (v_ind.nb_an>66 or (v_ind.nb_an=66 and v_ind.nb_mois >=7)) then trunc(add_months(v_ind.datnai,66*12+7))
                    when to_char(v_ind.datnai,'YYYY') = '1955' and (v_ind.nb_an>=67) then trunc(add_months(v_ind.datnai,67*12))
                    else null 
              end) as date_limite_activite
		from
		(  select i.nudoss, i.matcle, substr(trim(i_adm.lognid),1,12) as uid_ldap 
           ,i.qualit, i.nomuse, i.prenom, i.nompat
           ,v_naiss.datnai ,v_naiss.sexe  --v3.2
           ,floor(floor(months_between(p_date_systeme, v_naiss.datnai))/12) as nb_an			-- v3.2
           ,mod(floor(months_between(p_date_systeme, v_naiss.datnai)),12) as nb_mois			-- v3.2
		   -- date maintien de situ stratégique sinon date fin position admin type prolong = PA%
           ,nvl(nvl( trim(situ_strat.dtef1s-1),  v_pos.date_maintien_activ_pos),v_compl_carr.date_suite_prolong) as date_maintien_activ
           from hr.zy00@SIHAM.WORLD i            	-- dossier agent
                ,hr.zy4i@SIHAM.WORLD i_adm     	-- HRA id user
                ,(	---- v_naiss -----------------------------------------------------------------
					select naiss.nudoss
						, naiss.datnai
						, trim(substr(lib_reg.libabr,1,1)) as sexe
						from hr.zy10@SIHAM.WORLD naiss 
						,hr.zd00@SIHAM.WORLD reg          -- reglementation
						, hr.zd01@SIHAM.WORLD lib_reg
					where     -- statut actif
						naiss.nudoss = p_nudoss
						-- reglementation UGP
						and naiss.sexemp = reg.cdcode
						and reg.cdstco='UGP'
						and reg.nudoss = lib_reg.nudoss
						and lib_reg.cdlang = 'F' 
				) v_naiss  -- naissance  -- v3.2
                ,hr.zy1S@SIHAM.WORLD situ_strat 	-- carriere situation strategique
				,(  -- v1.4b position admin
					select nudoss, trim(datxxx-1) as date_maintien_activ_pos
                    from hr.zyPO@SIHAM.WORLD pos 		
                    where pos.nudoss = p_nudoss
					and p_date_systeme between pos.dateff and pos.datxxx-1
                    and pos.posits = 'AC'    -- en activite
                    and pos.RSPRO like 'PA%' -- Prolongation activite
                ) v_pos
				,( -- v2.3 si contractuel date prolongation saisie dans carriere complement retraire
				   -- v2.3b pb date en zone texte saisie libre
				   -- v3.3  dans zy19 la zone est maintenant au format date donc to_date pour que ca marche !
					select nudoss, decode(trim(dtrtpr),to_date('01/01/01','DD/MM/YY'),null,trim(dtrtpr)) as date_suite_prolong   -- v3.3  18/07/22
					from zy19@SIHAM.WORLD
					where nudoss = p_nudoss
				) v_compl_carr
			where i.nudoss = p_nudoss
			-- ##A_PERSONNALISER_CHOIX_SIHAM## tous nos agents sont codes UDM<matricule harpege avec zero devant> et les enfants REL<matricule>...
			and i.matcle like 'UDM%'
            and i.nudoss = i_adm.nudoss(+)
            and i.nudoss = v_naiss.nudoss(+)		-- v3.2
            and i.nudoss = situ_strat.nudoss(+)
			-- ##A_PERSONNALISER_CHOIX_SIHAM##
            and situ_strat.cgstat(+) = 'MC100'   -- Maintien activite sup age limite pour les TITU
			and i.nudoss = v_pos.nudoss(+)  
			and i.nudoss = v_compl_carr.nudoss(+) -- Maintien activite sup age limite pour les CTR
			--and matcle = 'UDM000105874' -- FABRE LAURENT
        ) v_ind
		order by v_ind.matcle
	) i
	,(	---- v_orec : specif vacataires Montpellier : infos compl dans OREC pas dans Siham  ---------------------------------
		select 
		matricule
		,recrutement
		,type_emp
		,tem_fonc
		,employeur
		-- v0.8 - 17/09/2018 - MYP - pour les MAD/PFA mettre employeur#ville_service_rectorat dans la zone ville_service_rectorat
		-- -- ##A_PERSONNALISER_CHOIX_OSE##
		,case when (employeur like 'MAD%' or employeur like 'PFA%') then employeur||'#'||ville_service_rectorat
			else ville_service_rectorat
		end as ville_service_rectorat
		,nb_h_service_rectorat
		,code_uo
		,orec_lib_categorie
		,intitule_corps
		,libelle
		,tem_remunere
		,type_vac			-- v1.5
		,emp_source_code 	-- v3.1  11/10/2021
		from 
		(	select matricule
				,trim(recrutement) recrutement
				,trim(type_emp) type_emp
				,tem_fonc
				,case 
					-- ##A_PERSONNALISER_CHOIX_OSE##
					when libelle = 'MAD-PFA FDE' and intitule_categorie like 'MAD Rectorat 1er%' then 'MAD RECTORAT' 
					when libelle = 'MAD-PFA FDE' and intitule_categorie like 'MAD Rectorat 2nd%' then 'MAD RECTORAT' 
					when libelle = 'MAD-PFA FDE' and intitule_categorie = 'Professeur Formateur Académique' then 'PFA RECTORAT'         
					else upper(trim(employeur))
				end as employeur
				--,service_rectorat
				,case 
					-- ##A_PERSONNALISER_CHOIX_OSE##
					when substr(service_rectorat,1,1) = 'C' then 'CARCASSONNE'
					when substr(service_rectorat,1,1) = 'P' then 'PERPIGNAN'
					when substr(service_rectorat,1,1) = 'E' then 'MENDE'
					when substr(service_rectorat,1,1) = 'N' then 'NIMES'
					when substr(service_rectorat,1,1) = 'M' then 'MONTPELLIER'
					else ''
				 end as ville_service_rectorat
					-- v2.2 - 06/04/2020 formatage déplacé dans proc principale
				 ,substr(trim(service_rectorat),2,length(trim(service_rectorat))-1) as nb_h_service_rectorat
				,code_uo
				,case
					-- ##A_PERSONNALISER_CHOIX_OSE##
					when (intitule_categorie like 'Etudiants%' or intitule_categorie like 'Étudiants%')				then 'ETU'		-- v2.5 - 23/02/2020
					when intitule_categorie like 'Retraités%' 														then 'RET'
					when intitule_categorie like 'Agents titulaires%Fonction Publique%autres établissements%' 		then 'FP_TIT_EXT'
					when intitule_categorie like 'Agents non titulaires%Fonction Publique%autres établissements%' 	then 'FP_CTR_EXT'
					when intitule_categorie like 'Dirigeant%' 														then 'PDG'
					--v1.5b  V2 mapping categories orec
					when intitule_categorie like 'Doctorants contractuels%autres établissements%' 					then 'DOC_EXT'
					when intitule_categorie like 'Agents titulaires%BIATS%Université de Montpellier%' 				then 'BIATS_TIT_UM'
					when intitule_categorie like 'Agents non titulaires%BIATS%Université de Montpellier%' 			then 'BIATS_CTR_UM'
					when intitule_categorie like 'Enseignants%enseignement privé%' 									then 'ENS_PRIVE'
					when intitule_categorie like 'Intermittents%' 													then 'SPECT'
					when intitule_categorie like 'Salariés%privé%' 													then 'EMP_PRIVE'
					when intitule_categorie like 'Travailleurs non salariés%' 										then 'AUTO'
					-- v1.5b V2 mapping categories orec
					when intitule_categorie like 'Convention%' 														then 'CONV'
					else ''
				end as orec_lib_categorie
				,intitule_corps          -- Vacataire d'enseignement rémunéré/Autre intervenant
				,libelle                 -- MAD-PFA FDE
				,intitule_categorie      -- MAD Rectorat 1er Degré/MAD Rectorat 2nd degré/Professeur Formateur Académique
				,case 	-- ##A_PERSONNALISER_CHOIX_OSE## -- Remunéré/Gracieux 
					when instr(intitule_corps,'rémunéré') >= 1 			then 'R'         
					when instr(intitule_corps,'titre gracieux') >= 1 	then 'G'  
					else ''
				end tem_remunere
				,type_vac			-- v1.5
				,emp_source_code  	-- v3.1  11/10/2021
			from OSE.UM_OREC_INFO
			where matricule = p_siham_matricule
			and annee_id = p_annee_id 	-- v2.1
		) v_detail_orec
	) v_orec
	,(	-- v_aff : affectation active dans limite de sysdate sur annee univ ou N+1 -------------------------------------
		select distinct 
		v_aff_princ.nudoss
		-- uo principale pour OSE du niveau choisi
		,OSE.UM_AFFICH_UO_SUP(v_aff_princ.idou00) as uo_affect_princ
		,v_aff_princ.dtef00 	as deb_affect
		,v_aff_princ.dten00 	as fin_affect
		,v_aff_princ.idjb00 	as code_emploi
		from 
		( select nudoss
				,idou00	 -- uo_affect_hie detaillée
				,dtef00	 -- date_deb
				,dten00  -- date_fin
				,idjb00	 -- code_emploi
				,row_number() over (partition by nudoss order by dtef00 desc) as rnum	-- la plus récente en premier
				from hr.zy3b@SIHAM.WORLD 	-- affectation HIE
				where nudoss = p_nudoss
					-- ##A_PERSONNALISER_CHOIX_SIHAM##
					and trim(idou00) not in (v_uo_a_exclure) 	-- v3.0 04/12/2020 voir remplissage variable
					-- v0.1 - 05/04/2018 -- v2.4 rajout not exists sinon je recup toujours la future année
					and ( -- affectations sur annee univ
						(trunc(dtef00) <= p_d_fin_annee_univ
							and trunc(dtef00) <= p_date_systeme
							and trunc(dten00) >= p_d_deb_annee_univ
						)
						-- ##A_PERSONNALISER_CHOIX_SIHAM## : ou affectation future si aucune sur annee univ (demandé par notre DRH pour planning PROSE)
					    or ( trunc(dtef00) > p_date_systeme
							and not exists (
									select 1
									from hr.zy3b@SIHAM.WORLD -- affectation HIE
									where nudoss = p_nudoss
									-- ##A_PERSONNALISER_CHOIX_SIHAM##
									and trim(idou00) not in (v_uo_a_exclure) 	-- v3.2 17/06/22 voir remplissage variable
									and ( trunc(dtef00) <= p_d_fin_annee_univ
											and trunc(dtef00) <= p_date_systeme
											and trunc(dten00) >= p_d_deb_annee_univ
										   )
													
							)
						  )
						)	
		) v_aff_princ
		where v_aff_princ.rnum = 1
	) v_aff
	,(	---- v_statut -----------------------------------------------------------------
		select st.nudoss
            ,trim(st.statut) statut_pip
			,trunc(st.dateff) as date_effet
            ,row_number() over (partition by st.nudoss order by st.dateff desc) as rnum 
            from hr.zyfl@SIHAM.WORLD st		-- statut pip
            ,hr.zd00@SIHAM.WORLD reg      	-- reglementation
            ,hr.zdvp@SIHAM.WORLD ens        	-- recup temoin enseig
        where 	-- statut actif
			st.nudoss = p_nudoss
			-- v2.4 rajout not exists sinon je recup toujours la future année
			and (  -- statut sur annee univ
				 (trunc(st.dateff) <= p_d_fin_annee_univ
				  and trunc(st.dateff) <= p_date_systeme
				  and trunc(st.datxxx-1) >= p_d_deb_annee_univ
				 )
				or ( -- ##A_PERSONNALISER_CHOIX_SIHAM## : ou futur si aucun sur annee univ (demandé par notre DRH pour planning PROSE)
					trunc(st.dateff) > p_date_systeme
					and not exists (
							select 1
							from hr.zyfl@SIHAM.WORLD -- statut pip
							where nudoss = p_nudoss
							 and trunc(dateff) <= p_d_fin_annee_univ
							 and trunc(dateff) <= p_date_systeme
							 and trunc(datxxx-1) >= p_d_deb_annee_univ					
							)
				   )
				)	
			-- reglementation HJ8
			and st.statut = reg.cdcode
			and reg.cdstco='HJ8'
			and reg.nudoss = ens.nudoss(+)
			and trim(st.statut) <> '00000'
	) v_statut
	,(	---- v_corps_grade -----------------------------------------------------------------
		SELECT distinct
		v_gr.nudoss
		,v_gr.corps
		,v_gr.adecod as grade
        ,dateff
        ,datfin
        ,row_number() over (partition by v_gr.nudoss order by v_gr.dateff desc, v_gr.corps desc, v_gr.datfin) as rnum 	
		from
		(	select nudoss
            ,trim(corps)    as corps
            ,trim(adecod)   as adecod
			,dateff
			,datfin
			from hr.zygr@SIHAM.WORLD 		--carriere administrative
			where nudoss = p_nudoss
				and numcar = 1
                -- carriere normale (pas secondaire) 
                and adecod <> '0000'
                -- toutes les périodes sur l'année univ jusqu'à date syst
                and (( trunc(dateff) <= p_d_fin_annee_univ
                        and trunc(dateff) <= p_date_systeme
                        and trunc(datfin) >= p_d_deb_annee_univ
                     )
                    or ( -- ##A_PERSONNALISER_CHOIX_SIHAM## : ou futur si aucun sur annee univ (demandé par notre DRH pour planning PROSE)
                        trunc(dateff) > p_date_systeme
                        and not exists (
                                select 1
                                from hr.zygr@SIHAM.WORLD -- statut pip
                                where nudoss = p_nudoss
                                 and numcar = 1
                                    -- carriere normale (pas secondaire) 
                                 and adecod <> '0000'
                                 and trunc(dateff) <= p_d_fin_annee_univ
                                 and trunc(dateff) <= p_date_systeme
                                 and trunc(datfin) >= p_d_deb_annee_univ                    
                                )
                       )
                    )  
			UNION
			select nudoss
			,decode(trim(adecod),null,'000',corps)
			,decode(trim(adecod),null,'0000',adecod)
			,dateff
			,decode(to_char(datxxx,'YYYY-MM-DD'),'2999-12-31',datxxx, datxxx-1)
			from hr.zyfa@SIHAM.WORLD 	 	-- administration origine
			where nudoss = p_nudoss
				and rtrim(orgori,' ') is null
				-- toutes les périodes sur l'année univ
				and decode(to_char(finpre,'YYYY-MM-DD'),'0001-01-01','2999-12-31', to_char(finpre,'YYYY-MM-DD')) >= to_char(p_d_deb_annee_univ,'YYYY-MM-DD')
				and dateff <= p_d_fin_annee_univ
                and dateff <= p_date_systeme
				and adecod <> '0000'
		) v_gr
		where datfin <> dateff  -- lignes annulées remplacées
	) v_corps_grade
	,(	---- v_groupe_hie -----------------------------------------------------------------
		-- groupe hierarchique correspondant au grade siham
		select rtrim(g.cdcode,' ') cdcode
		,h.liblon
		,trim(i.cdhiec) as groupe_hierarchique
		from hr.zd00@SIHAM.WORLD g,
			hr.zd01@SIHAM.WORLD h,
			hr.zd63@SIHAM.WORLD i
		where g.nudoss = p_nudoss
		and g.nudoss = h.nudoss
		and g.nudoss = i.nudoss
		and g.cdstco = 'HJB'
    ) v_groupe_hie
	,(   --- departs definitifs avant date systeme ----------------------------------------
		select v_ES.nudoss
			,trunc(v_ES.date_effet) as date_deb
			,v_ES.code_depart
			,v_ES.lib_depart
			,row_number() over (partition by v_ES.nudoss order by trunc(v_ES.date_effet) desc) as rnum
		from
			( ----- DEPART ENTREES SORTIES
			  select f.nudoss
				,f.dtef1s as date_effet
				,trim(f.cgstat) as code_depart
				-- ##A_PERSONNALISER_CHOIX_SIHAM##  -- ##A_PERSONNALISER_CHOIX_OSE##
				,case when trim(f.cgstat) in ('MC100','MC110') 	then 'RETRAITE'
				  when trim(f.cgstat) = 'MC120' 				then 'DEMISSION'
				  when trim(f.cgstat) = 'MC130' 				then 'LICENCIEMENT'
				  when trim(f.cgstat) in ('MC140','MC190') 		then 'FIN CONTRAT'
				  when trim(f.cgstat) in ('MC150') 				then 'DECES'
				  when trim(f.cgstat) = 'MC160' 				then 'RADIATION'
				  when trim(f.cgstat) = 'MC180' 				then 'FIN FONCTION'
				  when trim(f.cgstat) = 'MCHB0' 				then 'FIN HEBERGEMENT'
				  when trim(f.cgstat) = 'MUTATI' 				then 'MUTATION'
				  else ''
				end as lib_depart
			  from hr.zy1s@SIHAM.WORLD f -- statut depart
					,hr.zypo@SIHAM.WORLD b -- positions
					,hr.ZD00@SIHAM.WORLD c, hr.ZD01@SIHAM.WORLD d -- repertoire hors univ
					,hr.ZD00@SIHAM.WORLD g, hr.ZD01@SIHAM.WORLD h -- repertoire fin de travail
					,hr.zytd12@SIHAM.WORLD    z
			  where f.nudoss = p_nudoss
				and f.rsstat <> 'PEC'
			  -- ##A_PERSONNALISER_CHOIX_SIHAM##
				and trim(f.cgstat) in ('MC100','MC110','MC120','MC130','MC140','MC150','MCHB0','MC160','MC180','MC190','MUTATI')
				and f.nudoss = b.nudoss
				and b.SITCOD = 'FINAC'
				and b.dateff = f.dtef1s
				and b.dateff >=  p_d_deb_annee_univ
				and b.dateff <=  p_d_fin_annee_univ
				and b.dateff <= p_date_systeme
				-- hors univ
				and b.SITCOD = c.CDCODE 
				and c.cdstco = 'HKK'	
				and c.nudoss = d.nudoss
				and d.cdlang = 'F'
				-- fin de travail
				and rtrim(f.CGSTAT,' ') = rtrim(g.CDCODE,' ')
				and g.cdstco = 'UAJ'
				and g.nudoss = h.nudoss
				and h.cdlang = 'F'
				--  Maj en Entrees sorties
				and b.nudoss = z.nudoss(+)
				and Z.CDINFO(+) = 'ES'
			UNION  --- DEPART DANS POSITIONS ADMINISTRATIVES ----------------------------
				select distinct 
				b.nudoss
				,b.dateff   		as date_effet
				,trim(b.SITCOD) 	as code_depart
				-- ##A_PERSONNALISER_CHOIX_SIHAM## -- ##A_PERSONNALISER_CHOIX_OSE##
				,case when trim(b.SITCOD) = 'CGP06' then 'AUTRE'
						  when trim(b.SITCOD) in ('DET01','DET02','DET05','DET08','DET10','DET11','DET18','DET20') then 'DETACH'
						  when trim(b.SITCOD) in ('DSP11','DSP13','DSP15','DSP22','DSP23','DSP33','DSP40','DSP41') then 'DISPO'
						  else ''
				end as lib_depart
				--,d.liblon as ll_position
				from 
				hr.zypo@SIHAM.WORLD b,				  -- positions
				hr.zy1s@SIHAM.WORLD f,				  -- statut
				hr.ZD00@SIHAM.WORLD c, hr.ZD01@SIHAM.WORLD d, -- repertoire
				hr.ZD00@SIHAM.WORLD g, hr.ZD01@SIHAM.WORLD h  -- repertoire
				where 
				b.nudoss = p_nudoss
				-- ##A_PERSONNALISER_CHOIX_SIHAM##
				and b.SITCOD in ('CGP06','DET01','DET02','DET05','DET08','DET10','DET11','DET18','DET20'
							,'DSP11','DSP13','DSP15','DSP22','DSP23','DSP33','DSP40','DSP41')
				and b.nudoss = f.nudoss
				and f.dtef1s <= b.datxxx-1
				and f.datxxx-1 >= b.dateff 
                and b.dateff <= p_d_fin_annee_univ
                and b.datxxx-1 >= p_d_deb_annee_univ
				and b.dateff <= p_date_systeme
				and b.SITCOD=c.CDCODE 
				and c.cdstco = 'HKK'
				and c.nudoss=d.nudoss
				and d.cdlang = 'F'
				and rtrim(f.CGSTAT,' ') = rtrim(g.CDCODE,' ')
				and g.cdstco = 'UAJ'
				and g.nudoss=h.nudoss
				and h.cdlang = 'F'	
		) v_ES -- Entrees sorties
	) v_depart_def
where 
i.nudoss = v_aff.nudoss	 				-- que ceux actifs
and i.matcle = v_orec.matricule(+)	    -- infos compl vacataires dans OREC
and i.nudoss = v_statut.nudoss(+)		-- infos Statut pour les PERM
and v_statut.rnum(+) = 1
and i.nudoss = v_corps_grade.nudoss(+)
and v_corps_grade.rnum(+) = 1
and v_corps_grade.grade = v_groupe_hie.cdcode(+)
and i.nudoss = v_depart_def.nudoss(+)   -- depart definitif
and v_depart_def.rnum(+) = 1
order by trim(i.matcle)
;

/*===========================================================================================================================*/
/*====================================== PROG PRINCIPAL PROCEDURE ===========================================================*/
/*===========================================================================================================================*/
BEGIN
	-- ##A_PERSONNALISER_CHOIX_SIHAM## : table UM_PARAM_ETABL -- v3.0 04/12/2020
	select trim(valeur) INTO v_prefixe_matricule 	from UM_PARAM_ETABL where code = 'PREFIXE_MATRICULE'; 
	select trim(valeur) INTO v_uo_a_exclure 		from UM_PARAM_ETABL where code = 'C_UO_A_EXCLURE'; 

	dbms_output.put_line('   Le '||to_char(sysdate, 'DD/MM/YYYY HH24:MI:SS')||' : SELECTION des intervenants : ');
	dbms_output.put_line('    - Date systeme = '||to_char(p_date_systeme,'DD/MM/YYYY'));
	dbms_output.put_line('    - Annee univ = '||to_char(p_d_deb_annee_univ,'DD/MM/YYYY')||'-'||to_char(p_d_fin_annee_univ,'DD/MM/YYYY'));
	dbms_output.put_line('    - Type transfert : '||p_type_transfert);

	/* =================================== MANUEL ================================================*/
	IF p_type_transfert = 'MANUEL' then
		dbms_output.put_line('      Synchro MANU FORCEE (UM_TRANSFERT_FORCE)');
	
		FOR c_doss in cur_dossier_manu LOOP
			for c_compl in cur_info(c_doss.nudoss, c_doss.matricule) loop
				BEGIN
				-- SPECIF OREC (Mariadb): 
				v_service_rectorat := c_compl.nb_h_service_rectorat;
				-- dbms_output.put_line ('v_service_rectorat :'||v_service_rectorat||'|');
				select
					case when v_service_rectorat is null then 0
						when instr(v_service_rectorat,'.') <> 0 then to_number(v_service_rectorat, '999D99', 'NLS_NUMERIC_CHARACTERS=''. ''')
						when instr(v_service_rectorat,',') <> 0 then to_number(v_service_rectorat, '999D99', 'NLS_NUMERIC_CHARACTERS='', ''')
						else to_number(v_service_rectorat, '999D99', 'NLS_NUMERIC_CHARACTERS='', ''')
					end INTO v_nb_h_service_rectorat
				from dual;
				----------- fin OREC
				insert into OSE.UM_TRANSFERT_INDIVIDU(
					TYPE_TRANSFERT,	 		-- info pop OREC /statut Siham
					D_HORODATAGE,
					NUDOSS,
					MATCLE,
					UID_LDAP,
					QUALIT,
					NOMUSE,
					PRENOM,
					NOMPAT,
					TEM_OSE_UPDATE,
					TEM_OSE_INSERT,
					RECRUTEMENT,
					TYPE_EMP,
					TEM_FONCTIONNAIRE,
					DATE_DEPART_DEF,
					CAUSE_DEPART_DEF,
					GROUPE_HIERARCHIQUE,
					EMPLOYEUR,
					ville_service_rectorat,
					nb_h_service_rectorat,
					orec_code_uo,
					orec_lib_categorie,
					orec_type_vac,		-- v1.5
					annee_id,
					emp_source_code,	-- v3.1  11/10/21
					sexe				-- v3.2  25/03/22
					) 
				values (c_compl.type_transfert
					,sysdate
					,c_doss.nudoss
					,c_doss.matricule
					,c_compl.uid_ldap
					,c_compl.qualit
					,c_compl.nomuse
					,c_compl.prenom
					,c_compl.nompat
					-- si existe deja et pas d insert multi-statut => écrase = UPDATE 
					,case when (UM_EXISTE_INTERVENANT(p_annee_id, c_doss.matricule) <> 0 and UM_INSERT_STATUT_VALIDE(c_doss.matricule, p_annee_id) <> 'INSERT') then 'TODO' 
					 else 'N' end  -- TEM_OSE_UPDATE
					 -- si nexiste pas ou existe mais insert multi-statut = INSERT
					,case when (UM_EXISTE_INTERVENANT(p_annee_id, c_doss.matricule) = 0 or UM_INSERT_STATUT_VALIDE(c_doss.matricule, p_annee_id) = 'INSERT') then 'TODO'
					 else 'N' end  -- TEM_OSE_INSERT
					,c_compl.recrutement				-- specif OREC
					,c_compl.type_emp					-- specif OREC
					,c_compl.tem_fonctionnaire			-- specif OREC
					,c_compl.date_depart_def
					,c_compl.cause_depart_def
					,c_compl.groupe_hierarchique
					,c_compl.employeur					-- specif OREC
					,c_compl.ville_service_rectorat		-- specif OREC
					,c_compl.nb_h_service_rectorat		-- specif OREC
					,c_compl.orec_code_uo				-- specif OREC
					,c_compl.orec_lib_categorie			-- specif OREC
					,c_compl.orec_type_vac				-- specif OREC -- v1.5
					,p_annee_id							-- v2.1
					,c_compl.emp_source_code			-- v3.1  11/10/2021
					,c_compl.sexe						-- v3.2  03/05/22
					);
				EXCEPTION
				when NO_DATA_FOUND then 
						dbms_output.put_line  (p_annee_id||'   !!! Pb insert UM_TRANSFERT_INDIVIDU - NO_DATA_FOUND : '||trim(c_doss.matricule));	-- v2.1
				when TOO_MANY_ROWS then 
						dbms_output.put_line  (p_annee_id||'   !!! Pb insert UM_TRANSFERT_INDIVIDU - TOO_MANY_ROWS : '||trim(c_doss.matricule));	-- v2.1
				when DUP_VAL_ON_INDEX then
						dbms_output.put_line  (p_annee_id||'   !!! Pb insert UM_TRANSFERT_INDIVIDU - DUP_VAL_ON_INDEX : '||trim(c_doss.matricule));	-- v2.1
				when others then
						rollback;
						dbms_output.put_line  (p_annee_id||'   !!! Pb insert UM_TRANSFERT_INDIVIDU - OTHERS : '||trim(c_doss.matricule)||' : '||SQLERRM);	-- v2.1
				END;
				v_nb_dossier_traites := v_nb_dossier_traites +1 ;
				COMMIT;
			end loop;
		END LOOP;
		dbms_output.put_line  ('      '||p_annee_id||' - NB de Dossiers traites (UM_TRANSFERT_INDIVIDU): '||v_nb_dossier_traites);		-- v2.1
	END IF;	
	
	/* =================================== DIFFERENTIEL ================================================*/
	IF p_type_transfert = 'DIFF' then
		dbms_output.put_line('      Synchro DIFF : Dossiers Forces/ou OREC/ou modifies dans Siham depuis le = '||to_char(p_date_profondeur,'DD/MM/YYYY')||' au '||' DATE SYSTEME ='||to_char(p_date_systeme,'DD/MM/YYYY'));
	
		FOR c_doss in cur_dossier_diff LOOP
			--dbms_output.put_line (p_annee_id||'   !!! traitement pour  : '||trim(c_doss.matricule));
			for c_compl in cur_info(c_doss.nudoss, c_doss.matricule) loop
				BEGIN
				--SPECIF OREC : 
				v_service_rectorat := c_compl.nb_h_service_rectorat;
				--dbms_output.put_line ('v_service_rectorat|'||v_service_rectorat||'|');
				select
					case when v_service_rectorat is null then 0
						when instr(v_service_rectorat,'.') <> 0 then to_number(v_service_rectorat, '999D99', 'NLS_NUMERIC_CHARACTERS=''. ''')
						when instr(v_service_rectorat,',') <> 0 then to_number(v_service_rectorat, '999D99', 'NLS_NUMERIC_CHARACTERS='', ''')
						else to_number(v_service_rectorat, '999D99', 'NLS_NUMERIC_CHARACTERS='', ''')
					end INTO v_nb_h_service_rectorat
				from dual;
				--dbms_output.put_line ('v_nb_h_service_rectorat|'||to_char(v_nb_h_service_rectorat)||'|');
				----------- fin OREC
				insert into OSE.UM_TRANSFERT_INDIVIDU(
					TYPE_TRANSFERT,			-- type population pour suivi synchro
					D_HORODATAGE,
					NUDOSS,
					MATCLE,
					UID_LDAP,
					QUALIT,
					NOMUSE,
					PRENOM,
					NOMPAT,
					TEM_OSE_UPDATE,
					TEM_OSE_INSERT,
					RECRUTEMENT,
					TYPE_EMP,
					TEM_FONCTIONNAIRE,
					DATE_DEPART_DEF,
					CAUSE_DEPART_DEF,
					GROUPE_HIERARCHIQUE,
					EMPLOYEUR,
					ville_service_rectorat,
					nb_h_service_rectorat,
					orec_code_uo,
					orec_lib_categorie,
					orec_type_vac,			-- v1.5
					annee_id,				-- v2.1
					emp_source_code,		-- v3.1  11/10/21
					sexe					-- v3.2  30/05/22
					) 
				values (c_compl.type_transfert
					,sysdate
					,c_doss.nudoss
					,c_doss.matricule
					,c_compl.uid_ldap
					,c_compl.qualit
					,c_compl.nomuse
					,c_compl.prenom
					,c_compl.nompat
					-- si existe deja et pas d insert multi-statut => écrase = UPDATE 
					,case when (UM_EXISTE_INTERVENANT(p_annee_id, c_doss.matricule) <> 0 and UM_INSERT_STATUT_VALIDE(c_doss.matricule, p_annee_id) <> 'INSERT') then 'TODO' 
					 else 'N' end  -- TEM_OSE_UPDATE
					 -- si nexiste pas ou existe mais insert multi-statut = INSERT
					,case when (UM_EXISTE_INTERVENANT(p_annee_id, c_doss.matricule) = 0 or UM_INSERT_STATUT_VALIDE(c_doss.matricule, p_annee_id) = 'INSERT') then 'TODO'
					 else 'N' end  -- TEM_OSE_INSERT
					,c_compl.recrutement				-- specif OREC
					,c_compl.type_emp					-- specif OREC
					,c_compl.tem_fonctionnaire			-- specif OREC
					,c_compl.date_depart_def
					,c_compl.cause_depart_def
					,c_compl.groupe_hierarchique
					,c_compl.employeur					-- specif OREC
					,c_compl.ville_service_rectorat		-- specif OREC
					,v_nb_h_service_rectorat			-- specif OREC
					,c_compl.orec_code_uo				-- specif OREC
					,c_compl.orec_lib_categorie			-- specif OREC
					,c_compl.orec_type_vac				-- specif OREC -- v1.5
					,p_annee_id							-- v2.1
					,c_compl.emp_source_code			-- v3.1  11/10/21
					,c_compl.sexe						-- v3.2  30/05/22
					);
				EXCEPTION
				when NO_DATA_FOUND then 
						dbms_output.put_line  (p_annee_id||'   !!! Pb insert UM_TRANSFERT_INDIVIDU - NO_DATA_FOUND : '||trim(c_doss.matricule)); 	-- v2.1
				when TOO_MANY_ROWS then 
						dbms_output.put_line  (p_annee_id||'   !!! Pb insert UM_TRANSFERT_INDIVIDU - TOO_MANY_ROWS : '||trim(c_doss.matricule)); 	-- v2.1
				when DUP_VAL_ON_INDEX then
						dbms_output.put_line  (p_annee_id||'   !!! Pb insert UM_TRANSFERT_INDIVIDU - DUP_VAL_ON_INDEX : '||trim(c_doss.matricule)); 	-- v2.1
				when others then
						rollback;
						dbms_output.put_line  (p_annee_id||'   !!! Pb insert UM_TRANSFERT_INDIVIDU - OTHERS : '||trim(c_doss.matricule)||' : '||SQLERRM); 	-- v2.1
				END;
				v_nb_dossier_traites := v_nb_dossier_traites +1 ;
				COMMIT;
			end loop;
		END LOOP;
		dbms_output.put_line  ('      '||p_annee_id||' - NB de Dossiers traites (UM_TRANSFERT_INDIVIDU): '||v_nb_dossier_traites);	-- v2.1
	END IF;
	
	/* =================================== ACTIFS A DATE SYSTEME ================================================*/
	IF p_type_transfert = 'ACTIFS' then
		--dbms_output.put_line('      Synchro ACTIFS : Actifs a la Date systeme = '||to_char(p_date_systeme,'DD/MM/YYYY'));
		
		FOR c_doss in cur_dossier_actif LOOP
			for c_compl in cur_info(c_doss.nudoss, c_doss.matricule) loop 
				BEGIN
				--SPECIF OREC : 
				v_service_rectorat := c_compl.nb_h_service_rectorat;
				--dbms_output.put_line ('v_service_rectorat|'||v_service_rectorat||'|');
				select
					case when v_service_rectorat is null then 0
						when instr(v_service_rectorat,'.') <> 0 then to_number(v_service_rectorat, '999D99', 'NLS_NUMERIC_CHARACTERS=''. ''')
						when instr(v_service_rectorat,',') <> 0 then to_number(v_service_rectorat, '999D99', 'NLS_NUMERIC_CHARACTERS='', ''')
						else to_number(v_service_rectorat, '999D99', 'NLS_NUMERIC_CHARACTERS='', ''')
					end INTO v_nb_h_service_rectorat
				from dual;
				----------- fin OREC
				
				insert into OSE.UM_TRANSFERT_INDIVIDU(
					TYPE_TRANSFERT,	 -- 'POP_OREC'/'POP_PERM_ENS'/'ACTIFS'/'PASSE'/'POP_GEISHA'
					D_HORODATAGE,
					NUDOSS,
					MATCLE,
					UID_LDAP,
					QUALIT,
					NOMUSE,
					PRENOM,
					NOMPAT,
					TEM_OSE_UPDATE,
					TEM_OSE_INSERT,
					RECRUTEMENT,
					TYPE_EMP,
					TEM_FONCTIONNAIRE,
					DATE_DEPART_DEF,
					CAUSE_DEPART_DEF,
					GROUPE_HIERARCHIQUE,
					EMPLOYEUR,
					ville_service_rectorat,
					nb_h_service_rectorat,
					orec_code_uo,
					orec_lib_categorie,
					orec_type_vac,			-- v1.5
					annee_id,				-- v2.1
					emp_source_code,		-- v3.1  11/10/2021
					sexe					-- v3.2  30/05/22
					) 
				values (c_compl.type_transfert
					,sysdate
					,c_doss.nudoss
					,c_doss.matricule
					,c_compl.uid_ldap
					,c_compl.qualit
					,c_compl.nomuse
					,c_compl.prenom
					,c_compl.nompat
					-- si existe deja et pas d insert multi-statut => écrase = UPDATE 
					,case when (UM_EXISTE_INTERVENANT(p_annee_id, c_doss.matricule) <> 0 and UM_INSERT_STATUT_VALIDE(c_doss.matricule, p_annee_id) <> 'INSERT') then 'TODO' 
					 else 'N' end  -- TEM_OSE_UPDATE
					 -- si nexiste pas ou existe mais insert multi-statut = INSERT
					,case when (UM_EXISTE_INTERVENANT(p_annee_id, c_doss.matricule) = 0 or UM_INSERT_STATUT_VALIDE(c_doss.matricule, p_annee_id) = 'INSERT') then 'TODO'
					 else 'N' end  -- TEM_OSE_INSERT
					,c_compl.recrutement				-- specif OREC
					,c_compl.type_emp					-- specif OREC
					,c_compl.tem_fonctionnaire			-- specif OREC
					,c_compl.date_depart_def
					,c_compl.cause_depart_def
					,c_compl.groupe_hierarchique
					,c_compl.employeur					-- specif OREC
					,c_compl.ville_service_rectorat		-- specif OREC
					,c_compl.nb_h_service_rectorat		-- specif OREC
					,c_compl.orec_code_uo				-- specif OREC
					,c_compl.orec_lib_categorie			-- specif OREC
					,c_compl.orec_type_vac				-- specif OREC -- v1.5
					,p_annee_id							-- v2.1
					,c_compl.emp_source_code			-- v3.1  11/10/21
					,c_compl.sexe 						-- v3.2  25/03/22
					);
				EXCEPTION
				when NO_DATA_FOUND then 
						dbms_output.put_line  (p_annee_id||'   !!! Pb insert UM_TRANSFERT_INDIVIDU - NO_DATA_FOUND : '||trim(c_doss.matricule));	-- v2.1
				when TOO_MANY_ROWS then 
						dbms_output.put_line  (p_annee_id||'   !!! Pb insert UM_TRANSFERT_INDIVIDU - TOO_MANY_ROWS : '||trim(c_doss.matricule));	-- v2.1
				when DUP_VAL_ON_INDEX then
						dbms_output.put_line  (p_annee_id||'   !!! Pb insert UM_TRANSFERT_INDIVIDU - DUP_VAL_ON_INDEX : '||trim(c_doss.matricule)); -- v2.1
				when others then
						rollback;
						dbms_output.put_line  (p_annee_id||'   !!! Pb insert UM_TRANSFERT_INDIVIDU - OTHERS : '||trim(c_doss.matricule)||' : '||SQLERRM); -- v2.1
				END;
				v_nb_dossier_traites := v_nb_dossier_traites +1 ;
				COMMIT;
			end loop;
		END LOOP;
		dbms_output.put_line  ('      '||p_annee_id||' - NB de Dossiers UM_TRANSFERT_INDIVIDU pour la synchro ACTIFS sur année univ : '||v_nb_dossier_traites);			
	END IF;
	
END;
/