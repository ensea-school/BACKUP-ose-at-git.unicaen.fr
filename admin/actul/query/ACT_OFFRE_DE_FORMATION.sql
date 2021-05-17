with
  arbre_offre_de_formation as (
    select
      connect_by_root rel.annee_id             as annee_id,
      rel.noeud_sup_id,
      rel.noeud_sup_exported,
      rel.structure_sup_id,
      rel.comp_sup_id,
      rel.choix_minimum,
      rel.choix_maximum,
      rel.liste_id,
      rel.lse_exported,
      rel.lien_lse_exported,
      rel.type_choix,
      rel.libelle_liste,
      rel.libelle_court_liste,
      rel.noeud_inf_id,
      rel.noeud_inf_exported,
      rel.structure_inf_id,
      rel.comp_inf_id,
      rel.libelle,
      rel.libelle_court,
      rel.nature,
      rtrim ( sys_connect_by_path ( case when rel.nature in ( 'MIRA', 'MIRB', 'MIR' ) then null else rel.noeud_inf_id end,  '>' ), '>' ) as chemin_noeud_inf_id,
      rtrim ( sys_connect_by_path ( rel.cod_pel,       '>' ), '>' ) as chemin_periode,
      rtrim ( sys_connect_by_path ( rel.tem_a_dis_elp, '>' ), '>' ) as chemin_taux_foad,
      connect_by_isleaf                        as isleaf,
	  rel.ects
    from act_odf_relations rel
      start with rel.noeud_sup_id is null
      connect by prior rel.annee_id = rel.annee_id and prior rel.noeud_inf_id = rel.noeud_sup_id
  ),
  branches_inutiles as (
    select distinct
      arb.annee_id,
      arb.noeud_inf_id
    from arbre_offre_de_formation arb
    join arbre_offre_de_formation inu on inu.annee_id = arb.annee_id and inu.chemin_noeud_inf_id = arb.chemin_noeud_inf_id
    where arb.nature in ( 'MIR', 'MIRA', 'MIRB' ) -- Exclusion des elements techniques
      and arb.isleaf = 1
      and inu.nature in ( 'MIR', 'MIRA', 'MIRB' )
  ),
  arbre_elague as (
    select
      arb.annee_id,
      arb.noeud_sup_id,
      arb.noeud_sup_exported,
      arb.structure_sup_id,
      arb.comp_sup_id,
      arb.choix_minimum,
      arb.choix_maximum,
      arb.liste_id,
      arb.lse_exported,
      arb.lien_lse_exported,
      arb.type_choix,
      arb.libelle_liste,
      arb.libelle_court_liste,
      arb.noeud_inf_id,
      arb.noeud_inf_exported,
      arb.structure_inf_id,
      arb.comp_inf_id,
      arb.libelle,
      arb.libelle_court,
      arb.nature,
      substr ( arb.chemin_periode,   instr ( arb.chemin_periode,   '>', -1 ) + 1 ) as periode,
      substr ( arb.chemin_taux_foad, instr ( arb.chemin_taux_foad, '>', -1 ) + 1 ) as taux_foad,
	  arb.ects
    from      arbre_offre_de_formation arb
    left join branches_inutiles        inu on inu.annee_id = arb.annee_id and inu.noeud_inf_id = arb.noeud_inf_id
    where inu.noeud_inf_id is null
  ),
  noeud_unique as (
    select
      arb.annee_id,
      arb.noeud_inf_id,
      arb.nature,
      min ( arb.periode )                             as periode_min,
      max ( arb.periode )                             as periode_max,
      0  as taux_foad
    from arbre_elague arb
    group by
      arb.annee_id,
      arb.noeud_inf_id,
      arb.nature
  )
select distinct
  arb.annee_id,
  arb.noeud_sup_id,
  arb.noeud_sup_exported,
  uo_sup.code_uo,
  arb.comp_sup_id,
  arb.choix_minimum,
  arb.choix_maximum,
  arb.liste_id,
  arb.lse_exported,
  arb.lien_lse_exported,
  arb.libelle_liste,
  arb.libelle_court_liste,
  arb.noeud_inf_id,
  arb.noeud_inf_exported,
  uo_inf.code_uo,
  arb.comp_inf_id,
  arb.libelle,
  arb.libelle_court,
  arb.nature,
  case when uno.periode_min = uno.periode_max
    then  case uno.periode_min
			when 'AN' then 'S2'
			else uno.periode_min
		  end
    else null
    end                                        as periode,
  uno.taux_foad,
  arb.ects
from arbre_elague arb
join noeud_unique uno on uno.annee_id = arb.annee_id and uno.noeud_inf_id = arb.noeud_inf_id
left join um_comp_apo_uo uo_sup on uo_sup.cod_cmp = arb.comp_sup_id
left join um_comp_apo_uo uo_inf on uo_inf.cod_cmp = arb.comp_inf_id
where arb.annee_id = :v_annee
order by 1, 2, 6, 9