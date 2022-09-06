CREATE MATERIALIZED VIEW MV_INTERVENANT AS
SELECT 
  substr(i.source_code, 1,12) 																									  code,
  substr(i.source_code, 1,12) 																									  code_rh,
  'Siham' 					  																									  z_source_id,
  substr(i.source_code, 1,12) 																									  source_code, 
  substr(i.source_code, 1,12) 																									  utilisateur_code,
  decode(st.type_intervenant_id,2,'HU00000001',str.source_code)															  		  z_structure_id,
  st.code_statut   																												  z_statut_id,
  gr.source_code  								    																			  z_grade_id,
  ' '                                      																						  z_discipline_id,
  civ.libelle_court		  																										  z_civilite_id,
  i.nom_usuel               																									  nom_usuel,
  i.prenom                  																									  prenom,
  i.date_naissance          																									  date_naissance,
  i.nom_patronymique        																									  nom_patronymique,
  i.ville_naissance_libelle																										  commune_naissance,
  pv.source_code               																									  z_pays_naissance_id,
  dep.source_code																											  	  z_departement_naissance_id,
--  i.ville_naissance_code_insee       																					  		  ville_naissance_code_insee,
  pn.source_code                       																							  z_pays_nationalite_id,
  i.tel_pro		                            																					  tel_pro,
  i.tel_mobile						                            																  tel_perso,
  i.email_pro                                     																				  email_pro,
  null					                      																					  email_perso,
  null																														  	  adresse_precisions,
  null																		  												  	  adresse_numero,
  null					 																										  z_adresse_numero_compl_id,
  null																														  	  z_adresse_voirie_id,
  null																								  						  	  adresse_voie,
  null																														  	  adresse_lieu_dit,
  null																														  	  adresse_code_postal,
  null																														  	  adresse_commune,
  null																															  z_adresse_pays_id,
  i.numero_insee||i.numero_insee_cle   																					  		  numero_insee,
--  i.numero_insee_cle                   																					  	  numero_insee_cle,
  i.numero_insee_provisoire																										  numero_insee_provisoire,
  i.iban                                      																					  iban,
  i.bic                                       																					  bic,
  nvl(i.rib_hors_sepa,	0)																										  rib_hors_sepa,
/* Données complémentaires */   
  cast(null as varchar2(255))                                   																  autre_1,
  cast(null as varchar2(255))                                   																  autre_2,
  cast(null as varchar2(255))                                   																  autre_3,
  cast(null as varchar2(255))                                   																  autre_4,
  cast(null as varchar2(255))                                   																  autre_5,
  i.date_depart_def				                                   																  affectation_fin,
/* employeur */   
  i.emp_source_code                                   																  			  z_employeur_id,
  i.date_deb_statut																											      validite_debut,
  i.date_fin_statut																												  validite_fin
from um_intervenant i
   , civilite civ
   , um_structure str
   , um_statut st
   , um_grade gr
   , um_pays pn
   , um_pays pv
   , departement dep
   , ( select distinct annee_id lasty, source_code from um_intervenant) a
where a.source_code = i.source_code
   and i.annee_id=a.lasty
   and civ.id= i.civilite_id
   and str.id = i.structure_id
   and st.id = i.statut_id
   and gr.id = i.grade_id
   and pn.id = i.pays_nationalite_id(+)
   and i.pays_nationalite_id is not null 
   and pv.id = i.pays_naissance_id
   and dep.source_code = i.dep_naissance
   and i.email_pro is not null
   and i.w_statut_pip in ('TITU1','STAGI')
union all
SELECT 
  substr(i.source_code, 1,12) 																									  code,
  substr(i.source_code, 1,12) 																									  code_rh,
  'Siham' 					  																									  z_source_id,
  substr(i.source_code, 1,12) 																									  source_code, 
  substr(i.source_code, 1,12) 																									  utilisateur_code,
  decode(st.type_intervenant_id,2,'HU00000001',str.source_code)															  		  z_structure_id,
  st.code_statut   																												  z_statut_id,
  gr.source_code  								    																			  z_grade_id,
  ' '                                      																						  z_discipline_id,
  civ.libelle_court		  																										  z_civilite_id,
  i.nom_usuel               																									  nom_usuel,
  i.prenom                  																									  prenom,
  i.date_naissance          																									  date_naissance,
  i.nom_patronymique        																									  nom_patronymique,
  i.ville_naissance_libelle																										  commune_naissance,
  pv.source_code               																									  z_pays_naissance_id,
  dep.source_code																											  	  z_departement_naissance_id,
--  i.ville_naissance_code_insee       																					  		  ville_naissance_code_insee,
  null 			                       																							  z_pays_nationalite_id,
  i.tel_pro		                            																					  tel_pro,
  i.tel_mobile                             																  						  tel_perso,
  i.email_pro                                     																				  email_pro,
  null						                 																					  email_perso,
  null																															  adresse_precisions,
  null																			  												  adresse_numero,
  null																															  z_adresse_numero_compl_id,
  null																															  z_adresse_voirie_id,
  null																									  						  adresse_voie,
  null																															  adresse_lieu_dit,
  null																															  adresse_code_postal,
  null																															  adresse_commune,
  null																															  z_adresse_pays_id,
  i.numero_insee||i.numero_insee_cle   																					  		  numero_insee,
--  i.numero_insee_cle                   																					  	  numero_insee_cle,
  i.numero_insee_provisoire																										  numero_insee_provisoire,
  i.iban                                      																					  iban,
  i.bic                                       																					  bic,
  nvl(i.rib_hors_sepa,	0)																										  rib_hors_sepa,
/* Données complémentaires */   
  cast(null as varchar2(255))                                   																  autre_1,
  cast(null as varchar2(255))                                   																  autre_2,
  cast(null as varchar2(255))                                   																  autre_3,
  cast(null as varchar2(255))                                   																  autre_4,
  cast(null as varchar2(255))                                   																  autre_5,
  i.date_depart_def				                                   																  affectation_fin,
/* employeur */   
  i.emp_source_code                               																  				  z_employeur_id,
  i.date_deb_statut																											      validite_debut,
  i.date_fin_statut			
from um_intervenant i
   , civilite civ
   , um_structure str
   , um_statut st
   , um_grade gr
   , um_pays pv
   , departement dep
   , ( select distinct annee_id lasty, source_code from um_intervenant) a
where a.source_code = i.source_code
   and i.annee_id=a.lasty
   and civ.id= i.civilite_id
   and str.id = i.structure_id
   and st.id = i.statut_id
   and gr.id = i.grade_id
   and i.pays_nationalite_id is null 
   and pv.id = i.pays_naissance_id
   and dep.source_code = i.dep_naissance
   and i.w_statut_pip in ('TITU1','STAGI')
union all
SELECT 
  substr(i.source_code, 1,12) 																									  code,
  substr(i.source_code, 1,12) 																									  code_rh,
  'Siham' 					  																									  z_source_id,
  substr(i.source_code, 1,12) 																									  source_code, 
  substr(i.source_code, 1,12) 																									  utilisateur_code,
  decode(st.type_intervenant_id,2,'HU00000001',str.source_code)															  		  z_structure_id,
  st.code_statut   																												  z_statut_id,
    decode(gr.corps
                  ,'STSV',decode(i.orec_lib_categorie, null,gr.source_code, /*i.w_statut_pip*/gr.source_code||'_'||cat.source_code)
                  ,gr.source_code)   								    														  z_grade_id,
  ' '                                      																						  z_discipline_id,
  civ.libelle_court		  																										  z_civilite_id,
  i.nom_usuel               																									  nom_usuel,
  i.prenom                  																									  prenom,
  i.date_naissance          																									  date_naissance,
  i.nom_patronymique        																									  nom_patronymique,
  i.ville_naissance_libelle																										  commune_naissance,
  pv.source_code               																									  z_pays_naissance_id,
  dep.source_code																											  	  z_departement_naissance_id,
--  i.ville_naissance_code_insee       																					  		  ville_naissance_code_insee,
  pn.source_code               																							  		  z_pays_nationalite_id,
  i.tel_pro		                            																					  tel_pro,
  nvl(i.tel_mobile,uad.tel_domicile)                             																  tel_perso,
  i.email_pro                                     																				  email_pro,
  uad.w_mail_perso                           																					  email_perso,
  uad.batiment 																													  adresse_precisions,
  uad.no_voie																	  												  adresse_numero,
  uad.numero_compl_code 																										  z_adresse_numero_compl_id,
  uad.voirie_code 																												  z_adresse_voirie_id,
  uad.nom_voie 																							  						  adresse_voie,
  uad.localite 	  																												  adresse_lieu_dit,
  uad.code_postal 																												  adresse_code_postal,
  uad.ville 	  																												  adresse_commune,
  uad.pays_code_insee																											  z_adresse_pays_id,
  i.numero_insee||i.numero_insee_cle   																					  		  numero_insee,
--  i.numero_insee_cle                   																					  	  numero_insee_cle,
  i.numero_insee_provisoire																										  numero_insee_provisoire,
  i.iban                                      																					  iban,
  i.bic                                       																					  bic,
  nvl(i.rib_hors_sepa,	0)																										  rib_hors_sepa,
/* Données complémentaires */   
  cast(null as varchar2(255))                                   																  autre_1,
  cast(null as varchar2(255))                                   																  autre_2,
  cast(null as varchar2(255))                                   																  autre_3,
  cast(null as varchar2(255))                                   																  autre_4,
  cast(null as varchar2(255))                                   																  autre_5,
  i.date_depart_def				                                   																  affectation_fin,
/* employeur */   
  nvl(i.emp_source_code  ,'ose_neutre')                																  			  z_employeur_id,
  i.date_deb_statut																											      validite_debut,
  i.date_fin_statut																  												  validite_fin
from um_intervenant i
   , civilite civ
   , um_structure str
   , um_statut st
   , (select g.*, c.source_code corps
      from um_grade g
         , um_corps c
      where c.id=g.corps_id
    )gr
   , um_pays pn
   , um_pays pv
   , departement dep
   , um_orec_categorie cat
   , ( select distinct annee_id lasty, source_code from um_intervenant) a
   , um_adresse_intervenant uad
   , um_pays payr
   , um_voirie vr
--   , pays opr
where a.source_code = i.source_code
   and i.annee_id=a.lasty
   and civ.id= i.civilite_id
   and str.id = i.structure_id
   and st.id = i.statut_id
   and cat.source_code(+) = i.orec_lib_categorie
   and gr.id = i.grade_id
   and pn.id = i.pays_nationalite_id(+)
   and i.pays_nationalite_id is not null 
   and pv.id = i.pays_naissance_id
   and dep.source_code = i.dep_naissance
   and uad.source_code = i.source_code||'_'||i.annee_id
   and uad.intervenant_id = i.id
   and nvl(i.email_pro,uad.w_mail_perso) is not null
   and payr.source_code = uad.pays_code_insee
--   and opr.source_code = payr.source_code
   and i.w_statut_pip not in ('TITU1','STAGI')
   and vr.code(+) = uad.voirie_code
   and st.type_intervenant_id = 2
union all
SELECT 
  substr(i.source_code, 1,12) 																									  code,
  substr(i.source_code, 1,12) 																									  code_rh,
  'Siham' 					  																									  z_source_id,
  substr(i.source_code, 1,12) 																									  source_code, 
  substr(i.source_code, 1,12) 																									  utilisateur_code,
  decode(st.type_intervenant_id,2,'HU00000001',str.source_code)															  		  z_structure_id,
  st.code_statut   																												  z_statut_id,
  decode(gr.corps
				,'STSV',decode(i.orec_lib_categorie, null,gr.source_code, /*i.w_statut_pip*/gr.source_code||'_'||cat.source_code)
				,gr.source_code)   								    														  	  z_grade_id,
  ' '                                      																						  z_discipline_id,
  civ.libelle_court		  																										  z_civilite_id,
  i.nom_usuel               																									  nom_usuel,
  i.prenom                  																									  prenom,
  i.date_naissance          																									  date_naissance,
  i.nom_patronymique        																									  nom_patronymique,
  i.ville_naissance_libelle																										  commune_naissance,
  pv.source_code               																									  z_pays_naissance_id,
  dep.source_code																											  	  z_departement_naissance_id,
--  i.ville_naissance_code_insee       																					  		  ville_naissance_code_insee,
  null                       																							  		  z_pays_nationalite_id,
  i.tel_pro		                            																					  tel_pro,
  nvl(i.tel_mobile,uad.tel_domicile)                             																  tel_perso,
  i.email_pro                                     																				  email_pro,
  uad.w_mail_perso                           																					  email_perso,
  uad.batiment 																													  adresse_precisions,
  uad.no_voie																		  											  adresse_numero,
  uad.numero_compl_code 																										  z_adresse_numero_compl_id,
  uad.voirie_code 																												  z_adresse_voirie_id,
  uad.nom_voie 																							   						  adresse_voie,
  uad.localite 	  																												  adresse_lieu_dit,
  uad.code_postal 																												  adresse_code_postal,
  uad.ville 	  																												  adresse_commune,
  uad.pays_code_insee																											  z_adresse_pays_id,
  i.numero_insee||i.numero_insee_cle   																					  		  numero_insee,
--  i.numero_insee_cle                   																					  	  numero_insee_cle,
  i.numero_insee_provisoire																										  numero_insee_provisoire,
  i.iban                                      																					  iban,
  i.bic                                       																					  bic,
  nvl(i.rib_hors_sepa,	0)																										  rib_hors_sepa,
/* Données complémentaires */   
  cast(null as varchar2(255))                                   																  autre_1,
  cast(null as varchar2(255))                                   																  autre_2,
  cast(null as varchar2(255))                                   																  autre_3,
  cast(null as varchar2(255))                                   																  autre_4,
  cast(null as varchar2(255))                                   																  autre_5,
  i.date_depart_def				                                   																  affectation_fin,
/* employeur */   
  nvl(i.emp_source_code  ,'ose_neutre')                																  			  z_employeur_id,
  i.date_deb_statut																											      validite_debut,
  i.date_fin_statut																  												  validite_fin
from um_intervenant i
   , civilite civ
   , um_structure str
   , um_statut st
   , (select g.*, c.source_code corps
      from um_grade g
         , um_corps c
      where c.id=g.corps_id
    )gr
   , um_pays pv
   , departement dep
   , um_orec_categorie cat
   , ( select distinct annee_id lasty, source_code from um_intervenant) a
   , um_adresse_intervenant uad
   , um_pays payr
   , um_voirie vr
--   , pays opr
where a.source_code = i.source_code
   and i.annee_id=a.lasty
   and civ.id= i.civilite_id
   and str.id = i.structure_id
   and st.id = i.statut_id
   and cat.source_code(+) = i.orec_lib_categorie
   and gr.id = i.grade_id
   and i.pays_nationalite_id is  null 
   and pv.id = i.pays_naissance_id
   and dep.source_code = i.dep_naissance
   and uad.source_code = i.source_code||'_'||i.annee_id
   and uad.intervenant_id = i.id
   and nvl(i.email_pro,uad.w_mail_perso) is not null
   and payr.source_code = uad.pays_code_insee
--   and opr.source_code = payr.source_code
   and i.w_statut_pip not in ('TITU1','STAGI')
   and vr.code(+) = uad.voirie_code
   and st.type_intervenant_id = 2
union all
SELECT 
  substr(i.source_code, 1,12) 																									  code,
  substr(i.source_code, 1,12) 																									  code_rh,
  'Siham' 					  																									  z_source_id,
  substr(i.source_code, 1,12) 																									  source_code, 
  substr(i.source_code, 1,12) 																									  utilisateur_code,
  decode(st.type_intervenant_id,2,'HU00000001',str.source_code)															  		  z_structure_id,
  st.code_statut   																												  z_statut_id,
    decode(gr.corps
                  ,'STSV',decode(i.orec_lib_categorie, null,gr.source_code, /*i.w_statut_pip*/gr.source_code||'_'||cat.source_code)
                  ,gr.source_code)   								    														  z_grade_id,
  ' '                                      																						  z_discipline_id,
  civ.libelle_court		  																										  z_civilite_id,
  i.nom_usuel               																									  nom_usuel,
  i.prenom                  																									  prenom,
  i.date_naissance          																									  date_naissance,
  i.nom_patronymique        																									  nom_patronymique,
  i.ville_naissance_libelle																										  commune_naissance,
  pv.source_code               																									  z_pays_naissance_id,
  dep.source_code																											  	  z_departement_naissance_id,
--  i.ville_naissance_code_insee       																					  		  ville_naissance_code_insee,
  pn.source_code               																							  		  z_pays_nationalite_id,
  i.tel_pro		                            																					  tel_pro,
  i.tel_mobile                           																  						  tel_perso,
  i.email_pro                                     																				  email_pro,
  null						                 																					  email_perso,
  null																															  adresse_precisions,
  null																			  												  adresse_numero,
  null																															  z_adresse_numero_compl_id,
  null																															  z_adresse_voirie_id,
  null																									  						  adresse_voie,
  null																															  adresse_lieu_dit,
  null																															  adresse_code_postal,
  null																															  adresse_commune,
  null																															  z_adresse_pays_id,
  i.numero_insee||i.numero_insee_cle   																					  		  numero_insee,
--  i.numero_insee_cle                   																					  	  numero_insee_cle,
  i.numero_insee_provisoire																										  numero_insee_provisoire,
  i.iban                                      																					  iban,
  i.bic                                       																					  bic,
  nvl(i.rib_hors_sepa,	0)																										  rib_hors_sepa,
/* Données complémentaires */   
  cast(null as varchar2(255))                                   																  autre_1,
  cast(null as varchar2(255))                                   																  autre_2,
  cast(null as varchar2(255))                                   																  autre_3,
  cast(null as varchar2(255))                                   																  autre_4,
  cast(null as varchar2(255))                                   																  autre_5,
  i.date_depart_def				                                   																  affectation_fin,
/* employeur */   
  i.emp_source_code						               																  			  z_employeur_id,
  i.date_deb_statut																											      validite_debut,
  i.date_fin_statut																  												  validite_fin
from um_intervenant i
   , civilite civ
   , um_structure str
   , um_statut st
   , (select g.*, c.source_code corps
      from um_grade g
         , um_corps c
      where c.id=g.corps_id
    )gr
   , um_pays pn
   , um_pays pv
   , departement dep
   , um_orec_categorie cat
   , ( select distinct annee_id lasty, source_code from um_intervenant) a
where a.source_code = i.source_code
   and i.annee_id=a.lasty
   and civ.id= i.civilite_id
   and str.id = i.structure_id
   and st.id = i.statut_id
   and cat.source_code(+) = i.orec_lib_categorie
   and gr.id = i.grade_id
   and pn.id = i.pays_nationalite_id(+)
   and i.pays_nationalite_id is not null 
   and pv.id = i.pays_naissance_id
   and dep.source_code = i.dep_naissance
   and i.email_pro is not null
   and i.w_statut_pip not in ('TITU1','STAGI')
   and st.type_intervenant_id = 1
union all
SELECT 
  substr(i.source_code, 1,12) 																									  code,
  substr(i.source_code, 1,12) 																									  code_rh,
  'Siham' 					  																									  z_source_id,
  substr(i.source_code, 1,12) 																									  source_code, 
  substr(i.source_code, 1,12) 																									  utilisateur_code,
  decode(st.type_intervenant_id,2,'HU00000001',str.source_code)															  		  z_structure_id,
  st.code_statut   																												  z_statut_id,
  decode(gr.corps
				,'STSV',decode(i.orec_lib_categorie, null,gr.source_code, /*i.w_statut_pip*/gr.source_code||'_'||cat.source_code)
				,gr.source_code)   								    														  	  z_grade_id,
  ' '                                      																						  z_discipline_id,
  civ.libelle_court		  																										  z_civilite_id,
  i.nom_usuel               																									  nom_usuel,
  i.prenom                  																									  prenom,
  i.date_naissance          																									  date_naissance,
  i.nom_patronymique        																									  nom_patronymique,
  i.ville_naissance_libelle																										  commune_naissance,
  pv.source_code               																									  z_pays_naissance_id,
  dep.source_code																											  	  z_departement_naissance_id,
--  i.ville_naissance_code_insee       																					  		  ville_naissance_code_insee,
  null                       																							  		  z_pays_nationalite_id,
  i.tel_pro		                            																					  tel_pro,
  i.tel_mobile                            																  						  tel_perso,
  i.email_pro                                     																				  email_pro,
  null							             																					  email_perso,
  null																															  adresse_precisions,
  null																				  											  adresse_numero,
  null																															  z_adresse_numero_compl_id,
  null																															  z_adresse_voirie_id,
  null																									   						  adresse_voie,
  null																															  adresse_lieu_dit,
  null																															  adresse_code_postal,
  null																															  adresse_commune,
  null																															  z_adresse_pays_id,
  i.numero_insee||i.numero_insee_cle   																					  		  numero_insee,
--  i.numero_insee_cle                   																					  	  numero_insee_cle,
  i.numero_insee_provisoire																										  numero_insee_provisoire,
  i.iban                                      																					  iban,
  i.bic                                       																					  bic,
  nvl(i.rib_hors_sepa,	0)																										  rib_hors_sepa,
/* Données complémentaires */   
  cast(null as varchar2(255))                                   																  autre_1,
  cast(null as varchar2(255))                                   																  autre_2,
  cast(null as varchar2(255))                                   																  autre_3,
  cast(null as varchar2(255))                                   																  autre_4,
  cast(null as varchar2(255))                                   																  autre_5,
  i.date_depart_def				                                   																  affectation_fin,
/* employeur */   
  i.emp_source_code							           																  			  z_employeur_id,
  i.date_deb_statut																											      validite_debut,
  i.date_fin_statut																  												  validite_fin
from um_intervenant i
   , civilite civ
   , um_structure str
   , um_statut st
   , (select g.*, c.source_code corps
      from um_grade g
         , um_corps c
      where c.id=g.corps_id
    )gr
   , um_pays pv
   , departement dep
   , um_orec_categorie cat
   , ( select distinct annee_id lasty, source_code from um_intervenant) a
where a.source_code = i.source_code
   and i.annee_id=a.lasty
   and civ.id= i.civilite_id
   and str.id = i.structure_id
   and st.id = i.statut_id
   and cat.source_code(+) = i.orec_lib_categorie
   and gr.id = i.grade_id
   and i.pays_nationalite_id is  null 
   and pv.id = i.pays_naissance_id
   and dep.source_code = i.dep_naissance
   and i.email_pro is not null
   and i.w_statut_pip not in ('TITU1','STAGI')
   and st.type_intervenant_id = 1;
