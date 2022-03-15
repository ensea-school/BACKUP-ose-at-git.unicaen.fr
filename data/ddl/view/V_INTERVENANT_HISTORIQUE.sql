CREATE
OR REPLACE FORCE VIEW V_INTERVENANT_HISTORIQUE AS
WITH historique AS(
SELECT
  d.intervenant_id   intervenant_id,
  ''Données personnelles''                         categorie,
  ''Saisie des données personnelles''              label,
  d.histo_modification                          histo_date,
  d.histo_createur_id                              histo_createur_id,
  u.display_name                       histo_user,
  ''glyphicon glyphicon-ok'' icon,
  1                        ordre
FROM
  intervenant_dossier d
  JOIN utilisateur u ON u.id = d.histo_createur_id
  WHERE d.histo_destruction IS NULL

  UNION ALL
--Validation des données personnelles
SELECT
  d.intervenant_id   intervenant_id,
  ''Données personnelles''                         categorie,
  ''Validation des données personnelles''              label,
  v.histo_creation                     histo_date,
v.histo_createur_id                       histo_createur_id,
  u.display_name                       histo_user,
  ''glyphicon glyphicon-ok'' icon,
  1                        ordre
FROM
  validation v
  JOIN utilisateur u ON u.id = v.histo_createur_id
  JOIN type_validation tv ON tv.id = v.type_validation_id AND tv.code = ''DONNEES_PERSO_PAR_COMP''
  JOIN intervenant_dossier d ON d.histo_destruction IS NULL AND d.intervenant_id = v.intervenant_id

  UNION ALL

 SELECT
  pj.intervenant_id   intervenant_id,
  ''Pièces justificatives''		                   categorie,
  ''Validation pièce justificative : '' || tpj.libelle               label,
  v.histo_creation           histo_date,
  v.histo_createur_id        histo_createur_id,
  u.display_name     histo_user,
  ''glyphicon glyphicon-ok'' icon,
  2                        ordre
FROM
  validation v
  JOIN utilisateur u ON u.id = v.histo_createur_id
  JOIN piece_jointe pj ON pj.validation_id = v.id
  JOIN type_piece_jointe tpj ON pj.type_piece_jointe_id = tpj.id

  UNION ALL

SELECT pj.intervenant_id                                      intervenant_id,
	   ''Pièces justificatives''		                   categorie,
       ''Dépôt pièce justificative : '' || tpj.libelle          label,
       pj.histo_creation                                      histo_date,
       pj.histo_createur_id                                   histo_createur_id,
       u.display_name                                         histo_user,
       ''glyphicon glyphicon-ok''                               icon,
       2													  ordre
FROM piece_jointe pj
         JOIN type_piece_jointe tpj ON pj.type_piece_jointe_id = tpj.id
         JOIN utilisateur u ON u.id = pj.histo_createur_id
WHERE pj.histo_destruction IS NULL

UNION ALL

SELECT s.intervenant_id                                                               intervenant_id,
	   ''Services prévisionnels           		''		                   categorie,
       ''Saisi de service prévisionnel : '' || vh.heures || '' heures '' || ti.code || '', '' || p.libelle_court ||
       CASE WHEN mnp.id IS NULL THEN '''' ELSE '' (NP: '' || mnp.libelle_court || '')'' END label,
       vh.histo_creation                                                 histo_date,
       vh.histo_createur_id                                              histo_createur_id,
       u.display_name                                                    histo_user,
       ''glyphicon glyphicon-calendar''                                    icon,
       3																 ordre
FROM volume_horaire vh
         JOIN service s ON s.id = vh.service_id
         JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id AND tvh.code = ''PREVU''
         JOIN periode p ON p.id = vh.periode_id
         JOIN type_intervention ti ON ti.id = vh.type_intervention_id
         JOIN utilisateur u ON u.id = vh.histo_createur_id
         LEFT JOIN motif_non_paiement mnp ON mnp.id = vh.motif_non_paiement_id )
SELECT rownum id,
       categorie,
       ordre,
       intervenant_id,
       label,
       histo_date,
       histo_createur_id,
       histo_user,
       icon
FROM historique
ORDER BY intervenant_id, ordre ASC, histo_date ASC