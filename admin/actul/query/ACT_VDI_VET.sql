select anu.cod_anu  annee_id,
       concat(ltrim(rtrim(dip.code)),'_',vdi.code) code_vdi,
       concat(ltrim(rtrim(etp.code)),'_',tin.code) code_vet
from PREV_DIPLOME dip
   , PREV_VERSION_DIPLOME vdi
   , PREV_ETAPE etp
   , PREV_VERSION_ETAPE vet
   , PREV_VET_TYPINS tin
   , PREV_PROJET anu
where vdi.prev_diplome_id = dip.id
  and etp.prev_version_diplome_id = vdi.id
  and vet.prev_etape_id = etp.id
  and tin.prev_vet_id = vet.id
  and anu.cod_anu = :v_annee
  and anu.temoin_actif = 1
order by 1,2,3