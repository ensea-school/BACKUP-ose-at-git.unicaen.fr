CREATE OR REPLACE FORCE VIEW V_ETAT_PAIEMENT AS
SELECT
  annee_id,
  type_intervenant_id,
  statut_id,
  structure_id,
  structure_ids,
  periode_id,
  intervenant_id,
  centre_cout_id,
  domaine_fonctionnel_id,

  annee_id || '/' || (annee_id+1) annee,
  etat,
  composante,
  date_mise_en_paiement,
  periode,
  statut,
  statut_libelle,
  intervenant_code,
  intervenant_nom,
  intervenant_numero_insee,
  centre_cout_code,
  centre_cout_libelle,
  domaine_fonctionnel_code,
  domaine_fonctionnel_libelle,
  hetd,
  CASE WHEN pourc_ecart >= 0 THEN
    CASE WHEN RANK() OVER (PARTITION BY periode_id, intervenant_id, etat, structure_id ORDER BY CASE WHEN (pourc_ecart >= 0 AND pourc_diff >= 0) OR (pourc_ecart < 0 AND pourc_diff < 0) THEN pourc_diff ELSE -1 END DESC) <= (ABS(pourc_ecart) / 0.001) THEN hetd_pourc + (pourc_ecart / ABS(pourc_ecart) * 0.001) ELSE hetd_pourc END
     ELSE
    CASE WHEN RANK() OVER (PARTITION BY periode_id, intervenant_id, etat, structure_id ORDER BY CASE WHEN (pourc_ecart >= 0 AND pourc_diff >= 0) OR (pourc_ecart < 0 AND pourc_diff < 0) THEN pourc_diff ELSE -1 END) <= (ABS(pourc_ecart) / 0.001) THEN hetd_pourc + (pourc_ecart / ABS(pourc_ecart) * 0.001) ELSE hetd_pourc END
  END hetd_pourc,
  hetd_montant,
  rem_fc_d714,
  exercice_aa,
  exercice_aa_montant,
  exercice_ac,
  exercice_ac_montant,
  taux_remu,
  taux_horaire,
  taux_conges_payes
FROM
  (
  SELECT
    dep3.*,
    1-CASE WHEN hetd > 0 THEN SUM( hetd_pourc ) OVER ( PARTITION BY periode_id, intervenant_id, etat, structure_id) ELSE 0 END pourc_ecart
  FROM (
    SELECT
      periode_id,
      structure_id,
      structure_ids,
      type_intervenant_id,
      statut_id,
      intervenant_id,
      annee_id,
      centre_cout_id,
      domaine_fonctionnel_id,
      etat,
      composante,
      date_mise_en_paiement,
      periode,
      statut,
      statut_libelle,
      intervenant_code,
      intervenant_nom,
      intervenant_numero_insee,
      centre_cout_code,
      centre_cout_libelle,
      domaine_fonctionnel_code,
      domaine_fonctionnel_libelle,
      hetd,
      ROUND( CASE WHEN hetd > 0 THEN hetd / SUM( hetd ) OVER( PARTITION BY periode_id, intervenant_id, etat, structure_id) ELSE 0 END, 3 ) hetd_pourc,
      ROUND( hetd * taux_horaire * taux_conges_payes, 2 ) hetd_montant,
      ROUND( primes * taux_horaire * taux_conges_payes, 2 ) rem_fc_d714,
      exercice_aa,
      ROUND( exercice_aa * taux_horaire * taux_conges_payes, 2 ) exercice_aa_montant,
      exercice_ac,
      ROUND( exercice_ac * taux_horaire * taux_conges_payes, 2 ) exercice_ac_montant,
      (CASE WHEN hetd > 0 THEN hetd / SUM( hetd ) OVER( PARTITION BY periode_id, intervenant_id, etat, structure_id) ELSE 0 END)
      - ROUND( CASE WHEN hetd > 0 THEN hetd / SUM( hetd ) OVER( PARTITION BY periode_id, intervenant_id, etat, structure_id) ELSE 0 END, 3 ) pourc_diff,
        taux_remu,
        taux_horaire,
        taux_conges_payes
    FROM (
      SELECT
        is_primes,
        periode_id,
        structure_id,
        structure_ids,
        type_intervenant_id,
        statut_id,
        intervenant_id,
        annee_id,
        centre_cout_id,
        domaine_fonctionnel_id,
        etat,
        periode,
        composante,
        date_mise_en_paiement,
        statut,
        statut_libelle,
        intervenant_code,
        intervenant_nom,
        intervenant_numero_insee,
        centre_cout_code,
        centre_cout_libelle,
        domaine_fonctionnel_code,
        domaine_fonctionnel_libelle,
        SUM( hetd ) hetd,
        SUM( primes ) primes,
        SUM( exercice_aa ) exercice_aa,
        SUM( exercice_ac ) exercice_ac,
        taux_remu,
        taux_horaire,
        taux_conges_payes
      FROM
        (
        SELECT
          CASE WHEN th.code = 'primes' THEN 1 ELSE 0 END                      is_primes,
          p.id                                                                periode_id,
          s.id                                                                structure_id,
          s.ids                                                               structure_ids,
          i.id                                                                intervenant_id,
          si.id                                                               statut_id,
          i.annee_id                                                          annee_id,
          cc.id                                                               centre_cout_id,
          df.id                                                               domaine_fonctionnel_id,
          ti.id                                                               type_intervenant_id,
          CASE
            WHEN mis.periode_paiement_id IS NULL THEN 'a-mettre-en-paiement'
            ELSE 'mis-en-paiement'
          END                                                                 etat,
          TRIM(p.libelle_long || ' ' || to_char( add_months( a.date_debut, p.ecart_mois ), 'yyyy' )) periode,
          mep.date_mise_en_paiement                                           date_mise_en_paiement,
          s.libelle_court                                                     composante,
          ti.libelle                                                          statut,
          si.libelle                                                          statut_libelle,
          i.source_code                                                       intervenant_code,
          i.nom_usuel || ' ' || i.prenom                                      intervenant_nom,
          i.numero_insee                                                      intervenant_numero_insee,
          cc.source_code                                                      centre_cout_code,
          cc.libelle                                                          centre_cout_libelle,
          df.source_code                                                      domaine_fonctionnel_code,
          df.libelle                                                          domaine_fonctionnel_libelle,
          CASE WHEN th.code = 'primes' THEN 0 ELSE CASE
            WHEN mis.periode_paiement_id IS NULL THEN mis.heures_demandees_aa + mis.heures_demandees_ac
            ELSE mis.heures_payees_aa + mis.heures_payees_ac
          END END                                                             hetd,
          CASE WHEN th.code = 'primes' THEN CASE
            WHEN mis.periode_paiement_id IS NULL THEN mis.heures_demandees_aa + mis.heures_demandees_ac
            ELSE mis.heures_payees_aa + mis.heures_payees_ac
          END ELSE 0 END                                                      primes,
          CASE
            WHEN mis.periode_paiement_id IS NULL THEN mis.heures_demandees_aa
            ELSE mis.heures_payees_aa
          END                                                                 exercice_aa,
          CASE
            WHEN mis.periode_paiement_id IS NULL THEN mis.heures_demandees_ac
            ELSE mis.heures_payees_ac
          END                                                                 exercice_ac,
          tr.libelle taux_remu,
          mis.taux_horaire,
          mis.taux_conges_payes
        FROM
                    tbl_paiement mis
               JOIN mise_en_paiement        mep ON mep.id = mis.mise_en_paiement_id
               JOIN type_heures              th ON th.id = mis.type_heures_id
               JOIN centre_cout              cc ON cc.id = mis.centre_cout_id      -- pas d'historique pour les centres de coût, qui devront tout de même apparaitre mais en erreur
               JOIN intervenant               i ON i.id = mis.intervenant_id
               JOIN annee                     a ON a.id = i.annee_id
               JOIN statut                   si ON si.id = i.statut_id
               JOIN type_intervenant         ti ON ti.id = si.type_intervenant_id
               JOIN STRUCTURE                 s ON s.id = mis.structure_id
          LEFT JOIN domaine_fonctionnel      df ON df.id = mis.domaine_fonctionnel_id
          LEFT JOIN periode                   p ON p.id = mis.periode_paiement_id
          LEFT JOIN service                  se ON mis.service_id = se.id
          LEFT JOIN element_pedagogique      ep ON ep.id = se.element_pedagogique_id
          LEFT JOIN taux_remu                tr ON tr.id = mis.taux_remu_id
       ) dep
      GROUP BY
        is_primes,
        periode_id,
        structure_id,
        structure_ids,
        type_intervenant_id,
        statut_id,
        intervenant_id,
        annee_id,
        centre_cout_id,
        domaine_fonctionnel_id,
        etat,
        periode,
        composante,
        date_mise_en_paiement,
        statut,
        statut_libelle,
        intervenant_code,
        intervenant_nom,
        intervenant_numero_insee,
        centre_cout_code,
        centre_cout_libelle,
        domaine_fonctionnel_code,
        domaine_fonctionnel_libelle,
        taux_remu,
        taux_horaire,
        taux_conges_payes
      ) dep2
    ) dep3
  ) dep4
ORDER BY
  annee_id,
  intervenant_nom,
  type_intervenant_id,
  statut_id,
  structure_id,
  periode_id