DECLARE
  intervenant_id NUMERIC DEFAULT 11340;
BEGIN
  DBMS_OUTPUT.ENABLE(1000000); 
  ose_test.debug_enabled := true;

--ose_pj.update_intervenant(intervenant_id);
OSE_FORMULE.CALCULER(intervenant_id);
OSE_DOSSIER.calculer(intervenant_id);
OSE_SERVICE.calculer(intervenant_id);
OSE_SERVICE_REFERENTIEL.calculer(intervenant_id);
OSE_SERVICE_SAISIE.calculer(intervenant_id);
OSE_WORKFLOW.CALCULER(intervenant_id);
--OSE_WORKFLOW.CALCULER_TOUT(2014);
--OSE_PIECE_JOINTE_FOURNIE.calculer_tout;
END;

/

declare
  annee_id numeric default 2016;
begin
  ose_test.debug_enabled := false;
  --OSE_FORMULE.calculer_tout(annee_id);
  --OSE_DOSSIER.CALCULER_TOUT(annee_id);
  OSE_WORKFLOW.CALCULER_TOUT(annee_id);
end;

/



BEGIN

DELETE FROM TBL_AGREMENT;
DELETE FROM TBL_CLOTURE_REALISE;
DELETE FROM TBL_CONTRAT;
DELETE FROM TBL_DOSSIER;
DELETE FROM TBL_PAIEMENT;
DELETE FROM TBL_PIECE_JOINTE;
DELETE FROM TBL_PIECE_JOINTE_DEMANDE;
DELETE FROM TBL_PIECE_JOINTE_FOURNIE;
DELETE FROM TBL_SERVICE;
DELETE FROM TBL_SERVICE_REFERENTIEL;
DELETE FROM TBL_SERVICE_SAISIE;
DELETE FROM TBL_WORKFLOW;

END;

/

      SELECT COUNT(*) LIGNES, 'TBL_AGREMENT'             TBL FROM TBL_AGREMENT
UNION SELECT COUNT(*) LIGNES, 'TBL_CLOTURE_REALISE'      TBL FROM TBL_CLOTURE_REALISE
UNION SELECT COUNT(*) LIGNES, 'TBL_CONTRAT'              TBL FROM TBL_CONTRAT
UNION SELECT COUNT(*) LIGNES, 'TBL_DOSSIER'              TBL FROM TBL_DOSSIER
UNION SELECT COUNT(*) LIGNES, 'TBL_PAIEMENT'             TBL FROM TBL_PAIEMENT
UNION SELECT COUNT(*) LIGNES, 'TBL_PIECE_JOINTE'         TBL FROM TBL_PIECE_JOINTE
UNION SELECT COUNT(*) LIGNES, 'TBL_PIECE_JOINTE_DEMANDE' TBL FROM TBL_PIECE_JOINTE_DEMANDE
UNION SELECT COUNT(*) LIGNES, 'TBL_PIECE_JOINTE_FOURNIE' TBL FROM TBL_PIECE_JOINTE_FOURNIE
UNION SELECT COUNT(*) LIGNES, 'TBL_SERVICE'              TBL FROM TBL_SERVICE
UNION SELECT COUNT(*) LIGNES, 'TBL_SERVICE_REFERENTIEL'  TBL FROM TBL_SERVICE_REFERENTIEL
UNION SELECT COUNT(*) LIGNES, 'TBL_SERVICE_SAISIE'       TBL FROM TBL_SERVICE_SAISIE
UNION SELECT COUNT(*) LIGNES, 'TBL_WORKFLOW'             TBL FROM TBL_WORKFLOW
UNION SELECT COUNT(*) LIGNES, 'WF_DEP_BLOQUANTE'         TBL FROM WF_DEP_BLOQUANTE
;



select * from tbl_piece_jointe where intervenant_id = 548;



select 
  i.id i_id,
  e.code etape,
  s.libelle_court structure,
  w.atteignable,
  w.objectif,
  w.realisation
from 
  tbl_workflow w
  join intervenant i on i.id = w.intervenant_id
  join WF_ETAPE E on e.id = w.etape_id
  left join structure s on s.id = w.structure_id
where 
  intervenant_id =     5021
order by
  E.ORDRE;

select * from v_workflow_etape_pertinente where intervenant_id = 10347;