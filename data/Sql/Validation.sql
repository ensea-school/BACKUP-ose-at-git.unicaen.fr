/**
 * Création d'un nouveau type de validation.
 */

INSERT INTO TYPE_VALIDATION  (
    ID,
    CODE,
    LIBELLE,
    HISTO_CREATEUR_ID,
    HISTO_MODIFICATEUR_ID
  )
  VALUES  (
    type_validation_id_seq.nextval,
    'CLOTURE_REALISE',
    'Clôture de la saisie des enseignements réalisés',
    ose_parametre.get_ose_user(),
    ose_parametre.get_ose_user()
  );
  