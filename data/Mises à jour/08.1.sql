-- OSE 8.1

CREATE OR REPLACE FORCE VIEW "V_EXPORT_SERVICE" ("ID", "SERVICE_ID", "INTERVENANT_ID", "TYPE_INTERVENANT_ID", "ANNEE_ID", "SERVICE_DATE_MODIFICATION", "TYPE_VOLUME_HORAIRE_ID", "ETAT_VOLUME_HORAIRE_ID", "ETABLISSEMENT_ID", "STRUCTURE_AFF_ID", "STRUCTURE_ENS_ID", "NIVEAU_FORMATION_ID", "ETAPE_ID", "ELEMENT_PEDAGOGIQUE_ID", "PERIODE_ID", "TYPE_INTERVENTION_ID", "FONCTION_REFERENTIEL_ID", "TYPE_ETAT", "INTERVENANT_CODE", "INTERVENANT_NOM", "INTERVENANT_DATE_NAISSANCE", "INTERVENANT_STATUT_LIBELLE", "INTERVENANT_TYPE_CODE", "INTERVENANT_TYPE_LIBELLE", "INTERVENANT_GRADE_CODE", "INTERVENANT_GRADE_LIBELLE", "INTERVENANT_DISCIPLINE_CODE", "INTERVENANT_DISCIPLINE_LIBELLE", "SERVICE_STRUCTURE_AFF_LIBELLE", "SERVICE_STRUCTURE_ENS_LIBELLE", "ETABLISSEMENT_LIBELLE", "GROUPE_TYPE_FORMATION_LIBELLE", "TYPE_FORMATION_LIBELLE", "ETAPE_NIVEAU", "ETAPE_CODE", "ETAPE_LIBELLE", "ELEMENT_CODE", "ELEMENT_LIBELLE", "ELEMENT_DISCIPLINE_CODE", "ELEMENT_DISCIPLINE_LIBELLE", "FONCTION_REFERENTIEL_LIBELLE", "ELEMENT_TAUX_FI", "ELEMENT_TAUX_FC", "ELEMENT_TAUX_FA", "SERVICE_REF_FORMATION", "COMMENTAIRES", "PERIODE_LIBELLE", "ELEMENT_PONDERATION_COMPL", "ELEMENT_SOURCE_LIBELLE", "HEURES", "HEURES_REF", "HEURES_NON_PAYEES", "SERVICE_STATUTAIRE", "SERVICE_DU_MODIFIE", "SERVICE_FI", "SERVICE_FA", "SERVICE_FC", "SERVICE_REFERENTIEL", "HEURES_COMPL_FI", "HEURES_COMPL_FA", "HEURES_COMPL_FC", "HEURES_COMPL_FC_MAJOREES", "HEURES_COMPL_REFERENTIEL", "TOTAL", "SOLDE", "DATE_CLOTURE_REALISE") AS
  WITH t AS ( SELECT
                     'vh_' || vh.id                    id,
                     s.id                              service_id,
                     s.intervenant_id                  intervenant_id,
                     vh.type_volume_horaire_id         type_volume_horaire_id,
                     fr.etat_volume_horaire_id         etat_volume_horaire_id,
                     s.element_pedagogique_id          element_pedagogique_id,
                     s.etablissement_id                etablissement_id,
                     NULL                              structure_aff_id,
                     NULL                              structure_ens_id,
                     vh.periode_id                     periode_id,
                     vh.type_intervention_id           type_intervention_id,
                     NULL                              fonction_referentiel_id,

                     s.description                     service_description,

                     vh.heures                         heures,
                     0                                 heures_ref,
                     0                                 heures_non_payees,
                     frvh.service_fi                   service_fi,
                     frvh.service_fa                   service_fa,
                     frvh.service_fc                   service_fc,
                     0                                 service_referentiel,
                     frvh.heures_compl_fi              heures_compl_fi,
                     frvh.heures_compl_fa              heures_compl_fa,
                     frvh.heures_compl_fc              heures_compl_fc,
                     frvh.heures_compl_fc_majorees     heures_compl_fc_majorees,
                     0                                 heures_compl_referentiel,
                     frvh.total                        total,
                     fr.solde                          solde,
                     NULL                              service_ref_formation,
                     NULL                              commentaires
              FROM
                   formule_resultat_vh                frvh
                     JOIN formule_resultat                fr ON fr.id = frvh.formule_resultat_id
                     JOIN volume_horaire                  vh ON vh.id = frvh.volume_horaire_id AND vh.motif_non_paiement_id IS NULL AND vh.histo_destruction IS NULL
                     JOIN service                          s ON s.id = vh.service_id AND s.intervenant_id = fr.intervenant_id AND s.histo_destruction IS NULL

              UNION ALL

              SELECT
                     'vh_' || vh.id                    id,
                     s.id                              service_id,
                     s.intervenant_id                  intervenant_id,
                     vh.type_volume_horaire_id         type_volume_horaire_id,
                     vhe.etat_volume_horaire_id        etat_volume_horaire_id,
                     s.element_pedagogique_id          element_pedagogique_id,
                     s.etablissement_id                etablissement_id,
                     NULL                              structure_aff_id,
                     NULL                              structure_ens_id,
                     vh.periode_id                     periode_id,
                     vh.type_intervention_id           type_intervention_id,
                     NULL                              fonction_referentiel_id,

                     s.description                     service_description,

                     vh.heures                         heures,
                     0                                 heures_ref,
                     1                                 heures_non_payees,
                     0                                 service_fi,
                     0                                 service_fa,
                     0                                 service_fc,
                     0                                 service_referentiel,
                     0                                 heures_compl_fi,
                     0                                 heures_compl_fa,
                     0                                 heures_compl_fc,
                     0                                 heures_compl_fc_majorees,
                     0                                 heures_compl_referentiel,
                     0                                 total,
                     fr.solde                          solde,
                     NULL                              service_ref_formation,
                     NULL                              commentaires
              FROM
                   volume_horaire                  vh
                     JOIN service                     s ON s.id = vh.service_id
                     JOIN v_volume_horaire_etat     vhe ON vhe.volume_horaire_id = vh.id
                     JOIN formule_resultat           fr ON fr.intervenant_id = s.intervenant_id AND fr.type_volume_horaire_id = vh.type_volume_horaire_id AND fr.etat_volume_horaire_id = vhe.etat_volume_horaire_id
              WHERE
                  vh.motif_non_paiement_id IS NOT NULL
                AND vh.histo_destruction IS NULL
                AND s.histo_destruction IS NULL

              UNION ALL

              SELECT
                     'vh_ref_' || vhr.id               id,
                     sr.id                             service_id,
                     sr.intervenant_id                 intervenant_id,
                     fr.type_volume_horaire_id         type_volume_horaire_id,
                     fr.etat_volume_horaire_id         etat_volume_horaire_id,
                     NULL                              element_pedagogique_id,
                     OSE_PARAMETRE.GET_ETABLISSEMENT   etablissement_id,
                     NULL                              structure_aff_id,
                     sr.structure_id                   structure_ens_id,
                     NULL                              periode_id,
                     NULL                              type_intervention_id,
                     sr.fonction_id                    fonction_referentiel_id,

                     NULL                              service_description,

                     0                                 heures,
                     vhr.heures                        heures_ref,
                     0                                 heures_non_payees,
                     0                                 service_fi,
                     0                                 service_fa,
                     0                                 service_fc,
                     frvr.service_referentiel          service_referentiel,
                     0                                 heures_compl_fi,
                     0                                 heures_compl_fa,
                     0                                 heures_compl_fc,
                     0                                 heures_compl_fc_majorees,
                     frvr.heures_compl_referentiel     heures_compl_referentiel,
                     frvr.total                        total,
                     fr.solde                          solde,
                     sr.formation                      service_ref_formation,
                     sr.commentaires                   commentaires
              FROM
                   formule_resultat_vh_ref       frvr
                     JOIN formule_resultat           fr ON fr.id = frvr.formule_resultat_id
                     JOIN volume_horaire_ref        vhr ON vhr.id =  frvr.volume_horaire_ref_id
                     JOIN service_referentiel        sr ON sr.id = vhr.service_referentiel_id AND sr.intervenant_id = fr.intervenant_id AND sr.histo_destruction IS NULL

              UNION ALL

              SELECT
                     'vh_0_' || i.id                   id,
                     NULL                              service_id,
                     i.id                              intervenant_id,
                     tvh.id                            type_volume_horaire_id,
                     evh.id                            etat_volume_horaire_id,
                     NULL                              element_pedagogique_id,
                     OSE_PARAMETRE.GET_ETABLISSEMENT   etablissement_id,
                     NULL                              structure_aff_id,
                     NULL                              structure_ens_id,
                     NULL                              periode_id,
                     NULL                              type_intervention_id,
                     NULL                              fonction_referentiel_id,

                     NULL                              service_description,

                     0                                 heures,
                     0                                 heures_ref,
                     0                                 heures_non_payees,
                     0                                 service_fi,
                     0                                 service_fa,
                     0                                 service_fc,
                     0                                 service_referentiel,
                     0                                 heures_compl_fi,
                     0                                 heures_compl_fa,
                     0                                 heures_compl_fc,
                     0                                 heures_compl_fc_majorees,
                     NULL                              heures_compl_referentiel,
                     0                                 total,
                     0                                 solde,
                     NULL                              service_ref_formation,
                     NULL                              commentaires
              FROM
                   intervenant i
                     JOIN statut_intervenant si ON si.id = i.statut_id
                     JOIN etat_volume_horaire evh ON evh.code IN ('saisi','valide')
                     JOIN type_volume_horaire tvh ON tvh.code IN ('PREVU','REALISE')
                     LEFT JOIN modification_service_du msd ON msd.intervenant_id = i.id AND msd.histo_destruction IS NULL
                     LEFT JOIN motif_modification_service mms ON mms.id = msd.motif_id
              WHERE
                  i.histo_destruction IS NULL
                AND si.service_statutaire > 0
              GROUP BY
                       i.id, si.service_statutaire, evh.id, tvh.id
              HAVING
                  si.service_statutaire + SUM(msd.heures * mms.multiplicateur) = 0


  ), ponds AS (
      SELECT
             ep.id                                          element_pedagogique_id,
             MAX(COALESCE( m.ponderation_service_du, 1))    ponderation_service_du,
             MAX(COALESCE( m.ponderation_service_compl, 1)) ponderation_service_compl
      FROM
           element_pedagogique ep
             LEFT JOIN element_modulateur  em ON em.element_id = ep.id
                                                   AND em.histo_destruction IS NULL
             LEFT JOIN modulateur          m ON m.id = em.modulateur_id
      WHERE
          ep.histo_destruction IS NULL
      GROUP BY
               ep.id
  )
  SELECT
         t.id                            id,
         t.service_id                    service_id,
         i.id                            intervenant_id,
         ti.id                           type_intervenant_id,
         i.annee_id                      annee_id,
         his.histo_modification          service_date_modification,
         t.type_volume_horaire_id        type_volume_horaire_id,
         t.etat_volume_horaire_id        etat_volume_horaire_id,
         etab.id                         etablissement_id,
         saff.id                         structure_aff_id,
         sens.id                         structure_ens_id,
         ose_divers.niveau_formation_id_calc( gtf.id, gtf.pertinence_niveau, etp.niveau ) niveau_formation_id,
         etp.id                          etape_id,
         ep.id                           element_pedagogique_id,
         t.periode_id                    periode_id,
         t.type_intervention_id          type_intervention_id,
         t.fonction_referentiel_id       fonction_referentiel_id,

         tvh.libelle || ' ' || evh.libelle type_etat,
         i.source_code                   intervenant_code,
         i.nom_usuel || ' ' || i.prenom  intervenant_nom,
         i.date_naissance                intervenant_date_naissance,
         si.libelle                      intervenant_statut_libelle,
         ti.code                         intervenant_type_code,
         ti.libelle                      intervenant_type_libelle,
         g.source_code                   intervenant_grade_code,
         g.libelle_court                 intervenant_grade_libelle,
         di.source_code                  intervenant_discipline_code,
         di.libelle_court                intervenant_discipline_libelle,
         saff.libelle_court              service_structure_aff_libelle,

         sens.libelle_court              service_structure_ens_libelle,
         etab.libelle                    etablissement_libelle,
         gtf.libelle_court               groupe_type_formation_libelle,
         tf.libelle_court                type_formation_libelle,
         etp.niveau                      etape_niveau,
         etp.source_code                 etape_code,
         etp.libelle                     etape_libelle,
         ep.source_code                  element_code,
         COALESCE(ep.libelle,to_char(t.service_description)) element_libelle,
         de.source_code                  element_discipline_code,
         de.libelle_court                element_discipline_libelle,
         fr.libelle_long                 fonction_referentiel_libelle,
         ep.taux_fi                      element_taux_fi,
         ep.taux_fc                      element_taux_fc,
         ep.taux_fa                      element_taux_fa,
         t.service_ref_formation         service_ref_formation,
         t.commentaires                  commentaires,
         p.libelle_court                 periode_libelle,
         CASE WHEN ponds.ponderation_service_compl = 1 THEN NULL ELSE ponds.ponderation_service_compl END element_ponderation_compl,
         src.libelle                     element_source_libelle,

         t.heures                        heures,
         t.heures_ref                    heures_ref,
         t.heures_non_payees             heures_non_payees,
         si.service_statutaire           service_statutaire,
         fi.heures_service_modifie       service_du_modifie,
         t.service_fi                    service_fi,
         t.service_fa                    service_fa,
         t.service_fc                    service_fc,
         t.service_referentiel           service_referentiel,
         t.heures_compl_fi               heures_compl_fi,
         t.heures_compl_fa               heures_compl_fa,
         t.heures_compl_fc               heures_compl_fc,
         t.heures_compl_fc_majorees      heures_compl_fc_majorees,
         t.heures_compl_referentiel      heures_compl_referentiel,
         t.total                         total,
         t.solde                         solde,
         v.histo_modification            date_cloture_realise

  FROM
       t
         JOIN intervenant                        i ON i.id     = t.intervenant_id AND i.histo_destruction IS NULL
         JOIN statut_intervenant                si ON si.id    = i.statut_id
         JOIN type_intervenant                  ti ON ti.id    = si.type_intervenant_id
         JOIN etablissement                   etab ON etab.id  = t.etablissement_id
         JOIN type_volume_horaire              tvh ON tvh.id   = t.type_volume_horaire_id
         JOIN etat_volume_horaire              evh ON evh.id   = t.etat_volume_horaire_id
         LEFT JOIN histo_intervenant_service   his ON his.intervenant_id = i.id AND his.type_volume_horaire_id = tvh.id AND his.referentiel = 0
         LEFT JOIN grade                         g ON g.id     = i.grade_id
         LEFT JOIN discipline                   di ON di.id    = i.discipline_id
         LEFT JOIN structure                  saff ON saff.id  = i.structure_id AND ti.code = 'P'
         LEFT JOIN element_pedagogique          ep ON ep.id    = t.element_pedagogique_id
         LEFT JOIN discipline                   de ON de.id    = ep.discipline_id
         LEFT JOIN structure                  sens ON sens.id  = NVL(t.structure_ens_id, ep.structure_id)
         LEFT JOIN periode                       p ON p.id     = t.periode_id
         LEFT JOIN source                      src ON src.id   = ep.source_id OR (ep.source_id IS NULL AND src.code = 'OSE')
         LEFT JOIN etape                       etp ON etp.id   = ep.etape_id
         LEFT JOIN type_formation               tf ON tf.id    = etp.type_formation_id AND tf.histo_destruction IS NULL
         LEFT JOIN groupe_type_formation       gtf ON gtf.id   = tf.groupe_id AND gtf.histo_destruction IS NULL
         LEFT JOIN v_formule_intervenant        fi ON fi.intervenant_id = i.id
         LEFT JOIN ponds                     ponds ON ponds.element_pedagogique_id = ep.id
         LEFT JOIN fonction_referentiel         fr ON fr.id    = t.fonction_referentiel_id
         LEFT JOIN type_validation              tv ON tvh.code = 'REALISE' AND tv.code = 'CLOTURE_REALISE'
         LEFT JOIN validation                    v ON v.intervenant_id = i.id AND v.type_validation_id = tv.id AND v.histo_destruction IS NULL;


update etat_sortie set requete = 'SELECT * FROM V_EXPORT_PAIEMENT_WINPAIE'
where requete = 'SELECT epw.*, ''Bonjour'' champ_supp FROM V_EXPORT_PAIEMENT_WINPAIE epw';




CREATE OR REPLACE FORCE VIEW "V_ETAT_PAIEMENT" ("ANNEE_ID", "TYPE_INTERVENANT_ID", "STRUCTURE_ID", "PERIODE_ID", "INTERVENANT_ID", "CENTRE_COUT_ID", "DOMAINE_FONCTIONNEL_ID", "ANNEE", "ETAT", "COMPOSANTE", "DATE_MISE_EN_PAIEMENT", "PERIODE", "STATUT", "INTERVENANT_CODE", "INTERVENANT_NOM", "INTERVENANT_NUMERO_INSEE", "CENTRE_COUT_CODE", "CENTRE_COUT_LIBELLE", "DOMAINE_FONCTIONNEL_CODE", "DOMAINE_FONCTIONNEL_LIBELLE", "HETD", "HETD_POURC", "HETD_MONTANT", "REM_FC_D714", "EXERCICE_AA", "EXERCICE_AA_MONTANT", "EXERCICE_AC", "EXERCICE_AC_MONTANT") AS
  SELECT
         annee_id,
         type_intervenant_id,
         structure_id,
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
         exercice_ac_montant
  FROM
       (
       SELECT
              dep3.*,

              1-CASE WHEN hetd > 0 THEN SUM( hetd_pourc ) OVER ( PARTITION BY periode_id, intervenant_id, etat, structure_id) ELSE 0 END pourc_ecart


       FROM (

            SELECT
                   periode_id,
                   structure_id,
                   type_intervenant_id,
                   intervenant_id,
                   annee_id,
                   centre_cout_id,
                   domaine_fonctionnel_id,
                   etat,
                   composante,
                   date_mise_en_paiement,
                   periode,
                   statut,
                   intervenant_code,
                   intervenant_nom,
                   intervenant_numero_insee,
                   centre_cout_code,
                   centre_cout_libelle,
                   domaine_fonctionnel_code,
                   domaine_fonctionnel_libelle,
                   hetd,
                   ROUND( CASE WHEN hetd > 0 THEN hetd / SUM( hetd ) OVER( PARTITION BY periode_id, intervenant_id, etat, structure_id) ELSE 0 END, 3 ) hetd_pourc,
                   ROUND( hetd * taux_horaire, 2 ) hetd_montant,
                   ROUND( fc_majorees * taux_horaire, 2 ) rem_fc_d714,
                   exercice_aa,
                   ROUND( exercice_aa * taux_horaire, 2 ) exercice_aa_montant,
                   exercice_ac,
                   ROUND( exercice_ac * taux_horaire, 2 ) exercice_ac_montant,


                   (CASE WHEN hetd > 0 THEN hetd / SUM( hetd ) OVER( PARTITION BY periode_id, intervenant_id, etat, structure_id) ELSE 0 END)
                     -
                   ROUND( CASE WHEN hetd > 0 THEN hetd / SUM( hetd ) OVER( PARTITION BY periode_id, intervenant_id, etat, structure_id) ELSE 0 END, 3 ) pourc_diff

            FROM (
                 WITH dep AS ( -- détails par état de paiement
                     SELECT
                            CASE WHEN th.code = 'fc_majorees' THEN 1 ELSE 0 END                 is_fc_majoree,
                            p.id                                                                periode_id,
                            s.id                                                                structure_id,
                            i.id                                                                intervenant_id,
                            i.annee_id                                                          annee_id,
                            cc.id                                                               centre_cout_id,
                            df.id                                                               domaine_fonctionnel_id,
                            ti.id                                                               type_intervenant_id,
                            CASE
                              WHEN mep.date_mise_en_paiement IS NULL THEN 'a-mettre-en-paiement'
                              ELSE 'mis-en-paiement'
                                END                                                                 etat,

                            TRIM(p.libelle_long || ' ' || to_char( add_months( a.date_debut, p.ecart_mois ), 'yyyy' )) periode,
                            mep.date_mise_en_paiement                                           date_mise_en_paiement,
                            s.libelle_court                                                     composante,
                            ti.libelle                                                          statut,
                            i.source_code                                                       intervenant_code,
                            i.nom_usuel || ' ' || i.prenom                                      intervenant_nom,
                            TRIM( NVL(i.numero_insee,'') || NVL(TO_CHAR(i.numero_insee_cle,'00'),'') ) intervenant_numero_insee,
                            cc.source_code                                                      centre_cout_code,
                            cc.libelle                                                          centre_cout_libelle,
                            df.source_code                                                      domaine_fonctionnel_code,
                            df.libelle                                                          domaine_fonctionnel_libelle,
                            CASE WHEN th.code = 'fc_majorees' THEN 0 ELSE mep.heures END        hetd,
                            CASE WHEN th.code = 'fc_majorees' THEN mep.heures ELSE 0 END        fc_majorees,
                            mep.heures * 4 / 10                                                 exercice_aa,
                            mep.heures * 6 / 10                                                 exercice_ac,
                         --CASE WHEN th.code = 'fc_majorees' THEN 0 ELSE mep.heures END * 4 / 10                                                 exercice_aa,
                         --CASE WHEN th.code = 'fc_majorees' THEN 0 ELSE mep.heures END * 6 / 10                                                 exercice_ac,
                            OSE_FORMULE.GET_TAUX_HORAIRE_HETD( NVL(mep.date_mise_en_paiement,SYSDATE) )      taux_horaire
                     FROM
                          v_mep_intervenant_structure  mis
                            JOIN mise_en_paiement        mep ON mep.id = mis.mise_en_paiement_id AND mep.histo_destruction IS NULL
                            JOIN type_heures              th ON  th.id = mep.type_heures_id
                            JOIN centre_cout              cc ON  cc.id = mep.centre_cout_id      -- pas d'historique pour les centres de coût, qui devront tout de même apparaitre mais en erreur
                            JOIN intervenant               i ON   i.id = mis.intervenant_id      AND i.histo_destruction IS NULL
                            JOIN annee                     a ON   a.id = i.annee_id
                            JOIN statut_intervenant       si ON  si.id = i.statut_id
                            JOIN type_intervenant         ti ON  ti.id = si.type_intervenant_id
                            JOIN structure                 s ON   s.id = mis.structure_id
                            LEFT JOIN validation           v ON   v.id = mep.validation_id       AND v.histo_destruction IS NULL
                            LEFT JOIN domaine_fonctionnel df ON  df.id = mis.domaine_fonctionnel_id
                            LEFT JOIN periode              p ON   p.id = mep.periode_paiement_id
                 )
                 SELECT
                        periode_id,
                        structure_id,
                        type_intervenant_id,
                        intervenant_id,
                        annee_id,
                        centre_cout_id,
                        domaine_fonctionnel_id,
                        etat,
                        periode,
                        composante,
                        date_mise_en_paiement,
                        statut,
                        intervenant_code,
                        intervenant_nom,
                        intervenant_numero_insee,
                        centre_cout_code,
                        centre_cout_libelle,
                        domaine_fonctionnel_code,
                        domaine_fonctionnel_libelle,
                        SUM( hetd ) hetd,
                        SUM( fc_majorees ) fc_majorees,
                        SUM( exercice_aa ) exercice_aa,
                        SUM( exercice_ac ) exercice_ac,
                        taux_horaire
                 FROM
                      dep
                 GROUP BY
                          periode_id,
                          structure_id,
                          type_intervenant_id,
                          intervenant_id,
                          annee_id,
                          centre_cout_id,
                          domaine_fonctionnel_id,
                          etat,
                          periode,
                          composante,
                          date_mise_en_paiement,
                          statut,
                          intervenant_code,
                          intervenant_nom,
                          intervenant_numero_insee,
                          centre_cout_code,
                          centre_cout_libelle,
                          domaine_fonctionnel_code,
                          domaine_fonctionnel_libelle,
                          taux_horaire,
                          is_fc_majoree
                 )
                     dep2
            )
                dep3
       )
           dep4
  ORDER BY
           annee_id,
           type_intervenant_id,
           structure_id,
           periode_id,
           intervenant_nom;





CREATE TABLE formule_test_intervenant (
  id                               NUMBER(*,0) NOT NULL,
  annee_id                         NUMBER(*,0) NOT NULL,
  type_intervenant_id              NUMBER(*,0) DEFAULT 1 NOT NULL,
  structure_test_id                NUMBER(*,0) NOT NULL,
  type_volume_horaire_id           NUMBER(*,0) DEFAULT 1 NOT NULL,
  etat_volume_horaire_id           NUMBER(*,0) DEFAULT 1 NOT NULL,
  heures_decharge                  FLOAT NOT NULL,
  heures_service_statutaire        FLOAT DEFAULT 0 NOT NULL,
  depassement_service_du_sans_hc   NUMBER(1) DEFAULT 0 NOT NULL,
  service_du                       FLOAT DEFAULT 0 NOT NULL
)
LOGGING;

ALTER TABLE formule_test_intervenant ADD CONSTRAINT formule_test_intervenant_pk PRIMARY KEY ( id );
CREATE TABLE formule_test_structure (
  id           NUMBER(*,0) NOT NULL,
  libelle      VARCHAR2(80 CHAR) NOT NULL,
  université   NUMBER(1) DEFAULT 0 NOT NULL
)
LOGGING;

ALTER TABLE formule_test_structure ADD CONSTRAINT formule_test_structure_pk PRIMARY KEY ( id );

ALTER TABLE formule_test_structure ADD CONSTRAINT formule_test_structure__un UNIQUE ( libelle );
CREATE TABLE formule_test_volume_horaire (
  id                          NUMBER(*,0) NOT NULL,
  intervenant_test_id         NUMBER(*,0) NOT NULL,
  structure_test_id           NUMBER(*,0) NOT NULL,
  service_statutaire          NUMBER(1) DEFAULT 1 NOT NULL,
  taux_fi                     FLOAT DEFAULT 1 NOT NULL,
  taux_fa                     FLOAT DEFAULT 0 NOT NULL,
  taux_fc                     FLOAT DEFAULT 0 NOT NULL,
  taux_service_du             FLOAT DEFAULT 1 NOT NULL,
  taux_service_compl          FLOAT DEFAULT 1 NOT NULL,
  ponderation_service_du      FLOAT DEFAULT 1 NOT NULL,
  ponderation_service_compl   FLOAT DEFAULT 1 NOT NULL,
  heures                      FLOAT DEFAULT 0 NOT NULL,
  service_fi                  FLOAT,
  service_fa                  FLOAT,
  service_fc                  FLOAT,
  service_referentiel         FLOAT,
  heures_compl_fi             FLOAT,
  heures_compl_fa             FLOAT,
  heures_compl_fc             FLOAT,
  heures_compl_fc_majorees    FLOAT,
  heures_compl_referentiel    FLOAT
)
LOGGING;

ALTER TABLE formule_test_volume_horaire ADD CONSTRAINT formule_test_volume_horaire_pk PRIMARY KEY ( id );
ALTER TABLE formule_test_intervenant
  ADD CONSTRAINT fti_annee_fk FOREIGN KEY ( annee_id )
REFERENCES annee ( id )
ON DELETE CASCADE
  NOT DEFERRABLE;
ALTER TABLE formule_test_intervenant
  ADD CONSTRAINT fti_etat_volume_horaire_fk FOREIGN KEY ( etat_volume_horaire_id )
REFERENCES etat_volume_horaire ( id )
ON DELETE CASCADE
  NOT DEFERRABLE;
ALTER TABLE formule_test_intervenant
  ADD CONSTRAINT fti_type_volume_horaire_fk FOREIGN KEY ( type_volume_horaire_id )
REFERENCES type_volume_horaire ( id )
ON DELETE CASCADE
  NOT DEFERRABLE;
ALTER TABLE formule_test_intervenant
  ADD CONSTRAINT fti_formule_test_structure_fk FOREIGN KEY ( structure_test_id )
REFERENCES formule_test_structure ( id )
ON DELETE CASCADE
  NOT DEFERRABLE;
ALTER TABLE formule_test_intervenant
  ADD CONSTRAINT fti_type_intervenant_fk FOREIGN KEY ( type_intervenant_id )
REFERENCES type_intervenant ( id )
ON DELETE CASCADE
  NOT DEFERRABLE;
ALTER TABLE formule_test_volume_horaire
  ADD CONSTRAINT ftvh_formule_test_interv_fk FOREIGN KEY ( intervenant_test_id )
REFERENCES formule_test_intervenant ( id )
ON DELETE CASCADE
  NOT DEFERRABLE;
ALTER TABLE formule_test_volume_horaire
  ADD CONSTRAINT ftvh_formule_test_structure_fk FOREIGN KEY ( structure_test_id )
REFERENCES formule_test_structure ( id )
ON DELETE CASCADE
  NOT DEFERRABLE;