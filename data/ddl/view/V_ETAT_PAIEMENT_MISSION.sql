CREATE OR REPLACE FORCE VIEW V_ETAT_PAIEMENT_MISSION AS
SELECT
  annee_id,
  structure_id,
  structure_ids,
  periode_id,
  type_intervenant_id,
  statut_id,
  intervenant_id,
  mission_id,
  etat,
  titre,
  periode,
  structure,
  annee,
  intervenant_nom,
  intervenant_numero_insee,
  date_debut,
  date_fin,
  mission,
  centre_cout,
  domaine_fonctionnel,
  taux_nom,
  taux_horaire,
  taux_conges_payes,
  SUM(heures)                                             heures,
  ROUND(SUM(heures) * taux_horaire,2)                     montant_hors_cp,
  AVG(taux_conges_payes - 1)                              taux_cp,
  ROUND(SUM(heures) * taux_horaire * (taux_conges_payes - 1),2) montant_cp,
  ROUND(sum(heures * taux_conges_payes) * taux_horaire,2) montant_global
FROM
  (
  SELECT
    a.id                                      annee_id,
    p.structure_id                            structure_id,
    str.ids                                   structure_ids,
    p.periode_paiement_id                     periode_id,
    si.type_intervenant_id                    type_intervenant_id,
    si.id                                     statut_id,
    p.intervenant_id                          intervenant_id,
    p.mission_id                              mission_id,
    CASE
      WHEN pp.id IS NULL THEN 'a-mettre-en-paiement'
      ELSE 'mis-en-paiement'
    END                                                   etat,
    CASE
      WHEN pp.id IS NULL THEN 'Demandes de mises en paiement'
      ELSE 'État de paiement'
    END                                                   titre,
    TRIM(pp.libelle_long || ' ' || to_char( add_months( a.date_debut, pp.ecart_mois ), 'yyyy' )) periode,
    str.libelle_court                                         structure,
    a.libelle                                                 annee,
    i.nom_usuel || ' ' || i.prenom                            intervenant_nom,
    i.numero_insee                                            intervenant_numero_insee,
    m.date_debut                                              date_debut,
    m.date_fin                                                date_fin,
    COALESCE(m.libelle_mission, tm.libelle)                   mission,
    c.code                                                    centre_cout,
    df.libelle                                                domaine_fonctionnel,
    tr.libelle                                                taux_nom,
    p.taux_horaire                                            taux_horaire,
    p.taux_conges_payes                                       taux_conges_payes,
    CASE WHEN p.periode_paiement_id IS NULL THEN p.heures_demandees_aa + p.heures_demandees_ac  ELSE p.heures_payees_aa + p.heures_payees_ac END heures
  FROM
              tbl_paiement         p
         JOIN annee                a ON a.id = p.annee_id
         JOIN intervenant          i ON i.id = p.intervenant_id
         JOIN statut              si ON si.id = i.statut_id
         JOIN mission              m ON m.id = p.mission_id
         JOIN type_mission        tm ON tm.id = m.type_mission_id
         JOIN taux_remu           tr ON tr.id = p.taux_remu_id
         JOIN structure          str ON str.id = p.structure_id
    LEFT JOIN centre_cout          c ON c.id = p.centre_cout_id
    LEFT JOIN domaine_fonctionnel df ON df.id = p.domaine_fonctionnel_id
    LEFT JOIN periode             pp ON pp.id = p.periode_paiement_id
  WHERE
    CASE WHEN p.periode_paiement_id IS NULL THEN p.heures_demandees_aa + p.heures_demandees_ac  ELSE p.heures_payees_aa + p.heures_payees_ac END > 0
) t
GROUP BY
  annee_id, structure_id, structure_ids, periode_id, type_intervenant_id, statut_id, intervenant_id, mission_id, etat,
  titre, periode, structure, annee,
  intervenant_nom, intervenant_numero_insee,
  date_debut, date_fin, mission,
  centre_cout, domaine_fonctionnel,
  taux_nom, taux_horaire, taux_conges_payes
ORDER BY
  intervenant_nom