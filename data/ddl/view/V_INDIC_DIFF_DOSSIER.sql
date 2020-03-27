CREATE OR REPLACE FORCE VIEW V_INDIC_DIFF_DOSSIER AS
WITH aa AS (
  SELECT a.*, ose_divers.formatted_adresse(
      a.NO_VOIE,
      a.NOM_VOIE,
      a.BATIMENT,
      a.MENTION_COMPLEMENTAIRE,
      a.LOCALITE,
      a.CODE_POSTAL,
      a.VILLE,
      a.PAYS_LIBELLE) to_string FROM adresse_intervenant a
)
select
    i.id,
    i.nom_usuel,
    case when d.adresse <> a.to_string                                              then d.adresse                            else null end adresse_dossier,
    case when d.adresse <> a.to_string                                              then a.to_string                          else null end adresse_import,
    case when d.rib <> REPLACE(i.BIC || '-' || i.IBAN, ' ')                         then d.rib                                else null end rib_dossier,
    case when d.rib <> REPLACE(i.BIC || '-' || i.IBAN, ' ')                         then REPLACE(i.BIC || '-' || i.IBAN, ' ') else null end rib_import,
    case when UPPER(REPLACE(d.nom_usuel, ' ')) <> UPPER(REPLACE(i.nom_usuel, ' '))  then REPLACE(d.nom_usuel, ' ')            else null end nom_usuel_dossier,
    case when UPPER(REPLACE(d.nom_usuel, ' ')) <> UPPER(REPLACE(i.nom_usuel, ' '))  then REPLACE(i.nom_usuel, ' ')            else null end nom_usuel_import,
    case when UPPER(REPLACE(d.prenom, ' ')) <> UPPER(REPLACE(i.prenom, ' '))        then REPLACE(d.prenom, ' ')               else null end prenom_dossier,
    case when UPPER(REPLACE(d.prenom, ' ')) <> UPPER(REPLACE(i.prenom, ' '))        then REPLACE(i.prenom, ' ')               else null end prenom_import
  from intervenant i
  join dossier d on d.intervenant_id = i.id
  left join aa a on a.intervenant_id = i.id