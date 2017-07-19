
select * from type_population;
select * from type_contrat_travail;
select * from individu;

select distinct
  i.no_individu, i.nom_usuel, i.prenom, -- individu
--  TP.LL_TYPE_POPULATION as libelle,
--  TP.HEURE_TD as service_statutaire,
  --depassement,
  --fonction_e_c,
  --z_type_intervenant_id,
  --source_id,
--  TP.C_TYPE_POPULATION as source_code,
--  tp.d_creation as validite_debut,
  --validite_fin
  
  crct.c_section_cnu, -- 248
  ca.c_section_cnu,   -- 3849
  fa.c_section_cnu,   -- 2
  ch.c_section_cnu,   -- 81
  p.c_section_cnu,    -- 4251
  coalesce( crct.c_section_cnu, ca.c_section_cnu, fa.c_section_cnu, ch.c_section_cnu, p.c_section_cnu ) as cnu
from
  individu i 
  left join carriere c ON c.no_dossier_pers = I.NO_INDIVIDU
  left join occupation oc on oc.no_dossier_pers = i.no_individu
  left join poste p on p.no_poste = oc.no_poste
  left join type_population tp ON TP.C_TYPE_POPULATION = C.C_TYPE_POPULATION
  left join crct on crct.no_dossier_pers = i.no_individu
  left join contrat_avenant ca on ca.no_dossier_pers = i.no_individu
  left join FICHE_ACTIVITE fa on fa.no_individu = i.no_individu
  left join chercheur ch on ch.no_individu = i.no_individu
 where p.c_section_cnu is not null
  
