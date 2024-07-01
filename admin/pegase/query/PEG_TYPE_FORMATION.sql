select distinct
typedip.libelle_long LIBELLE_LONG,
typedip.libelle_court LIBELLE_COURT,
'Pegase' SOURCE_ID,
typedip.code SOURCE_CODE
from schema_ref.type_diplome typedip
order by typedip.code

