CREATE OR REPLACE FORCE VIEW V_EXPORT_DMEP AS
WITH tbl_p AS (
  SELECT
    tp.mise_en_paiement_id,
    MAX(tp.domaine_fonctionnel_id) AS domaine_fonctionnel_id
  FROM
    tbl_paiement tp
  WHERE
    tp.domaine_fonctionnel_id IS NOT NULL
  GROUP BY
    tp.mise_en_paiement_id
),
mep AS (
  SELECT
    mep.id AS mise_en_paiement_id,
    mep.service_id,
    mep.service_referentiel_id,
    mep.date_mise_en_paiement,
    mep.periode_paiement_id,
    mep.centre_cout_id,

    /*
     * Priorité au domaine présent dans TBL_PAIEMENT.
     * Si aucun domaine n'est présent dans TBL_PAIEMENT,
     * on conserve celui de MISE_EN_PAIEMENT.
     */
    COALESCE(
      tbl_p.domaine_fonctionnel_id,
      mep.domaine_fonctionnel_id
    ) AS domaine_fonctionnel_id,

    SUM(CASE WHEN th.code = 'fi'
             THEN mep.heures ELSE 0 END) AS heures_fi,

    SUM(CASE WHEN th.code = 'fa'
             THEN mep.heures ELSE 0 END) AS heures_fa,

    SUM(CASE WHEN th.code = 'fc'
             THEN mep.heures ELSE 0 END) AS heures_fc,

    SUM(CASE WHEN th.code = 'primes'
             THEN mep.heures ELSE 0 END) AS heures_primes,

    SUM(CASE WHEN th.code = 'referentiel'
             THEN mep.heures ELSE 0 END) AS heures_referentiel

  FROM
    mise_en_paiement mep

    JOIN type_heures th
      ON th.id = mep.type_heures_id

    LEFT JOIN tbl_p tbl_p
      ON tbl_p.mise_en_paiement_id = mep.id

  WHERE
    mep.histo_destruction IS NULL

  GROUP BY
    mep.id,
    mep.service_id,
    mep.service_referentiel_id,
    mep.date_mise_en_paiement,
    mep.periode_paiement_id,
    mep.centre_cout_id,
    COALESCE(
      tbl_p.domaine_fonctionnel_id,
      mep.domaine_fonctionnel_id
    )
)
SELECT
    i.id                                                       AS intervenant_id,
    i.code_rh                                                  AS code_rh,
    ti.id                                                      AS type_intervenant_id,
    i.annee_id                                                 AS annee_id,
    saff.id                                                    AS structure_aff_id,
    sens.id                                                    AS structure_ens_id,
    COALESCE(sens.id, saff.id)                                 AS structure_id,
    saff.ids                                                   AS structure_aff_ids,
    sens.ids                                                   AS structure_ens_ids,
    COALESCE(sens.ids, saff.ids)                               AS structure_ids,
    cc.id                                                      AS centre_cout_id,
    ep.id                                                      AS element_pedagogique_id,
    etp.id                                                     AS etape_id,
    tf.id                                                       AS type_formation_id,
    gtf.id                                                      AS groupe_type_formation_id,
    si.id                                                       AS statut_id,
    p.id                                                        AS periode_id,

    i.source_code                                               AS intervenant_code,
    i.nom_usuel || ' ' || i.prenom                              AS intervenant_nom,
    i.date_naissance                                            AS intervenant_date_naissance,
    si.libelle                                                  AS intervenant_statut_libelle,
    ti.code                                                     AS intervenant_type_code,
    ti.libelle                                                  AS intervenant_type_libelle,
    g.source_code                                               AS intervenant_grade_code,
    g.libelle_court                                             AS intervenant_grade_libelle,
    di.source_code                                              AS intervenant_discipline_code,
    di.libelle_court                                            AS intervenant_discipline_libelle,
    saff.libelle_court                                          AS service_structure_aff_libelle,

    sens.libelle_court                                          AS service_structure_ens_libelle,
    etab.libelle                                                AS etablissement_libelle,
    gtf.libelle_court                                           AS groupe_type_formation_libelle,
    tf.libelle_court                                            AS type_formation_libelle,
    etp.niveau                                                  AS etape_niveau,
    etp.code                                                    AS etape_code,
    etp.libelle                                                 AS etape_libelle,
    ep.code                                                     AS element_code,
    ep.libelle                                                  AS element_libelle,
    de.source_code                                              AS element_discipline_code,
    de.libelle_court                                            AS element_discipline_libelle,
    fr.libelle_long                                             AS fonction_referentiel_libelle,
    ep.taux_fi                                                  AS element_taux_fi,
    ep.taux_fc                                                  AS element_taux_fc,
    ep.taux_fa                                                  AS element_taux_fa,
    src.libelle                                                 AS element_source_libelle,
    COALESCE(
            TO_CHAR(s.description),
            TO_CHAR(sr.commentaires)
    )                                                           AS commentaires,

    CASE
        WHEN mep.date_mise_en_paiement IS NULL
            THEN 'a-mettre-en-paiement'
        ELSE 'mis-en-paiement'
        END                                                         AS etat,

    tr.libelle                                                  AS type_ressource_libelle,
    cc.source_code                                              AS centre_couts_code,
    cc.libelle                                                  AS centre_couts_libelle,

    df.source_code                                              AS domaine_fonctionnel_code,
    df.libelle                                                  AS domaine_fonctionnel_libelle,

    p.libelle_long                                              AS periode_libelle,
    mep.date_mise_en_paiement                                   AS date_mise_en_paiement,
    mep.heures_fi                                               AS heures_fi,
    mep.heures_fa                                               AS heures_fa,
    mep.heures_fc                                               AS heures_fc,
    mep.heures_primes                                           AS heures_primes,
    mep.heures_referentiel                                      AS heures_referentiel

FROM mep

        JOIN centre_cout cc
             ON cc.id = mep.centre_cout_id

        JOIN type_ressource tr
             ON tr.id = cc.type_ressource_id

        LEFT JOIN service s
                  ON s.id = mep.service_id

        LEFT JOIN element_pedagogique ep
                  ON ep.id = s.element_pedagogique_id

        LEFT JOIN SOURCE src
                  ON src.id = ep.source_id
                      OR (
                         ep.source_id IS NULL
                             AND src.code = 'OSE'
                         )

        LEFT JOIN discipline de
                  ON de.id = ep.discipline_id

        LEFT JOIN etape etp
                  ON etp.id = ep.etape_id

        LEFT JOIN type_formation tf
                  ON tf.id = etp.type_formation_id

        LEFT JOIN groupe_type_formation gtf
                  ON gtf.id = tf.groupe_id

        LEFT JOIN service_referentiel sr
                  ON sr.id = mep.service_referentiel_id

        LEFT JOIN fonction_referentiel fr
                  ON fr.id = sr.fonction_id

        JOIN intervenant i
             ON i.id = NVL(
                     s.intervenant_id,
                     sr.intervenant_id
                       )

        JOIN statut si
             ON si.id = i.statut_id

        JOIN type_intervenant ti
             ON ti.id = si.type_intervenant_id

        LEFT JOIN grade g
                  ON g.id = i.grade_id

        LEFT JOIN discipline di
                  ON di.id = i.discipline_id

        LEFT JOIN STRUCTURE saff
                  ON saff.id = i.structure_id

        LEFT JOIN STRUCTURE sens
                  ON sens.id = NVL(
                          ep.structure_id,
                          sr.structure_id
                               )

        JOIN etablissement etab
             ON etab.id = NVL(
                     s.etablissement_id,
                     ose_parametre.get_etablissement()
                          )

        LEFT JOIN periode p
                  ON p.id = mep.periode_paiement_id

        LEFT JOIN domaine_fonctionnel df
                  ON df.id = mep.domaine_fonctionnel_id

ORDER BY
    intervenant_nom,
    service_structure_aff_libelle,
    service_structure_ens_libelle,
    etape_libelle,
    element_libelle