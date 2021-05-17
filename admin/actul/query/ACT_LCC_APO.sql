select  elp.annee_id
     , elp.source_code
     , elp.libelle
     , lcc.cod_elp_s1_lcc
     , lcc.cod_elp_s2_lcc
     , lcc.daa_deb_val_lcc
     , lcc.daa_fin_val_lcc
     , lcc.com_lien_lcc
from act_element_pedagogique elp
   , elp_correspond_elp@apogee.world lcc
where lcc.cod_elp_cible_lcc = elp.source_code
  and elp.annee_id = :v_annee