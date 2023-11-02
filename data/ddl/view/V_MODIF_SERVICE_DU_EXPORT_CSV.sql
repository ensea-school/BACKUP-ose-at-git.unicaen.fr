CREATE OR REPLACE FORCE VIEW V_MODIF_SERVICE_DU_EXPORT_CSV AS
SELECT
  i.annee_id                      annee_id,
  i.id                            intervenant_id,
  s.id                            structure_id,
  s.ids                           structure_ids,

  a.libelle                       annee,
  s.libelle_court                 structure_libelle,

  i.code                          intervenant_code,
  i.nom_usuel                     intervenant_nom_usuel,
  i.nom_patronymique              intervenant_nom_patronymique,
  i.prenom                        intervenant_prenom,
  si.libelle                      intervenant_statut_libelle,
  si.service_statutaire           intervenant_service_statutaire,

  mss.code                        motif_code,
  mss.libelle                     motif_libelle,

  msd.heures * mss.multiplicateur heures,

  msd.commentaires                commentaires,
  u.display_name                  modificateur,
  msd.histo_modification          date_modification
FROM
       modification_service_du    msd
  JOIN intervenant                  i ON i.id = msd.intervenant_id
  JOIN annee                        a ON a.id = i.annee_id
  JOIN structure                    s ON s.id = i.structure_id
  JOIN statut                      si ON si.id = i.statut_id
  JOIN motif_modification_service mss ON mss.id = msd.motif_id
  JOIN utilisateur                  u ON u.id = msd.histo_modificateur_id
WHERE
  msd.histo_destruction IS NULL
  AND i.histo_destruction IS NULL
ORDER BY
  annee_id,
  structure_libelle,
  intervenant_nom_usuel,
  motif_libelle,
  heures