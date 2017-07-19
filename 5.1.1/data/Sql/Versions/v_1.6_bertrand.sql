
Insert into INDICATEUR (ID,CODE,TYPE,ORDRE,ENABLED) values (
    indicateur_id_seq.nextval,
    'DonneesPersoModif',
    'Données personnelles',
    '1050',
    '0'
);


/*
select
  'update indicateur set id_interne = ' ||
  (100 * dense_rank() over (order by type) + 10 * row_number() over (partition by type order by ordre)) ||
  ' where code = ''' ||
  i.code ||
  '''; -- ' || i.type
from indicateur i
order by type, ordre;
*/
update indicateur set numero = 110  where code = 'PermAffectAutreIntervMeme'; -- Affectation
update indicateur set numero = 120  where code = 'PermAffectMemeIntervAutre'; -- Affectation
update indicateur set numero = 130  where code = 'BiatssAffectMemeIntervAutre'; -- Affectation
update indicateur set numero = 210  where code = 'AttenteAgrementCR'; -- Agrément
update indicateur set numero = 220  where code = 'AttenteAgrementCA'; -- Agrément
update indicateur set numero = 310  where code = 'AgrementCAMaisPasContrat'; -- Contrat / avenant
update indicateur set numero = 320  where code = 'AttenteContrat'; -- Contrat / avenant
update indicateur set numero = 330  where code = 'AttenteAvenant'; -- Contrat / avenant
update indicateur set numero = 340  where code = 'SaisieServiceApresContratAvenant'; -- Contrat / avenant
update indicateur set numero = 350  where code = 'ContratAvenantDeposes'; -- Contrat / avenant
update indicateur set numero = 360  where code = 'AttenteRetourContrat'; -- Contrat / avenant
update indicateur set numero = 410  where code = 'AttenteValidationDonneesPerso'; -- Données personnelles
update indicateur set numero = 420  where code = 'DonneesPersoDiffImport'; -- Données personnelles
update indicateur set numero = 430  where code = 'DonneesPersoModif'; -- Données personnelles
update indicateur set numero = 510  where code = 'EnsHisto'; -- Enseignements et référentiel
update indicateur set numero = 520  where code = 'PlafondHcPrevuHorsRemuFcDepasse'; -- Enseignements et référentiel
update indicateur set numero = 530  where code = 'PlafondHcRealiseHorsRemuFcDepasse'; -- Enseignements et référentiel
update indicateur set numero = 610  where code = 'AttenteValidationEnsPrevuPerm'; -- Enseignements et référentiel <em>Permanents</em>
update indicateur set numero = 620  where code = 'AttenteValidationRefPrevuPerm'; -- Enseignements et référentiel <em>Permanents</em>
update indicateur set numero = 630  where code = 'EnsRealisePermSaisieNonCloturee'; -- Enseignements et référentiel <em>Permanents</em>
update indicateur set numero = 640  where code = 'AttenteValidationEnsRealisePerm'; -- Enseignements et référentiel <em>Permanents</em>
update indicateur set numero = 650  where code = 'AttenteValidationEnsRealisePermAutreComp'; -- Enseignements et référentiel <em>Permanents</em>
update indicateur set numero = 660  where code = 'AttenteValidationRefRealisePerm'; -- Enseignements et référentiel <em>Permanents</em>
update indicateur set numero = 670  where code = 'AttenteValidationRefRealisePermAutreComp'; -- Enseignements et référentiel <em>Permanents</em>
update indicateur set numero = 680  where code = 'PlafondRefPrevuDepasse'; -- Enseignements et référentiel <em>Permanents</em>
update indicateur set numero = 690  where code = 'PlafondRefRealiseDepasse'; -- Enseignements et référentiel <em>Permanents</em>
update indicateur set numero = 710  where code = 'AttenteValidationEnsPrevuVac'; -- Enseignements et référentiel <em>Vacataires</em>
update indicateur set numero = 720  where code = 'AttenteValidationEnsRealiseVac'; -- Enseignements et référentiel <em>Vacataires</em>
update indicateur set numero = 810  where code = 'AttenteDemandeMepPerm'; -- Mise en paiement <em>Permanents</em>
update indicateur set numero = 820  where code = 'AttenteMepPerm'; -- Mise en paiement <em>Permanents</em>
update indicateur set numero = 910  where code = 'AttenteDemandeMepVac'; -- Mise en paiement <em>Vacataires</em>
update indicateur set numero = 920  where code = 'AttenteMepVac'; -- Mise en paiement <em>Vacataires</em>
update indicateur set numero = 1010 where code = 'AttentePieceJustif'; -- Pièces justificatives
update indicateur set numero = 1020 where code = 'AttenteValidationPieceJustif'; -- Pièces justificatives
