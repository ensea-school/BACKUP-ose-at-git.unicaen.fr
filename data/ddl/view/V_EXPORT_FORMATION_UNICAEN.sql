CREATE OR REPLACE FORCE VIEW V_EXPORT_FORMATION_UNICAEN AS
WITH ep_base AS (
    SELECT
        e.annee_id,
        s.id                     AS structure_id,
        s.ids                    AS structure_ids,
        s.libelle_court          AS structure,
        e.code                   AS code_formation,
        e.libelle                AS libelle_formation,
        e.id                     AS etape_id,
        CONCAT(gtf.libelle_court, e.niveau) AS niveau,
        ep.id                    AS ep_id,
        ep.code                  AS code_enseignement,
        ep.libelle               AS libelle_enseignement,
        d.source_code            AS code_discipline,
        d.libelle_long           AS libelle_discipline,
        p.libelle_long           AS periode,
        ep.taux_foad             AS foad,
        ep.fi,
        ep.fa,
        ep.fc,
        NVL(ef.fi, 0)            AS effectif_fi,
        NVL(ef.fa, 0)            AS effectif_fa,
        NVL(ef.fc, 0)            AS effectif_fc
    FROM etape e
    JOIN element_pedagogique ep
        ON ep.etape_id = e.id
       AND ep.histo_destruction IS NULL
    LEFT JOIN type_formation tf ON tf.id = e.type_formation_id
    LEFT JOIN groupe_type_formation gtf ON gtf.id = tf.groupe_id
    LEFT JOIN structure s ON s.id = e.structure_id
    LEFT JOIN discipline d ON d.id = ep.discipline_id
    LEFT JOIN periode p ON p.id = ep.periode_id
    LEFT JOIN effectifs ef ON ef.element_pedagogique_id = ep.id
    WHERE e.histo_destruction IS NULL

),

cc_agrege AS (
    SELECT
        ep.id AS ep_id,

        CASE
            WHEN MAX(ep.fi) = 0 THEN NULL
            ELSE COALESCE(
                MAX(CASE WHEN th.code = 'fi' THEN cc.code END),
                'manquant'
            )
        END AS centre_cout_fi,

        CASE
            WHEN MAX(ep.fa) = 0 THEN NULL
            ELSE COALESCE(
                MAX(CASE WHEN th.code = 'fa' THEN cc.code END),
                'manquant'
            )
        END AS centre_cout_fa,

        CASE
            WHEN MAX(ep.fc) = 0 THEN NULL
            ELSE COALESCE(
                MAX(CASE WHEN th.code = 'fc' THEN cc.code END),
                'manquant'
            )
        END AS centre_cout_fc

    FROM element_pedagogique ep
    LEFT JOIN centre_cout_ep cce
        ON cce.element_pedagogique_id = ep.id
       AND cce.histo_destruction IS NULL
    LEFT JOIN centre_cout cc
        ON cc.id = cce.centre_cout_id
       AND cc.histo_destruction IS NULL
    LEFT JOIN type_heures th
        ON th.id = cce.type_heures_id
    GROUP BY ep.id
)

SELECT
    b.annee_id,
    b.structure_id,
    b.structure_ids,
    b.structure,
    b.code_formation,
    b.libelle_formation,
    b.etape_id,
    b.niveau,
    b.code_enseignement,
    b.libelle_enseignement,
    b.code_discipline,
    b.libelle_discipline,
    b.periode,
    b.foad,
    b.fi,
    b.fa,
    b.fc,
    b.effectif_fi,
    b.effectif_fa,
    b.effectif_fc,
    cc.centre_cout_fi,
    cc.centre_cout_fa,
    cc.centre_cout_fc,
    COALESCE(SUM(CASE WHEN ti.code = 'CM' THEN vhe.heures END), 0) AS heures_cm,
    COALESCE(SUM(CASE WHEN ti.code = 'CM' THEN vhe.groupes END), 0) AS nb_groupe_cm,
    COALESCE(SUM(CASE WHEN ti.code = 'TD' THEN vhe.heures END), 0) AS heures_td,
    COALESCE(SUM(CASE WHEN ti.code = 'TD' THEN vhe.groupes END), 0) AS nb_groupe_td,
    COALESCE(SUM(CASE WHEN ti.code = 'TP' THEN vhe.heures END), 0) AS heures_tp,
    COALESCE(SUM(CASE WHEN ti.code = 'TP' THEN vhe.groupes END), 0) AS nb_groupe_tp,
    COALESCE(SUM(CASE WHEN ti.code = 'Accompagnement' THEN vhe.heures END), 0) AS heures_accompagnement,
    COALESCE(SUM(CASE WHEN ti.code = 'Accompagnement' THEN vhe.groupes END), 0) AS nb_groupe_accompagnement

FROM ep_base b
LEFT JOIN cc_agrege cc ON cc.ep_id = b.ep_id
LEFT JOIN volume_horaire_ens vhe ON vhe.element_pedagogique_id = b.ep_id
LEFT JOIN type_intervention ti ON ti.id = vhe.type_intervention_id

GROUP BY
    b.annee_id,
    b.structure_id,
    b.structure_ids,
    b.structure,
    b.code_formation,
    b.libelle_formation,
    b.etape_id,
    b.niveau,
    b.code_enseignement,
    b.libelle_enseignement,
    b.code_discipline,
    b.libelle_discipline,
    b.periode,
    b.foad,
    b.fi,
    b.fa,
    b.fc,
    b.effectif_fi,
    b.effectif_fa,
    b.effectif_fc,
    cc.centre_cout_fi,
    cc.centre_cout_fa,
    cc.centre_cout_fc

ORDER BY
    b.annee_id,
    b.structure_id,
    b.code_formation,
    b.code_enseignement
