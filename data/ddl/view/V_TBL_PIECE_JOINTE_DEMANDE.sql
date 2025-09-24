CREATE OR REPLACE FORCE VIEW V_TBL_PIECE_JOINTE_DEMANDE AS
WITH i_h AS (
  SELECT
    s.intervenant_id,
    SUM(CASE WHEN vh.MOTIF_NON_PAIEMENT_ID IS NULL THEN vh.heures ELSE 0 END) heures,
    SUM(CASE WHEN vh.MOTIF_NON_PAIEMENT_ID IS NOT NULL THEN vh.heures ELSE 0 END) heures_non_payables,
    SUM(CASE WHEN ep.taux_fc > 0 THEN vh.heures ELSE 0 END) fc,
    SUM(CASE WHEN ep.taux_fa > 0 THEN vh.heures ELSE 0 END) fa
  FROM
         service               s
    JOIN type_volume_horaire tvh ON tvh.code = 'PREVU'
    JOIN volume_horaire       vh ON vh.service_id = s.id
                                AND vh.type_volume_horaire_id = tvh.id
                                AND vh.histo_destruction IS NULL
    JOIN element_pedagogique  ep ON ep.id = s.element_pedagogique_id -- Service sur l'établissement
  WHERE
    s.histo_destruction IS NULL
    /*@INTERVENANT_ID=s.intervenant_id*/
  GROUP BY
    s.intervenant_id
),
hetd AS (
  SELECT
    intervenant_id,
    SUM(total) AS total_hetd
  FROM
    formule_resultat_intervenant   fr
  JOIN type_volume_horaire tvh ON tvh.id = fr.type_volume_horaire_id
  JOIN etat_volume_horaire evh ON evh.id = fr.etat_volume_horaire_id
    WHERE
    tvh.code = 'PREVU' AND evh.code = 'saisi'
  GROUP BY
    intervenant_id
)
SELECT i.annee_id                        annee_id,
       i.code                            code_intervenant,
       i.id                              intervenant_id,
       tpj.id                            type_piece_jointe_id,
       MAX(COALESCE(i_h.heures, 0))      heures_pour_seuil,
       MAX(tpjs.obligatoire)             obligatoire,
       MAX(COALESCE(hetd.total_hetd, 0)) heures_pour_seuil_hetd,
       MIN(tpjs.duree_vie)               duree_vie
FROM intervenant i
         LEFT JOIN intervenant_dossier d ON d.intervenant_id = i.id AND d.histo_destruction IS NULL
         JOIN type_piece_jointe_statut tpjs
              ON tpjs.statut_id = i.statut_id AND tpjs.histo_destruction IS NULL AND i.annee_id = tpjs.annee_id
         JOIN type_piece_jointe tpj ON tpj.id = tpjs.type_piece_jointe_id AND tpj.histo_destruction IS NULL
         LEFT JOIN i_h ON i_h.intervenant_id = i.id
         LEFT JOIN hetd ON hetd.intervenant_id = i.id
WHERE i.histo_destruction IS NULL
    /*@INTERVENANT_ID=i.id*/
    /*@ANNEE_ID=i.annee_id*/

  -- Seuil heure soit en HETD soit en heure ou PJ obligatoire meme avec des heures non payables
  AND (
            COALESCE(tpjs.seuil_hetd, 0) = 0
        OR (COALESCE(tpjs.type_heure_hetd, 0) = 0 AND COALESCE(i_h.heures, 0) > COALESCE(tpjs.seuil_hetd, -1))
        OR (tpjs.type_heure_hetd = 1 AND COALESCE(hetd.total_hetd, 0) > COALESCE(tpjs.seuil_hetd, -1))
        OR (COALESCE(i_h.heures_non_payables, 0) > 0 AND tpjs.obligatoire_hnp = 1)
    )

  -- Le RIB n'est demandé QUE s'il est différent!!
  AND CASE
          WHEN tpjs.changement_rib = 0 OR d.id IS NULL
              THEN 1
          ELSE CASE
                   WHEN
                               REPLACE(i.bic, ' ', '') = REPLACE(d.bic, ' ', '')
                           AND REPLACE(i.iban, ' ', '') = REPLACE(d.iban, ' ', '')
                       THEN 0
                   ELSE 1 END
          END = 1
   -- Demandé uniquement si nationalité étrangère
   AND CASE
      WHEN tpjs.nationalite_etrangere = 0 OR d.id IS NULL
      THEN 1
    ELSE CASE
        WHEN
          d.pays_nationalite_id = (SELECT MAX(id) FROM pays p WHERE libelle IN ('France','FRANCE') AND histo_destruction IS NULL GROUP BY id)
        THEN 0
        ELSE 1 END
      END = 1
  -- Filtre FC
  AND (tpjs.fc = 0 OR i_h.fc > 0)
-- Filtre FA
  AND (tpjs.fa = 0 OR i_h.fa > 0)
GROUP BY i.annee_id,
         i.id,
         i.code,
         tpj.id