select anu.cod_anu                                             annee_id
     , elp.code                                                z_element_pedagogique_id
     , concat_ws('_' ,etp.code ,convert(vti.code using utf8) ) z_etape_porteuse_id
     , elp.nel
     , elp.nb_choix
     , case elp.exported
          when true then 'O'
          else 'N'
       end tem_exported
     , case elp.lse_exported
          when true then 'O'
          else 'N'
       end tem_lse_exported
     , case elp.lien_lse_exported
          when true then 'O'
          else 'N'
       end tem_lien_lse_exported
from PREV_PROJET anu
 , PREV_VERSION_ETAPE vet
 , PREV_ETAPE etp
 , PREV_VERSION_DIPLOME vdi
 , PREV_DIPLOME dip
 , PREV_ELEMENT_PEDAGOGI elp
 , PREV_VET_TYPINS vti
where anu.cod_anu = :v_annee
  and anu.temoin_actif = 1
  and (
		(((elp.commun = true)
		and (elp.prev_elp_reference_id is null))
		and (dip.prev_projet_id = anu.cod_anu))
		and ((((vet.id = elp.prev_vet_id)
		and (etp.id = vet.prev_etape_id))
		and (vdi.id = etp.prev_version_diplome_id))
		and (dip.id = vdi.prev_diplome_id))
  )
  and vti.prev_vet_id = vet.id
and elp.code is not null