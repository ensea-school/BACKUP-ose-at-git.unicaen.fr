CREATE OR REPLACE FORCE VIEW V_EXPORT_FORMATION_UNICAEN AS
WITH mutualisation AS (
    SELECT
        cp.element_pedagogique_id AS ep_id,
        COUNT(DISTINCT cp.etape_id) AS nb_autres_etapes
    FROM chemin_pedagogique cp
    JOIN element_pedagogique ep
        ON ep.id = cp.element_pedagogique_id
    JOIN etape e
        ON e.id = cp.etape_id
       AND e.histo_destruction IS NULL
    WHERE cp.histo_destruction IS NULL
      AND cp.etape_id <> ep.etape_id
    GROUP BY cp.element_pedagogique_id
),

ep_base AS (
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
        -- effectifs et charge uniquement sur la ligne de la formation porteuse, pour ne pas les compter plusieurs fois
        CASE WHEN ep.etape_id = e.id THEN NVL(ef.fi, 0) END AS effectif_fi,
        CASE WHEN ep.etape_id = e.id THEN NVL(ef.fa, 0) END AS effectif_fa,
        CASE WHEN ep.etape_id = e.id THEN NVL(ef.fc, 0) END AS effectif_fc,
        CASE WHEN mut.nb_autres_etapes > 0 THEN 'Oui' ELSE 'Non' END AS mutualise,
        CASE WHEN ep.etape_id = e.id THEN 'Oui' ELSE 'Non' END AS formation_porteuse,
        ep_e.code                AS code_etape_porteuse,
        ep_e.libelle             AS libelle_etape_porteuse
    FROM etape e
    JOIN chemin_pedagogique cp
        ON cp.etape_id = e.id
       AND cp.histo_destruction IS NULL
    JOIN element_pedagogique ep
        ON ep.id = cp.element_pedagogique_id
       AND ep.histo_destruction IS NULL
    JOIN etape ep_e ON ep_e.id = ep.etape_id
    LEFT JOIN mutualisation mut ON mut.ep_id = ep.id
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
    b.mutualise,
    b.formation_porteuse,
    b.code_etape_porteuse,
    b.libelle_etape_porteuse,
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
    CASE WHEN b.formation_porteuse = 'Oui' THEN COALESCE(SUM(CASE WHEN ti.code = 'CM' THEN vhe.heures END), 0) END AS heures_cm,
    CASE WHEN b.formation_porteuse = 'Oui' THEN COALESCE(SUM(CASE WHEN ti.code = 'CM' THEN vhe.groupes END), 0) END AS nb_groupe_cm,
    CASE WHEN b.formation_porteuse = 'Oui' THEN COALESCE(SUM(CASE WHEN ti.code = 'TD' THEN vhe.heures END), 0) END AS heures_td,
    CASE WHEN b.formation_porteuse = 'Oui' THEN COALESCE(SUM(CASE WHEN ti.code = 'TD' THEN vhe.groupes END), 0) END AS nb_groupe_td,
    CASE WHEN b.formation_porteuse = 'Oui' THEN COALESCE(SUM(CASE WHEN ti.code = 'TP' THEN vhe.heures END), 0) END AS heures_tp,
    CASE WHEN b.formation_porteuse = 'Oui' THEN COALESCE(SUM(CASE WHEN ti.code = 'TP' THEN vhe.groupes END), 0) END AS nb_groupe_tp,
    CASE WHEN b.formation_porteuse = 'Oui' THEN COALESCE(SUM(CASE WHEN ti.code = 'Accompagnement' THEN vhe.heures END), 0) END AS heures_accompagnement,
    CASE WHEN b.formation_porteuse = 'Oui' THEN COALESCE(SUM(CASE WHEN ti.code = 'Accompagnement' THEN vhe.groupes END), 0) END AS nb_groupe_accompagnement

FROM ep_base b
LEFT JOIN cc_agrege cc ON cc.ep_id = b.ep_id
LEFT JOIN volume_horaire_ens vhe ON vhe.element_pedagogique_id = b.ep_id
LEFT JOIN type_intervention ti ON ti.id = vhe.type_intervention_id
where b.annee_id = 2026
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
    b.mutualise,
    b.formation_porteuse,
    b.code_etape_porteuse,
    b.libelle_etape_porteuse,
    cc.centre_cout_fi,
    cc.centre_cout_fa,
    cc.centre_cout_fc

ORDER BY
    b.annee_id,
    b.structure_id,
    b.code_formation,
    b.code_enseignement