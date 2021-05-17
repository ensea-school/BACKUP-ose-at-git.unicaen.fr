with ETAPE_ENSEIGNEMENT as (
        select annee_id
            ,noeud_sup_id
            ,noeud_inf_id
            ,noeud_inf_exported
            ,periode
            ,taux_foad
        from (  select distinct
                connect_by_root annee_id                   as annee_id,
                connect_by_root odf.noeud_sup_id           as noeud_sup_id,
                odf.noeud_inf_id,
                odf.noeud_inf_exported,
                odf.periode,
                odf.taux_foad
                from act_offre_de_formation odf
                    start with ( odf.annee_id, odf.noeud_sup_id ) in ( select annee_id,  source_code from act_etape )
                    connect by prior odf.annee_id = odf.annee_id and  prior odf.noeud_inf_id = odf.noeud_sup_id) pk
  )
select
  etp.annee_id,
  etp.noeud_inf_id                                 as z_element_pedagogique_id,
  etp.noeud_sup_id                                 as z_etape_id,
  etp.noeud_sup_id || '_' || etp.noeud_inf_id          as source_code,
  etp.periode                                      as z_periode_id,
  max ( etp.taux_foad )                            as taux_foad,
  etp.annee_id || '_' || etp.noeud_sup_id || '_' || etp.noeud_inf_id as id,
  case com.z_element_pedagogique_id
    when null then 'N'
    else 'O'
  end tem_elp,
  case com.z_etape_porteuse_id
    when etp.noeud_sup_id then 'O'
    else 'N'
  end tem_vet_porteuse,
  noeud_inf_exported
from ETAPE_ENSEIGNEMENT etp
   , ACT_ELEMENT_COMMUN com
  , (
    select distinct z_element_pedagogique_id
    from ACT_VOLUME_HORAIRE_ENS
  ) chg
where com.annee_id(+) = etp.annee_id
  and com.z_element_pedagogique_id (+)= etp.noeud_inf_id
  and etp.annee_id = :v_annee
  and chg.z_element_pedagogique_id = etp.noeud_inf_id
group by
  etp.annee_id,
  etp.noeud_sup_id,
  etp.noeud_inf_id,
  etp.periode,
  com.z_element_pedagogique_id ,
  com.z_etape_porteuse_id,
  etp.noeud_inf_exported