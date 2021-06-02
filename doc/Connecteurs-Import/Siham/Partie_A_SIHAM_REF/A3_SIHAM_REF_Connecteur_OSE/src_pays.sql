create or replace force view src_pays as
  select
  libelle_long libelle,
  decode(temoin_ue, 'O', 1, 0) temoin_ue,
  validite_debut,
  validite_fin,
  source_id,
  source_code,
  source_code code
from
  um_pays p;
