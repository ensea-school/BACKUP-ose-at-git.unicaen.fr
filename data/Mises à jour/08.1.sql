-- OSE 8.1
-- Mise à jour depuis les versions 8.0 à 8.0.x vers la version 8.1

-- DdlPackage.drop.


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

/

CREATE OR REPLACE FORCE VIEW "V_ETAT_PAIEMENT" AS
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



/

DROP PACKAGE UNICAEN_OSE_FORMULE

/




-- DdlSequence.create.

CREATE SEQUENCE FORMULE_ID_SEQ INCREMENT BY 1 MAXVALUE 9999999999999999999999999999 MINVALUE 1 NOCACHE

/

CREATE SEQUENCE FTEST_INTERVENANT_ID_SEQ INCREMENT BY 1 MAXVALUE 9999999999999999999999999999 MINVALUE 1 NOCACHE

/

CREATE SEQUENCE FTEST_STRUCTURE_ID_SEQ INCREMENT BY 1 MAXVALUE 9999999999999999999999999999 MINVALUE 1 NOCACHE

/

CREATE SEQUENCE FTEST_VOLUME_HORAIRE_ID_SEQ INCREMENT BY 1 MAXVALUE 9999999999999999999999999999 MINVALUE 1 NOCACHE

/




-- DdlTable.create.

CREATE TABLE "FORMULE"
   (	"ID" NUMBER(*,0) NOT NULL ENABLE,
	"LIBELLE" VARCHAR2(100 CHAR) NOT NULL ENABLE,
	"PACKAGE_NAME" VARCHAR2(30 CHAR) NOT NULL ENABLE,
	"PROCEDURE_NAME" VARCHAR2(30 CHAR) NOT NULL ENABLE
   )

/

CREATE TABLE "FORMULE_TEST_INTERVENANT"
   (	"ID" NUMBER(*,0) NOT NULL ENABLE,
	"LIBELLE" VARCHAR2(150 CHAR) NOT NULL ENABLE,
	"FORMULE_ID" NUMBER(*,0) NOT NULL ENABLE,
	"ANNEE_ID" NUMBER(*,0) NOT NULL ENABLE,
	"TYPE_INTERVENANT_ID" NUMBER(*,0) DEFAULT 1 NOT NULL ENABLE,
	"STRUCTURE_TEST_ID" NUMBER(*,0) NOT NULL ENABLE,
	"TYPE_VOLUME_HORAIRE_ID" NUMBER(*,0) DEFAULT 1 NOT NULL ENABLE,
	"ETAT_VOLUME_HORAIRE_ID" NUMBER(*,0) DEFAULT 1 NOT NULL ENABLE,
	"HEURES_DECHARGE" FLOAT(126) NOT NULL ENABLE,
	"HEURES_SERVICE_STATUTAIRE" FLOAT(126) DEFAULT 0 NOT NULL ENABLE,
	"HEURES_SERVICE_MODIFIE" FLOAT(126) DEFAULT 0 NOT NULL ENABLE,
	"DEPASSEMENT_SERVICE_DU_SANS_HC" NUMBER(1,0) DEFAULT 0 NOT NULL ENABLE,
	"PARAM_1" VARCHAR2(50 CHAR),
	"PARAM_2" VARCHAR2(50 CHAR),
	"PARAM_3" VARCHAR2(50 CHAR),
	"PARAM_4" VARCHAR2(50 CHAR),
	"PARAM_5" VARCHAR2(50 CHAR),
	"A_SERVICE_DU" FLOAT(126) DEFAULT 0 NOT NULL ENABLE,
	"C_SERVICE_DU" FLOAT(126),
	"DEBUG_INFO" CLOB
   )

/

CREATE TABLE "FORMULE_TEST_STRUCTURE"
   (	"ID" NUMBER(*,0) NOT NULL ENABLE,
	"LIBELLE" VARCHAR2(80 CHAR) NOT NULL ENABLE,
	"UNIVERSITE" NUMBER(1,0) DEFAULT 0 NOT NULL ENABLE
   )

/

CREATE TABLE "FORMULE_TEST_VOLUME_HORAIRE"
   (	"ID" NUMBER(*,0) NOT NULL ENABLE,
	"INTERVENANT_TEST_ID" NUMBER(*,0) NOT NULL ENABLE,
	"STRUCTURE_TEST_ID" NUMBER(*,0) NOT NULL ENABLE,
	"REFERENTIEL" NUMBER(1,0) DEFAULT 0 NOT NULL ENABLE,
	"SERVICE_STATUTAIRE" NUMBER(1,0) DEFAULT 1 NOT NULL ENABLE,
	"TAUX_FI" FLOAT(126) DEFAULT 1 NOT NULL ENABLE,
	"TAUX_FA" FLOAT(126) DEFAULT 0 NOT NULL ENABLE,
	"TAUX_FC" FLOAT(126) DEFAULT 0 NOT NULL ENABLE,
	"TAUX_SERVICE_DU" FLOAT(126) DEFAULT 1 NOT NULL ENABLE,
	"TAUX_SERVICE_COMPL" FLOAT(126) DEFAULT 1 NOT NULL ENABLE,
	"PONDERATION_SERVICE_DU" FLOAT(126) DEFAULT 1 NOT NULL ENABLE,
	"PONDERATION_SERVICE_COMPL" FLOAT(126) DEFAULT 1 NOT NULL ENABLE,
	"PARAM_1" VARCHAR2(50 CHAR),
	"PARAM_2" VARCHAR2(50 CHAR),
	"PARAM_3" VARCHAR2(50 CHAR),
	"PARAM_4" VARCHAR2(50 CHAR),
	"PARAM_5" VARCHAR2(50 CHAR),
	"HEURES" FLOAT(126) DEFAULT 0 NOT NULL ENABLE,
	"A_SERVICE_FI" FLOAT(126),
	"A_SERVICE_FA" FLOAT(126),
	"A_SERVICE_FC" FLOAT(126),
	"A_SERVICE_REFERENTIEL" FLOAT(126),
	"A_HEURES_COMPL_FI" FLOAT(126),
	"A_HEURES_COMPL_FA" FLOAT(126),
	"A_HEURES_COMPL_FC" FLOAT(126),
	"A_HEURES_COMPL_FC_MAJOREES" FLOAT(126),
	"A_HEURES_COMPL_REFERENTIEL" FLOAT(126),
	"C_SERVICE_FI" FLOAT(126),
	"C_SERVICE_FA" FLOAT(126),
	"C_SERVICE_FC" FLOAT(126),
	"C_SERVICE_REFERENTIEL" FLOAT(126),
	"C_HEURES_COMPL_FI" FLOAT(126),
	"C_HEURES_COMPL_FA" FLOAT(126),
	"C_HEURES_COMPL_FC" FLOAT(126),
	"C_HEURES_COMPL_FC_MAJOREES" FLOAT(126),
	"C_HEURES_COMPL_REFERENTIEL" FLOAT(126),
	"DEBUG_INFO" CLOB
   )

/

CREATE TABLE "LISTE_NOIRE"
   (	"CODE" VARCHAR2(50 CHAR) NOT NULL ENABLE
   )

/




-- DdlPrimaryConstraint.create.

ALTER TABLE FORMULE ADD CONSTRAINT FORMULE_PK PRIMARY KEY (ID) USING INDEX (
	CREATE UNIQUE INDEX FORMULE_PK ON FORMULE(ID ASC)
) ENABLE

/

ALTER TABLE FORMULE_TEST_INTERVENANT ADD CONSTRAINT FORMULE_TEST_INTERVENANT_PK PRIMARY KEY (ID) USING INDEX (
	CREATE UNIQUE INDEX FORMULE_TEST_INTERVENANT_PK ON FORMULE_TEST_INTERVENANT(ID ASC)
) ENABLE

/

ALTER TABLE FORMULE_TEST_STRUCTURE ADD CONSTRAINT FORMULE_TEST_STRUCTURE_PK PRIMARY KEY (ID) USING INDEX (
	CREATE UNIQUE INDEX FORMULE_TEST_STRUCTURE_PK ON FORMULE_TEST_STRUCTURE(ID ASC)
) ENABLE

/

ALTER TABLE FORMULE_TEST_VOLUME_HORAIRE ADD CONSTRAINT FORMULE_TEST_VOLUME_HORAIRE_PK PRIMARY KEY (ID) USING INDEX (
	CREATE UNIQUE INDEX FORMULE_TEST_VOLUME_HORAIRE_PK ON FORMULE_TEST_VOLUME_HORAIRE(ID ASC)
) ENABLE

/

ALTER TABLE LISTE_NOIRE ADD CONSTRAINT INTERVENANT_LISTE_NOIRE_PK PRIMARY KEY (CODE) USING INDEX (
	CREATE UNIQUE INDEX INTERVENANT_LISTE_NOIRE_PK ON LISTE_NOIRE(CODE ASC)
) ENABLE

/




-- DdlPackage.create.

CREATE OR REPLACE PACKAGE "FORMULE_MONTPELLIER" AS

  PROCEDURE CALCUL_RESULTAT;

  FUNCTION calcCell( c VARCHAR2, l NUMERIC ) RETURN FLOAT;

END FORMULE_MONTPELLIER;

/

create or replace PACKAGE BODY FORMULE_MONTPELLIER AS
  decalageLigne NUMERIC DEFAULT 20;


  /* Stockage des valeurs intermédiaires */
  TYPE t_cell IS RECORD (
    valeur FLOAT,
    enCalcul BOOLEAN DEFAULT FALSE
  );
  TYPE t_cells IS TABLE OF t_cell INDEX BY PLS_INTEGER;
  TYPE t_coll IS RECORD (
    cells t_cells
  );
  TYPE t_colls IS TABLE OF t_coll INDEX BY VARCHAR2(50);
  feuille t_colls;

  debugActif BOOLEAN DEFAULT TRUE;
  debugLine NUMERIC;


  PROCEDURE dbg( val CLOB ) IS
  BEGIN
    ose_formule.volumes_horaires.items(debugLine).debug_info :=
      ose_formule.volumes_horaires.items(debugLine).debug_info || val;
  END;


  PROCEDURE dbgi( val CLOB ) IS
  BEGIN
    ose_formule.intervenant.debug_info := ose_formule.intervenant.debug_info || val;
  END;

  PROCEDURE dbgDump( val CLOB ) IS
  BEGIN
    dbg('<div class="dbg-dump">' || val || '</div>');
  END;

  PROCEDURE dbgCell( c VARCHAR2, l NUMERIC, val FLOAT ) IS
    ligne NUMERIC;
  BEGIN
    ligne := l;
    IF l <> 0 THEN
      ligne := ligne + decalageLigne;
    END IF;

    dbgi( '[cell|' || c || '|' || ligne || '|' || val );
  END;

  PROCEDURE dbgCalc( fncName VARCHAR2, c VARCHAR2, res FLOAT ) IS
  BEGIN
    dbgi( '[calc|' || fncName || '|' || c || '|' || res );
  END;

  FUNCTION cell( c VARCHAR2, l NUMERIC DEFAULT 0 ) RETURN FLOAT IS
    val FLOAT;
  BEGIN
    IF feuille.exists(c) THEN
      IF feuille(c).cells.exists(l) THEN
        IF feuille(c).cells(l).enCalcul THEN
          raise_application_error( -20001, 'Dépendance cyclique : la cellule [' || c || ';' || l || '] est déjà en cours de calcul');
        END IF;
        RETURN feuille(c).cells(l).valeur;
      END IF;
    END IF;

    feuille(c).cells(l).enCalcul := true;
    val := calcCell( c, l );
    IF debugActif THEN
      dbgCell( c, l, val );
    END IF;
    feuille(c).cells(l).valeur := val;
    feuille(c).cells(l).enCalcul := false;

    RETURN val;
  END;

  FUNCTION mainCell( libelle VARCHAR2, c VARCHAR2, l NUMERIC ) RETURN FLOAT IS
    val FLOAT;
  BEGIN
    debugLine := l;
    val := cell(c,l);

    RETURN val;
  END;

  FUNCTION calcFnc( fncName VARCHAR2, c VARCHAR2 ) RETURN FLOAT IS
    val FLOAT;
    cellRes FLOAT;
  BEGIN
    IF feuille.exists('__' || fncName || '__' || c || '__') THEN
      IF feuille('__' || fncName || '__' || c || '__').cells.exists(1) THEN
        RETURN feuille('__' || fncName || '__' || c || '__').cells(1).valeur;
      END IF;
    END IF;
    CASE
    -- Liste des fonctions supportées

    WHEN fncName = 'total' THEN
      val := 0;
      FOR l IN 1 .. ose_formule.volumes_horaires.length LOOP
        val := val + COALESCE(cell(c, l),0);
      END LOOP;

    WHEN fncName = 'max' THEN
      val := NULL;
      FOR l IN 1 .. ose_formule.volumes_horaires.length LOOP
        cellRes := cell(c,l);
        IF val IS NULL OR val < cellRes THEN
          val := cellRes;
        END IF;
      END LOOP;

    -- fin de la liste des fonctions supportées
    ELSE
      raise_application_error( -20001, 'La formule "' || fncName || '" n''existe pas!');
    END CASE;
    IF debugActif THEN
      dbgCalc(fncName, c, val );
    END IF;
    feuille('__' || fncName || '__' || c || '__').cells(1).valeur := val;

    RETURN val;
  END;


  FUNCTION calcVersion RETURN NUMERIC IS
  BEGIN
    RETURN 1;
  END;



  FUNCTION calcCell( c VARCHAR2, l NUMERIC ) RETURN FLOAT IS
    vh ose_formule.t_volume_horaire;
    v NUMERIC;
    val FLOAT;
  BEGIN
    v := calcVersion;

    IF l > 0 THEN
      vh := ose_formule.volumes_horaires.items(l);
    END IF;
    CASE


    -- J = SI(ESTVIDE(C21);0;RECHERCHEH(SI(ET(C21="TP";TP_vaut_TD="Oui");"TD";C21);types_intervention;2;0))
    WHEN c = 'j' AND v >= 1 THEN
      RETURN GREATEST(vh.taux_service_du * vh.ponderation_service_du,1);



    -- K = SI(H21="Oui";I21*J21;0)
    WHEN c = 'k' AND v >= 1 THEN
      IF vh.service_statutaire THEN
        RETURN vh.heures * cell('j',l);
      ELSE
        RETURN 0;
      END IF;



    -- l = SI(L20+K21>service_du;service_du;L20+K21)
    WHEN c = 'l' AND v >= 1 THEN
      IF l < 1 THEN
        RETURN 0;
      END IF;
      IF cell('l', l-1) + cell('k',l) > ose_formule.intervenant.service_du THEN
        RETURN ose_formule.intervenant.service_du;
      ELSE
        RETURN cell('l', l-1) + cell('k',l);
      END IF;



    -- m = SI(J21>0;SI(L20+K21<service_du;0;((L20+K21)-service_du)/J21);0)
    WHEN c = 'm' AND v >= 1 THEN
      IF cell('j',l) > 0 THEN
        IF cell('l',l-1) + cell('k',l) < ose_formule.intervenant.service_du THEN
          RETURN 0;
        ELSE
          RETURN (cell('l',l-1) + cell('k',l) - ose_formule.intervenant.service_du) / cell('j',l);
        END IF;
      ELSE
        RETURN 0;
      END IF;



    -- n = SI(ESTVIDE(C21);0;RECHERCHEH(C21;types_intervention;3;0))
    WHEN c = 'n' AND v >= 1 THEN
      RETURN vh.taux_service_compl * vh.ponderation_service_compl;



    -- o = SI(OU(service_realise<service_du;HC_autorisees<>"Oui");0;(M21+SI(H21<>"Oui";I21;0))*N21)
    -- service_realise = MAX($L$21:$L$50)
    -- service_du = ose_formule.intervenant.service_du
    -- HC_autorisees = ose_formule.intervenant.depassement_service_du_sans_hc = false
    WHEN c = 'o' AND v >= 1 THEN
      IF (calcFnc('max','l') < ose_formule.intervenant.service_du) OR ose_formule.intervenant.depassement_service_du_sans_hc THEN
        RETURN 0;
      ELSE
        IF vh.service_statutaire THEN
          RETURN cell('m',l) * cell('n',l);
        ELSE
          RETURN (cell('m',l) + vh.heures) * cell('n',l);
        END IF;
      END IF;



    -- q =SI(ESTVIDE(C21);0;SI(C21="TP";1;RECHERCHEH(C21;types_intervention;2;0)))
    -- Nouvelle interprêtation de la formule : on n'a pas 'TP' donc tout ce qui est <1 devient 1
    WHEN c = 'q' AND v >= 1 THEN
      RETURN GREATEST( vh.taux_service_compl, 1);



    -- r =I21*Q21
    WHEN c = 'r' AND v >= 1 THEN
      RETURN vh.heures * cell('q',l);



    -- r53 =SOMME.SI(B$21:B$50;composante_affectation;R$21:R$50)
    WHEN c = 'r53' AND v >= 1 THEN
      val := 0;
      FOR i IN 1 .. ose_formule.volumes_horaires.length LOOP
        IF ose_formule.volumes_horaires.items(i).structure_is_affectation THEN
          val := val + cell('r',i);
        END IF;
      END LOOP;
      RETURN val;



    -- r54 =SOMME.SI(B$21:B$50;"<>"&composante_affectation;R$21:R$50)
    WHEN c = 'r54' AND v >= 1 THEN
      val := 0;
      FOR i IN 1 .. ose_formule.volumes_horaires.length LOOP
        IF NOT ose_formule.volumes_horaires.items(i).structure_is_affectation THEN
          val := val + cell('r',i);
        END IF;
      END LOOP;
      RETURN val;



    -- s =SI(B21=composante_affectation;SI($R$53=0;0;R21*$S$53/$R$53);SI($R$54=0;0;R21*$S$54/$R$54))
    WHEN c = 's' AND v >= 1 THEN
      IF vh.structure_is_affectation THEN
        IF cell('r53') = 0 THEN
          RETURN 0;
        ELSE
          RETURN cell('r',l) * cell('s53')/cell('r53');
        END IF;
      ELSE
        IF cell('r54') = 0 THEN
          RETURN 0;
        ELSE
          RETURN cell('r',l) * cell('s54')/cell('r54');
        END IF;
      END IF;



    -- s53 =SI(OU(HC=0;R53<service_du);0;R53-service_du)
    WHEN c = 's53' AND v >= 1 THEN
      IF calcFnc('total','o') = 0 OR cell('r53') < ose_formule.intervenant.service_du THEN
        RETURN 0;
      ELSE
        RETURN cell('r53') - ose_formule.intervenant.service_du;
      END IF;



    -- s54 =SI(HC=0;0;R54*(HC-S53)/R54)
    WHEN c = 's54' AND v >= 1 THEN
      IF calcFnc('total','o') = 0 THEN
        RETURN 0;
      ELSE
        RETURN cell('r54')*(calcFnc('total','o')-cell('s53'))/cell('r54');
      END IF;



    -- u =SI(OU(ESTVIDE($C21);$C21="Référentiel");0;($K21-$M21)*D21)
    WHEN c = 'u' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        RETURN (cell('k',l)-cell('m',l))*vh.taux_fi;
      END IF;



    -- v =SI(OU(ESTVIDE($C21);$C21="Référentiel");0;($K21-$M21)*E21)
    WHEN c = 'v' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        RETURN (cell('k',l)-cell('m',l))*vh.taux_fa;
      END IF;




    -- w =SI(OU(ESTVIDE($C21);$C21="Référentiel");0;($K21-$M21)*F21)
    WHEN c = 'w' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN 0;
      ELSE
        RETURN (cell('k',l)-cell('m',l))*vh.taux_fc;
      END IF;



    -- x =SI($C21="Référentiel";$K21-$M21;0)
    WHEN c = 'x' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN cell('k',l) - cell('m',l);
      ELSE
        RETURN 0;
      END IF;



    -- y =SI($C21="Référentiel";0;$S21)
    WHEN c = 'y' AND v >= 1 THEN
      IF vh.volume_horaire_id IS NOT NULL THEN
        RETURN cell('s',l);
      ELSE
        RETURN 0;
      END IF;



    -- z =0
    WHEN c = 'z' AND v >= 1 THEN
      RETURN 0;



    -- aa =0
    WHEN c = 'aa' AND v >= 1 THEN
      RETURN 0;



    -- ab =0
    WHEN c = 'ab' AND v >= 1 THEN
      RETURN 0;



    -- ac =SI($C21="Référentiel";$S21;0)
    WHEN c = 'ac' AND v >= 1 THEN
      IF vh.volume_horaire_ref_id IS NOT NULL THEN
        RETURN cell('s',l);
      ELSE
        RETURN 0;
      END IF;





    ELSE
      raise_application_error( -20001, 'La colonne c=' || c || ', l=' || l || ' n''existe pas!');
  END CASE; END;



  PROCEDURE CALCUL_RESULTAT IS
  BEGIN
    feuille.delete;

    -- transmission des résultats aux volumes horaires et volumes horaires référentiel
    FOR l IN 1 .. ose_formule.volumes_horaires.length LOOP
      ose_formule.volumes_horaires.items(l).service_fi               := mainCell('Service FI', 'u',l);
      ose_formule.volumes_horaires.items(l).service_fa               := mainCell('Service FA', 'v',l);
      ose_formule.volumes_horaires.items(l).service_fc               := mainCell('Service FC', 'w',l);
      ose_formule.volumes_horaires.items(l).service_referentiel      := mainCell('Service référentiel', 'x',l);
      ose_formule.volumes_horaires.items(l).heures_compl_fi          := mainCell('Heures compl. FI', 'y',l);
      ose_formule.volumes_horaires.items(l).heures_compl_fa          := mainCell('Heures compl. FA', 'z',l);
      ose_formule.volumes_horaires.items(l).heures_compl_fc          := mainCell('Heures compl. FC', 'aa',l);
      ose_formule.volumes_horaires.items(l).heures_compl_fc_majorees := mainCell('Heures compl. FC Maj.', 'ab',l);
      ose_formule.volumes_horaires.items(l).heures_compl_referentiel := mainCell('Heures compl. référentiel', 'ac',l);
    END LOOP;
  END;

END FORMULE_MONTPELLIER;

/

CREATE OR REPLACE PACKAGE "FORMULE_UNICAEN" AS
  debug_enabled                BOOLEAN DEFAULT FALSE;
  debug_etat_volume_horaire_id NUMERIC DEFAULT 1;
  debug_volume_horaire_id      NUMERIC;
  debug_volume_horaire_ref_id  NUMERIC;

  PROCEDURE CALCUL_RESULTAT_V2;
  PROCEDURE CALCUL_RESULTAT;

  PROCEDURE PURGE_EM_NON_FC;

END FORMULE_UNICAEN;

/

CREATE OR REPLACE PACKAGE BODY "FORMULE_UNICAEN" AS

  /* Stockage des valeurs intermédiaires */
  TYPE t_valeurs IS TABLE OF FLOAT INDEX BY PLS_INTEGER;
  TYPE t_tableau IS RECORD (
    valeurs t_valeurs,
    total   FLOAT DEFAULT 0
  );
  TYPE t_tableaux       IS TABLE OF t_tableau INDEX BY PLS_INTEGER;
  TYPE t_tableau_config IS RECORD (
    tableau NUMERIC,
    version NUMERIC,
    referentiel BOOLEAN DEFAULT FALSE,
    setTotal BOOLEAN DEFAULT FALSE
  );
  TYPE t_tableaux_configs IS VARRAY(100) OF t_tableau_config;

  t                     t_tableaux;
  vh_index              NUMERIC;



  -- Crée une définition de tableau
  FUNCTION TC( tableau NUMERIC, version NUMERIC, options VARCHAR2 DEFAULT NULL) RETURN t_tableau_config IS
    tcRes t_tableau_config;
  BEGIN
    tcRes.tableau := tableau;
    tcRes.version := version;
    CASE
      WHEN options like '%t%' THEN tcRes.setTotal := TRUE;
      WHEN options like '%r%' THEN tcRes.referentiel := TRUE;
      ELSE RETURN tcRes;
    END CASE;

    RETURN tcRes;
  END;

  -- Setter d'une valeur intermédiaire au niveau case
  PROCEDURE SV( tableau NUMERIC, valeur FLOAT ) IS
  BEGIN
    t(tableau).valeurs(vh_index) := valeur;
    t(tableau).total             := t(tableau).total + valeur;
  END;

  -- Setter d'une valeur intermédiaire au niveau tableau
  PROCEDURE ST( tableau NUMERIC, valeur FLOAT ) IS
  BEGIN
    t(tableau).total      := valeur;
  END;

  -- Getter d'une valeur intermédiaire, au niveau case
  FUNCTION GV( tableau NUMERIC ) RETURN FLOAT IS
  BEGIN
    IF NOT t.exists(tableau) THEN RETURN 0; END IF;
    IF NOT t(tableau).valeurs.exists( vh_index ) THEN RETURN 0; END IF;
    RETURN t(tableau).valeurs( vh_index );
  END;

  -- Getter d'une valeur intermédiaire, au niveau tableau
  FUNCTION GT( tableau NUMERIC ) RETURN FLOAT IS
  BEGIN
    IF NOT t.exists(tableau) THEN RETURN 0; END IF;
    RETURN t(tableau).total;
  END;




  PROCEDURE DEBUG_VH IS
    tableau NUMERIC;
    vh ose_formule.t_volume_horaire;
  BEGIN
    IF NOT debug_enabled THEN RETURN; END IF;
    IF ose_formule.intervenant.etat_volume_horaire_id <> debug_etat_volume_horaire_id THEN RETURN; END IF;

    FOR i IN 1 .. ose_formule.volumes_horaires.length LOOP
      vh_index := i;
      vh := ose_formule.volumes_horaires.items(i);
      IF vh.volume_horaire_id = debug_volume_horaire_id OR vh.volume_horaire_ref_id = debug_volume_horaire_ref_id THEN
        ose_formule.DEBUG_INTERVENANT;
        ose_test.echo('');
        ose_test.echo('-- DEBUG DE VOLUME HORAIRE --');
        ose_test.echo('volume_horaire_id         = ' || vh.volume_horaire_id);
        ose_test.echo('volume_horaire_ref_id     = ' || vh.volume_horaire_ref_id);
        ose_test.echo('service_id                = ' || vh.service_id);
        ose_test.echo('service_referentiel_id    = ' || vh.service_referentiel_id);
        ose_test.echo('taux_fi                   = ' || vh.taux_fi);
        ose_test.echo('taux_fa                   = ' || vh.taux_fa);
        ose_test.echo('taux_fc                   = ' || vh.taux_fc);
        ose_test.echo('ponderation_service_du    = ' || vh.ponderation_service_du);
        ose_test.echo('ponderation_service_compl = ' || vh.ponderation_service_compl);
        ose_test.echo('structure_id              = ' || vh.structure_id);
        ose_test.echo('structure_is_affectation  = ' || CASE WHEN vh.structure_is_affectation THEN 'OUI' ELSE 'NON' END);
        ose_test.echo('structure_is_univ         = ' || CASE WHEN vh.structure_is_univ THEN 'OUI' ELSE 'NON' END);
        ose_test.echo('service_statutaire        = ' || CASE WHEN vh.service_statutaire THEN 'OUI' ELSE 'NON' END);
        ose_test.echo('heures                    = ' || vh.heures);
        ose_test.echo('taux_service_du           = ' || vh.taux_service_du);
        ose_test.echo('taux_service_compl        = ' || vh.taux_service_compl);

        tableau := t.FIRST;
        LOOP EXIT WHEN tableau IS NULL;
          IF gv(tableau) <> 0 OR gt(tableau) <> 0 THEN
            ose_test.echo('     t(' || LPAD(tableau,3,' ') || ') v=' || RPAD(round(gv(tableau),3),10,' ') || 't=' || round(gt(tableau),3));
          END IF;
          tableau := t.NEXT(tableau);
        END LOOP;

        ose_test.echo('service_fi                = ' || vh.service_fi);
        ose_test.echo('service_fa                = ' || vh.service_fa);
        ose_test.echo('service_fc                = ' || vh.service_fc);
        ose_test.echo('service_referentiel       = ' || vh.service_referentiel);
        ose_test.echo('heures_compl_fi           = ' || vh.heures_compl_fi);
        ose_test.echo('heures_compl_fa           = ' || vh.heures_compl_fa);
        ose_test.echo('heures_compl_fc           = ' || vh.heures_compl_fc);
        ose_test.echo('heures_compl_fc_majorees  = ' || vh.heures_compl_fc_majorees);
        ose_test.echo('heures_compl_referentiel  = ' || vh.heures_compl_referentiel);
        ose_test.echo('-- FIN DE DEBUG DE VOLUME HORAIRE --');
        ose_test.echo('');
      END IF;
    END LOOP;
  END;



  -- Formule de calcul définie par tableaux
  FUNCTION EXECFORMULE( tableau NUMERIC, version NUMERIC ) RETURN FLOAT IS
    vh ose_formule.t_volume_horaire;
  BEGIN
    vh := ose_formule.volumes_horaires.items(vh_index);
    CASE


    WHEN tableau = 11 AND version = 2 THEN
      IF vh.structure_is_affectation AND vh.taux_fc < 1 THEN
        RETURN vh.heures;
      ELSE
        RETURN 0;
      END IF;



    WHEN tableau = 11 AND version = 3 THEN
      IF vh.structure_is_affectation THEN
        RETURN vh.heures * (vh.taux_fi + vh.taux_fa);
      ELSE
        RETURN 0;
      END IF;



    WHEN tableau = 12 AND version = 2 THEN
      IF NOT vh.structure_is_affectation AND vh.taux_fc < 1 THEN
        RETURN vh.heures;
      ELSE
        RETURN 0;
      END IF;



    WHEN tableau = 12 AND version = 3 THEN
      IF NOT vh.structure_is_affectation THEN
        RETURN vh.heures * (vh.taux_fi + vh.taux_fa);
      ELSE
        RETURN 0;
      END IF;



    WHEN tableau = 13 AND version = 2 THEN
      IF vh.structure_is_affectation AND vh.taux_fc = 1 THEN
        RETURN vh.heures;
      ELSE
        RETURN 0;
      END IF;



    WHEN tableau = 13 AND version = 3 THEN
      IF vh.structure_is_affectation THEN
        RETURN vh.heures * vh.taux_fc;
      ELSE
        RETURN 0;
      END IF;



    WHEN tableau = 14 AND version = 2 THEN
      IF NOT vh.structure_is_affectation AND vh.taux_fc = 1 THEN
        RETURN vh.heures;
      ELSE
        RETURN 0;
      END IF;



    WHEN tableau = 14 AND version = 3 THEN
      IF NOT vh.structure_is_affectation THEN
        RETURN vh.heures * vh.taux_fc;
      ELSE
        RETURN 0;
      END IF;



    WHEN tableau = 15 AND version = 2 THEN
      IF vh.structure_is_affectation THEN
        RETURN vh.heures;
      ELSE
        RETURN 0;
      END IF;



    WHEN tableau = 16 AND version = 2 THEN
      IF NOT vh.structure_is_affectation AND NOT vh.structure_is_univ THEN
        RETURN vh.heures;
      ELSE
        RETURN 0;
      END IF;



    WHEN tableau = 17 AND version = 2 THEN
      IF vh.structure_is_univ THEN
        RETURN vh.heures;
      ELSE
        RETURN 0;
      END IF;



    WHEN tableau = 21 AND version = 2 THEN
      RETURN gv(11) * vh.taux_service_du;



    WHEN tableau = 22 AND version = 2 THEN
      RETURN gv(12) * vh.taux_service_du;



    WHEN tableau = 23 AND version = 2 THEN
      RETURN gv(13) * vh.taux_service_du;



    WHEN tableau = 24 AND version = 2 THEN
      RETURN gv(14) * vh.taux_service_du;



    WHEN tableau = 25 AND version = 2 THEN
      RETURN gv(15);



    WHEN tableau = 26 AND version = 2 THEN
      RETURN gv(16);



    WHEN tableau = 27 AND version = 2 THEN
      RETURN gv(17);



    WHEN tableau = 31 AND version = 2 THEN
      RETURN GREATEST( ose_formule.intervenant.service_du - gt(21), 0 );



    WHEN tableau = 32 AND version = 2 THEN
      RETURN GREATEST( gt(31) - gt(22), 0 );



    WHEN tableau = 33 AND version = 2 THEN
      RETURN GREATEST( gt(32) - gt(23), 0 );



    WHEN tableau = 34 AND version = 2 THEN
      RETURN GREATEST( gt(33) - gt(24), 0 );



    WHEN tableau = 35 AND version = 2 THEN
      RETURN GREATEST( gt(34) - gt(25), 0 );



    WHEN tableau = 36 AND version = 2 THEN
      RETURN GREATEST( gt(35) - gt(26), 0 );



    WHEN tableau = 37 AND version = 2 THEN
      RETURN GREATEST( gt(36) - gt(27), 0 );



    WHEN tableau = 41 AND version = 2 THEN
      IF gt(21) <> 0 THEN
        RETURN gv(21) / gt(21);
      ELSE
        RETURN 0;
      END IF;



    WHEN tableau = 42 AND version = 2 THEN
      IF gt(22) <> 0 THEN
        RETURN gv(22) / gt(22);
      ELSE
        RETURN 0;
      END IF;



    WHEN tableau = 43 AND version = 2 THEN
      IF gt(23) <> 0 THEN
        RETURN gv(23) / gt(23);
      ELSE
        RETURN 0;
      END IF;



    WHEN tableau = 44 AND version = 2 THEN
      IF gt(24) <> 0 THEN
        RETURN gv(24) / gt(24);
      ELSE
        RETURN 0;
      END IF;



    WHEN tableau = 45 AND version = 2 THEN
      IF gt(25) <> 0 THEN
        RETURN gv(25) / gt(25);
      ELSE
        RETURN 0;
      END IF;



    WHEN tableau = 46 AND version = 2 THEN
      IF gt(26) <> 0 THEN
        RETURN gv(26) / gt(26);
      ELSE
        RETURN 0;
      END IF;



    WHEN tableau = 47 AND version = 2 THEN
      IF gt(27) <> 0 THEN
        RETURN gv(27) / gt(27);
      ELSE
        RETURN 0;
      END IF;



    WHEN tableau = 51 AND version = 2 THEN
      RETURN LEAST( ose_formule.intervenant.service_du, gt(21) ) * gv(41);



    WHEN tableau = 52 AND version = 2 THEN
      RETURN LEAST( gt(31), gt(22) ) * gv(42);



    WHEN tableau = 53 AND version = 2 THEN
      RETURN LEAST( gt(32), gt(23) ) * gv(43);



    WHEN tableau = 54 AND version = 2 THEN
      RETURN LEAST( gt(33), gt(24) ) * gv(44);



    WHEN tableau = 55 AND version = 2 THEN
      RETURN LEAST( gt(34), gt(25) ) * gv(45);



    WHEN tableau = 56 AND version = 2 THEN
      RETURN LEAST( gt(35), gt(26) ) * gv(46);



    WHEN tableau = 57 AND version = 2 THEN
      RETURN LEAST( gt(36), gt(27) ) * gv(47);



    WHEN tableau = 61 AND version = 2 THEN
      RETURN gv(51) * vh.taux_fi;



    WHEN tableau = 61 AND version = 3 THEN
      IF vh.taux_fi + vh.taux_fa > 0 THEN
        RETURN gv(51) / (vh.taux_fi + vh.taux_fa) * vh.taux_fi;
      ELSE
        RETURN 0;
      END IF;



    WHEN tableau = 62 AND version = 2 THEN
      RETURN gv(52) * vh.taux_fi;



    WHEN tableau = 62 AND version = 3 THEN
      IF vh.taux_fi + vh.taux_fa > 0 THEN
        RETURN gv(52) / (vh.taux_fi + vh.taux_fa) * vh.taux_fi;
      ELSE
        RETURN 0;
      END IF;



    WHEN tableau = 71 AND version = 2 THEN
      RETURN gv(51) * vh.taux_fa;



    WHEN tableau = 71 AND version = 3 THEN
      IF vh.taux_fi + vh.taux_fa > 0 THEN
        RETURN gv(51) / (vh.taux_fi + vh.taux_fa) * vh.taux_fa;
      ELSE
        RETURN 0;
      END IF;



    WHEN tableau = 72 AND version = 2 THEN
      RETURN gv(52) * vh.taux_fa;



    WHEN tableau = 72 AND version = 3 THEN
      IF vh.taux_fi + vh.taux_fa > 0 THEN
        RETURN gv(52) / (vh.taux_fi + vh.taux_fa) * vh.taux_fa;
      ELSE
        RETURN 0;
      END IF;



    WHEN tableau = 81 AND version = 2 THEN
      RETURN gv(51) * vh.taux_fc;



    WHEN tableau = 82 AND version = 2 THEN
      RETURN gv(52) * vh.taux_fc;



    WHEN tableau = 83 AND version = 2 THEN
      RETURN gv(53) * vh.taux_fc;



    WHEN tableau = 83 AND version = 3 THEN
      RETURN gv(53);



    WHEN tableau = 84 AND version = 2 THEN
      RETURN gv(54) * vh.taux_fc;



    WHEN tableau = 84 AND version = 3 THEN
      RETURN gv(54);



    WHEN tableau = 91 AND version = 2 THEN
      IF gv(21) <> 0 THEN
        RETURN gv(51) / gv(21);
      ELSE
        RETURN 0;
      END IF;



    WHEN tableau = 92 AND version = 2 THEN
      IF gv(22) <> 0 THEN
        RETURN gv(52) / gv(22);
      ELSE
        RETURN 0;
      END IF;



    WHEN tableau = 93 AND version = 2 THEN
      IF gv(23) <> 0 THEN
        RETURN gv(53) / gv(23);
      ELSE
        RETURN 0;
      END IF;



    WHEN tableau = 94 AND version = 2 THEN
      IF gv(24) <> 0 THEN
        RETURN gv(54) / gv(24);
      ELSE
        RETURN 0;
      END IF;



    WHEN tableau = 95 AND version = 2 THEN
      IF gv(25) <> 0 THEN
        RETURN gv(55) / gv(25);
      ELSE
        RETURN 0;
      END IF;



    WHEN tableau = 96 AND version = 2 THEN
      IF gv(26) <> 0 THEN
        RETURN gv(56) / gv(26);
      ELSE
        RETURN 0;
      END IF;



    WHEN tableau = 97 AND version = 2 THEN
      IF gv(27) <> 0 THEN
        RETURN gv(57) / gv(27);
      ELSE
        RETURN 0;
      END IF;



    WHEN tableau = 101 AND version = 2 THEN
      IF gt(37) <> 0 THEN
        RETURN 0;
      ELSE
        RETURN 1 - gv(91);
      END IF;



    WHEN tableau = 102 AND version = 2 THEN
      IF gt(37) <> 0 THEN
        RETURN 0;
      ELSE
        RETURN 1 - gv(92);
      END IF;



    WHEN tableau = 103 AND version = 2 THEN
      IF gt(37) <> 0 THEN
        RETURN 0;
      ELSE
        RETURN 1 - gv(93);
      END IF;



    WHEN tableau = 104 AND version = 2 THEN
      IF gt(37) <> 0 THEN
        RETURN 0;
      ELSE
        RETURN 1 - gv(94);
      END IF;



    WHEN tableau = 105 AND version = 2 THEN
      IF gt(37) <> 0 THEN
        RETURN 0;
      ELSE
        RETURN 1 - gv(95);
      END IF;



    WHEN tableau = 106 AND version = 2 THEN
      IF gt(37) <> 0 THEN
        RETURN 0;
      ELSE
        RETURN 1 - gv(96);
      END IF;



    WHEN tableau = 107 AND version = 2 THEN
      IF gt(37) <> 0 THEN
        RETURN 0;
      ELSE
        RETURN 1 - gv(97);
      END IF;



    WHEN tableau = 111 AND version = 2 THEN
      RETURN gv(11) * vh.taux_service_compl * gv(101);



    WHEN tableau = 112 AND version = 2 THEN
      RETURN gv(12) * vh.taux_service_compl * gv(102);



    WHEN tableau = 113 AND version = 2 THEN
      RETURN gv(13) * vh.taux_service_compl * gv(103);



    WHEN tableau = 114 AND version = 2 THEN
      RETURN gv(14) * vh.taux_service_compl * gv(104);



    WHEN tableau = 115 AND version = 2 THEN
      RETURN gv(15) * gv(105);



    WHEN tableau = 116 AND version = 2 THEN
      RETURN gv(16) * gv(106);



    WHEN tableau = 117 AND version = 2 THEN
      RETURN gv(17) * gv(107);



    WHEN tableau = 123 AND version = 2 THEN
      IF vh.taux_fc = 1 THEN
        RETURN gv(113) * vh.ponderation_service_compl;
      ELSE
        RETURN gv(113);
      END IF;



    WHEN tableau = 123 AND version = 3 THEN
      IF vh.taux_fc > 0 THEN
        RETURN gv(113) * vh.ponderation_service_compl;
      ELSE
        RETURN gv(113);
      END IF;



    WHEN tableau = 124 AND version = 2 THEN
      IF vh.taux_fc = 1 THEN
        RETURN gv(114) * vh.ponderation_service_compl;
      ELSE
        RETURN gv(114);
      END IF;



    WHEN tableau = 124 AND version = 3 THEN
      IF vh.taux_fc > 0 THEN
        RETURN gv(114) * vh.ponderation_service_compl;
      ELSE
        RETURN gv(114);
      END IF;



    WHEN tableau = 131 AND version = 2 THEN
      RETURN gv(111) * vh.taux_fi;



    WHEN tableau = 131 AND version = 3 THEN
      IF vh.taux_fi + vh.taux_fa > 0 THEN
        RETURN gv(111) / (vh.taux_fi + vh.taux_fa) * vh.taux_fi;
      ELSE
        RETURN 0;
      END IF;



    WHEN tableau = 132 AND version = 2 THEN
      RETURN gv(112) * vh.taux_fi;



    WHEN tableau = 132 AND version = 3 THEN
      IF vh.taux_fi + vh.taux_fa > 0 THEN
        RETURN gv(112) / (vh.taux_fi + vh.taux_fa) * vh.taux_fi;
      ELSE
        RETURN 0;
      END IF;



    WHEN tableau = 141 AND version = 2 THEN
      RETURN gv(111) * vh.taux_fa;



    WHEN tableau = 141 AND version = 3 THEN
      IF vh.taux_fi + vh.taux_fa > 0 THEN
        RETURN gv(111) / (vh.taux_fi + vh.taux_fa) * vh.taux_fa;
      ELSE
        RETURN 0;
      END IF;



    WHEN tableau = 142 AND version = 2 THEN
      RETURN gv(112) * vh.taux_fa;



    WHEN tableau = 142 AND version = 3 THEN
      IF vh.taux_fi + vh.taux_fa > 0 THEN
        RETURN gv(112) / (vh.taux_fi + vh.taux_fa) * vh.taux_fa;
      ELSE
        RETURN 0;
      END IF;



    WHEN tableau = 151 AND version = 2 THEN
      RETURN gv(111) * vh.taux_fc;



    WHEN tableau = 152 AND version = 2 THEN
      RETURN gv(112) * vh.taux_fc;



    WHEN tableau = 153 AND version = 2 THEN
      IF gv(123) = gv(113) THEN
        RETURN gv(113) * vh.taux_fc;
      ELSE
        RETURN 0;
      END IF;



    WHEN tableau = 153 AND version = 3 THEN
      IF gv(123) = gv(113) THEN
        RETURN gv(113);
      ELSE
        RETURN 0;
      END IF;



    WHEN tableau = 154 AND version = 2 THEN
      IF gv(124) = gv(114) THEN
        RETURN gv(114) * vh.taux_fc;
      ELSE
        RETURN 0;
      END IF;



    WHEN tableau = 154 AND version = 3 THEN
      IF gv(124) = gv(114) THEN
        RETURN gv(114);
      ELSE
        RETURN 0;
      END IF;



    WHEN tableau = 163 AND version = 2 THEN
      IF gv(123) <> gv(113) THEN
        RETURN gv(123) * vh.taux_fc;
      ELSE
        RETURN 0;
      END IF;



    WHEN tableau = 163 AND version = 3 THEN
      IF gv(123) <> gv(113) THEN
        RETURN gv(123);
      ELSE
        RETURN 0;
      END IF;



    WHEN tableau = 164 AND version = 2 THEN
      IF gv(124) <> gv(114) THEN
        RETURN gv(124) * vh.taux_fc;
      ELSE
        RETURN 0;
      END IF;



    WHEN tableau = 164 AND version = 3 THEN
      IF gv(124) <> gv(114) THEN
        RETURN gv(124);
      ELSE
        RETURN 0;
      END IF;



    ELSE
      raise_application_error( -20001, 'Le tableau ' || tableau || ' version ' || version || ' n''existe pas!');
  END CASE; END;







  PROCEDURE CALCUL_RESULTAT_V2 IS
    tableaux       t_tableaux_configs;
    valeur         FLOAT;
  BEGIN

    -- Définition des tableaux à utiliser
    tableaux := t_tableaux_configs(
      tc( 11,2    ), tc( 12,2    ), tc( 13,2    ), tc( 14,2    ), tc( 15,2,'r' ), tc( 16,2,'r' ), tc( 17,2,'r' ),
      tc( 21,2    ), tc( 22,2    ), tc( 23,2    ), tc( 24,2    ), tc( 25,2,'r' ), tc( 26,2,'r' ), tc( 27,2,'r' ),
      tc( 31,2,'t'), tc( 32,2,'t'), tc( 33,2,'t'), tc( 34,2,'t'), tc( 35,2,'tr'), tc( 36,2,'tr'), tc( 37,2,'tr'),
      tc( 41,2    ), tc( 42,2    ), tc( 43,2    ), tc( 44,2    ), tc( 45,2,'r' ), tc( 46,2,'r' ), tc( 47,2,'r' ),
      tc( 51,2    ), tc( 52,2    ), tc( 53,2    ), tc( 54,2    ), tc( 55,2,'r' ), tc( 56,2,'r' ), tc( 57,2,'r' ),
      tc( 61,2    ), tc( 62,2    ),
      tc( 71,2    ), tc( 72,2    ),
      tc( 81,2    ), tc( 82,2    ), tc( 83,2    ), tc( 84,2    ),
      tc( 91,2    ), tc( 92,2    ), tc( 93,2    ), tc( 94,2    ), tc( 95,2,'r' ), tc( 96,2,'r' ), tc( 97,2,'r' ),
      tc(101,2    ), tc(102,2    ), tc(103,2    ), tc(104,2    ), tc(105,2,'r' ), tc(106,2,'r' ), tc(107,2,'r' ),
      tc(111,2    ), tc(112,2    ), tc(113,2    ), tc(114,2    ), tc(115,2,'r' ), tc(116,2,'r' ), tc(117,2,'r' ),
                                    tc(123,2    ), tc(124,2    ),
      tc(131,2    ), tc(132,2    ),
      tc(141,2    ), tc(142,2    ),
      tc(151,2    ), tc(152,2    ), tc(153,2    ), tc(154,2    ),
                                    tc(163,2    ), tc(164,2    )
    );

    -- calcul par tableau pour chaque volume horaire
    t.delete;
    FOR it IN tableaux.FIRST .. tableaux.LAST LOOP
      FOR ivh IN 1 .. ose_formule.volumes_horaires.length LOOP
        vh_index := ivh;
        IF
          ose_formule.volumes_horaires.items(ivh).service_id IS NOT NULL AND NOT tableaux(it).referentiel
          OR ose_formule.volumes_horaires.items(ivh).service_referentiel_id IS NOT NULL AND tableaux(it).referentiel
          OR tableaux(it).setTotal -- car on en a besoin tout le temps
        THEN
          valeur := EXECFORMULE(tableaux(it).tableau, tableaux(it).version);
          IF tableaux(it).setTotal THEN
            ST( tableaux(it).tableau, valeur );
          ELSE
            SV( tableaux(it).tableau, valeur );
          END IF;
        END IF;
      END LOOP;
    END LOOP;

    -- transmisssion des résultats aux volumes horaires et volumes horaires référentiel
    FOR i IN 1 .. ose_formule.volumes_horaires.length LOOP
      vh_index := i;
      IF ose_formule.volumes_horaires.items(i).service_id IS NOT NULL THEN
        ose_formule.volumes_horaires.items(i).service_fi               := gv( 61) + gv( 62);
        ose_formule.volumes_horaires.items(i).service_fa               := gv( 71) + gv( 72);
        ose_formule.volumes_horaires.items(i).service_fc               := gv( 81) + gv( 82) + gv( 83) + gv( 84);
        ose_formule.volumes_horaires.items(i).heures_compl_fi          := gv(131) + gv(132);
        ose_formule.volumes_horaires.items(i).heures_compl_fa          := gv(141) + gv(142);
        ose_formule.volumes_horaires.items(i).heures_compl_fc          := gv(151) + gv(152) + gv(153) + gv(154);
        ose_formule.volumes_horaires.items(i).heures_compl_fc_majorees :=                     gv(163) + gv(164);
      ELSIF ose_formule.volumes_horaires.items(i).service_referentiel_id IS NOT NULL THEN
        ose_formule.volumes_horaires.items(i).service_referentiel      := gv( 55) + gv( 56) + gv( 57);
        ose_formule.volumes_horaires.items(i).heures_compl_referentiel := gv(115) + gv(116) + gv(117);
      END IF;
    END LOOP;

    DEBUG_VH;
  END;



  PROCEDURE CALCUL_RESULTAT IS
    tableaux       t_tableaux_configs;
    valeur         FLOAT;
  BEGIN
    -- si l'année est antérieure à 2016/2017 alors on utilise la V2!!
    IF ose_formule.intervenant.annee_id < 2016 THEN
      CALCUL_RESULTAT_V2;
      RETURN;
    END IF;


    -- Définition des tableaux à utiliser
    tableaux := t_tableaux_configs(
      tc( 11,3    ), tc( 12,3    ), tc( 13,3    ), tc( 14,3    ), tc( 15,2,'r' ), tc( 16,2,'r' ), tc( 17,2,'r' ),
      tc( 21,2    ), tc( 22,2    ), tc( 23,2    ), tc( 24,2    ), tc( 25,2,'r' ), tc( 26,2,'r' ), tc( 27,2,'r' ),
      tc( 31,2,'t'), tc( 32,2,'t'), tc( 33,2,'t'), tc( 34,2,'t'), tc( 35,2,'tr'), tc( 36,2,'tr'), tc( 37,2,'tr'),
      tc( 41,2    ), tc( 42,2    ), tc( 43,2    ), tc( 44,2    ), tc( 45,2,'r' ), tc( 46,2,'r' ), tc( 47,2,'r' ),
      tc( 51,2    ), tc( 52,2    ), tc( 53,2    ), tc( 54,2    ), tc( 55,2,'r' ), tc( 56,2,'r' ), tc( 57,2,'r' ),
      tc( 61,3    ), tc( 62,3    ),
      tc( 71,3    ), tc( 72,3    ),
                                    tc( 83,3    ), tc( 84,3    ),
      tc( 91,2    ), tc( 92,2    ), tc( 93,2    ), tc( 94,2    ), tc( 95,2,'r' ), tc( 96,2,'r' ), tc( 97,2,'r' ),
      tc(101,2    ), tc(102,2    ), tc(103,2    ), tc(104,2    ), tc(105,2,'r' ), tc(106,2,'r' ), tc(107,2,'r' ),
      tc(111,2    ), tc(112,2    ), tc(113,2    ), tc(114,2    ), tc(115,2,'r' ), tc(116,2,'r' ), tc(117,2,'r' ),
                                    tc(123,3    ), tc(124,3    ),
      tc(131,3    ), tc(132,3    ),
      tc(141,3    ), tc(142,3    ),
                                    tc(153,3    ), tc(154,3    ),
                                    tc(163,3    ), tc(164,3    )
    );

    -- calcul par tableau pour chaque volume horaire
    t.delete;
    FOR it IN tableaux.FIRST .. tableaux.LAST LOOP
      FOR ivh IN 1 .. ose_formule.volumes_horaires.length LOOP
        vh_index := ivh;
        IF
          ose_formule.volumes_horaires.items(ivh).service_id IS NOT NULL AND NOT tableaux(it).referentiel
          OR ose_formule.volumes_horaires.items(ivh).service_referentiel_id IS NOT NULL AND tableaux(it).referentiel
          OR tableaux(it).setTotal -- car on en a besoin tout le temps
        THEN
          valeur := EXECFORMULE(tableaux(it).tableau, tableaux(it).version);
          IF tableaux(it).setTotal THEN
            ST( tableaux(it).tableau, valeur );
          ELSE
            SV( tableaux(it).tableau, valeur );
          END IF;
        END IF;
      END LOOP;
    END LOOP;

    -- transmission des résultats aux volumes horaires et volumes horaires référentiel
    FOR i IN 1 .. ose_formule.volumes_horaires.length LOOP
      vh_index := i;
      IF ose_formule.volumes_horaires.items(i).service_id IS NOT NULL THEN
        ose_formule.volumes_horaires.items(i).service_fi               := gv( 61) + gv( 62);
        ose_formule.volumes_horaires.items(i).service_fa               := gv( 71) + gv( 72);
        ose_formule.volumes_horaires.items(i).service_fc               := gv( 83) + gv( 84);
        ose_formule.volumes_horaires.items(i).heures_compl_fi          := gv(131) + gv(132);
        ose_formule.volumes_horaires.items(i).heures_compl_fa          := gv(141) + gv(142);
        ose_formule.volumes_horaires.items(i).heures_compl_fc          := gv(153) + gv(154);
        ose_formule.volumes_horaires.items(i).heures_compl_fc_majorees := gv(163) + gv(164);
      ELSIF ose_formule.volumes_horaires.items(i).service_referentiel_id IS NOT NULL THEN
        ose_formule.volumes_horaires.items(i).service_referentiel      := gv( 55) + gv( 56) + gv( 57);
        ose_formule.volumes_horaires.items(i).heures_compl_referentiel := gv(115) + gv(116) + gv(117);
      END IF;
    END LOOP;

    DEBUG_VH;
  END;



  PROCEDURE PURGE_EM_NON_FC IS
  BEGIN
    FOR em IN (
      SELECT
        em.id
      FROM
        ELEMENT_MODULATEUR em
        JOIN element_pedagogique ep ON ep.id = em.element_id AND ep.histo_destruction IS NULL
      WHERE
        em.histo_destruction IS NULL
        AND ep.taux_fc < 1
    ) LOOP
      UPDATE
        element_modulateur
      SET
        histo_destruction = SYSDATE,
        histo_destructeur_id = ose_parametre.get_ose_user
      WHERE
        id = em.id
      ;
    END LOOP;
  END;


END FORMULE_UNICAEN;

/




-- DdlRefConstraint.create.

ALTER TABLE FORMULE_TEST_INTERVENANT ADD CONSTRAINT FTI_ANNEE_FK FOREIGN KEY (ANNEE_ID)
        REFERENCES ANNEE (ID) ON DELETE CASCADE ENABLE

/

ALTER TABLE FORMULE_TEST_INTERVENANT ADD CONSTRAINT FTI_ETAT_VOLUME_HORAIRE_FK FOREIGN KEY (ETAT_VOLUME_HORAIRE_ID)
        REFERENCES ETAT_VOLUME_HORAIRE (ID) ON DELETE CASCADE ENABLE

/

ALTER TABLE FORMULE_TEST_INTERVENANT ADD CONSTRAINT FTI_FORMULE_FK FOREIGN KEY (FORMULE_ID)
        REFERENCES FORMULE (ID) ON DELETE CASCADE ENABLE

/

ALTER TABLE FORMULE_TEST_INTERVENANT ADD CONSTRAINT FTI_FORMULE_TEST_STRUCTURE_FK FOREIGN KEY (STRUCTURE_TEST_ID)
        REFERENCES FORMULE_TEST_STRUCTURE (ID) ON DELETE CASCADE ENABLE

/

ALTER TABLE FORMULE_TEST_INTERVENANT ADD CONSTRAINT FTI_TYPE_INTERVENANT_FK FOREIGN KEY (TYPE_INTERVENANT_ID)
        REFERENCES TYPE_INTERVENANT (ID) ON DELETE CASCADE ENABLE

/

ALTER TABLE FORMULE_TEST_INTERVENANT ADD CONSTRAINT FTI_TYPE_VOLUME_HORAIRE_FK FOREIGN KEY (TYPE_VOLUME_HORAIRE_ID)
        REFERENCES TYPE_VOLUME_HORAIRE (ID) ON DELETE CASCADE ENABLE

/

ALTER TABLE FORMULE_TEST_VOLUME_HORAIRE ADD CONSTRAINT FTVH_FORMULE_TEST_INTERV_FK FOREIGN KEY (INTERVENANT_TEST_ID)
        REFERENCES FORMULE_TEST_INTERVENANT (ID) ON DELETE CASCADE ENABLE

/

ALTER TABLE FORMULE_TEST_VOLUME_HORAIRE ADD CONSTRAINT FTVH_FORMULE_TEST_STRUCTURE_FK FOREIGN KEY (STRUCTURE_TEST_ID)
        REFERENCES FORMULE_TEST_STRUCTURE (ID) ON DELETE CASCADE ENABLE

/




-- DdlUniqueConstraint.create.

ALTER TABLE FORMULE_TEST_STRUCTURE ADD CONSTRAINT FORMULE_TEST_STRUCTURE__UN UNIQUE (LIBELLE) USING INDEX (
	CREATE UNIQUE INDEX FORMULE_TEST_STRUCTURE__UN ON FORMULE_TEST_STRUCTURE(LIBELLE ASC)
) ENABLE

/

ALTER TABLE FORMULE ADD CONSTRAINT FORMULE__UN UNIQUE (LIBELLE) USING INDEX (
	CREATE UNIQUE INDEX FORMULE__UN ON FORMULE(LIBELLE ASC)
) ENABLE

/




-- DdlIndex.create.

CREATE UNIQUE INDEX FORMULE_PK ON FORMULE (ID)

/

CREATE UNIQUE INDEX FORMULE_TEST_INTERVENANT_PK ON FORMULE_TEST_INTERVENANT (ID)

/

CREATE UNIQUE INDEX FORMULE_TEST_STRUCTURE_PK ON FORMULE_TEST_STRUCTURE (ID)

/

CREATE UNIQUE INDEX FORMULE_TEST_STRUCTURE__UN ON FORMULE_TEST_STRUCTURE (LIBELLE)

/

CREATE UNIQUE INDEX FORMULE_TEST_VOLUME_HORAIRE_PK ON FORMULE_TEST_VOLUME_HORAIRE (ID)

/

CREATE UNIQUE INDEX FORMULE__UN ON FORMULE (LIBELLE)

/

CREATE UNIQUE INDEX INTERVENANT_LISTE_NOIRE_PK ON LISTE_NOIRE (CODE)

/




-- DdlPackage.alter.

CREATE OR REPLACE PACKAGE "OSE_DIVERS" AS

  PROCEDURE CALCULER_TABLEAUX_BORD;

  FUNCTION GET_OSE_UTILISATEUR_ID RETURN NUMERIC;
  FUNCTION GET_OSE_SOURCE_ID RETURN NUMERIC;

  FUNCTION INTERVENANT_HAS_PRIVILEGE( intervenant_id NUMERIC, privilege_name VARCHAR2 ) RETURN NUMERIC;

  FUNCTION implode(i_query VARCHAR2, i_seperator VARCHAR2 DEFAULT ',') RETURN VARCHAR2;

  PROCEDURE intervenant_horodatage_service( INTERVENANT_ID NUMERIC, TYPE_VOLUME_HORAIRE_ID NUMERIC, REFERENTIEL NUMERIC, HISTO_MODIFICATEUR_ID NUMERIC, HISTO_MODIFICATION DATE );

  FUNCTION NIVEAU_FORMATION_ID_CALC( gtf_id NUMERIC, gtf_pertinence_niveau NUMERIC, niveau NUMERIC DEFAULT NULL ) RETURN NUMERIC;

  FUNCTION STR_REDUCE( str CLOB ) RETURN CLOB;

  FUNCTION STR_FIND( haystack CLOB, needle VARCHAR2 ) RETURN NUMERIC;

  FUNCTION LIKED( haystack CLOB, needle CLOB ) RETURN NUMERIC;

  FUNCTION CALCUL_TAUX_FI( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT;

  FUNCTION CALCUL_TAUX_FC( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT;

  FUNCTION CALCUL_TAUX_FA( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT;

  PROCEDURE SYNC_LOG( msg CLOB );

  FUNCTION FORMATTED_RIB (bic VARCHAR2, iban VARCHAR2) RETURN VARCHAR2;

  FUNCTION FORMATTED_ADRESSE(
    no_voie                VARCHAR2,
    nom_voie               VARCHAR2,
    batiment               VARCHAR2,
    mention_complementaire VARCHAR2,
    localite               VARCHAR2,
    code_postal            VARCHAR2,
    ville                  VARCHAR2,
    pays_libelle           VARCHAR2)
  RETURN VARCHAR2;

  PROCEDURE CALCUL_FEUILLE_DE_ROUTE( CONDS CLOB );

  FUNCTION GET_TRIGGER_BODY( TRIGGER_NAME VARCHAR2 ) RETURN VARCHAR2;
END OSE_DIVERS;

/

CREATE OR REPLACE PACKAGE BODY "OSE_DIVERS" AS
  OSE_UTILISATEUR_ID NUMERIC;
  OSE_SOURCE_ID NUMERIC;




PROCEDURE CALCULER_TABLEAUX_BORD IS
BEGIN
  FOR d IN (
    SELECT tbl_name
    FROM tbl
    WHERE tbl_name <> 'formule' -- TROP LONG !!
    ORDER BY ordre
  )
  LOOP
    UNICAEN_TBL.CALCULER(d.tbl_name);
    dbms_output.put_line('Calcul du tableau de bord "' || d.tbl_name || '" effectué');
    COMMIT;
  END LOOP;
END;



FUNCTION GET_OSE_UTILISATEUR_ID RETURN NUMERIC IS
BEGIN
  IF OSE_DIVERS.OSE_UTILISATEUR_ID IS NULL THEN
    SELECT
      to_number(valeur) INTO OSE_DIVERS.OSE_UTILISATEUR_ID
    FROM
      parametre
    WHERE
      nom = 'oseuser';
  END IF;

  RETURN OSE_DIVERS.OSE_UTILISATEUR_ID;
END;



FUNCTION GET_OSE_SOURCE_ID RETURN NUMERIC IS
BEGIN
  IF OSE_DIVERS.OSE_SOURCE_ID IS NULL THEN
    SELECT
      id INTO OSE_DIVERS.OSE_SOURCE_ID
    FROM
      source
    WHERE
      code = 'OSE';
  END IF;

  RETURN OSE_DIVERS.OSE_SOURCE_ID;
END;



FUNCTION INTERVENANT_HAS_PRIVILEGE( intervenant_id NUMERIC, privilege_name VARCHAR2 ) RETURN NUMERIC IS
  statut statut_intervenant%rowtype;
  itype  type_intervenant%rowtype;
  res NUMERIC;
BEGIN
  res := 1;
  SELECT si.* INTO statut FROM statut_intervenant si JOIN intervenant i ON i.statut_id = si.id WHERE i.id = intervenant_id;
  SELECT ti.* INTO itype  FROM type_intervenant ti WHERE ti.id = statut.type_intervenant_id;

  /* DEPRECATED */
  IF 'saisie_service' = privilege_name THEN
    res := statut.peut_saisir_service;
    RETURN res;
  ELSIF 'saisie_service_exterieur' = privilege_name THEN
    --IF INTERVENANT_HAS_PRIVILEGE( intervenant_id, 'saisie_service' ) = 0 OR itype.code = 'E' THEN -- cascade
    IF itype.code = 'E' THEN
      res := 0;
    END IF;
    RETURN res;
  ELSIF 'saisie_service_referentiel' = privilege_name THEN
    IF itype.code = 'E' THEN
      res := 0;
    END IF;
    RETURN res;
  ELSIF 'saisie_service_referentiel_autre_structure' = privilege_name THEN
    res := 1;
    RETURN res;
  ELSIF 'saisie_motif_non_paiement' = privilege_name THEN
    res := statut.peut_saisir_motif_non_paiement;
    RETURN res;
  END IF;
  /* FIN DE DEPRECATED */

  SELECT
    count(*)
  INTO
    res
  FROM
    intervenant i
    JOIN statut_privilege sp ON sp.statut_id = i.statut_id
    JOIN privilege p ON p.id = sp.privilege_id
    JOIN categorie_privilege cp ON cp.id = p.categorie_id
  WHERE
    i.id = INTERVENANT_HAS_PRIVILEGE.intervenant_id
    AND cp.code || '-' || p.code = privilege_name;

  RETURN res;
END;

FUNCTION implode(i_query VARCHAR2, i_seperator VARCHAR2 DEFAULT ',') RETURN VARCHAR2 AS
  l_return CLOB:='';
  l_temp CLOB;
  TYPE r_cursor is REF CURSOR;
  rc r_cursor;
BEGIN
  OPEN rc FOR i_query;
  LOOP
    FETCH rc INTO L_TEMP;
    EXIT WHEN RC%NOTFOUND;
    l_return:=l_return||L_TEMP||i_seperator;
  END LOOP;
  RETURN RTRIM(l_return,i_seperator);
END;

PROCEDURE intervenant_horodatage_service( INTERVENANT_ID NUMERIC, TYPE_VOLUME_HORAIRE_ID NUMERIC, REFERENTIEL NUMERIC, HISTO_MODIFICATEUR_ID NUMERIC, HISTO_MODIFICATION DATE ) AS
BEGIN
    MERGE INTO histo_intervenant_service his USING dual ON (

          his.INTERVENANT_ID                = intervenant_horodatage_service.INTERVENANT_ID
      AND NVL(his.TYPE_VOLUME_HORAIRE_ID,0) = NVL(intervenant_horodatage_service.TYPE_VOLUME_HORAIRE_ID,0)
      AND his.REFERENTIEL                   = intervenant_horodatage_service.REFERENTIEL

    ) WHEN MATCHED THEN UPDATE SET

      HISTO_MODIFICATEUR_ID = intervenant_horodatage_service.HISTO_MODIFICATEUR_ID,
      HISTO_MODIFICATION = intervenant_horodatage_service.HISTO_MODIFICATION

    WHEN NOT MATCHED THEN INSERT (

      ID,
      INTERVENANT_ID,
      TYPE_VOLUME_HORAIRE_ID,
      REFERENTIEL,
      HISTO_MODIFICATEUR_ID,
      HISTO_MODIFICATION
    ) VALUES (
      HISTO_INTERVENANT_SERVI_ID_SEQ.NEXTVAL,
      intervenant_horodatage_service.INTERVENANT_ID,
      intervenant_horodatage_service.TYPE_VOLUME_HORAIRE_ID,
      intervenant_horodatage_service.REFERENTIEL,
      intervenant_horodatage_service.HISTO_MODIFICATEUR_ID,
      intervenant_horodatage_service.HISTO_MODIFICATION

    );
END;


FUNCTION NIVEAU_FORMATION_ID_CALC( gtf_id NUMERIC, gtf_pertinence_niveau NUMERIC, niveau NUMERIC DEFAULT NULL ) RETURN NUMERIC AS
BEGIN
  IF 1 <> gtf_pertinence_niveau OR niveau IS NULL OR niveau < 1 OR gtf_id < 1 THEN RETURN NULL; END IF;
  RETURN gtf_id * 256 + niveau;
END;

FUNCTION STR_REDUCE( str CLOB ) RETURN CLOB IS
BEGIN
  RETURN utl_raw.cast_to_varchar2((nlssort(str, 'nls_sort=binary_ai')));
END;

FUNCTION STR_FIND( haystack CLOB, needle VARCHAR2 ) RETURN NUMERIC IS
BEGIN
  IF STR_REDUCE( haystack ) LIKE STR_REDUCE( '%' || needle || '%' ) THEN RETURN 1; END IF;
  RETURN 0;
END;

FUNCTION LIKED( haystack CLOB, needle CLOB ) RETURN NUMERIC IS
BEGIN
  RETURN CASE WHEN STR_REDUCE(haystack) LIKE STR_REDUCE(needle) THEN 1 ELSE 0 END;
END;

PROCEDURE DO_NOTHING IS
BEGIN
  RETURN;
END;

PROCEDURE CALCUL_TAUX( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, r_fi OUT FLOAT, r_fc OUT FLOAT, r_fa OUT FLOAT, arrondi NUMERIC DEFAULT 15 ) IS
  nt FLOAT;
  bi FLOAT;
  bc FLOAT;
  ba FLOAT;
  reste FLOAT;
BEGIN
  bi := eff_fi * fi;
  bc := eff_fc * fc;
  ba := eff_fa * fa;
  nt := bi + bc + ba;

  IF nt = 0 THEN -- au cas ou, alors on ne prend plus en compte les effectifs!!
    bi := fi;
    bc := fc;
    ba := fa;
    nt := bi + bc + ba;
  END IF;

  IF nt = 0 THEN -- toujours au cas ou...
    bi := 1;
    bc := 0;
    ba := 0;
    nt := bi + bc + ba;
  END IF;

  -- Calcul
  r_fi := bi / nt;
  r_fc := bc / nt;
  r_fa := ba / nt;

  -- Arrondis
  r_fi := ROUND( r_fi, arrondi );
  r_fc := ROUND( r_fc, arrondi );
  r_fa := ROUND( r_fa, arrondi );

  -- détermination du reste
  reste := 1 - r_fi - r_fc - r_fa;

  -- répartition éventuelle du reste
  IF reste <> 0 THEN
    IF r_fi > 0 THEN r_fi := r_fi + reste;
    ELSIF r_fc > 0 THEN r_fc := r_fc + reste;
    ELSE r_fa := r_fa + reste; END IF;
  END IF;

END;


FUNCTION CALCUL_TAUX_FI( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT IS
  ri FLOAT;
  rc FLOAT;
  ra FLOAT;
BEGIN
  CALCUL_TAUX( eff_fi, eff_fc, eff_fa, fi, fc, fa, ri, rc, ra, arrondi );
  RETURN ri;
END;

FUNCTION CALCUL_TAUX_FC( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT IS
  ri FLOAT;
  rc FLOAT;
  ra FLOAT;
BEGIN
  CALCUL_TAUX( eff_fi, eff_fc, eff_fa, fi, fc, fa, ri, rc, ra, arrondi );
  RETURN rc;
END;

FUNCTION CALCUL_TAUX_FA( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT IS
  ri FLOAT;
  rc FLOAT;
  ra FLOAT;
BEGIN
  CALCUL_TAUX( eff_fi, eff_fc, eff_fa, fi, fc, fa, ri, rc, ra, arrondi );
  RETURN ra;
END;

PROCEDURE SYNC_LOG( msg CLOB ) IS
BEGIN
  INSERT INTO SYNC_LOG( id, date_sync, message ) VALUES ( sync_log_id_seq.nextval, systimestamp, msg );
END;

FUNCTION FORMATTED_RIB (bic VARCHAR2, iban VARCHAR2) RETURN VARCHAR2 IS
BEGIN
  if bic is null and iban is null then
    return null;
  end if;
  RETURN regexp_replace(bic, '[[:space:]]+', '') || '-' || regexp_replace(iban, '[[:space:]]+', '');
END;

FUNCTION FORMATTED_ADRESSE(
    no_voie                VARCHAR2,
    nom_voie               VARCHAR2,
    batiment               VARCHAR2,
    mention_complementaire VARCHAR2,
    localite               VARCHAR2,
    code_postal            VARCHAR2,
    ville                  VARCHAR2,
    pays_libelle           VARCHAR2)
  RETURN VARCHAR2
IS
BEGIN
  return
    -- concaténation des éléments non null séparés par ', '
    trim(trim(',' FROM REPLACE(', ' || NVL(no_voie,'#') || ', ' || NVL(nom_voie,'#') || ', ' || NVL(batiment,'#') || ', ' || NVL(mention_complementaire,'#'), ', #', ''))) ||
    -- saut de ligne complet
    chr(13) || chr(10) ||
    -- concaténation des éléments non null séparés par ', '
    trim(trim(',' FROM REPLACE(', ' || NVL(localite,'#') || ', ' || NVL(code_postal,'#') || ', ' || NVL(ville,'#') || ', ' || NVL(pays_libelle,'#'), ', #', '')));
END;



PROCEDURE CALCUL_FEUILLE_DE_ROUTE( CONDS CLOB ) IS
BEGIN
  FOR d IN (
    SELECT   tbl_name
    FROM     tbl
    WHERE    feuille_de_route = 1
    ORDER BY ordre
  ) LOOP
    UNICAEN_TBL.CALCULER(d.tbl_name,CONDS);
  END LOOP;
END;



FUNCTION GET_TRIGGER_BODY( TRIGGER_NAME VARCHAR2 ) RETURN VARCHAR2 IS
  vlong long;
BEGIN
  SELECT trigger_body INTO vlong FROM all_triggers WHERE trigger_name = GET_TRIGGER_BODY.TRIGGER_NAME;

  RETURN substr(vlong, 1, 32767);
END;

END OSE_DIVERS;

/

CREATE OR REPLACE PACKAGE "OSE_EVENT" AS

  PROCEDURE ON_AFTER_FORMULE_CALC( INTERVENANT_ID NUMERIC );

END OSE_EVENT;

/

CREATE OR REPLACE PACKAGE BODY "OSE_EVENT" AS

  PROCEDURE ON_AFTER_FORMULE_CALC( INTERVENANT_ID NUMERIC ) IS
    p unicaen_tbl.t_params;
  BEGIN
    p := UNICAEN_TBL.make_params('INTERVENANT_ID', ON_AFTER_FORMULE_CALC.intervenant_id);
/*
    UNICAEN_TBL.CALCULER( 'agrement', p );
    UNICAEN_TBL.CALCULER( 'paiement', p );
    UNICAEN_TBL.CALCULER( 'workflow', p );*/
  END;

END OSE_EVENT;

/

CREATE OR REPLACE PACKAGE "OSE_FORMULE" AS

  TYPE t_intervenant IS RECORD (
    id                             NUMERIC,
    annee_id                       NUMERIC,
    structure_id                   NUMERIC,
    type_volume_horaire_id         NUMERIC,
    etat_volume_horaire_id         NUMERIC,

    heures_decharge                FLOAT DEFAULT 0,
    heures_service_statutaire      FLOAT DEFAULT 0,
    heures_service_modifie         FLOAT DEFAULT 0,
    depassement_service_du_sans_hc BOOLEAN DEFAULT FALSE,
    type_intervenant_code          VARCHAR(2),

    service_du                     FLOAT,
    debug_info                     CLOB
  );

  TYPE t_volume_horaire IS RECORD (
    -- identifiants
    volume_horaire_id          NUMERIC,
    volume_horaire_ref_id      NUMERIC,
    service_id                 NUMERIC,
    service_referentiel_id     NUMERIC,
    structure_id               NUMERIC,

    -- paramètres
    structure_is_affectation   BOOLEAN DEFAULT TRUE,
    structure_is_univ          BOOLEAN DEFAULT FALSE,
    service_statutaire         BOOLEAN DEFAULT TRUE,
    taux_fi                    FLOAT DEFAULT 1,
    taux_fa                    FLOAT DEFAULT 0,
    taux_fc                    FLOAT DEFAULT 0,

    -- pondérations et heures

    taux_service_du            FLOAT DEFAULT 1, -- en fonction des types d'intervention
    taux_service_compl         FLOAT DEFAULT 1, -- en fonction des types d'intervention
    ponderation_service_du     FLOAT DEFAULT 1, -- relatif aux modulateurs
    ponderation_service_compl  FLOAT DEFAULT 1, -- relatif aux modulateurs
    heures                     FLOAT DEFAULT 0, -- heures réelles saisies

    -- résultats
    service_fi                 FLOAT DEFAULT 0,
    service_fa                 FLOAT DEFAULT 0,
    service_fc                 FLOAT DEFAULT 0,
    service_referentiel        FLOAT DEFAULT 0,
    heures_compl_fi            FLOAT DEFAULT 0,
    heures_compl_fa            FLOAT DEFAULT 0,
    heures_compl_fc            FLOAT DEFAULT 0,
    heures_compl_fc_majorees   FLOAT DEFAULT 0,
    heures_compl_referentiel   FLOAT DEFAULT 0,

    debug_info                 CLOB
  );
  TYPE t_lst_volume_horaire IS TABLE OF t_volume_horaire INDEX BY PLS_INTEGER;
  TYPE t_volumes_horaires IS RECORD (
    length NUMERIC DEFAULT 0,
    items t_lst_volume_horaire
  );

  intervenant      t_intervenant;
  volumes_horaires t_volumes_horaires;

  FUNCTION GET_INTERVENANT_ID RETURN NUMERIC;

  FUNCTION GET_TAUX_HORAIRE_HETD( DATE_OBS DATE DEFAULT NULL ) RETURN FLOAT;
  PROCEDURE UPDATE_ANNEE_TAUX_HETD;

  PROCEDURE CALCULER( INTERVENANT_ID NUMERIC );
  PROCEDURE CALCULER_TOUT( ANNEE_ID NUMERIC DEFAULT NULL );        -- mise à jour de TOUTES les données ! ! ! !
  PROCEDURE CALCULER_TBL( PARAMS UNICAEN_TBL.T_PARAMS );

  PROCEDURE TEST( INTERVENANT_TEST_ID NUMERIC );
  PROCEDURE TEST_TOUT;

  PROCEDURE DEBUG_INTERVENANT;
  PROCEDURE DEBUG_VOLUMES_HORAIRES(VOLUME_HORAIRE_ID NUMERIC DEFAULT NULL);
END OSE_FORMULE;

/

CREATE OR REPLACE PACKAGE BODY "OSE_FORMULE" AS

  TYPE t_lst_vh_etats IS TABLE OF t_volumes_horaires INDEX BY PLS_INTEGER;
  TYPE t_lst_vh_types IS TABLE OF t_lst_vh_etats INDEX BY PLS_INTEGER;

  TYPE t_resultat IS RECORD (
    id                         NUMERIC,
    formule_resultat_id        NUMERIC,
    type_volume_horaire_id     NUMERIC,
    etat_volume_horaire_id     NUMERIC,
    service_id                 NUMERIC,
    service_referentiel_id     NUMERIC,
    volume_horaire_id          NUMERIC,
    volume_horaire_ref_id      NUMERIC,
    structure_id               NUMERIC,

    service_fi                 FLOAT DEFAULT 0,
    service_fa                 FLOAT DEFAULT 0,
    service_fc                 FLOAT DEFAULT 0,
    service_referentiel        FLOAT DEFAULT 0,
    heures_compl_fi            FLOAT DEFAULT 0,
    heures_compl_fa            FLOAT DEFAULT 0,
    heures_compl_fc            FLOAT DEFAULT 0,
    heures_compl_fc_majorees   FLOAT DEFAULT 0,
    heures_compl_referentiel   FLOAT DEFAULT 0,

    changed                    BOOLEAN DEFAULT FALSE,
    debug_info                 CLOB
  );
  TYPE t_resultats IS TABLE OF t_resultat INDEX BY VARCHAR2(15);

  all_volumes_horaires t_lst_vh_types;
  arrondi NUMERIC DEFAULT 2;
  t_res t_resultats;



  FUNCTION GET_INTERVENANT_ID RETURN NUMERIC IS
  BEGIN
    RETURN intervenant.id;
  END;



  FUNCTION GET_TAUX_HORAIRE_HETD( DATE_OBS DATE DEFAULT NULL ) RETURN FLOAT IS
    taux_hetd FLOAT;
  BEGIN
    SELECT valeur INTO taux_hetd
    FROM taux_horaire_hetd t
    WHERE
      DATE_OBS BETWEEN t.histo_creation AND COALESCE(t.histo_destruction,GREATEST(SYSDATE,DATE_OBS))
      AND rownum = 1
    ORDER BY
      histo_creation DESC;
    RETURN taux_hetd;
  END;



  PROCEDURE UPDATE_ANNEE_TAUX_HETD IS
  BEGIN
    UPDATE annee SET taux_hetd = GET_TAUX_HORAIRE_HETD(date_fin);
  END;



  PROCEDURE LOAD_INTERVENANT_FROM_BDD IS
    dsdushc NUMERIC DEFAULT 0;
  BEGIN
    intervenant.service_du := 0;

    SELECT
      intervenant_id,
      annee_id,
      structure_id,
      type_intervenant_code,
      heures_service_statutaire,
      depassement_service_du_sans_hc,
      heures_service_modifie,
      heures_decharge
    INTO
      intervenant.id,
      intervenant.annee_id,
      intervenant.structure_id,
      intervenant.type_intervenant_code,
      intervenant.heures_service_statutaire,
      dsdushc,
      intervenant.heures_service_modifie,
      intervenant.heures_decharge
    FROM
      v_formule_intervenant fi
    WHERE
      fi.intervenant_id = intervenant.id;

    intervenant.depassement_service_du_sans_hc := (dsdushc = 1);
    intervenant.service_du := CASE
      WHEN intervenant.depassement_service_du_sans_hc -- HC traitées comme du service
        OR intervenant.heures_decharge < 0 -- s'il y a une décharge => aucune HC

      THEN 9999
      ELSE intervenant.heures_service_statutaire + intervenant.heures_service_modifie
    END;

    EXCEPTION WHEN NO_DATA_FOUND THEN
      intervenant.id                             := NULL;
      intervenant.annee_id                       := null;
      intervenant.structure_id                   := null;
      intervenant.heures_service_statutaire      := 0;
      intervenant.depassement_service_du_sans_hc := FALSE;
      intervenant.heures_service_modifie         := 0;
      intervenant.heures_decharge                := 0;
      intervenant.type_intervenant_code          := 'E';
      intervenant.service_du                     := 0;
  END;



  PROCEDURE LOAD_INTERVENANT_FROM_TEST IS
    dsdushc NUMERIC DEFAULT 0;
  BEGIN
    SELECT
      fti.id,
      fti.annee_id,
      fti.structure_test_id,
      fti.type_volume_horaire_id,
      fti.etat_volume_horaire_id,
      fti.heures_decharge,
      fti.heures_service_statutaire,
      fti.heures_service_modifie,
      fti.depassement_service_du_sans_hc,
      fti.a_service_du,
      ti.code
    INTO
      intervenant.id,
      intervenant.annee_id,
      intervenant.structure_id,
      intervenant.type_volume_horaire_id,
      intervenant.etat_volume_horaire_id,
      intervenant.heures_decharge,
      intervenant.heures_service_statutaire,
      intervenant.heures_service_modifie,
      dsdushc,
      intervenant.service_du,
      intervenant.type_intervenant_code
    FROM
      formule_test_intervenant fti
      JOIN type_intervenant ti ON ti.id = fti.type_intervenant_id
    WHERE
      fti.id = intervenant.id;

    intervenant.depassement_service_du_sans_hc := (dsdushc = 1);
    intervenant.service_du := CASE
      WHEN intervenant.depassement_service_du_sans_hc -- HC traitées comme du service
        OR intervenant.heures_decharge < 0 -- s'il y a une décharge => aucune HC

      THEN 9999
      ELSE intervenant.heures_service_statutaire + intervenant.heures_service_modifie
    END;

    EXCEPTION WHEN NO_DATA_FOUND THEN
      intervenant.id                             := NULL;
      intervenant.annee_id                       := null;
      intervenant.structure_id                   := null;
      intervenant.heures_service_statutaire      := 0;
      intervenant.depassement_service_du_sans_hc := FALSE;
      intervenant.heures_service_modifie         := 0;
      intervenant.heures_decharge                := 0;
      intervenant.type_intervenant_code          := 'E';
      intervenant.service_du                     := 0;
  END;



  PROCEDURE LOAD_VH_FROM_BDD IS
    vh t_volume_horaire;
    etat_volume_horaire_id NUMERIC DEFAULT 1;
    structure_univ NUMERIC;
    length NUMERIC;
  BEGIN
    all_volumes_horaires.delete;

    SELECT to_number(valeur) INTO structure_univ FROM parametre WHERE nom = 'structure_univ';

    FOR d IN (
      SELECT *
      FROM   v_formule_volume_horaire fvh
      WHERE  fvh.intervenant_id = intervenant.id
    ) LOOP
      vh.volume_horaire_id         := d.volume_horaire_id;
      vh.volume_horaire_ref_id     := d.volume_horaire_ref_id;
      vh.service_id                := d.service_id;
      vh.service_referentiel_id    := d.service_referentiel_id;
      vh.taux_fi                   := d.taux_fi;
      vh.taux_fa                   := d.taux_fa;
      vh.taux_fc                   := d.taux_fc;
      vh.ponderation_service_du    := d.ponderation_service_du;
      vh.ponderation_service_compl := d.ponderation_service_compl;
      vh.structure_id              := d.structure_id;
      vh.structure_is_affectation  := NVL(d.structure_id,0) = NVL(intervenant.structure_id,-1);
      vh.structure_is_univ         := NVL(d.structure_id,0) = NVL(structure_univ,-1);
      vh.service_statutaire        := d.service_statutaire = 1;
      vh.heures                    := d.heures;
      vh.taux_service_du           := d.taux_service_du;
      vh.taux_service_compl        := d.taux_service_compl;

      FOR etat_volume_horaire_id IN 1 .. d.etat_volume_horaire_id LOOP
        BEGIN
          length := all_volumes_horaires(d.type_volume_horaire_id)(etat_volume_horaire_id).length;
        EXCEPTION WHEN NO_DATA_FOUND THEN
          length := 0;
        END;
        length := length + 1;
        all_volumes_horaires(d.type_volume_horaire_id)(etat_volume_horaire_id).length := length;
        all_volumes_horaires(d.type_volume_horaire_id)(etat_volume_horaire_id).items(length) := vh;
      END LOOP;
    END LOOP;
  END;



  PROCEDURE LOAD_VH_FROM_TEST IS
    vh t_volume_horaire;
    etat_volume_horaire_id NUMERIC DEFAULT 1;
    structure_univ NUMERIC;
    length NUMERIC;
  BEGIN
    volumes_horaires.items.delete;
    length := 0;

    SELECT id INTO structure_univ FROM formule_test_structure WHERE universite = 1;

    FOR d IN (
      SELECT *
      FROM   formule_test_volume_horaire ftvh
      WHERE  ftvh.intervenant_test_id = intervenant.id
      ORDER BY ftvh.id
    ) LOOP
      length := length + 1;
      volumes_horaires.length := length;

      IF d.referentiel = 0 THEN
        volumes_horaires.items(length).volume_horaire_id       := d.id;
        volumes_horaires.items(length).service_id              := d.id;
      ELSE
        volumes_horaires.items(length).volume_horaire_ref_id   := d.id;
        volumes_horaires.items(length).service_referentiel_id  := d.id;
      END IF;
      volumes_horaires.items(length).taux_fi                   := d.taux_fi;
      volumes_horaires.items(length).taux_fa                   := d.taux_fa;
      volumes_horaires.items(length).taux_fc                   := d.taux_fc;
      volumes_horaires.items(length).ponderation_service_du    := d.ponderation_service_du;
      volumes_horaires.items(length).ponderation_service_compl := d.ponderation_service_compl;
      volumes_horaires.items(length).structure_id              := d.structure_test_id;
      volumes_horaires.items(length).structure_is_affectation  := NVL(d.structure_test_id,0) = NVL(intervenant.structure_id,-1);
      volumes_horaires.items(length).structure_is_univ         := NVL(d.structure_test_id,0) = NVL(structure_univ,-1);
      volumes_horaires.items(length).service_statutaire        := d.service_statutaire = 1;
      volumes_horaires.items(length).heures                    := d.heures;
      volumes_horaires.items(length).taux_service_du           := d.taux_service_du;
      volumes_horaires.items(length).taux_service_compl        := d.taux_service_compl;
    END LOOP;
  END;



  PROCEDURE tres_add_heures( code VARCHAR2, vh t_volume_horaire, tvh NUMERIC, evh NUMERIC) IS
  BEGIN
    IF NOT t_res.exists(code) THEN
      t_res(code).service_fi               := 0;
      t_res(code).service_fa               := 0;
      t_res(code).service_fc               := 0;
      t_res(code).service_referentiel      := 0;
      t_res(code).heures_compl_fi          := 0;
      t_res(code).heures_compl_fa          := 0;
      t_res(code).heures_compl_fc          := 0;
      t_res(code).heures_compl_fc_majorees := 0;
      t_res(code).heures_compl_referentiel := 0;
    END IF;

    t_res(code).service_fi               := t_res(code).service_fi               + vh.service_fi;
    t_res(code).service_fa               := t_res(code).service_fa               + vh.service_fa;
    t_res(code).service_fc               := t_res(code).service_fc               + vh.service_fc;
    t_res(code).service_referentiel      := t_res(code).service_referentiel      + vh.service_referentiel;
    t_res(code).heures_compl_fi          := t_res(code).heures_compl_fi          + vh.heures_compl_fi;
    t_res(code).heures_compl_fa          := t_res(code).heures_compl_fa          + vh.heures_compl_fa;
    t_res(code).heures_compl_fc          := t_res(code).heures_compl_fc          + vh.heures_compl_fc;
    t_res(code).heures_compl_fc_majorees := t_res(code).heures_compl_fc_majorees + vh.heures_compl_fc_majorees;
    t_res(code).heures_compl_referentiel := t_res(code).heures_compl_referentiel + vh.heures_compl_referentiel;

    t_res(code).type_volume_horaire_id := tvh;
    t_res(code).etat_volume_horaire_id := evh;
  END;

  PROCEDURE DEBUG_TRES IS
    code varchar2(15);
    table_name varchar2(30);
    fr formule_resultat%rowtype;
    frs formule_resultat_service%rowtype;
    frsr formule_resultat_service_ref%rowtype;
    frvh formule_resultat_vh%rowtype;
    frvhr formule_resultat_vh_ref%rowtype;
  BEGIN
    code := t_res.FIRST;
    LOOP EXIT WHEN code IS NULL;
      table_name := CASE
        WHEN code LIKE '%-s-%' THEN 'FORMULE_RESULTAT_SERVICE'
        WHEN code LIKE '%-sr-%' THEN 'FORMULE_RESULTAT_SERVICE_REF'
        WHEN code LIKE '%-vh-%' THEN 'FORMULE_RESULTAT_VH'
        WHEN code LIKE '%-vhr-%' THEN 'FORMULE_RESULTAT_VH_REF'
        ELSE 'FORMULE_RESULTAT'
      END;

      ose_test.echo('T_RES( ' || code || ' - Table ' || table_name || ' ) ');
      ose_test.echo('  id = ' || t_res(code).id);
      ose_test.echo('  formule_resultat_id      = ' || t_res(code).formule_resultat_id);
      ose_test.echo('  type_volume_horaire_id   = ' || t_res(code).type_volume_horaire_id);
      ose_test.echo('  etat_volume_horaire_id   = ' || t_res(code).etat_volume_horaire_id);
      ose_test.echo('  volume_horaire_id        = ' || t_res(code).volume_horaire_id);
      ose_test.echo('  volume_horaire_ref_id    = ' || t_res(code).volume_horaire_ref_id);
      ose_test.echo('  service_id               = ' || t_res(code).service_id);
      ose_test.echo('  service_referentiel_id   = ' || t_res(code).service_referentiel_id);
      ose_test.echo('  structure_id             = ' || t_res(code).structure_id);
      ose_test.echo('  service_fi               = ' || t_res(code).service_fi);
      ose_test.echo('  service_fa               = ' || t_res(code).service_fa);
      ose_test.echo('  service_fc               = ' || t_res(code).service_fc);
      ose_test.echo('  service_referentiel      = ' || t_res(code).service_referentiel);
      ose_test.echo('  heures_compl_fi          = ' || t_res(code).heures_compl_fi);
      ose_test.echo('  heures_compl_fa          = ' || t_res(code).heures_compl_fa);
      ose_test.echo('  heures_compl_fc          = ' || t_res(code).heures_compl_fc);
      ose_test.echo('  heures_compl_fc_majorees = ' || t_res(code).heures_compl_fc_majorees);
      ose_test.echo('  heures_compl_referentiel = ' || t_res(code).heures_compl_referentiel);

      code := t_res.NEXT(code);
    END LOOP;
  END;

  PROCEDURE SAVE_TO_BDD IS
    bcode VARCHAR(15);
    code VARCHAR(15);
    type_volume_horaire_id NUMERIC;
    etat_volume_horaire_id NUMERIC;
    vh t_volume_horaire;
    fr formule_resultat%rowtype;
    frs formule_resultat_service%rowtype;
    frsr formule_resultat_service_ref%rowtype;
    frvh formule_resultat_vh%rowtype;
    frvhr formule_resultat_vh_ref%rowtype;
  BEGIN
    t_res.delete;

    /* On préinitialise avec ce qui existe déjà */
    FOR d IN (
      SELECT
        fr.type_volume_horaire_id || '-' || fr.etat_volume_horaire_id code,
        fr.id                       id,
        fr.id                       formule_resultat_id,
        fr.type_volume_horaire_id   type_volume_horaire_id,
        fr.etat_volume_horaire_id   etat_volume_horaire_id,
        null                        service_id,
        null                        service_referentiel_id,
        null                        volume_horaire_id,
        null                        volume_horaire_ref_id

      FROM
        formule_resultat fr
      WHERE
        fr.intervenant_id = intervenant.id

      UNION ALL SELECT
        fr.type_volume_horaire_id || '-' || fr.etat_volume_horaire_id || '-s-' || frs.service_id code,
        frs.id                      id,
        fr.id                       formule_resultat_id,
        fr.type_volume_horaire_id   type_volume_horaire_id,
        fr.etat_volume_horaire_id   etat_volume_horaire_id,
        frs.service_id              service_id,
        null                        service_referentiel_id,
        null                        volume_horaire_id,
        null                        volume_horaire_ref_id
      FROM
        formule_resultat_service frs
        JOIN formule_resultat fr ON fr.id = frs.formule_resultat_id
      WHERE
        fr.intervenant_id = intervenant.id

      UNION ALL SELECT
        fr.type_volume_horaire_id || '-' || fr.etat_volume_horaire_id || '-sr-' || frsr.service_referentiel_id code,
        frsr.id                     id,
        fr.id                       formule_resultat_id,
        fr.type_volume_horaire_id   type_volume_horaire_id,
        fr.etat_volume_horaire_id   etat_volume_horaire_id,
        null                        service_id,
        frsr.service_referentiel_id service_referentiel_id,
        null                        volume_horaire_id,
        null                        volume_horaire_ref_id
      FROM
        formule_resultat_service_ref frsr
        JOIN formule_resultat fr ON fr.id = frsr.formule_resultat_id
      WHERE
        fr.intervenant_id = intervenant.id

      UNION ALL SELECT
        fr.type_volume_horaire_id || '-' || fr.etat_volume_horaire_id || '-vh-' || frvh.volume_horaire_id code,
        frvh.id                     id,
        fr.id                       formule_resultat_id,
        fr.type_volume_horaire_id   type_volume_horaire_id,
        fr.etat_volume_horaire_id   etat_volume_horaire_id,
        null                        service_id,
        null                        service_referentiel_id,
        frvh.volume_horaire_id      volume_horaire_id,
        null                        volume_horaire_ref_id
      FROM
        formule_resultat_vh frvh
        JOIN formule_resultat fr ON fr.id = frvh.formule_resultat_id
      WHERE
        fr.intervenant_id = intervenant.id

      UNION ALL SELECT
        fr.type_volume_horaire_id || '-' || fr.etat_volume_horaire_id || '-vhr-' || frvhr.volume_horaire_ref_id code,
        frvhr.id                    id,
        fr.id                       formule_resultat_id,
        fr.type_volume_horaire_id   type_volume_horaire_id,
        fr.etat_volume_horaire_id   etat_volume_horaire_id,
        null                        service_id,
        null                        service_referentiel_id,
        null                        volume_horaire_id,
        frvhr.volume_horaire_ref_id volume_horaire_ref_id
      FROM
        formule_resultat_vh_ref frvhr
        JOIN formule_resultat fr ON fr.id = frvhr.formule_resultat_id
      WHERE
        fr.intervenant_id = intervenant.id
    ) LOOP
      t_res(d.code).id                     := d.id;
      t_res(d.code).formule_resultat_id    := d.formule_resultat_id;
      t_res(d.code).type_volume_horaire_id := d.type_volume_horaire_id;
      t_res(d.code).etat_volume_horaire_id := d.etat_volume_horaire_id;
      t_res(d.code).service_id             := d.service_id;
      t_res(d.code).service_referentiel_id := d.service_referentiel_id;
      t_res(d.code).volume_horaire_id      := d.volume_horaire_id;
      t_res(d.code).volume_horaire_ref_id  := d.volume_horaire_ref_id;
    END LOOP;

    /* On charge avec les résultats de formule */
    type_volume_horaire_id := all_volumes_horaires.FIRST;
    LOOP EXIT WHEN type_volume_horaire_id IS NULL;
      etat_volume_horaire_id := all_volumes_horaires(type_volume_horaire_id).FIRST;
      LOOP EXIT WHEN etat_volume_horaire_id IS NULL;
        FOR i IN 1 .. all_volumes_horaires(type_volume_horaire_id)(etat_volume_horaire_id).length LOOP
          vh := all_volumes_horaires(type_volume_horaire_id)(etat_volume_horaire_id).items(i);
          bcode := type_volume_horaire_id || '-' || etat_volume_horaire_id;

          -- formule_resultat
          code := bcode;
          tres_add_heures(code,vh, type_volume_horaire_id, etat_volume_horaire_id);

          -- formule_resultat_service
          IF vh.service_id IS NOT NULL THEN
            code := bcode || '-s-' || vh.service_id;
            t_res(code).service_id := vh.service_id;
            tres_add_heures(code,vh, type_volume_horaire_id, etat_volume_horaire_id);
          END IF;

          -- formule_resultat_service_ref
          IF vh.service_referentiel_id IS NOT NULL THEN
            code := bcode || '-sr-' || vh.service_referentiel_id;
            t_res(code).service_referentiel_id := vh.service_referentiel_id;
            tres_add_heures(code,vh, type_volume_horaire_id, etat_volume_horaire_id);
          END IF;

          -- formule_resultat_volume_horaire
          IF vh.volume_horaire_id IS NOT NULL THEN
            code := bcode || '-vh-' || vh.volume_horaire_id;
            t_res(code).volume_horaire_id := vh.volume_horaire_id;
            tres_add_heures(code,vh, type_volume_horaire_id, etat_volume_horaire_id);
          END IF;

          -- formule_resultat_volume_horaire_ref
          IF vh.volume_horaire_ref_id IS NOT NULL THEN
            code := bcode || '-vhr-' || vh.volume_horaire_ref_id;
            t_res(code).volume_horaire_ref_id := vh.volume_horaire_ref_id;
            tres_add_heures(code,vh, type_volume_horaire_id, etat_volume_horaire_id);
          END IF;

        END LOOP;
        etat_volume_horaire_id := all_volumes_horaires(type_volume_horaire_id).NEXT(etat_volume_horaire_id);
      END LOOP;
      type_volume_horaire_id := all_volumes_horaires.NEXT(type_volume_horaire_id);
    END LOOP;

    /* On fait la sauvegarde en BDD */
    /* D'abord le formule_resultat */
    code := t_res.FIRST;
    LOOP EXIT WHEN code IS NULL;
      IF code = (t_res(code).type_volume_horaire_id || '-' || t_res(code).etat_volume_horaire_id) THEN
        fr.id                       := t_res(code).id;
        fr.intervenant_id           := intervenant.id;
        fr.type_volume_horaire_id   := t_res(code).type_volume_horaire_id;
        fr.etat_volume_horaire_id   := t_res(code).etat_volume_horaire_id;
        fr.service_fi               := ROUND(t_res(code).service_fi,2);
        fr.service_fa               := ROUND(t_res(code).service_fa,2);
        fr.service_fc               := ROUND(t_res(code).service_fc,2);
        fr.service_referentiel      := ROUND(t_res(code).service_referentiel,2);
        fr.heures_compl_fi          := ROUND(t_res(code).heures_compl_fi,2);
        fr.heures_compl_fa          := ROUND(t_res(code).heures_compl_fa,2);
        fr.heures_compl_fc          := ROUND(t_res(code).heures_compl_fc,2);
        fr.heures_compl_fc_majorees := ROUND(t_res(code).heures_compl_fc_majorees,2);
        fr.heures_compl_referentiel := ROUND(t_res(code).heures_compl_referentiel,2);
        fr.total := fr.service_fi + fr.service_fa + fr.service_fc + fr.service_referentiel
                  + fr.heures_compl_fi + fr.heures_compl_fa + fr.heures_compl_fc
                  + fr.heures_compl_fc_majorees + fr.heures_compl_referentiel;

        fr.service_du := ROUND(CASE
          WHEN intervenant.depassement_service_du_sans_hc OR intervenant.heures_decharge < 0
          THEN GREATEST(fr.total, intervenant.heures_service_statutaire + intervenant.heures_service_modifie)
          ELSE intervenant.heures_service_statutaire + intervenant.heures_service_modifie
        END,2);

        fr.solde                    := fr.total - fr.service_du;
        IF fr.solde >= 0 THEN
          fr.sous_service           := 0;
          fr.heures_compl           := fr.solde;
        ELSE
          fr.sous_service           := fr.solde * -1;
          fr.heures_compl           := 0;
        END IF;
        fr.type_intervenant_code    := intervenant.type_intervenant_code;

        IF fr.id IS NULL THEN
          fr.id := formule_resultat_id_seq.nextval;
          t_res(code).id := fr.id;
          INSERT INTO formule_resultat VALUES fr;
        ELSE
          UPDATE formule_resultat SET ROW = fr WHERE id = fr.id;
        END IF;
      END IF;
      code := t_res.NEXT(code);
    END LOOP;

    --DEBUG_TRES;

    /* Ensuite toutes les dépendances... */
    code := t_res.FIRST;
    LOOP EXIT WHEN code IS NULL;
      bcode := t_res(code).type_volume_horaire_id || '-' || t_res(code).etat_volume_horaire_id;
      CASE
        WHEN code LIKE '%-s-%' THEN -- formule_resultat_service
          frs.id                         := t_res(code).id;
          frs.formule_resultat_id        := t_res(bcode).id;
          frs.service_id                 := t_res(code).service_id;
          frs.service_fi                 := ROUND(t_res(code).service_fi, 2);
          frs.service_fa                 := ROUND(t_res(code).service_fa, 2);
          frs.service_fc                 := ROUND(t_res(code).service_fc, 2);
          frs.heures_compl_fi            := ROUND(t_res(code).heures_compl_fi, 2);
          frs.heures_compl_fa            := ROUND(t_res(code).heures_compl_fa, 2);
          frs.heures_compl_fc            := ROUND(t_res(code).heures_compl_fc, 2);
          frs.heures_compl_fc_majorees   := ROUND(t_res(code).heures_compl_fc_majorees, 2);
          frs.total                      := frs.service_fi + frs.service_fa + frs.service_fc
                 + frs.heures_compl_fi + frs.heures_compl_fa + frs.heures_compl_fc + frs.heures_compl_fc_majorees;
          IF frs.id IS NULL THEN
            frs.id := formule_resultat_servic_id_seq.nextval;
            INSERT INTO formule_resultat_service VALUES frs;
          ELSE
            UPDATE formule_resultat_service SET ROW = frs WHERE id = frs.id;
          END IF;
        WHEN code LIKE '%-sr-%' THEN -- formule_resultat_service_ref
          frsr.id                        := t_res(code).id;
          frsr.formule_resultat_id       := t_res(bcode).id;
          frsr.service_referentiel_id    := t_res(code).service_referentiel_id;
          frsr.service_referentiel       := ROUND(t_res(code).service_referentiel, 2);
          frsr.heures_compl_referentiel  := ROUND(t_res(code).heures_compl_referentiel, 2);
          frsr.total                     := frsr.service_referentiel + frsr.heures_compl_referentiel;
          IF frsr.id IS NULL THEN
            frsr.id := formule_resultat_servic_id_seq.nextval;
            INSERT INTO formule_resultat_service_ref VALUES frsr;
          ELSE
            UPDATE formule_resultat_service_ref SET ROW = frsr WHERE id = frsr.id;
          END IF;
        WHEN code LIKE '%-vh-%' THEN -- formule_resultat_vh
          frvh.id := t_res(code).id;
          frvh.formule_resultat_id       := t_res(bcode).id;
          frvh.volume_horaire_id         := t_res(code).volume_horaire_id;
          frvh.service_fi                := ROUND(t_res(code).service_fi, 2);
          frvh.service_fa                := ROUND(t_res(code).service_fa, 2);
          frvh.service_fc                := ROUND(t_res(code).service_fc, 2);
          frvh.heures_compl_fi           := ROUND(t_res(code).heures_compl_fi, 2);
          frvh.heures_compl_fa           := ROUND(t_res(code).heures_compl_fa, 2);
          frvh.heures_compl_fc           := ROUND(t_res(code).heures_compl_fc, 2);
          frvh.heures_compl_fc_majorees  := ROUND(t_res(code).heures_compl_fc_majorees, 2);
          frvh.total                     := frvh.service_fi + frvh.service_fa + frvh.service_fc
                  + frvh.heures_compl_fi + frvh.heures_compl_fa + frvh.heures_compl_fc + frvh.heures_compl_fc_majorees;
          IF frvh.id IS NULL THEN
            frvh.id := formule_resultat_vh_id_seq.nextval;
            INSERT INTO formule_resultat_vh VALUES frvh;
          ELSE
            UPDATE formule_resultat_vh SET ROW = frvh WHERE id = frvh.id;
          END IF;
        WHEN code LIKE '%-vhr-%' THEN -- formule_resultat_vh_ref
          frvhr.id := t_res(code).id;
          frvhr.formule_resultat_id      := t_res(bcode).id;
          frvhr.volume_horaire_ref_id    := t_res(code).volume_horaire_ref_id;
          frvhr.service_referentiel      := ROUND(t_res(code).service_referentiel, 2);
          frvhr.heures_compl_referentiel := ROUND(t_res(code).heures_compl_referentiel, 2);
          frvhr.total                    := frvhr.service_referentiel + frvhr.heures_compl_referentiel;
          IF frvhr.id IS NULL THEN
            frvhr.id := formule_resultat_vh_ref_id_seq.nextval;
            INSERT INTO formule_resultat_vh_ref VALUES frvhr;
          ELSE
            UPDATE formule_resultat_vh_ref SET ROW = frvhr WHERE id = frvhr.id;
          END IF;
        ELSE code := code;
      END CASE;
      code := t_res.NEXT(code);
    END LOOP;
  END;



  PROCEDURE SAVE_TO_TEST(passed NUMERIC) IS
    vh t_volume_horaire;
  BEGIN
    UPDATE formule_test_intervenant SET
      c_service_du = CASE WHEN passed = 1 THEN intervenant.service_du ELSE NULL END,
      debug_info = intervenant.debug_info
    WHERE id = intervenant.id;

    FOR i IN 1 .. volumes_horaires.length LOOP
      vh := volumes_horaires.items(i);
      UPDATE formule_test_volume_horaire SET
        c_service_fi               = CASE WHEN passed = 1 THEN vh.service_fi ELSE NULL END,
        c_service_fa               = CASE WHEN passed = 1 THEN vh.service_fa ELSE NULL END,
        c_service_fc               = CASE WHEN passed = 1 THEN vh.service_fc ELSE NULL END,
        c_service_referentiel      = CASE WHEN passed = 1 THEN vh.service_referentiel ELSE NULL END,
        c_heures_compl_fi          = CASE WHEN passed = 1 THEN vh.heures_compl_fi ELSE NULL END,
        c_heures_compl_fa          = CASE WHEN passed = 1 THEN vh.heures_compl_fa ELSE NULL END,
        c_heures_compl_fc          = CASE WHEN passed = 1 THEN vh.heures_compl_fc ELSE NULL END,
        c_heures_compl_fc_majorees = CASE WHEN passed = 1 THEN vh.heures_compl_fc_majorees ELSE NULL END,
        c_heures_compl_referentiel = CASE WHEN passed = 1 THEN vh.heures_compl_referentiel ELSE NULL END,
        debug_info                 = vh.debug_info
      WHERE
        id = COALESCE(vh.volume_horaire_id,vh.volume_horaire_ref_id);
    END LOOP;
  END;



  PROCEDURE CALCULER( INTERVENANT_ID NUMERIC ) IS
    type_volume_horaire_id NUMERIC;
    etat_volume_horaire_id NUMERIC;
    fdata formule%rowtype;
  BEGIN
    intervenant.id := intervenant_id;
    fdata := ose_parametre.get_formule;

    LOAD_INTERVENANT_FROM_BDD;
    LOAD_VH_FROM_BDD;

    type_volume_horaire_id := all_volumes_horaires.FIRST;
    LOOP EXIT WHEN type_volume_horaire_id IS NULL;
      intervenant.type_volume_horaire_id := type_volume_horaire_id;
      etat_volume_horaire_id := all_volumes_horaires(type_volume_horaire_id).FIRST;
      LOOP EXIT WHEN etat_volume_horaire_id IS NULL;
        intervenant.etat_volume_horaire_id := etat_volume_horaire_id;
        volumes_horaires := all_volumes_horaires(type_volume_horaire_id)(etat_volume_horaire_id);
        EXECUTE IMMEDIATE 'BEGIN ' || fdata.package_name || '.' || fdata.procedure_name || '; END;';
        all_volumes_horaires(type_volume_horaire_id)(etat_volume_horaire_id) := volumes_horaires;
        etat_volume_horaire_id := all_volumes_horaires(type_volume_horaire_id).NEXT(etat_volume_horaire_id);
      END LOOP;
      type_volume_horaire_id := all_volumes_horaires.NEXT(type_volume_horaire_id);
    END LOOP;

    SAVE_TO_BDD;

    OSE_EVENT.ON_AFTER_FORMULE_CALC( CALCULER.INTERVENANT_ID );
  END;

  PROCEDURE CALCULER_TOUT( ANNEE_ID NUMERIC DEFAULT NULL ) IS
    a_id NUMERIC;
  BEGIN
    a_id := NVL(CALCULER_TOUT.ANNEE_ID, OSE_PARAMETRE.GET_ANNEE);
    FOR mp IN (
      SELECT DISTINCT
        intervenant_id
      FROM
        service s
        JOIN intervenant i ON i.id = s.intervenant_id
      WHERE
        s.histo_destruction IS NULL
        AND i.annee_id = a_id

      UNION ALL

      SELECT DISTINCT
        intervenant_id
      FROM
        service_referentiel sr
        JOIN intervenant i ON i.id = sr.intervenant_id
      WHERE
        sr.histo_destruction IS NULL
        AND i.annee_id = a_id

    )
    LOOP
      CALCULER( mp.intervenant_id );
    END LOOP;
  END;



  PROCEDURE TEST( INTERVENANT_TEST_ID NUMERIC ) IS
    procedure_name VARCHAR2(30);
    package_name VARCHAR2(30);
  BEGIN
    intervenant.id := INTERVENANT_TEST_ID;

    SELECT
      package_name, procedure_name INTO package_name, procedure_name
    FROM
      formule f JOIN formule_test_intervenant fti ON fti.formule_id = f.id
    WHERE
      fti.id = intervenant.id;

    LOAD_INTERVENANT_FROM_TEST;
    LOAD_VH_FROM_TEST;

    BEGIN
      EXECUTE IMMEDIATE 'BEGIN ' || package_name || '.' || procedure_name || '; END;';
      SAVE_TO_TEST(1);
    EXCEPTION WHEN OTHERS THEN
      SAVE_TO_TEST(0);
      RAISE_APPLICATION_ERROR(-20001, dbms_utility.format_error_backtrace,true);
    END;
  END;



  PROCEDURE TEST_TOUT IS
  BEGIN
    FOR d IN (SELECT id FROM formule_test_intervenant)
    LOOP
      TEST( d.id );
    END LOOP;
  END;



  PROCEDURE CALCULER_TBL( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    intervenant_id NUMERIC;
    TYPE r_cursor IS REF CURSOR;
    diff_cur r_cursor;
  BEGIN
    OPEN diff_cur FOR 'WITH interv AS (SELECT id intervenant_id, intervenant.* FROM intervenant)
    SELECT intervenant_id FROM interv WHERE ' || unicaen_tbl.PARAMS_TO_CONDS( params );
    LOOP
      FETCH diff_cur INTO intervenant_id; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN
        CALCULER( intervenant_id );
      END;
    END LOOP;
    CLOSE diff_cur;
  END;



  PROCEDURE DEBUG_INTERVENANT IS
  BEGIN
    ose_test.echo('OSE Formule DEBUG Intervenant');
    ose_test.echo('id                             = ' || intervenant.id);
    ose_test.echo('annee_id                       = ' || intervenant.annee_id);
    ose_test.echo('structure_id                   = ' || intervenant.structure_id);
    ose_test.echo('type_volume_horaire_id         = ' || intervenant.type_volume_horaire_id);
    ose_test.echo('heures_decharge                = ' || intervenant.heures_decharge);
    ose_test.echo('heures_service_statutaire      = ' || intervenant.heures_service_statutaire);
    ose_test.echo('heures_service_modifie         = ' || intervenant.heures_service_modifie);
    ose_test.echo('depassement_service_du_sans_hc = ' || CASE WHEN intervenant.depassement_service_du_sans_hc THEN 'OUI' ELSE 'NON' END);
    ose_test.echo('service_du                     = ' || intervenant.service_du);
  END;

  PROCEDURE DEBUG_VOLUMES_HORAIRES(VOLUME_HORAIRE_ID NUMERIC DEFAULT NULL) IS
    type_volume_horaire_id NUMERIC;
    etat_volume_horaire_id NUMERIC;
    vh t_volume_horaire;
  BEGIN
    ose_test.echo('OSE Formule DEBUG Intervenant');

    type_volume_horaire_id := all_volumes_horaires.FIRST;
    LOOP EXIT WHEN type_volume_horaire_id IS NULL;
      etat_volume_horaire_id := all_volumes_horaires(type_volume_horaire_id).FIRST;
      LOOP EXIT WHEN etat_volume_horaire_id IS NULL;
        ose_test.echo('tvh=' || type_volume_horaire_id || ', evh=' || etat_volume_horaire_id);
        FOR i IN 1 .. all_volumes_horaires(type_volume_horaire_id)(etat_volume_horaire_id).length LOOP
          vh := all_volumes_horaires(type_volume_horaire_id)(etat_volume_horaire_id).items(i);
          IF VOLUME_HORAIRE_ID IS NULL OR VOLUME_HORAIRE_ID = vh.volume_horaire_id OR VOLUME_HORAIRE_ID = vh.volume_horaire_ref_id THEN
            ose_test.echo('volume_horaire_id         = ' || vh.volume_horaire_id);
            ose_test.echo('volume_horaire_ref_id     = ' || vh.volume_horaire_ref_id);
            ose_test.echo('service_id                = ' || vh.service_id);
            ose_test.echo('service_referentiel_id    = ' || vh.service_referentiel_id);
            ose_test.echo('taux_fi                   = ' || vh.taux_fi);
            ose_test.echo('taux_fa                   = ' || vh.taux_fa);
            ose_test.echo('taux_fc                   = ' || vh.taux_fc);
            ose_test.echo('ponderation_service_du    = ' || vh.ponderation_service_du);
            ose_test.echo('ponderation_service_compl = ' || vh.ponderation_service_compl);
            ose_test.echo('structure_id              = ' || vh.structure_id);
            ose_test.echo('structure_is_affectation  = ' || CASE WHEN vh.structure_is_affectation THEN 'OUI' ELSE 'NON' END);
            ose_test.echo('structure_is_univ         = ' || CASE WHEN vh.structure_is_univ THEN 'OUI' ELSE 'NON' END);
            ose_test.echo('service_statutaire        = ' || CASE WHEN vh.service_statutaire THEN 'OUI' ELSE 'NON' END);
            ose_test.echo('heures                    = ' || vh.heures);
            ose_test.echo('taux_service_du           = ' || vh.taux_service_du);
            ose_test.echo('taux_service_compl        = ' || vh.taux_service_compl);
            ose_test.echo('service_fi                = ' || vh.service_fi);
            ose_test.echo('service_fa                = ' || vh.service_fa);
            ose_test.echo('service_fc                = ' || vh.service_fc);
            ose_test.echo('service_referentiel       = ' || vh.service_referentiel);
            ose_test.echo('heures_compl_fi           = ' || vh.heures_compl_fi);
            ose_test.echo('heures_compl_fa           = ' || vh.heures_compl_fa);
            ose_test.echo('heures_compl_fc           = ' || vh.heures_compl_fc);
            ose_test.echo('heures_compl_fc_majorees  = ' || vh.heures_compl_fc_majorees);
            ose_test.echo('heures_compl_referentiel  = ' || vh.heures_compl_referentiel);
            ose_test.echo('');
          END IF;
        END LOOP;
        etat_volume_horaire_id := all_volumes_horaires(type_volume_horaire_id).NEXT(etat_volume_horaire_id);
      END LOOP;
      type_volume_horaire_id := all_volumes_horaires.NEXT(type_volume_horaire_id);
    END LOOP;
  END;

END OSE_FORMULE;

/

CREATE OR REPLACE PACKAGE "OSE_PARAMETRE" AS

  function get_etablissement return Numeric;
  function get_annee return Numeric;
  function get_annee_import return Numeric;
  function get_ose_user return Numeric;
  function get_formule RETURN formule%rowtype;

END OSE_PARAMETRE;

/

CREATE OR REPLACE PACKAGE BODY "OSE_PARAMETRE" AS

  cache_ose_user NUMERIC;
  cache_annee_id NUMERIC;

  FUNCTION get_etablissement return Numeric AS
    etab_id numeric;
  BEGIN
    select to_number(valeur) into etab_id from parametre where nom = 'etablissement';
    RETURN etab_id;
  END get_etablissement;

  FUNCTION get_annee return Numeric AS
    annee_id numeric;
  BEGIN
    IF cache_annee_id IS NOT NULL THEN RETURN cache_annee_id; END IF;
    select to_number(valeur) into annee_id from parametre where nom = 'annee';
    cache_annee_id := annee_id;
    RETURN cache_annee_id;
  END get_annee;

  FUNCTION get_annee_import RETURN NUMERIC AS
    annee_id NUMERIC;
  BEGIN
    SELECT to_number(valeur) INTO annee_id FROM parametre WHERE nom = 'annee_import';
    RETURN annee_id;
  END get_annee_import;

  FUNCTION get_ose_user return NUMERIC AS
    ose_user_id numeric;
  BEGIN
    IF cache_ose_user IS NOT NULL THEN RETURN cache_ose_user; END IF;
    select to_number(valeur) into ose_user_id from parametre where nom = 'oseuser';
    cache_ose_user := ose_user_id;
    RETURN cache_ose_user;
  END get_ose_user;

  FUNCTION get_formule RETURN formule%rowtype IS
    fdata formule%rowtype;
  BEGIN
    SELECT
      f.* INTO fdata
    FROM
      formule f
      JOIN parametre p ON f.id = to_number(p.valeur)
    WHERE p.nom = 'formule';
    RETURN fdata;
  END;

END OSE_PARAMETRE;

/




-- DdlView.alter.

CREATE OR REPLACE FORCE VIEW "V_CONTRAT_SERVICES" ("CONTRAT_ID", "serviceComposante", "serviceCode", "serviceLibelle", "HEURES", "serviceHeures") AS
  SELECT
  c.id                                             contrat_id,
  str.libelle_court                                "serviceComposante",
  ep.code                                          "serviceCode",
  ep.libelle                                       "serviceLibelle",
  sum(vh.heures)                                   heures,
  replace(ltrim(to_char(sum(vh.heures), '999999.00')),'.',',') "serviceHeures"
FROM
            contrat                  c
       JOIN intervenant              i ON i.id = c.intervenant_id
       JOIN type_volume_horaire    tvh ON tvh.code = 'PREVU'
       JOIN service                  s ON s.intervenant_id = i.id AND s.histo_destruction IS NULL
       JOIN volume_horaire          vh ON vh.service_id = s.id AND vh.histo_destruction IS NULL AND vh.type_volume_horaire_id = tvh.id
  LEFT JOIN validation_vol_horaire vvh ON vvh.volume_horaire_id = vh.id
  LEFT JOIN validation               v ON v.id = vvh.validation_id AND v.histo_destruction IS NULL
  LEFT JOIN validation              cv ON cv.id = c.validation_id AND cv.histo_destruction IS NULL
  LEFT JOIN element_pedagogique     ep ON ep.id = s.element_pedagogique_id
       JOIN structure              str ON str.id = COALESCE(ep.structure_id,i.structure_id)
WHERE
    c.histo_destruction IS NULL
  AND (cv.id IS NULL OR vh.contrat_id = c.id)
  AND (vh.auto_validation = 1 OR v.id IS NOT NULL)
  --AND str.id = c.structure_id
GROUP BY
  c.id, str.libelle_court, ep.code, ep.libelle

/

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
               intervenant_nom

/

CREATE OR REPLACE FORCE VIEW "V_EXPORT_PAIEMENT_WINPAIE" ("ANNEE_ID", "TYPE_INTERVENANT_ID", "STRUCTURE_ID", "PERIODE_ID", "INTERVENANT_ID", "INSEE", "NOM", "CARTE", "CODE_ORIGINE", "RETENUE", "SENS", "MC", "NBU", "MONTANT", "LIBELLE") AS
  SELECT
    annee_id,
    type_intervenant_id,
    structure_id,
    periode_id,
    intervenant_id,

    insee,
    nom,
    '20' carte,
    code_origine,
    '0204' retenue,
    '0' sens,
    'B' mc,
    nbu,
    montant,
    libelle || ' ' || LPAD(TO_CHAR(FLOOR(nbu)),2,'00') || ' H' ||
      CASE to_char(ROUND( nbu-FLOOR(nbu), 2 )*100,'00')
      WHEN ' 00' THEN '' ELSE ' ' || LPAD(ROUND( nbu-FLOOR(nbu), 2 )*100,2,'00') END libelle
FROM (
  SELECT
    i.annee_id                                                                                          annee_id,
    si.type_intervenant_id                                                                              type_intervenant_id,
    t2.structure_id                                                                                     structure_id,
    t2.periode_paiement_id                                                                              periode_id,
    i.id                                                                                                intervenant_id,

    '''' || NVL(i.numero_insee,'') || TRIM(NVL(TO_CHAR(i.numero_insee_cle,'00'),''))                    insee,
    i.nom_usuel || ',' || i.prenom                                                                      nom,
    t2.code_origine                                                                                     code_origine,
    CASE WHEN ind <> CEIL(t2.nbu/max_nbu) THEN max_nbu ELSE t2.nbu - max_nbu*(ind-1) END                nbu,
    t2.nbu                                                                                              tnbu,
    OSE_FORMULE.GET_TAUX_HORAIRE_HETD( NVL(t2.date_mise_en_paiement,SYSDATE) )                          montant,
    COALESCE(t2.unite_budgetaire,'') || ' ' || to_char(i.annee_id) || ' ' || to_char(i.annee_id+1)      libelle
  FROM (
    SELECT
      structure_id,
      periode_paiement_id,
      intervenant_id,
      code_origine,
      ROUND( SUM(nbu), 2) nbu,
      unite_budgetaire,
      date_mise_en_paiement
    FROM (
      WITH mep AS (
      SELECT
        -- pour les filtres
        mep.id,
        mis.structure_id,
        mep.periode_paiement_id,
        mis.intervenant_id,
        mep.heures,
        cc.unite_budgetaire,
        mep.date_mise_en_paiement
      FROM
        v_mep_intervenant_structure  mis
        JOIN mise_en_paiement        mep ON mep.id = mis.mise_en_paiement_id AND mep.histo_destruction IS NULL
        JOIN centre_cout              cc ON cc.id = mep.centre_cout_id
        JOIN type_heures              th ON th.id = mep.type_heures_id
      WHERE
        mep.date_mise_en_paiement IS NOT NULL
        AND mep.periode_paiement_id IS NOT NULL
        AND th.eligible_extraction_paie = 1
      )
      SELECT
        mep.id,
        mep.structure_id,
        mep.periode_paiement_id,
        mep.intervenant_id,
        2 code_origine,
        mep.heures * 4 / 10 nbu,
        mep.unite_budgetaire,
        mep.date_mise_en_paiement
      FROM
        mep
      WHERE
        mep.heures * 4 / 10 > 0

      UNION

      SELECT
        mep.id,
        mep.structure_id,
        mep.periode_paiement_id,
        mep.intervenant_id,
        1 code_origine,
        mep.heures * 6 / 10 nbu,
        mep.unite_budgetaire,
        mep.date_mise_en_paiement
      FROM
        mep
      WHERE
        mep.heures * 6 / 10 > 0
    ) t1
    GROUP BY
      structure_id,
      periode_paiement_id,
      intervenant_id,
      code_origine,
      unite_budgetaire,
      date_mise_en_paiement
  ) t2
  JOIN (SELECT level ind, 99 max_nbu FROM dual CONNECT BY 1=1 AND LEVEL <= 11) tnbu ON ceil(t2.nbu / max_nbu) >= ind
  JOIN intervenant i ON i.id = t2.intervenant_id
  JOIN statut_intervenant si ON si.id = i.statut_id
  JOIN structure s ON s.id = t2.structure_id
) t3
ORDER BY
  annee_id, type_intervenant_id, structure_id, periode_id, nom, code_origine, nbu DESC

/

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
  LEFT JOIN validation                    v ON v.intervenant_id = i.id AND v.type_validation_id = tv.id AND v.histo_destruction IS NULL

/

CREATE OR REPLACE FORCE VIEW "V_FORMULE_INTERVENANT" ("INTERVENANT_ID", "ANNEE_ID", "STRUCTURE_ID", "TYPE_INTERVENANT_CODE", "HEURES_SERVICE_STATUTAIRE", "DEPASSEMENT_SERVICE_DU_SANS_HC", "HEURES_SERVICE_MODIFIE", "HEURES_DECHARGE") AS
  SELECT
  i.id                                                                 intervenant_id,
  i.annee_id                                                           annee_id,
  CASE WHEN ti.code = 'P' THEN i.structure_id ELSE NULL END           structure_id,
  ti.code                                                              type_intervenant_code,
  si.service_statutaire                                                heures_service_statutaire,
  si.depassement_service_du_sans_hc                                    depassement_service_du_sans_hc,
  COALESCE( SUM( msd.heures * mms.multiplicateur ), 0 )                heures_service_modifie,
  COALESCE( SUM( msd.heures * mms.multiplicateur * mms.decharge ), 0 ) heures_decharge
FROM
            intervenant                  i
  LEFT JOIN modification_service_du    msd ON msd.intervenant_id = i.id AND msd.histo_destruction IS NULL
  LEFT JOIN motif_modification_service mms ON mms.id = msd.motif_id
       JOIN statut_intervenant          si ON si.id = i.statut_id
       JOIN type_intervenant            ti ON ti.id = si.type_intervenant_id
WHERE
  i.histo_destruction IS NULL
  AND i.id = COALESCE( OSE_FORMULE.GET_INTERVENANT_ID, i.id )
GROUP BY
  i.id, i.annee_id, i.structure_id, ti.code, si.service_statutaire, si.depassement_service_du_sans_hc

/

CREATE OR REPLACE FORCE VIEW "V_FORMULE_VOLUME_HORAIRE" ("ID", "VOLUME_HORAIRE_ID", "VOLUME_HORAIRE_REF_ID", "SERVICE_ID", "SERVICE_REFERENTIEL_ID", "INTERVENANT_ID", "TYPE_INTERVENTION_ID", "TYPE_VOLUME_HORAIRE_ID", "ETAT_VOLUME_HORAIRE_ID", "TAUX_FI", "TAUX_FA", "TAUX_FC", "STRUCTURE_ID", "PONDERATION_SERVICE_DU", "PONDERATION_SERVICE_COMPL", "SERVICE_STATUTAIRE", "HEURES", "HORAIRE_DEBUT", "HORAIRE_FIN", "TAUX_SERVICE_DU", "TAUX_SERVICE_COMPL") AS
  SELECT
  to_number( 1 || vh.id )                                              id,
  vh.id                                                                volume_horaire_id,
  null                                                                 volume_horaire_ref_id,
  s.id                                                                 service_id,
  null                                                                 service_referentiel_id,
  s.intervenant_id                                                     intervenant_id,
  ti.id                                                                type_intervention_id,
  vh.type_volume_horaire_id                                            type_volume_horaire_id,
  vhe.etat_volume_horaire_id                                           etat_volume_horaire_id,

  CASE WHEN ep.id IS NOT NULL THEN ep.taux_fi ELSE 1 END               taux_fi,
  CASE WHEN ep.id IS NOT NULL THEN ep.taux_fa ELSE 0 END               taux_fa,
  CASE WHEN ep.id IS NOT NULL THEN ep.taux_fc ELSE 0 END               taux_fc,
  ep.structure_id                                                      structure_id,
  MAX(COALESCE( m.ponderation_service_du, 1))                          ponderation_service_du,
  MAX(COALESCE( m.ponderation_service_compl, 1))                       ponderation_service_compl,
  COALESCE(tf.service_statutaire,1)                                    service_statutaire,

  vh.heures                                                            heures,
  vh.horaire_debut                                                     horaire_debut,
  vh.horaire_fin                                                       horaire_fin,
  COALESCE(tis.taux_hetd_service,ti.taux_hetd_service,1)               taux_service_du,
  COALESCE(tis.taux_hetd_complementaire,ti.taux_hetd_complementaire,1) taux_service_compl
FROM
            volume_horaire            vh
       JOIN service                    s ON s.id = vh.service_id
       JOIN intervenant                i ON i.id = s.intervenant_id
       JOIN type_intervention         ti ON ti.id = vh.type_intervention_id
       JOIN v_volume_horaire_etat    vhe ON vhe.volume_horaire_id = vh.id

  LEFT JOIN element_pedagogique       ep ON ep.id = s.element_pedagogique_id
  LEFT JOIN etape                      e ON e.id = ep.etape_id
  LEFT JOIN type_formation            tf ON tf.id = e.type_formation_id
  LEFT JOIN element_modulateur        em ON em.element_id = s.element_pedagogique_id
                                        AND em.histo_destruction IS NULL
  LEFT JOIN modulateur                 m ON m.id = em.modulateur_id
  LEFT JOIN type_intervention_statut tis ON tis.type_intervention_id = ti.id AND tis.statut_intervenant_id = i.statut_id
WHERE
  vh.histo_destruction IS NULL
  AND s.histo_destruction IS NULL
  AND vh.heures <> 0
  AND vh.motif_non_paiement_id IS NULL
  AND s.intervenant_id = COALESCE( OSE_FORMULE.GET_INTERVENANT_ID, s.intervenant_id )
GROUP BY
  vh.id, s.id, s.intervenant_id, ti.id, vh.type_volume_horaire_id, vhe.etat_volume_horaire_id, ep.id,
  ep.taux_fi, ep.taux_fa, ep.taux_fc, ep.structure_id, tf.service_statutaire, vh.heures,
  vh.horaire_debut, vh.horaire_fin, tis.taux_hetd_service, tis.taux_hetd_complementaire,
  ti.taux_hetd_service, ti.taux_hetd_complementaire

UNION ALL

SELECT
  to_number( 2 || vhr.id )          id,
  null                              volume_horaire_id,
  vhr.id                            volume_horaire_ref_id,
  null                              service_id,
  sr.id                             service_referentiel_id,
  sr.intervenant_id                 intervenant_id,
  null                              type_intervention_id,
  vhr.type_volume_horaire_id        type_volume_horaire_id,
  evh.id                            etat_volume_horaire_id,

  0                                 taux_fi,
  0                                 taux_fa,
  0                                 taux_fc,
  sr.structure_id                   structure_id,
  1                                 ponderation_service_du,
  1                                 ponderation_service_compl,
  COALESCE(fr.service_statutaire,1) service_statutaire,

  vhr.heures                        heures,
  vhr.horaire_debut                 horaire_debut,
  vhr.horaire_fin                   horaire_fin,
  1                                 taux_service_du,
  1                                 taux_service_compl
FROM
  volume_horaire_ref               vhr
  JOIN service_referentiel          sr ON sr.id = vhr.service_referentiel_id
  JOIN v_volume_horaire_ref_etat  vher ON vher.volume_horaire_ref_id = vhr.id
  JOIN etat_volume_horaire         evh ON evh.id = vher.etat_volume_horaire_id
  JOIN fonction_referentiel         fr ON fr.id = sr.fonction_id
WHERE
  vhr.histo_destruction IS NULL
  AND sr.histo_destruction IS NULL
  AND vhr.heures <> 0
  AND sr.intervenant_id = COALESCE( OSE_FORMULE.GET_INTERVENANT_ID, sr.intervenant_id )

ORDER BY
  horaire_fin, horaire_debut, volume_horaire_id, volume_horaire_ref_id

/

CREATE OR REPLACE FORCE VIEW "V_INDICATEUR_560" ("ID", "ANNEE_ID", "INTERVENANT_ID", "STRUCTURE_ID", "PLAFOND", "HEURES") AS
  SELECT
  rownum                              id,
  i.annee_id                          annee_id,
  i.id                                intervenant_id,
  i.structure_id                      structure_id,
  si.maximum_hetd                     plafond,
  fr.total                            heures
FROM
  intervenant                     i
  JOIN etat_volume_horaire      evh ON evh.code = 'saisi'
  JOIN formule_resultat          fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id
  JOIN statut_intervenant        si ON si.id = i.statut_id
  JOIN type_volume_horaire      tvh ON tvh.id = fr.type_volume_horaire_id AND tvh.code= 'PREVU'
WHERE
  fr.total - fr.heures_compl_fc_majorees > si.maximum_hetd

/

CREATE OR REPLACE FORCE VIEW "V_INDICATEUR_570" ("ID", "ANNEE_ID", "INTERVENANT_ID", "STRUCTURE_ID", "PLAFOND", "HEURES") AS
  SELECT
  rownum                              id,
  i.annee_id                          annee_id,
  i.id                                intervenant_id,
  i.structure_id                      structure_id,
  si.maximum_hetd                     plafond,
  fr.total                            heures
FROM
  intervenant                     i
  JOIN etat_volume_horaire      evh ON evh.code = 'saisi'
  JOIN formule_resultat          fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id
  JOIN statut_intervenant        si ON si.id = i.statut_id
  JOIN type_volume_horaire      tvh ON tvh.id = fr.type_volume_horaire_id AND tvh.code= 'REALISE'
WHERE
  fr.total - fr.heures_compl_fc_majorees > si.maximum_hetd

/




-- DdlTrigger.alter.

CREATE OR REPLACE TRIGGER "F_STATUT_INTERVENANT"
AFTER UPDATE OF
  service_statutaire,
  depassement,
  type_intervenant_id,
  non_autorise
ON STATUT_INTERVENANT
FOR EACH ROW
BEGIN return; /* Désactivation du trigger... */

  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      fr.intervenant_id
    FROM
      intervenant i
      JOIN formule_resultat fr ON fr.intervenant_id = i.id
    WHERE
      (i.statut_id = :NEW.id OR i.statut_id = :OLD.id)
      AND i.histo_destruction IS NULL

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL('formule', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id) );

  END LOOP;
END;

/

CREATE OR REPLACE TRIGGER "INDIC_TRG_MODIF_DOSSIER"
  AFTER INSERT OR UPDATE OF NOM_USUEL, NOM_PATRONYMIQUE, PRENOM, CIVILITE_ID, ADRESSE, RIB, DATE_NAISSANCE ON "DOSSIER"

  FOR EACH ROW
/**
 * But : mettre à jour la liste des PJ attendues.
 */
DECLARE
  i integer := 1;
  intervenantId NUMERIC;
  found integer;
  estCreationDossier integer;
  type array_t is table of varchar2(1024);

  attrNames     array_t := array_t();
  attrOldVals   array_t := array_t();
  attrNewVals   array_t := array_t();

  -- valeurs importées (format texte) :
  impSourceName source.libelle%type;
  impNomUsuel   indic_modif_dossier.ATTR_NEW_VALUE%type;
  impNomPatro   indic_modif_dossier.ATTR_NEW_VALUE%type;
  impPrenom     indic_modif_dossier.ATTR_NEW_VALUE%type;
  impCivilite   indic_modif_dossier.ATTR_NEW_VALUE%type;
  impDateNaiss  indic_modif_dossier.ATTR_NEW_VALUE%type;
  impAdresse    indic_modif_dossier.ATTR_NEW_VALUE%type;
  impRib        indic_modif_dossier.ATTR_NEW_VALUE%type;
  -- anciennes valeurs dans le dossier (format texte) :
  oldSourceName source.libelle%type;
  oldNomUsuel   indic_modif_dossier.ATTR_NEW_VALUE%type;
  oldNomPatro   indic_modif_dossier.ATTR_NEW_VALUE%type;
  oldPrenom     indic_modif_dossier.ATTR_NEW_VALUE%type;
  oldCivilite   indic_modif_dossier.ATTR_NEW_VALUE%type;
  oldDateNaiss  indic_modif_dossier.ATTR_NEW_VALUE%type;
  oldAdresse    indic_modif_dossier.ATTR_NEW_VALUE%type;
  oldRib        indic_modif_dossier.ATTR_NEW_VALUE%type;
  -- nouvelles valeurs dans le dossier (format texte) :
  newSourceName source.libelle%type;
  newNomUsuel   indic_modif_dossier.ATTR_NEW_VALUE%type;
  newNomPatro   indic_modif_dossier.ATTR_NEW_VALUE%type;
  newPrenom     indic_modif_dossier.ATTR_NEW_VALUE%type;
  newCivilite   indic_modif_dossier.ATTR_NEW_VALUE%type;
  newDateNaiss  indic_modif_dossier.ATTR_NEW_VALUE%type;
  newAdresse    indic_modif_dossier.ATTR_NEW_VALUE%type;
  newRib        indic_modif_dossier.ATTR_NEW_VALUE%type;
BEGIN
  --
  -- Témoin indiquant s'il s'agit d'une création de dossier (insert).
  --
  estCreationDossier := case when inserting then 1 else 0 end;

  --
  -- Fetch source OSE.
  --
  select s.libelle into newSourceName from source s where s.code = 'OSE';

  --
  -- Fetch et formattage texte des valeurs importées.
  --
  select
      i.id,
      s.libelle,
      nvl(i.NOM_USUEL, '(Aucun)'),
      nvl(i.NOM_PATRONYMIQUE, '(Aucun)'),
      nvl(i.PRENOM, '(Aucun)'),
      nvl(c.libelle_court, '(Aucune)'),
      nvl(to_char(i.DATE_NAISSANCE, 'DD/MM/YYYY'), '(Aucune)'),
      nvl(ose_divers.formatted_rib(i.bic, i.iban), '(Aucun)'),
      case when a.id is not null
        then ose_divers.formatted_adresse(a.NO_VOIE, a.NOM_VOIE, a.BATIMENT, a.MENTION_COMPLEMENTAIRE, a.LOCALITE, a.CODE_POSTAL, a.VILLE, a.PAYS_LIBELLE)
        else '(Aucune)'
      end
    into
      intervenantId,
      oldSourceName,
      impNomUsuel,
      impNomPatro,
      impPrenom,
      impCivilite,
      impDateNaiss,
      impRib,
      impAdresse
    from intervenant i
    join source s on s.id = i.source_id
    left join civilite c on c.id = i.civilite_id
    left join adresse_intervenant a on a.intervenant_id = i.id AND a.histo_destruction IS NULL
    where i.id = :NEW.intervenant_id;

  --
  -- Anciennes valeurs dans le cas d'une création de dossier : ce sont les valeurs importées.
  --
  if (1 = estCreationDossier) then
    --dbms_output.put_line('inserting');
    oldNomUsuel  := impNomUsuel;
    oldNomPatro  := impNomPatro;
    oldPrenom    := impPrenom;
    oldCivilite  := impCivilite;
    oldDateNaiss := impDateNaiss;
    oldAdresse   := impAdresse;
    oldRib       := impRib;
  --
  -- Anciennes valeurs dans le cas d'une mise à jour du dossier.
  --
  else
    --dbms_output.put_line('updating');
    oldNomUsuel     := trim(:OLD.NOM_USUEL);
    oldNomPatro     := trim(:OLD.NOM_PATRONYMIQUE);
    oldPrenom       := trim(:OLD.PRENOM);
    oldDateNaiss    := case when :OLD.DATE_NAISSANCE is null then '(Aucune)' else to_char(:OLD.DATE_NAISSANCE, 'DD/MM/YYYY') end;
    oldAdresse      := trim(:OLD.ADRESSE);
    oldRib          := trim(:OLD.RIB);
    if :OLD.CIVILITE_ID is not null then
      select c.libelle_court into oldCivilite from civilite c where c.id = :OLD.CIVILITE_ID;
    else
      oldCivilite := '(Aucune)';
    end if;
    select s.libelle into oldSourceName from source s where s.code = 'OSE';
  end if;

  --
  -- Nouvelles valeurs saisies.
  --
  newNomUsuel   := trim(:NEW.NOM_USUEL);
  newNomPatro   := trim(:NEW.NOM_PATRONYMIQUE);
  newPrenom     := trim(:NEW.PRENOM);
  newDateNaiss  := case when :NEW.DATE_NAISSANCE is null then '(Aucune)' else to_char(:NEW.DATE_NAISSANCE, 'DD/MM/YYYY') end;
  newAdresse    := trim(:NEW.ADRESSE);
  newRib        := trim(:NEW.RIB);
  if :NEW.CIVILITE_ID is not null then
    select c.libelle_court into newCivilite from civilite c where c.id = :NEW.CIVILITE_ID;
  else
    newCivilite := '(Aucune)';
  end if;

  --
  -- Détection des différences.
  --
  if newNomUsuel <> oldNomUsuel then
    --dbms_output.put_line('NOM_USUEL ' || sourceLib || ' = ' || oldNomUsuel || ' --> NOM_USUEL OSE = ' || :NEW.NOM_USUEL);
    attrNames.extend(1);
    attrOldVals.extend(1);
    attrNewVals.extend(1);
    attrNames(i)   := 'Nom usuel';
    attrOldVals(i) := oldNomUsuel;
    attrNewVals(i) := newNomUsuel;
    i := i + 1;
  end if;
  if newNomPatro <> oldNomPatro then
    --dbms_output.put_line('NOM_PATRONYMIQUE ' || sourceLib || ' = ' || oldNomPatro || ' --> NOM_PATRONYMIQUE OSE = ' || :NEW.NOM_PATRONYMIQUE);
    attrNames.extend(1);
    attrOldVals.extend(1);
    attrNewVals.extend(1);
    attrNames(i)   := 'Nom de naissance';
    attrOldVals(i) := oldNomPatro;
    attrNewVals(i) := newNomPatro;
    i := i + 1;
  end if;
  if newPrenom <> oldPrenom then
    --dbms_output.put_line('PRENOM ' || sourceLib || ' = ' || oldPrenom || ' --> PRENOM OSE = ' || :NEW.PRENOM);
    attrNames.extend(1);
    attrOldVals.extend(1);
    attrNewVals.extend(1);
    attrNames(i)   := 'Prénom';
    attrOldVals(i) := oldPrenom;
    attrNewVals(i) := newPrenom;
    i := i + 1;
  end if;
  if newCivilite <> oldCivilite then
    --dbms_output.put_line('CIVILITE_ID ' || sourceLib || ' = ' || oldCivilite || ' --> CIVILITE_ID OSE = ' || :NEW.CIVILITE_ID);
    attrNames.extend(1);
    attrOldVals.extend(1);
    attrNewVals.extend(1);
    attrNames(i)   := 'Civilité';
    attrOldVals(i) := oldCivilite;
    attrNewVals(i) := newCivilite;
    i := i + 1;
  end if;
  if newDateNaiss <> oldDateNaiss then
    --dbms_output.put_line('DATE_NAISSANCE ' || sourceLib || ' = ' || oldDateNaiss || ' --> DATE_NAISSANCE OSE = ' || :NEW.DATE_NAISSANCE);
    attrNames.extend(1);
    attrOldVals.extend(1);
    attrNewVals.extend(1);
    attrNames(i)   := 'Date de naissance';
    attrOldVals(i) := oldDateNaiss;
    attrNewVals(i) := newDateNaiss;
    i := i + 1;
  end if;
  if newAdresse <> oldAdresse then
    --dbms_output.put_line('ADRESSE ' || sourceLib || ' = ' || oldAdresse || ' --> ADRESSE OSE = ' || :NEW.ADRESSE);
    attrNames.extend(1);
    attrOldVals.extend(1);
    attrNewVals.extend(1);
    attrNames(i)   := 'Adresse postale';
    attrOldVals(i) := oldAdresse;
    attrNewVals(i) := newAdresse;
    i := i + 1;
  end if;
  if oldRib is null or newRib <> oldRib then
    --dbms_output.put_line('RIB ' || sourceLib || ' = ' || oldRib || ' --> RIB OSE = ' || :NEW.RIB);
    attrNames.extend(1);
    attrOldVals.extend(1);
    attrNewVals.extend(1);
    attrNames(i)   := 'RIB';
    attrOldVals(i) := oldRib;
    attrNewVals(i) := newRib;
    i := i + 1;
  end if;

  --
  -- Enregistrement des différences.
  --
  for i in 1 .. attrNames.count loop
    --dbms_output.put_line(attrNames(i) || ' ' || oldSourceName || ' = ' || attrOldVals(i) || ' --> ' || attrNames(i) || ' ' || newSourceName || ' = ' || attrNewVals(i));

    -- vérification que la même modif n'est pas déjà consignée
    select count(*) into found from indic_modif_dossier
      where INTERVENANT_ID = intervenantId
      and ATTR_NAME = attrNames(i)
      and ATTR_OLD_VALUE = to_char(attrOldVals(i))
      and ATTR_NEW_VALUE = to_char(attrNewVals(i));
    if found > 0 then
      continue;
    end if;

    insert into INDIC_MODIF_DOSSIER(
      id,
      INTERVENANT_ID,
      ATTR_NAME,
      ATTR_OLD_SOURCE_NAME,
      ATTR_OLD_VALUE,
      ATTR_NEW_SOURCE_NAME,
      ATTR_NEW_VALUE,
      EST_CREATION_DOSSIER, -- témoin indiquant s'il s'agit d'une création ou d'une modification de dossier
      HISTO_CREATION,       -- NB: date de modification du dossier
      HISTO_CREATEUR_ID,    -- NB: auteur de la modification du dossier
      HISTO_MODIFICATION,
      HISTO_MODIFICATEUR_ID
    )
    values (
      indic_modif_dossier_id_seq.nextval,
      intervenantId,
      attrNames(i),
      oldSourceName,
      to_char(attrOldVals(i)),
      newSourceName,
      to_char(attrNewVals(i)),
      estCreationDossier,
      :NEW.HISTO_MODIFICATION,
      :NEW.HISTO_MODIFICATEUR_ID,
      :NEW.HISTO_MODIFICATION,
      :NEW.HISTO_MODIFICATEUR_ID
    );
  end loop;

END;

/





insert into formule(id, libelle, package_name, procedure_name)
values (formule_id_seq.nextval, 'Université de Caen', 'FORMULE_UNICAEN', 'CALCUL_RESULTAT_V3');

insert into formule(id, libelle, package_name, procedure_name)
values (formule_id_seq.nextval, 'Université de Montpellier', 'FORMULE_MONTPELLIER', 'CALCUL_RESULTAT');


insert into formule_test_structure (id, libelle, universite) values (ftest_structure_id_seq.nextval, 'Droit', 0);
insert into formule_test_structure (id, libelle, universite) values (ftest_structure_id_seq.nextval, 'Histoire', 0);
insert into formule_test_structure (id, libelle, universite) values (ftest_structure_id_seq.nextval, 'IAE', 0);
insert into formule_test_structure (id, libelle, universite) values (ftest_structure_id_seq.nextval, 'IUT', 0);
insert into formule_test_structure (id, libelle, universite) values (ftest_structure_id_seq.nextval, 'Lettres', 0);
insert into formule_test_structure (id, libelle, universite) values (ftest_structure_id_seq.nextval, 'Santé', 0);
insert into formule_test_structure (id, libelle, universite) values (ftest_structure_id_seq.nextval, 'Sciences', 0);
insert into formule_test_structure (id, libelle, universite) values (ftest_structure_id_seq.nextval, 'SUAPS', 0);
insert into formule_test_structure (id, libelle, universite) values (ftest_structure_id_seq.nextval, 'Université', 1);

INSERT INTO parametre (
  id, nom,
  valeur, description,
  histo_creation, histo_createur_id,
  histo_modification, histo_modificateur_id
) VALUES (
  parametre_id_seq.nextval, 'formule',
  '1', 'Formule de calcul',
  sysdate, (select id from utilisateur where username='oseappli'),
  sysdate, (select id from utilisateur where username='oseappli')
);
DELETE FROM parametre WHERE nom IN ('formule_package_name', 'formule_function_name');




INSERT INTO CATEGORIE_PRIVILEGE (ID,CODE,LIBELLE) VALUES (
  CATEGORIE_PRIVILEGE_ID_SEQ.nextval,
  'domaines-fonctionnels',
  'Domaines fonctionnels'
);
INSERT INTO CATEGORIE_PRIVILEGE (ID,CODE,LIBELLE) VALUES (
  CATEGORIE_PRIVILEGE_ID_SEQ.nextval,
  'motifs-modification-service-du',
  'Motifs de modification de service dû'
);
INSERT INTO CATEGORIE_PRIVILEGE (ID,CODE,LIBELLE) VALUES (
  CATEGORIE_PRIVILEGE_ID_SEQ.nextval,
  'structures',
  'Structures'
);
INSERT INTO CATEGORIE_PRIVILEGE (ID,CODE,LIBELLE) VALUES (
  CATEGORIE_PRIVILEGE_ID_SEQ.nextval,
  'formule',
  'Formule de calcul'
);

INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE)
SELECT
       privilege_id_seq.nextval id,
       (SELECT id FROM CATEGORIE_PRIVILEGE WHERE code = t1.c ) CATEGORIE_ID,
       t1.p CODE,
       t1.l LIBELLE,
       (SELECT count(*) FROM PRIVILEGE WHERE categorie_id = (SELECT id FROM CATEGORIE_PRIVILEGE WHERE code = t1.c )) + rownum ORDRE
FROM (

   SELECT 'odf' c, 'grands-types-diplome-visualisation' p, 'Grands types de diplômes (visualisation)' l FROM dual
   UNION ALL SELECT 'odf' c, 'grands-types-diplome-edition' p, 'Grands types de diplômes (édition)' l FROM dual

   UNION ALL SELECT 'odf' c, 'types-diplome-visualisation' p, 'Types de diplômes (visualisation)' l FROM dual
   UNION ALL SELECT 'odf' c, 'types-diplome-edition' p, 'Types de diplômes (édition)' l FROM dual

   UNION ALL SELECT 'motifs-modification-service-du' c, 'visualisation' p, 'Administration (visualisation)' l FROM dual
   UNION ALL SELECT 'motifs-modification-service-du' c, 'edition' p, 'Administration (édition)' l FROM dual

   UNION ALL SELECT 'structures' c, 'administration-visualisation' p, 'Administration (visualisation)' l FROM dual
   UNION ALL SELECT 'structures' c, 'administration-edition' p, 'Administration (édition)' l FROM dual

   UNION ALL SELECT 'budget' c, 'types-ressources-visualisation' p, 'Types de ressources - Visualisation' l FROM dual
   UNION ALL SELECT 'budget' c, 'types-ressources-edition' p, 'Types de ressources - Édition' l FROM dual

   UNION ALL SELECT 'domaines-fonctionnels' c, 'administration-visualisation' p, 'Administration (visualisation)' l FROM dual
   UNION ALL SELECT 'domaines-fonctionnels' c, 'administration-edition' p,	'Administration (édition)' l FROM dual

   UNION ALL SELECT 'formule' c, 'tests' p, 'Tests' l FROM dual

) t1;



CREATE OR REPLACE FORCE VIEW "V_CONTRAT_MAIN" AS
   WITH hs AS (
       SELECT contrat_id, sum(heures) "serviceTotal" FROM V_CONTRAT_SERVICES GROUP BY contrat_id
   )
   SELECT
          ct.id contrat_id,
          ct."annee",
          ct."nom",
          ct."prenom",
          ct."civilite",
          ct."e",
          ct."dateNaissance",
          ct."adresse",
          ct."numInsee",
          ct."statut",
          ct."totalHETD",
          ct."tauxHoraireValeur",
          ct."tauxHoraireDate",
          ct."dateSignature",
          ct."modifieComplete",
          CASE WHEN ct.est_contrat=1 THEN 1 ELSE null END "contrat1",
          CASE WHEN ct.est_contrat=1 THEN null ELSE 1 END "avenant1",
          CASE WHEN ct.est_contrat=1 THEN '3' ELSE '2' END "n",
          to_char(SYSDATE, 'dd/mm/YYYY - hh24:mi:ss') "horodatage",
          'Exemplaire à conserver' "exemplaire1",
          'Exemplaire à retourner' || ct."exemplaire2" "exemplaire2",
          ct."serviceTotal",

          CASE ct.est_contrat
             WHEN 1 THEN -- contrat
              'Contrat de travail '
             ELSE
              'Avenant au contrat de travail initial modifiant le volume horaire initial'
                 || ' de recrutement en qualité '
              END                                         "titre",
          CASE WHEN ct.est_atv = 1 THEN
              'd''agent temporaire vacataire'
               ELSE
              'de chargé' || ct."e" || ' d''enseignement vacataire'
              END                                         "qualite",

          CASE
             WHEN ct.est_projet = 1 AND ct.est_contrat = 1 THEN 'Projet de contrat'
             WHEN ct.est_projet = 0 AND ct.est_contrat = 1 THEN 'Contrat n°' || ct.id
             WHEN ct.est_projet = 1 AND ct.est_contrat = 0 THEN 'Projet d''avenant'
             WHEN ct.est_projet = 0 AND ct.est_contrat = 0 THEN 'Avenant n°' || ct.contrat_id || '.' || ct.numero_avenant
              END                                         "titreCourt"
   FROM
        (
        SELECT
               c.*,
               a.libelle                                                                                     "annee",
               COALESCE(d.nom_usuel,i.nom_usuel)                                                             "nom",
               COALESCE(d.prenom,i.prenom)                                                                   "prenom",
               civ.libelle_court                                                                             "civilite",
               CASE WHEN civ.sexe = 'F' THEN 'e' ELSE '' END                                                 "e",
               to_char(COALESCE(d.date_naissance,i.date_naissance), 'dd/mm/YYYY')                            "dateNaissance",
               COALESCE(d.adresse,ose_divers.formatted_adresse(
                                     ai.NO_VOIE, ai.NOM_VOIE, ai.BATIMENT, ai.MENTION_COMPLEMENTAIRE, ai.LOCALITE,
                                     ai.CODE_POSTAL, ai.VILLE, ai.PAYS_LIBELLE))                                               "adresse",
               COALESCE(d.numero_insee,i.numero_insee || ' ' || COALESCE(LPAD(i.numero_insee_cle,2,'0'),'')) "numInsee",
               si.libelle                                                                                    "statut",
               replace(ltrim(to_char(COALESCE(fr.total,0), '999999.00')),'.',',')                            "totalHETD",
               replace(ltrim(to_char(COALESCE(th.valeur,0), '999999.00')),'.',',')                           "tauxHoraireValeur",
               COALESCE(to_char(th.histo_creation, 'dd/mm/YYYY'), 'TAUX INTROUVABLE')                        "tauxHoraireDate",
               to_char(COALESCE(v.histo_creation, c.histo_creation), 'dd/mm/YYYY')                           "dateSignature",
               CASE WHEN c.structure_id <> COALESCE(cp.structure_id,0) THEN 'modifié' ELSE 'complété' END    "modifieComplete",
               CASE WHEN s.aff_adresse_contrat = 1 THEN
                   ' signé à l''adresse suivante :' || CHR(13) || CHR(10) ||
                   s.libelle_court || ' - ' || REPLACE(ose_divers.formatted_adresse(
                                                          astr.NO_VOIE, astr.NOM_VOIE, null, null, astr.LOCALITE,
                                                          astr.CODE_POSTAL, astr.VILLE, null), CHR(13), ' - ')
                    ELSE '' END                                                                                   "exemplaire2",
               replace(ltrim(to_char(COALESCE(hs."serviceTotal",0), '999999.00')),'.',',')                   "serviceTotal",
               CASE WHEN c.contrat_id IS NULL THEN 1 ELSE 0 END                                              est_contrat,
               CASE WHEN v.id IS NULL THEN 1 ELSE 0 END                                                      est_projet,
               si.tem_atv                                                                                    est_atv

        FROM
             contrat               c
                JOIN type_contrat         tc ON tc.id = c.type_contrat_id
                JOIN intervenant           i ON i.id = c.intervenant_id
                JOIN annee                 a ON a.id = i.annee_id
                JOIN statut_intervenant   si ON si.id = i.statut_id
                JOIN structure             s ON s.id = c.structure_id
                LEFT JOIN adresse_structure  astr ON astr.structure_id = s.id AND astr.principale = 1 AND astr.histo_destruction IS NULL
                LEFT JOIN dossier               d ON d.intervenant_id = i.id AND d.histo_destruction IS NULL
                JOIN civilite            civ ON civ.id = COALESCE(d.civilite_id,i.civilite_id)
                LEFT JOIN validation            v ON v.id = c.validation_id AND v.histo_destruction IS NULL
                LEFT JOIN adresse_intervenant  ai ON ai.intervenant_id = i.id AND ai.histo_destruction IS NULL

                JOIN type_volume_horaire tvh ON tvh.code = 'PREVU'
                JOIN etat_volume_horaire evh ON evh.code = 'valide'
                LEFT JOIN formule_resultat     fr ON fr.intervenant_id = i.id AND fr.type_volume_horaire_id = tvh.id AND fr.etat_volume_horaire_id = evh.id
                LEFT JOIN taux_horaire_hetd    th ON c.histo_creation BETWEEN th.histo_creation AND COALESCE(th.histo_destruction,SYSDATE)
                LEFT JOIN                      hs ON hs.contrat_id = c.id
                LEFT JOIN contrat              cp ON cp.id = c.contrat_id
        WHERE
            c.histo_destruction IS NULL
        ) ct;
