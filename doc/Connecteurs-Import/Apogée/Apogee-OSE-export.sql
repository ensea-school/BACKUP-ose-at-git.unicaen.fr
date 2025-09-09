--
-- ALIMENTATION DES TABLES EXPLOITEES PAR LA SYNCHRONISATION APOGEE -> OSE
-- Auteur : Bruno Bernard bruno.bernard@unicaen.fr
--
-- Evolutions
-- 04/10/2019 Plafonnement de la charge d'enseignement liee a l'encadrement individuel (decision d'établissement unicaen) : 1 heure maxi par étudiant
-- 04/10/2019 Mise en coherence entre les heures de type EAD et le flag a_distance : s'il existe une charge EAD alors a_distance = Oui sinon a_distance = Non
-- 31/03/2021 Exclusion des ELP fictifs en testant le témoin TEM_FICTIF de leur nature et non plus leur code nature
-- 01/06/2021 Alimentation nouvelle table des effectifs par étape par année et par regime d'inscription
-- 22/07/2025 Suppression des tables tampon ose_groupe_type_formation et ose_type_formation, modification requête alimentation de ose_etape
-- 01/09/2025 Les formations sont en FI par défaut, et sinon selon les régimes d'inscription en FA ou FC

--
-- Reinitialisation des tables
--
delete from ose_volume_horaire_ens
;
delete from ose_element_effectifs
;
delete from ose_element_pedagogique
;
delete from ose_chemin_pedagogique
;
delete from ose_offre_de_formation
;
delete from ose_etape_effectifs
;
delete from ose_etape
;


--
-- Etapes
-- Une etape OSE est assimilable a une VET Apogee
--
insert into ose_etape
with
  tmp as (
    select
      anu.cod_anu                              as annee_id,
      nvl ( vet.lib_web_vet, etp.lib_etp )     as libelle,
      etp.lic_etp                              as libelle_court,
      min ( dip.cod_tpd_etb ) keep ( dense_rank first order by anu.cod_anu, vde.cod_etp, vde.cod_vrs_vet ) as z_type_formation_id,
      min ( vde.cod_sis_daa_min ) keep ( dense_rank first order by anu.cod_anu, vde.cod_etp, vde.cod_vrs_vet ) as niveau,
      -- Identifiant de la structure dans le referentiel de l etablissement
      str.cod_str                              as z_structure_id,
      -- Reperer les formations dediees aux echanges
      min (
        case when dip.cod_tpd_etb in ( 'AL', 'DL', 'DM', 'MA', '83' ) then 1 else 0 end
                            ) keep ( dense_rank first order by anu.cod_anu, vde.cod_etp, vde.cod_vrs_vet ) as specifique_echanges,
      -- Determiner le domaine fonctionnel en fonction du type de diplome ou du niveau de la formation
      min (
        case
          when tpd.cod_tpd_sis in ( 'CY', 'CZ' ) then 'D101'
          when tpd.cod_tpd_sis in ( 'IC', 'FK' ) then 'D102'
          when tpd.cod_nif     in ( 0, 7, 8, 9 ) then 'D101'
          when tpd.cod_nif     in ( 6 )          then 'D103'
          else case tpd.cod_nif - nvl ( vdi.dur_ann_vdi, least ( vde.cod_sis_daa_min, tpd.cod_nif ) ) + least ( vde.cod_sis_daa_min, tpd.cod_nif )
            when 1                               then 'D101'
            when 2                               then 'D101'
            when 3                               then 'D101'
            when 4                               then 'D102'
            when 5                               then 'D102'
            else                                      'D101'
            end
          end
                            ) keep ( dense_rank first order by anu.cod_anu, vde.cod_etp, vde.cod_vrs_vet ) as domaine_fonctionnel,
      vde.cod_etp,
      vde.cod_vrs_vet
    from annee_uni                 anu
    join vdi_fractionner_vet       vde on anu.cod_anu between vde.daa_deb_rct_vet and vde.daa_fin_rct_vet
    join etape                     etp on etp.cod_etp = vde.cod_etp
    join diplome                   dip on dip.cod_dip = vde.cod_dip
    join typ_diplome               tpd on tpd.cod_tpd_etb = dip.cod_tpd_etb
    join version_diplome           vdi on vdi.cod_dip = vde.cod_dip and vdi.cod_vrs_vdi = vde.cod_vrs_vdi
    join version_etape             vet on vet.cod_etp = vde.cod_etp and vet.cod_vrs_vet = vde.cod_vrs_vet
    join composante                cmp on cmp.cod_cmp = vet.cod_cmp
    join ucbn_composante_ldap      str on str.cod_cmp = cmp.cod_cmp         -- Recherche du code structure dans le referentiel des structures de l etablissement
    where anu.cod_anu >= to_char ( add_months ( sysdate, -18 ), 'YYYY' )    -- Par convention l annee debute le 1er juillet
    and cmp.cod_tpc! = 'EXT'                                                -- Exclusion des structures exterieures (IFSI, etc.)
    group by
      anu.cod_anu,
      nvl ( vet.lib_web_vet, etp.lib_etp ),
      etp.lic_etp,
      str.cod_str,
      vde.cod_etp,
      vde.cod_vrs_vet
  )
select
  tmp.annee_id,
  tmp.libelle,
  tmp.libelle_court,
  tmp.z_type_formation_id,
  tmp.niveau,
  tmp.z_structure_id,
  tmp.cod_etp || '_' || tmp.cod_vrs_vet                                as source_code,
  least ( tmp.specifique_echanges, 1 )                                 as specifique_echanges,
  min ( tmp.domaine_fonctionnel )                                      as domaine_fonctionnel,
  -- Determiner en fonction des regimes d inscription si la VET est ouverte en FI, en FC et/ou en apprentissage
  max ( case when rve.cod_rgi not in ( '4', '2', '5', '6' ) then 1 else 0 end ) as FI,
  max ( case when rve.cod_rgi in ( '4' )           then 1 else 0 end ) as FA,
  max ( case when rve.cod_rgi in ( '2', '5', '6' ) then 1 else 0 end ) as FC,
  tmp.cod_etp,
  tmp.cod_vrs_vet,
  tmp.annee_id || '_' || tmp.cod_etp || '_' || tmp.cod_vrs_vet         as id
from                   tmp
join rgi_autoriser_vet rve on rve.cod_etp = tmp.cod_etp and rve.cod_vrs_vet = tmp.cod_vrs_vet
where rve.tem_en_sve_rve='O'
group by
  tmp.annee_id,
  tmp.libelle,
  tmp.libelle_court,
  tmp.z_type_formation_id,
  tmp.niveau,
  tmp.z_structure_id,
  tmp.cod_etp,
  tmp.cod_vrs_vet,
  tmp.specifique_echanges
;
--
-- Constatation des effectifs par annee par etape et par regime d inscription
--
insert into ose_etape_effectifs
select
  source_code      as z_etape_id,
  cod_anu          as annee_id,
  sum( case when iae.cod_rge not in ('4', '2', '5', '6') then 1 else 0 end ) as effectif_FI,
  sum( case when iae.cod_rge in ('4')           then 1 else 0 end ) as effectif_FA,
  sum( case when iae.cod_rge in ('2', '5', '6') then 1 else 0 end ) as effectif_FC
from ose_etape   etp
join ins_adm_etp iae on iae.cod_anu = etp.annee_id and iae.cod_etp = etp.cod_etp and iae.cod_vrs_vet = etp.cod_vrs_vet
where iae.eta_iae = 'E'
  and iae.eta_pmt_iae = 'P'
group by source_code,
  cod_anu
;
--
-- Table recursive de l offre de formation
-- L offre de formation est reconstituee sous forme d arbre :
--   en ignorant les elements feuilles de nature technique (elements miroirs, elements utiles uniquement pour le calcul de notes...)
--   en remplacant les elements portes par les elements porteurs
--   en recherchant dans la branche la periode ou le temoin A DISTANCE s il n est pas renseigne au niveau de l element pedagogique le plus fin
--
insert into ose_offre_de_formation
with
  annee as (
    select distinct
      etp.annee_id
    from ose_etape etp
  ),
  -- Les elements portes sont ignores au benefice des elements porteurs
  elements_portes as (
    select
      anu.annee_id,
      epo.cod_elp_porte,
      epo.cod_elp_porteur
    from annee             anu
    join elp_porteur_porte epo on epo.cod_anu = anu.annee_id
  ),
  relations as (
    select
      etp.annee_id,
      null                                     as noeud_sup_id,
      null                                     as structure_sup_id,
      null                                     as choix_minimum,
      null                                     as choix_maximum,
      null                                     as liste_id,
      null                                     as type_choix,
      null                                     as libelle_liste,
      null                                     as libelle_court_liste,
      etp.cod_etp || '_' || etp.cod_vrs_vet    as noeud_inf_id,
      etp.z_structure_id                       as structure_inf_id,
      etp.libelle,
      etp.libelle_court,
      'etape'                                  as nature,
      null                                     as cod_pel,
      null                                     as tem_a_dis_elp
    from ose_etape              etp
    union select
      etp.annee_id,
      etp.cod_etp || '_' || etp.cod_vrs_vet    as noeud_sup_id,
      etp.z_structure_id                       as structure_sup_id,
      vrl.nbr_min_elp_obl_chx_vet              as choix_minimum,
      vrl.nbr_max_elp_obl_chx_vet              as choix_maximum,
      '{}' || vrl.cod_lse                      as liste_id,
      lse.cod_typ_lse                          as type_choix,
      lse.lib_lse                              as libelle_liste,
      lse.lic_lse                              as libelle_court_liste,
      elp.cod_elp                              as noeud_inf_id,
      str.cod_str                              as structure_inf_id,
      elp.lib_elp                              as libelle,
      elp.lic_elp                              as libelle_court,
      case when nel.tem_fictif = 'O' then null else elp.cod_nel end as nature,
      -- Determiner les periodes qui relevent du semestre 1, du semestre 2, ou qui sont annuelles
      case
        when elp.cod_pel in ( 'S1', 'S3', 'S5', 'S7', 'S9' ) then 'S1'
        when elp.cod_pel in ( 'S2', 'S4', 'S6', 'S8', '10' ) then 'S2'
        else                                                      null
        end                                    as cod_pel,
    -- 04/10/2019 Mise en coherence entre les heures de type EAD et le flag a_distance : la donnee Apogee tem_a_dis_elp n est plus prise en compte
/*
      case
        when elp.tem_a_dis_elp = 'O' then '1'
        else                              null
        end                                    as tem_a_dis_elp
*/
      null                                     as tem_a_dis_elp
    from ose_etape              etp
    join vet_regroupe_lse       vrl on vrl.cod_etp = etp.cod_etp and vrl.cod_vrs_vet = etp.cod_vrs_vet
    join liste_elp              lse on lse.cod_lse = vrl.cod_lse
    join lse_regroupe_elp       lre on lre.cod_lse = lse.cod_lse
    left join elements_portes   epo on epo.annee_id = etp.annee_id and epo.cod_elp_porte = lre.cod_elp
    join element_pedagogi       elp on elp.cod_elp = nvl ( epo.cod_elp_porteur, lre.cod_elp )
    join nature_elp             nel on nel.cod_nel = elp.cod_nel
    join composante             cmp on cmp.cod_cmp = elp.cod_cmp
    join ucbn_composante_ldap   str on str.cod_cmp = cmp.cod_cmp -- Recherche du code structure dans le referentiel des structures de l etablissement
    where sysdate between nvl ( vrl.dat_cre_rel_lse_vet, sysdate - 1 ) and nvl ( vrl.dat_frm_rel_lse_vet, sysdate + 1 )
      and lse.eta_lse = 'O'
      and elp.eta_elp = 'O'
      and elp.tem_sus_elp = 'N'
    union select
      anu.annee_id,
      erl.cod_elp                              as noeud_sup_id,
      st1.cod_str                              as structure_sup_id,
      erl.nbr_min_elp_obl_chx                  as choix_minimum,
      erl.nbr_max_elp_obl_chx                  as choix_maximum,
      '{}' || erl.cod_lse                      as liste_id,
      lse.cod_typ_lse                          as type_choix,
      lse.lib_lse                              as libelle_liste,
      lse.lic_lse                              as libelle_court_liste,
      elp.cod_elp                              as noeud_inf_id,
      str.cod_str                              as structure_inf_id,
      elp.lib_elp                              as libelle,
      elp.lic_elp                              as libelle_court,
      case when nel.tem_fictif = 'O' then null else elp.cod_nel end as nature,
      case
        when elp.cod_pel in ( 'S1', 'S3', 'S5', 'S7', 'S9' ) then 'S1'
        when elp.cod_pel in ( 'S2', 'S4', 'S6', 'S8', '10' ) then 'S2'
        else                                                      null
        end                                    as cod_pel,
    -- 04/10/2019 Mise en coherence entre les heures de type EAD et le flag a_distance : la donnee Apogee tem_a_dis_elp n est plus prise en compte
/*
      case
        when elp.tem_a_dis_elp = 'O' then '1'
        else                              null
        end                                    as tem_a_dis_elp
*/
      null                                     as tem_a_dis_elp
    from annee                  anu
    cross join elp_regroupe_lse erl
    join element_pedagogi       el1 on el1.cod_elp = erl.cod_elp
    join composante             cm1 on cm1.cod_cmp = el1.cod_cmp
    join ucbn_composante_ldap   st1 on st1.cod_cmp = cm1.cod_cmp
    join liste_elp              lse on lse.cod_lse = erl.cod_lse
    join lse_regroupe_elp       lre on lre.cod_lse = lse.cod_lse
    left join elements_portes   epo on epo.annee_id = anu.annee_id and epo.cod_elp_porte = lre.cod_elp
    join element_pedagogi       elp on elp.cod_elp = nvl ( epo.cod_elp_porteur, lre.cod_elp )
    join nature_elp             nel on nel.cod_nel = elp.cod_nel
    join composante             cmp on cmp.cod_cmp = elp.cod_cmp
    join ucbn_composante_ldap   str on str.cod_cmp = cmp.cod_cmp
    where sysdate between nvl ( erl.dat_cre_rel_lse_elp, sysdate - 1 ) and nvl ( erl.dat_frm_rel_lse_elp, sysdate + 1 )
      and lse.eta_lse = 'O'
      and elp.eta_elp = 'O'
      and elp.tem_sus_elp = 'N'
  ),
  arbre_offre_de_formation as (
    select
      connect_by_root rel.annee_id             as annee_id,
      rel.noeud_sup_id,
      rel.structure_sup_id,
      rel.choix_minimum,
      rel.choix_maximum,
      rel.liste_id,
      rel.type_choix,
      rel.libelle_liste,
      rel.libelle_court_liste,
      rel.noeud_inf_id,
      rel.structure_inf_id,
      rel.libelle,
      rel.libelle_court,
      rel.nature,
      rtrim ( sys_connect_by_path ( case when rel.nature is null then null else rel.noeud_inf_id end,  '>' ), '>' ) as chemin_noeud_inf_id,
      rtrim ( sys_connect_by_path ( rel.cod_pel,       '>' ), '>' ) as chemin_periode,
      -- 04/10/2019 Mise en coherence entre les heures de type EAD et le flag a_distance : la donnee Apogee tem_a_dis_elp n est plus prise en compte
      -- rtrim ( sys_connect_by_path ( rel.tem_a_dis_elp, '>' ), '>' ) as chemin_taux_foad,
      connect_by_isleaf                        as isleaf
    from relations rel
      start with rel.noeud_sup_id is null
      connect by nocycle prior rel.annee_id = rel.annee_id and prior rel.noeud_inf_id = rel.noeud_sup_id
  ),
  branches_inutiles as (
    select distinct
      arb.annee_id,
      arb.noeud_inf_id
    from arbre_offre_de_formation arb
    join arbre_offre_de_formation inu on inu.annee_id = arb.annee_id and inu.chemin_noeud_inf_id = arb.chemin_noeud_inf_id
    where arb.nature is null -- Exclusion des elements techniques (elements miroirs, elements utiles uniquement pour le calcul de notes...)
      and arb.isleaf = 1
      and inu.nature is null
  ),
  arbre_elague as (
    select
      arb.annee_id,
      arb.noeud_sup_id,
      arb.structure_sup_id,
      arb.choix_minimum,
      arb.choix_maximum,
      arb.liste_id,
      arb.type_choix,
      arb.libelle_liste,
      arb.libelle_court_liste,
      arb.noeud_inf_id,
      arb.structure_inf_id,
      arb.libelle,
      arb.libelle_court,
      arb.nature,
      substr ( arb.chemin_periode,   instr ( arb.chemin_periode,   '>', -1 ) + 1 ) as periode
      -- 04/10/2019 Mise en coherence entre les heures de type EAD et le flag a_distance : la donnee Apogee tem_a_dis_elp n est plus prise en compte
      -- ,substr ( arb.chemin_taux_foad, instr ( arb.chemin_taux_foad, '>', -1 ) + 1 ) as taux_foad
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
      max ( arb.periode )                             as periode_max
      -- 04/10/2019 Mise en coherence entre les heures de type EAD et le flag a_distance : la donnee Apogee tem_a_dis_elp n est plus prise en compte
      -- ,max ( to_number ( nvl ( arb.taux_foad, '0') ) ) as taux_foad
    from arbre_elague arb
    group by
      arb.annee_id,
      arb.noeud_inf_id,
      arb.nature
  )
select distinct
  arb.annee_id,
  arb.noeud_sup_id,
  arb.structure_sup_id,
  case arb.type_choix
    when 'O' then null
    when 'F' then 0
    when 'X' then arb.choix_minimum
    end                                        as choix_minimum,
  case arb.type_choix
    when 'O' then null
    when 'F' then arb.choix_maximum
    when 'X' then arb.choix_maximum
    end                                        as choix_maximum,
  arb.liste_id,
  arb.libelle_liste,
  arb.libelle_court_liste,
  arb.noeud_inf_id,
  arb.structure_inf_id,
  arb.libelle,
  arb.libelle_court,
  arb.nature,
  case when uno.periode_min = uno.periode_max
    then uno.periode_min
    else null
    end                                        as periode,
--  uno.taux_foad
  0                                            as taux_foad
from arbre_elague arb
join noeud_unique uno on uno.annee_id = arb.annee_id and uno.noeud_inf_id = arb.noeud_inf_id
order by 1, 2, 6, 9
;
--
-- Chemins pedagogiques
-- Relations entre les etapes (racines) et les elements pedagogiques les plus fins (feuilles)
--
insert into ose_chemin_pedagogique
with etape_enseignement as (
  select distinct
    connect_by_root annee_id                   as annee_id,
    connect_by_root odf.noeud_sup_id           as noeud_sup_id,
    odf.noeud_inf_id,
    odf.periode,
    odf.taux_foad
  from ose_offre_de_formation odf
  where connect_by_isleaf = 1
    start with ( odf.annee_id, odf.noeud_sup_id ) in ( select annee_id, source_code from ose_etape )
    connect by prior odf.annee_id = odf.annee_id and prior odf.noeud_inf_id = odf.noeud_sup_id
  )
select
  annee_id,
  noeud_inf_id                                 as z_element_pedagogique_id,
  noeud_sup_id                                 as z_etape_id,
  noeud_sup_id || '_' || noeud_inf_id          as source_code,
  periode                                      as z_periode_id,
  -- 04/10/2019 Mise en coherence entre les heures de type EAD et le flag a_distance : la donnee Apogee tem_a_dis_elp n est plus prise en compte
  -- max ( taux_foad )                            as taux_foad,
  0                                            as taux_foad,
  annee_id || '_' || noeud_sup_id || '_' || noeud_inf_id as id
from etape_enseignement
/*
group by
  annee_id,
  noeud_sup_id,
  noeud_inf_id,
  periode
*/
;
--
-- Elements pedagogiques
-- Un element pedagogique OSE est assimilable a un element pedagogique feuille d une structure d enseignements Apogee
-- Si un element est commun a plusieurs etapes il sera ici associe a une etape de reference
--
insert into ose_element_pedagogique
select
  odf.annee_id,
  odf.libelle,
-- Si un element est commun a plusieurs etapes il sera ici associe a une etape de reference en fonction des criteres suivants
--    critere 1 : prise en compte de la VET porteuse definie dans Apogee
--                ( si la VET porteuse n est pas definie pour l annee consideree alors on prend en compte la VET porteuse definie pour ANN_CHARGES )
--                sous reserve que cette VET porteuse soit ouverte pour l annee retenue
--    critere 2 : les etapes dediees aux echanges sont non prioritaires
--    critere 3 : ordre alphabetique du code etape
  min ( chp.z_etape_id ) keep ( dense_rank first order by
    case when chp.z_etape_id = case when chp.annee_id >= vap.par_vap then prt.source_code else nvl ( prt.source_code, pr2.source_code ) end then 0 else 1 end,
    etp.specifique_echanges,
    chp.z_etape_id
    )                                          as z_etape_id,
  odf.structure_inf_id                         as z_structure_id,
  chp.z_periode_id,
  chp.taux_foad,
  chp.z_element_pedagogique_id                 as source_code,
  nvl ( ece.cod_scc, ec2.cod_scc )             as z_discipline_id,
  max ( etp.FI )                               as FI,
  max ( etp.FA )                               as FA,
  max ( etp.FC )                               as FC,
  odf.annee_id || '_' || chp.z_element_pedagogique_id as id
from ose_offre_de_formation    odf
join ose_chemin_pedagogique    chp on chp.annee_id = odf.annee_id and chp.z_element_pedagogique_id = odf.noeud_inf_id
join ose_etape                 etp on etp.annee_id = chp.annee_id and etp.source_code = chp.z_etape_id
-- Recherche de la VET porteuse pour l annee consideree
left outer join elp_charge_ens ece on ece.cod_anu  = odf.annee_id and ece.cod_elp     = odf.noeud_inf_id
left outer join ose_etape      prt on prt.annee_id = ece.cod_anu  and prt.source_code = ece.cod_etp_porteuse || '_' || ece.cod_vrs_vet_porteuse
-- Recherche de la VET porteuse pour l annee de reference des charges = ANN_CHARGES
cross join variable_appli      vap
left outer join elp_charge_ens ec2 on ec2.cod_anu  = vap.par_vap  and ec2.cod_elp     = odf.noeud_inf_id
left outer join ose_etape      pr2 on pr2.annee_id = ec2.cod_anu  and pr2.source_code = ec2.cod_etp_porteuse || '_' || ec2.cod_vrs_vet_porteuse
where vap.cod_vap = 'ANN_CHARGES'
group by
  odf.annee_id,
  odf.libelle,
  odf.structure_inf_id,
  chp.z_periode_id,
  chp.taux_foad,
  chp.z_element_pedagogique_id,
  nvl ( ece.cod_scc, ec2.cod_scc )
;
--
-- Constatation des effectifs par annee par element pedagogique et par regime d inscription
--
insert into ose_element_effectifs
with tmp_element_effectifs as (
  select
    elp.source_code,
    ice.cod_anu,
    case when iae.cod_rge not in ('4', '2', '5', '6') then 1 else 0 end as effectif_FI,
    case when iae.cod_rge in ('4')           then 1 else 0 end as effectif_FA,
    case when iae.cod_rge in ('2', '5', '6') then 1 else 0 end as effectif_FC
  from ose_element_pedagogique elp
  join ind_contrat_elp ice   on  ice.cod_elp = elp.source_code
                             and ice.cod_anu = elp.annee_id
  join ins_adm_etp iae       on  iae.cod_ind = ice.cod_ind
                             and iae.cod_anu = ice.cod_anu
                             and iae.cod_etp = ice.cod_etp
                             and iae.cod_vrs_vet = ice.cod_vrs_vet
  where elp.annee_id in ( to_char(add_months(sysdate, -28), 'YYYY') , to_char(add_months(sysdate, -16), 'YYYY') , to_char(add_months(sysdate,  -4), 'YYYY') )
    and ice.tem_prc_ice = 'N'
    and iae.eta_iae = 'E'
    and iae.eta_pmt_iae = 'P'
-- Ajout des effectifs des elements portes
  union all
  select
    elp.source_code,
    ice.cod_anu,
    case when iae.cod_rge not in ('4', '2', '5', '6') then 1 else 0 end as effectif_FI,
    case when iae.cod_rge in ('4')           then 1 else 0 end as effectif_FA,
    case when iae.cod_rge in ('2', '5', '6') then 1 else 0 end as effectif_FC
  from ose_element_pedagogique elp
  join elp_porteur_porte epo on  epo.cod_elp_porteur = elp.source_code
                             and epo.cod_anu = elp.annee_id
  join ind_contrat_elp ice   on  ice.cod_elp = epo.cod_elp_porte
                             and ice.cod_anu = epo.cod_anu
  join ins_adm_etp iae       on  iae.cod_ind = ice.cod_ind
                             and iae.cod_anu = ice.cod_anu
                             and iae.cod_etp = ice.cod_etp
                             and iae.cod_vrs_vet = ice.cod_vrs_vet
  where elp.annee_id in ( to_char(add_months(sysdate, -28), 'YYYY') , to_char(add_months(sysdate, -16), 'YYYY') , to_char(add_months(sysdate,  -4), 'YYYY') )
    and ice.tem_prc_ice = 'N'
    and iae.eta_iae = 'E'
    and iae.eta_pmt_iae = 'P'
  )
select
  source_code      as z_element_pedagogique_id,
  cod_anu          as annee_id,
  sum(effectif_FI) as effectif_FI,
  sum(effectif_FA) as effectif_FA,
  sum(effectif_FC) as effectif_FC
from tmp_element_effectifs
group by source_code,
  cod_anu
;
--
-- Volumes horaires et nombre de groupes ouverts pour chaque enseignement, par type de groupe
-- Le rapprochement, entre les volumes horaires definis dans le module Charges et les groupes ouverts, se fait sur le code type d heures = le code type de groupe
-- Si aucun groupe n est modelise pour un element pedagogique alors on considere qu il existe un groupe unique d etudiants pour cet element
-- Cas particulier des enseignements de type Mémoire, Projet, Stage : si aucun groupe n est modelise alors on considere qu il existe autant de groupes que d etudiants inscrits a l element
--
insert into ose_volume_horaire_ens
with elp_groupes as (
  select
    elp.annee_id,
    elp.source_code,
    gpe.cod_tgr,
    count ( distinct iag.cod_gpe ) as groupes
  from ose_element_pedagogique elp
  join gpe_obj                 gpo on gpo.typ_obj_gpo = 'ELP' and gpo.cod_elp = elp.source_code
  join groupe                  gpe on gpe.cod_gpe = gpo.cod_gpe
  join ind_affecte_gpe         iag on iag.cod_gpe = gpo.cod_gpe and iag.cod_anu = elp.annee_id
  where elp.annee_id between nvl ( gpe.daa_deb_val_gpe, '0000' ) and nvl ( gpe.daa_fin_val_gpe, '9999' )
  group by
    elp.annee_id,
    elp.source_code,
    gpe.cod_tgr
  )
-- Recherche des charges pour les annees <= ANN_CHARGES
select
  elp.annee_id,
  elp.source_code                              as z_element_pedagogique_id,
  ect.cod_typ_heu                              as z_type_intervention_id,
  case when ect.cod_typ_heu in ( 'MEMOIR', 'PROJET', 'STAGE', 'SORTIE' ) -- 04/10/2019 Plafonnement de la charge d enseignement liee a l'encadrement individuel a 1 heure par etudiant
    then least ( ect.nbr_heu_elp, 1 )
    else ect.nbr_heu_elp
    end                                        as heures,
  elp.source_code || '_' || ect.cod_typ_heu    as source_code,
  elp.annee_id || '_' || elp.source_code || '_' || ect.cod_typ_heu as id,
  case when nvl ( eff.effectif_FI, 0 ) + nvl (eff.effectif_FA, 0 ) + nvl (eff.effectif_FC, 0 ) > 0
    then case when ect.cod_typ_heu in ( 'MEMOIR', 'PROJET', 'STAGE', 'SORTIE' )
      then nvl ( elg.groupes, nvl ( eff.effectif_FI, 0 ) + nvl (eff.effectif_FA, 0 ) + nvl (eff.effectif_FC, 0 ) )
      else nvl ( elg.groupes, 1 )
      end
    else 0
    end                                        as groupes
from            ose_element_pedagogique elp
left outer join ose_element_effectifs   eff on eff.annee_id  = elp.annee_id and eff.z_element_pedagogique_id = elp.source_code
join            elp_chg_typ_heu         ect on ect.cod_anu   = elp.annee_id and ect.cod_elp = elp.source_code
left outer join elp_groupes             elg on elg.annee_id  = ect.cod_anu  and elg.source_code = ect.cod_elp and elg.cod_tgr = ect.cod_typ_heu
where nvl ( ect.nbr_heu_elp, 0 ) > 0
union
-- Recherche des charges pour les annees > ANN_CHARGES
-- si pas de charges definies alors on prend en compte les charges definies pour l annee ANN_CHARGES
select
  elp.annee_id,
  elp.source_code                              as z_element_pedagogique_id,
  ann.cod_typ_heu                              as z_type_intervention_id,
  case when ann.cod_typ_heu in ( 'MEMOIR', 'PROJET', 'STAGE', 'SORTIE' ) -- 04/10/2019 Plafonnement de la charge d enseignement liee a l'encadrement individuel a 1 heure par etudiant
    then least ( ann.nbr_heu_elp, 1 )
    else ann.nbr_heu_elp
    end                                        as heures,
  elp.source_code || '_' || ann.cod_typ_heu    as source_code,
  elp.annee_id || '_' || elp.source_code || '_' || ann.cod_typ_heu as id,
  case when nvl ( eff.effectif_FI, 0 ) + nvl (eff.effectif_FA, 0 ) + nvl (eff.effectif_FC, 0 ) > 0
    then case when ann.cod_typ_heu in ( 'MEMOIR', 'PROJET', 'STAGE', 'SORTIE' )
      then nvl ( elg.groupes, nvl ( eff.effectif_FI, 0 ) + nvl (eff.effectif_FA, 0 ) + nvl (eff.effectif_FC, 0 ) )
      else nvl ( elg.groupes, 1 )
      end
    else 0
    end                                        as groupes
from            variable_appli          vap
join            ose_element_pedagogique elp on elp.annee_id  > vap.par_vap
left outer join ose_element_effectifs   eff on eff.annee_id  = elp.annee_id and eff.z_element_pedagogique_id = elp.source_code
left outer join elp_chg_typ_heu         ect on ect.cod_anu   = elp.annee_id and ect.cod_elp = elp.source_code
left outer join elp_chg_typ_heu         ann on ann.cod_anu   = vap.par_vap  and ann.cod_elp = elp.source_code
left outer join elp_groupes             elg on elg.annee_id  = ann.cod_anu  and elg.source_code = ann.cod_elp and elg.cod_tgr = ann.cod_typ_heu
where vap.cod_vap = 'ANN_CHARGES'
  and ect.cod_elp is null
  and nvl ( ann.nbr_heu_elp, 0 ) > 0
;
--
-- 04/10/2019 Mise en coherence entre les heures de type EAD et le flag a_distance : s il existe une charge EAD alors TAUX_FOAD = 1 sinon laisser 0
--
merge into ose_element_pedagogique elp
using (
  select
    annee_id,
    z_element_pedagogique_id
  from ose_volume_horaire_ens
  where z_type_intervention_id = 'EAD'
  ) tmp
on ( elp.annee_id = tmp.annee_id and elp.source_code = tmp.z_element_pedagogique_id )
when matched then update set elp.taux_foad = 1
;
merge into ose_chemin_pedagogique chp
using (
  select
    annee_id,
    z_element_pedagogique_id
  from ose_volume_horaire_ens
  where z_type_intervention_id = 'EAD'
  ) tmp
on ( chp.annee_id = tmp.annee_id and chp.z_element_pedagogique_id = tmp.z_element_pedagogique_id )
when matched then update set chp.taux_foad = 1
;
merge into ose_offre_de_formation odf
using (
  select
    annee_id,
    z_element_pedagogique_id
  from ose_volume_horaire_ens
  where z_type_intervention_id = 'EAD'
  ) tmp
on ( odf.annee_id = tmp.annee_id and odf.noeud_inf_id = tmp.z_element_pedagogique_id )
when matched then update set odf.taux_foad = 1
;