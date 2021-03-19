create or replace force view src_structure as
 with 
siham_uo_query as
(select
  source_code code,
  libelle_court,
  libelle_long,
  source_id,
  source_code
from um_structure)
, siham_adr_uo_qry as
(
	select 
		  ads.localite			adresse_precisions
		, ads.no_voie       	adresse_numero
		, anc.id				adresse_numero_compl_id
		, vr.id             	adresse_voirie_id
		, ads.nom_voie      	adresse_voie
		, null             		adresse_lieu_dit
		, ads.code_postal  		adresse_code_postal
		, ads.ville      		adresse_commune
		, ads.pays_code_insee	z_adresse_pays_id
		, ads.source_code	 	uo_adr
	from um_adresse_structure ads
	    ,voirie vr
      ,um_voirie uvr
      ,adresse_numero_compl anc
	where uvr.code =  ads.voirie_code
    and vr.source_code(+) = uvr.source_code
	  and anc.code(+) = ads.numero_compl_code
)
select
    uo.code
  , uo.libelle_court
  , uo.libelle_long
  , uoa.adresse_precisions
  , uoa.adresse_numero
  , uoa.adresse_numero_compl_id
  , uoa.adresse_voirie_id
  , uoa.adresse_voie
  , uoa.adresse_lieu_dit
  , uoa.adresse_code_postal
  , uoa.adresse_commune
  , py.id z_adresse_pays_id
  , uo.source_id
  , uo.source_code
from siham_uo_query uo
    ,siham_adr_uo_qry uoa
	,pays py
where uoa.uo_adr = uo.code
  and py.source_code = uoa.z_adresse_pays_id;