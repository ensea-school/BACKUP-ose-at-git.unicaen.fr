CREATE OR REPLACE VIEW V_ETAT_PAIEMENT AS
SELECT 

  periode_paiement_id,
  structure_id, 
  intervenant_id, 
  annee_id, 
  centre_cout_id, 
  domaine_fonctionnel_id,
  etat,
  periode_paiement_libelle,
  intervenant_code,
  intervenant_nom,
  intervenant_numero_insee,
  centre_cout_code,
  domaine_fonctionnel_libelle,
  hetd,
  CASE WHEN hetd > 0 THEN hetd / SUM( hetd ) OVER( PARTITION BY periode_paiement_id, intervenant_id, etat, structure_id) ELSE 0 END  hetd_pourc,
  ROUND( hetd * taux_horaire, 2 ) hetd_montant,
  ROUND( fc_majorees * taux_horaire, 2 ) rem_fc_d714,
  exercice_aa,
  ROUND( exercice_aa * taux_horaire, 2 ) exercice_aa_montant,
  exercice_ac,
  ROUND( exercice_ac * taux_horaire, 2 ) exercice_ac_montant

FROM (
  WITH dep AS ( -- détails par état de paiement
  SELECT
    p.id                                                                periode_paiement_id,
    mis.structure_id                                                    structure_id,
    i.id                                                                intervenant_id,
    mis.annee_id                                                        annee_id,
    cc.id                                                               centre_cout_id,
    df.id                                                               domaine_fonctionnel_id,
    CASE
      WHEN v.id IS NULL THEN 'a-valider'
      ELSE CASE
        WHEN mep.date_mise_en_paiement IS NULL THEN 'a-mettre-en-paiement'
        ELSE 'mis-en-paiement'
      END
    END                                                                 etat,

    p.libelle_long                                                      periode_paiement_libelle,
    i.source_code                                                       intervenant_code,
    i.prenom || ' ' || i.nom_usuel                                      intervenant_nom,
    TRIM( NVL(i.numero_insee,'') || ' ' || NVL(i.numero_insee_cle,'') ) intervenant_numero_insee,
    cc.source_code                                                      centre_cout_code,
    df.libelle                                                          domaine_fonctionnel_libelle,
    CASE WHEN th.code = 'fc_majorees' THEN 0 ELSE mep.heures END        hetd,
    CASE WHEN th.code = 'fc_majorees' THEN mep.heures ELSE 0 END        fc_majorees,
    mep.heures * 4 / 10                                                 exercice_aa,
    mep.heures * 6 / 10                                                 exercice_ac,
    OSE_FORMULE.GET_TAUX_HORAIRE_HETD( NVL(mep.date_mise_en_paiement,SYSDATE) )      taux_horaire
  FROM
    v_mep_intervenant_structure  mis
    JOIN mise_en_paiement        mep ON mep.id = mis.mise_en_paiement_id AND 1 = ose_divers.comprise_entre( mep.histo_creation, mep.histo_destruction )
    JOIN type_heures              th ON  th.id = mep.type_heures_id      AND 1 = ose_divers.comprise_entre(  th.histo_creation,  th.histo_destruction )
    JOIN centre_cout              cc ON  cc.id = mep.centre_cout_id      -- pas d'historique pour les centres de coût, qui devront tout de même apparaitre mais en erreur
    JOIN intervenant               i ON   i.id = mis.intervenant_id      AND 1 = ose_divers.comprise_entre(   i.histo_creation,   i.histo_destruction )
    LEFT JOIN validation           v ON   v.id = mep.validation_id       AND 1 = ose_divers.comprise_entre(   v.histo_creation,   v.histo_destruction )
    LEFT JOIN domaine_fonctionnel df ON  df.id = mis.domaine_fonctionnel_id
    LEFT JOIN periode              p ON   p.id = mep.periode_paiement_id
  )
  SELECT
    periode_paiement_id,
    structure_id, 
    intervenant_id, 
    annee_id, 
    centre_cout_id, 
    domaine_fonctionnel_id, 
    etat,
    periode_paiement_libelle,
    intervenant_code,
    intervenant_nom,
    intervenant_numero_insee,
    centre_cout_code,
    domaine_fonctionnel_libelle,
    SUM( hetd ) hetd,
    SUM( fc_majorees ) fc_majorees,
    SUM( exercice_aa ) exercice_aa,
    SUM( exercice_ac ) exercice_ac,
    taux_horaire
  FROM
    dep
  GROUP BY
    periode_paiement_id,
    structure_id, 
    intervenant_id, 
    annee_id, 
    centre_cout_id, 
    domaine_fonctionnel_id, 
    etat,
    periode_paiement_libelle,
    intervenant_code,
    intervenant_nom,
    intervenant_numero_insee,
    centre_cout_code,
    domaine_fonctionnel_libelle,
    taux_horaire
) 
dep2