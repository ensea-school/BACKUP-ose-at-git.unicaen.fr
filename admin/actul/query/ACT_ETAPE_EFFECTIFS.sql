with tot_eff as (
      select ped.annee_id
           , ped.z_element_pedagogique_id
           , sum(vet_eff.eff_etp_fi) tot_eff_fi_elp
           , sum(vet_eff.eff_etp_fa) tot_eff_fa_elp
           , sum(vet_eff.eff_etp_fc) tot_eff_fc_elp
      from act_chemin_pedagogique ped
         , act_vet_effectifs vet_eff
      where vet_eff.annee_id = ped.annee_id
        and vet_eff.etape_id = ped.z_etape_id
      group by ped.annee_id
             , ped.z_element_pedagogique_id
      )
select etp.annee_id
      ,etp.source_code etape_id
      ,ped.z_element_pedagogique_id
      ,elp.z_etape_id
      , case tot_eff.tot_eff_fi_elp
          when 0 then 0
          else  round(elp_eff.effectif_fi * etp_eff.eff_etp_fi / tot_eff.tot_eff_fi_elp,2)
          end  eff_fi
      ,case tot_eff.tot_eff_fa_elp
          when 0 then 0
          else round(elp_eff.effectif_fa * etp_eff.eff_etp_fa / tot_eff.tot_eff_fa_elp ,2)
        end eff_fa
      ,case tot_eff.tot_eff_fc_elp
          when 0 then 0
          else round(elp_eff.effectif_fc * etp_eff.eff_etp_fc / tot_eff.tot_eff_fc_elp ,2)
       end eff_fc
from act_etape etp
   , act_element_effectifs elp_eff
   , act_chemin_pedagogique ped
   , tot_eff
   , act_vet_effectifs etp_eff
   , act_element_pedagogique elp
where etp.annee_id = ped.annee_id
  and etp.source_code = ped.z_etape_id
  and etp_eff.annee_id = ped.annee_id
  and etp_eff.etape_id = ped.z_etape_id
  and elp.annee_id = ped.annee_id
  and elp.source_code = ped.z_element_pedagogique_id
  and elp_eff.annee_id = ped.annee_id
  and elp_eff.z_element_pedagogique_id = ped.z_element_pedagogique_id
  and tot_eff.annee_id = ped.annee_id
  and tot_eff.z_element_pedagogique_id = ped.z_element_pedagogique_id