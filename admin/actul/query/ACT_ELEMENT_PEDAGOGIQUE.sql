select kern.annee_id
      ,kern.libelle
      ,kern.z_etape_id
      ,etp_p.z_structure_id
      ,etp_p.cmp_apo
      ,kern.z_periode_id
      ,kern.taux_foad
      ,kern.source_code
      ,kern.z_discipline_id
      ,kern.fi
      ,kern.fa
      ,kern.fc
      ,kern.id
	  ,kern.ects
from (select distinct
  odf.annee_id,
  odf.libelle,
  min ( chp.z_etape_id ) keep ( dense_rank first order by                                                 -- Rattachement de chaque element a une etape de reference
    case when chp.z_etape_id = ece.z_etape_porteuse_id then 0 else 1 end, -- Critere 1 : prise en compte de la VET porteuse definie dans Apogee
    case when (elp_com. nb > 1 and otf.service_statutaire=1) then 0 else 1  end,                     	  -- Critere 2 : prise en compte de la vet DN
    etp.specifique_echanges,                                                                              -- Critere 3 : les etapes dediees aux echanges sont non prioritaires
    chp.z_etape_id                                                                                        -- Critere 4 : ordre alphabetique du code etape
    )                                          as z_etape_id,
  chp.z_periode_id,
  chp.taux_foad,
  chp.z_element_pedagogique_id                 as source_code,
  'AD'                                 as z_discipline_id,
  max ( etp.FI )                               as FI,
  max ( etp.FA )                               as FA,
  max ( etp.FC )                               as FC,
  odf.annee_id || '_' || chp.z_element_pedagogique_id as id,
  odf.ects
from act_offre_de_formation    odf
join act_chemin_pedagogique    chp on chp.annee_id = odf.annee_id and chp.z_element_pedagogique_id = odf.noeud_inf_id
join act_etape                 etp on etp.annee_id = chp.annee_id and etp.source_code = chp.z_etape_id
join ose.type_formation        otf on otf.source_code = etp.z_type_formation_id
join  (select count( z_etape_id) nb , z_element_pedagogique_id, annee_id
			from act_chemin_pedagogique
			group by z_element_pedagogique_id, annee_id) elp_com on elp_com.z_element_pedagogique_id = odf.noeud_inf_id
                                                                      and elp_com.annee_id = odf.annee_id
left outer join act_element_commun ece on ece.annee_id = odf.annee_id  and ece.z_element_pedagogique_id = odf.noeud_inf_id
where odf.annee_id = :v_annee
group by
  odf.annee_id,
  odf.libelle,
  chp.z_periode_id,
  chp.taux_foad,
  chp.z_element_pedagogique_id,
  odf.ects
) kern
, act_etape etp_p
, (
    select distinct z_element_pedagogique_id, substr(id,1,4) annee_id
    from act_volume_horaire_ens
  ) chg
 where etp_p.source_code = kern.z_etape_id
   and etp_p.annee_id = kern.annee_id
   and chg.z_element_pedagogique_id = kern.source_code
   and chg.annee_id = kern.annee_id