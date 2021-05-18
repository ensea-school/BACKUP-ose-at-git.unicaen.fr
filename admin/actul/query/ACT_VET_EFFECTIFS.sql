select distinct
      anu.cod_anu                      as annee_id,
	  concat_ws('_', ltrim(rtrim(etp.code)), cast(vti.code as char(255))) as etape_id,
	  concat_ws('_', anu.cod_anu, ltrim(rtrim(etp.code)), cast(vti.code as char(255))) as etp_source_code,
	  ifnull(typ_ins.fi * eff_prev,0) as eff_etp_fi,
	  ifnull(typ_ins.fa * eff_prev,0) as eff_etp_fa,
	  ifnull(typ_ins.fc * eff_prev,0) as eff_etp_fc
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
    where anu.cod_anu =:v_annee
	  and anu.temoin_actif = 1
  order by
      anu.cod_anu,
      vde.libelle ,
      etp.libelle_court,
      tpd.typ_dip_apo ,
	  etp.code,
	  tpd.cod_nature_diplome