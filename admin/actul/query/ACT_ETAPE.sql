SELECT
    anu.cod_anu                      as annee_id,
    vde.libelle     					   	   as libelle,
    etp.libelle_court                as libelle_court,
	  tpd.typ_dip_apo                  as z_type_formation_id,
	  vde.vdi_vet_annee_min            as niveau,
    null                             as z_structure_id,
    etp.cod_cmp,
	  concat_ws('_', ltrim(rtrim(etp.code)), cast(vti.code as char(255))) as source_code,
    0                                as specifique_echanges ,
    'D999'                           as domaine_fonctionnel,
	  typ_ins.fi                       as fi,
	  typ_ins.fa                       as fa,
	  typ_ins.fc                       as fc,
    etp.code                         as cod_etp,
    cast(vti.code as char(255))      as cod_vrs_vet,
	  concat_ws('_', anu.cod_anu, ltrim(rtrim(etp.code)), cast(vti.code as char(255))) as id,
	  tpd.cod_nature_diplome           as tem_dn,
    vde.statut                       as statut_actul,
	  case etp.exported
		when true then 'O'
		else 'N'
	  end                                 tem_exported
  , etp.cod_pty_apo
  , etp.prem_sem
  , etp.cod_cge
  , etp.droit_bourse
  , vde.libelle
  , vde.cod_coll
  , vde.cod_duree_etape
  , vde.vdi_vet_annee_min
  , vde.vdi_vet_annee_max
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
where anu.cod_anu = :v_annee
  and anu.temoin_actif = 1
order by
  anu.cod_anu,
  vde.libelle ,
  etp.libelle_court,
  tpd.typ_dip_apo ,
  etp.code,
  tpd.cod_nature_diplome